document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    if (!form) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const userName = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const idioma = document.getElementById("idioma").value;

        try {
            const response = await fetch("/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Accept-Language": idioma
                },
                body: JSON.stringify({
                    userName,
                    password
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Credenciales invalidas");
            }

            setLanguage(idioma);
            setToken(data.token);

            if (data.usuario) {
                setUser(data.usuario);
            }

            window.location.href = "/inicio";
        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    });
});
