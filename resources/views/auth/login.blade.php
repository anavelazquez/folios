<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
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

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="{{ asset('img/sistema.png')}}" class="img-logo-login"/>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-header" style="text-align: center">Inicio de sesión</div>
    <div class="card-body login-card-body">


      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group mb-3">
          <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Nombre de usuario" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
          <div class="input-group-append input-group-text">
              <span class="fas fa-envelope"></span>
          </div>
          @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="input-group mb-3">
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
          <div class="input-group-append input-group-text">
              <span class="fas fa-lock"></span>
          </div>
          @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>


        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <!--
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
              -->
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>

          </div>
          <!-- /.col -->
        </div>
        <div class="row">
          <div class="col-12">
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                   
                </a>
            @endif
          </div>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
