<?php
class BeRocket_aapf_add_custom_postmeta extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'postmeta';
    function __construct() {
        parent::__construct();
        $active_addons = apply_filters('berocket_addons_active_'.$this->plugin_name, array());
        $tables_active = false;
        if( in_array($this->addon_file, $active_addons) ) {
            foreach($active_addons as $active_addon) {
                if( strpos($active_addon, 'additional_tables') !== FALSE ) {
                    $tables_active = true;
                    include_once('additional_tables.php');
                    new BeRocket_aapf_variations_tables_postmeta_addon();
                }
            }
        }
        if( $tables_active ) {
            include_once('additional_tables.php');
            new BeRocket_aapf_variations_tables_postmeta_addon();
        } elseif( is_admin() ) {
            $this->deactivate();
        }
    }
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Custom Post Meta Filtering (BETA)', 'BeRocket_AJAX_domain'),
            'tooltip'       => __('Option to filter products by custom post meta fields. Can slow down filtering.', 'BeRocket_AJAX_domain')
        ));
    }
    function deactivate() {
        $current_position = get_option('BeRocket_aapf_additional_tables_addon_position');
        if( ! empty($current_position) && strpos($current_position, 'cpm') !== FALSE ) {
            $current_position = str_replace(' cpm ', '', $current_position);
            update_option('BeRocket_aapf_additional_tables_addon_position', $current_position);
        }
        global $wpdb;
        $tables_drop = array(
            'braapf_custom_post_meta',
            'braapf_product_post_meta'
        );
        foreach($tables_drop as $table_drop) {
            $table_name = $wpdb->prefix . $table_drop;
            $sql = "DROP TABLE IF EXISTS {$table_name};";
            $wpdb->query($sql);
        }
    }
}
new BeRocket_aapf_add_custom_postmeta();
