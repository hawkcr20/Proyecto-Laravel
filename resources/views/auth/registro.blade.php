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

                <input type="hidden" id="idUsuario" value="{{ $idUsuario ?? '' }}">

                <input
                    type="text"
                    name="nombre"
                    class="form-control mb-3"
                    placeholder="Nombre"
                    required>

                <input
                    type="text"
                    name="apellidoUno"
                    class="form-control mb-3"
                    placeholder="Primer apellido"
                    required>

                <input
                    type="text"
                    name="apellidoDos"
                    class="form-control mb-3"
                    placeholder="Segundo apellido"
                    required>

                <input
                    type="tel"
                    name="telefono"
                    class="form-control mb-3"
                    placeholder="Teléfono"
                    required>

                <input
                    type="email"
                    name="email"
                    class="form-control mb-3"
                    placeholder="Correo electrónico"
                    required>

                <div class="text-center mb-3">
                    <h3 class="fw-bold titulo">Registro de usuario</h3>
                </div>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control mb-1"
                    placeholder="Usuario"
                    required>

                <div
                    id="usernameError"
                    class="text-danger mb-3"
                    style="display:none;">
                    El usuario ya existe
                </div>

                <input
                    type="password"
                    name="password"
                    class="form-control mb-3"
                    placeholder="Contraseña">

                <button class="btn btn-main w-100">
                    Guardar usuario
                </button>

                <div class="text-center mb-4">
                    <br>
                    <img
                        src="{{ asset('img/logo.png') }}"
                        alt="logo"
                        class="logo">
                </div>

            </form>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        const idUsuario = document.getElementById("idUsuario").value;

        const usernameInput = document.getElementById("username");

        const usernameError = document.getElementById("usernameError");



        document.addEventListener("DOMContentLoaded", async () => {

            if (!idUsuario) return;

            try {

                const res = await authFetch(`/usuarios/${idUsuario}`);

                if (!res.ok) {
                    throw new Error("Error cargando usuario");
                }

                const data = await res.json();

                const u = data.data;

                document.querySelector("[name=nombre]").value =
                    u.nombre ?? "";

                document.querySelector("[name=apellidoUno]").value =
                    u.apellidoUno ?? "";

                document.querySelector("[name=apellidoDos]").value =
                    u.apellidoDos ?? "";

                document.querySelector("[name=telefono]").value =
                    u.telefono ?? "";

                document.querySelector("[name=email]").value =
                    u.email ?? "";

                document.querySelector("[name=username]").value =
                    u.userName ?? "";

            } catch (err) {

                console.error(err);

                alert("No se pudo cargar el usuario");
            }
        });



        usernameInput.addEventListener("blur", async () => {

            const username = usernameInput.value.trim();

            if (!username) return;

            try {

                const res = await fetch(
                    `${API_URL}/usuarios/buscar/${encodeURIComponent(username)}`
                );

                if (!res.ok) return;

                const data = await res.json();

                const usuarios = data.data ?? data;

                const existe = usuarios.some(u =>

                    (u.userName ?? "").toLowerCase() ===
                    username.toLowerCase()

                    &&

                    u.id != idUsuario
                );

                usernameError.style.display =
                    existe ? "block" : "none";

            } catch (e) {

                console.error(e);
            }
        });



        document.getElementById("registro")
            .addEventListener("submit", async function(e) {

                e.preventDefault();

                usernameError.style.display = "none";

                const data = {

                    userName: usernameInput.value,

                    nombre: document.querySelector("[name=nombre]").value,

                    apellidoUno: document.querySelector("[name=apellidoUno]").value,

                    apellidoDos: document.querySelector("[name=apellidoDos]").value,

                    telefono: document.querySelector("[name=telefono]").value,

                    email: document.querySelector("[name=email]").value
                };


                let url = "/usuarios";

                let method = "POST";


                if (idUsuario) {

                    url = `/usuarios/${idUsuario}`;

                    method = "PUT";

                    const password =
                        document.querySelector("[name=password]").value;

                    if (password) {

                        data.password = password;
                    }

                } else {

                    data.password =
                        document.querySelector("[name=password]").value;
                }


                try {

                    let res;



                    if (idUsuario) {

                        res = await authFetch(url, {

                            method,

                            body: JSON.stringify(data)
                        });

                    } else {

                        res = await fetch(`${API_URL}${url}`, {

                            method,

                            headers: {

                                "Content-Type": "application/json",

                                "Accept": "application/json"
                            },

                            body: JSON.stringify(data)
                        });
                    }


                    if (!res.ok) {

                        const error = await res.text();

                        console.log(error);

                        if (

                            error.toLowerCase().includes("usuario")

                            ||

                            error.toLowerCase().includes("username")
                        ) {

                            usernameError.style.display = "block";

                            return;
                        }

                        throw new Error(error);
                    }


                    alert("Usuario guardado correctamente");

                    window.location.href = "/usuariosVista";

                } catch (err) {

                    console.error(err);

                    alert(err.message);
                }
            });
    </script>

</body>

</html>