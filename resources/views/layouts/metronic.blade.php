<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Metronic Layout')</title>
    <!-- Include Metronic CSS -->
    <link href="{{ asset('metronic_theme/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    <!-- Include Metronic JS -->
    <script src="{{ asset('metronic_theme/js/scripts.bundle.js') }}"></script>
</body>
</html>
