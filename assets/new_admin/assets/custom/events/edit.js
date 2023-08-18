
var TableDatatablesAjax = function () {

    var OpenModal = function () {
        // general settings
        // $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        //         '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        //         '<div class="progress progress-striped active">' +
        //         '<div class="progress-bar" style="width: 100%;"></div>' +
        //         '</div>' +
        //         '</div>';

        // $.fn.modalmanager.defaults.resize = true;

        //ajax demo:
        var $modal = $('#Level1');

        $(document).on('click', '.OpenDetails', function () {
            // create the backdrop and wait for next modal to be triggered
            //$('body').modalmanager('loading');
            $.ajax({
                url: URLBase + 'NewVersion_Events/DetailsModal',
                type: 'get',
                data: {par1: 1},
                success: function(response){ 
                     // Add response in Modal body
                     //$('.modal-body').html(response);
    
                     // Display Modal
                     $('#Level1').modal('show'); 
                }
          });
            // var el = $(this);
            // setTimeout(function () {
            //     $modal.load(URLBase + 'NewVersion_Events/DetailsModal', {par1: 1}, function () {
            //         $modal.modal();
            //         //App.initAjax();
            //     });
            // }, 1000);
        });

        

        

       
    }

    return {
        //main function to initiate the module
        init: function () {
            OpenModal();
        }

    };

}();


jQuery(document).ready(function () {
    //TableDatatablesAjax.init();
});