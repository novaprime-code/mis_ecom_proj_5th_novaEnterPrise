<?php
if( ! class_exists('BeRocket_AAPF_compat_Elementor_pro') ) {
    class BeRocket_AAPF_compat_Elementor_pro {
        public $attributes;
        function __construct() {
            add_action("elementor/element/woocommerce-products/section_content/before_section_end", array($this, 'add_control'), 10, 2);
            add_action('elementor/widget/before_render_content', array($this, 'before_render_content'), 10, 1);
        }
        function add_control($element, $args) {
            $element->add_control(
                'bapf_apply',
                [
                    'label' => __( 'Apply BeRocket AJAX Filters', 'BeRocket_AJAX_domain' ),
                    'type' => Elementor\Controls_Manager::SELECT,
                    'description' => __( 'All Filters will be applied to this module. You need correct unique selectors to work correct', 'BeRocket_AJAX_domain' ),
                    'default' => 'default',
                    'options' => [
                        'default' => __( 'Default', 'BeRocket_AJAX_domain' ),
                        'enable'  => __( 'Enable', 'BeRocket_AJAX_domain' ),
                        'disable' => __( 'Disable', 'BeRocket_AJAX_domain' ),
                    ],
                ]
            );
        }
        function before_render_content($element) {
            remove_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'enable_filtering'), 1000);
            if( $element->get_name() == 'woocommerce-products' ) {
                $this->attributes = $element->get_settings();
                add_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'enable_filtering'), 1000);
            }
        }
        function enable_filtering($enabled) {
            if( ! empty($this->attributes['bapf_apply']) && $this->attributes['bapf_apply'] == 'enable' ) {
                $enabled = true;
            } elseif( ! empty($this->attributes['bapf_apply']) && $this->attributes['bapf_apply'] == 'disable' ) {
                $enabled = false;
            } elseif( ! empty($this->attributes['query_post_type']) && $this->attributes['query_post_type'] == 'current_query' ) {
                $enabled = true;
            }
            return $enabled;
        }
    }
    new BeRocket_AAPF_compat_Elementor_pro();
}