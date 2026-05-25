const idUsuario = document.getElementById("idUsuario").value;
const usernameInput = document.getElementById("username");
const usernameError = document.getElementById("usernameError");

async function cargarUsuarioParaEditar() {
    if (!idUsuario) return;

    try {
        const res = await authFetch(`/usuarios/${idUsuario}`);

        if (!res.ok) {
            throw new Error("Error cargando usuario");
        }

        const data = await res.json();
        const u = data.data;

        document.querySelector("[name=nombre]").value = u.nombre ?? "";
        document.querySelector("[name=apellidoUno]").value = u.apellidoUno ?? "";
        document.querySelector("[name=apellidoDos]").value = u.apellidoDos ?? "";
        document.querySelector("[name=telefono]").value = u.telefono ?? "";
        document.querySelector("[name=email]").value = u.email ?? "";
        document.querySelector("[name=username]").value = u.userName ?? "";

    } catch (err) {
        console.error(err);
        alert("No se pudo cargar el usuario");
    }
}

async function validarUsername() {
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
            (u.userName ?? "").toLowerCase() === username.toLowerCase() &&
            u.id != idUsuario
        );

        usernameError.style.display = existe ? "block" : "none";
    } catch (e) {
        console.error(e);
    }
}

async function guardarRegistro(event) {
    event.preventDefault();

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

        const password = document.querySelector("[name=password]").value;

        if (password) {
            data.password = password;
        }
    } else {
        data.password = document.querySelector("[name=password]").value;
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
                error.toLowerCase().includes("usuario") ||
                error.toLowerCase().includes("username")
            ) {
                usernameError.style.display = "block";
                return;
            }

            throw new Error(error);
        }

        alert("Usuario guardado correctamente");

        window.location.href = idUsuario ? "/usuariosVista" : "/inicio";
    } catch (err) {
        console.error(err);
        alert(err.message);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarioParaEditar();
    usernameInput.addEventListener("blur", validarUsername);
    document.getElementById("registro").addEventListener("submit", guardarRegistro);
});
