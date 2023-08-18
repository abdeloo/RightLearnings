function DeleteItemAjax(el,reqPath,datatable,msg) {
    if(datatable == null){
        msg = Lang.Are_you_sure_you_want_to_delete_it;
    }
    Swal.fire({
        text: msg,
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: Lang.Yes,
        cancelButtonText: Lang.Cancel,
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: URLBase + reqPath ,
                data: {id: el.attr('par1')},
                dataType: "JSON",
                beforeSend: function () {
                    
                },
                complete: function () {
                   
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        text:xhr.status + " - " + thrownError,
                        icon:"error",
                        buttonsStyling:false,
                        confirmButtonText: Lang.Ok,
                        customClass:{confirmButton:"btn btn-light"}
                    })
                },
                success: function (response)
                {
                    if (response !== null && response.hasOwnProperty("Errors")) {
                        console.log(response["Errors"]);
                        var stringerror = response["Errors"];
                        Swal.fire({
                            text:stringerror,
                            icon:"error",
                            buttonsStyling:false,
                            confirmButtonText: Lang.Ok,
                            customClass:{confirmButton:"btn btn-light"}
                        })                                    
                    } else if (response !== null && response.hasOwnProperty("Success")) {
                        var Res = response['Success'];
                        Swal.fire({
                            text: Res.content,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: Lang.Ok,
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove current row
                            if(datatable != null){
                                var table = $('#' + datatable).DataTable();
                                table.draw();
                            }
                        }).then(function () {
                            // Detect checked checkboxes
                            //toggleToolbars();
                        });
                    }
                }
            })													
        } else if (result.dismiss === 'cancel') {
            Swal.fire({
                text: customerName + " was not deleted.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });




}

