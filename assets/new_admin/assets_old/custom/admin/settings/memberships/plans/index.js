// require datatables/jquery.dataTables.min
"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;

    // Private functions
    var initDatatable = function () {
        var target = document.querySelector("#kt_table_users");
        var blockUI = new KTBlockUI(target, {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        });
        dt = $("#kt_table_users").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            stateSave: true,
            // select: {
            //     style: 'multi',
            //     selector: 'td:first-child input[type="checkbox"]',
            //     className: 'row-selected'
            // },
            language: {
                "sInfo": "عرض" + " _START_ " + "إلي" + " _END_ " + "من" + " _TOTAL_ " ,
            },
            ajax: {
                'url': URLBase + "NewVersion_Admin_Settings_Memberships_Plans/GetData",
                'type': "POST",
                "data": function ( d ) {
                    d.account_id = $('[data-kt-user-table-filter="account_id"]').val();
                    d.currency_id = $('[data-kt-user-table-filter="currency_id"]').val();
                    d.level = $('[data-kt-user-table-filter="level"]').val();
                    d.from_date = $('[data-kt-user-table-filter="from_date"]').val();
                    d.to_date = $('[data-kt-user-table-filter="to_date"]').val();
                    d.main_accounts = $('[data-kt-user-table-filter="main_accounts"]').is(":checked");
                },
                "beforeSend": function () {
                    blockUI.block();
                },
                "complete": function () {
                    blockUI.release();
                },
            },            

            // columns: [
            //     { data: 'accountNumber' },
            //     { data: 'nameAr' },
            //     { data: 'debit' },
            //     { data: 'credit' },
            //     { data: 'total_debit' },
            //     { data: 'total_credit' },
            //     { data: 'debit_balance' },
            //     { data: 'credit_balance' },
            // ],
            
            columnDefs: [
            ],
            // Add data-filter attribute
            createdRow: function (row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            }
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            initToggleToolbar();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        const container = document.querySelector('#draw_trial_balance');
        //const checkboxes = container.querySelectorAll('[type="checkbox"]');
    }


        

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            initToggleToolbar();
        }
    }
}();



// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});