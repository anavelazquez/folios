<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('home') }}" class="brand-link">
    <img src="./img/logo.png" alt="Directorio PJEV" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Edictos</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ auth()->user()->imagen_usuario }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        @if(Auth::check())
          <a href="#" class="d-block">{{ auth()->user()->username }}</a>
        @endif
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <!-- Administrador -->
      @if(Auth::user()->id_tipo_usuario == 1)
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('edictos') }}" class="nav-link {{ request()->is('edictos') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Edictos
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('edictosPublicados') }}" class="nav-link {{ request()->is('edictos-publicados') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list-ul"></i>
              <p>
                Edictos Publicados
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview {{ request()->is('juicios') || request()->is('prestaciones') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Catálogos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1.5em;">
              <li class="nav-item" style="width: 96%;">
                <a href="{{ route('juicios') }}" class="nav-link {{ request()->is('juicios') ? 'active' : ''}} ">
                  <i class="fas fa-balance-scale"></i>
                  <p>Juicios</p>
                </a>
              </li>
              <li class="nav-item" style="width: 96%;">
                <a href="{{ route('prestacionesDemandadas') }}" class="nav-link {{ request()->is('prestaciones') ? 'active' : '' }}">
                  <i class="fas fa-comment"></i>
                  <p>Prestaciones demandadas</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Cerrar Sesión
              </p>
            </a>
          </li>
        </ul>
      @endif
      <!-- Juzgado -->
      @if(Auth::user()->id_tipo_usuario == 2)
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('edictos') }}" class="nav-link {{ request()->is('edictos') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Edictos
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Cerrar Sesión
              </p>
            </a>
          </li>
        </ul>
      @endif
    </nav>
    <!-- /.sidebar-menu -->
  </div>

<!-- /.sidebar -->
</aside>