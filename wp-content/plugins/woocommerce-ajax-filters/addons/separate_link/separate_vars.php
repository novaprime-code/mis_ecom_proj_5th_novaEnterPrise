<?php
class BeRocket_AAPF_lp_separate_vars extends BeRocket_AAPF_link_parser {
    function __construct() {
        parent::__construct();
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $option = $BeRocket_AAPF->get_option();
        add_filter('brfr_data_ajax_filters', array($this, 'brfr_data'), 50, 1);
        if( ! empty( $option['use_links_filters'] ) ) {
            add_action( 'current_screen', array( $this, 'register_permalink_option' ), 50 );
        }
        add_filter('aapf_localize_widget_script', array($this, 'localize_widget_script'), 900);
        add_filter('bapf_uparse_generate_filter_link_each_taxval_delimiters', array($this, 'each_taxval_delimiters'), 1000, 4);
        add_filter('bapf_uparse_generate_filter_link_each_values_line', array($this, 'generate_filter_link_each_values_line'), 1000, 6);
        add_filter('bapf_uparse_generate_filter_val_arr', array($this, 'generate_filter_val_arr'), 1000, 6);
        add_filter('bapf_uparse_generate_filter_link_delimiter', array($this, 'generate_filter_link_delimiter'), 1000);
        add_filter('bapf_uparse_add_filters_to_link', array($this, 'add_filters_to_link'), 1000, 4);
        add_filter('bapf_uparse_remove_filters_from_link', array($this, 'remove_filters_from_link'), 1000, 3);
        add_filter('bapf_url_parse_page_price_range_implode_values', array($this, 'price_range_implode_values'), 1000, 5);
    }
    function get_filter_line($result, $instance, $link = false) {
        $query_vars = array();
        if( $link !== false ) {
            $query_vars = array();
            $parsed_link = wp_parse_url($link);
            if( ! empty($parsed_link['query']) ) {
                parse_str($parts['query'], $query_vars);
            }
        } else {
            $query_vars = $_GET;
        }
        $var_names = array();
        $skip = array();
        $values = array();
        foreach($query_vars as $get_key => $get_value) {
            if( substr($get_key, 0, 3) == 'pa-' ) {
                $get_key = substr($get_key, 3);
            } else {
                continue;
            }
            $permalink_data = apply_filters('bapf_niceurl_get_permalinks_options', array(
                'variable' => 'filters',
                'value'    => '[values]',
                'split'    => '|'
            ));
            $permalink_values = explode('values', $permalink_data['value']);
            if( in_array($get_key, $var_names) ) continue;
            $attr = $attr_val = $attr_line = false;
            if( ($taxonomy = $this->check_taxonomy($get_key)) !== false ) {
                $var_names[] = $get_key;
                $operator = (empty($options['default_operator_and']) ? 'OR' : 'AND');
                $operator_var = 'pa-'.$get_key . '_operator';
                if( ! empty($query_vars[$operator_var]) ) {
                    $operator = $query_vars[$operator_var];
                    $var_names[] = $operator_var;
                }
                $delimiter = $instance->func_operator_to_delimiter($operator);
                $attr_val = explode(',', $get_value);
                $values[] = $get_key . $permalink_values[0] . implode($delimiter, $attr_val) . $permalink_values[1];
            } elseif( (strlen($get_key) > 5 && substr($get_key, -5) == '_from' && ! empty($query_vars['pa-'.substr_replace($get_key, '_to'  , -5)]))
                   || (strlen($get_key) > 3 && substr($get_key, -3) == '_to'   && ! empty($query_vars['pa-'.substr_replace($get_key, '_from', -3)]))
            ) {
                $taxonomy_key = substr_replace($get_key, '', (substr($get_key, -3) == '_to' ? -3 : -5));
                if( $taxonomy = $this->check_taxonomy($taxonomy_key) ) {
                    $var_names[] = $taxonomy_key.'_from';
                    $var_names[] = $taxonomy_key.'_to';
                    $values[] = $taxonomy_key . $permalink_values[0] . $query_vars['pa-'.$taxonomy_key.'_from'] . '_' . $query_vars['pa-'.$taxonomy_key.'_to'] . $permalink_values[1];
                }
            }
            $result = implode($permalink_data['split'], $values);
        }
        return $result;
    }
    function register_permalink_option() {
        global $wp_settings_sections;
        if( isset($wp_settings_sections[ 'permalink' ][ 'berocket_permalinks' ]) ) {
            unset($wp_settings_sections[ 'permalink' ][ 'berocket_permalinks' ]);
        }
    }
    function brfr_data($data) {
        if( isset($data['SEO']['nice_urls']) ) {
            unset($data['SEO']['nice_urls']);
        }
        if( isset($data['SEO']['seo_uri_decode']) ) {
            unset($data['SEO']['seo_uri_decode']);
        }
        $data['SEO']['default_operator_and'] = array(
                                "label"     => __( 'Default operator for URLs', "BeRocket_AJAX_domain" ),
                                "name"     => "default_operator_and",   
                                "type"     => "selectbox",
                                "options"  => array(
                                    array('value' => '', 'text' => __('OR', 'BeRocket_AJAX_domain')),
                                    array('value' => '1', 'text' => __('AND', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => '',
            'label_for' => __('Default operator will not be added to the URL', 'BeRocket_AJAX_domain'),
        );
        return $data;
    }
    function each_taxval_delimiters($delimiter, $instance, $filter, $data) {
        $delimiter['before_values'] = '=';
        $delimiter['after_values']  = '';
        return $delimiter;
    }
    function generate_filter_link_each_values_line($values_line, $instance, $filter, $data, $link_elements, $elements) {
        extract($elements);
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if(! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER') {
            $values_line = 'pa-'.$taxonomy_name.'_from='.$filter['val_arr']['from'];
            $values_line .='&pa-'.$taxonomy_name.'_to='.$filter['val_arr']['to'];
        } else {
            $operator = ( empty($filter['val_arr']['op']) ? 'OR' : $filter['val_arr']['op'] );
            $values_line = 'pa-'.$taxonomy_name.'='.$filter_line;
            if( $operator != (empty($options['default_operator_and']) ? 'OR' : 'AND') ) {
                $values_line .= 'pa-'.$taxonomy_name.'_operator='.$operator;
            }
        }
        return $values_line;
    }
    function generate_filter_val_arr($result, $instance, $val_arr, $filter) {
        if( isset($val_arr['op']) ) {
            unset($val_arr['op']);
        }
        $delimiter = ',';
        $values_lines = array();
        $values = array();
        foreach($val_arr as $value) {
            if( is_array($value) ) {
                $values_lines_add = $this->generate_filter_val_arr($value, $filter);
                $values_lines = array_merge($values_lines, $values_lines_add);
            } else {
                $values[] = $value;
            }
        }
        $values_lines[] = implode($delimiter, $values);
        return $values_lines;
    }
    function generate_filter_link_delimiter($delimiter) {
        return '&';
    }
    function add_filters_to_link($result, $instance, $link, $filters_line) {
        parse_str($filters_line, $query_vars);
        $link = add_query_arg($query_vars, $link);
        return $link;
    }
    function remove_filters_from_link($result, $instance, $link) {
        $parsed_link = wp_parse_url($link);
        if( ! empty($parsed_link['query']) ) {
            parse_str($parsed_link['query'], $query_vars);
            foreach($query_vars as $query_var => $query_val) {
                if( substr($query_var, 0, 3) == 'pa-' ) {
                    $link = remove_query_arg( $query_var, $link );
                }
            }
        }
        return $result;
    }
    function price_range_implode_values($values_line, $instance, $values, $filter, $data) {
        return implode(',', $values);
    }
    function add_filter_to_link($current_url = FALSE, $args = array()) {
        $args = array_merge(array(
            'attribute'         => '',
            'values'            => array(),
            'operator'          => 'OR',
            'remove_attribute'  => FALSE,
            'slider'            => FALSE
        ), $args);
        extract($args);
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! is_array($values) ) {
            $values = array($values);
        }
        if( taxonomy_is_product_attribute($attribute) && substr($attribute, 0, 3) == 'pa_' ) {
            $attribute = substr($attribute, 3);
        }
        
        $current_url = $this->get_query_vars_name_link($current_url);
        
        $link_data = $this->get_query_vars_name($current_url);
        $new_url = $current_url;
        if( $slider && count($values) == 2 ) {
            $values = array_values($values);
            $get_key1 = 'pa-'.$attribute.'_from';
            $get_key2 = 'pa-'.$attribute.'_to';
            $taxonomy_value1 = $values[0];
            $taxonomy_value2 = $values[1];
            $new_url = add_query_arg(array($get_key1 => $taxonomy_value1, $get_key2 => $taxonomy_value2), $new_url);
        } else {
            $taxonomy_value = implode(',', $values);
            $get_key = 'pa-'.$attribute;
            foreach($link_data['taxonomy'] as $taxonomy) {
                if( $taxonomy['get_key'] == $attribute ) {
                    $terms = $taxonomy['data']['terms'] ;
                    $terms = explode(',', $terms);
                    foreach($values as $value) {
                        if( ($position = array_search($value, $terms)) === FALSE ) {
                            $terms[] = $value;
                        } else {
                            unset($terms[$position]);
                        }
                    }
                    $taxonomy_value = implode(',', $terms);
                    $get_key = 'pa-'.$taxonomy['get_key'];
                }
            }
            if( empty($taxonomy_value) ) {
                $new_url = add_query_arg(array($get_key => null, $get_key.'_operator' => null), $new_url);
            } else {
                $operator_set = $operator;
                if( $operator == (empty($options['default_operator_and']) ? 'OR' : 'AND') ) {
                    $operator_set = null;
                }
                $new_url = add_query_arg(array($get_key => $taxonomy_value, $get_key.'_operator' => $operator_set), $new_url);
            }
        }
        return $new_url;
    }
    function js_generate_inside() {
        ?>
//Link Like Woocommerce
var braapf_get_current_filters_separate_link,
braapf_glue_by_operator_separate_link,
braapf_set_filters_to_link_separate_link,
braapf_compat_filters_to_string_single_separate_link,
braapf_compat_filters_result_separate_link;
(function ($){
    braapf_get_current_filters_separate_link = function(url_data) {
        var new_queryargs = [];
        var filters = '';
        $.each(url_data.queryargs, function(i, val) {
            if(val.name.substring(0, 3) == 'pa-') {
                if( filters === '' ) {
                    filters = '';
                } else {
                    filters = filters+'&';
                }
                filters = filters+val.name+'='+val.value;
            } else {
                new_queryargs.push(val);
            }
        });
        url_data.filter = filters;
        url_data.queryargs = new_queryargs;
        return url_data;
    }
    braapf_glue_by_operator_separate_link = function(glue) {
        return ',';
    }
    braapf_compat_filters_result_separate_link = function(filter, val) {
        var operator_string = '';
        if( typeof(val.operator) != 'undefined' && val.operator != the_ajax_script.default_operator ) {
            
            if( val.operator == 'slidr' ) {
                var two_values = filter.values.split('_');
                if( typeof(two_values[0]) != 'undefined' && typeof(two_values[1]) != 'undefined' ) {
                    filter.val_from = 'pa-'+filter.taxonomy+'_from='+two_values[0];
                    filter.val_to = 'pa-'+filter.taxonomy+'_to='+two_values[1];
                }
            } else {
                operator_string = 'pa-'+filter.taxonomy+'_operator='+val.operator;
            }
        }
        filter.operator = operator_string;
        return filter;
    }
    braapf_compat_filters_to_string_single_separate_link = function(single_string, val, compat_filters, filter_mask, glue_between_taxonomy) {
        if( typeof( val.val_from ) != 'undefined' ) {
            single_string = val.val_from+'&'+ val.val_to;
        } else if( val.operator.length ) {
            single_string = single_string+'&'+val.operator;
        }
        return single_string;
    }
    braapf_set_filters_to_link_separate_link = function(url, url_data, parameters, url_without_query, query_get) {
        if(url_data.filter.length) {
            if( query_get.length ) {
                query_get = query_get+'&'+url_data.filter;
            } else {
                query_get = '?'+url_data.filter;
            }
            url = url_without_query+query_get;
        }
        return url;
    }
})(jQuery);
function braapf_separate_link_init() {
    //Remove filters
    berocket_add_filter('get_current_url_data', braapf_get_current_filters_separate_link);
    //Add filters
    berocket_add_filter('glue_by_operator', braapf_glue_by_operator_separate_link, 1);
    berocket_add_filter('compat_filters_result_single', braapf_compat_filters_result_separate_link, 20);
    berocket_add_filter('compat_filters_to_string_single', braapf_compat_filters_to_string_single_separate_link);
    berocket_add_filter('url_from_urldata_linkget', braapf_set_filters_to_link_separate_link);
}
if( typeof(berocket_add_filter) == 'function' ) {
    braapf_separate_link_init();
} else {
    jQuery(document).on('berocket_hooks_ready', function() {
        braapf_separate_link_init();
    });
}
        <?php
    }
    function localize_widget_script($localization) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $localization['url_mask'] = 'pa-%t%=%v%';
        $localization['url_split'] = '&';
        $localization['nice_url'] = '';
        $localization['seo_uri_decode'] = '';
        $localization['default_operator'] = (empty($options['default_operator_and']) ? 'OR' : 'AND');
        return $localization;
    }
}
new BeRocket_AAPF_lp_separate_vars();
