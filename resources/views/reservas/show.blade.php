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
                        <input type="hidden" name="claseId" id="claseId" value="{{ $idClase ?? '' }}">

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
        <p>© 2026 VIKINGS</p>
    </div>
</footer>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
    requireAuth();

    const claseId = document.getElementById("claseId").value;

    async function cargarClase() {
        const mensajeCarga = document.getElementById("mensajeCarga");
        const detalleClase = document.getElementById("detalleClase");

        try {
            const res = await authFetch(`/api/clases/${claseId}`, {
                method: "GET"
            });

            if (!res || !res.ok) {
                mensajeCarga.innerHTML = "<p>No se encontró la clase</p>";
                return;
            }

            const respuesta = await res.json();
            const clase = respuesta.data ?? respuesta;

            document.getElementById("nombreClase").textContent = clase.nombre ?? "";
            document.getElementById("descripcionClase").textContent = clase.descripcion ?? "";
            document.getElementById("diaClase").textContent = clase.diaSemana ?? "";
            document.getElementById("horarioClase").textContent = clase.horario ?? "";

            const capacidad = clase.capacidad ?? 0;
            const estadoCapacidad = document.getElementById("estadoCapacidad");
            const textoCapacidad = document.getElementById("textoCapacidad");
            const btnReservar = document.getElementById("btnReservar");

            if (capacidad == 0) {
                estadoCapacidad.className = "estado lleno";
                textoCapacidad.textContent = "Clase llena";
                btnReservar.disabled = true;
            } else {
                estadoCapacidad.className = "estado disponible";
                textoCapacidad.textContent = capacidad + " cupos disponibles";
                btnReservar.disabled = false;
            }

            mensajeCarga.style.display = "none";
            detalleClase.style.display = "block";

        } catch (error) {
            mensajeCarga.innerHTML = "<p>Error al cargar la clase</p>";
        }
    }

    document.getElementById("formReserva")?.addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            clase_id: parseInt(claseId),
            estado: "Activa"
        };

        authFetch("/api/reservas", {
            method: "POST",
            body: JSON.stringify(data)
        })
            .then(res => {
                if (!res) return;

                if (!res.ok) {
                    return res.json().then(error => {
                        throw new Error(error.message || "Error al reservar");
                    });
                }

                alert(" Reserva realizada");
                window.location.href = "/horarioClases";
            })
            .catch(err => {
                alert("❌ " + err.message);
            });
    });

    document.addEventListener("DOMContentLoaded", cargarClase);
</script>

</body>

</html>