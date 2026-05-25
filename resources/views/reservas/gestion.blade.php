<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestion de Reservas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">

    <style>
        .filtro-activo {
            background-color: white !important;
            color: black !important;
        }
    </style>
</head>

<body>

    <nav class="navbar header px-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" class="logo me-2">
            <h4 class="titulo m-0">Administracion de Reservas</h4>
        </div>

        <div class="d-flex gap-2">
            <a href="/adminDashboard" class="btn btn-login-neon">Volver</a>
        </div>
    </nav>

    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2 class="titulo">Lista de Reservas</h2>
            <p class="subtitulo">Visualiza, cancela o elimina reservas</p>
        </div>

        <div class="mb-4 d-flex gap-2 justify-content-center flex-wrap">
            <button class="btn btn-outline-light filtro-btn" type="button" data-filter-status="">
                Todas
            </button>

            <button class="btn btn-outline-success filtro-btn" type="button" data-filter-status="ACTIVA">
                Activas
            </button>

            <button class="btn btn-outline-warning filtro-btn" type="button" data-filter-status="CANCELADA">
                Canceladas
            </button>

            <button class="btn btn-outline-info filtro-btn" type="button" data-filter-status="FINALIZADA">
                Finalizadas
            </button>
        </div>

        <div class="registro-card p-4">

            <table class="table table-dark table-hover text-center align-middle">

                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Clase</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tbodyReservas">
                    <tr>
                        <td colspan="5">Cargando reservas...</td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/reservas-gestion.js') }}"></script>
</body>

</html>
