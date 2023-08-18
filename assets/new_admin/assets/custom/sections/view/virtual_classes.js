"use strict";
// Class definition
var DataTableAjax1 = function () {

    // Define shared variables
    var datatable;
    var table = document.querySelector('#datatable_ajax');

    // Private functions
    var initDataView = function () {

        var status = {
            1: {"title": "our_server", "state": "danger"},
            2: {"title": "zoom", "state": "success"},
            3: {"title": "wiziq", "state": "primary"},
            4: {"title": "google", "state": "info"},
        };

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            'columnDefs': [
                {
                    "render": function ( data, type, row ) {
                        return '<span class="ms-2 badge badge-light-' + status[data]['state'] + ' fs-5 ">' + status[data]['title'] + '</span>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        var index = KTUtil.getRandomInt(1, 4);
                        return '<span class="ms-2 link-' + status[index]['state'] + ' fs-5 ">' + data + '</span>';
                    },
                    "targets": 2
                },
                {
                    targets: -1,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var item = data.split("_");
                        return `
                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 start_meeting" meeting-id="` + item[0] + `"> 
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Start Now">
                                        <i class="ki-duotone ki-youtube text-primary fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </a>
                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 start_class" meeting-id="` + item[0] + `"> 
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                        <i class="ki-duotone ki-pencil text-success fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </a>
                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 delete_meeting" par1="` + item[0] + `" data-kt-customer-table-filter="delete_row"  data-bs-toggle="tooltip" title="Delete">
                                    <i class="ki-duotone ki-trash text-danger fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </a>`;                          
                    },
                },

            ],
            searchDelay: 400,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            stateSave: true,
            language: {
                "sInfo": "عرض" + " _START_ " + "إلي" + " _END_ " + "من" + " _TOTAL_ " ,
            },
            ajax: {
                'url': URLBase + "NewVersion_Sections/GetVirtualClass",
                data: {'par1':$("#datatable_ajax").attr('par1'), 'par2':$("#datatable_ajax").attr('par2')},
                'type': "POST",
                "beforeSend": function () {
                    //blockUI.block();
                },
                "complete": function () {
                    //blockUI.release();
                    // Remove 'hidden' class from table headers
                    //$('#kt_table_users thead th').removeClass('hidden');
                },
            }, 
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            deleteRows();
            KTMenu.init(); // reinit KTMenu instances 
        });
    }

    // Delete customer
    var deleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-customer-table-filter="delete_row"]');
        
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                var el = $(this);
                DeleteItemAjax(el, "NewVersion_Sections_VClasses/DeleteClass","datatable_ajax",null);
            })
        });
    }

    // Public methods
    return {
        init: function () {
            if (!table) {
                return;
            }
            initDataView();
            deleteRows();
        }
    }
}();


var DataTableAjax2 = function () {

    // Define shared variables
    var datatable2;
    var table2 = document.querySelector('#previous_virtual_classes');

    // Private functions
    var initDataView = function () {

        var status = {
            1: {"title": "our_server", "state": "danger"},
            2: {"title": "zoom", "state": "success"},
            3: {"title": "wiziq", "state": "primary"},
            4: {"title": "google", "state": "info"},
        };

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable2 = $(table2).DataTable({
            'columnDefs': [
                {
                    "render": function ( data, type, row ) {
                        return '<span class="ms-2 badge badge-light-' + status[data]['state'] + ' fs-5 ">' + status[data]['title'] + '</span>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        var index = KTUtil.getRandomInt(1, 4);
                        return '<span class="ms-2 link-' + status[index]['state'] + ' fs-5 ">' + data + '</span>';
                    },
                    "targets": 2
                },
                {
                    targets: -1,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var item = data.split("_");
                        var meeting_actions = '';
                        if(item[1] != ''){
                            meeting_actions = `<a target="_blank" href="` + item[1] + `" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"> 
                                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="resume_session">
                                                        <i class="ki-solid ki-arrows-circle text-warning fs-2"></i>
                                                    </span>
                                                </a>
                                                <a par1="` + item[0] + `" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 stop_classroom"> 
                                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="stop_classroom">
                                                        <i class="ki-duotone ki-cross-circle text-danger fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </span>
                                                </a>`;
                        }
                        return `
                                <a class="btn btn-icon btn-active-light-primary w-30px h-30px me-3 view_class" meeting-id="` + item[0] + `"> 
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="view">
                                        <i class="ki-duotone ki-eye text-info fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </a>` + meeting_actions ;                        
                    },
                },

            ],
            searchDelay: 400,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            stateSave: true,
            language: {
                "sInfo": "عرض" + " _START_ " + "إلي" + " _END_ " + "من" + " _TOTAL_ " ,
            },
            ajax: {
                'url': URLBase + "NewVersion_Sections/GetVirtualClass",
                data: {'par1':$("#previous_virtual_classes").attr('par1'), 'par2':$("#previous_virtual_classes").attr('par2')},
                'type': "POST",
                "beforeSend": function () {
                    //blockUI.block();
                },
                "complete": function () {
                    //blockUI.release();
                    // Remove 'hidden' class from table headers
                    //$('#kt_table_users thead th').removeClass('hidden');
                },
            }, 
        });
    }

    var handleModals = () => {
        var $modal = $('#modal1');
        $(document).on('click', '.view_class', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections_VClasses/ViewClass_Modal',
                type: 'post',
                data: {par1: el.attr('meeting-id')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    //ClassDetails.init();
                }
            });
        });
    }
    // Public methods
    return {
        init: function () {
            if (!table2) {
                return;
            }
            initDataView();
            handleModals();
        }
    }
}();


					
// On document ready
KTUtil.onDOMContentLoaded(function () {
    DataTableAjax1.init();
    DataTableAjax2.init();
    // Wait for the document to finish loading
    window.addEventListener('load', function() {
        // Check if the URL contains a hash (i.e. a tab ID)
        if (window.location.hash) {
        // Get the ID of the tab to activate (without the # symbol)
        var tabId = window.location.hash.substring(1);
        
        // Find the link element that corresponds to the tab ID
        var tabLink = document.getElementById(tabId);
        console.log(tabLink);
        
        // If the link element exists, simulate a click on it to activate the tab
        if (tabLink) {
            tabLink.click();
        }
        }
    });

});