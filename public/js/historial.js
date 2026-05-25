requireAuth();

async function cargarHistorial() {
    const tabla = document.getElementById("tablaHistorial");

    try {
        const response = await authFetch("/reservas/mis-clases", {
            method: "GET"
        });

        if (!response || !response.ok) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="5">No se pudo cargar el historial</td>
                </tr>
            `;
            return;
        }

        const respuesta = await response.json();
        const data = respuesta.data ?? respuesta;

        tabla.innerHTML = "";

        if (data.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="5">No tienes clases reservadas</td>
                </tr>
            `;
            return;
        }

        let contenido = "";

        data.forEach(reserva => {
            const fechaFormateada = reserva.fechaReserva
                ? new Date(reserva.fechaReserva).toLocaleDateString("es-CR")
                : "";

            contenido += `
                <tr>
                    <td>${reserva.nombreClase ?? ""}</td>
                    <td>${reserva.capacidad ?? ""}</td>
                    <td>${fechaFormateada}</td>
                    <td>${reserva.horario ?? ""}</td>
                    <td>${reserva.estado ?? ""}</td>
                </tr>
            `;
        });

        tabla.innerHTML = contenido;
    } catch (error) {
        console.error("Error:", error);

        tabla.innerHTML = `
            <tr>
                <td colspan="5">Error al cargar historial</td>
            </tr>
        `;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    cargarHistorial();

    if (typeof getNombre === "function") {
        getNombre();
    }
});
