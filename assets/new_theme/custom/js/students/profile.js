var ProfileMethods = function () {

var demo1 = function () {
    $(".toggle-wishlist").on("click", function (e) {
        e.preventDefault();
        var el = $(this);
        var section_id = el.attr('section-id');
        var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
        $.ajax({
            type: "POST",
            url: URLBase + "NewHome_Students_Sections/ToggleSectionFromWishlist",
            data: {section_id: section_id},
            dataType: "JSON",
            beforeSend: function () {
                el.prop("disabled", true);
                //el.addClass('hidden');
            },
            complete: function () {
                el.prop("disabled", false);
                //el.removeClass('hidden');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                swal('Error', xhr.status + " - " + thrownError, "error");
            },
            success: function (response) {
                if (response !== null && response.hasOwnProperty("Errors")) {
                    var obj = response["Errors"];
                    var stringerror = '';
                    for (var prop in obj) {
                        stringerror += '* ' + obj[prop] + '</br>';
                    }
                    Swal.fire({
                        title: obj,
                        text: "",
                        icon: "error",
                        iconHtml: " <img src='" + imagem + "' >",
                    }).then((value) => {
                                                            
                    });
                } else if (response !== null && response.hasOwnProperty("Success")) {
                    Success = response.Success;
                    Swal.fire({
                        title: Success.title,
                        text: Success.content,
                        icon: "success",
                        iconHtml: " <img src='" + imagem + "' >",
                    }).then((value) => {
                        if(Success.status == "add"){
                            el.addClass('w-added');
                        }else{
                            el.removeClass('w-added');
                        }                        
                    });
                }

            }
        });
    });       
};

var PageMethods = function (){
    FormValidation('personal_data', 'save_personal_data');
    FormValidation('change_password', 'save_password');
    FormValidation('additiona_settings', 'save_details');
}

function checkValidations(form_id){
    var result = {'status': true};
    if(form_id == "change_password"){
        var new_password = $('#new_password').val();
        var confirm_new_password = $('#confirm_new_password').val();
        if(new_password.length < 6){
            result['status'] = false;
            result['error_msg'] = "password must be greater than 6 characters";
        }else if(new_password !== confirm_new_password){
            result['status'] = false;
            result['error_msg'] = "password confirmation is incorrect";
        }
    }
    console.log(new_password);
    console.log(confirm_new_password);

    return result
}

var FormValidation = function (form_id, submit_button_id){

    var Form1 = document.querySelector('#' + form_id);
    Form1.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var customValidations = checkValidations(form_id);
        if (Form1.checkValidity() && customValidations['status'] == true) {
            var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
            var theForm = $('#'+form_id);
            //upload files
            var data = new FormData(theForm[0]);
            data.append('par1', theForm.attr("par1"));
            $.ajax({
                type: "POST",
                url: URLBase + "NewHome_Students_Profile/Todb",
                data: data,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#" + form_id + " :input").prop("disabled", true);
                    $("#"+submit_button_id).addClass('hidden');
                },
                complete: function () {
                    //$.unblockUI();
                    $("#" + form_id + " :input").prop("disabled", false);
                    $("#"+submit_button_id).removeClass('hidden');
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
                            text: Success.content,
                            icon: "success",
                            iconHtml: " <img src='" + imagem + "' >",
                        }).then((value) => {
                        });
                    } 
                }
            });
        }else{
            Swal.fire({
                title: Lang.Error,
                text: customValidations['error_msg'],
                icon: "error",
                iconHtml: " <img src='" + imagem + "' >",
            })
        }
        Form1.classList.add('was-validated')
    }, false)
}

return {
    //main function to initiate the module
    init: function () {
        demo1();
        PageMethods();
    }
};
}();

jQuery(document).ready(function () {
    ProfileMethods.init();
});


