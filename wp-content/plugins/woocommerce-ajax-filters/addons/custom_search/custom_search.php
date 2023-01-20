<?php
class BeRocket_aapf_custom_search_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'custom_search_include';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, apply_filters('bapf_custom_search_addon_data', array(
            'addon_name'    => __('Custom Search (BETA)', 'BeRocket_AJAX_domain'),
            'tooltip'       => '<a href="https://docs.berocket.com/docs_section/custom-search">DOCUMENTATION</a><br>'.__('Manage search fields: title, description, excerpt, SKU.<br>Relevanssi search as a filter', 'BeRocket_AJAX_domain'),
            'image'         => plugins_url('/custom_search.png', __FILE__),
            'paid'          => true
        ), $this));
    }
}
new BeRocket_aapf_custom_search_addon();
