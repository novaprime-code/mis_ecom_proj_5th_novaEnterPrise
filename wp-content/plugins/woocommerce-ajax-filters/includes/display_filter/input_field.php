<?php
class BeRocket_AAPF_display_filters_input_type extends BeRocket_AAPF_display_filters_additional_type {
    public static $type_slug = 'search_field';
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
        static::$type_name = __('Search field', 'BeRocket_AJAX_domain');
        parent::init();
    }
    public static function return_html($html, $additional) {
        $set_query_var_title = $additional['set_query_var_title'];
        ob_start();
        if( ! empty($set_query_var_title['new_template']) ) {
            add_filter('BeRocket_AAPF_template_full_element_content', array(__CLASS__, 'add_input_class'), 1, 2);
            $set_query_var_title['searchf_filters_include'] = br_get_value_from_array($additional['options'], 'searchf_filters_include', '');
            $set_query_var_title['searchf_placeholder'] = br_get_value_from_array($additional['options'], 'searchf_placeholder', '');
            $set_query_var_title['searchf_button_text'] = br_get_value_from_array($additional['options'], 'searchf_button_text', '');
            $set_query_var_title['searchf_button_text'] = ( empty($set_query_var_title['searchf_button_text']) ? __('Search', 'BeRocket_AJAX_domain') : $set_query_var_title['searchf_button_text'] );
            $set_query_var_title = apply_filters('berocket_query_var_title_before_element', $set_query_var_title, $additional);
            set_query_var( 'berocket_query_var_title', $set_query_var_title);
            br_get_template_part('elements/'.$set_query_var_title['new_template']);
            remove_filter('BeRocket_AAPF_template_full_element_content', array(__CLASS__, 'add_input_class'), 1, 2);
        }
        return ob_get_clean();
    }
    public static function add_input_class($template_content, $berocket_query_var_title) {
        $template_content['template']['attributes']['data-taxonomy'] = 'srch';
        $template_content['template']['content']['filter']['content']['form']['content']['input']['attributes']['value'] = (empty($_GET['srch']) ? '' : esc_html($_GET['srch']));
        $template_content['template']['content']['filter']['content']['form']['content']['input']['attributes']['placeholder'] = $berocket_query_var_title['searchf_placeholder'];
        $template_content['template']['content']['filter']['content']['form']['content']['button'] = array(
            'type'          => 'tag',
            'tag'           => 'button',
            'attributes'    => array(
                'class'         => array(
                    'bapf_search'
                ),
                'type'      => 'submit'
            ),
            'content'       => array($berocket_query_var_title['searchf_button_text'])
        );
        return $template_content;
    }
}
new BeRocket_AAPF_display_filters_input_type();
