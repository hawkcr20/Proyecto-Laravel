<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas</title>

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
            <h4 class="titulo m-0">Administración de Reservas</h4>
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
            <button class="btn btn-outline-light filtro-btn" onclick="filtrar('', this)">
                Todas
            </button>

            <button class="btn btn-outline-success filtro-btn" onclick="filtrar('ACTIVA', this)">
                Activas
            </button>

            <button class="btn btn-outline-warning filtro-btn" onclick="filtrar('CANCELADA', this)">
                Canceladas
            </button>

            <button class="btn btn-outline-info filtro-btn" onclick="filtrar('FINALIZADA', this)">
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

    <script>

        requireAdmin();

        async function cargarReservas() {

            const tbody = document.getElementById("tbodyReservas");

            try {

                const res = await authFetch("/reservas", {
                    method: "GET"
                });

                if (!res || !res.ok) {
                    throw new Error("No se pudieron cargar las reservas");
                }

                const respuesta = await res.json();

                const reservas = respuesta.data ?? respuesta;

                pintarReservas(reservas);

            } catch (error) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">${error.message}</td>
                    </tr>
                `;
            }
        }

        function pintarReservas(reservas) {

            const tbody = document.getElementById("tbodyReservas");

            tbody.innerHTML = "";

            if (reservas.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">No hay reservas</td>
                    </tr>
                `;

                return;
            }

            reservas.forEach(r => {

                const id = r.idReserva ?? r.id;

                const estado = r.estado ?? "";

                const fechaFormateada = r.fechaReserva
                    ? new Date(r.fechaReserva).toLocaleDateString("es-CR")
                    : "";

                const color =
                    estado === "ACTIVA"
                        ? "text-success"
                        : estado === "CANCELADA"
                        ? "text-warning"
                        : "text-info";

                const disabled =
                    estado === "CANCELADA"
                        ? "disabled"
                        : "";

                tbody.innerHTML += `
                    <tr>

                        <td>
                            ${r.nombreUsuario ?? r.usuario?.nombre ?? ""}
                        </td>

                        <td>
                            ${r.nombreClase ?? r.clase?.nombre ?? ""}
                        </td>

                        <td>
                            ${fechaFormateada}
                        </td>

                        <td class="${color}">
                            ${estado}
                        </td>

                        <td>

                            <button
                                class="btn btn-sm btn-warning"
                                onclick="cancelarReserva(${id})"
                                ${disabled}>
                                Cancelar
                            </button>

                            <button
                                class="btn btn-sm btn-danger"
                                onclick="eliminarReserva(${id})">
                                Eliminar
                            </button>

                        </td>

                    </tr>
                `;
            });
        }

        function eliminarReserva(id) {

            if (!confirm("¿Seguro que deseas eliminar esta reserva?")) {
                return;
            }

            authFetch(`/reservas/${id}`, {
                method: "DELETE"
            })

            .then(async res => {

                if (!res || !res.ok) {

                    const error = await res.text();

                    console.log(error);

                    throw new Error("Error al eliminar reserva");
                }

                alert("Reserva eliminada correctamente");

                cargarReservas();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

        function cancelarReserva(id) {

            if (!confirm("¿Seguro que deseas cancelar esta reserva?")) {
                return;
            }

            authFetch(`/reservas/cancelar/${id}`, {
                method: "PUT"
            })

            .then(async res => {

                if (!res || !res.ok) {

                    const error = await res.text();

                    console.log(error);

                    throw new Error("Error al cancelar reserva");
                }

                alert("Reserva cancelada correctamente");

                cargarReservas();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

        async function filtrar(estado, btn) {

            document.querySelectorAll(".filtro-btn")
                .forEach(b => b.classList.remove("filtro-activo"));

            if (btn) {
                btn.classList.add("filtro-activo");
            }

            if (estado === "") {

                cargarReservas();

                return;
            }

            try {

                const res = await authFetch(`/reservas/estado/${estado}`, {
                    method: "GET"
                });

                if (!res || !res.ok) {
                    throw new Error("Error al filtrar reservas");
                }

                const respuesta = await res.json();

                const reservas = respuesta.data ?? respuesta;

                pintarReservas(reservas);

            } catch (error) {

                console.error(error);

                alert(error.message);
            }
        }

        document.addEventListener("DOMContentLoaded", cargarReservas);

    </script>

</body>

</html>