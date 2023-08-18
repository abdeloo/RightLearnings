"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Define shared variables
    var datatable;
    var filterQuestionType;
    var filterDifficulty;
    var table;
    var blockUI;

    // Private functions
    var inituserList = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            stateSave: true,
            language: {
                "sInfo": "عرض" + " _START_ " + "إلي" + " _END_ " + "من" + " _TOTAL_ " ,
            },
            ajax: {
                'url': URLBase + "NewVersion_QuestionsBank/GetData",
                'type': "POST",
                "data": function ( d ) {
                    d.question_type = $('[data-kt-user-table-filter="question_type"]').val();
                    d.difficulty_degree = $('[name="difficulty_degree"]:checked').val();
                    // d.main_accounts = $('[data-kt-user-table-filter="main_accounts"]').is(":checked");
                },
                "beforeSend": function () {
                    blockUI.block();
                },
                "complete": function () {
                    blockUI.release();
                },
            }, 
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data) {
                        return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input check-one" type="checkbox" value="${data}" />
                            </div>`;
                    }
                    
                },
            ], 
            // Remove block ui on initial draw
            initComplete: function () {
                if (blockUI.isBlocked()) {
                    setTimeout(() => {
                        blockUI.release();
                    }, 1000);      
                }
            }    
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();
            KTMenu.init(); // reinit KTMenu instances 
        });

        

        datatable.on('change', '.change_status', function() {
            var clicked_checkbox = $(this);
            var id = clicked_checkbox.attr('id');
            var type = clicked_checkbox.attr('status-type');
            var questions_ids =[id];
            if($(this).is(":checked")){
                var status = "Active";
            }else{
                var status = "Disabled";
            }
            changeStatus(questions_ids,type,status,blockUI); 
		});
    }

    function  changeStatus(questions_ids,type,status,blockUI){
        $.ajax({
            url: URLBase + 'NewVersion_QuestionsBank/UpdateQuestionStatus',
            type:'post',
            data: {type: type,status:status, id: questions_ids},
            dataType: "JSON",
            beforeSend: function(xhr) {
                blockUI.block();
            },
            complete: function () {
                blockUI.release();
            },
            success: function(response){
                if (response !== null && response.hasOwnProperty("Errors")) {
                    var obj = response["Errors"];
                    var stringerror = '';
                    for (var prop in obj) {
                        stringerror += '* ' + obj[prop] + '</br>';
                    }
                    Swal.fire({
                        text: stringerror,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });  

                } else if (response !== null && response.hasOwnProperty("Success")) {
                    var Success = response.Success;
                    Swal.fire({
                        text: Success.text,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function () {
                        // Remove current row
                    });                          
                }
            }
        }); 
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        filterQuestionType = $('[data-kt-user-table-filter="question_type"]');
        filterDifficulty = document.querySelectorAll('[name="difficulty_degree"]');
        const filterButton = document.querySelector('[data-kt-user-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Get filter values
            const QuestionValue = filterQuestionType.val();
            let difficultyValue = '';

            // Get payment value
            filterDifficulty.forEach(r => {
                if (r.checked) {
                    difficultyValue = r.value;
                }
            });

            // Build filter string from filter options
            const filterString = QuestionValue + ' ' + difficultyValue;

            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search(filterString).draw();
        });
    }

    // Delete user
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-user-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                var clicked_button = $(this);
                var id = clicked_button.attr('par1');

                // Select parent row
                const parent = e.target.closest('tr');

                // Get user name
                const userName = parent.querySelectorAll('td')[1].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: clicked_button.attr('Dcontent'),
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        var type = "delete";
                        var section_ids =[id];
                        changeStatus(section_ids,type,"Delete",blockUI); 
                        datatable.row($(parent)).remove().draw();
                        // Swal.fire({
                        //     text: "You have deleted " + userName + "!.",
                        //     icon: "success",
                        //     buttonsStyling: false,
                        //     confirmButtonText: "Ok, got it!",
                        //     customClass: {
                        //         confirmButton: "btn fw-bold btn-primary",
                        //     }
                        // }).then(function () {
                        //     // Remove current row
                        //     datatable.row($(parent)).remove().draw();
                        // });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: userName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    // Reset Filter
    var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector('[data-kt-user-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Reset month
            filterQuestionType.val(null).trigger('change');
            // Reset Difficulty type
            filterDifficulty.forEach(r => {
                r.checked = false;
            });
            
            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('.check-one');

        // Select elements
        const deleteSelected = document.querySelector('[data-kt-user-table-select="delete_selected"]');

        // Toggle delete selected toolbar
        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        deleteSelected.addEventListener('click', function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Are you sure you want to delete selected users?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    Swal.fire({
                        text: "You have deleted all selected users!.",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    }).then(function () {
                        // Remove all selected users
                        checkboxes.forEach(c => {
                            if (c.checked) {
                                //datatable.row($(c.closest('tbody tr'))).remove();
                            }
                        });
                        //datatable.draw();

                        // Remove header checked box
                        const headerCheckbox = table.querySelectorAll('.check-one')[0];
                        headerCheckbox.checked = false;
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Selected users was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }

    // Toggle toolbars
    const toggleToolbars = () => {
        // Define variables
        const toolbarBase = document.querySelector('[data-kt-user-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-user-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-user-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements 
        const allCheckboxes = table.querySelectorAll('.check-one');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        //console.log("hhhhhhhhh");

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_table_users');
            blockUI = new KTBlockUI(table, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
                timeout: 2000 // 2 seconds timeout
            });
            if (!table) {
                return;
            }

            inituserList();
            initToggleToolbar();
            handleSearchDatatable();
            handleFilterDatatable();
            handleDeleteRows();
            handleResetForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});