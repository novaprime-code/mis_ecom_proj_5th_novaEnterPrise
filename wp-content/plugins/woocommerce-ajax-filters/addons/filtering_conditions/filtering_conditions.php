<?php
class BeRocket_aapf_filtering_conditions_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'add_conditions';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Nested Filters', 'BeRocket_AJAX_domain'),
            'tooltip'       => __('The ability to set conditions for the filters based on other filters status', 'BeRocket_AJAX_domain')
            
        ));
    }
}
new BeRocket_aapf_filtering_conditions_addon();
