<?php
class BeRocket_AAPF_display_filters_selected_area_type extends BeRocket_AAPF_display_filters_additional_type {
    public static $type_slug = 'selected_area';
    public static $type_name;
    public static $needed_options = array(
        'title'         => '',
        'scroll_theme' => 'dark',
        'is_hide_mobile' => false,
        'selected_area_show' => '0',
        'hide_selected_arrow' => '0',
        'selected_is_hide' => '0',
    );
    function init() {
        static::$type_name = __('Selected Filters area', 'BeRocket_AJAX_domain');
        parent::init();
        add_filter('BeRocket_AAPF_widget_additional_classes', array(__CLASS__, 'additional_class'), 10, 3);
    }
    public static function return_html($html, $additional) {
        $set_query_var_title = $additional['set_query_var_title'];
        ob_start();
        if( ! empty($set_query_var_title['new_template']) ) {
            $set_query_var_title = apply_filters('berocket_query_var_title_before_element', $set_query_var_title, $additional);
            set_query_var( 'berocket_query_var_title', $set_query_var_title);
            br_get_template_part('elements/'.$set_query_var_title['new_template']);
        }
        return ob_get_clean();
    }
    public static function additional_class($additional_class, $filter_id, $filter_data) {
        if(! empty($filter_data['widget_type']) && $filter_data['widget_type'] == 'selected_area') {
            $additional_class[] = 'bapf_mt_none';
        }
        return $additional_class;
    }
}
new BeRocket_AAPF_display_filters_selected_area_type();
