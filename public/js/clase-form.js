requireAdmin();

async function cargarClaseParaEditar(idClase) {
    if (!idClase) return;

    try {
        const res = await authFetch(`/clases/${idClase}`);

        if (!res || !res.ok) throw new Error("No se pudo cargar la clase");

        const data = await res.json();
        const clase = data.data;

        document.querySelector("[name=nombre]").value = clase.nombre ?? "";
        document.querySelector("[name=descripcion]").value = clase.descripcion ?? "";
        document.querySelector("[name=diaSemana]").value = clase.diaSemana ?? "";
        document.querySelector("[name=horario]").value = clase.horario ?? "";
        document.querySelector("[name=capacidad]").value = clase.capacidad ?? "";
    } catch (err) {
        console.error(err);
        alert("Error cargando la clase");
    }
}

function guardarClase(event) {
    event.preventDefault();

    const id = document.querySelector("[name=idClase]").value;

    const data = {
        nombre: document.querySelector("[name=nombre]").value,
        descripcion: document.querySelector("[name=descripcion]").value,
        diaSemana: document.querySelector("[name=diaSemana]").value,
        horario: document.querySelector("[name=horario]").value,
        capacidad: parseInt(document.querySelector("[name=capacidad]").value)
    };

    let url = "/clases";
    let method = "POST";

    if (id) {
        url = `/clases/${id}`;
        method = "PUT";
    }

    authFetch(url, {
        method,
        body: JSON.stringify(data)
    })
        .then(res => {
            if (!res || !res.ok) {
                return res.json().then(err => {
                    throw new Error(err.message || "Error al guardar clase");
                });
            }

            return res.json();
        })
        .then(() => {
            alert("Clase guardada correctamente");
            window.location.href = "/clasesVista";
        })
        .catch(err => alert(err.message));
}

document.addEventListener("DOMContentLoaded", () => {
    const idClase = document.querySelector("[name=idClase]").value;
    const form = document.getElementById("formClase");

    cargarClaseParaEditar(idClase);

    if (form) {
        form.addEventListener("submit", guardarClase);
    }
});
