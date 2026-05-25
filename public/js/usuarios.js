requireAdmin();

async function cargarUsuarios() {
    const tbody = document.getElementById("tbodyUsuarios");

    try {
        const res = await authFetch("/usuarios", {
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
                            <a href="/usuarios/editar/${usuario.id}" class="btn btn-sm btn-primary">
                                Editar
                            </a>

                            <button class="btn btn-sm btn-danger" type="button" data-delete-user="${usuario.id}">
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
    if (!confirm("Seguro que deseas eliminar este usuario?")) return;

    authFetch(`/usuarios/${id}`, {
        method: "DELETE"
    })
        .then(res => {
            if (!res) return;

            if (!res.ok) throw new Error("Error al eliminar usuario");

            alert("Usuario eliminado");
            cargarUsuarios();
        })
        .catch(err => alert(err.message));
}

document.addEventListener("DOMContentLoaded", () => {
    const crearUsuario = document.getElementById("btnCrearUsuario");

    if (crearUsuario) {
        crearUsuario.addEventListener("click", () => {
            window.location.href = "/registro";
        });
    }

    document.addEventListener("click", event => {
        const button = event.target.closest("[data-delete-user]");

        if (button) {
            eliminarUsuario(button.dataset.deleteUser);
        }
    });

    cargarUsuarios();
});
