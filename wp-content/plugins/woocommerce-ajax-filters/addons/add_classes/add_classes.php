<?php
class BeRocket_aapf_add_classes_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'classes';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Add more classes', 'BeRocket_AJAX_domain'),
            'tooltip'       => __('Add more CSS classes to filters. Can be using for styling or other things', 'BeRocket_AJAX_domain')
        ));
    }
}
new BeRocket_aapf_add_classes_addon();
