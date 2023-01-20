<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 */
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
//Delete Additional table
wp_unschedule_hook('braapf_additional_table_cron');
$tables_drop = array(
    'braapf_product_stock_status_parent',
    'braapf_product_variation_attributes',
    'braapf_variation_attributes',
    'braapf_variable_attributes',
    'braapf_term_taxonomy_hierarchical'
);
foreach($tables_drop as $table_drop) {
    $table_name = $wpdb->prefix . $table_drop;
    $sql = "DROP TABLE IF EXISTS {$table_name};";
    $wpdb->query($sql);
}
$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%br_custom_table_hierarhical_%';");
delete_option('BeRocket_aapf_additional_tables_addon_position');
if ( defined( 'BR_AAPF_REMOVE_ALL_DATA' ) && true === BR_AAPF_REMOVE_ALL_DATA ) {
    
}
