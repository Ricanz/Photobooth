"use strict";
var KTUsersAddUser = (function () {
    const modalEl = document.getElementById("kt_modal_add_frame"),
        formEl = modalEl.querySelector("#kt_modal_add_frame_form"),
        modal = new bootstrap.Modal(modalEl);

    return {
        init: function () {
            const validator = FormValidation.formValidation(formEl, {
                fields: {
                    title: {
                        validators: {
                            notEmpty: { message: "Title is required" },
                        },
                    },
                    description: {
                        validators: {
                            notEmpty: { message: "Description is required" },
                        },
                    },
                    price: {
                        validators: {
                            notEmpty: { message: "Price is required" },
                        },
                    },
                    total_print: {
                        validators: {
                            notEmpty: { message: "Total print is required" },
                            integer: { message: "Must be a number" },
                        },
                    },
                    type: {
                        validators: {
                            notEmpty: { message: "Type is required" },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                },
            });

            const submitBtn = modalEl.querySelector(
                '[data-kt-pckages-modal-action="submit"]'
            );
            submitBtn.addEventListener("click", function (e) {
                e.preventDefault();
                validator.validate().then(function (status) {
                    if (status === "Valid") {
                        submitBtn.setAttribute("data-kt-indicator", "on");
                        submitBtn.disabled = true;

                        const formData = new FormData(formEl);

                        fetch("/packages/store", {
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
                                submitBtn.removeAttribute("data-kt-indicator");
                                submitBtn.disabled = false;

                                if (data.success) {
                                    Swal.fire({
                                        text:
                                            data.message ||
                                            "Frame successfully created!",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "OK, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    }).then(() => {
                                        modal.hide();
                                        formEl.reset();
                                        location.reload(); // refresh datatable or page
                                    });
                                } else {
                                    Swal.fire({
                                        text: "Something went wrong!",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "OK",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                }
                            })
                            .catch((error) => {
                                submitBtn.removeAttribute("data-kt-indicator");
                                submitBtn.disabled = false;
                                Swal.fire({
                                    text: "Server error. Please try again later.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "OK",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                                console.error(error);
                            });
                    } else {
                        Swal.fire({
                            text: "Please fix the errors in the form.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "OK, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                });
            });

            // Cancel & Close logic
            ["cancel", "close"].forEach((action) => {
                modalEl
                    .querySelector(`[data-kt-pckages-modal-action="${action}"]`)
                    .addEventListener("click", function (e) {
                        e.preventDefault();
                        Swal.fire({
                            text: "Are you sure you want to cancel?",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: "Yes, cancel it!",
                            cancelButtonText: "No, return",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-active-light",
                            },
                        }).then((result) => {
                            if (result.value) {
                                formEl.reset();
                                modal.hide();
                            }
                        });
                    });
            });
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTUsersAddUser.init();
});
