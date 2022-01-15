<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? trans('app') }}</title>

    {{ load_assets() }}
</head>
<body class="antialiased">
    <div id="app">
        {{ $slot }}
    </div>
</body>
</html>
