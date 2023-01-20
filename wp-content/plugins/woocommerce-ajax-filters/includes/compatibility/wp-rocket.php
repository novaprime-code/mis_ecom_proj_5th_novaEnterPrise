<?php
if( ! class_exists('BeRocket_AAPF_compat_wp_rocket') ) {
    class BeRocket_AAPF_compat_wp_rocket {
        function __construct() {
            add_filter('rocket_defer_inline_exclusions', array($this, 'defer_exclusions'));
        }
        function defer_exclusions($list) {
            if( ! is_array($list) ) {
                $list = array();
            }
            $list[] = 'var the_ajax_script';
            return $list;
        }
    }
    new BeRocket_AAPF_compat_wp_rocket();
}