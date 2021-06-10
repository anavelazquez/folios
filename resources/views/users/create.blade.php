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
                    <form action="{{route('users.store')}}" method="post" class="form-horizontal">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Usuario</h4>
                                <p class="card-category">Ingresar datos</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="username"
                                            placeholder="Ingrese su nombre" requiered>

                                    </div>
                                </div>
                                <div class="row">
                                    <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Ingrese su contraseña" requiered>

                                    </div>
                                </div>
                                <div class="row">
                                    <label for="permissions" class="col-sm-2 col-form-label">permisos</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="permissions" placeholder=""
                                            requiered>

                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label" for="trabajador_id">Nombre del
                                        trabajador</label>
                                    <div class="col-sm-7" padding-left="-50px">
                                        <div class="form-group" padding-left="-50px">
                                            <select class="form-control" id="trabajador_id" name="trabajador_id">
                                                <option value="">Seleccione</option>
                                                @foreach($tipo as $tipos)
                                                <option value="{{$tipos->id_trabajador}}">{{ $tipos->nombre_trabajador}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <!-- <div class="invalid-feedback" id="invalid-feedback-TipoArchivo" style="display: none"></div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer ml-auto mr-auto" style="text-align:center">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                                <!--End footer-->
                            </div>
                            </div>
                    </form>
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