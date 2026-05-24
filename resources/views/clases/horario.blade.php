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
        Escoge un día
    </div>

    <div class="dias text-center mb-4">
        <a href="/horarioClases?diaSemana=LUNES" class="dia" data-dia="LUNES">LUN</a>
        <a href="/horarioClases?diaSemana=MARTES" class="dia" data-dia="MARTES">MAR</a>
        <a href="/horarioClases?diaSemana=MIERCOLES" class="dia" data-dia="MIERCOLES">MIÉ</a>
        <a href="/horarioClases?diaSemana=JUEVES" class="dia" data-dia="JUEVES">JUE</a>
        <a href="/horarioClases?diaSemana=VIERNES" class="dia" data-dia="VIERNES">VIE</a>
        <a href="/horarioClases?diaSemana=SABADO" class="dia" data-dia="SABADO">SÁB</a>
        <a href="/horarioClases?diaSemana=DOMINGO" class="dia" data-dia="DOMINGO">DOM</a>
    </div>

    <div class="lista-clases" id="listaClases">
        <p class="text-center">Cargando clases...</p>
    </div>

    <div class="text-center mt-4" id="mensajeVacio" style="display:none;">
        <p>No hay clases disponibles para este día</p>
    </div>

</div>

<footer class="footer mt-5 py-4 text-center">
    <div class="container">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="120" class="mb-2">
        <p class="mb-1">© 2026 VIKINGS</p>
        <p class="mb-0 small">Todos los derechos reservados</p>
    </div>
</footer>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
    requireAuth();

    const params = new URLSearchParams(window.location.search);
    const diaActual = params.get("diaSemana") || "LUNES";

    document.querySelectorAll(".dia").forEach(link => {
        if (link.dataset.dia === diaActual) {
            link.classList.add("activo");
        }
    });

    async function cargarClases() {
        const lista = document.getElementById("listaClases");
        const mensajeVacio = document.getElementById("mensajeVacio");

        try {
            const res = await authFetch(`/clases?diaSemana=${diaActual}`, {
                method: "GET"
            });

            if (!res || !res.ok) {
                throw new Error("No se pudieron cargar las clases");
            }

            const respuesta = await res.json();
            const clases = respuesta.data ?? respuesta;

            lista.innerHTML = "";

            if (clases.length === 0) {
                mensajeVacio.style.display = "block";
                return;
            }

            clases.forEach(clase => {
                const id = clase.idClase ?? clase.id;
                const horario = clase.horario ?? "";
                const nombre = clase.nombre ?? "";

                lista.innerHTML += `
                    <div class="clase-item">
                        <div class="hora">${horario}</div>

                        <div class="info">
                            <div class="nombre">${nombre}</div>
                        </div>

                        <a href="/reservas/${id}" class="btn-mas">
                            Más
                        </a>
                    </div>
                `;
            });

        } catch (error) {
            lista.innerHTML = `<p class="text-center">${error.message}</p>`;
        }
    }

    document.addEventListener("DOMContentLoaded", cargarClases);
</script>

</body>

</html>