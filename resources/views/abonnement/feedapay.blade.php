<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement - Plateforme</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .card-popular {
            transform: scale(1.05);
            border: 2px solid #f97316;
            position: relative;
            overflow: hidden;
        }

        .card-popular::before {
            content: 'POPULAIRE';
            position: absolute;
            top: 20px;
            right: -30px;
            background: #f97316;
            color: white;
            padding: 4px 30px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .feature-list li {
            position: relative;
            padding-left: 28px;
        }

        .feature-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
        }

        .price-tag {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #fef3c7 0%, #ffedd5 50%, #fed7aa 100%);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen p-4 md:p-8">
    <!-- Navigation -->
    <div class="max-w-6xl mx-auto mb-10">
        <div class="flex justify-between items-center mb-12">
            <!-- Logo avec icône + texte comme les pages d'authentification -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl flex items-center justify-center shadow-md">
                    <svg
                        class="w-6 h-6 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-800 tracking-tight">Nunche</span>
            </div>
            <a href="{{ url('/') }}" class="text-orange-600 hover:text-orange-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Choisissez votre <span class="text-orange-600">abonnement</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Accédez à du contenu exclusif et soutenez la création locale.
                <span class="font-semibold text-orange-600">Annulez à tout moment.</span>
            </p>

            <!-- Badge de sécurité -->
            <div class="inline-flex items-center mt-6 bg-green-50 px-4 py-2 rounded-full">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                <span class="text-sm font-medium text-green-700">Paiement 100% sécurisé</span>
            </div>
        </div>

        <!-- Comparatif des offres -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Offre Mensuelle -->
            <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">Mensuel</h3>
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Parfait pour découvrir la plateforme</p>

                    <div class="mb-8">
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-bold price-tag">2 000</span>
                            <span class="text-gray-500 ml-2">FCFA</span>
                        </div>
                        <p class="text-gray-500 text-sm">par mois</p>
                    </div>
                </div>

                <div class="flex-grow mb-8">
                    <ul class="feature-list space-y-4">
                        <li class="text-gray-700">Accès à tout le contenu</li>
                        <li class="text-gray-700">Support prioritaire</li>
                        <li class="text-gray-700">Sans engagement</li>
                        <li class="text-gray-700">Annulation à tout moment</li>
                    </ul>
                </div>

                <form action="{{ route('abonnement.process') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                        <span>Commencer maintenant</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>
            </div>

            <!-- Offre Annuelle (POPULAIRE) -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 flex flex-col card-popular transition-all duration-300 hover:-translate-y-2">
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Annuel</h3>
                            <span class="inline-block mt-1 px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                                Économisez 17%
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-crown text-orange-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Le choix le plus économique</p>

                    <div class="mb-8">
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-bold price-tag">20 000</span>
                            <span class="text-gray-500 ml-2">FCFA</span>
                        </div>
                        <p class="text-gray-500 text-sm">
                            <span class="line-through text-gray-400">24 000 FCFA</span> • par an
                        </p>
                        <p class="text-green-600 text-sm font-semibold mt-2">
                            <i class="fas fa-bolt mr-1"></i> Économisez 4 000 FCFA
                        </p>
                    </div>
                </div>

                <div class="flex-grow mb-8">
                    <ul class="feature-list space-y-4">
                        <li class="text-gray-700">Tout l'accès mensuel</li>
                        <li class="text-gray-700 font-semibold">2 mois offerts</li>
                        <li class="text-gray-700">Contenus exclusifs</li>
                        <li class="text-gray-700">Badge "Membre Premium"</li>
                        <li class="text-gray-700">Invitations aux événements</li>
                    </ul>
                </div>

                <form action="{{ route('abonnement.process') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan" value="yearly">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold py-3 px-6 rounded-xl hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                        <span>Choisir cette offre</span>
                        <i class="fas fa-star ml-2"></i>
                    </button>
                </form>
            </div>

            <!-- Offre Contributeur -->
            <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">Contributeur</h3>
                        <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-hands-helping text-purple-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Pour ceux qui veulent soutenir la création</p>

                    <div class="mb-8">
                        <div class="flex items-baseline mb-2">
                            <span class="text-4xl font-bold price-tag">5 000</span>
                            <span class="text-gray-500 ml-2">FCFA</span>
                        </div>
                        <p class="text-gray-500 text-sm">par mois</p>
                    </div>
                </div>

                <div class="flex-grow mb-8">
                    <ul class="feature-list space-y-4">
                        <li class="text-gray-700">Tout l'accès annuel</li>
                        <li class="text-gray-700 font-semibold">Crédit mention contributeur</li>
                        <li class="text-gray-700">Accès aux statistiques</li>
                        <li class="text-gray-700">Participation aux décisions</li>
                        <li class="text-gray-700">Badge exclusif "Contributeur"</li>
                    </ul>
                </div>

                <form action="{{ route('abonnement.process') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan" value="contributor">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                        <span>Devenir contributeur</span>
                        <i class="fas fa-heart ml-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl shadow-lg p-8 mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Questions fréquentes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-sync-alt text-orange-500 mr-3"></i>
                        Puis-je changer d'abonnement ?
                    </h4>
                    <p class="text-gray-600 text-sm">Oui, vous pouvez changer d'offre à tout moment. La transition se fera au début de votre prochaine période de facturation.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-ban text-orange-500 mr-3"></i>
                        Comment annuler ?
                    </h4>
                    <p class="text-gray-600 text-sm">Annulez quand vous voulez depuis votre espace personnel. Votre accès sera maintenu jusqu'à la fin de la période payée.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-credit-card text-orange-500 mr-3"></i>
                        Moyens de paiement
                    </h4>
                    <p class="text-gray-600 text-sm">Nous acceptons les cartes Visa, Mastercard, Mobile Money et les paiements via FedaPay.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-headset text-orange-500 mr-3"></i>
                        Support disponible
                    </h4>
                    <p class="text-gray-600 text-sm">Notre équipe est disponible 7j/7 pour répondre à vos questions par email ou chat.</p>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="text-center text-gray-500 text-sm">
            <p class="mb-4">
                <i class="fas fa-lock mr-2"></i>
                Vos données sont protégées et cryptées. Nous ne stockons pas vos informations bancaires.
            </p>
            <p>
                En vous abonnant, vous acceptez nos
                <a href="#" class="text-orange-600 hover:underline">Conditions d'utilisation</a>
                et notre
                <a href="#" class="text-orange-600 hover:underline">Politique de confidentialité</a>.
            </p>
        </div>
    </div>

    <!-- Script pour l'interactivité -->
    <script>
        // Animation au survol des boutons
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Afficher un message lors du clic sur un bouton
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement en cours...';
                button.disabled = true;

                // Réinitialiser après 3 secondes (au cas où le formulaire échoue)
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        });
    </script>
</body>
</html>
