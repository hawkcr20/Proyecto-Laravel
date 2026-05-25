<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reserva</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reservas.css') }}">
</head>

<body>

    <div class="container-fluid px-5">

        <header class="header d-flex justify-content-between align-items-center py-3">
            <img src="{{ asset('img/logo.png') }}" class="logo-header">
            <a href="/horarioClases" class="btn btn-login-neon">Volver</a>
        </header>

        <div class="card reserva-detalle p-4">

            <h2 class="text-center mb-4 titulo">Reserva tu espacio</h2>

            <div class="row">

                <div class="col-md-5">
                    <img src="{{ asset('img/reserva.png') }}" class="img-fluid rounded">
                </div>

                <div class="col-md-7">

                    <div id="mensajeCarga">
                        <p>Cargando clase...</p>
                    </div>

                    <div id="detalleClase" style="display:none;">

                        <h2 id="nombreClase"></h2>
                        <p id="descripcionClase"></p>
                        <p id="diaClase"></p>
                        <p id="horarioClase"></p>

                        <p id="estadoCapacidad">
                            <span id="textoCapacidad"></span>
                        </p>

                        <form id="formReserva">
                            <input type="hidden" id="claseId" value="{{ $idClase ?? '' }}">

                            <button type="submit" id="btnReservar" class="btn btn-main w-100 mt-3">
                                Reservar
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <footer class="footer mt-5 py-4 text-center">
        <div class="container">
            <img src="{{ asset('img/logo.png') }}" width="120" class="mb-2">
            <p>2026 VIKINGS</p>
        </div>
    </footer>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/reserva-show.js') }}"></script>
</body>

</html>
