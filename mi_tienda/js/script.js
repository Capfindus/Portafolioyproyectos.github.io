// ======= MENU RESPONSIVE =======
const menuToggle = document.getElementById("menuToggle");
const navMenu = document.querySelector(".navbar ul");

menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("show");
});
