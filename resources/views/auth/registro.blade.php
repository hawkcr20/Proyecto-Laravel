<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/formularioVikingNuevo.css') }}">
</head>

<body>
<br><br><br>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 registro-card">

        <div class="text-center mb-3">
            <h3 class="fw-bold titulo">Datos de usuario</h3>
        </div>

        <form id="registro">

            <input type="hidden" id="idUsuario" name="idUsuario" value="{{ $idUsuario ?? '' }}">

            <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre" required>

            <input type="text" name="apellidoUno" class="form-control mb-3" placeholder="Primer apellido" required>

            <input type="text" name="apellidoDos" class="form-control mb-3" placeholder="Segundo apellido" required>

            <input type="tel" name="telefono" class="form-control mb-3" placeholder="Teléfono" required>

            <input type="email" name="email" class="form-control mb-3" placeholder="Correo electrónico" required>

            <div class="text-center mb-3">
                <h3 class="fw-bold titulo">Registro de usuario</h3>
            </div>

            <input type="text" id="username" name="username" class="form-control mb-1" placeholder="Usuario" required>

            <div id="usernameError" class="text-danger mb-3" style="display:none;">
                El usuario ya existe
            </div>

            <input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>

            <button class="btn btn-main w-100">
                Crear usuario
            </button>

            <div class="text-center mb-4">
                <br>
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo">
            </div>

        </form>

    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
    const usernameInput = document.getElementById("username");
    const usernameError = document.getElementById("usernameError");
    const idUsuario = document.getElementById("idUsuario").value;

    usernameInput.addEventListener("blur", async () => {
        const username = usernameInput.value.trim();

        if (!username) return;

        try {
            const res = await authFetch(`/api/usuarios/buscar/${encodeURIComponent(username)}`, {
                method: "GET"
            });

            if (!res || !res.ok) return;

            const respuesta = await res.json();
            const data = respuesta.data ?? respuesta;

            const existe = data.some(u =>
                (u.userName ?? u.username ?? "").toLowerCase() === username.toLowerCase()
                && (u.idUsuario ?? u.id) != idUsuario
            );

            usernameError.style.display = existe ? "block" : "none";

        } catch (e) {
            console.error(e);
        }
    });

    document.getElementById("registro").addEventListener("submit", async function (e) {
        e.preventDefault();

        let rolId = typeof isAdmin === "function" && isAdmin() ? 2 : 1;

        const data = {
            userName: usernameInput.value,
            username: usernameInput.value,
            password: document.querySelector("[name=password]").value,
            nombre: document.querySelector("[name=nombre]").value,
            apellidoUno: document.querySelector("[name=apellidoUno]").value,
            apellidoDos: document.querySelector("[name=apellidoDos]").value,
            telefono: document.querySelector("[name=telefono]").value,
            email: document.querySelector("[name=email]").value,
            rol: { idRol: rolId }
        };

        let url = "/api/usuarios";
        let method = "POST";

        if (idUsuario) {
            url = `/api/usuarios/${idUsuario}`;
            method = "PUT";

            if (!data.password) {
                delete data.password;
            }
        }

        try {
            const res = await authFetch(url, {
                method: method,
                body: JSON.stringify(data)
            });

            if (!res) return;

            if (!res.ok) {
                const error = await res.text();

                if (error.toLowerCase().includes("usuario") ||
                    error.toLowerCase().includes("username")) {
                    usernameError.style.display = "block";
                    return;
                }

                throw new Error(error || "Error al guardar usuario");
            }

            alert(" Usuario guardado");

            window.location.href = typeof isAdmin === "function" && isAdmin()
                ? "/usuariosVista"
                : "/inicio";

        } catch (err) {
            console.error(err);
            alert(err.message);
        }
    });
</script>

</body>

</html>