document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("filter-form");
    const prioriteSelect = document.getElementById("priorite");
    const statutSelect = document.getElementById("statut");
    const ticketContainer = document.querySelector(".ticket-container");

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        loadTickets();
    });

    prioriteSelect.addEventListener("change", loadTickets);
    statutSelect.addEventListener("change", loadTickets);

    function loadTickets() {
        const priorite = prioriteSelect.value;
        const statut = statutSelect.value;

        // Effectuer une requête AJAX vers le script PHP pour récupérer les tickets filtrés
        $.ajax({
            type: "POST",
            url: "ticket.php", // Assurez-vous de spécifier le bon chemin vers votre script PHP
            data: JSON.stringify({ priorite: priorite, statut: statut }),
            contentType: "application/json",
            success: function (data) {
                // Mettre à jour l'affichage des tickets avec les données JSON reçues
                ticketContainer.innerHTML = "";

                if (data.length > 0) {
                    data.forEach((ticket) => {
                        const ticketDiv = document.createElement("div");
                        ticketDiv.classList.add("ticket");
                        ticketContainer.appendChild(ticketDiv);
                    });
                } else {
                    const noTicketsDiv = document.createElement("div");
                    noTicketsDiv.classList.add("no-tickets");
                    noTicketsDiv.textContent = "Aucun ticket trouvé pour les critères sélectionnés.";
                    ticketContainer.appendChild(noTicketsDiv);
                }
            },
            error: function (error) {
                console.error("Erreur lors de la récupération des tickets:", error);
            },
        });
    }

    // Chargez les tickets initiaux au chargement de la page
    loadTickets();
});
