<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div>
        @yield('content') {{-- Ovo zamjenjuje {{ $slot }} --}}
    </div>
</body>
</html>
