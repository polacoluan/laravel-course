<!doctype html>
<html class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config("app.name") }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-base-900 text-slate-50 h-full">
    {{ $slot }}
</body>

</html>