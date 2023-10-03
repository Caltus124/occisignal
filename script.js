// Sélection des éléments de la barre latérale et des boutons
const sidebar = document.querySelector(".sidebar");
const sidebarOpenBtn = document.querySelector("#sidebar-open");
const sidebarCloseBtn = document.querySelector("#sidebar-close");
const sidebarLockBtn = document.querySelector("#lock-icon");

// Fonction pour basculer l'état de verrouillage de la barre latérale
const toggleLock = () => {
  sidebar.classList.toggle("locked");
  // Si la barre latérale n'est pas verrouillée
  if (!sidebar.classList.contains("locked")) {
    sidebar.classList.add("hoverable");
    sidebarLockBtn.classList.replace("bx-lock-alt", "bx-lock-open-alt");
  } else {
    sidebar.classList.remove("hoverable");
    sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
  }
};

// Fonction pour masquer la barre latérale lorsque la souris la quitte
const hideSidebar = () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.add("close");
  }
};

// Fonction pour afficher la barre latérale lorsque la souris entre
const showSidebar = () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.remove("close");
  }
};

// Fonction pour afficher et masquer la barre latérale
const toggleSidebar = () => {
  sidebar.classList.toggle("close");
};

// Vérifiez si l'URL actuelle contient "main.php?page=map"
if (window.location.href.includes("main.php?page=map")) {
  sidebar.classList.add("locked");
} else {
  sidebar.classList.remove("locked");
  sidebar.classList.add("hoverable");
}

// Ajout des écouteurs d'événements aux boutons et à la barre latérale pour les actions correspondantes
sidebarLockBtn.addEventListener("click", toggleLock);
sidebar.addEventListener("mouseleave", hideSidebar);
sidebar.addEventListener("mouseenter", showSidebar);
sidebarOpenBtn.addEventListener("click", toggleSidebar);
sidebarCloseBtn.addEventListener("click", toggleSidebar);
