<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-dark">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <a class="navbar-brand d-none d-md-block" href="#">
      <img src="./img/logo.png" alt="" class="brand-image ew-brand-image">
    </a>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('oficios') }}" class="nav-link">Oficios</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('circulares') }}" class="nav-link">Circulares</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('tarjetas') }}" class="nav-link">Tarjetas</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('memorandums') }}" class="nav-link">Memorándums</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown text-body">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fa fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-item p-3"><i class="fa fa-user mr-2"></i>admin</div>
        
        
        <div class="dropdown-divider"></div>
        <div class="dropdown-footer p-2 text-right">
          <a class="btn btn-default" href="{{ route('logout') }}">Logout</a>
        </div>
        
      </div>
    </li>
  </ul>


  <!-- SEARCH FORM -->
  <!--
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Buscar">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
  -->
</nav>
  <!-- /.navbar -->