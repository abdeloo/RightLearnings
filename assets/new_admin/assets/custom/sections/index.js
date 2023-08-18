"use strict";

// Class definition
var KTSectionsList = function () {
    // Define shared variables
    var datatable;
    var filterMonth;
    var filterPayment;
    var table;
    var blockUI

    // Private functions
    var inituserList = function () {
        $('#kt_table_users thead th').addClass('hidden');
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        // tableRows.forEach(row => {
        //     const dateRow = row.querySelectorAll('td');
        //     const realDate = moment(dateRow[5].innerHTML, "DD MMM YYYY, LT").format(); // select date from 5th column in table
        //     dateRow[5].setAttribute('data-order', realDate);
        // });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            // "info": false,
            // 'order': [],
            // 'columnDefs': [
            //     { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
            //     { orderable: false, targets: 6 }, // Disable ordering on column 6 (actions)
            // ],
            responsive: {
                details: {
                    type: "column",
                    target: -1
                }
            },
            //responsive:true,
            searchDelay: 400,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            stateSave: true,
            language: {
                "sInfo": "عرض" + " _START_ " + "إلي" + " _END_ " + "من" + " _TOTAL_ " ,
            },
            fnDrawCallback: function() {
                if (document.documentElement.dir === 'rtl' && $('#kt_table_users tbody tr td.dataTables_empty').length) {
                  $('#kt_table_users tbody tr td.dataTables_empty').attr('colspan', '2');
                }
            },
            ajax: {
                'url': URLBase + "NewVersion_Sections/GetData",
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
                    //blockUI.block();
                },
                "complete": function () {
                    //blockUI.release();
                    // Remove 'hidden' class from table headers
                    $('#kt_table_users thead th').removeClass('hidden');
                },
            }, 
            columnDefs: [
                {
                    className: "dtr-control dtr-control-last",
                    orderable: false,
                    targets:   -1
                },
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
                {
                    targets: 1,
                    className: 'd-flex align-items-center'
                },
            ],
            initComplete: function() {
                $('#kt_table_users th.details-content').css('display', '');
            },
            // Add data-filter attribute
            // createdRow: function (row, data, dataIndex) {
            //     $(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
            // }    

            
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            var UserType = $('#kt_table_users').attr('user-type'); 
            if(UserType == "teacher"){
                datatable.column(1).visible(false);
            }else{
                datatable.column(1).visible(true);
            }
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();
            KTMenu.init(); // reinit KTMenu instances 
        });

        $(".edit_item").click(function(e) {
            var details =  $(this).data('details');
            $('#item_type_id').val(details["id"]);
            // $('#edit_ar_name').val(details["name_ar"]);
            // $('#edit_en_name').val(details["name_en"]);
            // $('#edit_status').attr('checked', details["status"] );             
        });

        datatable.on('change', '.change_status', function() {
            var clicked_checkbox = $(this);
            var id = clicked_checkbox.attr('id');
            var type = clicked_checkbox.attr('status-type');
            var section_ids =[id];
            if($(this).is(":checked")){
                var status = "Active";
                var set_checked = true;
            }else{
                var status = "Disabled";
                var set_checked = false;
            }
            changeStatus(section_ids,type,status,blockUI); 
            //clicked_checkbox.prop('checked', set_checked);               
		});
    }

    function  changeStatus(section_ids,type,status,blockUI){
        $.ajax({
            url: URLBase + 'NewVersion_Sections/UpdateSectionStatus',
            type:'post',
            data: {type: type,status:status, id: section_ids},
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
                    //clicked_checkbox.prop('checked', !set_checked);

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
                    //clicked_checkbox.prop('checked', set_checked);
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
        filterMonth = $('[data-kt-user-table-filter="month"]');
        filterPayment = document.querySelectorAll('[data-kt-user-table-filter="payment_type"] [name="payment_type"]');
        const filterButton = document.querySelector('[data-kt-user-table-filter="filter"]');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Get filter values
            const monthValue = filterMonth.val();
            let paymentValue = '';

            // Get payment value
            filterPayment.forEach(r => {
                if (r.checked) {
                    paymentValue = r.value;
                }

                // Reset payment value if "All" is selected
                if (paymentValue === 'all') {
                    paymentValue = '';
                }
            });

            // Build filter string from filter options
            const filterString = monthValue + ' ' + paymentValue;

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
            filterMonth.val(null).trigger('change');

            // Reset payment type
            filterPayment[0].checked = true;

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

    var handleModals = () => {
        var $modal = $('#modal1');
        $(document).on('click', '.students_details', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections/Students_Progress',
                type: 'post',
                data: {par1: el.attr('par1')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
                    loading += ' <img  src="' + URLBase + 'files/logo.jpeg" alt="logo-img"> ';
                    loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
                    loading += ' </div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    KTTimelineWidget1.init();
                }
            });
        });
    }

    var KTTimelineWidget1 = function () {
        // Private methods
        // Day timeline
        const initTimelineDay = () => {
            // Detect element
            const element = document.querySelector('#kt_timeline_widget_1_1');
            if (!element) {
                return;
            }
            if(element.innerHTML){
                return;
            }
            var group;
            var item;
            // Set variables
            var now = Date.now();
            var rootImagePath = element.getAttribute('data-kt-timeline-widget-1-image-root');

            // $.ajax({
            //     url: URLBase + 'NewVersion_Sections/GetStatistics',
            //     type: 'post',
            //     data: {},
            //     cache: false,
            //     success: function (response) {
            //         group = response.groups;
            //         item = response.items;
            //         $modal.html(response);
            //         KTTimelineWidget1.init();
            //     }
            // });
    
            // Build vis-timeline datasets
            var groups = new vis.DataSet([
                {
                    id: "research",
                    content: "01-01-2023",
                    order: 1
                },
                {
                    id: "qa",
                    content: "01-02-2023",
                    order: 2
                },
                {
                    id: "ui",
                    content: "01-07-2023",
                    order: 3
                },
                {
                    id: "dev",
                    content: "01-09-2023",
                    order: 4
                }
            ]);
    
    
            var items = new vis.DataSet([
                {
                    id: 1,
                    group: 'research',
                    start: now,
                    end: moment(now).add(1.5, 'hours'),
                    content: 'Meeting',
                    progress: "60%",
                    color: 'primary',
                    users: [
                        'avatars/300-6.jpg',
                        'avatars/300-1.jpg'
                    ]
                },
                {
                    id: 2,
                    group: 'qa',
                    start: moment(now).add(1, 'hours'),
                    end: moment(now).add(2, 'hours'),
                    content: 'Testing',
                    progress: "47%",
                    color: 'success',
                    users: [
                        'avatars/300-2.jpg'
                    ]
                },
                {
                    id: 3,
                    group: 'ui',
                    start: moment(now).add(30, 'minutes'),
                    end: moment(now).add(2.5, 'hours'),
                    content: 'Landing page',
                    progress: "55%",
                    color: 'danger',
                    users: [
                        'avatars/300-5.jpg',
                        'avatars/300-20.jpg'
                    ]
                },
                {
                    id: 4,
                    group: 'dev',
                    start: moment(now).add(1.5, 'hours'),
                    end: moment(now).add(3, 'hours'),
                    content: 'Products module',
                    progress: "75%",
                    color: 'info',
                    users: [
                        'avatars/300-23.jpg',
                        'avatars/300-12.jpg',
                        'avatars/300-9.jpg'
                    ]
                },
            ]);
    
            // Set vis-timeline options
            var options = {
                zoomable: false,
                moveable: false,
                selectable: false,
                // More options https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                margin: {
                    item: {
                        horizontal: 10,
                        vertical: 35
                    }
                },
    
                // Remove current time line --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                showCurrentTime: false,
    
                // Whitelist specified tags and attributes from template --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                xss: {
                    disabled: false,
                    filterOptions: {
                        whiteList: {
                            div: ['class', 'style'],
                            img: ['data-kt-timeline-avatar-src', 'alt'],
                            a: ['href', 'class']
                        },
                    },
                },
                // specify a template for the items
                template: function (item) {
                    // Build users group
                    const users = item.users;
                    let userTemplate = '';
                    users.forEach(user => {
                        userTemplate += `<div class="symbol symbol-circle symbol-25px"><img data-kt-timeline-avatar-src="${rootImagePath + user}" alt="" /></div>`;
                    });
    
                    return `<div class="rounded-pill bg-light-${item.color} d-flex align-items-center position-relative h-40px w-100 p-2 overflow-hidden">
                        <div class="position-absolute rounded-pill d-block bg-${item.color} start-0 top-0 h-100 z-index-1" style="width: ${item.progress};"></div>
            
                        <div class="d-flex align-items-center position-relative z-index-2">
                            <div class="symbol-group symbol-hover flex-nowrap me-3">
                                ${userTemplate}
                            </div>
            
                            <a href="#" class="fw-bold text-white text-hover-dark">${item.content}</a>
                        </div>
            
                        <div class="d-flex flex-center bg-body rounded-pill fs-7 fw-bolder ms-auto h-100 px-3 position-relative z-index-2">
                            ${item.progress}
                        </div>
                    </div>        
                    `;
                },
    
                // Remove block ui on initial draw
                onInitialDrawComplete: function () {
                    handleAvatarPath();
    
                    const target = element.closest('[data-kt-timeline-widget-1-blockui="true"]');
                    const blockUI = KTBlockUI.getInstance(target);
    
                    if (blockUI.isBlocked()) {
                        setTimeout(() => {
                            blockUI.release();
                        }, 1000);      
                    }
                }
            };
    
            // Init vis-timeline
            const timeline = new vis.Timeline(element, items, groups, options);
    
            // Prevent infinite loop draws
            timeline.on("currentTimeTick", () => {            
                // After fired the first time we un-subscribed
                timeline.off("currentTimeTick");
            });
        }
    
        // Week timeline
        const initTimelineWeek = () => {
            // Detect element
            const element = document.querySelector('#kt_timeline_widget_1_2');
            if (!element) {
                return;
            }
    
            if(element.innerHTML){
                return;
            }
    
            // Set variables
            var now = Date.now();
            var rootImagePath = element.getAttribute('data-kt-timeline-widget-1-image-root');
    
            // Build vis-timeline datasets
            var groups = new vis.DataSet([
                {
                    id: 1,
                    content: "Research",
                    order: 1
                },
                {
                    id: 2,
                    content: "Phase 2.6 QA",
                    order: 2
                },
                {
                    id: 3,
                    content: "UI Design",
                    order: 3
                },
                {
                    id: 4,
                    content: "Development",
                    order: 4
                }
            ]);
    
    
            var items = new vis.DataSet([
                {
                    id: 1,
                    group: 1,
                    start: now,
                    end: moment(now).add(7, 'days'),
                    content: 'Framework',
                    progress: "71%",
                    color: 'primary',
                    users: [
                        'avatars/300-6.jpg',
                        'avatars/300-1.jpg'
                    ]
                },
                {
                    id: 2,
                    group: 2,
                    start: moment(now).add(7, 'days'),
                    end: moment(now).add(14, 'days'),
                    content: 'Accessibility',
                    progress: "84%",
                    color: 'success',
                    users: [
                        'avatars/300-2.jpg'
                    ]
                },
                {
                    id: 3,
                    group: 3,
                    start: moment(now).add(3, 'days'),
                    end: moment(now).add(20, 'days'),
                    content: 'Microsites',
                    progress: "69%",
                    color: 'danger',
                    users: [
                        'avatars/300-5.jpg',
                        'avatars/300-20.jpg'
                    ]
                },
                {
                    id: 4,
                    group: 4,
                    start: moment(now).add(10, 'days'),
                    end: moment(now).add(21, 'days'),
                    content: 'Deployment',
                    progress: "74%",
                    color: 'info',
                    users: [
                        'avatars/300-23.jpg',
                        'avatars/300-12.jpg',
                        'avatars/300-9.jpg'
                    ]
                },
            ]);
    
            // Set vis-timeline options
            var options = {
                zoomable: false,
                moveable: false,
                selectable: false,
    
                // More options https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                margin: {
                    item: {
                        horizontal: 10,
                        vertical: 35
                    }
                },
    
                // Remove current time line --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                showCurrentTime: false,
    
                // Whitelist specified tags and attributes from template --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                xss: {
                    disabled: false,
                    filterOptions: {
                        whiteList: {
                            div: ['class', 'style'],
                            img: ['data-kt-timeline-avatar-src', 'alt'],
                            a: ['href', 'class']
                        },
                    },
                },
                // specify a template for the items
                template: function (item) {
                    // Build users group
                    const users = item.users;
                    let userTemplate = '';
                    users.forEach(user => {
                        userTemplate += `<div class="symbol symbol-circle symbol-25px"><img data-kt-timeline-avatar-src="${rootImagePath + user}" alt="" /></div>`;
                    });
    
                    return `<div class="rounded-pill bg-light-${item.color} d-flex align-items-center position-relative h-40px w-100 p-2 overflow-hidden">
                        <div class="position-absolute rounded-pill d-block bg-${item.color} start-0 top-0 h-100 z-index-1" style="width: ${item.progress};"></div>
            
                        <div class="d-flex align-items-center position-relative z-index-2">
                            <div class="symbol-group symbol-hover flex-nowrap me-3">
                                ${userTemplate}
                            </div>
            
                            <a href="#" class="fw-bold text-white text-hover-dark">${item.content}</a>
                        </div>
            
                        <div class="d-flex flex-center bg-body rounded-pill fs-7 fw-bolder ms-auto h-100 px-3 position-relative z-index-2">
                            ${item.progress}
                        </div>
                    </div>        
                    `;
                },
    
                // Remove block ui on initial draw
                onInitialDrawComplete: function () {
                    handleAvatarPath();
    
                    const target = element.closest('[data-kt-timeline-widget-1-blockui="true"]');
                    const blockUI = KTBlockUI.getInstance(target);
    
                    if (blockUI.isBlocked()) {
                        setTimeout(() => {
                            blockUI.release();
                        }, 1000);      
                    }
                }
            };
    
            // Init vis-timeline
            const timeline = new vis.Timeline(element, items, groups, options);
    
            // Prevent infinite loop draws
            timeline.on("currentTimeTick", () => {            
                // After fired the first time we un-subscribed
                timeline.off("currentTimeTick");
            });
        }
    
        // Month timeline
        const initTimelineMonth = () => {
            // Detect element
            const element = document.querySelector('#kt_timeline_widget_1_3');
            if (!element) {
                return;
            }
    
            if(element.innerHTML){
                return;
            }
    
            // Set variables
            var now = Date.now();
            var rootImagePath = element.getAttribute('data-kt-timeline-widget-1-image-root');
    
            // Build vis-timeline datasets
            var groups = new vis.DataSet([
                {
                    id: "research",
                    content: "Research",
                    order: 1
                },
                {
                    id: "qa",
                    content: "Phase 2.6 QA",
                    order: 2
                },
                {
                    id: "ui",
                    content: "UI Design",
                    order: 3
                },
                {
                    id: "dev",
                    content: "Development",
                    order: 4
                }
            ]);
    
    
            var items = new vis.DataSet([
                {
                    id: 1,
                    group: 'research',
                    start: now,
                    end: moment(now).add(2, 'months'),
                    content: 'Tags',
                    progress: "79%",
                    color: 'primary',
                    users: [
                        'avatars/300-6.jpg',
                        'avatars/300-1.jpg'
                    ]
                },
                {
                    id: 2,
                    group: 'qa',
                    start: moment(now).add(0.5, 'months'),
                    end: moment(now).add(5, 'months'),
                    content: 'Testing',
                    progress: "64%",
                    color: 'success',
                    users: [
                        'avatars/300-2.jpg'
                    ]
                },
                {
                    id: 3,
                    group: 'ui',
                    start: moment(now).add(2, 'months'),
                    end: moment(now).add(6.5, 'months'),
                    content: 'Media',
                    progress: "82%",
                    color: 'danger',
                    users: [
                        'avatars/300-5.jpg',
                        'avatars/300-20.jpg'
                    ]
                },
                {
                    id: 4,
                    group: 'dev',
                    start: moment(now).add(4, 'months'),
                    end: moment(now).add(7, 'months'),
                    content: 'Plugins',
                    progress: "58%",
                    color: 'info',
                    users: [
                        'avatars/300-23.jpg',
                        'avatars/300-12.jpg',
                        'avatars/300-9.jpg'
                    ]
                },
            ]);
    
            // Set vis-timeline options
            var options = {
                zoomable: false,
                moveable: false,
                selectable: false,
    
                // More options https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                margin: {
                    item: {
                        horizontal: 10,
                        vertical: 35
                    }
                },
    
                // Remove current time line --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                showCurrentTime: false,
    
                // Whitelist specified tags and attributes from template --- more info: https://visjs.github.io/vis-timeline/docs/timeline/#Configuration_Options
                xss: {
                    disabled: false,
                    filterOptions: {
                        whiteList: {
                            div: ['class', 'style'],
                            img: ['data-kt-timeline-avatar-src', 'alt'],
                            a: ['href', 'class']
                        },
                    },
                },
                // specify a template for the items
                template: function (item) {
                    // Build users group
                    const users = item.users;
                    let userTemplate = '';
                    users.forEach(user => {
                        userTemplate += `<div class="symbol symbol-circle symbol-25px"><img data-kt-timeline-avatar-src="${rootImagePath + user}" alt="" /></div>`;
                    });
    
                    return `<div class="rounded-pill bg-light-${item.color} d-flex align-items-center position-relative h-40px w-100 p-2 overflow-hidden">
                        <div class="position-absolute rounded-pill d-block bg-${item.color} start-0 top-0 h-100 z-index-1" style="width: ${item.progress};"></div>
            
                        <div class="d-flex align-items-center position-relative z-index-2">
                            <div class="symbol-group symbol-hover flex-nowrap me-3">
                                ${userTemplate}
                            </div>
            
                            <a href="#" class="fw-bold text-white text-hover-dark">${item.content}</a>
                        </div>
            
                        <div class="d-flex flex-center bg-body rounded-pill fs-7 fw-bolder ms-auto h-100 px-3 position-relative z-index-2">
                            ${item.progress}
                        </div>
                    </div>        
                    `;
                },
    
                // Remove block ui on initial draw
                onInitialDrawComplete: function () {
                    handleAvatarPath();
                    
                    const target = element.closest('[data-kt-timeline-widget-1-blockui="true"]');
                    const blockUI = KTBlockUI.getInstance(target);
    
                    if (blockUI.isBlocked()) {
                        setTimeout(() => {
                            blockUI.release();
                        }, 1000);                    
                    }
                }
            };
    
            // Init vis-timeline
            const timeline = new vis.Timeline(element, items, groups, options);
    
            // Prevent infinite loop draws
            timeline.on("currentTimeTick", () => {            
                // After fired the first time we un-subscribed
                timeline.off("currentTimeTick");
            });
        }
    
        // Handle BlockUI
        const handleBlockUI = () => {
            // Select block ui elements
            const elements = document.querySelectorAll('[data-kt-timeline-widget-1-blockui="true"]');
    
            // Init block ui
            elements.forEach(element => {
                const blockUI = new KTBlockUI(element, {
                    overlayClass: "bg-body",
                });
    
                blockUI.block();
            });
        }
    
        // Handle tabs visibility
        const tabsVisibility = () => {
            const tabs = document.querySelectorAll('[data-kt-timeline-widget-1="tab"]');
    
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', e => {
                    // Week tab
                    if(tab.getAttribute('href') === '#kt_timeline_widget_1_tab_week'){
                        initTimelineWeek();
                    }
    
                    // Month tab
                    if(tab.getAttribute('href') === '#kt_timeline_widget_1_tab_month'){
                        initTimelineMonth();
                    }
                });
            });
        }
    
        // Handle avatar path conflict
        const handleAvatarPath = () => {
            const avatars = document.querySelectorAll('[data-kt-timeline-avatar-src]');
    
            if(!avatars){
                return;
            }
    
            avatars.forEach(avatar => {
                avatar.setAttribute('src', avatar.getAttribute('data-kt-timeline-avatar-src'));
                avatar.removeAttribute('data-kt-timeline-avatar-src');
            });
        }
    
        // Public methods
        return {
            init: function () {
                initTimelineDay();
                handleBlockUI();
                tabsVisibility();
            }
        }
    }();


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
            handleModals();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSectionsList.init();
});