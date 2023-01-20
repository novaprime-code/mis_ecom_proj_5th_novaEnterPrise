<?php
class BeRocket_aapf_variations_tables {
    public $variation_attributes = FALSE;
    function __construct() {
        add_filter('berocket_aapf_wcvariation_filtering_total_query', array($this, 'wcvariation_filtering_total_query'), 10, 4);
        add_filter('berocket_aapf_wcvariation_filtering_main_query', array($this, 'wcvariation_filtering_main_query'), 10, 2);
        add_filter('berocket_aapf_wcvariation_filtering_single_attribute', array($this, 'wcvariation_filtering_single_attribute'), 10, 4);
        add_action( 'woocommerce_variation_set_stock_status', array($this, 'set_stock_status'), 10, 3 );
        add_action( 'woocommerce_product_set_stock_status', array($this, 'set_stock_status'), 10, 3 );
        add_action( 'delete_post', array($this, 'delete_post'), 10, 1 );
        add_action( 'woocommerce_after_product_object_save', array($this, 'variation_object_save'), 10, 1 );
        //hierarhical recount custom table
        add_action('berocket_aapf_recount_terms_initialized', array($this, 'recount_terms_initialized'), 10, 1);
        add_filter('berocket_aapf_recount_stock_status_query', array($this, 'recount_stock_status'), 10, 1);
        //Stock status modify
        add_filter('bapf_uparse_generate_tax_query_each', array($this, 'stock_status_tax_query'), 110, 4);
        add_filter('bapf_uparse_generate_custom_query_each', array($this, 'stock_status_custom_query'), 110, 6);
        
        add_filter('bapf_wcvariation_check_is_taxonomy_variable', array($this, 'check_is_taxonomy_variable'), 10, 2);
    }
    function get_current_variation_attributes() {
        if( $this->variation_attributes !== FALSE ) return $this->variation_attributes;
        global $wpdb;
        $result = $wpdb->get_col("SELECT attribute FROM {$wpdb->prefix}braapf_variable_attributes GROUP BY CAST(attribute AS binary)");
        if( ! is_array($result) ) {
            $result = array();
        }
        $this->variation_attributes = $result;
        return $result;
    }
    function check_is_taxonomy_variable($is_var, $taxonomy) {
        $attributes = $this->get_current_variation_attributes();
        return in_array($taxonomy, $attributes);
    }
    function wcvariation_filtering_main_query($query, $data) {
        $current_terms = array(0);
        
        foreach($data['filters'] as $filter) {
            if( substr( $filter['taxonomy'], 0, 3 ) == 'pa_' && ! empty($filter['terms']) ) {
                foreach($filter['terms'] as $term) {
                    $current_terms[] = $term->term_id;
                }
            }
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'braapf_product_variation_attributes';
        $query = array(
            'select'    => 'SELECT '.$table_name.'.post_id as var_id, '.$table_name.'.parent_id as ID, COUNT('.$table_name.'.post_id) as meta_count, max('.$table_name.'.stock_status) as stock_status',
            'from'      => 'FROM '.$table_name,
            'where'     => 'WHERE '.$table_name.'.meta_value_id IN ('.implode(',', $current_terms).')',
            'group'     => 'GROUP BY '.$table_name.'.post_id'
        );
        return $query;
    }
    function wcvariation_filtering_single_attribute($query, $data, $current_attributes_part, $current_terms_part) {
        $current_terms = array(0);
        
        foreach($data['filters'] as $filter) {
            if( $this->check_is_taxonomy_variable(false, $filter['taxonomy']) && ! empty($filter['terms']) ) {
                foreach($filter['terms'] as $term) {
                    $current_terms[] = $term->term_id;
                }
            }
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'braapf_product_variation_attributes';
        $return_query = array(
            'select'        => 'SELECT '.$table_name.'.parent_id as ID, '.$wpdb->term_taxonomy.'.term_taxonomy_id as term_id, MIN(out_of_stock_var.out_of_stock) AS out_of_stock',
            'from'          => 'FROM '.$table_name,
            'join_start'    => $query['join3_start'],
            'join_select'   => $query['join3_select'],
            'join_end'      => ') AS out_of_stock_var ON '.$table_name.'.post_id = out_of_stock_var.var_id',
            'join2'         => "INNER JOIN {$wpdb->term_taxonomy} on {$table_name}.meta_value_id = {$wpdb->term_taxonomy}.term_id",
            'where'         => 'WHERE '.$table_name.'.meta_value_id IN ('.implode(',', $current_terms).')',
            'group'         => 'GROUP BY ID, term_id',
            'having'        => 'HAVING out_of_stock = 1'
        );
        return apply_filters('berocket_aapf_wcvariation_filtering_single_attribute-add_table', $return_query, $data, $current_attributes_part, $current_terms_part);
    }
    function wcvariation_filtering_total_query($query, $data, $current_attributes, $current_terms) {
        $current_attributes = array();
        $current_terms = array(0);
        foreach($data['filters'] as $filter) {
            if( $this->check_is_taxonomy_variable(false, $filter['taxonomy']) ) {
                $taxonomy1 = sanitize_title($filter['taxonomy']);
                $taxonomy2 = urldecode($taxonomy1);
                $current_attributes[] = $taxonomy1;
                if( $taxonomy1 != $taxonomy2 ) {
                    $current_attributes[] = $taxonomy2;
                }
                if( ! empty($filter['terms']) ) {
                    foreach($filter['terms'] as $term) {
                        $current_terms[] = $term->term_id;
                    }
                }
            }
        }
        $current_attributes = array_unique($current_attributes);
        global $wpdb;
        $query['subquery']['subquery_2'] = array(
            'select' => 'SELECT post_id as ID, COUNT(post_id) as max_meta_count',
            'from'   => "FROM {$wpdb->prefix}braapf_variable_attributes",
            'where'  => "WHERE attribute IN ('".implode("','", $current_attributes)."')",
            'group'  => 'GROUP BY post_id',
        );
        
        $query['subquery']['select'] = 'SELECT filtered_post.post_id as var_id, MAX(filtered_post.parent_id) as ID, COUNT(filtered_post.post_id) as meta_count, 
 max(filtered_post.stock_status) as stock_status, MAX(max_filtered_post.max_meta_count) as max_meta_count,
 IF(MAX(max_filtered_post.max_meta_count) != COUNT(filtered_post.post_id) OR max(filtered_post.stock_status) = 0, 1, 0) as out_of_stock';

        $query['subquery']['from_open'] = "FROM {$wpdb->prefix}braapf_product_variation_attributes as filtered_post";
        $query['subquery']['join_close_1'] = ') as max_filtered_post ON max_filtered_post.ID = filtered_post.parent_id';
        unset($query['subquery']['group']);
        $query['subquery']['where'] = 'WHERE filtered_post.meta_value_id IN ('.implode(',', $current_terms).')';
        $query['subquery']['group'] = 'GROUP BY filtered_post.post_id';
        unset($query['subquery']['subquery_1'], $query['subquery']['from_close'], $query['subquery']['join_open_2'], $query['subquery']['subquery_3'], $query['subquery']['join_close_2']);
        return apply_filters('berocket_aapf_wcvariation_filtering_total_query-add_table', $query, $data, $current_attributes, $current_terms);
    }
    function delete_post($product_id) {
        global $wpdb;
        $sql = "DELETE FROM {$wpdb->prefix}braapf_product_stock_status_parent WHERE post_id={$product_id};";
        $wpdb->query($sql);
        $sql = "DELETE FROM {$wpdb->prefix}braapf_product_stock_status_parent WHERE parent_id={$product_id};";
        $wpdb->query($sql);
        $sql = "DELETE FROM {$wpdb->prefix}braapf_product_variation_attributes WHERE post_id={$product_id};";
        $wpdb->query($sql);
        $sql = "DELETE FROM {$wpdb->prefix}braapf_product_variation_attributes WHERE parent_id={$product_id};";
        $wpdb->query($sql);
        $sql = "DELETE FROM {$wpdb->prefix}braapf_variable_attributes WHERE post_id={$product_id};";
        $wpdb->query($sql);
    }
    function set_stock_status($product_id, $stock_status, $product) {
        global $wpdb;
        $parent = wp_get_post_parent_id($product_id);
        $stock_status_int = ($stock_status == 'instock' ? 1 : 0);
        $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_product_stock_status_parent (post_id, parent_id, stock_status) VALUES({$product_id}, {$parent}, {$stock_status_int}) ON DUPLICATE KEY UPDATE stock_status={$stock_status_int}";
        $wpdb->query($sql);
        
        if ( $product->get_manage_stock() ) {
            $children = $product->get_children();
            if ( $children ) {
                $status           = $product->get_stock_status();
                $format           = array_fill( 0, count( $children ), '%d' );
                $query_in         = '(' . implode( ',', $format ) . ')';
                $managed_children = array_unique( $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_manage_stock' AND meta_value != 'yes' AND post_id IN {$query_in}", $children ) ) );
                foreach ( $managed_children as $managed_child ) {
                    $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_product_stock_status_parent (post_id, parent_id, stock_status) VALUES({$managed_child}, {$product_id}, {$stock_status_int}) ON DUPLICATE KEY UPDATE stock_status={$stock_status_int}";
                    $wpdb->query($sql);
                }
            }
        }
    }
    function variation_object_save($product) {
        global $wpdb;
        $product_id = $product->get_id();
        $product_type = $product->get_type();
        if ( defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE && class_exists( 'SitePress' ) ) {
            $current_language = apply_filters( 'wpml_current_language', NULL );
            $language_code = apply_filters('wpml_element_language_code', NULL, array('element_id' => $product_id, 'element_type' => ( $product_type == 'variation' ? 'product_variation' : 'product' ) ));
            do_action( 'wpml_switch_language', $language_code );
        }
        if( $product_type == 'variation' ) {
            $parent_id = $product->get_parent_id();
            $product_attributes = $product->get_variation_attributes();
            $parent_product = wc_get_product($parent_id);
            $stock_status = ($product->is_in_stock() ? '1' : '0');
            $sql = "DELETE FROM {$wpdb->prefix}braapf_product_variation_attributes WHERE post_id={$product_id};";
            $wpdb->query($sql);
            foreach($product_attributes as $taxonomy => $attributes) {
                $taxonomy = str_replace('attribute_', '', $taxonomy);
                if( empty($attributes) ) {
                    $attributes = $parent_product->get_variation_attributes();
                    if( isset($attributes[$taxonomy]) ) {
                        $attributes = $attributes[$taxonomy];
                    } else {
                        $attributes = array();
                    }
                } elseif( ! is_array($attributes) ) {
                    $attributes = array($attributes);
                }
                foreach($attributes as $attribute) {
                    $term = get_term_by('slug', $attribute, $taxonomy);
                    if( $term !== false ) {
                        $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_product_variation_attributes (post_id, parent_id, meta_key, meta_value_id, stock_status) VALUES({$product_id}, {$parent_id}, '{$taxonomy}', {$term->term_id}, '{$stock_status}')";
                        $wpdb->query($sql);
                    }
                }
            }
        } elseif( $product_type == 'variable' ) {
            $child_ids = $product->get_children();
            $sql = "DELETE FROM {$wpdb->prefix}braapf_product_variation_attributes WHERE parent_id = {$product_id};";
            $wpdb->query($sql);
            if( is_array($child_ids) && count($child_ids) > 0 ) {
                $insert_values = array();
                $terms_cache = array();
                $parent_attributes = $product->get_variation_attributes(false);
                if( count($parent_attributes) > 0 ) {
                    foreach($parent_attributes as $taxonomy => $terms_slug) {
                        $terms = get_terms(array('taxonomy' => $taxonomy, 'slug' => $terms_slug));
                        $terms_cache[$taxonomy] = array();
                        if( is_array($terms) ) {
                            foreach($terms as $term) {
                                $terms_cache[$taxonomy][$term->slug] = $term;
                            }
                        }
                    }
                }
                $sql = "SELECT post_id as id, meta_key as k, meta_value as v FROM {$wpdb->postmeta} WHERE post_id IN (".implode(',', $child_ids).") AND meta_key LIKE 'attribute_%'";
                $result = $wpdb->get_results($sql);
                $child_attributes = array();
                foreach($result as $attr_val) {
                    if( empty($child_attributes[$attr_val->id]) ) {
                        $child_attributes[$attr_val->id] = array();
                    }
                    $child_attributes[$attr_val->id][str_replace('attribute_', '', $attr_val->k)] = $attr_val->v;
                }
                foreach($child_ids as $child_id) {
                    $time_post = microtime(true);
                    $variation = wc_get_product( $child_id );
                    if( ! empty($variation) ) {
                        $stock_status = ($variation->is_in_stock() ? '1' : '0');
                    } else {
                        $stock_status = ($product->is_in_stock() ? '1' : '0');
                    }
                    if( empty($child_attributes[$child_id]) ) {
                        if( empty($variation) ) {
                            $product_attributes = array();
                        } else {
                            $product_attributes = $variation->get_variation_attributes(false);
                        }
                    } else {
                        $product_attributes = $child_attributes[$child_id];
                    }
                    foreach($product_attributes as $taxonomy => $attributes) {
                        if( empty($attributes) ) {
                            $attributes = array();
                            if( isset($parent_attributes[$taxonomy]) ) {
                                $attributes = $parent_attributes[$taxonomy];
                            }
                        } elseif( ! is_array($attributes) ) {
                            $attributes = array($attributes);
                        }
                        foreach($attributes as $attribute) {
                            if( empty($terms_cache[$taxonomy]) || empty($terms_cache[$taxonomy][$attribute]) ) {
                                if( empty($terms_cache[$taxonomy]) ) {
                                    $terms_cache[$taxonomy] = array();
                                }
                                $term_test = get_term_by('slug', $attribute, $taxonomy);
                                if( $term_test === false ) {
                                    $term_test = get_term_by('slug', $attribute, urldecode($taxonomy));
                                }
                                $terms_cache[$taxonomy][$attribute] = $term_test;
                            }
                            $term = $terms_cache[$taxonomy][$attribute];
                            if( $term !== false ) {
                                $insert_values[] = "({$child_id}, {$product_id}, '{$taxonomy}', {$term->term_id}, '{$stock_status}')";
                            }
                        }
                    }
                }
                if( count($insert_values) > 0 ) {
                    $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_product_variation_attributes (post_id, parent_id, meta_key, meta_value_id, stock_status) 
                    VALUES ".implode(',', $insert_values);
                    $wpdb->query($sql);
                }
            }
            $wpdb->query($sql);
            $sql = "DELETE FROM {$wpdb->prefix}braapf_variable_attributes WHERE post_id={$product_id};";
            $product_attribute = get_post_meta($product_id, '_product_attributes', true);
            $insert_values = array();
            if( is_array($product_attribute) ) {
                foreach($product_attribute as $attribute) {
                    if( ! empty($attribute['is_variation']) ) {
                        $insert_values[] = "({$product_id}, '".$attribute['name']."')";
                    }
                }
            }
            if( ! empty($insert_values) ) {
                $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_variable_attributes (post_id, attribute) 
                    VALUES ".implode(',', $insert_values);
                $wpdb->query($sql);
            }
            $table_name = $wpdb->prefix . 'braapf_variable_attributes';
            $sql_select = "SELECT {$table_name}.post_id as post_id, 
                   {$table_name}.post_id as parent_id,
                   {$table_name}.attribute as meta_key,
                   {$wpdb->term_taxonomy}.term_id as meta_value_id,
                   0 as stock_status
            FROM {$table_name}
            JOIN {$wpdb->term_relationships} ON {$table_name}.post_id = {$wpdb->term_relationships}.object_id
            JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id AND {$wpdb->term_taxonomy}.taxonomy = {$table_name}.attribute
            LEFT JOIN {$wpdb->prefix}braapf_product_variation_attributes ON {$wpdb->prefix}braapf_product_variation_attributes.parent_id = {$table_name}.post_id AND {$wpdb->prefix}braapf_product_variation_attributes.meta_value_id = {$wpdb->term_taxonomy}.term_id
            WHERE {$wpdb->prefix}braapf_product_variation_attributes.meta_value_id IS NULL 
                  AND {$table_name}.post_id = {$product_id}";
            $test_row = $wpdb->get_row($sql_select);
            if( ! empty($test_row) ) {
                $sql = "INSERT IGNORE INTO {$wpdb->prefix}braapf_product_variation_attributes {$sql_select}";
                $query_status = $wpdb->query($sql);
            }
        }
        if ( defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE && class_exists( 'SitePress' ) ) {
            do_action( 'wpml_switch_language', $current_language );
        }
    }
    function recount_terms_initialized($recount_object) {
        remove_filter('berocket_aapf_recount_terms_query', array($recount_object, 'child_include'), 50, 3);
        add_filter('berocket_aapf_recount_terms_query', array($this, 'child_include'), 50, 3);
    }
    public $hierarhical_data_to_table_ready = array();
    function child_include($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( $include_child ) {
            $taxonomy_object = get_taxonomy($taxonomy);
            if( ! empty($taxonomy_object->hierarchical) ) {
                $this->set_hierarhical_data_to_table($taxonomy);
                $table_name = $wpdb->prefix . 'braapf_term_taxonomy_hierarchical';
                $join_query = "INNER JOIN (SELECT object_id,term_taxonomy.term_taxonomy_id as term_taxonomy_id, term_order FROM {$wpdb->term_relationships}
                JOIN $table_name as term_taxonomy 
                ON {$wpdb->term_relationships}.term_taxonomy_id = term_taxonomy.term_taxonomy_child_id ) as term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
                $query['join']['term_relationships'] = $join_query;
            }
        }
        return $query;
    }
    function set_hierarhical_data_to_table($taxonomy) {
        if( in_array($taxonomy, $this->hierarhical_data_to_table_ready) ) return;
        global $wpdb;
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $newmd5 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MD5(GROUP_CONCAT(tt.term_taxonomy_id+tt.term_id+tt.parent+tt.count)) FROM $wpdb->term_taxonomy AS tt 
                WHERE tt.taxonomy IN (%s)",
                $taxonomy
            )
        );
        $newmd5 = apply_filters('BRaapf_cache_check_md5', $newmd5, 'br_generate_child_relation', $taxonomy);
        $md5 = get_option(apply_filters('br_aapf_md5_cache_text', 'br_custom_table_hierarhical_'.$taxonomy));
        if($md5 != $newmd5) {
            $categories_ids = $this->get_terms_all(array('taxonomy' => $taxonomy, 'hide_empty' => false, 'suppress_filter' => true));
            if( empty($categories_ids) || is_wp_error($categories_ids) ) {
                return;
            }
            $hierarchy = $this->taxonomy_hierarchical_get($taxonomy);
            $new_categories = array();
            foreach($categories_ids as $categories_id) {
                $new_categories[$categories_id->term_id] = $categories_id;
            }
            unset($categories_ids);
            $table_name = $wpdb->prefix . 'braapf_term_taxonomy_hierarchical';
            $wpdb->query("DELETE FROM $table_name WHERE taxonomy = '$taxonomy';");
            $join_query = "INSERT IGNORE INTO $table_name VALUES ";
            $join_list = array();
            $drop_query = array();
            $count = 0;
            foreach($hierarchy as $term_id => $term_childs) {
                foreach($term_childs as $term_child) {
                    if( ! empty($new_categories[$term_id]) && ! empty($new_categories[$term_child]) ) {
                        $drop_query[] = sprintf("(%d,%d,%d,%d,'%s')", 
                            $new_categories[$term_child]->term_taxonomy_id,
                            $new_categories[$term_child]->term_id,
                            $new_categories[$term_id]->term_taxonomy_id,
                            $new_categories[$term_id]->term_id,
                            $new_categories[$term_id]->taxonomy);
                        $count++;
                        if( $count > 100 ) {
                            $join_list[] = $drop_query;
                            $drop_query = array();
                            $count = 0;
                        }
                    }
                }
            }
            $join_list[] = $drop_query;
            unset($drop_query, $count);
            foreach($join_list as $drop_query) {
                if( empty($drop_query) ) continue;
                $drop_query = implode(',', $drop_query);
                $drop_query = $join_query . $drop_query;
                $wpdb->query($drop_query);
            }
            update_option(apply_filters('br_aapf_md5_cache_text', 'br_custom_table_hierarhical_'.$taxonomy), $newmd5);
        }
        $this->hierarhical_data_to_table_ready[] = $taxonomy;
    }
    function taxonomy_hierarchical_get($taxonomy) {
        $terms = $this->get_terms_all(array(
            'hide_empty'        => false,
            'taxonomy'          => $taxonomy,
            'suppress_filter'   => true
        ));
        $term_id_terms = array();
        foreach($terms as $term) {
            $term_id_terms[$term->term_id] = $term;
        }
        unset($terms);
        foreach($term_id_terms as $term_id => $term) {
            $term_id_terms = $this->find_all_parent($term_id_terms, $term_id);
        }
        foreach($term_id_terms as $term_id => $term) {
            $term_id_terms[$term_id] = $term->all_parents;
        }
        return $term_id_terms;
    }
    function find_all_parent($terms, $i) {
        if( ! empty($terms[$i]->all_parents) ) {
            return $terms;
        }
        $ids = array();
        $ids[] = $terms[$i]->term_id;
        if( $terms[$i]->parent != 0 && isset($terms[$terms[$i]->parent]) ) {
            if( empty($terms[$terms[$i]->parent]->all_parents) ) {
                $terms = $this->find_all_parent($terms, $terms[$i]->parent);
            }
            $ids = array_merge($ids, $terms[$terms[$i]->parent]->all_parents);
        }
        $terms[$i]->all_parents = $ids;
        return $terms;
    }
    function get_terms_all($args) {
        //WPML Compatibility Part
        $languages = apply_filters('wpml_active_languages', array());
        $wpml_active_languages = apply_filters('wpml_current_language', NULL);
        if( is_array($languages) && count($languages) && $wpml_active_languages != NULL ) {
            $terms = array();
            foreach($languages as $language_code => $language) {
                do_action( 'wpml_switch_language', $language_code );
                $single_lang_terms = get_terms($args);
                if( is_array($single_lang_terms) ) {
                    $terms = array_merge($terms, $single_lang_terms);
                }
            }
            do_action( 'wpml_switch_language', $wpml_active_languages );
        } elseif( function_exists('pll_current_language') ) {
            //Polylang Compatibility Part
            $args['lang'] = '';
            $terms = get_terms($args);
        } else {
            $terms = get_terms($args);
        }
        return $terms;
    }
    function recount_stock_status($join_query) {
        global $wpdb;
        $join_query = "INNER JOIN (SELECT post_id as object_id, IF(stock_status = 0, 2, 1) as term_taxonomy_id, 0 as term_order 
                FROM {$wpdb->prefix}braapf_product_stock_status_parent
                WHERE parent_id = 0) as term_relationships
                ON {$wpdb->posts}.ID = term_relationships.object_id";
        return $join_query;
    }
    function stock_status_tax_query($result, $instance, $filter, $data) {
        if( $result !== null && isset($filter['type']) && $filter['type'] == 'stock_status' ) {
            $result = null;
        }
        return $result;
    }
    function stock_status_custom_query($result, $instance, $filter, $data) {
        if( $result === null && isset($filter['type']) && $filter['type'] == 'stock_status' ) {
            $status = 'none';
            foreach($filter['terms'] as $filter_term) {
                if($status == 'none' ) {
                    $status = $filter_term->slug;
                } else {
                    $status = 'both';
                }
            }
            if( $status != 'both' && $status != 'none' ) {
                $result = $filter;
                $result['custom_query'] = array($this, 'stock_status_post_clauses');
                $result['custom_query_line'] = 'stock_status:' . $status;
            }
        }
        return $result;
    }
    function stock_status_post_clauses($args, $filter) {
        global $wpdb;
        $status = 'none';
        foreach($filter['terms'] as $filter_term) {
            if($status == 'none' ) {
                $status = $filter_term->slug;
            } else {
                $status = 'both';
            }
        }
        $args['join'] .= " INNER JOIN {$wpdb->prefix}braapf_product_stock_status_parent ON {$wpdb->posts}.ID = {$wpdb->prefix}braapf_product_stock_status_parent.post_id";
        $args['where'] .= " AND {$wpdb->prefix}braapf_product_stock_status_parent.stock_status = ".($status == 'instock' ? '1' : '0').' ';
        return $args;
    }
}
new BeRocket_aapf_variations_tables();
