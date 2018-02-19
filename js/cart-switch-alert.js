(function ($, Drupal) {
    Drupal.behaviors.cartSwitchAlert = {
        attach: function (context, settings) {
            $('#views-form-location-finder-page-1').off().on('click', '.cta a', function(e){
                var storefrontUrl = $.cookie('Drupal.visitor.storefrontUrl');
                var url = $(this).attr('href');
                if (typeof storefrontUrl != 'undefined' && storefrontUrl != url) {
                    if (confirm('Warning: You are attempting to switch stores, continuing will empty the contents of your cart.') != true) {
                        event.preventDefault();
                    }
                }
            });
        }
    };
})(jQuery, Drupal);