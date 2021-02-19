<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edictos | Poder Judicial del Estado de Veracruz</title>
  <!-- Styles -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap.min.css">
  <link href="{{ asset('css/public.css') }}" rel="stylesheet">
</head>
  <body>
    <div class="wrapper">
      <div class="header">
        <div class="topbar">
          <div class="container">
            <ul class="loginbar pull-right">
              <li>
                <a target="_blank" href="http://mail.pjeveracruz.gob.mx">
                  <i class="fa fa-envelope"></i>
                  <span class="hidden-xs">E-mail</span>
                </a>
              </li>
              <li class="topbar-devider"></li>
              <li>
                <a href="https://www.pjeveracruz.gob.mx/pjev/login">
                  <i class="fa fa-sign-in"></i>
                  <span class="hidden-xs"> Intranet </span>
                </a>
              </li>
              <li class="topbar-devider"></li>
              <li>
                <a target="_self" href="https://www.pjeveracruz.gob.mx/pjev/contact">
                  <i class="fa fa-phone-square"></i>
                  <span class="hidden-xs"> Contacto</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="navbar-publico navbar-default mega-menu" role="navigation" style="background-color: #fff !important;">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
              </button>

              <a class="navbar-brsand" href="https://www.pjeveracruz.gob.mx/">
                <img src="https://www.pjeveracruz.gob.mx/pjev/assets/img/logo1-default.png" height="75">
              </a>
            </div>

            <div class="collapse navbar-collapse navbar-responsive-collapse">
              <ul class="nav navbar-nav">
                <li class="active">
                  <a href="https://www.pjeveracruz.gob.mx/" class="" data-toggle=""> Inicio </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="contenidos">
      <br><br><br><br>
      <div class="breadcrumbs">
        <div class="container">
          <ul class="pull-right breadcrumb" style="background-color: transparent !important; margin-bottom: 0 !important;">
            <li><a href="/">Inicio</a></li>
            <li class="active">Edictos</li>
          </ul>
        </div>
      </div>

      <div class="container content">
        @yield('content')
      </div>
    </div>

    <footer class="site-footer site-footer-relative" id="footer">
      <div class="text-center">
        Poder Judicial del Estado de Veracruz de Ignacio de la Llave
        <br>
        Av. Lázaro Cárdenas No. 373, Colonia El Mirador. Xalapa, Veracruz. C.P. 91170
      </div>
    </footer>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js"></script>
  @yield('js')
  </body>
</html>