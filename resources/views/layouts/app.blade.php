<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-secondary">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow border-b border-primary/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @if(is_string($header))
                            {{ $header }}
                        @else
                            {!! $header !!}
                        @endif
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @if(isset($slot))
                    @if(is_string($slot))
                        {{ $slot }}
                    @else
                        {!! $slot !!}
                    @endif
                @endif
            </main>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="fixed bottom-4 end-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                @if(is_string(session('success')))
                    {{ session('success') }}
                @else
                    {!! session('success') !!}
                @endif
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="fixed bottom-4 end-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                @if(is_string(session('error')))
                    {{ session('error') }}
                @else
                    {!! session('error') !!}
                @endif
            </div>
        @endif
    </body>
</html>
