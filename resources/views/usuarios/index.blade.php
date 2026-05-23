<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

<nav class="navbar header px-4">
    <div class="d-flex align-items-center">
        <img src="{{ asset('img/logo.png') }}" class="logo me-2">
        <h4 class="titulo m-0">Administración de Usuarios</h4>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-main" onclick="window.location.href='/registro'">
            + Crear Usuario
        </button>

        <a href="/adminDashboard" class="btn btn-login-neon">Volver</a>
    </div>
</nav>

<div class="container mt-5">

    <div class="text-center mb-4">
        <h2 class="titulo">Lista de Usuarios</h2>
        <p class="subtitulo">Administra los usuarios del sistema</p>
    </div>

    <div class="registro-card p-4">

        <table class="table table-dark table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="tbodyUsuarios">
                <tr>
                    <td colspan="6">Cargando usuarios...</td>
                </tr>
            </tbody>

        </table>

    </div>

</div>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
    requireAdmin();

    async function cargarUsuarios() {
        const tbody = document.getElementById("tbodyUsuarios");

        try {
            const res = await authFetch("/api/usuarios", {
                method: "GET"
            });

            if (!res || !res.ok) {
                throw new Error("No se pudieron cargar los usuarios");
            }

            const respuesta = await res.json();

            const usuarios = respuesta.data ?? respuesta;

            tbody.innerHTML = "";

            if (usuarios.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6">No hay usuarios registrados</td>
                    </tr>
                `;
                return;
            }

            usuarios.forEach(usuario => {
                tbody.innerHTML += `
                    <tr>
                        <td>${usuario.nombre ?? usuario.name ?? ""}</td>
                        <td>${(usuario.apellidoUno ?? "") + " " + (usuario.apellidoDos ?? "")}</td>
                        <td>${usuario.email ?? ""}</td>
                        <td>${usuario.telefono ?? ""}</td>
                        <td>${usuario.userName ?? usuario.username ?? usuario.name ?? ""}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="/usuarios/editar/${usuario.idUsuario ?? usuario.id}" class="btn btn-sm btn-primary">
                                    Editar
                                </a>

                                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${usuario.idUsuario ?? usuario.id})">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

        } catch (error) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">${error.message}</td>
                </tr>
            `;
        }
    }

    function eliminarUsuario(id) {
        if (!confirm("¿Seguro que deseas eliminar este usuario?")) return;

        authFetch(`/api/usuarios/${id}`, {
            method: "DELETE"
        })
            .then(res => {
                if (!res) return;

                if (!res.ok) throw new Error("Error al eliminar usuario");

                alert(" Usuario eliminado ");
                cargarUsuarios();
            })
            .catch(err => alert(err.message));
    }

    document.addEventListener("DOMContentLoaded", cargarUsuarios);
</script>

</body>

</html>