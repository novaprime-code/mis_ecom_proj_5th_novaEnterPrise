<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_radio') ) {
    class BeRocket_AAPF_Template_Style_radio extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'radio',
                'template'      => 'checkbox',
                'name'          => 'Radio',
                'file'          => __FILE__,
                'style_file'    => 'css/radio.css',
                'script_file'   => 'js/radio.js',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/radio.png',
                'version'       => '1.0',
                'image_price'   => plugin_dir_url( __FILE__ ) . 'paid/images/radio-price.png',
            );
            parent::__construct();
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            if( ! isset($template['template']['attributes']) || ! is_array($template['template']['attributes']) ) {
                $template['template']['attributes'] = array();
            }
            if( ! isset($template['template']['attributes']['class']) ) {
                $template['template']['attributes']['class'] = array();
            }
            if( ! is_array($template['template']['attributes']['class']) ) {
                $template['template']['attributes']['class'] = array($template['template']['attributes']['class']);
            }
            $template['template']['attributes']['class'][] = 'bapf_asradio2';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_radio();
}
