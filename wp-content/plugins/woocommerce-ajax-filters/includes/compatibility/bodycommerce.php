<?php
if ( ! function_exists('berocket_aapf_bodycommerce_archive_module_args') ) {
    add_filter('db_archive_module_args', 'berocket_aapf_bodycommerce_archive_module_args');
    function berocket_aapf_bodycommerce_archive_module_args( $new_args ) {
        $unset_values = array(
            'bapf_tax_applied',
            'bapf_meta_applied',
            'bapf_postin_applied',
            'bapf_postnotin_applied'
        );
        foreach( $unset_values as $unset_value ) {
            if( isset($new_args[$unset_value]) ) {
                unset($new_args[$unset_value]);
            }
        }
        $new_args = apply_filters('bapf_uparse_apply_filters_to_query_vars_save', $new_args);
        return $new_args;
    }
}
