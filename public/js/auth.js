const API_URL = "http://127.0.0.1:8000/api";

function getToken() {
    return localStorage.getItem("token");
}

function setToken(token) {
    localStorage.setItem("token", token);
}

function removeToken() {
    localStorage.removeItem("token");
}

function setUser(user) {
    localStorage.setItem("user", JSON.stringify(user));
}

function getUser() {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
}

function removeUser() {
    localStorage.removeItem("user");
}


function isAuthenticated() {
    return !!getToken();
}

function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = "/login";
    }
}

async function authFetch(url, options = {}) {
    const token = getToken();

    const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        ...(options.headers || {})
    };

    if (token) {
        headers.Authorization = `Bearer ${token}`;
    }

    const response = await fetch(`${API_URL}${url}`, {
        ...options,
        headers
    });

    if (response.status === 401) {
        removeToken();
        removeUser();
        window.location.href = "/login";
        return;
    }

    return response;
}


async function getCurrentUser() {
    if (!isAuthenticated()) return null;

    try {
        const response = await authFetch("/perfil");

        if (!response || !response.ok) return null;

        const data = await response.json();

        return data.data ?? data;

    } catch (error) {
        console.error("Error getCurrentUser:", error);
        return null;
    }
}

async function isAdmin() {
    const user = await getCurrentUser();
    return user?.rol?.nombre === "ROLE_ADMIN";
}

async function isUser() {
    const user = await getCurrentUser();
    return user?.rol?.nombre === "ROLE_USER";
}

async function requireAdmin() {
    requireAuth();

    const admin = await isAdmin();

    if (!admin) {
        alert("No tienes permisos");
        window.location.href = "/inicio";
    }
}

async function logout() {
    try {
        await authFetch("/logout", {
            method: "POST"
        });
    } catch (error) {
        console.error(error);
    }

    removeToken();
    removeUser();

    window.location.href = "/inicio";
}

async function getNombre() {
    const usuario = await getCurrentUser();
    if (!usuario) return;

    const elemento = document.getElementById("nombreUsuario");
    if (!elemento) return;

    const nombreCompleto = `${usuario.nombre ?? ""} ${usuario.apellidoUno ?? ""}`.trim();

    const ruta = window.location.pathname;

    if (ruta.includes("inicio")) {
        elemento.textContent = `¡Hola, ${nombreCompleto}! 👋`;
    } else if (ruta.includes("historial")) {
        elemento.textContent = `Historial de ${nombreCompleto}`;
    } else {
        elemento.textContent = nombreCompleto;
    }
}

async function usernameExiste(username, idUsuario = null) {
    try {
        const response = await fetch(
            `${API_URL}/usuarios/buscar/${encodeURIComponent(username)}`
        );

        if (!response.ok) return false;

        const data = await response.json();
        const usuarios = data.data ?? data;

        return usuarios.some(u =>
            (u.userName || "").toLowerCase() === username.toLowerCase() &&
            (u.idUsuario || u.id) != idUsuario
        );

    } catch (error) {
        console.error(error);
        return false;
    }
}

async function guardarUsuario(data, idUsuario = null) {
    let url = "/usuarios";
    let method = "POST";

    if (idUsuario) {
        url = `/usuarios/${idUsuario}`;
        method = "PUT";
    }

    return await authFetch(url, {
        method,
        body: JSON.stringify(data)
    });
}

async function obtenerRolRegistro() {
    const user = await getCurrentUser();

    if (user?.rol?.nombre === "ROLE_ADMIN") {
        return 2;
    }

    return 1;
}