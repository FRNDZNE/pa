$(document).ready(function () {
    window.addEventListener("show-modal", function (event) {
        const id = event.detail.id;
        const modalEl = $("#" + id)[0];
        const modal = new bootstrap.Modal(modalEl);

        modal.show();
    });

    window.addEventListener("hide-modal", function (event) {
        const id = event.detail.id;
        const modalEl = $("#" + id)[0];
        const modal = bootstrap.Modal.getInstance(modalEl);

        if (modal) {
            modal.hide();
        }
    });
});
