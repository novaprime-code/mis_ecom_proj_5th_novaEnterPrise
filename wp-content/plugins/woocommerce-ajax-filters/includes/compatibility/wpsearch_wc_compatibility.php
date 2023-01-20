<?php
if( ! class_exists('BeRocket_AAPF_compat_wpsearch_woocommerce') ) {
    class BeRocket_AAPF_compat_wpsearch_woocommerce {
        public $instance = false;
        function __construct() {
            add_action('searchwp_woocommerce_before_search', array($this, 'save_instance'));
            add_filter('berocket_widget_attribute_type_terms', array($this, 'remove_hook'), 1);
            add_filter('berocket_widget_attribute_type_terms', array($this, 'add_hook'), 999999);
        }
        function save_instance($instance) {
            $this->instance = $instance;
        }
        function remove_hook($filtered) {
            if( $this->instance != false ) {
                remove_filter( 'woocommerce_price_filter_widget_min_amount', array( $this->instance, 'get_price_min_amount' ), 999 );
                remove_filter( 'woocommerce_price_filter_widget_max_amount', array( $this->instance, 'get_price_max_amount' ), 999 );
            }
            return $filtered;
        }
        function add_hook($filtered) {
            if( $this->instance != false ) {
                add_filter( 'woocommerce_price_filter_widget_min_amount', array( $this->instance, 'get_price_min_amount' ), 999 );
                add_filter( 'woocommerce_price_filter_widget_max_amount', array( $this->instance, 'get_price_max_amount' ), 999 );
            }
            return $filtered;
        }
    }
    new BeRocket_AAPF_compat_wpsearch_woocommerce();
}