<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement - Plateforme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
<div class="max-w-4xl mx-auto">

    <h1 class="text-4xl font-bold text-center mb-8">Choisissez votre abonnement</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Mensuel --}}
        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-xl font-bold">Mensuel</h2>
            <p class="text-gray-600">2 000 FCFA / mois</p>

            <form action="{{ route('abonnement.process') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="monthly">
                <button class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-lg">S’abonner</button>
            </form>
        </div>

        {{-- Annuel --}}
        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-xl font-bold">Annuel</h2>
            <p class="text-gray-600">20 000 FCFA / an</p>

            <form action="{{ route('abonnement.process') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="yearly">
                <button class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-lg">S’abonner</button>
            </form>
        </div>

        {{-- Contributeur --}}
        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-xl font-bold">Contributeur</h2>
            <p class="text-gray-600">5 000 FCFA / mois</p>

            <form action="{{ route('abonnement.process') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="contributor">
                <button class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-lg">S’abonner</button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
