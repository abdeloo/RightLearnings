"use strict";

// Class definition
var KTAccountSettingsSigninMethods = function () {
    var googleForm;
    var googleMainEl;
    var googleEditEl;
    var zoomMainEl;
    var zoomEditEl;
    var googleChange;
    var googleCancel;
    var zoomChange;
    var zoomCancel;


    var toggleDefaultApp = function () {
        $('.is_default').click(function(e) {
            var el_id = $(this).attr('id');
            var checked_status = $(this).is(':checked');
            if(checked_status == true){
                if(el_id == "zoom_meeting" && $("#zoom_api_key").val() != "" && $("#zoom_api_secret").val() != "" && $("#zoom_status").is(':checked')) {
                    handleToggleDefault("zoom_meeting", 2);
                }else if(el_id == "google_meet" && $("#google_api_key").val() != "" && $("#google_api_secret").val() != "" && $("#google_status").is(':checked')){
                    handleToggleDefault("google_meet", 3);
                }else if(el_id == "localserver"){
                    handleToggleDefault("localserver", 1);
                }else{
                    swal.fire({
                        text: "Please Enter The application Key and Secret and make it active",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            }
        });
    }

    function handleToggleDefault(app_name, app_id){
        $.ajax({
            type: "POST",
            url: URLBase + "NewVersion_Users/ToggleApiKey",
            data: {app_type: app_id},
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
                        text:Res.text,
                        icon:"success",
                        buttonsStyling:false,
                        confirmButtonText: Lang.Ok,
                        customClass:{
                            confirmButton:"btn btn-primary"
                        }
                    }).then((function(e){
                        $(".is_default").each(function(i) {
                            $(this).prop("checked", false)
                            $(this).attr('disabled',false);
                        });
                        $("#" + app_name).prop("checked", true)
                        $("#" + app_name).attr('disabled',true);
                    }))
                }
            }
        })
    }

    var toggleChangeGoogle = function () {
        googleMainEl.classList.toggle('d-none');
        googleChange.classList.toggle('d-none');
        googleEditEl.classList.toggle('d-none');
    }

    var toggleChangeZoom = function () {
        zoomMainEl.classList.toggle('d-none');
        zoomChange.classList.toggle('d-none');
        zoomEditEl.classList.toggle('d-none');
    }

    // Private functions
    var initSettings = function () {  
        if (!googleMainEl) {
            return;
        }        

        // toggle UI
        googleChange.querySelector('button').addEventListener('click', function () {
            toggleChangeGoogle();
        });

        googleCancel.addEventListener('click', function () {
            toggleChangeGoogle();
        });

        zoomChange.querySelector('button').addEventListener('click', function () {
            toggleChangeZoom();
        });

        zoomCancel.addEventListener('click', function () {
            toggleChangeZoom();
        });
    }

    var handleChangeGoogle = function (e) {
        var validation;        

        if (!googleForm) {
            return;
        }

        validation = FormValidation.formValidation(
            googleForm,
            {
                fields: {
                    api_key: {
                        validators: {
                            notEmpty: {
                                message: 'Api Key is required'
                            }
                        }
                    },

                    api_secret: {
                        validators: {
                            notEmpty: {
                                message: 'Api Secret is required'
                            }
                        }
                    }
                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        googleForm.querySelector('#kt_google_submit').addEventListener('click', function (e) {
            e.preventDefault();
            console.log('click');
            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    var form_elements = new FormData($('#kt_google_settings')[0]);
                    form_elements.append('app_type', 4);
                    sendData(form_elements, 3, "kt_google_settings",googleForm,validation);
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    var handleChangeZoom = function (e) {
        var validation;

        // form elements
        var zoomForm = document.getElementById('kt_zoom_settings');

        if (!zoomForm) {
            return;
        }

        validation = FormValidation.formValidation(
            zoomForm,
            {
                fields: {
                    api_key: {
                        validators: {
                            notEmpty: {
                                message: 'API Key is required'
                            }
                        }
                    },

                    api_secret: {
                        validators: {
                            notEmpty: {
                                message: 'Api Secret is required'
                            }
                        }
                    }
                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        zoomForm.querySelector('#kt_zoom_submit').addEventListener('click', function (e) {
            e.preventDefault();
            console.log('click');

            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    var form_elements = new FormData($('#kt_zoom_settings')[0]);
                    form_elements.append('app_type', 2);
                    sendData(form_elements, 2, "kt_zoom_settings",zoomForm, validation);
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }


    function sendData(form_elements, app_type, form_id, form_element, validation){
        $.ajax({
            type: "POST",
            url: URLBase + "NewVersion_Users/ApiKeyTodb",
            data: form_elements,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function () {
                $("#" + form_id + " :input").prop("disabled", true)
            },
            complete: function () {
                $("#" + form_id + " :input").prop("disabled", false)
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
                        text:Res.text,
                        icon:"success",
                        buttonsStyling:false,
                        confirmButtonText: Lang.Ok,
                        customClass:{
                            confirmButton:"btn btn-primary"
                        }
                    }).then((function(e){
                        //form_element.reset();
                        //validation.resetForm(); // Reset formvalidation --- more info: https://formvalidation.io/guide/api/reset-form/
                        if(app_type == 2){
                            toggleChangeZoom();
                        }else if(app_type == 4){
                            toggleChangeGoogle();
                        }
                    }))
                }
            }
        })
    }

    // Public methods
    return {
        init: function () {
            googleForm = document.getElementById('kt_google_settings');
            googleMainEl = document.getElementById('kt_google_meetings');
            googleEditEl = document.getElementById('kt_google_api_edit');
            zoomMainEl = document.getElementById('kt_zoom_meetings');
            zoomEditEl = document.getElementById('kt_zoom_api_edit');
            googleChange = document.getElementById('kt_google_button');
            googleCancel = document.getElementById('kt_google_cancel');
            zoomChange = document.getElementById('kt_zoom_button');
            zoomCancel = document.getElementById('kt_zoom_cancel');

            initSettings();
            handleChangeGoogle();
            handleChangeZoom();
            toggleDefaultApp();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAccountSettingsSigninMethods.init();
});
