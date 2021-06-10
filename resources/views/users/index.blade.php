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

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Usuarios</h4>
                                    <p class="card-category">Usuarios registrados</p>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success" role="success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Añadir
                                                usuario</a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="text-primary">
                                                <th>ID</th>
                                                <th>Nombre de usuario</th>
                                                <th>permisos</th>
                                                <th class="text-center">Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user -> ID}}</td>
                                                    <td>{{ $user -> username}}</td>
                                                    <td>{{ $user -> permissions}}</td>                                                    
                                                    <td class="td-actions text-center">
                                                    <a href="{{ route('users.show', $user->ID) }}" class="btn btn-outline-dark btn-sm"><i class='fas fa-eye'></i></a>
                                                    <a href="{{ route('users.edit', $user->ID) }}" class="btn btn-outline-dark btn-sm"><i class='right fas fa-edit'></i></a>
                                                    <form action="{{ route('users.destroy', $user->ID) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Seguro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-dark btn-sm" type="submit" rel="tooltip">
                                                <i class='right fas fa-trash-alt'></i>
                                </button>
                                                    </form>
                                                      
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    </div>
    <!--end card user-->

</body>

</html>