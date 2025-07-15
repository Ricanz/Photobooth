"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("kt_edit_frame_form");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const submitButton = form.querySelector("button[type='submit']");
        const formData = new FormData(form);
        const actionUrl = form.getAttribute("data-action");

        submitButton.setAttribute("data-kt-indicator", "on");
        submitButton.disabled = true;

        fetch(actionUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;

                if (data.success) {
                    Swal.fire({
                        text: data.message || "Frame updated successfully!",
                        icon: "success",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    }).then(() => {
                        window.location.href = "/frames";
                    });
                } else {
                    Swal.fire({
                        text: "Update failed.",
                        icon: "error",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                }
            })
            .catch((error) => {
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;

                Swal.fire({
                    text: "Something went wrong!",
                    icon: "error",
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                });
            });
    });
});
