   document.addEventListener("DOMContentLoaded", function() {
        const bellIcon = document.getElementById("bell-icon");
        const dropdown = document.querySelector(".notification-dropdown");

        // Alternar visibilidad al hacer clic en la campana
        bellIcon.addEventListener("click", function(e) {
            e.stopPropagation(); // Evita cerrar inmediatamente por el click global
            dropdown.classList.toggle("show");
        });

        // Cerrar dropdown si se hace clic fuera
        document.addEventListener("click", function() {
            dropdown.classList.remove("show");
        });

        // Evita que se cierre si haces clic dentro del dropdown
        dropdown.addEventListener("click", function(e) {
            e.stopPropagation();
        });
    });