requireAdmin();

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-href]").forEach(element => {
        element.addEventListener("click", () => {
            window.location.href = element.dataset.href;
        });
    });
});
