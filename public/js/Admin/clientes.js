let modal = document.getElementById("modal");
let modalOverlay = document.getElementById("modal-overlay");

function openModal() {
    modal.classList.remove("hidden");
    modal.classList.add("grid");

    modalOverlay.classList.remove("hidden");
    modalOverlay.classList.add("flex");
}

function closeModal() {
    modal.classList.remove("grid");
    modal.classList.add("hidden");

    modalOverlay.classList.remove("flex");
    modalOverlay.classList.add("hidden");
}
