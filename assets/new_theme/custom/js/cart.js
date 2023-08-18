var cartMethods = function () {

    var demo1 = function () {
        $(".delete_section").on("click", function (e) {
            e.preventDefault();
            var el = $(this);
            var student_section_id = el.attr('student-section-id');
            var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
            Swal.fire({
                title: el.attr('Dtitle'),
                text: el.attr('Dcontent'),
                icon: "error",
                iconHtml: " <img src='" + imagem + "' >",
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: `Cancel`,
                confirmButtonColor: '#d33',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: URLBase + "NewHome_Sections_Register/DeleteSection",
                        data: {student_section_id: student_section_id},
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
                                    if (response.hasOwnProperty("redirect")) {
                                        window.location.replace(response.redirect);
                                    }                                    
                                });
                            } else if (response !== null && response.hasOwnProperty("Success")) {
                                Success = response.Success;
                                Swal.fire({
                                    title: Success.title,
                                    text: Success.content,
                                    icon: "success",
                                    iconHtml: " <img src='" + imagem + "' >",
                                }).then((value) => {
                                    if (Success.hasOwnProperty("redirect")) {
                                        window.location.replace(Success.redirect);
                                    } else {
                                        location.reload();
                                    }                                    
                                });
                            }
    
                        }
                    });
                } 
                // else if (result.isDismissed) {
                //   Swal.fire('Changes are not saved', '', 'info')
                // }
            })  
        });       
    };

    return {
        //main function to initiate the module
        init: function () {
            demo1();
        }
    };
}();

jQuery(document).ready(function () {
    cartMethods.init();
});


