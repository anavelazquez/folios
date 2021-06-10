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
  <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Header -->
    @include('layouts.header')

    <!-- Content -->
    @yield('content')

  </div>
  
<body>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
          <div class="card-title">Usuarios</div>
            <p class="card-category">Vista detallada del usuario <strong>{{$user->username}}</strong> </p>
          </div>
            <!--body-->
            <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="card card-user">
                  <div class="card-body">
                    <p class="card-text">
                      <div class="author">
                        <a href="#">
                          <img src="{{ asset('/img/avatar.jpg')}}" alt="image" width="40px" height="40px" class="avatar">
                          <h5 class="title mt-3"><strong>{{$user->username}}</strong></h5>
                        </a>
                        <p class="description">
                        ID:<strong> {{$user->ID}}</strong> <br>
                        Usuario:<strong> {{$user->username}}</strong> <br>
                       Contraseña: <strong> {{$user->password}}</strong> <br>
                       Permisos: <strong> {{$user->permissions}}</strong> <br>
                        ID de trabajador:<strong> {{$user->trabajador_id}}</strong> <br>
                        </p>
                      </div>
                    </p>
                  </div>
                  <div class="card-footer">
                    <div class="button-container">
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Volver</a>
                    </div>
                  </div>
                </div>
                <br><br><br><br><br><br>
                    <!-- Footer -->
    @include('layouts.footer')
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  @include('sweet::alert')
  @yield('js')  
              </div><!--end card user-->
             
 
        
</body>
</html>