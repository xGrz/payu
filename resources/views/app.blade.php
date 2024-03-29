<!doctype html>
<html lang="en" class="bg-slate-900 text-slate-400 h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel-App-Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="flex flex-col min-h-full">

    @include('payu::navigation.container')

    <main class="container px-4 mx-auto mb-2 grow">
        @include('payu::status.status')
        @yield('content')
    </main>

    <footer class="px-4 py-6 mt-8 bg-slate-950 grow-0">
        <div class="container mx-auto">
            xGrz/payu plugin for Laravel
        </div>
    </footer>

</body>
</html>
