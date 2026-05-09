document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebarMenu");

    btn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });
});
