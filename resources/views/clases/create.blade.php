<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Clase</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/crearClase.css') }}">
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">

        <div class="registro-card p-4">

            <div class="text-center">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo">
                <h2 class="titulo">Nueva Clase</h2>
                <p class="subtitulo">Crea o edita una clase</p>
            </div>

            <form id="formClase">

                <input type="hidden" name="idClase" value="{{ $idClase ?? '' }}">

                <div class="mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre de la clase" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="descripcion" class="form-control" placeholder="Descripción" required>
                </div>

                <div class="mb-3">
                    <select name="diaSemana" class="form-control" required>
                        <option value="">Día de la semana</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="time" name="horario" class="form-control" required>
                </div>

                <div class="mb-3">
                    <input type="number" name="capacidad" class="form-control" placeholder="Capacidad" min="1" required>
                </div>

                <button type="submit" class="btn btn-main w-100">
                    Guardar Clase
                </button>

            </form>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        requireAdmin();

        const idClase = document.querySelector("[name=idClase]").value;

        
        document.addEventListener("DOMContentLoaded", async () => {

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
        });

        
        document.getElementById("formClase").addEventListener("submit", function(e) {
            e.preventDefault();

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
                    method: method,
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
        });
    </script>

</body>

</html>