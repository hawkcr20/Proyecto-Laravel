document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById("authButtons");

    if (!container) return;

    if (isAuthenticated()) {
        if (await isAdmin()) {
            container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/adminDashboard">Admin</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" type="button" id="btnLogout">Salir</button>
                    </li>
                </ul>
            `;
        } else if (await isUser()) {
            container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/historial">Historial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">Clases</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" type="button" id="btnLogout">Salir</button>
                    </li>
                </ul>
            `;
        }
    }

    const logoutButton = document.getElementById("btnLogout");

    if (logoutButton) {
        logoutButton.addEventListener("click", logout);
    }

    await getNombre();
});
