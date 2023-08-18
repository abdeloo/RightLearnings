"use strict";
var OpenModal = function () {
    var $modal = $('#modal1');
    return{
        init:function(){
            $(document).on('click', '.ae_item', function () {
                var el = $(this);
                // AJAX request
                $.ajax({
                    url: URLBase + 'NewVersion_QuestionsBank/AE_Modal',
                    type: 'post',
                    data: {par1: el.attr('par1')},
                    cache: false,
                    beforeSend: function () {
                        // alert('');
                        $modal.modal('show');
                        var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                        $modal.find('.modal-dialog').html(loading);
                    }, success: function (response) {
                        // Add response in Modal body
                        $modal.html(response);
                        $(".select2me").select2({
                            dropdownParent: $("#modal1")
                        });
                        KTImageInput.createInstances();
                        KTCreateApp.init();
                    }
                });
            });
        }
    }
}();

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
            dataType: "HTML",
            beforeSend: function () {
              
            },
            complete: function () {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    text: xhr.status + " - " + thrownError,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: Lang.Ok,
                    customClass: {
                        confirmButton: "btn btn-light"
                    }
                })
                .then((function(){KTUtil.scrollTop()})) 
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

var KTCreateApp=function(){
    "use strict";
        // Elements
        var modal;	
        var modalEl;
    
        var stepper;
        var form;
        var formSubmitButton;
        var formContinueButton;
    
        // Variables
        var stepperObj;
        var validations = [];

        var form_elements ;
    
        // Private Functions
        var initStepper = function () {
            // Initialize Stepper
            stepperObj = new KTStepper(stepper);
    
            // Stepper change event(handle hiding submit button for the last step)
            stepperObj.on('kt.stepper.changed', function (stepper) {
                if (stepperObj.getCurrentStepIndex() === 2) {
                    formSubmitButton.classList.remove('d-none');
                    formSubmitButton.classList.add('d-inline-block');
                    formContinueButton.classList.add('d-none');
                } else if (stepperObj.getCurrentStepIndex() === 3) {
                    formSubmitButton.classList.add('d-none');
                    formContinueButton.classList.add('d-none');
                } else {
                    formSubmitButton.classList.remove('d-inline-block');
                    formSubmitButton.classList.remove('d-none');
                    formContinueButton.classList.remove('d-none');
                }
            });
    
            // Validation before going to next page
            stepperObj.on('kt.stepper.next', function (stepper) {
                console.log('stepper.next');
    
                // Validate form before change stepper step
                var validator = validations[stepper.getCurrentStepIndex() - 1]; // get validator for currnt step
    
                if (validator) {
                    validator.validate().then(function (status) {
                        console.log('validated!');
    
                        if (status == 'Valid') {

                            //first step
                            if (stepper.getCurrentStepIndex()==1) {
                                $('#lbl_question_type').html($('#question_type').select2('data')[0]['text']);
                                var QuestionTypeId = $('#question_type').val();
                                $.ajax({
                                    type: "POST",
                                    url: URLBase + "NewVersion_QuestionsBank/loadNewQuestion/"+$('#section').val(),//study_section_id
                                    data: {'questionType': QuestionTypeId},
                                    dataType: "HTML",
                                    beforeSend: function () {
                                       
                                    },
                                    complete: function () {

                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        Swal.fire({
                                            text: xhr.status + " - " + thrownError,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: Lang.Ok,
                                            customClass: {
                                                confirmButton: "btn btn-light"
                                            }
                                        }).then(function () {
                                            //KTUtil.scrollTop();
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
                                            $(".select2me").select2({
                                                dropdownParent: $("#modal1")
                                            });
                                            DecoupledEditor
                                                .create(document.querySelector('#kt_question_description'))
                                                .then(editor => {
                                                    const toolbarContainer = document.querySelector('#kt_question_description__toolbar');

                                                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                                                })
                                                .catch(error => {
                                                    console.error(error);
                                                });
                                            if($('.kt_answer').length >0){
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

                                            if(QuestionTypeId == 7){
                                                DecoupledEditor
                                                    .create( document.querySelector( '#kt_answer_2' ) )
                                                    .then( editor => {
                                                        const toolbarContainer = document.querySelector( '#kt_answer_toolbar_2' );

                                                        toolbarContainer.appendChild( editor.ui.view.toolbar.element );

                                                        window.editor = editor;
                                                    } )
                                                    .catch( err => {
                                                        console.error( err );
                                                    });
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
                                                $(drop_zone).attr('data-open','true');
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
                                                }
                                                $.ajax({
                                                    type: "POST",
                                                    url: URLBase + "NewVersion_QuestionsBank/loadPart",
                                                    data: {'part_num': part_num},
                                                    dataType: "HTML",
                                                    beforeSend: function () {

                                                    },
                                                    complete: function () {
                                                    },
                                                    error: function (xhr, ajaxOptions, thrownError) {
                                                        Swal.fire({
                                                            text: xhr.status + " - " + thrownError,
                                                            icon: "error",
                                                            buttonsStyling: false,
                                                            confirmButtonText: Lang.Ok,
                                                            customClass: {
                                                                confirmButton: "btn btn-light"
                                                            }
                                                        }).then(function () {
                                                            //KTUtil.scrollTop();
                                                        });
                                                    },
                                                    success: function (response) {
                                                        // console.log(response);
                                                        if (response !== null) {
                                                            $('#paragraph_question').append(response);
                                                            load_questions_paragraph_question_type();
                                                            remove_part();
                                                            add_new_answer();
                                                            $(".select2me").select2({
                                                                dropdownParent: $("#modal1")
                                                            });
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
                            }
                            
                            stepper.goNext();
                        } else {
                            // Show error message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                text: Lang.there_are_some_errors_detected,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: Lang.Ok,
                                customClass: {
                                    confirmButton: "btn btn-light"
                                }
                            }).then(function () {
                                //KTUtil.scrollTop();
                            });
                        }
                    });
                } else {
                    stepper.goNext();
    
                    KTUtil.scrollTop();
                }
            });
    
            // Prev event
            stepperObj.on('kt.stepper.previous', function (stepper) {
                console.log('stepper.previous');
    
                stepper.goPrevious();
                KTUtil.scrollTop();
            });
    
            formSubmitButton.addEventListener('click', function (e) {
                // Validate form before change stepper step
                var validator = validations[1]; // get validator for last form
    
                validator.validate().then(function (status) {
                    console.log('validated!');
    
                    if (status == 'Valid') {
                        // Prevent default button action
                        e.preventDefault();    
                        var formQuestion = $("#kt_modal_question_app_form");
                        var difficulty_degree = formQuestion.find('select[name="difficulty_degree"]').val();
                        var lesson = formQuestion.find('select[name="lesson"]').val();
                        var description = formQuestion.find('textarea[name="description"]').val();
                        var question_type = formQuestion.find('select[name="question_type"]').val();
                        if( (question_type == 1||question_type == 6|| question_type == 7)&& formQuestion.find('div[data-repeater-list="answers"] textarea ').length ==0){
                            Swal.fire(
                                {
                                    text: 'يجب ادخال اجابات',
                                    icon: "error",
                                    buttonsStyling: false,
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
                        }else if (question_type == 5) {
                            var no_of_points = formQuestion.find('input[name="no_of_points"]').val();

                            if (no_of_points == "") {
                                validationForm = false;
                                $('#modal1 .modal-body input[name="no_of_points"]').after('<span class="error">This field is required</span>');
                            }
                        }else if (question_type == 6) { //rearrange
                            $('#modal1 .modal-body input[type="number"]').each(function () {
                                if ($(this).val() == '') {
                                    $(this).after('<span class="error">This field is required</span>');
                                    validationForm = false;

                                }
                            });
                        }
                        // else if(question_type == 3){
                        //     console.log(description.length);
                        //     if (description.length < 1) {
                        //         validationForm = false;
                        //         $(this).after('<span class="error">This field is required</span>');
                        //     }
                        // }

                        formQuestion.find('div[data-repeater-list="answers"] textarea ').each(function () {
                            var area_name = $(this).attr('name');
                            var area_file = area_name.replace("description", "photo1");
                            var file_val = formQuestion.find('input[name="' + area_file + '"]').val();
                            //console.log(file_val);

                            if ($(this).val() == '' && file_val == '') {
                                $(this).after('<span class="error">This field is required</span>');
                                validationForm = false;
                            }
                        });

                        if (validationForm === true) {
                            form_elements = new FormData($('#kt_modal_question_app_form')[0]);

                            var formData = new FormData(formQuestion[0]);
                            form_elements.append('par1', formQuestion.attr("par1"));
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
                                    $("#kt_modal_question_app_form input").prop("disabled", true);
                                    $(".add_questions_to_bank").addClass('hidden');

                                    $("#kt_modal_section_app_form :input").prop("disabled", true);
                                    // Disable button to avoid multiple click 
                                    formSubmitButton.disabled = true;
                                    // Show loading indication
                                    formSubmitButton.setAttribute('data-kt-indicator', 'on'); 
                                    
                                },
                                complete: function () {
                                    $("#kt_modal_question_app_form :input").prop("disabled", false),
                                    formSubmitButton.removeAttribute("data-kt-indicator"),
                                    formSubmitButton.disabled = false
                                },
                                success: function (response) {
                                    if (response !== null && response.hasOwnProperty("Errors")) {
                                        var obj = response["Errors"];
                                        var stringerror = '';
                                        for (var prop in obj) {
                                            stringerror += '* ' + obj[prop] + '</br>';
                                        }
                                        Swal.fire({
                                            text: stringerror,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: Lang.Ok,
                                            customClass: {
                                                confirmButton: "btn btn-light"
                                            }
                                        }).then(function () {
                                            //KTUtil.scrollTop();
                                        });
                                    } else if (response !== null && response.hasOwnProperty("Success")) {
                                        var Res = response['Success'];
                                        Swal.fire({
                                            text:Res.text,
                                            icon:"success",
                                            buttonsStyling: false,
                                            confirmButtonText: Lang.Ok,
                                            customClass:{
                                                confirmButton:"btn btn-primary"
                                            }
                                        }).then((function(e){
                                            setTimeout(function () {
                                                var part_num = $('#modal1 .modal-footer .part-num').val();
                                                loadQuestionOnPartById(part_num, Res.question_id);

                                                // todo::check if repater ot no
                                                if ($('#modal1 .modal-footer #repeat_question').prop("checked") == false) {
                                                    $("#modal1").modal('hide');
                                                } else {
                                                    $(".add_questions_to_bank").removeClass('hidden');
                                                    $("#" + formID + " :input").prop("disabled", false);
                                                    $("#" + formID + " textarea").val('');
                                                    for (var instanceName in CKEDITOR.instances) {
                                                        CKEDITOR.instances[instanceName].setData('');
                                                    }
                                                }
                                            }, Res.life);
                                        }))                                                       
                                    }
                                }
                            });
                        } else {

                        }
                    } else {
                        Swal.fire({
                            text: Lang.there_are_some_errors_detected,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: Lang.Ok,
                            customClass: {
                                confirmButton: "btn btn-light"
                            }
                        }).then(function () {
                            KTUtil.scrollTop();
                        });
                    }
                });
            });
        }
    
        // Init form inputs
        var initForm = function() {
            $(form.querySelector('[name="lesson"]')).on('change', function() {
                validations[0].revalidateField('lesson');
            });  
            $(form.querySelector('[name="difficulty_degree"]')).on('change', function() {
                validations[0].revalidateField('difficulty_degree');
            });  
            $(form.querySelector('[name="question_type"]')).on('change', function() {
                validations[0].revalidateField('question_type');
            });    
            $(form.querySelector('[name="learning_outcomes"]')).on('change', function() {
                validations[0].revalidateField('learning_outcomes');
            });
            
            $(form.querySelector('[name="course"]')).on("change", (function () {
                // Revalidate the field when an option is chosen
                validations[0].revalidateField('course');
                var course_id = document.getElementById("course").value;
                $("#section").html("");
                $("#lesson").html("");
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewVersion_QuestionsBank/getSectionByCourse",
                    data: {course_id: course_id},
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
                        $("#section").select2('val', 'All');
                        $("#section").html(html);
                    }
                })
            })),
            $(form.querySelector('[name="section"]')).on("change", (function () {
                validations[0].revalidateField('section');
                var section_id = document.getElementById("section").value;
                $("#lesson").html("");
                $.ajax({
                    type: "POST",
                    url: URLBase + "NewVersion_QuestionsBank/getLessonsBySection",
                    data: {section_id: section_id},
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
            }))
        }
    
        var initValidation = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            // Step 1
            validations.push(FormValidation.formValidation(
                form,
                {
                    fields: {
                        course: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        section: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        question_type: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                        difficulty_degree: {
                            validators: {
                                notEmpty: {
                                    message: Lang.required_field
                                }
                            }
                        },
                    },
                        
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            ));
    
            // Step 2
            validations.push(FormValidation.formValidation(
                form,
                {                    
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            ));
        }
    
        return {
            // Public Functions
            init: function () {
                // Elements
                modalEl = document.querySelector('#modal1');
    
                if (!modalEl) {
                    return;
                }
    
                modal = new bootstrap.Modal(modalEl);
    
                stepper = document.querySelector('#kt_modal_question_app_stepper');
                form = document.querySelector('#kt_modal_question_app_form');
                formSubmitButton = stepper.querySelector('[data-kt-stepper-action="submit"]');
                formContinueButton = stepper.querySelector('[data-kt-stepper-action="next"]');
                initStepper();
                initForm();
                initValidation();
            }
        };
}();
KTUtil.onDOMContentLoaded((function () {
    OpenModal.init();
}));

