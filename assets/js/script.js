
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

function toggleServices() {
    const list = document.getElementById("services-list");
    const icon = document.getElementById("services-icon");
    list.classList.toggle("hidden");
    icon.classList.toggle("rotate-180");
}

// -- For toggle navbar end -- //


// -- Swiper start -- //


var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});


// -- Swiper end -- //


// -- FooterPlaceholder start -- //


document.addEventListener("DOMContentLoaded", function () {
    const footerPlaceholder = document.getElementById('footer-placeholder');

    if (footerPlaceholder) {
        fetch('/footer.html')
            .then(response => response.ok ? response.text() : Promise.reject('Footer not found'))
            .then(data => {
                footerPlaceholder.innerHTML = data;

                lucide.createIcons();

                attachContactFormListener();
            })
            .catch(error => {
                console.error('Error fetching the footer:', error);
                footerPlaceholder.innerHTML = '<p style="text-align:center; color:red;">Could not load footer.</p>';
            });
    }
});


// -- FooterPlaceholder end -- //


// -- ContactForm start -- //


function attachContactFormListener() {
    const contactForm = document.getElementById('contactForm');
    const formStatus = document.getElementById('form-status');

    if (!contactForm) {
        return;
    }

    contactForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const submitButton = contactForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;

        const formData = new FormData(contactForm);

        fetch('/sendmail.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                formStatus.textContent = data.message;
                if (data.status === 'success') {
                    formStatus.style.color = '#006400';
                    contactForm.reset();
                } else {
                    formStatus.style.color = '#8B0000';
                }

                setTimeout(() => {
                    formStatus.textContent = '';
                }, 4000);
            })
            .catch(error => {
                formStatus.textContent = 'A network error occurred. Please try again.';
                formStatus.style.color = '#8B0000';
                console.error('Error:', error);
            })
            .finally(() => {
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            });
    });
}


// -- ContactForm end -- //


