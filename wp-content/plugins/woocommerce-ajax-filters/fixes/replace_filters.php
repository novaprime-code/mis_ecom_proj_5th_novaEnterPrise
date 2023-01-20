<?php
class braapf_replace_filters_version_non_3 {
    public $filters;
    public $global_plugin;
    function __construct() {
        $this->filters = BeRocket_AAPF_single_filter::getInstance();
        $this->global_plugin = BeRocket_AAPF::getInstance();
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_widget_type'), 10, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_attribute'), 20, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_style_type'), 30, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_collapse'), 40, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_price'), 50, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_color'), 60, 2);
        add_filter('braapf_replace_filters_version_non_3', array($this, 'replace_other'), 70, 2);
        $this->replace_filters_one_by_one();
    }
    function replace_filters_one_by_one() {
        $filters_list = $this->filters->get_custom_posts(array('lang' => '', 'suppress_filters' => true));
        foreach($filters_list as $filter_id) {
            $this->replace_single_filter($filter_id);
        }
    }
    function replace_single_filter($filter_id) {
        $options = $this->filters->get_option($filter_id);
        if( empty($options['version']) ) {
            $options = apply_filters( 'braapf_replace_filters_version_non_3', $options, $filter_id );
            $filter_post = get_post($filter_id);
            $_POST[$this->filters->post_name] = $options;
            $this->filters->wc_save_product_without_check($filter_id, $filter_post);
        }
    }
    function replace_widget_type($options, $filter_id) {
        if($options['widget_type'] == 'selected_area') {
            $options['widget_collapse'] = '';
            $options['description'] = '';
            $options['height'] = '';
        } elseif($options['widget_type'] == 'search_box') {
            $options['widget_type'] = '';
        }
        return $options;
    }
    function replace_attribute($options, $filter_id) {
        if( $options['filter_type'] == 'product_cat' ) {
            $options['filter_type'] = 'all_product_cat';
            if( empty($options['parent_product_cat_current']) ) {
                if( empty($options['parent_product_cat']) ) {
                    $options['parent_product_cat'] = 'bapf1level';
                }
            } else {
                $options['parent_product_cat'] = 'bapf4current';
            }
        } elseif( $options['filter_type'] == 'date' ) {
            $options['style'] = 'datepicker';
        } elseif( $options['filter_type'] == 'attribute' && $options['attribute'] == 'price' ) {
            $options['filter_type'] = 'price';
        }
        return $options;
    }
    function replace_style_type($options, $filter_id) {
        $global_options = $this->global_plugin->get_option();
        if($options['widget_type'] == 'update_button' || $options['widget_type'] == 'reset_button') {
            $options['style'] = 'button_default';
        } elseif($options['widget_type'] == 'selected_area') {
            $options['style'] = 'sfa_default';
        } elseif($options['filter_type'] == 'date') {
            $options['style'] = 'datepicker';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'checkbox') {
            $options['style'] = 'checkbox';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'radio') {
            $options['style'] = 'checkbox';
            $options['single_selection'] = '1';
        } elseif($options['type'] == 'select') {
            $options['style'] = (empty($global_options['use_select2']) ? 'select' : 'select2');
            $options['single_selection'] = (empty($options['select_multiple']) ? '1' : '');
        } elseif($options['type'] == 'color') {
            $options['style'] = 'color';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'image') {
            $options['style'] = 'image';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'slider') {
            $options['style'] = 'slider';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'ranges') {
            $options['style'] = 'checkbox';
            $options['single_selection'] = '';
        } elseif($options['type'] == 'tag_cloud') {
            $options['style'] = 'checkbox';
            $options['single_selection'] = '';
        } else {
            $options['style'] = 'checkbox';
            $options['single_selection'] = '';
        }
        return $options;
    }
    function replace_collapse($options, $filter_id) {
        if( empty($options['widget_collapse_enable']) ) {
            $options['widget_collapse'] = '';
        } else {
            if( empty($options['hide_collapse_arrow']) ) {
                $options['widget_collapse'] = 'without_arrow_mobile';
            } else {
                $options['widget_collapse'] = 'without_arrow';
            }
        }
        return $options;
    }
    function replace_price($options, $filter_id) {
        if( $options['filter_type'] == 'price' ) {
            if( empty($options['use_min_price']) ) {
                $options['min_price'] = '';
            }
            if( empty($options['use_max_price']) ) {
                $options['max_price'] = '';
            }
        } else {
            $options['number_style'] = '';
            $options['enable_slider_inputs'] = '';
            $options['text_before_price'] = '';
            $options['text_after_price'] = '';
        }
        return $options;
    }
    function replace_color($options, $filter_id) {
        if( $options['style'] == 'color' || $options['style'] == 'image' ) {
            $options['single_selection'] = (empty($options['disable_multiple']) ? '' : '1');
            $options['use_value_with_color'] = (empty($options['use_value_with_color']) ? '' : 'right');
        }
        return $options;
    }
    function replace_other($options, $filter_id) {
        global $wpdb;
        $options['filter_title'] = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE ID = %d", $filter_id));
        if( $options['values_per_row'] == 1) {
            $options['values_per_row'] = '';
        }
        $options['version'] = '1.0';
        return $options;
    }
}
new braapf_replace_filters_version_non_3();