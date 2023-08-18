var GeneralFunctions = function () {

    var PageMethods = function (){
        $("#send_token_button").on("click", function () {
            var user_email = $("#user_email").val();
            if(user_email != ""){
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Login/SendResetPasswordToken",
                    data: {email: user_email},
                    //async: false,
                    //cache: false,
                    //contentType: false,
                    //processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#Reset_Password_Modal :input").prop("disabled", true);
                        $("#send_token_div").addClass('hidden');
                    },
                    complete: function () {
                        $("#Reset_Password_Modal :input").prop("disabled", false);
                        $("#send_token_div").removeClass('hidden');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal(Lang.Error, xhr.status + " - " + thrownError, "error");
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
                            })
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            Success = response.Success;
                            Swal.fire({
                                title: Lang.Done,
                                text: response.Success_msg,
                                icon: "success",
                                iconHtml: " <img src='" + imagem + "' >",
                            }).then((value) => {
                                if (response.Success === 'redirect') {
                                    if (response.hasOwnProperty("redirect_url")) {
                                        window.location.replace(response.redirect_url);
                                    } else {
                                        location.reload();
                                    }
                                }
                            });
                        } 
                    }
                });

            }else{
                Swal.fire({
                    title: Lang.Error,
                    text: "enter_your_email",
                    icon: "error",
                    iconHtml: " <img src='" + URLBase + "assets/new_theme/assets/img/shape/shape-light.png" + "' >",
                })
            }

        });
        
    }


    var LoginModal = function () {
        var Login_Modal = document.querySelector('#Login_Modal');
        Login_Modal.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (Login_Modal.checkValidity()) {
                var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
                console.log("jjjjjjjjjj");
                var theForm = $('#Login_Modal');
                //upload files
                var data = new FormData(theForm[0]);
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Login/ProcessLogin",
                    data: data,
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#Login_Modal :input").prop("disabled", true);
                        $("#submit-buttons").addClass('hidden');
                    },
                    complete: function () {
                        $("#Login_Modal :input").prop("disabled", false);
                        $("#submit-buttons").removeClass('hidden');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal(Lang.Error, xhr.status + " - " + thrownError, "error");
                    },
                    success: function (response) {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            var obj = response["Errors"];
                            var stringerror = '';
                            for (var prop in obj) {
                                stringerror += '* ' + obj[prop] + '</br>';
                            }
                            $('#Server_alerts').html(obj);
                            $('.Server_alerts').removeClass('hidden');
                            // Swal.fire({
                            //     title: obj,
                            //     text: "",
                            //     icon: "error",
                            //     iconHtml: " <img src='" + imagem + "' >",
                            // })
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            Success = response.Success;
                            $('.Server_alerts').addClass('hidden');
                            $('#Login_Modal').html("<div class='alert alert-success'>" + response.Success_msg + "</div>");
                            if (response.Success === 'redirect') {
                                if (response.hasOwnProperty("redirect_url")) {
                                    window.location.replace(response.redirect_url);
                                } else {
                                    location.reload();
                                }
                            }
                            //Success = response.Success;
                            // Swal.fire({
                            //     title: Lang.Done,
                            //     text: response.Success_msg,
                            //     icon: "success",
                            //     iconHtml: " <img src='" + imagem + "' >",
                            // }).then((value) => {
                            //     if (response.Success === 'redirect') {
                            //         if (response.hasOwnProperty("redirect_url")) {
                            //             window.location.replace(response.redirect_url);
                            //         } else {
                            //             location.reload();
                            //         }
                            //     }
                            // });
                        }
                    }
                });
            }
            Login_Modal.classList.add('was-validated')
        }, false)
    }


//     var LoginModal = function () {
//         // for more info visit the official plugin documentation:
//         // http://docs.jquery.com/Plugins/Validation

//         var form3 = $('#Login_Modal');
//         form3.validate({
//             lang: Lang.theLang,
//             errorElement: 'span', //default input error message container
//             errorClass: 'help-block help-block-error', // default input error message class
//             focusInvalid: false, // do not focus the last invalid input
//             ignore: "", // validate all fields including form hidden input
//             rules: {
//                 username: {
//                     required: true,
//                 },
//                 password: {
//                     required: true,
//                 },
//             },
//             messages: {// custom messages for radio buttons and checkboxes

//             },
//             errorPlacement: function (error, element) { // render error placement for each input type
                
//                 error.insertAfter(element); // for other inputs, just perform default behavior
                
//             },
//             invalidHandler: function (event, validator) { //display error alert on form submit
//                 // success3.addClass('hidden');
//                 // error3.removeClass('hidden');

//                 $("html,body").animate({scrollTop: $('#Login_Modal').offset().top - 100}, "slow");
//                 // $(".box-content").animate({ scrollTop: 0 }, "slow");
//             },
//             highlight: function (element) { // hightlight error inputs
//                 $(element).parent().addClass('has-error'); // set error class to the control group
//             },
//             unhighlight: function (element) { // revert the change done by hightlight
//                 $(element).parent().removeClass('has-error'); // set error class to the control group
//             },
//             success: function (label) {
//                 label.parent().removeClass('has-error'); // set success class to the control group
//             },
//             submitHandler: function (form) {
//                 //success3.addClass('hidden');
//                 //error3.addClass('hidden');
//                 var theForm = $('#Login_Modal');
//                 //upload files
//                 var data = new FormData(theForm[0]);

// //                jQuery.each(jQuery('#file')[0].files, function (i, file) {
// //                    data.append('file-' + i, file);
// //                });
//                 $.ajax({
//                     type: "POST",
//                     url: URLBase + "Login/ProcessLogin",
//                     data: data,
//                     //async: false,
//                     cache: false,
//                     contentType: false,
//                     processData: false,
//                     dataType: "JSON",
//                     beforeSend: function () {
//                         // $.blockUI({
//                         //     message: Lang.Please_wait,
//                         //     css: {
//                         //         border: 'none',
//                         //         padding: '15px',
//                         //         backgroundColor: '#000',
//                         //         '-webkit-border-radius': '10px',
//                         //         '-moz-border-radius': '10px',
//                         //         opacity: .5,
//                         //         color: '#fff'
//                         //     }});
//                         $("#Login_Modal :input").prop("disabled", true);
//                         //$("#submit-buttons").addClass('hidden');
//                     },
//                     complete: function () {
//                         $.unblockUI();
//                         $("#Login_Modal :input").prop("disabled", false);
//                         //$("#submit-buttons").removeClass('hidden');
//                     },
//                     success: function (response) {
//                         // error3.addClass('hidden');
//                         // success3.addClass('hidden');

//                         if (response !== null && response.hasOwnProperty("Errors")) {
//                             //console.log(response['Errors']);
//                             var obj = response["Errors"];

//                             // $('#Server_alerts').html(obj);
//                             // $('.Server_alerts').removeClass('hidden');

//                         } else if (response !== null && response.hasOwnProperty("Success")) {

//                             //$('.Server_alerts').addClass('hidden');
//                             Success = response.Success;
//                             $('#Login_Modal').html("<div class='alert alert-success'>" + response.Success_msg + "</div>");
//                             if (response.Success === 'redirect') {
//                                 if (response.hasOwnProperty("redirect_url")) {
//                                     window.location.replace(response.redirect_url);
//                                 } else {
//                                     location.reload();
//                                 }
//                             }


//                         }
//                     }
//                 });
//                 //form[0].submit(); // submit the form
//             }

//         });
//     }


    var initValidation = function () {


        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        var imagem=URLBase + "assets/new_theme/assets/img/shape/shape-light.png";

                        event.preventDefault();
                        event.stopPropagation();
                        Swal.fire({
                            title: 'Do you want to save the changes?',
                            iconHtml:" <img src='"+imagem+"' >",
                             showDenyButton: true,
                            showCancelButton: true,
                            content:true,
                            confirmButtonText: 'Save',
                            denyButtonText: `Don't save`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Saved Success!",
                                    text: "",
                                    icon: "success",
                                    iconHtml:" <img src='"+imagem+"' >",
                                })

                            } else if (result.isDenied) {

                                Swal.fire({
                                    title: "Changes are not saved",
                                    text: "",
                                    icon: "error",
                                    iconHtml:" <img src='"+imagem+"' >",
                                })

                            }
                        });
                    }
                    else
                    {

                    }

                    form.classList.add('was-validated')
                }, false)
            })
    }




    return {
        //main function to initiate the module
        init: function () {
            LoginModal();
            //initValidation();
            PageMethods();
        }

    };

}();

jQuery(document).ready(function () {
    GeneralFunctions.init();
    $('#tbl_last_event').DataTable(
        {
            searching: false, info: true,"lengthChange": false,"pageLength": 3, "bPaginate": true,"ordering": false,
            language: {
                oPaginate: {
                    sNext: '<i class="fa fa-forward"></i>',
                    sPrevious: '<i class="fa fa-backward"></i>',
                    sFirst: '<i class="fa fa-step-backward"></i>',
                    sLast: '<i class="fa fa-step-forward"></i>',
                },
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]    
            }
        });
    $('#tbl_last_event_info').html('<a class="edo-theme-btn" href="' + URLBase + 'NewHome_Events">see all events</a>');
    var parentElement = $(".select-postion");
    $('.select2').select2(
        {
            tags: true,
            //dropdownParent: $("#SignUpModal"),
            // dropdownParent: parentElement,
            width: 'resolve' // need to override the changed default
        }
    );
    $(".cdCountry").select2({
        placeholder: "Select a country",
        templateResult: formatState,
        templateSelection: formatSelection,
    });

    $('#icon_search_header').click(function(){
        $(this).addClass('d-none');
        $('#header_search_mobile').css('width',"0px");
        $('#header_search_mobile').removeClass('d-none');
        $("#header_search_mobile").animate( {
            opacity: '1',
            width: '135px'}, 1000);

        $( "#text-search-mobile" ).trigger( "focus" );
    });

    $('#text-search-mobile').blur(function(){

        $('#header_search_mobile').addClass('d-none');
        $('#icon_search_header').removeClass('d-none');
    });


    function formatSelection(country) {
        var baseUrl = URLBase + "assets/new_theme/assets/img/flags";
        var $country = $(
            '<span class="country-flag"><img src="' + baseUrl + '/' + country.element.value.toLowerCase() + '.svg" class="img-flag" /> ' + country.text + '</span>'
        );
        return $country;
    }
    function formatState (country) {
        if (!country.id) {
            return country.text;
        }
        var baseUrl = URLBase + "assets/new_theme/assets/img/flags";
        var $country = $(
            '<span class="country-flag"><img src="' + baseUrl + '/' + country.element.value.toLowerCase() + '.svg" class="img-flag" /> ' + country.text + '</span>'
        );
        return $country;
    };




});