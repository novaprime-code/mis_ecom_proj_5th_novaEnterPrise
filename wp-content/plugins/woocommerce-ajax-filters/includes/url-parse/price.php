<?php
if( ! class_exists('BeRocket_url_parse_page_price') ) {
    class BeRocket_url_parse_page_price {
        public $main_class;
        function __construct() {
            add_action('bapf_class_ready', array($this, 'init'), 10, 1);
            add_filter('bapf_uparse_func_check_attribute_name', array($this, 'name_price'), 100, 3);
            add_filter('bapf_uparse_func_check_attribute_values', array($this, 'values_price'), 100, 6);
            add_filter('bapf_uparse_generate_custom_query_each', array($this, 'custom_query'), 100, 6);
            add_filter('bapf_uparse_generate_filter_link_each', array($this, 'generate_filter_link'), 100, 4);
            add_filter('BeRocket_AAPF_template_full_content', array($this, 'slider_selected'), 10, 4);
        }
        public function init($BeRocket_AAPF) {
            $this->main_class = $BeRocket_AAPF;
            $options = $this->main_class->get_option();
            if( ! empty($options['filter_price_variation']) ) {
                add_filter('berocket_aapf_wcvariation_filtering_total_query-add_table', array($this, 'variation_product'), 1000, 4);
                add_filter('berocket_aapf_wcvariation_filtering_single_attribute-add_table', array($this, 'variation_product_attribute'), 1000, 4);
                add_filter('berocket_variation_cache_key', array($this, 'variation_cache'), 1000, 1);
            }
        }
        public function name_price($result, $instance, $attribute_name) {
            $price_taxonomy = apply_filters('bapf_uparse_price_taxonomy', 'price');
            if( $result === null && $attribute_name == $price_taxonomy ) {
                $result = array(
                    'taxonomy' => 'bapf_price',
                    'type'     => 'price'
                );
            }
            return $result;
        }
        public function values_price($result, $instance, $values_line, $taxonomy, $filter, $args) {
            if( $result === null && isset($filter['type']) && $filter['type'] == 'price' ) {
                $error = array(
                    'error' => new WP_Error( 'bapf_uparse', __('Incorrect data for price: ', 'BeRocket_AJAX_domain').$values_line )
                );
                $values = explode('_', $values_line);
                if( count($values) == 2 ) {
                    $values[0] = floatval($values[0]);
                    $values[1] = floatval($values[1]);
                    if( $values[0] >= 0 && $values[1] >= 0 && $values[0] <= $values[1] ) {
                        $result = array(
                            'values'    => array('from' => $values[0], 'to' => $values[1]),
                            'operator'  => $instance->func_delimiter_to_operator('_')
                        );
                    } else { return $error; }
                } else { return $error; }
            }
            return $result;
        }
        public function custom_query($result, $instance, $filter, $data) {
            if( $result === null && isset($filter['type']) && $filter['type'] == 'price' && isset($filter['val_arr']['from']) && isset($filter['val_arr']['to']) ) {
                $result = $filter;
                $result['custom_query'] = array($this, 'post_clauses');
                $result['custom_query_line'] = 'priceslider:'.$filter['val_arr']['from'].'-'.$filter['val_arr']['to'];
            }
            return $result;
        }
        public function generate_filter_link($result, $instance, $filter, $data) {
            if( $filter['type'] == 'price' && isset($filter['val_arr']['from']) && isset($filter['val_arr']['to']) ) {
                $price_taxonomy = apply_filters('bapf_uparse_price_taxonomy', 'price');
                $link_elements = apply_filters('bapf_uparse_generate_filter_link_each_taxval_delimiters', array(
                    'before_values'  => '[',
                    'after_values'   => ']',
                ), $this, $filter, $data);
                $values_line = $this->generate_filter_link_val_arr($filter['val_arr'], $filter, $instance);
                if( ! empty($values_line) ) {
                    $values_line = $price_taxonomy . $link_elements['before_values'] . $values_line . $link_elements['after_values'];
                    return array(apply_filters('bapf_uparse_generate_filter_link_each_values_line', $values_line, $this, $filter, $data, $link_elements, array(
                        'taxonomy_name' => $price_taxonomy,
                        'filter_line'   => $values_line
                    )));
                }
            }
            return $result;
        }
        public function generate_filter_link_val_arr($val_arr, $filter, $instance) {
            $filter_line = '';
            if( isset($val_arr['from']) && isset($val_arr['to']) ) {
                $delimiter = '_';
                if( isset($val_arr['op']) ) {
                    $delimiter = $instance->func_operator_to_delimiter($val_arr['op']);
                    unset($val_arr['op']);
                }
                $filter_line = $val_arr['from'] . $delimiter . $val_arr['to'];
            }
            return $filter_line;
        }
        public function post_clauses($args, $filter) {
            return $this->add_price_to_post_clauses($args, $filter);
        }
        public function add_price_to_post_clauses($args, $filter = false) {
            global $berocket_parse_page_obj;
            if( ! empty($filter['val_arr']) && isset($filter['val_arr']['from']) && isset($filter['val_arr']['to']) ) {
                $options = $this->main_class->get_option();
                if( empty($options['filter_price_variation']) ) {
                    $args = $this->wc_price_to_post_clauses($args, $filter);
                } else {
                    $args = $this->advanced_price_to_post_clauses($args, $filter);
                }
            }
            return $args;
        }
        public function wc_price_to_post_clauses($args, $filter) {
            global $wpdb;
            if ( ! strstr( $args['join'], 'wc_product_meta_lookup' ) ) {
                $args['join'] .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} as wc_product_meta_lookup ON {$wpdb->posts}.ID = wc_product_meta_lookup.product_id ";
            }
            $min = apply_filters('bapf_uparse_price_for_filtering_convert', (isset( $filter['val_arr']['from'] ) ? floatval( $filter['val_arr']['from'] ) : 0));
            $max = apply_filters('bapf_uparse_price_for_filtering_convert', (isset( $filter['val_arr']['to'] ) ? floatval( $filter['val_arr']['to'] ) : 9999999999));
            list($min, $max) = apply_filters('berocket_min_max_filter', array($min, $max));
            $args['where'] .= $wpdb->prepare(
                ' AND NOT (%f<wc_product_meta_lookup.min_price OR %f>wc_product_meta_lookup.max_price ) ',
                $max,
                $min
            );
            return $args;
        }
        public function advanced_price_to_post_clauses($args, $filter) {
            $where = $this->get_advanced_where_price_query_part($filter);
            $args['join'] .= $this->get_advanced_price_temp_table($where);
            return $args;
        }
        public function get_advanced_price_temp_table ($where) {
            global $wpdb;
            $query_price = array(
                'select'    => "SELECT IF(bapf_price_post.post_parent = 0, bapf_price_post.ID, bapf_price_post.post_parent) as product_id from {$wpdb->posts} as bapf_price_post",
                'join'      => "JOIN {$wpdb->wc_product_meta_lookup} as bapf_price_lookup ON bapf_price_post.ID = bapf_price_lookup.product_id",
                'where'     => "WHERE (" . $where . ")"
            );
            $query_price = apply_filters('berocket_aapf_get_advanced_price_temp_table', $query_price, $where);
            $query_price = implode(' ', $query_price);
            $table = " JOIN ({$query_price}) as bapf_custom_price ON {$wpdb->posts}.ID = bapf_custom_price.product_id";
            
            return $table;
        }
        public function get_advanced_where_price_query_part($filter) {
            global $wpdb;
            $min = apply_filters('bapf_uparse_price_for_filtering_convert', (isset( $filter['val_arr']['from'] ) ? floatval( $filter['val_arr']['from'] ) : 0));
            $max = apply_filters('bapf_uparse_price_for_filtering_convert', (isset( $filter['val_arr']['to'] ) ? floatval( $filter['val_arr']['to'] ) : 9999999999));
            list($min, $max) = apply_filters('berocket_min_max_filter', array($min, $max));
            $where = $wpdb->prepare(
                'bapf_price_lookup.min_price >= %f AND bapf_price_lookup.max_price <= %f ',
                $min,
                $max
            );
            return $where;
        }
        function slider_selected($template_content, $terms, $berocket_query_var_title) {
            if( in_array($berocket_query_var_title['new_template'], array('slider', 'new_slider')) ) {
                foreach($terms as $term){break;}
                if( count($terms) == 1 ) {
                    global $berocket_parse_page_obj;
                    $filter_data = $berocket_parse_page_obj->get_current();
                    foreach($filter_data['filters'] as $filter) {
                        if( (($term->taxonomy == 'price' && $filter['type'] == 'price') 
                            || ( in_array($filter['type'], array('attribute', 'taxonomy')) && $filter['taxonomy'] == $term->taxonomy ) ) 
                        && ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER') {
                            $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-start'] = floatval($filter['val_arr']['from']);
                            $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-end'] = floatval($filter['val_arr']['to']);
                            break;
                        }
                    }
                }
            }
            return $template_content;
        }
        function variation_product($query, $data, $current_attributes, $current_terms) {
            global $berocket_parse_page_obj;
            $data = $berocket_parse_page_obj->get_current();
            if( ! empty($data['filters']) && count($data['filters']) > 0 ) {
                foreach($data['filters'] as $filter) {
                    if( $filter['type'] == 'price' && ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER') {
                        $where = $this->get_advanced_where_price_query_part($filter);
                        global $wpdb;
                        $query_price = array(
                            'select'    => "SELECT bapf_price_post.ID as product_id from {$wpdb->posts} as bapf_price_post",
                            'join'      => "JOIN {$wpdb->wc_product_meta_lookup} as bapf_price_lookup ON bapf_price_post.ID = bapf_price_lookup.product_id",
                            'where'     => "WHERE (" . $where . ") AND bapf_price_post.post_parent != 0"
                        );
                        $query_price = apply_filters('berocket_aapf_get_advanced_price_temp_table', $query_price, $where);
                        $query_price = implode(' ', $query_price);
                        $table = " LEFT JOIN ({$query_price}) as bapf_custom_price ON filtered_post.post_id = bapf_custom_price.product_id";
                        $query['subquery']['join_close_1'] .= $table;
                        $query['subquery']['select'] .= ',IF(min(bapf_custom_price.product_id) IS NULL, 1, 0) as not_in_price';
                        $query['select'] .= ',min(filtered_post.not_in_price) as not_in_price';
                        $query['having'] .= ' OR not_in_price = 1';
                    }
                }
            }
            
            return $query;
        }
        function variation_product_attribute($query, $partial_data, $current_attributes_part, $current_terms_part) {
            if( strpos($query['join_select'], 'not_in_price') !== FALSE ) {
                $query['select'] .= ',min(out_of_stock_var.not_in_price) as not_in_price';
                $query['having'] .= ' OR not_in_price = 1';
            }
            return $query;
        }
        function variation_cache($key) {
            global $berocket_parse_page_obj;
            $data = $berocket_parse_page_obj->get_current();
            if( ! empty($data['filters']) && count($data['filters']) > 0 ) {
                foreach($data['filters'] as $filter) {
                    if( $filter['type'] == 'price' && ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER') {
                        $where = $this->get_advanced_where_price_query_part($filter);
                        $key .= md5($where);
                    }
                }
            }
            return $key;
        }
    }
    new BeRocket_url_parse_page_price();
}