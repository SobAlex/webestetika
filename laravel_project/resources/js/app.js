import "./bootstrap";
import "flowbite";
import "./modals";
import { initPhoneMask } from "./phone-mask";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Initialize phone mask when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    initPhoneMask();
    initFAQ();
    initConsentCheckboxes();
});

function initConsentCheckboxes() {
    document.querySelectorAll("form").forEach(function (form) {
        const checkbox = form.querySelector(".consent-checkbox");
        if (!checkbox) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (!submitButton) {
            return;
        }

        const sync = function () {
            submitButton.disabled = !checkbox.checked;
        };

        sync();
        checkbox.addEventListener("change", sync);
    });
}

// FAQ functionality
function initFAQ() {
    const faqToggles = document.querySelectorAll(".faq-toggle");

    faqToggles.forEach((toggle) => {
        toggle.addEventListener("click", function () {
            const targetId = this.getAttribute("data-target");
            const answer = document.getElementById(targetId);
            const icon = this.querySelector(".faq-icon i");
            const isExpanded = this.getAttribute("aria-expanded") === "true";

            if (isExpanded) {
                // Close
                answer.classList.add("hidden");
                this.setAttribute("aria-expanded", "false");
                icon.textContent = "expand_more";
                this.querySelector(".faq-icon").classList.remove("rotate-180");
            } else {
                // Open
                answer.classList.remove("hidden");
                this.setAttribute("aria-expanded", "true");
                icon.textContent = "expand_less";
                this.querySelector(".faq-icon").classList.add("rotate-180");
            }
        });
    });
}
