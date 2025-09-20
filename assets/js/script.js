lucide.createIcons();
const mobileMenu = document.getElementById("mobile-menu");
const menuPanel = document.getElementById("menu-panel");
const menuBtn = document.getElementById("menu-btn");
const header = document.querySelector("header");
let lastScroll = 0;

function toggleMenu() {
    const isOpen = mobileMenu.classList.contains("opacity-100");
    if (isOpen) {
        mobileMenu.classList.remove("opacity-100", "visible");
        mobileMenu.classList.add("opacity-0", "invisible");
        menuPanel.classList.add("translate-x-full");
    } else {
        mobileMenu.classList.remove("opacity-0", "invisible");
        mobileMenu.classList.add("opacity-100", "visible");
        menuPanel.classList.remove("translate-x-full");
    }
}
menuBtn.addEventListener("click", toggleMenu);

// Hide header on scroll down, show on scroll up
window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;
    if (currentScroll > lastScroll && currentScroll > 50) {
        header.classList.add("-translate-y-full");
    } else {
        header.classList.remove("-translate-y-full");
    }
    lastScroll = currentScroll;
});

// Close menu if clicking outside sidebar
mobileMenu.addEventListener("click", function (e) {
    if (e.target === this) toggleMenu();
});