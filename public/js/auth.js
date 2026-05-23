const API_URL = "http://127.0.0.1:8000/api";

function guardarToken(token) {
    localStorage.setItem("token", token);
}

function obtenerToken() {
    return localStorage.getItem("token");
}

function cerrarSesion() {
    localStorage.removeItem("token");
    window.location.href = "/login";
}