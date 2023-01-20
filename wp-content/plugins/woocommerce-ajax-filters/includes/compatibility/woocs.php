<?php
if( ! class_exists('BeRocket_AAPF_compat_woocs') ) {
    
    class BeRocket_AAPF_compat_woocs {
        function __construct() {
            add_action( 'woocommerce_price_filter_widget_min_amount', array( $this, 'return_custom_price_one' ) );
            add_action( 'woocommerce_price_filter_widget_max_amount', array( $this, 'return_custom_price_one' ) );
            add_filter('berocket_min_max_filter', array( $this, 'invert_custom_price_one' ) );
        }
        function return_custom_price_one($price) {
            $price = apply_filters('woocs_convert_price', $price);
            return $price;
        }
        function invert_custom_price_one($price) {
            if( is_array($price) ) {
                foreach($price as &$single) {
                    $single = apply_filters('woocs_back_convert_price', $single);
                }
            }
            return $price;
        }
    }
    new BeRocket_AAPF_compat_woocs();
}
