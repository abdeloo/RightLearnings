"use strict";
var KTCreateApp=function(){
    var e,t,o,r,a,i,n=[];
    var points_of_speak, myDropzone;
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
                points_of_speak = document.querySelector("#points_of_speak"),
                
                // Initialize Tagify components on the above inputs
                new Tagify(points_of_speak),

                (i=new KTStepper(t)).on
                (
                    "kt.stepper.changed",
                    (function(e){
                        3===i.getCurrentStepIndex()?(
                            r.classList.remove("d-none"),
                            r.classList.add("d-inline-block"),
                            a.classList.add("d-none"))
                        :4===i.getCurrentStepIndex()?(
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
                                var files = $('#kt_modal_upload_dropzone').get(0).dropzone.getAcceptedFiles();
                                form_elements = new FormData($('#kt_modal_section_app_form')[0]);
                                // iterate them
                                // var sponsors = {};
                                // console.log(files);
                                for (var i = 0; i< files.length; i++) {
                                //     // get file obj
                                //     // var addedFile = addedFiles[i];
                                //     // // get done function
                                //     // var doneFile = addedFilesHash[addedFile];
                                //     // // call done function to upload file to server
                                //     // doneFile();
                                //     var item = sponsors[i];
                                //     item["dataURL"] = files[i]["dataURL"];
                                //     console.log(files[i]["dataURL"]);
                                    form_elements.append('sponsers[]', files[i])
                                }
                                console.log(files);

                                "Valid"==t?(
                                    //console.log(new FormData(o)),
                                    // form_elements = new FormData($('#kt_modal_section_app_form')[0]),
                                    // form_elements.append('sponsers[]', files),
                                    //form_elements = new FormData(o[0]),
                                    $.ajax({
                                        type: "POST",
                                        url: URLBase + "NewVersion_Events/ToDB",
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
                                                            window.location.replace(URLBase + "NewVersion_Events");
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
                $(o.querySelector('[name="teacher"]')).on("change",(function(){n[0].revalidateField("teacher")})),         
                
                n.push(
                    FormValidation.formValidation(
                        o,{
                            fields:{
                                title:{validators:{notEmpty:{message: Lang.required}}},
                                description:{validators:{notEmpty:{message: Lang.required}}},
                                teacher:{validators:{notEmpty:{message: Lang.required}}},
                                event_date:{validators:{notEmpty:{message: Lang.required}}},
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
                        {
                            fields:{
                                dbname:{validators:{notEmpty:{message:"Database name is required"}}},
                                dbengine:{validators:{notEmpty:{message:"Database engine is required"}}}
                            },
                            plugins:{
                                trigger:new FormValidation.plugins.Trigger,
                                bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})
                            }
                        }
                    )
                ),
                n.push(FormValidation.formValidation(
                    o,{
                        fields:{
                            card_name:{validators:{notEmpty:{message:"Name on card is required"}}},
                            card_number:{validators:{notEmpty:{message:"Card member is required"},creditCard:{message:"Card number is not valid"}}},
                            card_expiry_month:{validators:{notEmpty:{message:"Month is required"}}},
                            card_expiry_year:{validators:{notEmpty:{message:"Year is required"}}},
                            card_cvv:{
                                validators:{
                                    notEmpty:{message:"CVV is required"},
                                    digits:{message:"CVV must contain only digits"},
                                    stringLength:{min:3,max:4,message:"CVV must contain 3 to 4 digits only"}
                                }
                            }
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
                )),


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
                            parallelUploads:10,previewTemplate:n,maxFilesize:1,autoProcessQueue:!1,autoQueue:!1,previewsContainer:e+" .dropzone-items",
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
            )
        }
    }
}();
KTUtil.onDOMContentLoaded((function(){KTCreateApp.init()}));