(function ($, Drupal, drupalSettings) {

    Drupal.behaviors.LocationIndicator = {
        attach: function (context, settings) {

            var storefrontName = $.cookie('Drupal.visitor.storefrontName');
            var storefrontUrl = $.cookie('Drupal.visitor.storefrontUrl');

            if (typeof storefrontName != 'undefined') {
                var siteLink = '<li role="menuitem" class="contextual-site-url"><a href="' + storefrontUrl + '">' + storefrontName + '</a></li>';
                var locationIndicator = $('ul.main-menu a[href="/location-finder"]');
                locationIndicator.once('store-menu-title').text(storefrontName);
                locationIndicator.once('store-menu-url').attr('href', storefrontUrl);
            }
            else {
                $('.product-description-form').once('no-context-hide-product').addClass('no-stores');
                var storeLink = '<h3 class="no-store-redirect"> You are currently not shopping a specific store. To order online, <a href="/location-finder">find a location near you</a>.</h3>';
                $('.product-description-form').once('no-context-store-locator').append(storeLink);
            }
        }
    };
})(jQuery, Drupal, drupalSettings);