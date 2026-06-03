<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halaman Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('img/asset 7.png') }}">
    <link rel="stylesheet" href="{{ asset('css/styling.css') }}">
</head>

<body>

    <!-- Layout Content -->
    @yield('content')

    @include('layouts.sections.mobile-bottombar')

    @stack('scripts')

</body>

</html>
