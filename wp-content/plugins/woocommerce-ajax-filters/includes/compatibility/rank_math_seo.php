<?php
if( ! class_exists('BeRocket_AAPF_compat_rank_math_seo') ) {
    class BeRocket_AAPF_compat_rank_math_seo {
        private static $seo_meta;
        function __construct() {
            add_action('braapf_seo_meta_description', array($this, 'description'), 10, 1);
            add_action('braapf_seo_meta_title', array($this, 'title'), 10, 1);
            add_filter('rank_math/frontend/canonical', array($this, 'add_canonical'));
        }
        function description($instance) {
            self::$seo_meta = $instance;
            add_filter('rank_math/frontend/description', array($this, 'add_description'));
        }
        function add_description($text) {
            $add_text = trim(self::$seo_meta->meta_description(''));
            if( ! empty($add_text) ) {
                $text = $text.' '.$add_text;
            }
            return $text;
        }
        function title($instance) {
            self::$seo_meta = $instance;
            add_filter('rank_math/frontend/title', array($this, 'add_title'));
        }
        function add_title($title) {
            $title = self::$seo_meta->wpseo_title($title);
            return $title;
        }
        function add_canonical($canonical) {
            if( class_exists('BeRocket_AAPF_paid') ) {
                remove_filter('berocket_wp_head_canonical', array($this, 'prevent_default_canonical'));
                $BeRocket_AAPF_paid = BeRocket_AAPF_paid::getInstance();
                if( $BeRocket_AAPF_paid->is_canonical_applied() ) {
                    $canonical = $BeRocket_AAPF_paid->get_current_canonical_url();
                }
                add_filter('berocket_wp_head_canonical', array($this, 'prevent_default_canonical'));
            }
            return $canonical;
        }
        function prevent_default_canonical() {
            return false;
        }
    }
    new BeRocket_AAPF_compat_rank_math_seo();
}
