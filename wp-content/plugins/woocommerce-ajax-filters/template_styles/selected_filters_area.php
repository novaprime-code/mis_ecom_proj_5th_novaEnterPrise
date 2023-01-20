<?php
if( ! class_exists('BeRocket_AAPF_Elemets_Style_sfa_default') ) {
    class BeRocket_AAPF_Elemets_Style_sfa_default extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'sfa_default',
                'template'      => 'selected_filters',
                'name'          => 'Selected Filters Area',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/selected_filters_area.png',
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
    new BeRocket_AAPF_Elemets_Style_sfa_default();
}
if( ! class_exists('BeRocket_AAPF_Elemets_Style_sfa_inline') ) {
    class BeRocket_AAPF_Elemets_Style_sfa_inline extends BeRocket_AAPF_Elemets_Style_sfa_default {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'sfa_inline';
            $this->data['name'] = 'Selected Filters Area Inline';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/selected_filters_area-inline.png';
            $this->data['style_file'] = 'css/selected_filters_area.css';
        }
        function template_element_full($template, $berocket_query_var_title) {
            $template['template']['attributes']['class']['inline'] = 'bapf_sfa_inline';
            return $template;
        }
    }
    new BeRocket_AAPF_Elemets_Style_sfa_inline();
}