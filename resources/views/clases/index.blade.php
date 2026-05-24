<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

<nav class="navbar header px-4">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/logo.png') }}" class="logo me-2">
        <h4 class="titulo m-0">Administración de Clases</h4>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-main" onclick="window.location.href='/crearClase'">
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
                    <th>Descripción</th>
                    <th>Día</th>
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

<script>
    requireAdmin();

    let clasesOriginales = [];

    async function cargarClases() {
        const tbody = document.getElementById("tbodyClases");

        try {
            const res = await authFetch("/clases", {
                method: "GET"
            });

            if (!res || !res.ok) {
                throw new Error("No se pudieron cargar las clases");
            }

            const respuesta = await res.json();
            clasesOriginales = respuesta.data ?? respuesta;

            pintarClases(clasesOriginales);

        } catch (error) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">${error.message}</td>
                </tr>
            `;
        }
    }

    function pintarClases(clases) {
        const tbody = document.getElementById("tbodyClases");
        tbody.innerHTML = "";

        if (clases.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">No hay clases registradas</td>
                </tr>
            `;
            return;
        }

        clases.forEach(clase => {
            const id = clase.idClase ?? clase.id;
            const horario = clase.horario ? clase.horario.substring(0, 5) : "";

            tbody.innerHTML += `
                <tr>
                    <td>${clase.nombre ?? ""}</td>
                    <td>${clase.descripcion ?? ""}</td>
                    <td>${clase.diaSemana ?? ""}</td>
                    <td>${horario}</td>
                    <td>${clase.capacidad ?? ""}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/clases/editar/${id}" class="btn btn-sm btn-primary">
                                Editar
                            </a>

                            <button class="btn btn-sm btn-danger" onclick="eliminarClase(${id})">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function eliminarClase(id) {
        if (!confirm("¿Seguro que deseas eliminar esta clase?")) return;

        authFetch(`/clases/${id}`, {
            method: "DELETE"
        })
            .then(res => {
                if (!res || !res.ok) throw new Error("Error al eliminar");

                alert(" Clase eliminada");
                cargarClases();
            })
            .catch(err => alert(err.message));
    }

    document.getElementById("buscador").addEventListener("keyup", function () {
        const texto = this.value.trim().toLowerCase();

        if (texto === "") {
            pintarClases(clasesOriginales);
            return;
        }

        const filtradas = clasesOriginales.filter(clase =>
            (clase.nombre ?? "").toLowerCase().includes(texto) ||
            (clase.descripcion ?? "").toLowerCase().includes(texto) ||
            (clase.diaSemana ?? "").toLowerCase().includes(texto)
        );

        pintarClases(filtradas);
    });

    document.addEventListener("DOMContentLoaded", cargarClases);
</script>

</body>

</html>