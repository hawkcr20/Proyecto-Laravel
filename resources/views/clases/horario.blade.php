<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Horario de Clases</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/horarioClases.css') }}">
</head>

<body>

<div class="container mt-5">

    <nav class="navbar header px-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" class="logo me-2">
            <h4 class="titulo m-0">Horarios de clases</h4>
        </div>

        <div class="d-flex">
            <a href="/inicio" class="btn btn-login-neon">Volver</a>
        </div>
    </nav>

    <div class="text-center mb-2 subtitulo-dia">
        Escoge un dia
    </div>

    <div class="dias text-center mb-4">
        <a href="/horarioClases?diaSemana=LUNES" class="dia" data-dia="LUNES">LUN</a>
        <a href="/horarioClases?diaSemana=MARTES" class="dia" data-dia="MARTES">MAR</a>
        <a href="/horarioClases?diaSemana=MIERCOLES" class="dia" data-dia="MIERCOLES">MIE</a>
        <a href="/horarioClases?diaSemana=JUEVES" class="dia" data-dia="JUEVES">JUE</a>
        <a href="/horarioClases?diaSemana=VIERNES" class="dia" data-dia="VIERNES">VIE</a>
        <a href="/horarioClases?diaSemana=SABADO" class="dia" data-dia="SABADO">SAB</a>
        <a href="/horarioClases?diaSemana=DOMINGO" class="dia" data-dia="DOMINGO">DOM</a>
    </div>

    <div class="lista-clases" id="listaClases">
        <p class="text-center">Cargando clases...</p>
    </div>

    <div class="text-center mt-4" id="mensajeVacio" style="display:none;">
        <p>No hay clases disponibles para este dia</p>
    </div>

</div>

<footer class="footer mt-5 py-4 text-center">
    <div class="container">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="120" class="mb-2">
        <p class="mb-1">2026 VIKINGS</p>
        <p class="mb-0 small">Todos los derechos reservados</p>
    </div>
</footer>

<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ asset('js/horario-clases.js') }}"></script>

</body>

</html>
