requireAuth();

const claseId = document.getElementById("claseId").value;

async function cargarClaseReserva() {
    const mensajeCarga = document.getElementById("mensajeCarga");
    const detalleClase = document.getElementById("detalleClase");

    try {
        const res = await authFetch(`/clases/${claseId}`, {
            method: "GET"
        });

        if (!res || !res.ok) {
            mensajeCarga.innerHTML = "<p>No se encontro la clase</p>";
            return;
        }

        const respuesta = await res.json();
        const clase = respuesta.data ?? respuesta;

        document.getElementById("nombreClase").textContent = clase.nombre ?? "";
        document.getElementById("descripcionClase").textContent = clase.descripcion ?? "";
        document.getElementById("diaClase").textContent = clase.diaSemana ?? "";
        document.getElementById("horarioClase").textContent = clase.horario ?? "";

        actualizarCapacidad(clase.capacidad ?? 0);

        mensajeCarga.style.display = "none";
        detalleClase.style.display = "block";
    } catch (error) {
        mensajeCarga.innerHTML = "<p>Error al cargar la clase</p>";
    }
}

function actualizarCapacidad(capacidad) {
    const estadoCapacidad = document.getElementById("estadoCapacidad");
    const textoCapacidad = document.getElementById("textoCapacidad");
    const btnReservar = document.getElementById("btnReservar");

    if (capacidad <= 0) {
        estadoCapacidad.className = "estado lleno";
        textoCapacidad.textContent = "Clase llena";
        btnReservar.disabled = true;
        return;
    }

    estadoCapacidad.className = "estado disponible";
    textoCapacidad.textContent = capacidad + " cupos disponibles";
    btnReservar.disabled = false;
}

async function guardarReserva(event) {
    event.preventDefault();

    const user = getUser();

    if (!user || !user.id) {
        alert("Debes iniciar sesion");
        return;
    }

    const data = {
        idClase: parseInt(claseId)
    };

    try {
        const res = await authFetch("/reservas", {
            method: "POST",
            body: JSON.stringify(data)
        });

        if (!res) return;

        if (!res.ok) {
            const error = await res.json();
            throw new Error(error.message || "Error al reservar");
        }

        alert("Reserva realizada correctamente");
        window.location.href = "/horarioClases";
    } catch (err) {
        alert(err.message);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formReserva");

    cargarClaseReserva();

    if (form) {
        form.addEventListener("submit", guardarReserva);
    }
});
