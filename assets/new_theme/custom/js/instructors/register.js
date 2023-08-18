var InstructorsRegister = function () {
    var pageMethods = function (){
        $('#school').change(function () {
            GetFormElements();                           
        });     
        $('#country').change(function () {
            country_id = document.getElementById("country").value;
            $("#city1").html("");
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

                        $("#city1").html(html);
                    }
                }
            });
        });
        //refill the program drop down menu with study majors depending on the selected program
        $('#majors').change(function () {
            program_id = $("#majors").val();
            $("#stages").html("");
            $("#plans").html("");
            if (program_id != null){
                $.ajax({
                    type: "GET",
                    url: URLBase + "Admission_Employment/getMajors?id=" + JSON.stringify(program_id) ,
                    dataType: "JSON",                
                    success: function (response)
                    {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            alert('Error!');
                        } else if (response !== null && response.hasOwnProperty("Majors")) {
                            var Majors = response['Majors'];
                            var html = '<option value=""></option>';
                            $.each(Majors, function (index, value) {
                                html += '<option value="' + index + '">' + value + '</option>';
                            });
                            $("#stages").select2('val', 'All');
                            $("#stages").html(html);
                        }                     
                    }
                });   
            }         
        });

        $('#stages').change(function () {
            grade_id = $("#stages").val();  
            $("#plans").html("");
            if (grade_id != null){
                $.ajax({
                    type: "GET",
                    url: URLBase + "Admission_Employment/getPlans?id=" + JSON.stringify(grade_id),
                    dataType: "JSON",                
                    success: function (response)
                    {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            alert('Error!');
                        } else if (response !== null && response.hasOwnProperty("Plans")) {
                            var Majors = response['Plans'];
                            var html = '<option value=""></option>';
                            $.each(Majors, function (index, value) {
                                html += '<option value="' + index + '">' + value + '</option>';
                            });
                            $("#plans").select2('val', 'All');
                            $("#plans").html(html);
                        }                     
                    }
                });  
            }          
        });
    }

    var ValidateForm = function () {
        var register_employee = document.querySelector('#register_employee');

        register_employee.addEventListener('submit', function (event) {

            event.preventDefault();
            event.stopPropagation();
            if (register_employee.checkValidity()) {
                var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light-green.png";
                console.log("jjjjjjjjjj");
                var theForm = $('#register_employee');
                //upload files
                var data = new FormData(theForm[0]);
                data.append('membership_plan', theForm.attr("membership_plan"));
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Instructors_Memberships/AddEmployee",
                    data: data,
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#register_employee :input").prop("disabled", true);
                        $("#submit-buttons").addClass('hidden');
                    },
                    complete: function () {
                        //$.unblockUI();
                        $("#register_employee :input").prop("disabled", false);
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
                                title: "error",
                                text: stringerror,
                                icon: "error",
                                iconHtml: " <img src='" + imagem + "' >",
                            })
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            $('.Server_alerts').addClass('hidden');
                            var Success = response.Success;
                            $("#FCont").html(Success.HtmlMsg);
                            //App.scrollTo($("#FCont"), -200);

                            // var Success = response.Success;
                            // Swal.fire({
                            //     title: Success.title,
                            //     text: Success.HtmlMsg,
                            //     icon: "success",
                            //     iconHtml: " <img src='" + imagem + "' >",
                            // }).then((value) => {
                            //     window.location.href = Success.Redirect;
                            // });
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
            register_employee.classList.add('was-validated')
        }, false)
    }
  
    var GetFormElements = function () {
        var college_id = document.getElementById("school").value;
        var app_type = document.getElementById("app_type").value;
        var form3 = $('#register_employee');
        $("#plans").html("");
        $("#stages").html("");
        $("#majors").html("");
        $.ajax({
            type: "GET",
            url: URLBase + "Admission_Employment/getFields?id=" + college_id,
            dataType: "JSON",                
            success: function (response)
            {
                if (response !== null && response.hasOwnProperty("Errors")) {
                    alert('Error!');
                } else if (response !== null && response.hasOwnProperty("Rules")) {
                    var Rules = response['Rules'];
                    //console.log(app_type == 2);
                    $.each(Rules, function (index, value) {
                        if (index == "majors" || index == "programs" || index == "plans" || index == "courses"){
                            index = index + "\\[\\]";
                        }
                        var input_id = form3.find("[name=" + index + "]") ;
                            if(value !== "hidden" ){
                                //console.log(index + "---" + app_type);
                                if (index == "name_first_en" || index == "name_first_ar" || index == "mobile" || index == "img_path" || index == "id_no" || index == "gender" || index == "email" || index == "cv_file" || index == "country" || index == "city"){
                                    input_id.closest(".form-input").removeClass('hidden');
                                    if(value == "required"){
                                        input_id.prop('required',true);
                                        input_id.parent().addClass('has-error'); // set error class to the control group
                                    }else{
                                        input_id.prop('required',false);
                                        input_id.parent().removeClass('has-error'); // set error class to the control group
                                    }
                                }else if (index == "college"){
                                    if(app_type == "3"){
                                        input_id.prop('required',false);
                                        input_id.closest(".form-input").addClass('hidden');
                                        input_id.parent().removeClass('has-error'); // set error class to the control group
                                    }else{
                                        input_id.closest(".form-input").removeClass('hidden');
                                        if(value == "required"){
                                            input_id.prop('required',true);
                                            input_id.parent().addClass('has-error'); // set error class to the control group
                                        }else{
                                            input_id.prop('required',false);
                                            input_id.parent().removeClass('has-error'); // set error class to the control group
                                        }
                                    }
                                }else{
                                    if(app_type == "2"){
                                        input_id.closest(".form-input").removeClass('hidden');
                                        if(value == "required"){
                                            input_id.prop('required',true);
                                            input_id.parent().addClass('has-error'); // set error class to the control group
                                        }else{
                                            input_id.prop('required',false);
                                            input_id.parent().removeClass('has-error'); // set error class to the control group
                                        }
                                    }else{
                                        input_id.prop('required',false);
                                        input_id.closest(".form-input").addClass('hidden');
                                        input_id.parent().removeClass('has-error'); // set error class to the control group
                                    }                                    
                                }
                                if(index == "name_first_ar"){
                                    input_id.prop('pattern', "^[\u0621-\u064A- ]*$");
                                    input_id.parent().addClass('has-error'); // set error class to the control group
                                    // input_id.rules('add', {
                                    //     "arabiconly": true,
                                    //     "maxlength": 255
                                    // });
                                }
                                if(index == "name_first_en"){
                                    input_id.prop('pattern', "^[\a-zA-Z-]*$");
                                    input_id.parent().addClass('has-error'); // set error class to the control group
                                    // input_id.rules('add', {
                                    //     "englishonly": true,
                                    //     "maxlength": 255
                                    // });
                                }
                                if(index == "mobile"){
                                    input_id.prop('pattern', "^[\0-9]*$");
                                    input_id.parent().addClass('has-error'); // set error class to the control group
                                    // input_id.rules('add', {
                                    //     "number": true,
                                    //     "minlength": 10,
                                    //     "maxlength": 12
                                    // });
                                }
                                if(index == "id_no"){
                                    input_id.prop('pattern', "^[\0-9]*$");
                                    input_id.parent().addClass('has-error'); // set error class to the control group
                                    // input_id.rules('add', {
                                    //     "number": true,
                                    // });
                                }
                                if(index == "email"){
                                    input_id.prop('pattern', "^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$");
                                    //input_id.prop('pattern', "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i");
                                    input_id.parent().addClass('has-error'); // set error class to the control group
                                    // input_id.rules('add', {
                                    //     "email": true,
                                    // });
                                }
                                if(index == "img_path"){
                                    // input_id.rules('add', {
                                    //     'accept': "jpg|jpeg|png|gif",
                                    //     'filesize': 5 * 1000000, //B
                                    // });
                                }
                                if(index == "cv_file"){
                                    // input_id.rules('add', {
                                    //     'accept': "pdf|jpg|jpeg|png|gif|doc|docx",
                                    //     'filesize': 5 * 1000000, //B
                                    // });
                                }
                            }else{
                                if (app_type == "3"){
                                    input_id.prop('required',false);
                                    input_id.closest(".form-input").addClass('hidden');
                                    input_id.parent().removeClass('has-error'); // set error class to the control group
                                }else if(app_type == "1"){
                                    if(index != "college"){
                                        input_id.prop('required',false);
                                        input_id.closest(".form-input").addClass('hidden');
                                        input_id.parent().removeClass('has-error'); // set error class to the control group
                                    }
                                }else{
                                    if (index != "college" && index != "majors[]" && index != "programs[]" && index != "plans[]" && index != "courses[]"){
                                        input_id.prop('required',false);
                                        input_id.closest(".form-input").addClass('hidden');
                                        input_id.parent().removeClass('has-error'); // set error class to the control group
                                    }
                                }
                                
                            }
                      
                    });
                }
            }
        }); 
        $.ajax({
            type: "GET",
            url: URLBase + "Onlinereg/getBranches?id=" + college_id,
            dataType: "JSON",                
            success: function (response)
            {
                if (response !== null && response.hasOwnProperty("Errors")) {
                    alert('Error!');
                } else if (response !== null && response.hasOwnProperty("Branches")) {
                    var Branches = response['Branches'];
                    var html = '<option value="">select</option>';
                    $.each(Branches, function (index, value) {
                        html += '<option value="' + index + '">' + value + '</option>';
                    });
                    //$("#majors").select2('val', 'All');
                    $("#majors").html(html);
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
    InstructorsRegister.init();
});