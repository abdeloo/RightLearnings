"use strict";
var KTCreateAccount=function(){
    var form_elements,a=[];
    // The DOM elements you wish to replace with Tagify
    var learning_points_ar = document.querySelector("#learning_points_ar");
    var learning_points_en = document.querySelector("#learning_points_en");
    var requirements_ar = document.querySelector("#requirements_ar");
    var requirements_en = document.querySelector("#requirements_en");

    return {
        init:function(){
            // Stepper lement
            var element = document.querySelector("#kt_create_account_stepper");
            var i=element.querySelector("#kt_create_account_form");
            var o=element.querySelector('[data-kt-stepper-action="submit"]');

            // Initialize Stepper
            var stepper = new KTStepper(element);

            // Handle navigation click
            stepper.on("kt.stepper.click", function (stepper) {
                stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
            });

            // Handle next step
            stepper.on("kt.stepper.next", function (stepper) {
                stepper.goNext(); // go next step
            });

            // Handle previous step
            stepper.on("kt.stepper.previous", function (stepper) {
                stepper.goPrevious(); // go previous step
            });

            o.classList.remove("d-none"),
            o.classList.add("d-inline-block"),

            o.addEventListener("click",(function(e){
                a[0].validate().then(
                    (
                        function(t){
                            console.log("validated!"),
                            "Valid"==t?(
                                form_elements = new FormData($('#kt_create_account_form')[0]),
                                form_elements.append('par1', $('#kt_create_account_form').attr("par1")),
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
                                        message:"Center type is required"
                                    }
                                }
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
            ),

            // Initialize Tagify components on the above inputs
            new Tagify(learning_points_ar);
            new Tagify(learning_points_en);
            new Tagify(requirements_ar);
            new Tagify(requirements_en);

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
            });
            $('#chapters_repeater').repeater({
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
            });
            $('#lesson_repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'text-input': 'foo'
                },
                show: function () {
                    $(this).slideDown();
                    // Re-init select2
                    $(this).find('[data-kt-repeater="select2"]').select2();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(){
                    // Init select2
                    $('[data-kt-repeater="select2"]').select2();
                }
            });


        }

    }
   





    // var e,t,i,o,s,r,a=[];
    // return{
    //     init:function(){
    //         (e=document.querySelector("#kt_modal_create_account"))&&new bootstrap.Modal(e),
    //         t=document.querySelector("#kt_create_account_stepper"),
    //         i=t.querySelector("#kt_create_account_form"),
    //         o=t.querySelector('[data-kt-stepper-action="submit"]'),
    //         s=t.querySelector('[data-kt-stepper-action="next"]'),
    //         (r=new KTStepper(t)).on("kt.stepper.changed",(function(e){
    //             4===r.getCurrentStepIndex()?
    //             (
    //                 o.classList.remove("d-none"),
    //                 o.classList.add("d-inline-block"),
    //                 s.classList.add("d-none")
    //             )
    //             :5===r.getCurrentStepIndex()?
    //             (
    //                 o.classList.add("d-none"),
    //                 s.classList.add("d-none")
    //             )
    //             :(
    //                 o.classList.remove("d-inline-block"),
    //                 o.classList.remove("d-none"),
    //                 s.classList.remove("d-none")
    //             )
    //         })),
    //         r.on("kt.stepper.next",(
    //             function(e){
    //                 console.log("stepper.next");
    //                 var t=a[e.getCurrentStepIndex()-1];
    //                 t?t.validate().then(
    //                     (
    //                         function(t){
    //                             console.log("validated!"),
    //                             "Valid"==t?(e.goNext(),KTUtil.scrollTop())
    //                             :Swal.fire({
    //                                 text:"Sorry, looks like there are some errors detected, please try again.",
    //                                 icon:"error",
    //                                 buttonsStyling:!1,
    //                                 confirmButtonText:"Ok, got it!",
    //                                 customClass:{confirmButton:"btn btn-light"}
    //                             })
    //                             .then((function(){KTUtil.scrollTop()}))
    //                         }
    //                     )
    //                 )
    //                 :(
    //                     e.goNext(),
    //                     KTUtil.scrollTop()
    //                 )
    //             }
    //         )),
    //         r.on("kt.stepper.previous",(
    //             function(e){
    //                 console.log("stepper.previous"),
    //                 e.goPrevious(),KTUtil.scrollTop()
    //             }
    //         )),
    //         a.push(
    //             FormValidation.formValidation(
    //                 i,{
    //                     fields:{
    //                         account_type:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Account type is required"
    //                                 }
    //                             }
    //                         }
    //                     },
    //                     plugins:{
    //                         trigger:new FormValidation.plugins.Trigger,
    //                         bootstrap:new FormValidation.plugins.Bootstrap5(
    //                             {
    //                                 rowSelector:".fv-row",
    //                                 eleInvalidClass:"",
    //                                 eleValidClass:""
    //                             }
    //                         )
    //                     }
    //                 }
    //             )
    //         ),
    //         a.push(
    //             FormValidation.formValidation(
    //                 i,{
    //                     fields:{
    //                         account_team_size:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Time size is required"
    //                                 }
    //                             }
    //                         },
    //                         account_name:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Account name is required"
    //                                 }
    //                             }
    //                         },
    //                         account_plan:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Account plan is required"
    //                                 }
    //                             }
    //                         }
    //                     },
    //                     plugins:{
    //                         trigger:new FormValidation.plugins.Trigger,
    //                         bootstrap:new FormValidation.plugins.Bootstrap5({
    //                             rowSelector:".fv-row",
    //                             eleInvalidClass:"",
    //                             eleValidClass:""
    //                         })
    //                     }
    //                 }
    //             )
    //         ),
    //         a.push(
    //             FormValidation.formValidation(
    //                 i,{
    //                     fields:{
    //                         business_name:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Busines name is required"
    //                                 }
    //                             }
    //                         },
    //                         business_descriptor:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Busines descriptor is required"
    //                                 }
    //                             }
    //                         },
    //                         business_type:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Busines type is required"
    //                                 }
    //                             }
    //                         },
    //                         business_description:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Busines description is required"
    //                                 }
    //                             }
    //                         },
    //                         business_email:{
    //                             validators:{
    //                                 notEmpty:{
    //                                     message:"Busines email is required"
    //                                 },
    //                                 emailAddress:{
    //                                     message:"The value is not a valid email address"
    //                                 }
    //                             }
    //                         }
    //                     },
    //                     plugins:{
    //                         trigger:new FormValidation.plugins.Trigger,
    //                         bootstrap:new FormValidation.plugins.Bootstrap5({
    //                             rowSelector:".fv-row",
    //                             eleInvalidClass:"",
    //                             eleValidClass:""
    //                         })
    //                     }
    //                 }
    //             )
    //         ),
    //         a.push(
    //             FormValidation.formValidation(i,{
    //                 fields:{
    //                     card_name:{
    //                         validators:{
    //                             notEmpty:{
    //                                 message:"Name on card is required"
    //                             }
    //                         }
    //                     },
    //                     card_number:{
    //                         validators:{
    //                             notEmpty:{
    //                                 message:"Card member is required"
    //                             },
    //                             creditCard:{
    //                                 message:"Card number is not valid"
    //                             }
    //                         }
    //                     },
    //                     card_expiry_month:{
    //                         validators:{
    //                             notEmpty:{
    //                                 message:"Month is required"
    //                             }
    //                         }
    //                     },
    //                     card_expiry_year:{
    //                         validators:{
    //                             notEmpty:{
    //                                 message:"Year is required"
    //                             }
    //                         }
    //                     },
    //                     card_cvv:{
    //                         validators:{
    //                             notEmpty:{
    //                                 message:"CVV is required"
    //                             },
    //                             digits:{
    //                                 message:"CVV must contain only digits"
    //                             },
    //                             stringLength:{
    //                                 min:3,
    //                                 max:4,
    //                                 message:"CVV must contain 3 to 4 digits only"
    //                             }
    //                         }
    //                     }
    //                 },
    //                 plugins:{
    //                     trigger:new FormValidation.plugins.Trigger,
    //                     bootstrap:new FormValidation.plugins.Bootstrap5({
    //                         rowSelector:".fv-row",
    //                         eleInvalidClass:"",
    //                         eleValidClass:""
    //                     })
    //                 }
    //             })
    //         ),
    //         o.addEventListener("click",(function(e){
    //             a[3].validate().then(
    //                 (
    //                     function(t){
    //                         console.log("validated!"),
    //                         "Valid"==t?(
    //                             e.preventDefault(),
    //                             o.disabled=!0,
    //                             o.setAttribute("data-kt-indicator","on"),
    //                             setTimeout((function(){
    //                                 o.removeAttribute("data-kt-indicator"),
    //                                 o.disabled=!1,
    //                                 r.goNext()
    //                             }),2e3)
    //                         )
    //                         :Swal.fire({
    //                             text:"Sorry, looks like there are some errors detected, please try again.",
    //                             icon:"error",
    //                             buttonsStyling:!1,
    //                             confirmButtonText:"Ok, got it!",
    //                             customClass:{
    //                                 confirmButton:"btn btn-light"
    //                             }
    //                         })
    //                         .then((function(){
    //                             KTUtil.scrollTop()
    //                         }))
    //                     }
    //                 )
    //             )
    //         })),
    //         $(i.querySelector('[name="card_expiry_month"]')).on("change",(function(){
    //             a[3].revalidateField("card_expiry_month")
    //         })),
    //         $(i.querySelector('[name="card_expiry_year"]')).on("change",(function(){
    //             a[3].revalidateField("card_expiry_year")
    //         })),
    //         $(i.querySelector('[name="business_type"]')).on("change",(function(){
    //             a[2].revalidateField("business_type")
    //         }))
    //     }
    // }
}();
KTUtil.onDOMContentLoaded((function(){KTCreateAccount.init()}));