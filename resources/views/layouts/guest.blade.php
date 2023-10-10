<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
             <body class="skin-blue fixed-layout">
    
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">...Q8votes</p>
    </div>
</div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

  <style>
        #header_contact_us
        {
            color: #fff;
            font-size: 20px;
            font-weight: 400;
            padding: 1rem;
            text-align: center;
        }
        #header_contact_us i
        {
            display: none;
        }
        #header_contact_us:hover > i
        {
            display: inline-block;
        }
        #header_contact_us:hover
        {
            color: #d0d0d0!important;
        }
        .logoq, .navbar-nav
        {
            display: inline;
        }
        @media screen and (max-width: 768px) {
            .container { display: flex; flex-flow: row; }
            .logoq {
                order: 3;
                background: #fff;
                width: 60%;
                max-width: 60%;
            }
            .logoq a { float: left; }
            .logoq img { height: 100%; }
            .navbar-nav { order: 1; width: 20%;padding: 0; text-align:center; }
            #header_contact_us { width: 40%; order: 2; }
        }
    </style>    <style>
    footer{
        display:none;
    }
    </style>
