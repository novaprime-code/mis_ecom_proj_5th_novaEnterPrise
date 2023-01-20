<?php
if( ! class_exists('BeRocket_AAPF_Template_Style') ) {
    class BeRocket_AAPF_Template_Style {
        public $data = array();
        function __construct() {
            $this->data = array_merge(
                array(
                    'slug'          => 'parent',
                    'template'      => 'checkbox',
                    'name'          => 'Parent',
                    'file'          => __FILE__,
                    'style_file'    => false,
                    'script_file'   => false,
                    'image'         => false,
                    'version'       => '1.0',
                    'specific'      => '',
                    'sort_pos'      => '900'
                ), $this->data
            );
            add_filter('BeRocket_AAPF_getall_Template_Styles', array($this, 'add_style_data'), 10, 2);
        }
        function activate() {
            $this->filters();
            $this->enqueue_all();
        }
        function enqueue_all() {
            if( ! empty($this->data['style_file']) && file_exists(dirname($this->data['file']).'/'.$this->data['style_file']) ) {
                BeRocket_AAPF::wp_enqueue_style( 'BeRocket_AAPF_style-'.sanitize_title($this->data['style_file']), plugins_url( $this->data['style_file'], $this->data['file'] ), array(), $this->data['version'] );
            }
            if( ! empty($this->data['script_file']) && file_exists(dirname($this->data['file']).'/'.$this->data['script_file']) ) {
                BeRocket_AAPF::wp_enqueue_script( 'BeRocket_AAPF_script-'.sanitize_title($this->data['script_file']), plugins_url( $this->data['script_file'], $this->data['file'] ), array('jquery', 'berocket_aapf_widget-script'), $this->data['version'], true );
            }
        }
        function deactivate() {
            $this->filters('remove');
        }
        function filters($action = 'add') {
            $filter_func = 'add_filter';
            $action_func = 'add_action';
            if( $action != 'add' ) {
                $filter_func = 'remove_filter';
                $action_func = 'remove_action';
            }
            $filter_func('BeRocket_AAPF_template_style', array($this, 'template'), 10, 1);
            $filter_func('BeRocket_AAPF_template_single_item', array($this, 'template_single_item'), 10, 4);
            $filter_func('BeRocket_AAPF_template_full_content', array($this, 'template_full'), 10, 3);
            $action_func('berocket_aapf_filter_end_generation', array($this, 'deactivate'));
        }
        function add_style_data($styles, $search = array()) {
            $write = false;
            if( empty($search) ) {
                $write = true;
            } elseif( is_array($search) ) {
                $write = true;
                foreach($search as $field => $value) {
                    if( empty($value) || empty($this->data[$field]) || $value != $this->data[$field] ) {
                        $write = false;
                        break;
                    }
                }
            }
            if( $write ) {
                $data = $this->data;
                $data['this'] = $this;
                $styles[$this->data['slug']] = $data;
            }
            return $styles;
        }
        function template_single_item($template, $term, $i, $berocket_query_var_title) {
            return $template;
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            return $template;
        }
        function template($template_names) {
            if( ! is_array($template_names) ) {
                $template_names = array();
            }
            $template_names[$this->data['slug']] = $this->data['template'];
            return $template_names;
        }
        function array_set(&$element, $fields) {
            if( empty($element) || ! is_array($element) ) {
                $element = array();
            }
            $element_check = &$element;
            $last = array_pop($fields);
            foreach($fields as $field) {
                if( empty($element_check[$field]) || ! is_array($element_check[$field]) ) {
                    $element_check[$field] = array();
                }
                $temp = &$element_check[$field];
                unset($element_check);
                $element_check = &$temp;
                unset($temp);
            }
            if( isset($element_check[$last]) ) {
                if( ! is_array($element_check[$last]) ) {
                    $single = $element_check[$last];
                    $element_check[$last] = array($single);
                }
            } else {
                $element_check[$last] = array();
            }
            return $element;
        }
    }
}
