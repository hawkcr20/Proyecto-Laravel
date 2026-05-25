document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById("authButtons");
    const idioma = getLanguage();
    const textos = {
        es: {
            login: "Login",
            clases: "Clases",
            historial: "Historial",
            admin: "Admin",
            salir: "Salir"
        },
        en: {
            login: "Login",
            clases: "Classes",
            historial: "History",
            admin: "Admin",
            salir: "Logout"
        }
    };
    const t = textos[idioma] || textos.es;

    if (!container) return;

    if (isAuthenticated()) {
        if (await isAdmin()) {
            container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">${t.clases}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/adminDashboard">${t.admin}</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" type="button" id="btnLogout">${t.salir}</button>
                    </li>
                </ul>
            `;
        } else if (await isUser()) {
            container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/historial">${t.historial}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">${t.clases}</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" type="button" id="btnLogout">${t.salir}</button>
                    </li>
                </ul>
            `;
        }
    } else {
        container.innerHTML = `<a href="/login" class="btn btn-login-neon">${t.login}</a>`;
    }

    const logoutButton = document.getElementById("btnLogout");

    if (logoutButton) {
        logoutButton.addEventListener("click", logout);
    }

    await getNombre();
});
