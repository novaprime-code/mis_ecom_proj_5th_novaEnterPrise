<?php
if( ! class_exists('BeRocket_AAPF_compat_woo_multi_currency') ) {
    
    class BeRocket_AAPF_compat_woo_multi_currency {
        function __construct() {
            add_filter('wmc_get_link', array($this, 'wmc_get_link'));
        }
        function wmc_get_link($link) {
            $link = remove_query_arg( array('filters'), $link);
            return $link;
        }
    }
    new BeRocket_AAPF_compat_woo_multi_currency();
}
if ( ! function_exists( 'wmc_get_default_price' ) && function_exists('wmc_get_price') ) {
	function wmc_get_default_price( $price, $currency_code = false ) {
        $price_multiplier = wmc_get_price(1, $currency_code);
        $price = $price / $price_multiplier;

		return $price;
	}
}
