var pageMethods = function () {
    var OpenModal = function () {
        var $modal = $('#modal1');
        $(document).on('click', '.view-popup-video', function () {
            var el = $(this);
            // AJAX request
            $.ajax({
                url: URLBase + 'NewHome_Centers/DetailsModal',
                type: 'post',
                data: {url: el.attr('url'), type: el.attr('type')},
            cache: false,
                beforeSend: function() {
                   // alert('');
                    $modal.modal('show');

            var loading=   '<div class="loading-icon text-center d-flex flex-column align-items-center justify-content-center">';
                    loading+=    ' <img  src="'+URLBase+'files/s0ew4rmezihulupvirra.jpg" alt="logo-imgnnnnnnnnnnn"> ';
                    loading+= ' <img class="loading-logo" src="'+URLBase+'assets/new_theme/assets/img/logo/preloader.svg" alt="">';
                    loading+=' </div>';

                // <img src='"+URLBase+"assets/global/plugins/anything_slider/demos/colorbox/loading.gif'/>
                $modal.find('.modal-body').html(loading);
                 },success: function(response){
                    // Add response in Modal body
                    $modal.html(response);
                }
            });
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
    pageMethods.init();
});