var pageMethods = function () {

    $(document).on('click', '.items_filter', function(e) {
        //console.log($(this).attr("filter-type"));

        url = get_search_url();

        window.location.href = url;

        // console.log(window.location.pathname);
        // console.log(window.location.href.split('&')[0]);


    });

    function get_search_url(){
        var url = window.location.pathname + "?";
        var categories = ",";
        var plans = ",";
        var courses = ",";
        var languages = ",";
        var ratings = ",";
        var prices = ",";
        $('.category_filter:checkbox:checked').each(function () {
            categories+=$(this).val() + ",";
        })
        if (categories != ","){
            url += "&categories={" + categories + "}";
        }

        $('.plan_filter:checkbox:checked').each(function () {
            plans+=$(this).val() + ",";
        })
        if (plans != ","){
            url += "&plans={" + plans + "}";
        }

        $('.course_filter:checkbox:checked').each(function () {
            courses+=$(this).val() + ",";
        })
        if (courses != ","){
            url += "&courses={" + courses + "}";
        }

        $('.language_filter:checkbox:checked').each(function () {
            languages+=$(this).val() + ",";
        })
        if (languages != ","){
            url += "&languages={" + languages + "}";
        }

        $('.rating_filter:checkbox:checked').each(function () {
            ratings+=$(this).val() + ",";
        })
        if (ratings != ","){
            url += "&ratings={" + ratings + "}";
        }

        $('.price_filter:checkbox:checked').each(function () {
            prices+=$(this).val() + ",";
        })
        if (prices != ","){
            url += "&prices={" + prices + "}";
        }

        return url;
        
    }

    // var url = '?parent=' + $('#parent').val() + '&year=' + $('#academic_year').val();
    // var terms = $('#term').val();
    // var arrTerm=",";
    // for (var j=0;j< terms.length;j++)
    // {
    //     arrTerm+=terms[j] + ",";
    // }
    // url += "?&terms={" + arrTerm + "}";

    // $(document).on('click', '.pagination a', function(e) {
    //     // e.preventDefault();
    //     // var page = $(this).attr('href').split('page=')[1];
    //     // $.get(URLBase + 'NewHome_Sections/GetData?page=' + page, function(data) {
    //     //     $('#my_view_container').html(data);
    //     // });
    //     // var page = $(this).attr('href').split('page=')[1];
    //     // $.get(URLBase + 'NewHome_Sections/ajax_pagination?page=' + page, function(data) {
    //     //     $('#sections_list').html(data);
    //     // });

    //     e.preventDefault();
    //     var page = $(this).attr('href').split('page=')[1];
    //     $.post(URLBase + 'NewHome_Sections/ajax_pagination?page=' + page, {
    //         pagination: <?= json_encode($pagination); ?>
    //     }, function(data) {
    //         $('#sections_list').html(data);
    //     });
    // });

    // $(document).on('click', '.pagination a', function(e) {
    //     e.preventDefault();
    //     var page = $(this).attr('href').split('page=')[1];
    //     console.log(page);
    //     $.ajax({
    //         type: "POST",
    //         url: URLBase + "NewHome_Sections/GetData",
    //         data: {page: page},
    //         cache: false,
    //             contentType: false,
    //             processData: false,
    //         dataType: "JSON",
    //         beforeSend: function () {
                
    //         },
    //         complete: function () {
                
    //         },
    //         error: function (xhr, ajaxOptions, thrownError) {
    //             console.log(xhr);
    //             swal('Error', xhr.status + " - " + thrownError, "error");
    //         },
    //         success: function (response) {
    //             $("#all_sections").html(response);
    //         }
    //     });
        
    // });

    var DisplaySections = function () {
        $.ajax({
            type: "POST",
            url: URLBase + "NewHome_Sections/GetData",
            dataType: "JSON",
            beforeSend: function () {
                
            },
            complete: function () {
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                swal('Error', xhr.status + " - " + thrownError, "error");
            },
            success: function (response) {
                $("#all_sections").html(response);
            }
        });
       
    }

   


    return {
        //main function to initiate the module
        init: function () {
            //DisplaySections();
        }

    };

}();

// Class definition
var KTFormsCKEditorDocument = function () {
    // Private functions
    var exampleDocument = function () {
        DecoupledEditor
            .create(document.querySelector('#kt_docs_ckeditor_document'))
            .then(editor => {
                const toolbarContainer = document.querySelector('#kt_docs_ckeditor_document_toolbar');

                toolbarContainer.appendChild(editor.ui.view.toolbar.element);
            })
            .catch(error => {
                console.error(error);
            });
    }

    return {
        // Public Functions
        init: function () {
            exampleDocument();
        }
    };
}();




jQuery(document).ready(function () {
    pageMethods.init();
    KTFormsCKEditorDocument.init();
});