"use strict";
var PageMethods = function() {
    var handleModals = () => {
        var $modal = $('#modal1');
        $(document).on('click', '.start_class', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections_VClasses/StartClass_Modal',
                type: 'post',
                data: {par1: el.attr('section-id'), par2: el.attr('meeting-id')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    $(".select2me").select2({
                        dropdownParent: $("#modal1")
                    });
                    $(".date_picker").flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                    });
                    handleForm.init();
                }
            });
        });

        $(document).on('click', '.start_meeting', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                type: "POST",
                url: URLBase + "NewVersion_Sections_VClasses/StartMeeting",
                data: {par1: el.attr('meeting-id')},
                dataType: "JSON",
                beforeSend: function () {
                   
                },
                complete: function () {
                  
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
                            text: Res.content,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                if(Res.redirect){
                                    // Redirect to customers list page
                                    //setTimeout(function () {
                                        var table = $('#datatable_ajax').DataTable();
                                        var table2 = $('#previous_virtual_classes').DataTable();
                                        table.ajax.reload();
                                        table2.ajax.reload();
                                        window.open(Res.redirect,'MyWindow','width=1500,height=800');
                                    //}, 100);
                                    //window.location.replace(Res.redirect);
                                }else{
                                    var table = $('#datatable_ajax').DataTable();
                                    var table2 = $('#previous_virtual_classes').DataTable();
                                    table.ajax.reload();
                                    table2.ajax.reload();
                                }
                            }
                        });	
                    }
                }
            })
        });
        $(document).on('click', '.stop_classroom', function () {
            var el = $(this);
            DeleteItemAjax(el, "NewHome_Live_Meetings/Close_Class","previous_virtual_classes",Lang.are_you_want_to_close_it);
        });
    }    
    return {
        // Public Functions
        init: function() {
            handleModals();
        }
    };
}();

var handleForm = function () {
    var submitButton;
    var cancelButton;
    var validator;
    var form;
    var $modal;
    var form_elements;

    // Init form inputs
    var handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
                    'class_type': {
                        validators: {
							notEmpty: {
								message: 'App Type is required'
							}
						}
                    },
                    'title': {
						validators: {
							notEmpty: {
								message: 'title is required'
							}
						}
					},
                    'duration': {
						validators: {
							notEmpty: {
								message: 'duration is required'
							}
						}
					},
                    'start_time': {
                        validators: {
							notEmpty: {
								message: 'Start Time is required'
							},
                            // date: {
                            //     format: 'YYYY-MM-DD HH:mm:ss',
                            //     min: function() {
                            //         // Get the current datetime
                            //         return moment();
                            //     },
                            //     message: 'Start Time must be greater than or equal to the current datetime'
                            // }
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

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();
			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');
					if (status == 'Valid') {
                        form_elements = new FormData($('#Form1')[0]);
                        form_elements.append('section_id', $('#Form1').attr("section_id"));
                        form_elements.append('meeting_id', $('#Form1').attr("meeting_id"));
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_Sections_VClasses/StartClass",
                            data: form_elements,
                            //async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            beforeSend: function () {
                                $("#Form1 :input").prop("disabled", true)
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
                                            if(Res.redirect){
                                                // Redirect to customers list page
                                                window.open(Res.redirect,'MyWindow','width=1500,height=800');
                                                //window.location.replace(Res.redirect);
                                            }else{
                                                var table = $('#datatable_ajax').DataTable();
                                                table.ajax.reload();
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
                    modal.hide(); // Hide modal				
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
    }
    return {
        // Public functions
        init: function () {
            // Elements
            $modal = $('#modal1'); //new bootstrap.Modal(document.querySelector('#modal1'));
            form = document.querySelector('#Form1');
            submitButton = form.querySelector('#kt_modal_submit');
            cancelButton = form.querySelector('#kt_modal_cancel');
            handleForm();
        }
    };
}();
// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageMethods.init();
});