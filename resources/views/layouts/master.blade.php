<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Folios</title>
  <!-- Fonts -->
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Header -->
    @include('layouts.header')

    <!-- Content -->
    @yield('content')

  </div>

  <!-- Footer -->
    @include('layouts.footer')
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  @include('sweet::alert')
  @yield('js')
</body>
</html>
