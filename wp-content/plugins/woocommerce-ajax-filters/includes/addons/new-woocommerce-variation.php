<?php
class BeRocket_AAPF_compat_woocommerce_variation_new {
    public $limit_post__not_in_where_array = array();
    public $is_apply = true;
    function __construct() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( berocket_isset($options['out_of_stock_variable']) == 2 ) {
            $this->is_apply = false;
            add_filter('bapf_uparse_generate_tax_query_each', array($this, 'is_out_of_stock'), 101, 4);
        }
        if( ! empty($options['out_of_stock_variable_reload']) ) {
            add_action('bapf_faster_recount_before_recount_terms', array($this, 'before_recount'), 10, 2 );
            add_action('bapf_faster_recount_after_recount_terms', array($this, 'after_recount'), 10, 2 );
        } else {
            add_filter('bapf_faster_recount_get_query_for_calculate', array($this, 'modify_query_vars'), 9000, 2);
        }
        add_filter('bapf_uparse_query_vars_apply_filters', array($this, 'apply_filter'), 9000, 3);
    }
    function is_out_of_stock($result, $instance, $filter, $data) {
        if( ! $this->is_apply && $result !== null && isset($filter['type']) && $filter['type'] == 'stock_status' && ! empty($result['tax_query']) ) {
            foreach($result['tax_query'] as $tax_data) {
                if( is_array($tax_data) && isset($tax_data['operator']) && $tax_data['operator'] == 'NOT IN' ) {
                    $this->is_apply = true;
                }
            }
        }
        return $result;
    }
    function check_is_taxonomy_variable($taxonomy) {
        return apply_filters('bapf_wcvariation_check_is_taxonomy_variable', (substr($taxonomy, 0, 3) == 'pa_'), $taxonomy);
    }
    public $attribute_recount_enable = false;
    function before_recount($terms, $taxonomy_data) {
        if( $this->is_apply && ! empty($taxonomy_data) && ! empty($taxonomy_data['taxonomy']) && $this->check_is_taxonomy_variable($taxonomy_data['taxonomy']) ) {
            $this->attribute_recount_enable = true;
            add_filter('berocket_posts_clauses_recount', array($this, 'apply_recount'), 9000, 3);
        }
    }
    function after_recount($terms, $taxonomy_data) {
        if( $this->attribute_recount_enable ) {
            remove_filter('berocket_posts_clauses_recount', array($this, 'apply_recount'), 9000, 3);
        }
    }
    function modify_query_vars($query_vars, $data) {
        $args = $this->apply_filter(array(), $query_vars, $data);
        $query_vars = array_merge($query_vars, $args);
        return $query_vars;
    }
    function apply_filter($args, $query_vars, $data) {
        if( ! $this->is_apply ) return $args;
        global $bapf_test_count;
        if( ! isset($bapf_test_count) ) {
            $bapf_test_count = 1;
        } else {
            $bapf_test_count++;
        }
        $post_not_in = self::out_of_stock_variable(array(), $data, false);
        if( is_array($post_not_in) && count($post_not_in) ) {
            if( ! empty($query_vars['post__not_in']) && is_array($query_vars['post__not_in']) ) {
                $post_not_in = array_merge($post_not_in, $query_vars['post__not_in']);
            }
            $args['post__not_in'] = $post_not_in;
            if( ! empty($query_vars['post__in']) && is_array($query_vars['post__in']) ) {
                $posts_in = array_diff($query_vars['post__in'], $post_not_in);
                $args['post__in'] = $posts_in;
            }
        }
        return $args;
    }
    function apply_recount($query, $terms, $taxonomy_data) {
        if( $terms !== FALSE && $taxonomy_data !== FALSE ) {
            global $berocket_parse_page_obj, $wpdb;
            $terms = $berocket_parse_page_obj->func_get_terms_slug_id($taxonomy_data['taxonomy']);
            $modify_values = array();
            foreach($terms as $term) {
                $modify_values[] = array(
                    'taxonomy' => $taxonomy_data['taxonomy'],
                    'value'    => $term
                );
            }
            $data = $berocket_parse_page_obj->modify_data(array('values' => $modify_values, 'type' => 'add', 'op' => 'OR', 'calculate' => TRUE));
            list($current_terms, $current_attributes) = self::current_selected_data($data);
            $variation_query = self::out_of_stock_sql_array($data, false);
            $partial_data = $data;
            foreach($partial_data['filters'] as $filter) {
                if( $taxonomy_data['taxonomy'] != $filter['taxonomy'] ) {
                    $partial_data = $berocket_parse_page_obj->remove_taxonomy(array('taxonomy' => $filter['taxonomy']), $partial_data);
                }
            }
            list($current_terms_part, $current_attributes_part) = self::current_selected_data($partial_data);
            $variation_query = $variation_query['subquery'];
            $variation_query['group'] = 'GROUP BY var_id';
            $variation_query = self::implode_recursive($variation_query);
            $variation_query = self::replace_query_elements($variation_query, $current_attributes, $current_terms);
            $query_filtered_posts = apply_filters( 'berocket_aapf_wcvariation_filtering_single_attribute', array(
                'select'        => 'SELECT %1$s.post_parent as ID, '.$wpdb->term_taxonomy.'.term_taxonomy_id as term_id, min(out_of_stock_var.out_of_stock) AS out_of_stock',
                'from'          => 'FROM %1$s',
                'join'          => 'INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)',
                'join2'         => "INNER JOIN {$wpdb->terms} AS term_ids ON term_ids.slug = pf1.meta_value",
                'join3_start'   => "LEFT JOIN (",
                'join3_select'  => "{$variation_query}",
                'join3_end'     => ') AS out_of_stock_var ON %1$s.ID = out_of_stock_var.var_id',
                'join4'         => "INNER JOIN {$wpdb->term_taxonomy} on term_ids.term_id = {$wpdb->term_taxonomy}.term_id",
                'where'         => 'WHERE %1$s.post_type = "product_variation"',
                'and1'          => 'AND %1$s.post_status != "trash"',
                'and2'          => 'AND pf1.meta_key IN ("%4$s")',
                'and3'          => 'AND pf1.meta_value IN ("%5$s")',
                'group'         => 'GROUP BY ID, term_id',
                'having'        => 'HAVING out_of_stock = 1'
            ), $partial_data, $current_attributes_part, $current_terms_part);
            $query_filtered_posts = self::implode_recursive($query_filtered_posts);
            $query_filtered_posts = self::replace_query_elements($query_filtered_posts, $current_attributes_part, $current_terms_part);
            $query['join']  .= " LEFT JOIN ({$query_filtered_posts}) as out_of_stock_variable on {$wpdb->posts}.ID = out_of_stock_variable.ID AND term_relationships.term_taxonomy_id = out_of_stock_variable.term_id";
            $query['where'] .= ' AND out_of_stock_variable.term_id IS NULL';
        }
        return $query;
    }
    public function filter_hooks($add = true) {
        $action = ($add ? 'add_filter' : 'remove_filter');
    }
    public function bapf_uparse($data, $instance) {
        
        return $data;
    }
    public static function current_selected_data($data, $query = false) {
        if( $query === false ) {
            $get_queried_object = get_queried_object();
        } else {
            $get_queried_object = $query->get_queried_object();
        }
        $current_terms = array();
        $current_attributes = array();
        if( is_a($get_queried_object, 'WP_Term') && strpos($get_queried_object->taxonomy, 'pa_') !== FALSE ) {
            $current_attributes[] = sanitize_title('attribute_' . $get_queried_object->taxonomy);
            $current_terms[] = sanitize_title($get_queried_object->slug);
        }
        foreach($data['filters'] as $filter) {
            if( substr( $filter['taxonomy'], 0, 3 ) == 'pa_' && ! empty($filter['terms']) ) {
                $current_attributes[] = sanitize_title('attribute_' . $filter['taxonomy']);
                foreach($filter['terms'] as $term) {
                    $current_terms[] = sanitize_title($term->slug);
                }
            }
        }
        $current_terms = array_unique($current_terms);
        $current_attributes = array_unique($current_attributes);
        sort($current_terms);
        sort($current_attributes);
        $current_terms = implode('","', $current_terms);
        $current_attributes = implode('","', $current_attributes);
        return array($current_terms, $current_attributes);
    }
    public static function out_of_stock_sql_array($data, $query = false, $current_attributes = false, $current_terms = false) {
        if( $current_attributes === false && $current_terms === false ) {
            list($current_terms, $current_attributes) = self::current_selected_data($data, $query);
        }
        $outofstock = wc_get_product_visibility_term_ids();
        if( empty($outofstock['outofstock']) ) {
            $outofstock = get_term_by( 'slug', 'outofstock', 'product_visibility' );
            $outofstock = $outofstock->term_taxonomy_id;
        } else {
            $outofstock = $outofstock['outofstock'];
        }
        $query_filtered_posts = apply_filters( 'berocket_aapf_wcvariation_filtering_main_query', array(
            'select'    => 'SELECT %1$s.id as var_id, %1$s.post_parent as ID, COUNT(%1$s.id) as meta_count',
            'from'      => 'FROM %1$s',
            'join'      => 'INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)',
            'where'     => 'WHERE %1$s.post_type = "product_variation"',
            'and1'      => 'AND %1$s.post_status != "trash"',
            'and2'      => 'AND pf1.meta_key IN ("%4$s")',
            'and3'      => 'AND pf1.meta_value IN ("%5$s")',
            'group'     => 'GROUP BY %1$s.id'
        ), $data, $current_attributes, $current_terms);
        $query = array(
            'select'        => 'SELECT filtered_post.id, MIN(filtered_post.out_of_stock) as out_of_stock, COUNT(filtered_post.ID) as post_count',
            'from_open'     => 'FROM (',
            'subquery'      => array(
                'select'        => 'SELECT filtered_post.*, max_filtered_post.max_meta_count, stock_table.out_of_stock_init as out_of_stock',
                'from_open'     => 'FROM (',
                'subquery_1'    => $query_filtered_posts,
                'from_close'    => ') as filtered_post',
                'join_open_1'   => 'INNER JOIN (',
                'subquery_2'    => array(
                    'select'        => 'SELECT ID, MAX(meta_count) as max_meta_count',
                    'from_open'     => 'FROM (',
                    'subquery'      => $query_filtered_posts,
                    'from_close'    => ') as max_filtered_post',
                    'group'         => 'GROUP BY ID'
                ),
                'join_close_1'  => ') as max_filtered_post ON max_filtered_post.ID = filtered_post.ID AND max_filtered_post.max_meta_count = filtered_post.meta_count',
                'join_open_2'   => 'LEFT JOIN (',
                'subquery_3'    => array(
                    'select'        => 'SELECT %1$s .id as id, IF(%1$s.post_status = "private", 1, COALESCE(stock_table_init.out_of_stock_init1, "0")) as out_of_stock_init',
                    'from'          => 'FROM %1$s',
                    'join_open'     => 'LEFT JOIN (',
                    'subquery'      => array(
                        'select'    => 'SELECT %1$s.id as id, "1" as out_of_stock_init1',
                        'from'      => 'FROM %1$s',
                        'where'     => apply_filters('brAAPFcompat_WCvariation_out_of_stock_where', 'WHERE %1$s.id IN 
                            (
                                SELECT object_id FROM %3$s 
                                WHERE term_taxonomy_id IN ( '.$outofstock.' ) 
                            ) '
                        )
                    ),
                    'join_close'    => ') as stock_table_init on %1$s.id = stock_table_init.id',
                    'group'         => 'GROUP BY id',
                ),
                'join_close_2'  => ') as stock_table ON filtered_post.var_id = stock_table.id',
                'group'         => 'GROUP BY filtered_post.ID, out_of_stock',
            ),
            'from_close'    => ') as filtered_post',
            'group'         => 'GROUP BY filtered_post.ID',
            'having'        => 'HAVING out_of_stock = 1',
        );
        $query = apply_filters('berocket_aapf_wcvariation_filtering_total_query', $query, $data, $current_attributes, $current_terms);
        return $query;
    }
    public static function out_of_stock_variable($input, $data, $query = false) {
        global $wpdb;
        list($current_terms, $current_attributes) = self::current_selected_data($data, $query);
        if( empty($current_terms) && empty($current_attributes) ) return array();
        $out_of_stock_variable = br_get_cache(apply_filters('berocket_variation_cache_key', md5($current_terms.$current_attributes)), 'berocket_variation');
        if( $out_of_stock_variable === false ) {
            $query = self::out_of_stock_sql_array($data, $query, $current_attributes, $current_terms);
            $query = self::implode_recursive($query);
            $query = str_replace(
                array( '%1$s',          '%2$s',             '%3$s',                     '%4$s',                 '%5$s' ),
                array( $wpdb->posts,    $wpdb->postmeta,    $wpdb->term_relationships,  $current_attributes,    $current_terms ),
                $query
            );
            $out_of_stock_variable = $wpdb->get_results( $query, ARRAY_N );
            br_set_cache(apply_filters('berocket_variation_cache_key', md5($current_terms.$current_attributes)), $out_of_stock_variable, 'berocket_variation', HOUR_IN_SECONDS);
        }
        if( BeRocket_AAPF::$debug_mode ) {
            if( ! isset(BeRocket_AAPF::$error_log['_addons_variations_query']) || ! is_array(BeRocket_AAPF::$error_log['_addons_variations_query']) ) {
                BeRocket_AAPF::$error_log['_addons_variations_query'] = array();
            }
            BeRocket_AAPF::$error_log['_addons_variations_query'][] = array(
                'query'  => $query,
                'result' => $out_of_stock_variable,
                'terms'  => $data
            );
        }
        $post_not_in = array();
        if( is_array($out_of_stock_variable) && count($out_of_stock_variable) ) {
            foreach($out_of_stock_variable as $out_of_stock) {
                $post_not_in[] = $out_of_stock[0];
            }
        }
        return $post_not_in;
    }
    public static function implode_recursive($array, $glue = ' ') {
        foreach($array as &$element) {
            if( is_array($element) ) {
                $element = self::implode_recursive($element, $glue);
            }
        }
        return implode($glue, $array);
    }
    public function faster_recount_add_data($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( ! $use_filters ) return $query;
        $br_options = BeRocket_AAPF::get_aapf_option();
        if( ! empty($br_options['out_of_stock_variable_reload']) ) {
            global $berocket_parse_page_obj;
            $current_filter_data = $berocket_parse_page_obj->get_current();
            $filter_data = $berocket_parse_page_obj->remove_taxonomy(array('taxonomy' => $taxonomy));
            $limit_post__not_in = array();
            foreach($terms as $term_data) {
                $new_filter_data = $berocket_parse_page_obj->modify_data(array(
                    'values' => array(array('value' => intval($term_data->term_id), 'taxonomy' => $taxonomy)), 'type' => 'add', 'op' => 'AND'
                ), $filter_data);
                $berocket_parse_page_obj->set_default_data($new_filter_data);
                $limit_post__not_in[$term_data->term_taxonomy_id] = apply_filters('berocket_add_out_of_stock_variable', array(), $new_filter_data);
            }
            $berocket_parse_page_obj->set_default_data($current_filter_data);
            
            $limit_post__not_in_where_array = array();
            if( is_array($limit_post__not_in) && count($limit_post__not_in) ) {
                $limit_post__term_id_without_product = array();
                foreach($limit_post__not_in as $wp_terms_id => $limit_post) {
                    if( is_array($limit_post) && count($limit_post) ) {
                        $limit_post__not_in_where_array[$wp_terms_id] = "({$wpdb->posts}.ID NOT IN (\"" . implode('","', $limit_post) . "\") AND term_relationships.term_taxonomy_id = {$wp_terms_id})";
                    } else {
                        $limit_post__term_id_without_product[] = $wp_terms_id;
                    }
                }
                if( count($limit_post__term_id_without_product) ) {
                    $limit_post__not_in_where_array[] = "(term_relationships.term_taxonomy_id IN (".implode(', ', $limit_post__term_id_without_product)."))";
                }
                $limit_post__not_in_where = implode(' OR ', $limit_post__not_in_where_array);
            }
            $this->limit_post__not_in_where_array = $limit_post__not_in_where_array;
        }
        return $query;
    }
    public static function replace_query_elements($query, $current_attributes, $current_terms) {
        global $wpdb;
        $query = str_replace(
            array( '%1$s',          '%2$s',             '%3$s',                     '%4$s',                 '%5$s' ),
            array( $wpdb->posts,    $wpdb->postmeta,    $wpdb->term_relationships,  $current_attributes,    $current_terms ),
            $query
        );
        return $query;
    }
}
new BeRocket_AAPF_compat_woocommerce_variation_new();
