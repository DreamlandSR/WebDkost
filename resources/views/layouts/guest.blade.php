<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" />

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <svg class="w-20 h-20 fill-current text-gray-500" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.395 36.028L3.24 24.712 24.08 0l20.843 24.712-8.153 11.316L24.08 14.117z"/>
                    <path d="M14.744 40.503l9.336 7.497 9.175-7.497-9.175-12.776z"/>
                </svg>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
