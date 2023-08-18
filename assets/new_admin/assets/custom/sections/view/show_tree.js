"use strict";

function ShowTree(section_id){
    console.log(section_id);
    $("#kt_docs_jstree_dragdrop").jstree({
        "core": {
            "themes": {
                "responsive": false
            },
            // so that create works
            "check_callback": true,
            'data': {
                'url': function(node) {
                    return URLBase + 'NewVersion_Sections/getTree'; // Demo API endpoint -- Replace this URL with your set endpoint
                },
                'data': function(node) {
                    return {
                        'parent': node.id,
                        'section_id': section_id
                    };
                },
                dataType: "JSON",
            }
        },
        "types": {
            "default": {
                "icon": "ki-outline ki-folder text-primary"
            },
            "file": {
                "icon": "ki-outline ki-file  text-primary"
            }
        },
        "state": {
            "key": "demo7"
        },
        "plugins": ["dnd", "state", "types","wholerow"]
    })
    .on('ready.jstree', function () {
        $("#kt_docs_jstree_dragdrop").jstree("close_all");
        var $tree = $(this);
        $($tree.jstree().get_json($tree, {
            flat: true
        }))
    });
}

var KTJSTreeDragDrop = function() {
    var handleModals = () => {
        var $modal = $('#modal1');
        $(document).on('click', '.open_file', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections/Show_File',
                type: 'post',
                data: {par1: el.attr('doc_id')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                }
            });
        });

        $(document).on('click', '.edit_item', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections/Edit_Item',
                type: 'post',
                data: {item_id: el.attr('item_id'), type: el.attr('type')},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    handleAddEditItem.init();
                }
            });
        });

        $(document).on('click', '.add_new', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections/Edit_Item',
                type: 'post',
                data: {item_id: el.attr('parent'), type: el.attr('item-type'), op_type: 'add_new'},
                cache: false,
                beforeSend: function () {
                    // alert('');
                    $modal.modal('show');
                    var loading = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>';
                    $modal.find('.modal-dialog').html(loading);
                }, success: function (response) {
                    // Add response in Modal body
                    $modal.html(response);
                    handleAddEditItem.init();
                }
            });
        });

        $(document).on('click', '#delete_item', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewVersion_Sections/Delete_Item',
                type: 'post',
                data: {item_id: el.attr('item-id'), type: el.attr('item-type')},
                dataType: "JSON",
                beforeSend: function () {
                    document.querySelector('#delete_item').setAttribute('data-kt-indicator', 'on');
                    $('#kt_modal_submit').disabled = true;
                }, 
                complete: function () {
                    document.querySelector('#delete_item').setAttribute('data-kt-indicator', 'off');
                },
                success: function (response) {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        var obj = response["Errors"];
                        var stringerror = '';
                        for (var prop in obj) {
                            stringerror += '* ' + obj[prop] + '</br>';
                        }
                        Swal.fire({
                            text:stringerror,
                            icon:"error",
                            buttonsStyling:false,
                            confirmButtonText:Lang.Ok,
                            customClass:{confirmButton:"btn btn-light"}
                        })                                    
                    } else if (response !== null && response.hasOwnProperty("Success")) {
                        var Res = response['Success'];
                        Swal.fire({
                            text: Res.text,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: Lang.Ok,
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                $modal.modal('hide');
                                $("#kt_docs_jstree_dragdrop").jstree("destroy");  
                                ShowTree(Res.section_id);
                            }
                        });	
                    }
                    
                    
                }
            });
        });
    }

    // Private functions
    var exampleDragDrop = function() {
        ShowTree(section_id);
    }

    return {
        // Public Functions
        init: function() {
            exampleDragDrop();
            handleModals();
        }
    };
}();

var handleAddEditItem = function () {
    var submitButton;
    var cancelButton;
    var validator;
    var form;
    var $modal;
    var form_elements;

    // Init form inputs
    var handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
                    'name': {
						validators: {
							notEmpty: {
								message: 'name is required'
							}
						}
					} 
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
		);

		// // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
        // $(form.querySelector('[name="country"]')).on('change', function() {
        //     // Revalidate the field when an option is chosen
        //     validator.revalidateField('country');
        // });

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();

			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');
					if (status == 'Valid') {
                        form_elements = new FormData($('#Form1')[0]);
                        if(document.querySelector("#kt_modal_upload_dropzone") != null){
                            var files = $('#kt_modal_upload_dropzone').get(0).dropzone.getAcceptedFiles();
                            for (var i = 0; i< files.length; i++) {
                                form_elements.append('files[]', files[i])
                            }
                        }
                        form_elements.append('description', $('#kt_docs_ckeditor_document').html());
                        if($('#Form1').attr("parent") != undefined){
                            form_elements.append('parent', $('#Form1').attr("parent"));
                        }
                        if($('#Form1').attr("par1") != undefined){
                            form_elements.append('par1', $('#Form1').attr("par1"));
                        }
                        form_elements.append('type', $('#Form1').attr("type"));
                        $.ajax({
                            type: "POST",
                            url: URLBase + "NewVersion_Sections/ItemToDB",
                            data: form_elements,
                            //async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: "JSON",
                            beforeSend: function () {
                                $("#Form1 :input").prop("disabled", true)
                                submitButton.setAttribute('data-kt-indicator', 'on');
                                // Disable submit button whilst loading
                                submitButton.disabled = true;
                            },
                            complete: function () {
                                $("#Form1 :input").prop("disabled", false),
                                submitButton.removeAttribute('data-kt-indicator');
                            },
                            success: function (response)
                            {
                                if (response !== null && response.hasOwnProperty("Errors")) {
                                    var obj = response["Errors"];
                                    var stringerror = '';
                                    for (var prop in obj) {
                                        stringerror += '* ' + obj[prop] + '</br>';
                                    }
                                    Swal.fire({
                                        text:stringerror,
                                        icon:"error",
                                        buttonsStyling:false,
                                        confirmButtonText:Lang.Ok,
                                        customClass:{confirmButton:"btn btn-light"}
                                    })                                    
                                } else if (response !== null && response.hasOwnProperty("Success")) {
                                    var Res = response['Success'];
                                    Swal.fire({
                                        text: Res.text,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: Lang.Ok,
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            // Hide modal
                                            $modal.modal('hide');
                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                            // Redirect to customers list page
                                            $("#kt_docs_jstree_dragdrop").jstree("destroy");  
                                            ShowTree(section_id);
                                        }
                                    });	
                                }
                            }
                        })													
					} else {
						Swal.fire({
							text: "Sorry, looks like there are some errors detected, please try again.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: Lang.Ok,
							customClass: {
								confirmButton: "btn btn-primary"
							}
						});
					}
				});
			}
		});

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: Lang.Ok,
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });
    }
    var initDropZone = function (e,t) {
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
                    confirmButtonText:Lang.Ok,customClass:{confirmButton:"btn btn-primary"}
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

    return {
        // Public functions
        init: function () {
            // Elements
            $modal = $('#modal1'); //new bootstrap.Modal(document.querySelector('#modal1'));
            form = document.querySelector('#Form1');
            submitButton = form.querySelector('#kt_modal_submit');
            cancelButton = form.querySelector('#kt_modal_cancel');
            const e="#kt_modal_upload_dropzone";
            var t=document.querySelector(e);
            handleForm();
            if(t != null){
                console.log(t);
                initDropZone(e,t);
            }
            DecoupledEditor
            .create(document.querySelector('#kt_docs_ckeditor_document'))
            .then(editor => {
                const toolbarContainer = document.querySelector( '#kt_docs_ckeditor_document_toolbar' );
                toolbarContainer.appendChild( editor.ui.view.toolbar.element );
            })
            .catch(error => {
                console.error(error);
            });

        }
    };
}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTJSTreeDragDrop.init();
});