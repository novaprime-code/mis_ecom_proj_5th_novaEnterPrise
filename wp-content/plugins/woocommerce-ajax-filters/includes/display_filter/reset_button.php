<?php
class BeRocket_AAPF_display_filters_reset_button_type extends BeRocket_AAPF_display_filters_additional_type {
    public static $type_slug = 'reset_button';
    public static $type_name;
    public static $needed_options = array(
        'title' => 'Reset Filters',
        'is_hide_mobile' => false
    );
    function init() {
        static::$type_name = __('Reset Products button', 'BeRocket_AJAX_domain');
        parent::init();
    }
    public static function return_html($html, $additional) {
        $set_query_var_title = $additional['set_query_var_title'];
        ob_start();
        if( ! empty($set_query_var_title['new_template']) ) {
            add_filter('BeRocket_AAPF_template_full_element_content', array(__CLASS__, 'add_button_class'), 1, 2);
            $set_query_var_title = apply_filters('berocket_query_var_title_before_element', $set_query_var_title, $additional);
            set_query_var( 'berocket_query_var_title', $set_query_var_title);
            br_get_template_part('elements/'.$set_query_var_title['new_template']);
            remove_filter('BeRocket_AAPF_template_full_element_content', array(__CLASS__, 'add_button_class'), 1, 2);
        }
        return ob_get_clean();
    }
    public static function add_button_class($template_content, $berocket_query_var_title) {
        $template_content['template']['content']['filter']['content']['button']['attributes']['class']['main'] = 'bapf_reset';
        if( ! empty($berocket_query_var_title['reset_hide']) ) {
            $template_content['template']['attributes']['class']['reset_hide'] = $berocket_query_var_title['reset_hide'];
            if( ! isset($template_content['template']['attributes']['style']) ) {
                $template_content['template']['attributes']['style'] = array();
            }
            if( ! is_array($template_content['template']['attributes']['style']) ) {
                $template_content['template']['attributes']['style'] = array($template_content['template']['attributes']['style']);
            }
            $template_content['template']['attributes']['style']['display_none'] = 'display:none;';
        }
        return $template_content;
    }
}
new BeRocket_AAPF_display_filters_reset_button_type();
