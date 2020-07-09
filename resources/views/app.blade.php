<!DOCTYPE html>
<html class="h-full bg-grey-lighter">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <script src="{{ asset('public/js/app.js') }}" defer></script>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    @routes
</head>
<body class="font-sans leading-none text-grey-darkest antialiased">

@inertia

</body>
</html>
