"use strict";



function generateRandomString(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        result += characters.charAt(randomIndex);
    }

    return result;
}

var load_questions_paragraph_question_type = function () {
    $('.paragraph_question_type').on("change", (function () {
        var div_paragraph_details_question=   $(this).closest('.accordion-item').find('.paragraph_details_question');
        $.ajax({
            type: "POST",
            url: URLBase + "NewVersion_QuestionsBank/loadNewQuestion/0",//study_section_id
            data: {'questionType': $(this).val()},
            //async: false,
            dataType: "HTML",
            beforeSend: function () {
                // App.blockUI({
                //     target: '#addNewQuestionModal .modal-body',
                //     overlayColor: 'none',
                //     cenrerY: true,
                //     animate: true
                // });
            },
            complete: function () {
                // App.unblockUI('#addNewQuestionModal');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.alert({
                    title: Lang.Error,
                    content: xhr.status + " - " + thrownError,
                    type: 'red',
                    rtl: App.isRTL(),
                    closeIcon: true,
                    buttons: {
                        cancel: {
                            text: Lang.Ok,
                            action: function () {
                            }
                        }
                    }
                });
            },
            success: function (response) {
                if (response !== null) {
                    $('.add_answers').repeater({
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
                    div_paragraph_details_question.html(response);
                    var question_new_id = generateRandomString(10);

                    div_paragraph_details_question.find('.kt_question_description__toolbar').attr('id',question_new_id+'_toolbar');
                    div_paragraph_details_question.find('.kt_question_description').attr('id',question_new_id);



                    DecoupledEditor
                        .create(document.getElementById(question_new_id))
                        .then(editor => {
                            const toolbarContainer = document.getElementById(question_new_id+'_toolbar');

                            toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    if( div_paragraph_details_question.find('.kt_answer').length >=1)
                    {
                        var answer_new_id = generateRandomString(10);

                        div_paragraph_details_question.find('.kt_answer_toolbar').attr('id',answer_new_id+'_toolbar');
                        div_paragraph_details_question.find('.kt_answer').attr('id',answer_new_id);



                        DecoupledEditor
                            .create(document.getElementById(answer_new_id))
                            .then(editor => {
                                const toolbarContainer = document.getElementById(answer_new_id+'_toolbar');

                                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                            })
                            .catch(error => {
                                console.error(error);
                            });


                    }
                    if( div_paragraph_details_question.find('.kt_answer2').length >=1)
                    {
                        var answer_new2_id = generateRandomString(10);

                        div_paragraph_details_question.find('.kt_answer_toolbar2').attr('id',answer_new2_id+'_toolbar');
                        div_paragraph_details_question.find('.kt_answer2').attr('id',answer_new2_id);



                        DecoupledEditor
                            .create(document.getElementById(answer_new2_id))
                            .then(editor => {
                                const toolbarContainer = document.getElementById(answer_new2_id+'_toolbar');

                                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                            })
                            .catch(error => {
                                console.error(error);
                            });


                    }
                    $('.add_answers').repeater({
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
                    add_new_answer();

                }
            }
        });


    }));
}

var remove_part = function () {
    $('.remove_part').on("click", (function () {

        var div_remove=   $(this).closest('.accordion-item');

        div_remove.remove();

    }));
}

var add_new_answer = function () {


    $(".add_answers").on("click", ".add_new_answer", function () {

        var lastDiv = $('.add_answers .mt-repeater-item').last();
        var answer_new_id = generateRandomString(10);

        lastDiv.find('.kt_answer_toolbar').attr('id',answer_new_id+'_toolbar');
        lastDiv.find('.kt_answer').attr('id',answer_new_id);
        DecoupledEditor
            .create( document.getElementById( answer_new_id) )
            .then( editor => {
                const toolbarContainer = document.getElementById(answer_new_id+'_toolbar');

                toolbarContainer.appendChild( editor.ui.view.toolbar.element );

                window.editor = editor;
            } )
            .catch( err => {
                console.error( err );
            } );

        if( lastDiv.find('.kt_answer2').length >=1)
        {
            var answer_new2_id = generateRandomString(10);

            lastDiv.find('.kt_answer_toolbar2').attr('id',answer_new2_id+'_toolbar');
            lastDiv.find('.kt_answer2').attr('id',answer_new2_id);



            DecoupledEditor
                .create(document.getElementById(answer_new2_id))
                .then(editor => {
                    const toolbarContainer = document.getElementById(answer_new2_id+'_toolbar');

                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                })
                .catch(error => {
                    console.error(error);
                });


        }

        console.log(lastDiv);
    });

}

var KTCreateApp = function () {
    var e, t, o, r, a, i, n = [];
    var learning_points_ar, learning_points_en, requirements_ar, requirements_en, myDropzone;
    var form_elements;
    return {
        init: function () {
            (
                e = document.querySelector("#kt_modal_question_app")
            ) && (
                new bootstrap.Modal(e),
                    t = document.querySelector("#kt_modal_question_app_stepper"),
                    o = document.querySelector("#kt_modal_question_app_form"),
                    r = t.querySelector('[data-kt-stepper-action="submit"]'),
                    a = t.querySelector('[data-kt-stepper-action="next"]'),

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
                        dropdownParent: $("#kt_modal_question_app")
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

                    (i = new KTStepper(t)).on
                    (
                        "kt.stepper.changed",
                        (function (e) {
                            4 === i.getCurrentStepIndex() ? (
                                    r.classList.remove("d-none"),
                                        r.classList.add("d-inline-block"),
                                        a.classList.add("d-none"))
                                : 5 === i.getCurrentStepIndex() ? (
                                    r.classList.add("d-none"),
                                        a.classList.add("d-none")) :
                                (
                                    r.classList.remove("d-inline-block"),
                                        r.classList.remove("d-none"),
                                        a.classList.remove("d-none")
                                )
                        })
                    ),
                    i.on("kt.stepper.next", (function (e) {
                        console.log("stepper.next");
                        var t = n[e.getCurrentStepIndex() - 1];
                        t ? t.validate().then(
                            (
                                function (t) {
                                    console.log("validated!");
                                    if ("Valid" == t) {
                                        //first step
                                        if (i.getCurrentStepIndex()==1) {
                                            // mona $('#lbl_difficulty_degree' + $('#difficulty_degree').val()).css('display', 'block');
                                            //   $('#lbl_course').html($('#Courses').select2('data')[0]['text']);
                                            //    $('#lbl_section').html($('#section').select2('data')[0]['text']);
                                            //  $('#lbl_lesson').html($('#lesson').select2('data')[0]['text']);
                                             $('#lbl_question_type').html($('#question_type').select2('data')[0]['text']);
                                            var QuestionTypeId = $('#question_type').val();


                                            $.ajax({
                                                type: "POST",
                                                url: URLBase + "NewVersion_QuestionsBank/loadNewQuestion/"+$('#section').val(),//study_section_id
                                                data: {'questionType': QuestionTypeId},
                                                //async: false,
                                                dataType: "HTML",
                                                beforeSend: function () {
                                                    // App.blockUI({
                                                    //     target: '#addNewQuestionModal .modal-body',
                                                    //     overlayColor: 'none',
                                                    //     cenrerY: true,
                                                    //     animate: true
                                                    // });
                                                },
                                                complete: function () {
                                                   // App.unblockUI('#addNewQuestionModal');
                                                },
                                                error: function (xhr, ajaxOptions, thrownError) {
                                                    $.alert({
                                                        title: Lang.Error,
                                                        content: xhr.status + " - " + thrownError,
                                                        type: 'red',
                                                        rtl: App.isRTL(),
                                                        closeIcon: true,
                                                        buttons: {
                                                            cancel: {
                                                                text: Lang.Ok,
                                                                action: function () {
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                success: function (response) {
                                                    if (response !== null) {
                                                        $('.question-container-answers').html(response);
                                                        $('.add_answers').repeater({
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


                                                        $(".select2me").select2();

                                                        DecoupledEditor
                                                            .create(document.querySelector('#kt_question_description'))
                                                            .then(editor => {
                                                                const toolbarContainer = document.querySelector('#kt_question_description__toolbar');

                                                                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                                                            })
                                                            .catch(error => {
                                                                console.error(error);
                                                            });
                                                       // $('.kt_answer').each({})
                                                        if($('.kt_answer').length >0)
                                                        {
                                                            DecoupledEditor
                                                                .create( document.querySelector( '.kt_answer' ) )
                                                                .then( editor => {
                                                                    const toolbarContainer = document.querySelector( '.kt_answer_toolbar' );

                                                                    toolbarContainer.appendChild( editor.ui.view.toolbar.element );

                                                                    window.editor = editor;
                                                                } )
                                                                .catch( err => {
                                                                    console.error( err );
                                                                } );
                                                        }

                                                            if(QuestionTypeId == 7)
                                                            {
                                                                DecoupledEditor
                                                                    .create( document.querySelector( '#kt_answer_2' ) )
                                                                    .then( editor => {
                                                                        const toolbarContainer = document.querySelector( '#kt_answer_toolbar_2' );

                                                                        toolbarContainer.appendChild( editor.ui.view.toolbar.element );

                                                                        window.editor = editor;
                                                                    } )
                                                                    .catch( err => {
                                                                        console.error( err );
                                                                    } );
                                                            }



                                                        $(".add_answers").on("click", ".show_browse_image", function () {

                                                            var modal_name = this.closest('.answer_items').getElementsByClassName('modal')[0];

                                                            var  drop_zone=  this.closest('.answer_items').getElementsByClassName('modal')[0].getElementsByClassName('dropzone')[0];

                                                          if($(drop_zone).attr('data-open') =='false' ) {
                                                              var myDropzone = new Dropzone(drop_zone, {
                                                                  url: URLBase + "NewVersion_QuestionsBank/uploadFile",// Set the url for your upload script location
                                                                  paramName: "file", // The name that will be used to transfer the file
                                                                  maxFiles: 10,
                                                                  maxFilesize: 10, // MB
                                                                  addRemoveLinks: true,
                                                                  accept: function (file, done) {
                                                                      if (file.name == "wow.jpg") {
                                                                          done("Naha, you don't.");
                                                                      } else {
                                                                          done();
                                                                      }
                                                                  }
                                                              });
                                                          }
                                                            $(drop_zone).attr('data-open','true')

                                                            $(modal_name).modal('show');

                                                        });


                                                        $("#question_items").on("click", ".show_browse_image", function () {

                                                            var modal_name = this.closest('#question_items').getElementsByClassName('modal')[0];

                                                            var  drop_zone=  this.closest('#question_items').getElementsByClassName('modal')[0].getElementsByClassName('dropzone')[0];

                                                            if($(drop_zone).attr('data-open') =='false' ) {
                                                                var myDropzone = new Dropzone(drop_zone, {
                                                                    url: URLBase + "NewVersion_QuestionsBank/uploadFile",// Set the url for your upload script location
                                                                    paramName: "file", // The name that will be used to transfer the file
                                                                    maxFiles: 10,
                                                                    maxFilesize: 10, // MB
                                                                    addRemoveLinks: true,
                                                                    accept: function (file, done) {
                                                                        if (file.name == "wow.jpg") {
                                                                            done("Naha, you don't.");
                                                                        } else {
                                                                            done();
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                            $(drop_zone).attr('data-open','true')

                                                            $(modal_name).modal('show');

                                                        });

                                                        $('#add_parts').click(function (e) {

                                                            e.preventDefault();
                                                            var part_num=1;
                                                            if ($('.accordion-item').length >=1) {
                                                                var last_part_id = $('.accordion-item:last').find('.accordion-header').attr('id');
                                                                part_num = last_part_id.split("_");
                                                                part_num = parseInt(part_num[2]) + 1;
                                                               // $('.accordion-collapse:last').removeClass('show');
                                                            }

                                                            $.ajax({
                                                                type: "POST",
                                                                url: URLBase + "NewVersion_QuestionsBank/loadPart",
                                                                data: {'part_num': part_num},
                                                                //async: false,
                                                                dataType: "HTML",
                                                                beforeSend: function () {

                                                                },
                                                                complete: function () {
                                                                },
                                                                error: function (xhr, ajaxOptions, thrownError) {
                                                                    $.alert({
                                                                        title: Lang.Error,
                                                                        content: xhr.status + " - " + thrownError,
                                                                        type: 'red',
                                                                        rtl: App.isRTL(),
                                                                        closeIcon: true,
                                                                        buttons: {
                                                                            cancel: {
                                                                                text: Lang.Ok,
                                                                                action: function () {
                                                                                }
                                                                            }
                                                                        }
                                                                    });
                                                                },
                                                                success: function (response) {
                                                                    // console.log(response);
                                                                    if (response !== null) {
                                                                        $('#paragraph_question').append(response);
                                                                        load_questions_paragraph_question_type();
                                                                        remove_part();
                                                                        add_new_answer();
                                                                        $(".select2me").select2();
                                                                    }
                                                                }
                                                            });


                                                        });



                                                        load_questions_paragraph_question_type();
                                                        remove_part();
                                                        add_new_answer();

                                                    }
                                                }
                                            });




                                            // get question answer container using question type id
                                            // $.ajax({
                                            //     type: "GET",
                                            //     url: URLBase + "NewVersion_QuestionsBank/getQuestionAnswerByQuestionType",
                                            //     data: {'questionTypeId': QuestionTypeId},
                                            //     cache: false,
                                            //     dataType: "html",
                                            //     success: function (data) {
                                            //         $('.question-container-answers').html(data);
                                            //     }
                                            // });
                                        }
                                        if (i.getCurrentStepIndex()==2) {
                                                    // for (var instanceName in CKEDITOR.instances) {
                                                    //     CKEDITOR.instances[instanceName].updateElement();
                                                    // }

                                                    var formQuestion = $("#kt_modal_question_app_form");
                                                    var difficulty_degree = formQuestion.find('select[name="difficulty_degree"]').val();
                                                    var lesson = formQuestion.find('select[name="lesson"]').val();
                                                    var description = formQuestion.find('textarea[name="question[][description]"]').val();
                                                    var question_type = formQuestion.find('select[name="question_type"]').val();
                                                    if( (question_type == 1||question_type == 6|| question_type == 7)&& formQuestion.find('div[data-repeater-list="answers"] textarea ').length ==0)
                                                    {
                                                        Swal.fire(
                                                            {
                                                                text: 'يجب ادخال اجابات',
                                                                icon: "error",
                                                                buttonsStyling: !1,
                                                                confirmButtonText: "Ok",
                                                                customClass: {
                                                                    confirmButton: "btn btn-light"
                                                                }
                                                            }
                                                        ).then((function () {

                                                        }));
                                                        return;
                                                    }
                                                    $(".error").remove();
                                                    var validationForm = true;
                                                    if (question_type == 1) {
                                                        var trueAnswersCount = $('.add_answers input[type="checkbox"]:checked').length;
                                                        alert(trueAnswersCount);
                                                        if (trueAnswersCount < 1) {
                                                            validationForm = false;
                                                            $('.add_answers input[type="checkbox"]').first().after('<span class="error">This field is required</span>');
                                                        }
                                                    }
                                                    if (question_type == 5) {
                                                        var no_of_points = formQuestion.find('input[name="no_of_points"]').val();

                                                        if (no_of_points == "") {
                                                            validationForm = false;
                                                            $('#addNewQuestionModal .modal-body input[name="no_of_points"]').after('<span class="error">This field is required</span>');
                                                        }
                                                    }
                                                    if (question_type == 6) { //rearrange
                                                        $('#addNewQuestionModal .modal-body input[type="number"]').each(function () {
                                                            if ($(this).val() == '') {
                                                                $(this).after('<span class="error">This field is required</span>');
                                                                validationForm = false;

                                                            }
                                                        });
                                                    }

                                                    formQuestion.find('div[data-repeater-list="answers"] textarea ').each(function () {
                                                        var area_name = $(this).attr('name');
                                                        var area_file = area_name.replace("description", "photo1");
                                                        var file_val = formQuestion.find('input[name="' + area_file + '"]').val();
                                                        //console.log(file_val);

                                                        //console.log($(this).attr('name'));
                                                        if ($(this).val() == '' && file_val == '') {
                                                            $(this).after('<span class="error">This field is required</span>');
                                                            validationForm = false;

                                                        }
                                                    });

                                                    if (validationForm === true) {

                                                        var formData = new FormData(formQuestion[0]);
                                                        var formID = formQuestion.attr('id');
                                                        formData.append('par1', formQuestion.attr("par1"));
                                                        formData.append('par2', formQuestion.attr("par2"));
                                                        $.ajax({
                                                            type: "POST",
                                                            url: URLBase + "Teacher_Sections_Exams/ToDB",
                                                            data: formData,
                                                            //async: false,
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            dataType: "JSON",
                                                            beforeSend: function () {
                                                                $("#" + formID + " :input").prop("disabled", true);
                                                                $(".add_questions_to_bank").addClass('hidden');
                                                                $("#spinner").removeClass('hidden');
                                                                // App.blockUI({
                                                                //     target: '#' + formID,
                                                                //     overlayColor: 'none',
                                                                //     cenrerY: true,
                                                                //     animate: true
                                                                // });
                                                            },
                                                            complete: function () {
                                                                App.unblockUI('#' + formID);
                                                            },
                                                            success: function (response) {
                                                                if (response !== null && response.hasOwnProperty("Errors")) {
                                                                    //console.log(response['Errors']);
                                                                    var obj = response["Errors"];
                                                                    var stringerror = '';
                                                                    for (var prop in obj) {
                                                                        stringerror += '* ' + obj[prop] + '</br>';
                                                                    }
                                                                    $('#Server_alerts').html(stringerror);
                                                                    $('.Server_alerts').removeClass('hidden');
                                                                    $("html,body").animate({scrollTop: $('#Server_alerts').offset().top - 100}, "slow");

                                                                } else if (response !== null && response.hasOwnProperty("Success")) {
                                                                    // $('.Server_alerts').addClass('hidden');
                                                                    // $("#submit-buttons").remove();
                                                                    var Res = response['Success'];
                                                                    UINotific8.init(Res.theme, Res.horizontalEdge, Res.verticalEdge, Res.heading, Res.life, Res.text);

                                                                    setTimeout(function () {
                                                                        var part_num = $('#addNewQuestionModal .modal-footer .part-num').val();
                                                                        loadQuestionOnPartById(part_num, Res.question_id);

                                                                        // todo::check if repater ot no
                                                                        if ($('#addNewQuestionModal .modal-footer #repeat_question').prop("checked") == false) {
                                                                            $("#addNewQuestionModal").modal('hide');
                                                                        } else {
                                                                            $(".add_questions_to_bank").removeClass('hidden');
                                                                            $("#" + formID + " :input").prop("disabled", false);
                                                                            $("#" + formID + " textarea").val('');
                                                                            for (var instanceName in CKEDITOR.instances) {
                                                                                CKEDITOR.instances[instanceName].setData('');
                                                                            }

                                                                            // formQuestion.reset();
                                                                        }

                                                                        // window.location.replace(URLBase + "Teacher_Sections_Questions/AE/add/" + form3.attr("par2") );
                                                                    }, Res.life);
                                                                }
                                                            }
                                                        });
                                                    } else {

                                                    }



                                        }
                                        e.goNext();
                                    } else {
                                        Swal.fire(
                                            {
                                                text: "Sorry, looks like there are some errors detected, please try again.",
                                                icon: "error",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-light"
                                                }
                                            }
                                        ).then((function () {
                                        }));
                                    }
                                }
                            )
                            ) :
                            (
                                e.goNext(),
                                    KTUtil.scrollTop()
                            )
                    })),
                    i.on(
                        "kt.stepper.previous",
                        (function (e) {
                            console.log("stepper.previous"),
                                e.goPrevious(),
                                KTUtil.scrollTop()
                        })
                    ),
                    r.addEventListener("click", (function (e) {
                        n[3].validate().then(
                            (
                                function (t) {
                                    "Valid" == t ? (
                                            //console.log(new FormData(o)),
                                            form_elements = new FormData($('#kt_modal_question_app_form')[0]),
                                                //form_elements = new FormData(o[0]),
                                                $.ajax({
                                                    type: "POST",
                                                    url: URLBase + "NewVersion_QuestionsBank/ToDB",
                                                    data: form_elements,
                                                    //async: false,
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    dataType: "JSON",
                                                    beforeSend: function () {
                                                        $("#kt_modal_question_app_form :input").prop("disabled", true)
                                                        //$("#spinner").removeClass('hidden');
                                                        // App.blockUI({
                                                        //     target: '#SForm',
                                                        //     overlayColor: 'none',
                                                        //     cenrerY: true,
                                                        //     animate: true
                                                        // });
                                                    },
                                                    complete: function () {
                                                        $("#kt_modal_question_app_form :input").prop("disabled", false),
                                                            r.removeAttribute("data-kt-indicator"),
                                                            r.disabled = !1
                                                        //$("#submit-buttons").removeClass('hidden');
                                                        //$("#spinner").addClass('hidden');
                                                        //App.unblockUI('#SForm');
                                                    },
                                                    success: function (response) {
                                                        if (response !== null && response.hasOwnProperty("Errors")) {
                                                            //console.log(response['Errors']);
                                                            var obj = response["Errors"];
                                                            var stringerror = '';
                                                            for (var prop in obj) {
                                                                stringerror += '* ' + obj[prop] + '</br>';
                                                            }
                                                            Swal.fire({
                                                                text: stringerror,
                                                                icon: "error",
                                                                buttonsStyling: !1,
                                                                confirmButtonText: "Ok, got it!",
                                                                customClass: {confirmButton: "btn btn-light"}
                                                            })
                                                                .then((function () {
                                                                    KTUtil.scrollTop()
                                                                }))
                                                        } else if (response !== null && response.hasOwnProperty("Success")) {
                                                            var Res = response['Success'];
                                                            setTimeout((function () {
                                                                Swal.fire({
                                                                    text: Res.text,
                                                                    icon: "success",
                                                                    buttonsStyling: !1,
                                                                    confirmButtonText: Lang.Ok,
                                                                    customClass: {
                                                                        confirmButton: "btn btn-primary"
                                                                    }
                                                                }).then((function (e) {
                                                                    //t.isConfirmed&&
                                                                    KTUtil.scrollTop(),
                                                                        setTimeout(function () {
                                                                            window.location.replace(URLBase + "NewVersion_QuestionsBank");
                                                                        }, 2000)
                                                                }))
                                                            }), 2e3);
                                                        }
                                                    }
                                                })


                                        )
                                        : Swal.fire(
                                        {
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {confirmButton: "btn btn-light"}
                                        })
                                            .then((function () {
                                                KTUtil.scrollTop()
                                            }))
                                }
                            )
                        )
                    })),
                    $(o.querySelector('[name="Courses"]')).on("change", (function () {
                        var course_id = document.getElementById("Courses").value;
                        $("#section").html("");
                        $("#lesson").html("");
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_QuestionsBank/getSectionByCourse",
                            data: {course_id: course_id},
                            //async: false,
                            cache: false,

                            dataType: "JSON",
                            beforeSend: function () {
                            },
                            complete: function () {
                            },
                            success: function (data) {
                                var name = 'name_' + $('#lang').val();
                                var html = '<option value=""></option>';
                                $.each(data, function (index, lectObj) {
                                    html += "<option value='" + lectObj['id'] + "'>" + lectObj[name] + "</option>";
                                });
                                $("#section").select2('val', 'All');
                                $("#section").html(html);


                            }
                        })
                    })),

                    $(o.querySelector('[name="section"]')).on("change", (function () {

                        var section_id = document.getElementById("section").value;
                        $("#lesson").html("");
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_QuestionsBank/getLessonsBySection",
                            data: {section_id: section_id},
                            //async: false,
                            cache: false,

                            dataType: "JSON",
                            beforeSend: function () {
                            },
                            complete: function () {
                            },
                            success: function (data) {

                                var html = '<option value=""></option>';
                                $.each(data, function (index, lectObj) {
                                    html += "<option value='" + lectObj['id'] + "'>" + lectObj['name'] + "</option>";
                                });
                                $("#lesson").select2('val', 'All');
                                $("#lesson").html(html);


                            }
                        })
                    })),



                    n.push(
                        FormValidation.formValidation(
                            o, {
                                fields: {
                                    //mona  course:{validators:{notEmpty:{message: Lang.required}}},
                                    //section:{validators:{notEmpty:{message: Lang.required}}},
                                    //lesson:{validators:{notEmpty:{message: Lang.required}}},
                                    question_type: {validators: {notEmpty: {message: Lang.required}}},
                                    //Learning_Outcomes:{validators:{notEmpty:{message: Lang.required}}},
                                    //difficulty_degree:{validators:{notEmpty:{message: Lang.required}}},

                                },
                                plugins: {
                                    trigger: new FormValidation.plugins.Trigger,
                                    bootstrap: new FormValidation.plugins.Bootstrap5({
                                        rowSelector: ".fv-row",
                                        eleInvalidClass: "",
                                        eleValidClass: ""
                                    })
                                }
                            }
                        )
                    ),
                    n.push(FormValidation.formValidation(o,
                        {
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger,
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
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
KTUtil.onDOMContentLoaded((function () {
    KTCreateApp.init();
  //  KTFormsCKEditorDocument.init();




    // DecoupledEditor
    //     .create(document.querySelector('#kt_docs_ckeditor_document'))
    //     .then(editor => {
    //         const toolbarContainer = document.querySelector( '#kt_docs_ckeditor_document_toolbar' );
    //
    //         toolbarContainer.appendChild( editor.ui.view.toolbar.element );
    //     })
    //     .catch(error => {
    //         console.error(error);
    //     });
}));


