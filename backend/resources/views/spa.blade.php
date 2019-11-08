<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{ mix('favicon.png') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset("css/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("css/font-awesome.min.css")}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset("css/style.css")}}"> --}}
    <title>CoffeeSign</title>
</head>
<body class="sidebar-lg-show">
    <div id="app">
        <app></app>
    </div>

    <script src="{{ mix('js/main.js') }}"></script>
</body>
</html>