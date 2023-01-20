<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_radio_check') ) {
    class BeRocket_AAPF_Template_Style_radio_check extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'radio-check',
                'template'      => 'checkbox',
                'name'          => 'Radio Check',
                'file'          => __FILE__,
                'style_file'    => 'css/radio-check.css',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/radio-check.png',
                'version'       => '1.0',
                'image_price'   => plugin_dir_url( __FILE__ ) . 'paid/images/radio-check-price.png',
            );
            parent::__construct();
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $this->array_set($template, array('template', 'attributes', 'class'));
            $template['template']['attributes']['class'][] = 'bapf_radio_chck';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_radio_check();
}
