<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Error 404</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}">
        <link href="{{ asset('admin_assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">

    </head>
    <body class="error-page">
        <div class="main-wrapper">
			<div class="error-box">
				<h1>404</h1>
				<h3 class="h2 mb-3"> {{__('pages.Oops')}} <i class="ti-alert"></i></h3>
				<p class="h4 font-weight-normal">{{__('pages.oops-request')}}</p>
				<a href="{{route('dashboard')}}" class="btn btn-primary">{{__('pages.back to home')}}</a>
			</div>
        </div>
    </body>
</html>