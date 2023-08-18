(function ($) {
    ("use strict");

    var windowOn = $(window);
    ////////////////////////////////////////////////////
    // 01. PreLoader Js
    windowOn.on("load", function () {
        $("#loading").fadeOut(500);
    });

    // 28. user-btn-active

    $(".display_video").on("click", function () {
        var video_path = $(this).attr("link");
        $("#video_src").attr("src", video_path);
        $("#show_file video")[0].load();
        $("#display_content").addClass("open");
        $(".offcanvas-overlay").addClass("overlay-open");
    });
    $(".offcanvas-overlay").on("click", function () {
        $(".video-area").removeClass("open");
        $(".offcanvas-overlay").removeClass("overlay-open");
    });

    $(".toggle-wishlist").on("click", function () {
        var section_id = $(this).attr("section-id");
        Swal.fire({
            title: $(this).attr("section_id"),
            text: "",
            icon: "error",
            iconHtml: " <img src='" + URLBase + "assets/new_theme/assets/img/shape/shape-light.png" + "' >",
        })
        console.log(section_id);
    });

})(jQuery);

function OpenExam(obj) {

    $.ajax({
        url: URLBase + 'NewHome_Sections/getExamFromModal',
        type: 'post',
        data: {par1: $(obj).attr('data-exam-id')},
        cache: false,
        beforeSend: function () {
            // alert('');
            var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
            loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
            loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
            loading += ' </div>';

            // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
            $('#modalTabContent').html(loading);
        }, success: function (response) {
            // Add response in Modal body


            $(obj).closest('.product__modal').css('max-width', '90%');
            $(obj).closest('.product__modal').css('padding-top', '20px');
            $(obj).closest('.product__modal').removeClass('modal-fullscreen');
            $(obj).closest('.product__modal-wrapper').css('padding', '20px');
            $(obj).closest('.product__modal').css('padding', '20px');
            //$('#display_file').html($(obj).closest('.product-items').html());
            //  $('#display_file').find('.pdf_viewer').css('height', '511px');


            $('#modalTabExamContent').html(response);
            $('.previous').hide();
            $('#modalTabExamContent').css('display', 'block');

            //$(obj).closest('.product__modal').css('max-width', '100%');
            //$(obj).closest('.product__modal').css('height', 'auto');
            //$(obj).closest('.product__modal').css('padding-top', '20px');
            //$(obj).closest('.product__modal').addClass('modal-fullscreen');

            // $(obj).closest('.product__modal-wrapper').css('padding', '0px');
            // $(obj).closest('.product__modal').css('padding', '0px');

            $('#all_file_details').hide();

            handleValidation_exam();
            // var elem = document.documentElement;
            // if (elem.requestFullscreen) {
            //     elem.requestFullscreen();
            // } else if (elem.msRequestFullscreen) {
            //     elem.msRequestFullscreen();
            // } else if (elem.mozRequestFullScreen) {
            //     elem.mozRequestFullScreen();
            // } else if (elem.webkitRequestFullscreen) {
            //     elem.webkitRequestFullscreen();
            // }


        }
    });
}

function handleValidation_exam() {
    $("#exam_form").validate({
        // Specify validation rules
        errorClass: "error",
        rules: {
            // The key name on the left side is the name attribute
            // of an input field. Validation rules are defined
            // on the right side


        },
        errorPlacement: function (error, element) { // render error placement for each input type

            if (element.attr('type') == 'radio') {
                error.insertAfter(element.parents(".clearfix"));
            } else {
                error.insertAfter(element);
            }
            return true;
        },
        // Specify validation error messages
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function (form) {
            alert('aaa');
            if (!this.beenSubmitted) {
                this.beenSubmitted = true;
                ord_questions();
                form.submit();
            }

        }
    });
}

function finish_exam(obj) {

    $("#exam_form").valid();

    $.ajax({
        url: URLBase + 'NewHome_Sections/getResults',
        type: 'post',
        data: $('#exam_form').serialize(),
        cache: false,
        beforeSend: function () {
            handleValidation();
            // alert('');
            var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
            loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
            loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
            loading += ' </div>';

            // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
            $('#modalTabContent').html(loading);
        }, success: function (response) {
            // Add response in Modal body


            $(obj).closest('.modal-fullscreen').css('height', '100vh');
            // $(obj).closest('.product__modal').css('padding-top', '20px');
            // $(obj).closest('.product__modal').removeClass('modal-fullscreen');
            // $(obj).closest('.product__modal-wrapper').css('padding', '20px');
            // $(obj).closest('.product__modal').css('padding', '20px');
            //$('#display_file').html($(obj).closest('.product-items').html());
            //  $('#display_file').find('.pdf_viewer').css('height', '511px');


            $('#modalTabExamContent').html(response);
            $('#modalTabExamContent').css('display', 'block');

            //$(obj).closest('.product__modal').css('max-width', '100%');
            //$(obj).closest('.product__modal').css('height', 'auto');
            //$(obj).closest('.product__modal').css('padding-top', '20px');
            //$(obj).closest('.product__modal').addClass('modal-fullscreen');

            // $(obj).closest('.product__modal-wrapper').css('padding', '0px');
            // $(obj).closest('.product__modal').css('padding', '0px');

            $('#all_file_details').hide();


            // var elem = document.documentElement;
            // if (elem.requestFullscreen) {
            //     elem.requestFullscreen();
            // } else if (elem.msRequestFullscreen) {
            //     elem.msRequestFullscreen();
            // } else if (elem.mozRequestFullScreen) {
            //     elem.mozRequestFullScreen();
            // } else if (elem.webkitRequestFullscreen) {
            //     elem.webkitRequestFullscreen();
            // }


        }
    });
}


function OpenExamResult(obj) {
    $.ajax({
        url: URLBase + 'NewHome_Sections/Answers',
        type: 'post',
        data: {par1: $(obj).attr('data-exam-id')},
        cache: false,
        beforeSend: function () {
            handleValidation();
            // alert('');
            var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
            loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
            loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
            loading += ' </div>';

            // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
            $('#modalTabContent').html(loading);
        }, success: function (response) {
            // Add response in Modal body


            $(obj).closest('.modal-fullscreen').css('height', '100vh');
            // $(obj).closest('.product__modal').css('padding-top', '20px');
            // $(obj).closest('.product__modal').removeClass('modal-fullscreen');
            // $(obj).closest('.product__modal-wrapper').css('padding', '20px');
            // $(obj).closest('.product__modal').css('padding', '20px');
            //$('#display_file').html($(obj).closest('.product-items').html());
            //  $('#display_file').find('.pdf_viewer').css('height', '511px');


            $('#modalTabExamContent').html(response);
            $('#modalTabExamContent').css('display', 'block');

            //$(obj).closest('.product__modal').css('max-width', '100%');
            //$(obj).closest('.product__modal').css('height', 'auto');
            //$(obj).closest('.product__modal').css('padding-top', '20px');
            //$(obj).closest('.product__modal').addClass('modal-fullscreen');

            // $(obj).closest('.product__modal-wrapper').css('padding', '0px');
            // $(obj).closest('.product__modal').css('padding', '0px');

            $('#all_file_details').hide();


            // var elem = document.documentElement;
            // if (elem.requestFullscreen) {
            //     elem.requestFullscreen();
            // } else if (elem.msRequestFullscreen) {
            //     elem.msRequestFullscreen();
            // } else if (elem.mozRequestFullScreen) {
            //     elem.mozRequestFullScreen();
            // } else if (elem.webkitRequestFullscreen) {
            //     elem.webkitRequestFullscreen();
            // }


        }
    });
}

function show_right_answer(obj) {
    $.ajax({
        url: URLBase + 'NewHome_Sections/RightAnswer',
        type: 'post',
        data: {par1: $(obj).attr('data-exam-id')},
        cache: false,
        beforeSend: function () {
            handleValidation();
            // alert('');
            var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
            loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
            loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
            loading += ' </div>';

            // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
            $('#modalTabContent').html(loading);
        }, success: function (response) {
            // Add response in Modal body


            $(obj).closest('.modal-fullscreen').css('height', '100vh');
            // $(obj).closest('.product__modal').css('padding-top', '20px');
            // $(obj).closest('.product__modal').removeClass('modal-fullscreen');
            // $(obj).closest('.product__modal-wrapper').css('padding', '20px');
            // $(obj).closest('.product__modal').css('padding', '20px');
            //$('#display_file').html($(obj).closest('.product-items').html());
            //  $('#display_file').find('.pdf_viewer').css('height', '511px');


            $('#modalTabExamContent').html(response);
            $('#modalTabExamContent').css('display', 'block');

            //$(obj).closest('.product__modal').css('max-width', '100%');
            //$(obj).closest('.product__modal').css('height', 'auto');
            //$(obj).closest('.product__modal').css('padding-top', '20px');
            //$(obj).closest('.product__modal').addClass('modal-fullscreen');

            // $(obj).closest('.product__modal-wrapper').css('padding', '0px');
            // $(obj).closest('.product__modal').css('padding', '0px');

            $('#all_file_details').hide();


            // var elem = document.documentElement;
            // if (elem.requestFullscreen) {
            //     elem.requestFullscreen();
            // } else if (elem.msRequestFullscreen) {
            //     elem.msRequestFullscreen();
            // } else if (elem.mozRequestFullScreen) {
            //     elem.mozRequestFullScreen();
            // } else if (elem.webkitRequestFullscreen) {
            //     elem.webkitRequestFullscreen();
            // }


        }
    });
}
function show_wrong_answer(obj) {
    $.ajax({
        url: URLBase + 'NewHome_Sections/WrongAnswer',
        type: 'post',
        data: {par1: $(obj).attr('data-exam-id')},
        cache: false,
        beforeSend: function () {
            handleValidation();
            // alert('');
            var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
            loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
            loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
            loading += ' </div>';

            // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
            $('#modalTabContent').html(loading);
        }, success: function (response) {
            // Add response in Modal body


            $(obj).closest('.modal-fullscreen').css('height', '100vh');
            // $(obj).closest('.product__modal').css('padding-top', '20px');
            // $(obj).closest('.product__modal').removeClass('modal-fullscreen');
            // $(obj).closest('.product__modal-wrapper').css('padding', '20px');
            // $(obj).closest('.product__modal').css('padding', '20px');
            //$('#display_file').html($(obj).closest('.product-items').html());
            //  $('#display_file').find('.pdf_viewer').css('height', '511px');


            $('#modalTabExamContent').html(response);
            $('#modalTabExamContent').css('display', 'block');

            //$(obj).closest('.product__modal').css('max-width', '100%');
            //$(obj).closest('.product__modal').css('height', 'auto');
            //$(obj).closest('.product__modal').css('padding-top', '20px');
            //$(obj).closest('.product__modal').addClass('modal-fullscreen');

            // $(obj).closest('.product__modal-wrapper').css('padding', '0px');
            // $(obj).closest('.product__modal').css('padding', '0px');

            $('#all_file_details').hide();


            // var elem = document.documentElement;
            // if (elem.requestFullscreen) {
            //     elem.requestFullscreen();
            // } else if (elem.msRequestFullscreen) {
            //     elem.msRequestFullscreen();
            // } else if (elem.mozRequestFullScreen) {
            //     elem.mozRequestFullScreen();
            // } else if (elem.webkitRequestFullscreen) {
            //     elem.webkitRequestFullscreen();
            // }


        }
    });
}

function OpenFileFullScreen(obj,is_vh) {

    $(obj).closest('.product__modal').css('max-width', '100%');
    $(obj).closest('.product__modal').css('padding-top', '20px');
    $(obj).closest('.product__modal').addClass('modal-fullscreen');

    $(obj).closest('.product__modal-wrapper').css('padding', '0px');
    $(obj).closest('.product__modal').css('padding', '0px');
    if(is_vh=='1')
    {

        $(obj).closest('.product__modal').css('height', '100vh');
    }
    else
    {

        $(obj).closest('.product__modal').css('height', 'auto');
    }
    $('#display_file').html($(obj).closest('.product-items').html());
    $('#display_file').find('.pdf_viewer').css('height', '100vh');

    $('#all_file_details').hide();
    var elem = document.documentElement;
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
    }

    $(obj).closest('.modal').css('padding-left', '0px !important');

}

function OpenModalFileFullScreen(obj,is_vh) {

    $(obj).closest('.product__modal').css('max-width', '100%');
    $(obj).closest('.product__modal').css('padding-top', '20px');
    $(obj).closest('.product__modal').addClass('modal-fullscreen');

    $(obj).closest('.product__modal-wrapper').css('padding', '0px');
    $(obj).closest('.modal').css('padding-left', '0px !important');
    $(obj).closest('.product__modal').css('padding', '0px');
    $(obj).closest('.product__modal').css('height', '100vh');

   // $('#display_file').html($(obj).closest('.product-items').html());
  //  $('#display_file').find('.pdf_viewer').css('height', '100vh');

   // $('#all_file_details').hide();
    var elem = document.documentElement;
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
    }
    $(obj).closest('.modal').css('padding-left', '0px !important');

}

function OpenFileFullModal(obj) {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
    $(obj).closest('.product__modal').css('height', 'auto');
    $(obj).closest('.product__modal').css('max-width', '90%');
    $(obj).closest('.product__modal').css('padding-top', '20px');
    $(obj).closest('.product__modal').removeClass('modal-fullscreen');
    $(obj).closest('.product__modal-wrapper').css('padding', '20px');
    $(obj).closest('.product__modal').css('padding', '20px');
    $('#display_file').html($(obj).closest('.product-items').html());
    $('#display_file').find('.pdf_viewer').css('height', '511px');
    $('#all_file_details').hide();
}

function BackToFile(obj) {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
    $(obj).closest('.product__modal').css('max-width', '90%');
    $(obj).closest('.product__modal').css('padding-top', '20px');
    $(obj).closest('.product__modal').removeClass('modal-fullscreen');
    $(obj).closest('.product__modal-wrapper').css('padding', '20px');
    $(obj).closest('.product__modal').css('padding', '20px');
    $('#display_file').html('');
    $('#all_file_details').show();
}

function displayWhyAnswer(obj)
{
    $(obj).closest('.question-assessment').find('.description').toggleClass( "bounce" );
}
var pageMethods = function () {

    var $modal = $('#modal1');
    var $modal2 = $('#modal2');
    $(".join_section").on("click", function (e) {
        e.preventDefault();
        $modal.find('.modal-body').html('');
        var el = $(this);
        var op_type = el.attr('type');
        var section_id = el.attr('section-id');
        var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
        if (op_type == "user") {
            $.ajax({
                type: "POST",
                url: URLBase + "NewHome_Sections_Register/AddSecTodb",
                data: {op_type: op_type, section_id: section_id},
                // cache: false,
                // contentType: false,
                // processData: false,
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
                            title: "Done",
                            text: Success.text,
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
        } else if (op_type == "login") {
            // AJAX request
            $.ajax({
                url: URLBase + 'NewHome_Sections_Register/LoginAddSection',
                type: 'post',
                data: {section: section_id, type: op_type},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
                    loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-img"> ';
                    loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
                    loading += ' </div>';
                    $modal.find('.modal-body').html(loading);
                },
                success: function (response) {
                    // Add response in Modal body

                    $modal.find('.modal-body').html(response);
                    $modal.css("z-index","9999");
                    FormValidation();
                }
            });
        }
    });
    $(document).on('click', '.nav-item', function () {

        if (($(this).find('.lock_exam')).length == 0) {

        $(this).closest('#all_file_details').find('.product__modal-box').css('display', 'block');
         }
    });
    $(document).on('click', '.OpenDetails_lesson', function () {
        var el = $(this);
        $modal2.find('.modal-body').html('');
        $.ajax({
            url: URLBase + 'NewHome_Sections/DetailsModal',
            type: 'post',
            data: {lesson_id: el.attr('data-lesson-id')},
            cache: false,
            beforeSend: function () {
                // alert('');
                $modal2.modal('show');

                var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
                loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
                loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
                loading += ' </div>';

                // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
                $modal2.find('.modal-body').html(loading);
            }, success: function (response) {
                // Add response in Modal body
                $modal2.html(response);
                $modal2.find('.product__modal').css('max-width', '90% !important');
                $modal2.find('.product__modal-box').css('display','none');
                handleModalFunctions();
                ToggleWishList(registed_student);
                AddRate();
                AddCommentForm();
            }
        });
    });

    $(document).on('click', '.showNext_lesson', function () {
        var el = $(this);
        $modal2.find('.modal-body').html('');
      var is_full= $modal2.find('.product__modal').hasClass('modal-fullscreen');

        $.ajax({
            url: URLBase + 'NewHome_Sections/DetailsModal',
            type: 'post',
            data: {lesson_id: el.attr('data-lesson-id')},
            cache: false,
            beforeSend: function () {
                // alert('');
                $modal2.modal('show');

                var loading = '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
                loading += ' <img  src="' + URLBase + 'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
                loading += ' <img class="loading-logo" src="' + URLBase + 'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
                loading += ' </div>';

                // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
                $modal2.find('.modal-body').html(loading);
            }, success: function (response) {
                // Add response in Modal body
                $modal2.html(response);
                $modal2.closest('.product__modal').css('max-width', '90% !important');
                $modal2.find('.product__modal-box').css('display','none');
                if(is_full)
                {

                    $modal2.find('.product__modal').css('max-width', '100%');
                    $modal2.find('.product__modal').css('padding-top', '20px');
                    $modal2.find('.product__modal').addClass('modal-fullscreen');

                    $modal2.find('.product__modal-wrapper').css('padding', '0px');
                    $modal2.find('.product__modal').css('padding', '0px');

                    $modal2.find('.product__modal').css('height', '100vh');

                    var elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    }
                }
                handleModalFunctions();
                ToggleWishList(registed_student);
                AddRate();
                AddCommentForm();
            }
        });
    });
    var handleModalFunctions = function () {
        $(".attached_file").on("click", function () {
            console.log($(this).attr("file-id"));
            var file_id = $(this).attr("file-id");
            $("#evaluation_text").text(Lang.evaluate_file);
            $('.add_review').each(function(i, obj) {
                $(obj).attr("item-type", "file");
                $(obj).attr("item-id", file_id);
                $(".star-color").removeClass('fas');
                $(".star-color").addClass('fal');
                $("#add_comment").attr("item-type", "file");
                $("#add_comment").attr("item-id", file_id);
            });
            //$("#video_wishlist").attr("id", video_id);
        });
        $("#show-review-form").on("click", function () {
            $("#review-form").slideToggle(700);
        });
    }
    var ToggleWishList = function () {
        $(".toggle_wishlist").on("click", function (e) {

            e.preventDefault();
            var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
            if(registed_student == "false"){
                Swal.fire({
                    title: Lang.Error,
                    text: Lang.sign_in_to_allow_this_action,
                    icon: "error",
                    iconHtml: " <img src='" + imagem + "' >",
                }).then(() => {
                    $(".join_section").click();                        
                });
            }else{
                var el = $(this);
                var section_id = el.attr("section-id");
                var item_type = el.attr("item-type");
                var item_id = el.attr("item-id");
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Students_Sections/ToggleSectionFromWishlist",
                    data: {section_id: section_id,item_type: item_type, item_id: item_id},
                    dataType: "JSON",
                    beforeSend: function () {
                        el.prop("disabled", true);
                    },
                    complete: function () {
                        el.prop("disabled", false);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        Swal.fire({
                            title: Lang.Error,
                            text: xhr.status + " - " + thrownError,
                            icon: "error",
                            iconHtml: " <img src='" + imagem + "' >",
                        })
                    },
                    success: function (response) {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            var obj = response["Errors"];
                            Swal.fire({
                                title: Lang.Error,
                                text: obj,
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
                                    el.addClass('wishlist-btn w-added');
                                }else{
                                    el.removeClass('wishlist-btn w-added');
                                }                        
                            });
                        }
        
                    }
                });
            }
        });
    }
    var AddRate = function () {
        $(".add_review").on("click", function () {
            var section_id = $(this).attr("section-id");
            var rate = $(this).attr("rate");
            var item_type = $(this).attr("item-type");
            var item_id = $(this).attr("item-id");
            $('.star-color').each(function(i, obj) {
                var other_rate = $(obj).attr("star-review-id");
                if(parseInt(other_rate) > parseInt(rate)){
                    $(obj).removeClass('fas');
                    $(obj).addClass('fal');
                }else if(parseInt(other_rate) <= parseInt(rate)){
                    $(obj).removeClass('fal');
                    $(obj).addClass('fas');
                }
            });
            $.ajax({
                type: "POST",
                url: URLBase + "NewHome_Students_Sections/AE_Rate",
                data: {section_id: section_id, rate: rate,item_type: item_type, item_id: item_id},
                dataType: "JSON",
                beforeSend: function () {
                },
                complete: function () {
                   
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr);
                    Swal.fire({
                        title: Lang.Error,
                        text: xhr.status + " - " + thrownError,
                        icon: "error",
                        iconHtml: " <img src='" + imagem + "' >",
                    })
                },
                success: function (response) {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        var obj = response["Errors"];
                        var stringerror = '';
                        for (var prop in obj) {
                            stringerror += '* ' + obj[prop] + '</br>';
                        }
                        Swal.fire({
                            title: Lang.Error,
                            text: stringerror,
                            icon: "error",
                            iconHtml: " <img src='" + imagem + "' >",
                        }).then((value) => {
                            
                        });
                    } else if (response !== null && response.hasOwnProperty("Success")) {
                        
                    }

                }
            });
        });
    }

    var AddCommentForm = function () {
        var Form1 = document.querySelector('#add_comment');
        Form1.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (Form1.checkValidity()) {
                var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
                var theForm = $('#add_comment');
                var data = new FormData(theForm[0]);
                data.append('item_type', theForm.attr('item-type'));
                data.append('section', theForm.attr('section-id'));
                data.append('item_id', theForm.attr('item-id'));
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Students_Sections/AE_Comment",
                    data: data,
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#add_comment :input").prop("disabled", true);
                        $("#save_comments").addClass('hidden');
                    },
                    complete: function () {
                        $("#add_comment :input").prop("disabled", false);
                        $("#save_comment").removeClass('hidden');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        Swal.fire({
                            title: Lang.Error,
                            text: xhr.status + " - " + thrownError, 
                            icon: "error",
                            iconHtml: " <img src='" + imagem + "' >",
                        });
                    },
                    success: function (response) {
                        if (response !== null && response.hasOwnProperty("Errors")) {
                            var obj = response["Errors"];
                            var stringerror = '';
                            for (var prop in obj) {
                                stringerror += '* ' + obj[prop] + '</br>';
                            }
                            Swal.fire({
                                title: Lang.Error,
                                text: stringerror,
                                icon: "error",
                                iconHtml: " <img src='" + imagem + "' >",
                            }).then((value) => {
                                
                            });
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            $("#comment_content").val("");
                            $("#review-form").slideToggle(100);
                            Success = response.Success;
                            Swal.fire({
                                title: Lang.Done,
                                text: Success.text,
                                icon: "success",
                                iconHtml: " <img src='" + imagem + "' >",
                            }).then((value) => {
                                
                            });
                        }

                    }
                });
            }
            Form1.classList.add('was-validated')
        }, false)

    }


    var FormValidation = function () {
        var Login_Section_Form = document.querySelector('#signin_section_form');
        Login_Section_Form.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (Login_Section_Form.checkValidity()) {
                var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
                console.log("jjjjjjjjjj");
                var theForm = $('#signin_section_form');
                //upload files
                var data = new FormData(theForm[0]);
                data.append('op_type', theForm.attr('type'));
                data.append('section_id', theForm.attr('section-id'));
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewHome_Sections_Register/AddSecTodb",
                    data: data,
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        // $("#signin_section_form :input").prop("disabled", true);
                        // $("#submit-buttons").addClass('hidden');
                    },
                    complete: function () {
                        $("#signin_section_form :input").prop("disabled", false);
                        $("#submit-buttons").removeClass('hidden');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        swal('Error', xhr.status + " - " + thrownError, "error");
                    },
                    success: function (response) {
                      //  $modal.css('display',"none");
                        $modal.modal('hide');
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
                                } else {
                                    //location.reload();
                                }
                            });
                        } else if (response !== null && response.hasOwnProperty("Success")) {
                            Success = response.Success;
                            Swal.fire({
                                title: "Done",
                                text: Success.text,
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
            Login_Section_Form.classList.add('was-validated')
        }, false)

    }

    return {
        //main function to initiate the module
        init: function () {

        }

    };

}();
jQuery(document).ready(function () {
    //console.log(registed_student);
    pageMethods.init(registed_student);
});