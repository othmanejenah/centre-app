<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue sur notre plateforme</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-md">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Bienvenue sur notre plateforme
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Connectez-vous ou inscrivez-vous pour commencer
            </p>
        </div>
        <div class="mt-8 space-y-6">
            <div>
                <a href="{{ route('login') }}" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Se connecter
                </a>
            </div>
            <div>
                <a href="{{ route('register') }}" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    S'inscrire
                </a>
            </div>
        </div>
    </div>
</body>
</html>