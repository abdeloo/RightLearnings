"use strict";
var OpenModal = function () {
    var $modal = $('#modal1');
    return{
        init:function(){
            $(document).on('click', '.ae_item', function () {
                var el = $(this);
                // AJAX request
                $.ajax({
                    url: URLBase + 'NewVersion_Centers/AE',
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
                        $(".select2me").select2({
                            dropdownParent: $("#modal1")
                        });
                        KTCreateAccount();
                    }
                });
            });
        }
    }
}();
var KTCreateAccount=function(){
    var form_elements,a=[];
    var element,e,i,o,stepper;
    //return {
        //init:function(){
            // Stepper lement
            (e=document.querySelector("#modal1"))&&
            (
                new bootstrap.Modal(e),
                element = document.querySelector("#kt_create_account_stepper"),
                i=element.querySelector("#kt_create_account_form"),
                o=element.querySelector('[data-kt-stepper-action="submit"]'),

                // Initialize Stepper
                stepper = new KTStepper(element),

                // Handle navigation click
                stepper.on("kt.stepper.click", function (stepper) {
                    stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
                }),

                // Handle next step
                stepper.on("kt.stepper.next", function (stepper) {
                    stepper.goNext(); // go next step
                }),

                // Handle previous step
                stepper.on("kt.stepper.previous", function (stepper) {
                    stepper.goPrevious(); // go previous step
                }),

                o.classList.remove("d-none"),
                o.classList.add("d-inline-block"),

                o.addEventListener("click",(function(e){
                    a[0].validate().then(
                        (
                            function(t){
                                var files = $('#kt_modal_upload_dropzone').get(0).dropzone.getAcceptedFiles();
                                var video = $('#kt_modal_video_dropzone').get(0).dropzone.getAcceptedFiles();
                                form_elements = new FormData($('#kt_create_account_form')[0]);
                                form_elements.append('par1', $('#kt_create_account_form').attr("par1"));
                                for (var i = 0; i< files.length; i++) {
                                    form_elements.append('images[]', files[i])
                                }
                                for (var j = 0; j< video.length; j++) {
                                    form_elements.append('video_path', video[j])
                                }
                                console.log("validated!"),
                                "Valid"==t?(
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
                                            e.preventDefault(),
                                            o.disabled=!0,
                                            o.setAttribute("data-kt-indicator","on")
                                        },
                                        complete: function () {
                                            o.removeAttribute("data-kt-indicator"),
                                            o.disabled=!1
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
                                                    text:stringerror,
                                                    icon:"error",
                                                    buttonsStyling:!1,
                                                    confirmButtonText:"Ok, got it!",
                                                    customClass:{confirmButton:"btn btn-light"}
                                                })
                                                .then((function(){KTUtil.scrollTop()}))                    
                                            } else if (response !== null && response.hasOwnProperty("Success")) {
                                                var Res = response['Success'];
                                                //setTimeout((function(){
                                                Swal.fire({
                                                    text:Res.text,
                                                    icon:"success",
                                                    buttonsStyling:!1,
                                                    confirmButtonText: Lang.Ok,
                                                    customClass:{
                                                        confirmButton:"btn btn-primary"
                                                    }
                                                }).then((function(e){
                                                    //t.isConfirmed&&
                                                    stepper.goNext()
                                                    KTUtil.scrollTop(),
                                                    setTimeout(function () {
                                                        //window.location.replace(URLBase + "NewVersion_Sections");
                                                    }, 2000)
                                                }))
                                                //}),2e3);
                                            }
                                        }
                                    })
                                )
                                :Swal.fire({
                                    text:"Sorry, looks like there are some errors detected, please try again.",
                                    icon:"error",
                                    buttonsStyling:!1,
                                    confirmButtonText:"Ok, got it!",
                                    customClass:{
                                        confirmButton:"btn btn-light"
                                    }
                                })
                                .then((function(){
                                    KTUtil.scrollTop()
                                }))
                            }
                        )
                    )
                })),
                a.push(
                    FormValidation.formValidation(
                        i,{
                            fields:{
                                college:{
                                    validators:{
                                        notEmpty:{
                                            message:"Center is required"
                                        }
                                    }
                                },
                                teacher:{
                                //     validators:{
                                //         // notEmpty:{
                                //         //     message:"Teacher is required"
                                //         // }
                                //     }
                                },
                                course:{
                                //     validators:{
                                //         notEmpty:{
                                //             message:"course is required"
                                //         }
                                //     }
                                }
                            },
                            plugins:{
                                trigger:new FormValidation.plugins.Trigger,
                                bootstrap:new FormValidation.plugins.Bootstrap5(
                                    {
                                        rowSelector:".fv-row",
                                        eleInvalidClass:"",
                                        eleValidClass:""
                                    }
                                )
                            }
                        }
                    )
                )
            )
        
};
KTUtil.onDOMContentLoaded((function(){OpenModal.init()}));