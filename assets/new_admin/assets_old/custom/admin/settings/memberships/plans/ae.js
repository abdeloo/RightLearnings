"use strict";
var OpenModal = function () {
    var $modal = $('#modal1');
    return{
        init:function(){
            $(document).on('click', '.ae_item', function () {
                var el = $(this);
                // AJAX request
                $.ajax({
                    url: URLBase + 'NewVersion_Admin_Settings_Memberships_Plans/AE',
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
                        $("#membership_type").select2({
                            dropdownParent: $("#modal1")
                        });
                        $("#price_type").select2({
                            dropdownParent: $("#modal1")
                        });
                        KTCreateApp();
                    }
                });
            });
        }
    }
}();
var KTCreateApp=function(){
    var e,t,o,r,a,i,n,features_ar,features_en;
    var form_elements ;
    (e=document.querySelector("#modal1"))&&(
        new bootstrap.Modal(e),
        o=document.querySelector("#kt_modal_create_app_form"),
        r=e.querySelector('[data-kt-stepper-action="submitt"]'),
        a=e.querySelector('[data-kt-stepper-action="next"]'),
        // The DOM elements you wish to replace with Tagify
        features_ar = document.querySelector("#features_ar"),
        features_en = document.querySelector("#features_en"),
        // Initialize Tagify components on the above inputs
        new Tagify(features_ar),
        new Tagify(features_en),
        n=FormValidation.formValidation(
            o,{
                fields:{
                    name_ar:{
                        validators:{
                            notEmpty:{message:"name_ar is required"}
                        }
                    },
                    name_en:{
                        validators:{
                            notEmpty:{
                                message:"name_en is required"
                            }
                        }
                    },
                    membership_type:{
                        validators:{
                            notEmpty:{
                                message:"membership_type is required"}
                            }
                        },
                    price:{
                        validators:{
                            notEmpty:{
                                message:"price is required"
                            }
                        }
                    }
                    // "targets_notifications[]":{
                    //     validators:{
                    //         notEmpty:{
                    //             message:"Please select at least one communication method"
                    //         }
                    //     }
                    // }
                },
                plugins:{
                    trigger:new FormValidation.plugins.Trigger,
                    bootstrap:new FormValidation.plugins.Bootstrap5({
                        rowSelector:".fv-row",
                        eleInvalidClass:"",
                        eleValidClass:""
                    })
                }
            }
        ),
        $(o.querySelector('[name="membership_type"]')).on("change",(function(){n.revalidateField("membership_type")})),         
        r.addEventListener("click",(function(e){
            e.preventDefault(),
            n&&n.validate().then(
                (
                    function(e){
                        console.log("validated!"),
                        "Valid"==e?(
                            form_elements = new FormData($('#kt_modal_create_app_form')[0]),
                            form_elements.append('par1', $('#kt_modal_create_app_form').attr("par1")),
                            $.ajax({
                                type: "POST",
                                url: URLBase + "NewVersion_Admin_Settings_Memberships_Plans/ToDB",
                                data: form_elements,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: "JSON",
                                beforeSend: function () {
                                    $("#kt_modal_create_app_form :input").prop("disabled", true),
                                    r.setAttribute("data-kt-indicator","on"),
                                    r.disabled=!0
                                },
                                complete: function () {
                                    $("#kt_modal_create_app_form :input").prop("disabled", false),
                                    r.removeAttribute("data-kt-indicator"),
                                    r.disabled=!1
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
                                            buttonsStyling:!1,
                                            confirmButtonText:"Ok, got it!",
                                            customClass:{confirmButton:"btn btn-light"}
                                        })
                                        .then((function(){KTUtil.scrollTop()}))                    
                                    } else if (response !== null && response.hasOwnProperty("Success")) {
                                        var Res = response['Success'];
                                        Swal.fire({
                                            text:Res.text,
                                            icon:"success",
                                            buttonsStyling:!1,
                                            confirmButtonText: Lang.Ok,
                                            customClass:{
                                                confirmButton:"btn btn-primary"
                                            }
                                        }).then((function(e){
                                            KTUtil.scrollTop(),
                                            setTimeout(function () {
                                                window.location.replace(URLBase + "NewVersion_Admin_Settings_Memberships_Plans");
                                            },1000)
                                        }))                                        
                                    }
                                }
                            })
                        )
                        :Swal.fire(
                            {
                                text:"Sorry, looks like there are some errors detected, please try again.",
                                icon:"error",
                                buttonsStyling:!1,
                                confirmButtonText:"Ok, got it!",
                                customClass:{
                                    confirmButton:"btn btn-primary"
                                }
                            }
                        )
                    }
                )
            )
        }))
    )
};

KTUtil.onDOMContentLoaded((function(){OpenModal.init()}));