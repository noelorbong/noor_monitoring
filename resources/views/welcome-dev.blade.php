<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="/images/chose1s_fav.png" type="image/png">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&amp;subset=latin-ext"
		rel="stylesheet">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<!-- Scripts -->
	<script src="{{ mix('js/app.js') }}" defer></script>
	<!-- Fonts -->
	{{-- <link rel="dns-prefetch" href="https://fonts.gstatic.com"> --}}
	{{-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> --}}
	<!-- Styles -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
	<div id="app"></div>
	<!-- <script src="{{ asset('js/app.js') }}" defer></script>
	 <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
</body>

</html>