"use strict";
var KTCreateApp=function(){
    var e,t,o,r,a,i,n=[];
    var learning_points_ar, learning_points_en, requirements_ar, requirements_en, myDropzone;
    var form_elements ;
    return{
        init:function(){
            (
                e=document.querySelector("#kt_modal_section_app")
            )&&(
                new bootstrap.Modal(e),
                t=document.querySelector("#kt_modal_section_app_stepper"),
                o=document.querySelector("#kt_modal_section_app_form"),
                r=t.querySelector('[data-kt-stepper-action="submit"]'),
                a=t.querySelector('[data-kt-stepper-action="next"]'),

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

                $(".select2me").select2({
                    dropdownParent: $("#kt_modal_section_app")
                }),

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

                // myDropzone = new Dropzone(".kt_dropzonejs", {
                //     url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
                //     paramName: "lesson_file", // The name that will be used to transfer the file
                //     maxFiles: 10,
                //     maxFilesize: 10, // MB
                //     addRemoveLinks: true,
                //     accept: function(file, done) {
                //         if (file.name == "wow.jpg") {
                //             done("Naha, you don't.");
                //         } else {
                //             done();
                //         }
                //     }
                // }),

                (i=new KTStepper(t)).on
                (
                    "kt.stepper.changed",
                    (function(e){
                        4===i.getCurrentStepIndex()?(
                            r.classList.remove("d-none"),
                            r.classList.add("d-inline-block"),
                            a.classList.add("d-none"))
                        :5===i.getCurrentStepIndex()?(
                            r.classList.add("d-none"),
                            a.classList.add("d-none")):
                            (
                                r.classList.remove("d-inline-block"),
                                r.classList.remove("d-none"),
                                a.classList.remove("d-none")
                            )
                    })
                ),
                i.on("kt.stepper.next",(function(e){
                    console.log("stepper.next");
                    var t=n[e.getCurrentStepIndex()-1];
                    t?t.validate().then(
                        (
                            function(t){
                                console.log("validated!"),
                                "Valid"==t?e.goNext()
                                :Swal.fire(
                                    {
                                        text:"Sorry, looks like there are some errors detected, please try again.",
                                        icon:"error",
                                        buttonsStyling:!1,
                                        confirmButtonText:"Ok, got it!",
                                        customClass:{
                                            confirmButton:"btn btn-light"
                                        }
                                    }
                                ).then((function(){}))
                            }
                        )
                    ):
                    (
                        e.goNext(),
                        KTUtil.scrollTop()
                    )
                })),
                i.on(
                    "kt.stepper.previous",
                    (function(e){
                        console.log("stepper.previous"),
                        e.goPrevious(),
                        KTUtil.scrollTop()
                    })
                ),
                r.addEventListener("click",(function(e){
                    n[3].validate().then(
                        (
                            function(t){
                                "Valid"==t?(
                                    //console.log(new FormData(o)),
                                    form_elements = new FormData($('#kt_modal_section_app_form')[0]),
                                    //form_elements = new FormData(o[0]),
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
                                            $("#kt_modal_section_app_form :input").prop("disabled", true)
                                            //$("#spinner").removeClass('hidden');
                                            // App.blockUI({
                                            //     target: '#SForm',
                                            //     overlayColor: 'none',
                                            //     cenrerY: true,
                                            //     animate: true
                                            // });
                                        },
                                        complete: function () {
                                            $("#kt_modal_section_app_form :input").prop("disabled", false),
                                            r.removeAttribute("data-kt-indicator"),
                                            r.disabled=!1
                                            //$("#submit-buttons").removeClass('hidden');
                                            //$("#spinner").addClass('hidden');
                                            //App.unblockUI('#SForm');
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
                                                setTimeout((function(){
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
                                                        KTUtil.scrollTop(),
                                                        setTimeout(function () {
                                                            window.location.replace(URLBase + "NewVersion_Sections");
                                                        }, 2000)
                                                    }))
                                                }),2e3);
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
                                        customClass:{confirmButton:"btn btn-light"}
                                })
                                .then((function(){KTUtil.scrollTop()}))
                            }
                        )
                    )
                })),
                $(o.querySelector('[name="course"]')).on("change", (function() {n[0].revalidateField("course")})),
                $(o.querySelector('[name="teacher"]')).on("change",(function(){n[0].revalidateField("teacher")})),
                $(o.querySelector('[name="college"]')).on("change",(function(){
                    n[0].revalidateField("college");
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
                })),
                $(o.querySelector('[name="major"]')).on("change",(function(){
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
                })),
                $(o.querySelector('[name="program"]')).on("change",(function(){
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
                })),
                
                n.push(
                    FormValidation.formValidation(
                        o,{
                            fields:{
                                course:{validators:{notEmpty:{message: Lang.required}}},
                                teacher:{validators:{notEmpty:{message: Lang.required}}},
                                college:{validators:{notEmpty:{message: Lang.required}}},
                            },
                            plugins:{
                                trigger:new FormValidation.plugins.Trigger,
                                bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})
                            }
                        }
                    )
                ),
                n.push(FormValidation.formValidation(o,
                    {
                        plugins:{
                            trigger:new FormValidation.plugins.Trigger,
                            bootstrap:new FormValidation.plugins.Bootstrap5({
                                rowSelector:".fv-row",
                                eleInvalidClass:"",
                                eleValidClass:""
                            })
                        }
                    }
                )),
                n.push(
                    FormValidation.formValidation(o,
                        // {
                        //     fields:{
                        //         dbname:{validators:{notEmpty:{message:"Database name is required"}}},
                        //         dbengine:{validators:{notEmpty:{message:"Database engine is required"}}}
                        //     },
                        //     plugins:{
                        //         trigger:new FormValidation.plugins.Trigger,
                        //         bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})
                        //     }
                        // }
                    )
                ),
                n.push(FormValidation.formValidation(
                    // o,{
                    //     fields:{
                    //         card_name:{validators:{notEmpty:{message:"Name on card is required"}}},
                    //         card_number:{validators:{notEmpty:{message:"Card member is required"},creditCard:{message:"Card number is not valid"}}},
                    //         card_expiry_month:{validators:{notEmpty:{message:"Month is required"}}},
                    //         card_expiry_year:{validators:{notEmpty:{message:"Year is required"}}},
                    //         card_cvv:{
                    //             validators:{
                    //                 notEmpty:{message:"CVV is required"},
                    //                 digits:{message:"CVV must contain only digits"},
                    //                 stringLength:{min:3,max:4,message:"CVV must contain 3 to 4 digits only"}
                    //             }
                    //         }
                    //     },
                    //     plugins:{
                    //         trigger:new FormValidation.plugins.Trigger,
                    //         bootstrap:new FormValidation.plugins.Bootstrap5({
                    //             rowSelector:".fv-row",
                    //             eleInvalidClass:"",
                    //             eleValidClass:""
                    //         })
                    //     }
                    // }
                ))
            )
        }
    }
}();
KTUtil.onDOMContentLoaded((function(){KTCreateApp.init()}));