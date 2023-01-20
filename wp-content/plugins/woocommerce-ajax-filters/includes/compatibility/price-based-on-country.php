<?php
//use this hooks "berocket_price_filter_widget_min_amount" AND "berocket_price_filter_widget_max_amount"
if( ! class_exists('BeRocket_AAPF_compat_WCPBC') ) {
    class BeRocket_AAPF_compat_WCPBC {
        private static $_zone = null;
        function __construct() {
            add_filter("berocket_price_filter_widget_min_amount", array(__CLASS__, 'to_current_rate'), 10, 2);
            add_filter("berocket_price_filter_widget_max_amount", array(__CLASS__, 'to_current_rate'), 10, 2);
            //add_filter("berocket_price_filter_meta_key", array(__CLASS__, 'price_filter_meta_key'), 10, 2);
        }
        static function to_current_rate($amount, $untoched_amount = FALSE) {
            $_zone = WCPBC_Pricing_Zones::get_zone();
            if( is_object( $_zone ) && in_array( get_class( $_zone ), array( 'WCPBC_Pricing_Zone', 'WCPBC_Pricing_Zone_Pro' ), true ) ) {
                if( $untoched_amount === FALSE ) {
                    $untoched_amount = $amount;
                }
                $amount = $_zone->get_exchange_rate_price($untoched_amount, false);
            }
            return $amount;
        }
        static function to_base_rate($amount, $untoched_amount = FALSE) {
            $_zone = WCPBC_Pricing_Zones::get_zone();
            if( is_object( $_zone ) && in_array( get_class( $_zone ), array( 'WCPBC_Pricing_Zone', 'WCPBC_Pricing_Zone_Pro' ), true ) ) {
                if( $untoched_amount === FALSE ) {
                    $untoched_amount = $amount;
                }
                if ( empty( $amount ) ) {
                    $value = $amount;
                } else {
                    $value = $_zone->get_base_currency_amount( $amount );
                    $amount = $value;
                }
            }
            return $amount;
        }
        static function price_filter_meta_key($meta_key, $place) {
            $meta_keys = apply_filters('woocommerce_price_filter_meta_keys', array($meta_key));
            $meta_key = $meta_keys[0];
            return $meta_key;
        }
    }
    new BeRocket_AAPF_compat_WCPBC();
}
