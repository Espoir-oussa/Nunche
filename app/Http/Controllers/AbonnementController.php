<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Customer;
use FedaPay\FedaPayObject;
use App\Models\Paiement;
use App\Models\User;

class AbonnementController extends Controller
{
    public function index()
    {
        return view('abonnement.feedapay');
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly,contributor'
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['auth' => 'Veuillez vous connecter.']);
        }

        // Vérification des informations utilisateur
        if (empty($user->nom) || empty($user->prenom) || empty($user->email)) {
            return back()->withErrors(['payment' => 'Veuillez compléter votre profil (nom, prénom, email) avant de procéder au paiement.']);
        }

        $plans = [
            'monthly'     => ['amount' => 2000,  'name' => 'Abonnement Mensuel'],
            'yearly'      => ['amount' => 20000, 'name' => 'Abonnement Annuel'],
            'contributor' => ['amount' => 5000,  'name' => 'Abonnement Contributeur'],
        ];

        $plan = $plans[$request->plan];

        try {
            // Utilisation du service Guzzle pour FedaPay
            $apiService = new \App\Services\FedaPayApiService();

            Log::info('Traitement abonnement pour utilisateur', [
                'user_id' => $user->id,
                'email' => $user->email,
                'plan' => $plan['name'],
                'montant' => $plan['amount']
            ]);

            $uniqueEmail = $this->generateUniqueEmail($user->email, $user->id);
            Log::info('Création client avec email unique', [
                'email_original' => $user->email,
                'email_unique' => $uniqueEmail
            ]);


                        $transactionData = [
                            'description' => 'Test paiement',
                            'amount' => 100,
                            'currency' => ['iso' => 'XOF'],
                            'customer' => [
                                'firstname' => $this->sanitizeName($user->prenom ?: 'Client'),
                                'lastname' => $this->sanitizeName($user->nom ?: 'User'),
                                'email' => $uniqueEmail,
                                'address' => [
                                    'address1' => 'Non spécifié',
                                    'city' => 'Cotonou',
                                    'country' => 'BJ'
                                ]
                            ],
                            'callback_url' => route('abonnement.callback') .
                                '?plan=' . urlencode($request->plan) .
                                '&user_id=' . $user->id .
                                '&customer_email=' . urlencode($uniqueEmail),
                            'cancel_url' => route('abonnement.index')
                        ];
                        Log::info('Création transaction FedaPay - données envoyées', $transactionData);
                        $montants = [100, 200, 300];
                        $transactionResponse = null;
                        $transactionUrl = null;
                        foreach ($montants as $montant) {
                            $transactionData['amount'] = $montant;
                            try {
                                $transactionResponse = $apiService->createTransaction($transactionData);
                                Log::info('Réponse brute FedaPay', [
                                    'montant' => $montant,
                                    'response' => $transactionResponse
                                ]);
                                $transactionUrl = $transactionResponse['v1/transaction']['payment_url'] ?? null;
                                if ($transactionUrl) {
                                    // 4. Sauvegarder le paiement en attente dans notre base
                                    $paiement = \App\Models\Paiement::create([
                                        'user_id' => $user->id,
                                        'contenu_id' => null, // Paiement d'abonnement général
                                        'montant' => $plan['amount'], // Montant original
                                        'statut' => 'pending',
                                        'numero' => 'FDP-' . ($transactionResponse['token']['id'] ?? 'N/A'),
                                        'paiement_methode' => 'fedapay',
                                        'transaction_id' => $transactionResponse['token']['id'] ?? null,
                                        'metadata' => json_encode([
                                            'fedapay_transaction_id' => $transactionResponse['token']['id'] ?? null,
                                            'fedapay_customer_email' => $uniqueEmail,
                                            'test_transaction' => $transactionData['amount'] != $plan['amount'],
                                            'created_at' => now()->toDateTimeString()
                                        ])
                                    ]);

                                    Log::info('Paiement enregistré en base', [
                                        'paiement_id' => $paiement->id,
                                        'transaction_id' => $transactionResponse['token']['id'] ?? null,
                                        'real_amount' => $plan['amount'],
                                        'charged_amount' => $transactionResponse['token']['amount'] ?? null
                                    ]);

                                    // 5. Rediriger vers FedaPay
                                    Log::info('Redirection vers FedaPay', [
                                        'payment_url' => $transactionUrl,
                                        'transaction_id' => $transactionResponse['v1/transaction']['id'] ?? null
                                    ]);

                                    return redirect($transactionUrl);
                                }
                            } catch (\Exception $e) {
                                Log::warning('Tentative échouée avec montant', [
                                    'montant' => $montant,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }

        } catch (\FedaPay\Error\InvalidRequest $e) {
            // Capture l'erreur spécifique de FedaPay
            $errorBody = $e->getJsonBody();
            // Conversion en tableau pour éviter l'erreur d'accès
            $errorArray = json_decode(json_encode($errorBody), true);
            Log::error('Erreur FedaPay InvalidRequest - DÉTAILS', [
                'message' => $e->getMessage(),
                'error_body' => $errorArray,
                'http_status' => $e->getHttpStatus(),
                'request_params' => isset($transactionData) ? $transactionData : 'non défini'
            ]);

            // Message utilisateur
            $errorMessage = 'Erreur lors de la création de la transaction de paiement. ';

            if (isset($errorArray['errors']) && is_array($errorArray['errors'])) {
                foreach ($errorArray['errors'] as $error) {
                    if (is_array($error) && isset($error['message'])) {
                        $errorMessage .= $error['message'] . ' ';
                    } elseif (is_string($error)) {
                        $errorMessage .= $error . ' ';
                    }
                }
            }

            return back()->withErrors(['payment' => trim($errorMessage) ?: 'Veuillez réessayer ou contacter le support.']);

        } catch (\FedaPay\Error\ApiConnection $e) {
            Log::error('Erreur Connexion API FedaPay', [
                'error' => $e->getMessage(),
                'api_key_prefix' => substr($apiKey ?? '', 0, 10) . '...'
            ]);
            return back()->withErrors(['payment' => 'Service de paiement temporairement indisponible. Veuillez réessayer dans quelques minutes.']);

        } catch (\Exception $e) {
            Log::error('Erreur Générale AbonnementController', [
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'masqué',
                'user_id' => $user->id ?? 'N/A',
                'plan' => $request->plan
            ]);
            return back()->withErrors(['payment' => 'Une erreur inattendue est survenue. Code: ' . substr(md5(time()), 0, 6)]);
        }
    }

    public function callback(Request $request)
    {
        Log::info('FedaPay Callback reçu', $request->all());

        try {
            $transactionId = $request->input('id');
            $plan = $request->query('plan', 'monthly');
            $userId = $request->query('user_id');

            if (!$transactionId) {
                // FedaPay envoie parfois l'ID dans d'autres champs
                $transactionId = $request->input('transaction_id') ??
                               $request->input('reference') ??
                               $request->query('transaction_id');

                if (!$transactionId) {
                    Log::warning('Callback sans identifiant de transaction', $request->all());
                    return redirect()->route('abonnement.index')
                        ->withErrors(['payment' => 'Identifiant de transaction manquant.']);
                }
            }

            // Initialisation FedaPay
            $apiKey = config('services.fedapay.secret_key');
            $environment = config('services.fedapay.env', 'sandbox');

            FedaPay::setApiKey($apiKey);
            FedaPay::setEnvironment($environment);

            // Récupérer la transaction depuis FedaPay
            Log::info('Récupération transaction FedaPay', ['transaction_id' => $transactionId]);

            try {
                $transaction = Transaction::retrieve($transactionId);
            } catch (\Exception $e) {
                Log::warning('Transaction non trouvée directement, recherche par référence', [
                    'transaction_id' => $transactionId,
                    'error' => $e->getMessage()
                ]);

                // Essayez de trouver la transaction par d'autres moyens
                $paiement = Paiement::where('transaction_id', $transactionId)->first();
                if ($paiement) {
                    // Transaction déjà enregistrée
                    return $this->handleExistingPayment($paiement, $plan, $request);
                }

                throw new \Exception("Transaction non trouvée: " . $transactionId);
            }

            Log::info('Transaction récupérée', [
                'id' => $transaction->id,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'customer_id' => $transaction->customer->id ?? 'N/A'
            ]);

            // Trouver l'utilisateur
            $user = $this->findUserForTransaction($transactionId, $userId);

            if (!$user) {
                throw new \Exception("Utilisateur non trouvé pour la transaction: " . $transactionId);
            }

            // Vérifier le statut de la transaction
            if ($transaction->status !== "approved") {
                Log::warning('Transaction non approuvée', [
                    'transaction_id' => $transactionId,
                    'status' => $transaction->status,
                    'amount' => $transaction->amount
                ]);

                // Mettre à jour le paiement en échec
                Paiement::where('transaction_id', $transactionId)
                    ->update(['statut' => 'failed']);

                $statusMessage = $this->getStatusMessage($transaction->status);

                return redirect()->route('abonnement.index')
                    ->withErrors(['payment' => 'Paiement ' . $statusMessage]);
            }

            // Mettre à jour l'utilisateur
            $user->is_subscribed = true;
            $user->save();

            Log::info('Utilisateur abonné', [
                'user_id' => $user->id,
                'email' => $user->email,
                'plan' => $plan
            ]);

            // Mettre à jour le paiement
            $paiement = Paiement::where('transaction_id', $transactionId)->first();
            if ($paiement) {
                $paiement->update([
                    'statut' => 'completed',
                    'montant' => $transaction->amount,
                    'metadata' => json_encode([
                        'transaction_data' => $transaction->toArray(),
                        'plan' => $plan,
                        'callback_data' => $request->all(),
                        'completed_at' => now()->toDateTimeString()
                    ], JSON_PRETTY_PRINT)
                ]);

                Log::info('Paiement complété', [
                    'paiement_id' => $paiement->id,
                    'montant' => $transaction->amount
                ]);
            }

            // Connecter l'utilisateur s'il ne l'est pas déjà
            if (!Auth::check()) {
                Auth::login($user);
            }

            return redirect()->route('dashboard')
                ->with('success', '✅ Paiement réussi ! Votre abonnement est maintenant actif.');

        } catch (\Exception $e) {
            Log::error("Erreur Callback FedaPay", [
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'masqué',
                'request' => $request->all()
            ]);

            // Ne pas montrer l'erreur technique à l'utilisateur
            return redirect()->route('abonnement.index')
                ->withErrors(['payment' => 'Erreur lors du traitement. Vérifiez votre compte ou contactez le support.']);
        }
    }

    /**
     * Méthodes utilitaires
     */

    private function generateUniqueEmail($originalEmail, $userId)
    {
        // Version plus simple pour éviter les problèmes
        $timestamp = time();
        $random = substr(md5($timestamp . $userId), 0, 6);

        $parts = explode('@', $originalEmail);
        if (count($parts) === 2) {
            // Utiliser un format email+tag@domain.com (standard)
            return $parts[0] . '+' . $random . $userId . '@' . $parts[1];
        }

        // Fallback
        return 'user.' . $userId . '.' . $timestamp . '@nunche-app.com';
    }

    private function sanitizeName($name)
    {
        // Nettoyer le nom pour FedaPay
        $name = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $name);
        $name = trim($name);

        if (empty($name)) {
            return 'Client';
        }

        // Limiter la longueur
        return substr($name, 0, 50);
    }

    private function sanitizeDescription($description)
    {
        // Nettoyer la description
        $description = preg_replace('/[^\p{L}\p{N}\s\-\.]/u', '', $description);
        $description = trim($description);

        if (empty($description)) {
            return 'Abonnement';
        }

        // Limiter la longueur
        return substr($description, 0, 100);
    }

    private function findUserForTransaction($transactionId, $userIdFromUrl = null)
    {
        // 1. Utiliser l'ID de l'URL si fourni
        if ($userIdFromUrl) {
            $user = User::find($userIdFromUrl);
            if ($user) {
                return $user;
            }
        }

        // 2. Chercher via le paiement
        $paiement = Paiement::where('transaction_id', $transactionId)->first();
        if ($paiement) {
            $user = User::find($paiement->user_id);
            if ($user) {
                return $user;
            }
        }

        // 3. Utiliser l'utilisateur connecté
        if (Auth::check()) {
            return Auth::user();
        }

        return null;
    }

    private function handleExistingPayment($paiement, $plan, $request)
    {
        // Si le paiement existe déjà
        if ($paiement->statut === 'completed') {
            return redirect()->route('dashboard')
                ->with('info', '✅ Ce paiement a déjà été traité.');
        }

        if ($paiement->statut === 'failed') {
            return redirect()->route('abonnement.index')
                ->withErrors(['payment' => 'Ce paiement a échoué précédemment.']);
        }

        // Mettre à jour comme complété
        $paiement->update([
            'statut' => 'completed',
            'metadata' => json_encode(array_merge(
                json_decode($paiement->metadata ?? '[]', true),
                ['callback_processed_at' => now()->toDateTimeString()]
            ))
        ]);

        // Mettre à jour l'utilisateur
        $user = User::find($paiement->user_id);
        if ($user) {
            $user->is_subscribed = true;
            $user->save();
        }

        return redirect()->route('dashboard')
            ->with('success', '✅ Paiement confirmé !');
    }

    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'en attente',
            'canceled' => 'annulé',
            'declined' => 'refusé',
            'failed' => 'échoué',
            'abandoned' => 'abandonné'
        ];

        return $messages[$status] ?? $status;
    }

    /**
     * Méthode de test pour vérifier la connexion
     */
    public function testConnection()
    {
        try {
            $apiKey = config('services.fedapay.secret_key');
            $environment = config('services.fedapay.env', 'sandbox');

            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clé API non configurée',
                    'env_keys' => [
                        'FEDAPAY_SECRET_KEY' => env('FEDAPAY_SECRET_KEY'),
                        'FEDAPAY_PUBLIC_KEY' => env('FEDAPAY_PUBLIC_KEY'),
                        'FEDAPAY_ENV' => env('FEDAPAY_ENV')
                    ]
                ], 500);
            }

            FedaPay::setApiKey($apiKey);
            FedaPay::setEnvironment($environment);

            // Test 1: Liste des comptes
            $accounts = \FedaPay\Account::all(['per_page' => 1]);

            // Test 2: Création d'une transaction test
            $testCustomer = Customer::create([
                'firstname' => 'Test',
                'lastname' => 'Connection',
                'email' => 'test.connection.' . time() . '@example.com',
                'address' => [
                    'address1' => 'Test',
                    'city' => 'Cotonou',
                    'country' => 'BJ'
                ]
            ]);

            $testTransaction = Transaction::create([
                'description' => 'Test connection',
                'amount' => 100,
                'currency' => ['iso' => 'XOF'],
                'customer' => $testCustomer->id,
                'callback_url' => route('abonnement.callback') . '?plan=monthly&test=1'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Connexion FedaPay OK',
                'environment' => $environment,
                'api_key_prefix' => substr($apiKey, 0, 10) . '...',
                'test_customer_id' => $testCustomer->id,
                'test_transaction_id' => $testTransaction->id,
                'app_url' => config('app.url'),
                'callback_url_example' => route('abonnement.callback')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion',
                'error' => $e->getMessage(),
                'environment' => $environment ?? 'non défini',
                'api_key' => $apiKey ? substr($apiKey, 0, 10) . '...' : 'non défini'
            ], 500);
        }
    }
}
