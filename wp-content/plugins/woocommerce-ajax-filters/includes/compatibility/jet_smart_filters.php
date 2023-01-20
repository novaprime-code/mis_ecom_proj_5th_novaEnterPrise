<?php
class BeRocket_AAPF_compat_JetSmartFilter {
    function __construct() {
        $filter_nn_name = apply_filters('berocket_aapf_filter_variable_name_nn', 'filters');
        if(defined('DOING_AJAX') && DOING_AJAX && !empty($_POST['action']) && $_POST['action'] == 'jet_smart_filters') {
            if( ! empty($_POST['brfilters']) ) {
                $_GET[$filter_nn_name] = $_POST['brfilters'];
                add_filter('jet-smart-filters/query/final-query', array($this, 'apply_filters'));
            }
        }
    }
    function apply_filters($query) {
        $query = apply_filters('bapf_uparse_apply_filters_to_query_vars_save', $query);
        return $query;
    }
}