requireAuth();

const horarioParams = new URLSearchParams(window.location.search);
const diaActual = horarioParams.get("diaSemana") || "LUNES";
const diasApi = {
    LUNES: "Lunes",
    MARTES: "Martes",
    MIERCOLES: "Miercoles",
    JUEVES: "Jueves",
    VIERNES: "Viernes",
    SABADO: "Sabado",
    DOMINGO: "Domingo"
};

function marcarDiaActivo() {
    document.querySelectorAll(".dia").forEach(link => {
        if (link.dataset.dia === diaActual) {
            link.classList.add("activo");
        }
    });
}

async function cargarHorarioClases() {
    const lista = document.getElementById("listaClases");
    const mensajeVacio = document.getElementById("mensajeVacio");

    try {
        const diaConsulta = diasApi[diaActual] ?? diaActual;

        const res = await authFetch(`/clases?diaSemana=${encodeURIComponent(diaConsulta)}`, {
            method: "GET"
        });

        if (!res || !res.ok) {
            throw new Error("No se pudieron cargar las clases");
        }

        const respuesta = await res.json();
        const clases = respuesta.data ?? respuesta;

        lista.innerHTML = "";

        if (clases.length === 0) {
            mensajeVacio.style.display = "block";
            return;
        }

        clases.forEach(clase => {
            const id = clase.idClase ?? clase.id;
            const horario = clase.horario ?? "";
            const nombre = clase.nombre ?? "";

            lista.innerHTML += `
                <div class="clase-item">
                    <div class="hora">${horario}</div>

                    <div class="info">
                        <div class="nombre">${nombre}</div>
                    </div>

                    <a href="/reservas/${id}" class="btn-mas">
                        Mas
                    </a>
                </div>
            `;
        });
    } catch (error) {
        lista.innerHTML = `<p class="text-center">${error.message}</p>`;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    marcarDiaActivo();
    cargarHorarioClases();
});
