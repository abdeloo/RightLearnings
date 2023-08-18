"use strict";
var PageMethods = function() {

    var handleTimeTable = () => {
 
        var target = document.querySelector("#weekly_sessions");
        var blockUI = new KTBlockUI(target);
        getTimeTable((new Date()).toISOString().split('T')[0]);
        $(document).on('change', '#timetable_date', function () {
            getTimeTable($(this).val());
        })
        $(document).on('click', '.change_date', function () {
            $("#timetable_date").val($(this).attr('current-date'));
        })
        

        

        function getTimeTable(current_date){            
            $.ajax({
                url: URLBase + 'NewVersion_Teachers_Home/GetTimeTable',
                type: 'post',
                data: {par1: current_date},
                cache: false,
                beforeSend: function () {
                    blockUI.block();
                },
                complete: function () {
                    blockUI.release();
                }, success: function (response) {
                    // Add response in Modal body
                    $("#weekly_sessions").html(response);
                    $(".date_picker").flatpickr({
                        enableTime: false,
                        dateFormat: "Y-m-d",
                    });
                }
            });
        }
    }
    var handleModals = () => {

        var $modal = $('#modal1');
        $(document).on('click', '.cancel_session', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Teachers_Home/Cancel_Session_Modal',
                type: 'post',
                data: {par1: el.attr('appointment-id'), par2: el.attr('current-date')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span>' + Lang.Please_wait + '...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    $(".select2me").select2({
                        dropdownParent: $("#modal1")
                    });
                    $(".date_picker").flatpickr({
                        enableTime: false,
                        dateFormat: "Y-m-d",
                    });                    
                    KTModalAddPayment.init();
                }
            });
        });
    }
    return {
            // Public Functions
            init: function() {
                handleTimeTable();
                handleModals();
            }
    };
}();


// Class definition
var KTModalAddPayment = function () {
    var element;
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator = FormValidation.formValidation();;
    var newBalance;
    var form;
    var $modal;
    var form_elements;


    // Init form inputs
    var initForm = function () {
        // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector('[name="replacement_form"]')).on('change', function () {
            // Revalidate the field when an option is chosen
            if ($(this).is(":checked")){
                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
                validator = FormValidation.formValidation(
                    form,
                    {
                        fields: {
                            'new_date': {
                                validators: {
                                    notEmpty: {
                                        message: 'new date is required'
                                    }
                                }
                            },
                            'start': {
                                validators: {
                                    notEmpty: {
                                        message: 'Start is required'
                                    }
                                }
                            },
                            'end': {
                                validators: {
                                    notEmpty: {
                                        message: 'End is required'
                                    }
                                }
                            }
                        },

                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '',
                                eleValidClass: ''
                            })
                        }
                    }
                );
            }else{
                //validator.revalidateField('status');
                validator = FormValidation.formValidation();
            }
        });

        // Action buttons
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {

                        form_elements = new FormData($('#Form1')[0]);
                        form_elements.append('par1', $('#Form1').attr("par1"));
                        form_elements.append('par2', $('#Form1').attr("par2"));
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_Teachers_Home/Cancel_Session",
                            data: form_elements,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            beforeSend: function () {
                                $("#Form1 :input").prop("disabled", true)
                                // Show loading indication
                                submitButton.setAttribute('data-kt-indicator', 'on');
                                // Disable submit button whilst loading
                                submitButton.disabled = true;
                            },
                            complete: function () {
                                $("#Form1 :input").prop("disabled", false),
                                submitButton.removeAttribute('data-kt-indicator');
                            },
                            success: function (response)
                            {
                                if (response !== null && response.hasOwnProperty("Errors")) {
                                    var obj = response["Errors"];
                                    var stringerror = '';
                                    for (var prop in obj) {
                                        stringerror += '* ' + obj[prop] + '</br>';
                                    }
                                    Swal.fire({
                                        text:stringerror,
                                        icon:"error",
                                        buttonsStyling:false,
                                        confirmButtonText:"Ok, got it!",
                                        customClass:{confirmButton:"btn btn-light"}
                                    })                                    
                                } else if (response !== null && response.hasOwnProperty("Success")) {
                                    var Res = response['Success'];
                                    Swal.fire({
                                        text: Res.text,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            // Hide modal
                                            $modal.modal('hide');
                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                            // Reset form for demo purposes only
                                            form.reset();
                                            if(Res.redirect){
                                                // var table = $('#datatable_ajax').DataTable();
                                                // table.ajax.reload();
                                            }
                                        }
                                    });	
                                }
                            }
                        })													
					} else {
						Swal.fire({
							text: "Sorry, looks like there are some errors detected, please try again.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn btn-primary"
							}
						});
					}
                });
            }
        });

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form	
                    $modal.modal('hide'); // Hide modal				
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

        closeButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form	
                    $modal.modal('hide'); // Hide modal				
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

        $("#replacement_form").click(function(e) {
            if($(this).is(":checked")){
                $('#set_other_date').removeClass('hidden');
            }else{
                form.reset(); // Reset form	
                $('#set_other_date').addClass('hidden');
            }
        });
    }

    return {
        // Public functions
        init: function () {
            // Elements
            element = document.querySelector('#modal1');
            //$modal = new bootstrap.Modal(element);
            $modal = $('#modal1'); 
            form = document.querySelector('#Form1');
            submitButton = form.querySelector('#kt_modal_add_payment_submit');
            cancelButton = form.querySelector('#kt_modal_add_payment_cancel');
            closeButton = element.querySelector('#kt_modal_add_payment_close');
            initForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageMethods.init();
});