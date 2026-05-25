<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestion de Clases</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

<nav class="navbar header px-4">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/logo.png') }}" class="logo me-2">
        <h4 class="titulo m-0">Administracion de Clases</h4>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-main" type="button" id="btnCrearClase">
            + Crear Clase
        </button>

        <a href="/adminDashboard" class="btn btn-login-neon">Volver</a>
    </div>
</nav>

<div class="container mt-5">

    <div class="text-center mb-4">
        <h2 class="titulo">Lista de Clases</h2>
        <p class="subtitulo">Administra las clases del sistema</p>
    </div>

    <div class="mb-4">
        <input type="text" id="buscador" class="form-control" placeholder="Buscar clase...">
    </div>

    <div class="registro-card p-4">

        <table class="table table-dark table-hover text-center align-middle" id="tablaClases">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Dia</th>
                    <th>Horario</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="tbodyClases">
                <tr>
                    <td colspan="6">Cargando clases...</td>
                </tr>
            </tbody>
        </table>

    </div>

</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ asset('js/clases-admin.js') }}"></script>

</body>

</html>
