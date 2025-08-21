<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Authorization Success</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded shadow-lg flex flex-col items-center">
   
        <img src="http://localhost:8000/instagram.png" alt="Instagram Logo" class="w-24 mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mb-4" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7" />
    </svg>
        <h1 class="text-3xl font-semibold mb-2">Autorizado com Sucesso</h1>
        <p class="text-gray-700 text-center">Você conectou a sua conta do Instagram com a galeria da {{ $barbearia->nome }} com sucesso.</p>
        <a href="/{{ $barbearia->slug }}" wire:navigate class="mt-4 text-blue-500 hover:underline">Ir para a Barbearia</a>
    </div>
</body>

</html>
