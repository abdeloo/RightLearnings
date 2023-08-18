"use strict";
var OpenModal = function () {
    var $modal = $('#modal1');
    return{
        init:function(){
            $(document).on('click', '.ae_item', function () {
                var el = $(this);
                // AJAX request
                $.ajax({
                    url: URLBase + 'NewVersion_Sections/AE_Modal',
                    type: 'post',
                    data: {par1: el.attr('par1')},
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
                            enableTime: false,
                            dateFormat: "Y-m-d",
                        });
                        KTImageInput.createInstances();
                        
                        
                        KTCreateApp.init();
                    }
                });
            });
        }
    }
}();
var KTCreateApp=function(){
    "use strict";
        // Elements
        var modal;	
        var modalEl;
    
        var stepper;
        var form;
        var formSubmitButton;
        var formContinueButton;
    
        // Variables
        var stepperObj;
        var validations = [];

        var learning_points_ar, learning_points_en, requirements_ar, requirements_en, myDropzone;
        var form_elements ;
    
        // Private Functions
        var initStepper = function () {
            // Initialize Stepper
            stepperObj = new KTStepper(stepper);
    
            // Stepper change event(handle hiding submit button for the last step)
            stepperObj.on('kt.stepper.changed', function (stepper) {
                if (stepperObj.getCurrentStepIndex() === 4) {
                    formSubmitButton.classList.remove('d-none');
                    formSubmitButton.classList.add('d-inline-block');
                    formContinueButton.classList.add('d-none');
                } else if (stepperObj.getCurrentStepIndex() === 5) {
                    formSubmitButton.classList.add('d-none');
                    formContinueButton.classList.add('d-none');
                } else {
                    formSubmitButton.classList.remove('d-inline-block');
                    formSubmitButton.classList.remove('d-none');
                    formContinueButton.classList.remove('d-none');
                }
            });
    
            // Validation before going to next page
            stepperObj.on('kt.stepper.next', function (stepper) {
                console.log('stepper.next');
    
                // Validate form before change stepper step
                var validator = validations[stepper.getCurrentStepIndex() - 1]; // get validator for currnt step
    
                if (validator) {
                    validator.validate().then(function (status) {
                        console.log('validated!');
    
                        if (status == 'Valid') {
                            stepper.goNext();
    
                            //KTUtil.scrollTop();
                        } else {
                            // Show error message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                text: Lang.there_are_some_errors_detected,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: Lang.Ok,
                                customClass: {
                                    confirmButton: "btn btn-light"
                                }
                            }).then(function () {
                                //KTUtil.scrollTop();
                            });
                        }
                    });
                } else {
                    stepper.goNext();
    
                    KTUtil.scrollTop();
                }
            });
    
            // Prev event
            stepperObj.on('kt.stepper.previous', function (stepper) {
                console.log('stepper.previous');
    
                stepper.goPrevious();
                KTUtil.scrollTop();
            });
    
            formSubmitButton.addEventListener('click', function (e) {
                // Validate form before change stepper step
                var validator = validations[3]; // get validator for last form
    
                validator.validate().then(function (status) {
                    console.log('validated!');
    
                    if (status == 'Valid') {
                        // Prevent default button action
                        e.preventDefault();    
                        form_elements = new FormData($('#kt_modal_section_app_form')[0]),
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_Sections/ToDB",
                            data: form_elements,
                            //async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            beforeSend: function () {
                                $("#kt_modal_section_app_form :input").prop("disabled", true);
                                // Disable button to avoid multiple click 
                                formSubmitButton.disabled = true;
                                // Show loading indication
                                formSubmitButton.setAttribute('data-kt-indicator', 'on');   
                            },
                            complete: function () {
                                $("#kt_modal_section_app_form :input").prop("disabled", false);
                                // Hide loading indication
                                formSubmitButton.removeAttribute('data-kt-indicator');
                                // Enable button
                                formSubmitButton.disabled = false;
                            },
                            success: function (response)
                            {
                                if (response !== null && response.hasOwnProperty("Errors")) {
                                    //console.log(response['Errors']);
                                    var obj = response["Errors"];
                                    var stringerror = '';
                                    for (var prop in obj) {
                                        stringerror += '* ' + obj[prop] + '</br>';
                                    }
                                    Swal.fire({
                                        text: stringerror,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: Lang.Ok,
                                        customClass: {
                                            confirmButton: "btn btn-light"
                                        }
                                    })
                                    .then((function(){KTUtil.scrollTop()}))                    
                                } else if (response !== null && response.hasOwnProperty("Success")) {
                                    var Res = response['Success'];
                                    setTimeout((function(){
                                        Swal.fire({
                                            text:Res.text,
                                            icon:"success",
                                            buttonsStyling: false,
                                            confirmButtonText: Lang.Ok,
                                            customClass:{
                                                confirmButton:"btn btn-primary"
                                            }
                                        }).then((function(e){
                                            //KTUtil.scrollTop(),
                                            stepperObj.goNext(),
                                            setTimeout(function () {
                                                window.location.replace(URLBase + "NewVersion_Sections");
                                            }, 2000)
                                        }))
                                    }),2e3);
                                }
                            }
                        })
                    } else {
                        Swal.fire({
                            text: Lang.there_are_some_errors_detected,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: Lang.Ok,
                            customClass: {
                                confirmButton: "btn btn-light"
                            }
                        }).then(function () {
                            KTUtil.scrollTop();
                        });
                    }
                });
            });
        }
    
        // Init form inputs
        var initForm = function() {
            // Expiry month. For more info, plase visit the official plugin site: https://select2.org/
            $(form.querySelector('[name="course"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validations[0].revalidateField('course');
            });
    
            // Expiry year. For more info, plase visit the official plugin site: https://select2.org/
            $(form.querySelector('[name="teacher"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validations[0].revalidateField('teacher');
            });
            $(form.querySelector('[name="college"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validations[0].revalidateField('college');
                var school_id = document.getElementById("school").value;
                $("#branch").html("");
                $("#teacher").html("");
                $("#stage").html("");
                $("#grade").html("");
                $.ajax({
                    type: "GET",
                    url: URLBase + "Students_Managments/getBranches?id=" + school_id,
                    dataType: "JSON",                
                    success: function (response)
                    {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            alert('Error!');
                        } else {
                            if (response !== null && response.hasOwnProperty("Branches")) {
                                var Branches = response['Branches'];
                                var html = '<option value=""></option>';
                                $.each(Branches, function (index, value) {
                                    html += '<option value="' + index + '">' + value + '</option>';
                                });
                                $("#branch").select2('val', 'All');
                                $("#branch").html(html);
                            } 
                            if (response !== null && response.hasOwnProperty("Teachers")) {
                                var Teachers = response['Teachers'];
                                var html = '<option value=""></option>';
                                $.each(Teachers, function (index, value) {
                                    html += '<option value="' + index + '">' + value + '</option>';
                                });
                                $("#teacher").select2('val', 'All');
                                $("#teacher").html(html);
                            } 
                        }                    
                    }
                });
            });
            $(form.querySelector('[name="major"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validations[1].revalidateField('major');
                var major_id = document.getElementById("branch").value;
                $("#stage").html("");
                $("#grade").html("");
                $.ajax({
                    type: "GET",
                    url: URLBase + "Study_Sections/getProgramss?id=" + major_id ,
                    dataType: "JSON",                
                    success: function (response)
                    {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            alert('Error!');
                        } else if (response !== null && response.hasOwnProperty("Programs")) {
                            var Programs = response['Programs'];
                            var html = '<option value=""></option>';
                            $.each(Programs, function (index, value) {
                                html += '<option value="' + index + '">' + value + '</option>';
                            });
                            $("#stage").select2('val', 'All');
                            $("#stage").html(html);
                        }                     
                    }
                }); 
            });
            $(form.querySelector('[name="program"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validations[1].revalidateField('program');
                var program_id = document.getElementById("stage").value;
                $("#grade").html("");
                $.ajax({
                    type: "GET",
                    url: URLBase + "Study_Sections/getPlans?id=" + program_id ,
                    dataType: "JSON",                
                    success: function (response)
                    {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            alert('Error!');
                        } else if (response !== null && response.hasOwnProperty("Plans")) {
                            var Plans = response['Plans'];
                            var html = '<option value=""></option>';
                            $.each(Plans, function (index, value) {
                                html += '<option value="' + index + '">' + value + '</option>';
                            });
                            $("#grade").select2('val', 'All');
                            $("#grade").html(html);
                        }                     
                    }
                }); 
            });
        }
    
        var initValidation = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            // Step 1
            validations.push(FormValidation.formValidation(
                form,
                {
                    fields: {
                        course: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        teacher: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        college: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        start_date: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        end_date: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        logo_path: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                },
                                file: {
                                    extension: 'png,jpg,jpeg',
                                    type: 'image/jpeg,image/png',
                                    message: 'Please choose a png, jpg or jpeg files only',
                                },
                            }
                        },
                        profile_banner: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                },
                                file: {
                                    extension: 'png,jpg,jpeg',
                                    type: 'image/jpeg,image/png',
                                    message: 'Please choose a png, jpg or jpeg files only',
                                },
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
            ));
    
            // Step 2
            validations.push(FormValidation.formValidation(
                form,
                {
                    fields: {
                        major: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        program: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        plan: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        }
                    },
                    
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            ));
    
            // Step 3
            validations.push(FormValidation.formValidation(
                form,
                {
                    fields: {
                        
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            ));
    
            // Step 4
            validations.push(FormValidation.formValidation(
                form,
                {
                    fields: {
                        // 'card_cvv': {
                        //     validators: {
                        //         notEmpty: {
                        //             message: 'CVV is required'
                        //         },
                        //         digits: {
                        //             message: 'CVV must contain only digits'
                        //         },
                        //         stringLength: {
                        //             min: 3,
                        //             max: 4,
                        //             message: 'CVV must contain 3 to 4 digits only'
                        //         }
                        //     }
                        // }
                    },
    
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            ));
        }
    
        return {
            // Public Functions
            init: function () {
                // Elements
                modalEl = document.querySelector('#modal1');
    
                if (!modalEl) {
                    return;
                }
    
                modal = new bootstrap.Modal(modalEl);
    
                stepper = document.querySelector('#kt_modal_section_app_stepper');
                form = document.querySelector('#kt_modal_section_app_form');
                formSubmitButton = stepper.querySelector('[data-kt-stepper-action="submit"]');
                formContinueButton = stepper.querySelector('[data-kt-stepper-action="next"]');

                // The DOM elements you wish to replace with Tagify
                learning_points_ar = document.querySelector("#learning_points_ar"),
                learning_points_en = document.querySelector("#learning_points_en"),
                requirements_ar = document.querySelector("#requirements_ar"),
                requirements_en = document.querySelector("#requirements_en"),

                // Initialize Tagify components on the above inputs
                new Tagify(learning_points_ar),
                new Tagify(learning_points_en),
                new Tagify(requirements_ar),
                new Tagify(requirements_en),

                $('#description_repeater').repeater({
                    initEmpty: false,
                    defaultValues: {
                        'text-input': 'foo'
                    },
                    show: function () {
                        $(this).slideDown();
                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }), 
                
                $('#chapters_repeater_nested').repeater({
                    repeaters: [{
                        selector: '.inner-repeater',
                        show: function () {
                            $(this).slideDown();
                        },
                
                        hide: function (deleteElement) {
                            $(this).slideUp(deleteElement);
                        }
                    }],
                
                    show: function () {
                        $(this).slideDown();
                    },
                
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }),               
    
                initStepper();
                initForm();
                initValidation();
            }
        };
    
}();
KTUtil.onDOMContentLoaded((function(){OpenModal.init()}));