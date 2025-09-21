
// -- Initialize Lucide icons -- //
lucide.createIcons();

// -- For toggle navbar start -- //

const mobileMenu = document.getElementById("mobile-menu");
const menuBtn = document.getElementById("menu-btn");
function toggleMenu() {
    const isOpen = mobileMenu.classList.contains("opacity-100");
    if (isOpen) {
        mobileMenu.classList.remove("opacity-100", "visible");
        mobileMenu.classList.add("opacity-0", "invisible");
        mobileMenu.firstElementChild.classList.add("translate-x-full");
    } else {
        mobileMenu.classList.remove("opacity-0", "invisible");
        mobileMenu.classList.add("opacity-100", "visible");
        mobileMenu.firstElementChild.classList.remove("translate-x-full");
    }
}
menuBtn.addEventListener("click", toggleMenu);
mobileMenu.addEventListener("click", (e) => e.target === mobileMenu && toggleMenu());
let lastScroll = window.pageYOffset;
let ticking = false;
const navwrapper = document.getElementById("navwrapper");
window.addEventListener("scroll", () => {
    if (!ticking) {
        window.requestAnimationFrame(() => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > lastScroll && currentScroll > 50) {
                navwrapper.classList.add("-translate-y-full");
            } else {
                navwrapper.classList.remove("-translate-y-full");
            }
            lastScroll = currentScroll;
            ticking = false;
        });
        ticking = true;
    }
});
document.body.style.overflowX = "hidden";

// -- For toggle navbar end -- //

// -- Typing animation for headline start -- //

const headline = "Your Growth/Your talent, Our Expertise.";
const typingHeadline = document.getElementById("typing-headline");
let index = 0;
function type() {
    if (index < headline.length) {
        typingHeadline.textContent += headline.charAt(index);
        index++;
        setTimeout(type, 100);
    }
}
type();

// -- Typing animation for headline end -- //