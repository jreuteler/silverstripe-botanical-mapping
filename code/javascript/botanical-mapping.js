(function ($) {
    jQuery.entwine('ss.geolocation', function ($) {

        jQuery('.botanical-mapping .ss-gridfield-buttonrow .ss-gridfield-add-new-inline').entwine({

            onclick: function () {
                $('html,body').animate({scrollTop: $(".botanical-mapping .action.save-btn").offset().top}, 'slow');
            },

        });
    });
})(jQuery);
