<?php
class BeRocket_aapf_custom_slug_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'custom_slug_include';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Custom Slug (BETA)', 'BeRocket_AJAX_domain'),
            'tooltip'       => '<a href="https://docs.berocket.com/docs_section/custom-slug">DOCUMENTATION</a><br>'.__('Setup specific slug for each filter.<br>Provide possibility to use multiple filters for same attribute/taxonomy', 'BeRocket_AJAX_domain'),
            'paid'          => true
        ));
    }
}
new BeRocket_aapf_custom_slug_addon();
