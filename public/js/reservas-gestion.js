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

        const disabled = estado === "CANCELADA" ? "disabled" : "";

        tbody.innerHTML += `
            <tr>
                <td>${r.nombreUsuario ?? r.usuario?.nombre ?? ""}</td>
                <td>${r.nombreClase ?? r.clase?.nombre ?? ""}</td>
                <td>${fechaFormateada}</td>
                <td class="${color}">${estado}</td>
                <td>
                    <button class="btn btn-sm btn-warning" type="button" data-cancel-reservation="${id}" ${disabled}>
                        Cancelar
                    </button>

                    <button class="btn btn-sm btn-danger" type="button" data-delete-reservation="${id}">
                        Eliminar
                    </button>
                </td>
            </tr>
        `;
    });
}

function eliminarReserva(id) {
    if (!confirm("Seguro que deseas eliminar esta reserva?")) return;

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
    if (!confirm("Seguro que deseas cancelar esta reserva?")) return;

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

async function filtrarReservas(estado, btn) {
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

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-filter-status]").forEach(button => {
        button.addEventListener("click", () => {
            filtrarReservas(button.dataset.filterStatus, button);
        });
    });

    document.addEventListener("click", event => {
        const deleteButton = event.target.closest("[data-delete-reservation]");
        const cancelButton = event.target.closest("[data-cancel-reservation]");

        if (deleteButton) {
            eliminarReserva(deleteButton.dataset.deleteReservation);
        }

        if (cancelButton) {
            cancelarReserva(cancelButton.dataset.cancelReservation);
        }
    });

    cargarReservas();
});
