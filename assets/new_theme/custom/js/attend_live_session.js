var AttendClassRoom = function () {
    var pageMethods = function () {
        $(document).on('click', '.enter_classroom', function (e) {
            e.preventDefault();
            $(this).parent().addClass('hidden');
            $(this).closest('.class-metting').find('.loading-spinner').removeClass('hidden');
            var el = $(this);
            var imagem = URLBase + "assets/new_theme/assets/img/shape/shape-light.png";
            $.ajax({
                type: "POST",
                url: URLBase + "NewHome_Live_Meetings/AttendClass",
                data: {par1: el.attr('par1')},
                dataType: "JSON",
                beforeSend: function () {
                    
                },
                complete: function () {
                    $('.join_meeting').removeClass('hidden');
                    $('.loading-spinner').addClass('hidden');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        title: xhr.status + " - " + thrownError,
                        text: "",
                        icon: "error",
                        iconHtml: " <img src='" + imagem + "' >",
                    })
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
                            title: stringerror,
                            text: "",
                            icon: "error",
                            iconHtml: " <img src='" + imagem + "' >",
                        })
                    }else if (response !== null && response.hasOwnProperty("Success")) {
                        var Res = response['Success'];
                        Swal.fire({
                            title: Res.title,
                            text: Res.content,
                            icon: "success",
                            iconHtml: " <img src='" + imagem + "' >",
                        }).then((value) => {
                            if(Res.redirect){
                                window.open(Res.redirect,'MyWindow','width=1500,height=800');
                            }
                        });
                    }
                }
            });
        });        
    }
    return {
        //main function to initiate the module
        init: function () {
            pageMethods();
        }
    };
}();
jQuery(document).ready(function () {
    AttendClassRoom.init();
});








