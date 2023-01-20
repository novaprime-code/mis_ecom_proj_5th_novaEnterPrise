<?php
if( ! class_exists('BeRocket_AAPF_shortcode_apply_filters') ) {
    class BeRocket_AAPF_shortcode_apply_filters {
        function __construct() {
            add_shortcode( 'brapf_next_shortcode_apply', array( $this, 'shortcode_apply' ) );
        }
        public function shortcode_apply( $atts = array() ) {
            if( ! is_array($atts) ) $atts = array();
            $atts = array_merge(array('apply' => true), $atts);
            $this->remove_all();
            if( $atts['apply'] === 'false') $atts['apply'] = false;
            if($atts['apply'] !== 'default') {
                $atts['apply'] = (bool) $atts['apply'];
                if( $atts['apply'] ) {
                    $this->add_apply_filter();
                } else {
                    $this->add_not_apply_filter();
                }
            }
        }
        public function apply_filter_to_shortcode($enable) {
            $this->remove_all();
            return true;
        }
        public function not_apply_filter_to_shortcode($enable) {
            $this->remove_all();
            return false;
        }
        public function is_query_product($post_type) {
            if( is_array($post_type) && count($post_type) > 1 ) {
                return false;
            } elseif(is_array($post_type)) {
                return array_pop($post_type) == 'product';
            } else {
                return $post_type == 'product';
            }
        }
        public function apply_filter_to_query($query) {
            $post_type = $query->get('post_type');
            if( ! $this->is_query_product($post_type) ) return;
            $this->remove_all();
            $query->set('bapf_apply', true);
            $query->set('bapf_save_query', true);
            $unset_values = array(
                'bapf_tax_applied',
                'bapf_meta_applied',
                'bapf_postin_applied',
                'bapf_postnotin_applied'
            );
            foreach( $unset_values as $unset_value ) {
                if( isset($new_args[$unset_value]) ) {
                    $query->set($unset_value, false);
                }
            }
        }
        public function not_apply_filter_to_query($query) {
            $post_type = $query->get('post_type');
            if( ! $this->is_query_product($post_type) ) return;
            $this->remove_all();
            $query->set('bapf_apply', false);
        }
        public function add_apply_filter() {
            add_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'apply_filter_to_shortcode'));
            add_filter('pre_get_posts', array($this, 'apply_filter_to_query'));
        }
        public function add_not_apply_filter() {
            add_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'not_apply_filter_to_shortcode'));
            add_filter('pre_get_posts', array($this, 'not_apply_filter_to_query'));
        }
        public function remove_all() {
            remove_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'apply_filter_to_shortcode'));
            remove_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'not_apply_filter_to_shortcode'));
            remove_filter('pre_get_posts', array($this, 'apply_filter_to_query'));
            remove_filter('pre_get_posts', array($this, 'not_apply_filter_to_query'));
        }
    }
    new BeRocket_AAPF_shortcode_apply_filters();
}