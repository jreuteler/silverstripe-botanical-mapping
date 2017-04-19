(function ($) {
    jQuery.entwine('ss.geolocation', function ($) {

        jQuery('.botanical-mapping .ss-gridfield-buttonrow .ss-gridfield-add-new-inline').entwine({

            onclick: function () {
                $('html,body').animate({scrollTop: $(".botanical-mapping .action.save-btn").offset().top}, 'slow');
            },

        });
    });
})(jQuery);




// adds change listener to value holder field and redirects to the elements edit-page (proof in concept)
(function ($) {
    $.entwine('ss.autocomplete-edit', function ($) {

        $('.field.autocomplete.autocomplete-edit input.text').entwine({

            onmatch: function () {

                var input = $(this);
                var hiddenInput = input.parent().find(':hidden');
                var valueLabel = input.parent().find('.value-holder .value');

                valueLabel.bind("DOMSubtreeModified", function () {
                    var segmentStr = window.location.pathname;
                    var segmentArray = segmentStr.split('/');
                    var objectType = input.attr('name');
                    var objectTypeArray = objectType.split('|');
                    var redirectUrl = '';
                    for (i = 0; i < segmentArray.length; i++) {
                        redirectUrl += segmentArray[i] + '/';

                        if (segmentArray[i] == 'botanical-frontend') {
                            break;
                        }
                    }

                    redirectUrl += objectTypeArray[0] + '/edit/' + hiddenInput.val();
                    window.location = redirectUrl;

                });


            },


        });
    });
})(jQuery);
