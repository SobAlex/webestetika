// Modal functions for service order and callback
function openServiceOrderModal(serviceName) {
    console.log("Opening modal for service:", serviceName);
    document.getElementById("service_display").value = serviceName;
    document.getElementById("service_name_input").value = serviceName;
    document.getElementById("serviceOrderModal").style.display = "flex";
}

function closeServiceOrderModal() {
    console.log("Closing modal");
    document.getElementById("serviceOrderModal").style.display = "none";
}

// Fallback modal functions in case JS modules don't load
if (typeof window.openServiceOrderModal === "undefined") {
    window.openServiceOrderModal = function (serviceName) {
        console.log("Opening modal for service:", serviceName);
        document.getElementById("service_display").value = serviceName;
        document.getElementById("service_name_input").value = serviceName;
        document.getElementById("serviceOrderModal").style.display = "flex";
    };
}

if (typeof window.closeServiceOrderModal === "undefined") {
    window.closeServiceOrderModal = function () {
        console.log("Closing modal");
        document.getElementById("serviceOrderModal").style.display = "none";
    };
}

// Make functions globally available
window.openServiceOrderModal = openServiceOrderModal;
window.closeServiceOrderModal = closeServiceOrderModal;

// Close modal on Escape key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeServiceOrderModal();
    }
});

// Initialize on DOM load
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded - modals initialized");
});
