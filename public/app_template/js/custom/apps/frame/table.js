"use strict";

var KTUsersList = (function () {
    var e, t, n, r;
    const o = document.getElementById("kt_table_frames");

    const c = () => {
        o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach(
            (btn) => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    const row = e.target.closest("tr");
                    const itemName = row.querySelectorAll("td")[1].innerText;
                    const rowId = row.getAttribute("data-id");
                    
                    Swal.fire({
                        text:
                            "Are you sure you want to delete " + itemName + "?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton:
                                "btn fw-bold btn-active-light-primary",
                        },
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            fetch(`/packages/${rowId}/destroy`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute("content"),
                                    Accept: "application/json",
                                },
                            })
                                .then((response) => response.json())
                                .then((data) => {
                                    if (data.success) {
                                        Swal.fire({
                                            text: data.message,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton:
                                                    "btn fw-bold btn-primary",
                                            },
                                        }).then(() => {
                                            const table =
                                                $(
                                                    "#kt_table_frames"
                                                ).DataTable();
                                            table.row($(row)).remove().draw();
                                        });
                                    } else {
                                        throw new Error(
                                            data.message || "Delete failed."
                                        );
                                    }
                                })
                                .catch((err) => {
                                    Swal.fire({
                                        text:
                                            err.message ||
                                            "Something went wrong.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    });
                                });
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            Swal.fire({
                                text: itemName + " was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        }
                    });
                });
            }
        );
    };


    return {
        init: function () {
            if (!o) return;

            e = $(o).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/packages/list",
                    type: "GET",
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    { data: "title" },
                    { data: "description" },
                    { data: "price" },
                    { data: "total_print" },
                    { data: "type" },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                fill="black"/>
                                        </svg>
                                    </span>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="/frames/${row.id}/show" class="menu-link px-3">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-users-table-filter="delete_row">Delete</a>
                                    </div>
                                </div>
                            `;
                        },
                    },
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr("data-id", data.id);
                },
                info: false,
                order: [],
                pageLength: 10,
                lengthChange: false,
            });

            // After table draw, re-initialize dropdown and event handlers
            e.on("draw", function () {
                c(); // Delete click
                KTMenu.createInstances(); // 🔁 Re-init dropdowns
            });

            // Search
            const searchInput = document.querySelector(
                '[data-kt-frame-table-filter="search"]'
            );
            if (searchInput) {
                searchInput.addEventListener("keyup", function (t) {
                    e.search(t.target.value).draw();
                });
            }
        },
    };
})();

// On DOM ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersList.init();
});
