<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_square_check') ) {
    class BeRocket_AAPF_Template_Style_square_check extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'square-check',
                'template'      => 'checkbox',
                'name'          => 'Square Check',
                'file'          => __FILE__,
                'style_file'    => 'css/square-check.css',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/square-check.png',
                'version'       => '1.0',
                'name_price'    => 'Price Ranges Square Check',
                'image_price'   => plugin_dir_url( __FILE__ ) . 'paid/images/square-check-price.png',
            );
            parent::__construct();
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $this->array_set($template, array('template', 'attributes', 'class'));
            $template['template']['attributes']['class'][] = 'bapf_ckbox_sqchck';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_square_check();
}
