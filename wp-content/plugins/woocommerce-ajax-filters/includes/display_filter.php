<?php
class BeRocket_AAPF_display_filters_additional_type {
    public static $type_slug;
    public static $type_name;
    public static $custom_type_list = array();
    public static $needed_options = array();
    function __construct() {
        if( ! empty(static::$type_slug) && empty(self::$custom_type_list[static::$type_slug]) ) {
            self::$custom_type_list[static::$type_slug] = $this;
            $this->init();
        }
    }
    function init() {
        add_filter('berocket_aapf_display_filter_custom_type', array($this, 'custom_type'), 10, 3);
        add_filter('berocket_aapf_display_filter_type_list', array($this, 'type_list'));
    }
    function type_list($list) {
        $list[static::$type_slug] = static::$type_name;
        return $list;
    }
    function custom_type($html, $type, $additional = array()) {
        if( $type == static::$type_slug ) {
            $additional['options'] = $this->check_input_options($additional['options']);
            $html = $this->return_html($html, $additional);
        }
        return $html;
    }
    public function check_input_options(&$options = array()) {
        if( ! is_array($options) ) {
            $options = array();
        }
        $options = array_merge(static::$needed_options, $options);
        return $options;
    }
    public static function return_html($html, $additional) {
        return $html;
    }
    public static function get_option() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        return $BeRocket_AAPF->get_option();
    }
}
