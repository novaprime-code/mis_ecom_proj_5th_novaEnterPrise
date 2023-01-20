<?php
if( ! class_exists('BeRocket_AAPF_currency_exchange') ) {
    class BeRocket_AAPF_currency_exchange {
        function __construct() {
            add_filter('bapf_uparse_price_for_filtering_convert', array($this, 'convert_price'));
        }
        function convert_price($price) {
            global $woocommerce_wpml;
            if( ! empty($woocommerce_wpml) && is_object($woocommerce_wpml)
                && property_exists($woocommerce_wpml, 'multi_currency') && is_object($woocommerce_wpml->multi_currency)
                && property_exists($woocommerce_wpml->multi_currency, 'prices') && is_object($woocommerce_wpml->multi_currency->prices)
                && method_exists($woocommerce_wpml->multi_currency->prices, 'unconvert_price_amount') ) {
                $price = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($price);
            }
            if( function_exists('wmc_get_default_price') ) {
                $price = wmc_get_default_price($price);
            }
            if( class_exists('BeRocket_AAPF_compat_WCPBC') ) {
                $price = BeRocket_AAPF_compat_WCPBC::to_base_rate($price);
            }
            return $price;
        }
    }
    new BeRocket_AAPF_currency_exchange();
}