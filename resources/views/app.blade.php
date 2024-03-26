<!doctype html>
<html lang="en" class="bg-slate-900 text-slate-400">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel-App-Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="mx-auto">

@include('payu::status.status')
@include('payu::navigation.container')

<main class="container px-4 mx-auto mb-2">
    @yield('content')
</main>
<footer class="px-2 py-4">
    xGrz/payu plugin for Laravel
</footer>
</body>
</html>
