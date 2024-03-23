<!doctype html>
<html lang="en" class="bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel-App-Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body style="max-width: 1100px; margin: 0 auto;">
<header class="container px-1 mx-auto">
    @include('payu::status.status')
    <h1 class="text-3xl pt-4">{{ $title ?? 'Page title' }}</h1>
    <div class="mb-4">
        @yield('breadcrumbs')
    </div>
</header>
<main class="container px-1 mx-auto">
    @yield('content')
</main>
</body>
</html>
