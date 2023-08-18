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

            $(i.querySelector('[name="course"]')).on("change", (function() {a[0].revalidateField("course")})),
                $(i.querySelector('[name="teacher"]')).on("change",(function(){a[0].revalidateField("teacher")})),
                $(i.querySelector('[name="college"]')).on("change",(function(){
                    a[0].revalidateField("college");
                    var school_id = document.getElementById("college").value;
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
                $(i.querySelector('[name="major"]')).on("change",(function(){
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
                $(i.querySelector('[name="program"]')).on("change",(function(){
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
            ),
            (
                ()=>{
                    const e="#kt_modal_upload_dropzone",
                    t=document.querySelector(e);
                    var o=t.querySelector(".dropzone-item");
                    o.id="";
                    var n=o.parentNode.innerHTML;
                    o.parentNode.removeChild(o);
                    var r=new Dropzone(e,{
                        url:"path/to/your/server",
                        maxFiles: 4,parallelUploads:10,previewTemplate:n,maxFilesize:1,autoProcessQueue:!1,autoQueue:!1,acceptedFiles: ".jpeg,.jpg,.png,.gif",previewsContainer:e+" .dropzone-items",
                        clickable:e+" .dropzone-select"
                    });
                    r.on("addedfile",(
                        function(o){
                            o.previewElement.querySelector(e+" .dropzone-start").onclick=function(){
                                const e=o.previewElement.querySelector(".progress-bar");
                                e.style.opacity="1";
                                var t=1,
                                n=setInterval((function(){
                                    t>=100?(
                                        r.emit("success",o),
                                        r.emit("complete",o),
                                        clearInterval(n)
                                    ):
                                    (t++,e.style.width=t+"%")
                                }),20)
                            },
                            t.querySelectorAll(".dropzone-item").forEach((e=>{e.style.display=""})),
                            t.querySelector(".dropzone-upload").style.display="inline-block",
                            t.querySelector(".dropzone-remove-all").style.display="inline-block"
                    })),

                    r.on("complete",(function(e){
                        const o=t.querySelectorAll(".dz-complete");
                        setTimeout((function(){
                            o.forEach((e=>{
                                e.querySelector(".progress-bar").style.opacity="0",
                                e.querySelector(".progress").style.opacity="0",
                                e.querySelector(".dropzone-start").style.opacity="0"
                            }))
                        }),300)
                    })),
                    t.querySelector(".dropzone-upload").addEventListener("click",(function(){
                        r.files.forEach((e=>{
                            const t=e.previewElement.querySelector(".progress-bar");
                            t.style.opacity="1";
                            var o=1,
                            n=setInterval((function(){o>=100?(r.emit("success",e),r.emit("complete",e),clearInterval(n)):(o++,t.style.width=o+"%")}),20)
                        }))
                    })),
                    t.querySelector(".dropzone-remove-all").addEventListener("click",(function(){
                        Swal.fire({
                            text:"Are you sure you would like to remove all files?",
                            icon:"warning",showCancelButton:!0,buttonsStyling:!1,
                            confirmButtonText:"Yes, remove it!",cancelButtonText:"No, return",
                            customClass:{
                                confirmButton:"btn btn-primary",cancelButton:"btn btn-active-light"
                            }
                        }).then((function(e){
                            e.value?(
                                t.querySelector(".dropzone-upload").style.display="none",
                                t.querySelector(".dropzone-remove-all").style.display="none",
                                r.removeAllFiles(!0)
                            )
                            :"cancel"===e.dismiss&&Swal.fire({
                                text:"Your files was not removed!.",icon:"error",buttonsStyling:!1,
                                confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}
                            })
                        }))
                    })),
                    r.on("queuecomplete",(function(e){
                        t.querySelectorAll(".dropzone-upload").forEach((e=>{e.style.display="none"}))
                    })),
                    r.on("removedfile",(function(e){
                        r.files.length<1&&(
                            t.querySelector(".dropzone-upload").style.display="none",
                            t.querySelector(".dropzone-remove-all").style.display="none"
                        )
                    }))
                }
            )(),
            (
                ()=>{
                    const e="#kt_modal_video_dropzone",
                    t=document.querySelector(e);
                    var o=t.querySelector(".dropzone-item");
                    o.id="";
                    var n=o.parentNode.innerHTML;
                    o.parentNode.removeChild(o);
                    var r=new Dropzone(e,{
                        url:"path/to/your/server",
                        maxFiles: 1,parallelUploads:10,previewTemplate:n,maxFilesize:10,autoProcessQueue:!1,autoQueue:!1,acceptedFiles: ".mp4",previewsContainer:e+" .dropzone-items",
                        clickable:e+" .dropzone-select"
                    });
                    r.on("addedfile",(
                        function(o){
                            o.previewElement.querySelector(e+" .dropzone-start").onclick=function(){
                                const e=o.previewElement.querySelector(".progress-bar");
                                e.style.opacity="1";
                                var t=1,
                                n=setInterval((function(){
                                    t>=100?(
                                        r.emit("success",o),
                                        r.emit("complete",o),
                                        clearInterval(n)
                                    ):
                                    (t++,e.style.width=t+"%")
                                }),20)
                            },
                            t.querySelectorAll(".dropzone-item").forEach((e=>{e.style.display=""})),
                            t.querySelector(".dropzone-upload").style.display="inline-block",
                            t.querySelector(".dropzone-remove-all").style.display="inline-block"
                    })),

                    r.on("complete",(function(e){
                        const o=t.querySelectorAll(".dz-complete");
                        setTimeout((function(){
                            o.forEach((e=>{
                                e.querySelector(".progress-bar").style.opacity="0",
                                e.querySelector(".progress").style.opacity="0",
                                e.querySelector(".dropzone-start").style.opacity="0"
                            }))
                        }),300)
                    })),
                    t.querySelector(".dropzone-upload").addEventListener("click",(function(){
                        r.files.forEach((e=>{
                            const t=e.previewElement.querySelector(".progress-bar");
                            t.style.opacity="1";
                            var o=1,
                            n=setInterval((function(){o>=100?(r.emit("success",e),r.emit("complete",e),clearInterval(n)):(o++,t.style.width=o+"%")}),20)
                        }))
                    })),
                    t.querySelector(".dropzone-remove-all").addEventListener("click",(function(){
                        Swal.fire({
                            text:"Are you sure you would like to remove all files?",
                            icon:"warning",showCancelButton:!0,buttonsStyling:!1,
                            confirmButtonText:"Yes, remove it!",cancelButtonText:"No, return",
                            customClass:{
                                confirmButton:"btn btn-primary",cancelButton:"btn btn-active-light"
                            }
                        }).then((function(e){
                            e.value?(
                                t.querySelector(".dropzone-upload").style.display="none",
                                t.querySelector(".dropzone-remove-all").style.display="none",
                                r.removeAllFiles(!0)
                            )
                            :"cancel"===e.dismiss&&Swal.fire({
                                text:"Your files was not removed!.",icon:"error",buttonsStyling:!1,
                                confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}
                            })
                        }))
                    })),
                    r.on("queuecomplete",(function(e){
                        t.querySelectorAll(".dropzone-upload").forEach((e=>{e.style.display="none"}))
                    })),
                    r.on("removedfile",(function(e){
                        r.files.length<1&&(
                            t.querySelector(".dropzone-upload").style.display="none",
                            t.querySelector(".dropzone-remove-all").style.display="none"
                        )
                    }))
                }
            )()

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
           
            $('#sections_repeater').repeater({
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