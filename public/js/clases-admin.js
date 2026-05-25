requireAdmin();

let clasesOriginales = [];

async function cargarClasesAdmin() {
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

                        <button class="btn btn-sm btn-danger" type="button" data-delete-class="${id}">
                            Eliminar
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function eliminarClase(id) {
    if (!confirm("Seguro que deseas eliminar esta clase?")) return;

    authFetch(`/clases/${id}`, {
        method: "DELETE"
    })
        .then(res => {
            if (!res || !res.ok) throw new Error("Error al eliminar");

            alert("Clase eliminada");
            cargarClasesAdmin();
        })
        .catch(err => alert(err.message));
}

document.addEventListener("DOMContentLoaded", () => {
    const crearClase = document.getElementById("btnCrearClase");
    const buscador = document.getElementById("buscador");

    if (crearClase) {
        crearClase.addEventListener("click", () => {
            window.location.href = "/crearClase";
        });
    }

    if (buscador) {
        buscador.addEventListener("keyup", function () {
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
    }

    document.addEventListener("click", event => {
        const button = event.target.closest("[data-delete-class]");

        if (button) {
            eliminarClase(button.dataset.deleteClass);
        }
    });

    cargarClasesAdmin();
});
