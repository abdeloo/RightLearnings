"use strict";
var PageMethods3 = function() {
    var handleStatistics = () => {

        var target2 = document.querySelector("#show_homework");
        var blockUI2 = new KTBlockUI(target2);
               
        getHomeWorks();
        
        function getHomeWorks(){            
            $.ajax({
                url: URLBase + 'NewVersion_Teachers_Home/GetHomeWorkStatistics',
                type: 'post',
                data: {},
                cache: false,
                beforeSend: function () {
                    blockUI2.block();
                },
                complete: function () {
                    blockUI2.release();
                }, success: function (response) {
                    $('#show_homework').html(response);              
                }
            });
        }
    }
    return {
            // Public Functions
            init: function() {
                handleStatistics();
            }
    };
}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageMethods3.init();
});