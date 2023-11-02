@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div>
                        @if (auth()->user()->position === 'Administrador')
                            <button id="modalCrear" class="btn btn-primary" data-toggle="modal">Crear Nuevo Usuario</button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Fecha Creaci&oacute;n</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->position }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        @if (auth()->user()->position === 'Administrador' || auth()->user()->id === $user->id)
                                            <a href="{{ url('editar-usuario/'.$user->id) }}">
                                                <button class="btn btn-success">Editar</button>
                                            </a>
                                            <a href="{{ url('eliminar-usuario/'.$user->id) }}" class="delete-user" data-id="{{ $user->id }}">
                                                <button class="btn btn-danger">Eliminar</button>
                                            </a>
                                        @endif
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

<div class="modal fade" id="crearUsuario">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">

                @csrf
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <h2>Tipo de Usuario</h2>
                            <select class="form-control input-lg" name="cargo" required="">
                                <option value="">Seleccionar...</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Vendedor">Vendedor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <h2>Nombre:</h2>
                            <input type="text" class="form-control input-lg" name="name" required="" value="{{old('name')}}">
                        </div>

                        <div class="form-group">
                            <h2>Email:</h2>
                            <input type="email" class="form-control input-lg" name="email" required="">
                        </div>

                        @error('email')
                            <br>
                            <div class="alert alert-danger">El Email ya existe</div>
                        @enderror

                        <div class="form-group">
                            <h2>Contraseña:</h2>
                            <input type="text" class="form-control input-lg" name="password" required="">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Crear</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($usuario))
    <div class="modal fade" id="editarUsuario">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ url('actualizar-usuario/'.$usuario->id) }}">

                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <h2>Tipo de Usuario</h2>
                                <select class="form-control input-lg" id="cargo" name="cargo" required="">
                                    <option value="{{ $usuario->position }}">{{ $usuario->position }}</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Vendedor">Vendedor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h2>Nombre:</h2>
                                <input type="text" class="form-control input-lg" name="name" required="" value="{{ $usuario->name }}">
                            </div>

                            <div class="form-group">
                                <h2>Email:</h2>
                                <input type="email" class="form-control input-lg" name="email" required="" value="{{ $usuario->email }}">
                            </div>

                            @error('email')
                                <br>
                                <div class="alert alert-danger">El Email ya existe</div>
                            @enderror

                            <div class="form-group">
                                <h2>Contraseña:</h2>
                                <input type="text" class="form-control input-lg" name="password">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Editar</button>
                        <button type="button" id="cancelarBtn" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
<script>
    $(document).ready(function(){

        $('#editarUsuario').on('hidden.bs.modal', function (e) {
            window.location.href = '/usuarios';
        });

        $("#modalCrear").click(function() {
            $("#crearUsuario").modal("show");
        });

        $('#editarUsuario').modal('toggle');

        $('#cancelarBtn').click(function() {
            $('#editarUsuario').modal('hide');
        });

        // -- SweetAlert2 --

        $('.delete-user').on('click', function(event) {
            event.preventDefault();

            const userId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/eliminar-usuario/' + userId;
                }
            });
        });
    })

    @if(session('UsuarioCreado') == 'OK')
        Swal.fire(
            'El Usuario ha sido creado',
            '',
            'success'
            )
    @endif

    @if(session('UsuarioEliminado') == 'OK')
        Swal.fire(
            'El Usuario ha sido eliminado',
            '',
            'success'
            )
    @endif

    // -- FIN SweetAlert2 --

</script>
@endsection
