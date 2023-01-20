<?php
if( ! class_exists('BeRocket_AAPF_Elemets_Style_button_default') ) {
    class BeRocket_AAPF_Elemets_Style_button_default extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'button_default',
                'template'      => 'button',
                'name'          => 'Default',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/button_default.png',
                'version'       => '1.0',
                'specific'      => 'elements',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function filters($action = 'add') {
            parent::filters($action);
            $filter_func = 'add_filter';
            $action_func = 'add_action';
            if( $action != 'add' ) {
                $filter_func = 'remove_filter';
                $action_func = 'remove_action';
            }
            $filter_func('BeRocket_AAPF_template_full_element_content', array($this, 'template_element_full'), 10, 2);
        }
        function template_element_full($template, $berocket_query_var_title) {
            return $template;
        }
    }
    new BeRocket_AAPF_Elemets_Style_button_default();
}
if( ! class_exists('BeRocket_AAPF_Elemets_Style_button_berocket') ) {
    class BeRocket_AAPF_Elemets_Style_button_berocket extends BeRocket_AAPF_Elemets_Style_button_default {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'button_berocket';
            $this->data['name'] = 'BeRocket';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/button_berocket.png';
            $this->data['style_file'] = 'css/button.css';
            $this->data['sort_pos'] = '900';
        }
        function template_element_full($template, $berocket_query_var_title) {
            $template['template']['attributes']['class']['inline'] = 'bapf_button_berocket';
            return $template;
        }
    }
    new BeRocket_AAPF_Elemets_Style_button_berocket();
}