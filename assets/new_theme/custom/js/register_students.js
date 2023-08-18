var ValidationFunctions = function () {


    var pageMethods = function () {
        $('#select_school', register_student).change(function () {
            GetFormElements();
        });
        // var city_instance;
        // if($('#select_city').is('[searchable]')){
        //      city_instance=NiceSelect.bind(document.getElementById("select_city"), {searchable: true, placeholder: 'اختر ', searchtext: 'ابحث...'});

        // } else {
        //      city_instance=NiceSelect.bind(document.getElementById("select_city"),{placeholder: 'اختر '});

        // }

        $('#select_country').change(function () {
            country_id = document.getElementById("select_country").value;
            $("#select_city").html("");
            $.ajax({
                type: "GET",
                url: URLBase + "Center/getCities?id=" + country_id,
                dataType: "JSON",
                success: function (response) {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        alert('Error!');
                    } else if (response !== null && response.hasOwnProperty("Cities")) {
                        var Cities = response['Cities'];
                        var html = '<option value="">select</option>';
                        $.each(Cities, function (index, value) {
                            html += '<option value="' + index + '">' + value + '</option>';
                        });

                        $("#select_city").html(html);
                        //city_instance.update();
                    }
                }
            });
        });
        $('#select_branch').change(function () {
            program_id = document.getElementById("select_branch").value;
            $("#select_grade").html("");
            $("#select_stage").html("");
            $.ajax({
                type: "GET",
                url: URLBase + "Onlinereg/getMajors?id=" + program_id,
                dataType: "JSON",
                success: function (response) {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        alert('Error!');
                    } else if (response !== null && response.hasOwnProperty("Majors")) {
                        var Majors = response['Majors'];
                        var html = '<option value="">select</option>';
                        $.each(Majors, function (index, value) {
                            html += '<option value="' + index + '">' + value + '</option>';
                        });
                        $("#select_stage").html(html);
                    }
                }
            });
        });
        $('#select_stage', register_student).change(function () {
            grade_id = document.getElementById("select_stage").value;
            $("#select_grade").html("");
            $.ajax({
                type: "GET",
                url: URLBase + "Onlinereg/getPlans?id=" + grade_id,
                dataType: "JSON",
                success: function (response) {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        alert('Error!');
                    } else if (response !== null && response.hasOwnProperty("Plans")) {
                        var Majors = response['Plans'];
                        var html = '<option value="">select</option>';
                        $.each(Majors, function (index, value) {
                            html += '<option value="' + index + '">' + value + '</option>';
                        });
                        $("#select_grade").html(html);
                    }
                }
            });
        });
    }
    var ValidateForm = function () {

        var register_student = document.querySelector('#register_student');

        register_student.addEventListener('submit', function (event) {

            event.preventDefault();
            event.stopPropagation();
            if (register_student.checkValidity()) {
                var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
                console.log("jjjjjjjjjj");
                var theForm = $('#register_student');
                //upload files
                var data = new FormData(theForm[0]);
                data.append('par1', theForm.attr("par1"));
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewVersion_Registeration/AddStudent",
                    data: data,
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#register_student :input").prop("disabled", true);
                        $("#submit-buttons").addClass('hidden');
                    },
                    complete: function () {
                        //$.unblockUI();
                        $("#register_student :input").prop("disabled", false);
                        $("#submit-buttons").removeClass('hidden');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal(Lang.Error, xhr.status + " - " + thrownError, "error");
                    },
                    success: function (response) {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            console.log(response['Errors']);
                            var obj = response["Errors"];
                            var stringerror = '';
                            for (var prop in obj) {
                                stringerror += '* ' + obj[prop] + '</br>';
                            }
                            Swal.fire({
                                title: stringerror,
                                text: "",
                                icon: "error",
                                iconHtml: " <img src='" + imagem + "' >",
                            })
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            $('.Server_alerts').addClass('hidden');
                            var Success = response.Success;
                            Swal.fire({
                                title: Success.title,
                                text: Success.HtmlMsg,
                                icon: "success",
                                iconHtml: " <img src='" + imagem + "' >",
                            }).then((value) => {
                                window.location.href = Success.Redirect;
                            });
                        } else if (response !== null && response.hasOwnProperty("SuccessR")) {
                            var Success = response.SuccessR;
                            Swal.fire({
                                title: Success.title,
                                text: Success.content,
                                icon: "success",
                                iconHtml: " <img src='" + imagem + "' >",
                            }).then((value) => {
                                window.location.href = Success.Redirect;
                            });
                        }
                    }
                });
            }
            register_student.classList.add('was-validated')
        }, false)


        // var register_student = $('#register_student');
        // if (register_student.length) {
        //     // for more info visit the official plugin documentation: 
        //     // http://docs.jquery.com/Plugins/Validation

        //     var error3 = $('.alert-danger', register_student);
        //     var success3 = $('.alert-success', register_student);
        //     register_student.validate({
        //         lang: Lang.theLang,
        //         onkeyup: function (element) {
        //             this.element(element);  // <- "eager validation"
        //         },
        //         onfocusout: function (element) {
        //             this.element(element);  // <- "eager validation"
        //         },
        //         errorElement: 'span', //default input error message container
        //         errorClass: 'help-block help-block-error', // default input error message class
        //         focusInvalid: false, // do not focus the last invalid input
        //         ignore: "", // validate all fields including form hidden input
        //         //rules: App.GetFormRules("Onlinereg/StepRules", {par1: StepA_Form.attr("par1"), par2: 'A'}),
        //         messages: {// custom messages for radio buttons and checkboxes

        //         },
        //         errorPlacement: function (error, element) { // render error placement for each input type
        //             {
        //                 error.insertAfter(element); // for other inputs, just perform default behavior
        //             }
        //         },
        //         invalidHandler: function (event, validator) { //display error alert on form submit   
        //             success3.addClass('hidden');
        //             error3.removeClass('hidden');

        //             $("html,body").animate({scrollTop: $('#register_student').offset().top - 100}, "slow");
        //             // $(".box-content").animate({ scrollTop: 0 }, "slow");
        //         },
        //         highlight: function (element) { // hightlight error inputs
        //             $(element).parent().addClass('has-error'); // set error class to the control group
        //         },
        //         unhighlight: function (element) { // revert the change done by hightlight
        //             $(element).parent().removeClass('has-error'); // set error class to the control group
        //         },
        //         success: function (label) {
        //             label.parent().removeClass('has-error'); // set success class to the control group
        //         },
        //         submitHandler: function (form) {
        //             success3.addClass('hidden');
        //             error3.addClass('hidden');
        //             var theForm = $('#register_student');
        //             //upload files
        //             var data = new FormData(theForm[0]);

        //             data.append('par1', register_student.attr("par1"));
        //             $.ajax({
        //                 type: "POST",
        //                 url: URLBase + "NewVersion_Registeration/AddStudent",
        //                 data: data,
        //                 //async: false,
        //                 cache: false,
        //                 contentType: false,
        //                 processData: false,
        //                 dataType: "JSON",
        //                 beforeSend: function () {
        //                     // $.blockUI({
        //                     //     message: Lang.Please_wait,
        //                     //     css: {
        //                     //         border: 'none',
        //                     //         padding: '15px',
        //                     //         backgroundColor: '#000',
        //                     //         '-webkit-border-radius': '10px',
        //                     //         '-moz-border-radius': '10px',
        //                     //         opacity: .5,
        //                     //         color: '#fff'
        //                     //     }});
        //                     $("#register_student :input").prop("disabled", true);
        //                     $("#submit-buttons").addClass('hidden');
        //                 },
        //                 complete: function () {
        //                     //$.unblockUI();
        //                     $("#register_student :input").prop("disabled", false);
        //                     $("#submit-buttons").removeClass('hidden');
        //                 },
        //                 error: function (xhr, ajaxOptions, thrownError) {
        //                     swal(Lang.Error, xhr.status + " - " + thrownError, "error");
        //                 },
        //                 success: function (response)
        //                 {
        //                     error3.addClass('hidden');
        //                     success3.addClass('hidden');

        //                     if (response !== null && response.hasOwnProperty("Errors")) {
        //                         console.log(response['Errors']);
        //                         var obj = response["Errors"];
        //                         var stringerror = '';
        //                         for (var prop in obj) {
        //                             stringerror += '* ' + obj[prop] + '</br>';
        //                         }
        //                         swal(Lang.Error, stringerror , "error");
        //                         // $('#Server_alerts').html(stringerror);
        //                         // $('.Server_alerts').removeClass('hidden');
        //                     } else if (response !== null && response.hasOwnProperty("Success")) {
        //                         $('.Server_alerts').addClass('hidden');
        //                         var Success = response.Success;
        //                         swal({
        //                             title: Success.title,
        //                             text: Success.HtmlMsg,
        //                             icon: "success",
        //                             button: Lang.OK,
        //                         }).then((value) => {
        //                             window.location.href = Success.Redirect;
        //                         });
        //                     } else if (response !== null && response.hasOwnProperty("SuccessR")) {
        //                         var Success = response.SuccessR;
        //                         swal({
        //                             title: Success.title,
        //                             text: Success.content,
        //                             icon: "success",
        //                             button: Lang.OK,
        //                         }).then((value) => {
        //                             window.location.href = Success.Redirect;
        //                         });
        //                     }
        //                 }
        //             });
        //             //form[0].submit(); // submit the form
        //         }

        //     });

        //     //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        //     $('.selectme', register_student).change(function () {
        //         register_student.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        //     });

        //     //refill the program drop down menu with study majors depending on the selected program
        //     $('#select_school', register_student).change(function () {
        //         GetFormElements();                           
        //     });

        //     //refill the program drop down menu with study majors depending on the selected program
        //     $('#select_branch', register_student).change(function () {
        //         program_id = document.getElementById("select_branch").value;
        //         $("#select_grade").html("");
        //         $("#select_stage").html("");
        //         $.ajax({
        //             type: "GET",
        //             url: URLBase + "Onlinereg/getMajors?id=" + program_id,
        //             dataType: "JSON",                
        //             success: function (response)
        //             {
        //                 if (response !== null && response.hasOwnProperty("Errors")) {
        //                     alert('Error!');
        //                 } else if (response !== null && response.hasOwnProperty("Majors")) {
        //                     var Majors = response['Majors'];
        //                     var html = '<option value="">select</option>';
        //                     $.each(Majors, function (index, value) {
        //                         // $('#select_stage').append($('<option>', { 
        //                         //     value: index,
        //                         //     text : value 
        //                         // }));
        //                         html += '<option value="' + index + '">' + value + '</option>';
        //                     });
        //                     //$("#select_stage").select2('val', 'All');
        //                     $("#select_stage").html(html);
        //                 }                     
        //             }
        //         });            
        //     });

        //     $('#select_stage', register_student).change(function () {
        //         grade_id = document.getElementById("select_stage").value;
        //         $("#select_grade").html("");
        //         $.ajax({
        //             type: "GET",
        //             url: URLBase + "Onlinereg/getPlans?id=" + grade_id,
        //             dataType: "JSON",                
        //             success: function (response)
        //             {
        //                 if (response !== null && response.hasOwnProperty("Errors")) {
        //                     alert('Error!');
        //                 } else if (response !== null && response.hasOwnProperty("Plans")) {
        //                     var Majors = response['Plans'];
        //                     var html = '<option value="">select</option>';
        //                     $.each(Majors, function (index, value) {
        //                         html += '<option value="' + index + '">' + value + '</option>';
        //                     });
        //                     //$("#select_grade").select2('val', 'All');
        //                     $("#select_grade").html(html);
        //                 }                     
        //             }
        //         });            
        //     });

        //     //refill the program drop down menu with study cities depending on the selected program
        //     $('#select_country', register_student).change(function () {
        //         console.log("kkkkkkk");
        //         country_id = document.getElementById("select_country").value;
        //         $("#select_city").html("");
        //         $.ajax({
        //             type: "GET",
        //             url: URLBase + "Center/getCities?id=" + country_id,
        //             dataType: "JSON",                
        //             success: function (response)
        //             {
        //                 if (response !== null && response.hasOwnProperty("Errors")) {
        //                     alert('Error!');
        //                 } else if (response !== null && response.hasOwnProperty("Cities")) {
        //                     var Cities = response['Cities'];
        //                     var html = '<option value="">select</option>';
        //                     $.each(Cities, function (index, value) {
        //                         html += '<option value="' + index + '">' + value + '</option>';
        //                     });
        //                     //$("#select_city").select2('val', 'All');
        //                     $("#select_city").html(html);
        //                 }                     
        //             }
        //         });            
        //     });

        //}
    }


    var GetFormElements = function () {
        college_id = document.getElementById("select_school").value;
        var register_student = $('#register_student');
        $("#select_grade").html("");
        $("#select_stage").html("");
        $("#select_branch").html("");

        $.ajax({
            type: "GET",
            url: URLBase + "NewVersion_Registeration/getFields?id=" + college_id,
            dataType: "JSON",
            success: function (response) {
                if (response !== null && response.hasOwnProperty("Errors")) {
                    alert('Error!');
                } else if (response !== null && response.hasOwnProperty("Rules")) {
                    var Rules = response['Rules'];
                    $.each(Rules, function (index, value) {
                        var input_id = register_student.find("[name=" + index + "]");
                        if (value !== "hidden") {
                            input_id.closest(".signup-wrapper").removeClass('hidden');
                            if (value.includes('required')) {
                                input_id.prop('required', true);
                                input_id.parent().addClass('has-error'); // set error class to the control group
                            } else {
                                input_id.prop('required', false);
                                input_id.parent().removeClass('has-error'); // set error class to the control group
                            }

                            if (index == "Full_Name_Arabic") {
                                input_id.prop('pattern', "^[\u0621-\u064A- ]*$");
                                input_id.parent().addClass('has-error'); // set error class to the control group

                            }
                            if (index == "Full_Name_English") {

                            }
                            if (index == "Phone" || index == "Mobile") {

                            }
                            if (index == "ID_No") {

                            }
                            if (index == "Email") {

                            }
                        } else {
                            input_id.prop('required', false);
                            input_id.closest(".signup-wrapper").addClass('hidden');
                            input_id.parent().removeClass('has-error'); // set error class to the control group
                        }

                    });
                }
            }
        });
        $.ajax({
            type: "GET",
            url: URLBase + "Onlinereg/getBranches?id=" + college_id,
            dataType: "JSON",
            success: function (response) {
                if (response !== null && response.hasOwnProperty("Errors")) {
                    alert('Error!');
                } else if (response !== null && response.hasOwnProperty("Branches")) {
                    var Branches = response['Branches'];
                    var html = '<option value="">select</option>';
                    $.each(Branches, function (index, value) {
                        html += '<option value="' + index + '">' + value + '</option>';
                    });
                    //$("#select_branch").select2('val', 'All');
                    $("#select_branch").html(html);
                }
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            GetFormElements();
            pageMethods();
            ValidateForm();
        }

    };

}();


jQuery(document).ready(function () {
    ValidationFunctions.init();
});