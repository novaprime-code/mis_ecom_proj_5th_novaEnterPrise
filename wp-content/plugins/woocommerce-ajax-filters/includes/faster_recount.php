<?php
class BeRocket_AAPF_faster_attribute_recount {
    function __construct() {
        add_filter('berocket_aapf_recount_terms_apply', array(__CLASS__, 'recount_terms'), 10, 2);
        add_filter('berocket_aapf_recount_terms_query', array(__CLASS__, 'search_query'), 50, 3);
        add_filter('berocket_aapf_recount_terms_query', array(__CLASS__, 'wpml_query'), 70, 3);
        //Child terms include for hierarchical taxonomy
        add_filter('berocket_aapf_recount_terms_query', array(__CLASS__, 'child_include'), 50, 3);
        //Stock Status custom recount
        add_filter('berocket_aapf_recount_terms_query', array(__CLASS__, 'stock_status_query'), 20, 3);
        //Sale Status custom recount
        add_filter('berocket_aapf_recount_terms_query', array(__CLASS__, 'onsale_query'), 20, 3);
        add_action('plugins_loaded', array(__CLASS__, 'plugins_loaded'));
    }
    static function plugins_loaded() {
        do_action('berocket_aapf_recount_terms_initialized', __CLASS__);
    }
    static function recount_terms($terms = FALSE, $taxonomy_data = array()) {
        $taxonomy_data = apply_filters('berocket_recount_taxonomy_data', array_merge(array(
            'taxonomy'      => '',
            'operator'      => 'OR',
            'use_filters'   => TRUE,
            'tax_query'     => FALSE,
            'meta_query'    => FALSE,
            'post__not_in'  => array(),
            'post__in'      => array(),
            'include_child' => TRUE,
            'additional_tax_query' => FALSE
        ), $taxonomy_data), $terms);
        global $braapf_recount_taxonomy_data;
        $braapf_recount_taxonomy_data = $taxonomy_data;
        do_action('berocket_term_recount_before_action', $terms, $taxonomy_data);
        $result = self::recount_terms_without_prepare($terms, $taxonomy_data);
        do_action('berocket_term_recount_after_action', $terms, $taxonomy_data);
        $braapf_recount_taxonomy_data = FALSE;
        return $result;
    }
    static function recount_terms_without_prepare($terms = FALSE, $taxonomy_data = array()) {
        if( BeRocket_AAPF::$debug_mode ) {
            if( empty(BeRocket_AAPF::$error_log['faster_recount_sql']) || ! is_array(BeRocket_AAPF::$error_log['faster_recount_sql']) ) {
                BeRocket_AAPF::$error_log['faster_recount_sql'] = array();
            }
        }
        extract($taxonomy_data);
        global $wpdb, $berocket_parse_page_obj;
        if( $terms === FALSE ) {
            $terms = self::get_terms($taxonomy);
        }
        if( empty($terms) || is_wp_error($terms) ) {
            if( BeRocket_AAPF::$debug_mode ) {
                $taxonomy_data['error'] = 'Empty terms';
                BeRocket_AAPF::$error_log['faster_recount_sql'][] = $taxonomy_data;
            }
            return array();
        }
        do_action('bapf_faster_recount_before_recount_terms', $terms, $taxonomy_data);
        $taxonomy_data['term_taxonomy_ids'] = $term_taxonomy_ids    = wp_list_pluck($terms, 'term_taxonomy_id', 'term_id');
        $new_taxonomy_data = self::get_query_for_calculate(array(
            'use_filters'   => $use_filters,
            'add_tax_query' => ( empty($taxonomy_data['additional_tax_query']) ? array() : $taxonomy_data['additional_tax_query'] ),
            'taxonomy_remove' => (strtoupper($operator) == 'OR' ? $taxonomy : FALSE),
            'taxonomy_data'   => $taxonomy_data
        ));
        $query = $new_taxonomy_data['query'];
        unset($new_taxonomy_data['query']);
        $taxonomy_data = array_merge($taxonomy_data, $new_taxonomy_data);
        $query['select']['elements'] = array(
            'term_count'    => "COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count",
            'term_count_id' => "MAX(term_relationships.term_taxonomy_id) as term_count_id",
        );
        $query['join']['term_relationships'] = "INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
        $query['where']['term_taxonomy_id']  = 'AND term_relationships.term_taxonomy_id IN (' . implode( ',', array_map( 'absint', $term_taxonomy_ids ) ) . ')';
        $query['group_by'] = 'GROUP BY term_relationships.term_taxonomy_id';
        $query             = apply_filters('berocket_aapf_recount_terms_query', $query, $taxonomy_data, $terms);
        $query['select']['elements']= implode(', ', $query['select']['elements']);
        $query['select']   = implode(' ', $query['select']);
        $query['join']     = implode(' ', $query['join']);
        $query['where']    = implode(' ', $query['where']);
        $query             = apply_filters('woocommerce_get_filtered_term_product_counts_query', $query);
        if( $use_filters ) {
            $query             = apply_filters( 'berocket_posts_clauses_recount', $query, $terms, $taxonomy_data );
        }
        $query_imploded    = implode( ' ', $query );
        $use_recount_cache = apply_filters('berocket_recount_cache_use', (! $use_filters), $taxonomy_data);
        if( $use_recount_cache ) {
            $terms_cache = br_get_cache(apply_filters('berocket_recount_cache_key', md5(json_encode($query_imploded)), $taxonomy_data), 'berocket_recount');
        }
        if( empty($terms_cache) ) {
            $result            = $wpdb->get_results( $query_imploded );
            $result            = apply_filters('berocket_query_result_recount', $result, $query, $terms);
            $result            = wp_list_pluck($result, 'term_count', 'term_count_id');
            foreach($terms as &$term) {
                $term->count   = (isset($result[$term->term_taxonomy_id]) ? $result[$term->term_taxonomy_id] : 0);
            }
            if( isset($term) ) {
                unset($term);
            }
            $terms             = apply_filters('berocket_terms_after_recount', $terms, $query, $result);
            if( $use_recount_cache ) {
                br_set_cache(md5(json_encode($query_imploded)), $terms, 'berocket_recount', DAY_IN_SECONDS);
            }
        } else {
            $terms = $terms_cache;
        }
        if( BeRocket_AAPF::$debug_mode ) {
            $taxonomy_data['query_imploded']    = $query_imploded;
            $taxonomy_data['return_terms']      = $terms;
            $taxonomy_data['result']            = (isset($result) ? $result : 'cache');
            BeRocket_AAPF::$error_log['faster_recount_sql'][] = $taxonomy_data;
        }
        do_action('bapf_faster_recount_after_recount_terms', $terms, $taxonomy_data);
        self::restore_url_data_after_recount();
        return apply_filters('berocket_terms_recount_return', $terms, $taxonomy_data, $query_imploded);
    }
    static $current_url_data = FALSE;
    static function restore_url_data_after_recount() {
        if( self::$current_url_data !== FALSE ) {
            global $berocket_parse_page_obj;
            $berocket_parse_page_obj->set_default_data(self::$current_url_data);
            self::$current_url_data = FALSE;
        }
    }
    static function get_query_for_calculate($additional_data = array()) {
        global $wpdb, $berocket_parse_page_obj;
        $additional_data = array_merge(array(
            'tax_query'       => array(),
            'meta_query'      => array(),
            'post__in'        => array(),
            'post__not_in'    => array(),
            'add_tax_query'   => array(),
            'taxonomy_remove' => false,
            'use_filters'     => false
        ), $additional_data);
        $query_vars = $berocket_parse_page_obj->query_vars;
        $wc_main_query = WC_Query::get_main_query();
        $author = false;
        self::$current_url_data = $berocket_parse_page_obj->get_current();
        if( ! empty($additional_data['use_filters']) ) {
            if( $additional_data['taxonomy_remove'] === false ) {
                $filter_data = self::$current_url_data;
            } else {
                $filter_data = $berocket_parse_page_obj->remove_taxonomy(
                    apply_filters('bapf_faster_recount_remove_taxonomy_data', 
                    array('taxonomy' => $additional_data['taxonomy_remove']),
                    $additional_data)
                );
            }
            $berocket_parse_page_obj->set_default_data($filter_data);
            $query_vars = apply_filters('bapf_uparse_apply_filters_to_query_vars', $query_vars);
            $query_vars = apply_filters('bapf_faster_recount_get_query_for_calculate', $query_vars, self::$current_url_data);
        }
        $tax_query = $meta_query = $post__not_in = $post__in = false;
        if( ! empty($query_vars) ) {
            if( ! empty($query_vars['tax_query']) ) {
                $tax_query  = $query_vars['tax_query'];
            }
            if( ! empty($query_vars['meta_query']) ) {
                $meta_query = $query_vars['meta_query'];
            }
            if( ! empty($query_vars['post__not_in']) ) {
                $post__not_in = $query_vars['post__not_in'];
            }
            if( ! empty($query_vars['post__in']) ) {
                $post__in = $query_vars['post__in'];
            }
        }
        if( ! empty($wc_main_query) ) {
            $author = $wc_main_query->get('author');
            if( empty($author) ) {
                $author = false;
            }
        }
        if( ! empty($additional_data['add_tax_query']) ) {
            if( empty($tax_query) ) {
                $tax_query = array(
                    'relation' => 'AND',
                );
            }
            $tax_query['additional_tax_query'] = $additional_data['add_tax_query'];
        }
        $taxonomy_data = array();
        $taxonomy_data['meta_query_ready']  = $meta_query           = new WP_Meta_Query( $meta_query );
        $taxonomy_data['tax_query_ready']   = $tax_query            = new WP_Tax_Query( ( empty($tax_query) || ! is_array($tax_query) ? array() : $tax_query ) );
        $taxonomy_data['meta_query_sql']    = $meta_query_sql       = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $taxonomy_data['tax_query_sql']     = $tax_query_sql        = $tax_query->get_sql( $wpdb->posts, 'ID' );

        // Generate query.
        $query = array(
            'select' => array(
                'select'    => "SELECT", 
                'elements'  => array('*'),
            ),
            'from'  => "FROM {$wpdb->posts}",
            'join'  => array(
                'tax_query' => $tax_query_sql['join'],
                'meta_query' => $meta_query_sql['join'],
            ),
            'where' => array(
                'where_main'        => "WHERE {$wpdb->posts}.post_type IN ( 'product' ) AND {$wpdb->posts}.post_status = 'publish'",
                'tax_query'         => $tax_query_sql['where'],
                'meta_query'        => $meta_query_sql['where'],
            ),
        );
        if( $author != false ) {
            $query['where']['author'] = "AND {$wpdb->posts}.post_author IN ({$author})";
        }
        if( ! empty($post__not_in) ) {
            $query['where']['post__not_in'] = "AND {$wpdb->posts}.ID NOT IN (\"" . implode('","', $post__not_in) . "\")";
        }
        if( ! empty($post__in) ) {
            $query['where']['post__in'] = "AND {$wpdb->posts}.ID IN (\"" . implode('","', $post__in) . "\")";
        }
		if ( ! empty( $query_vars['date_query'] ) ) {
			$date_query = new WP_Date_Query( $query_vars['date_query'] );
			$query['where']['date'] = $date_query->get_sql();
		}
        $taxonomy_data['query'] = $query;
        return $taxonomy_data;
    }
    static function child_include($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( $include_child ) {
            $taxonomy_object = get_taxonomy($taxonomy);
            if( ! empty($taxonomy_object->hierarchical) ) {
                $hierarchy = br_get_taxonomy_hierarchy(array('taxonomy' => $taxonomy, 'return' => 'child'));
                $join_query = "INNER JOIN (SELECT object_id,tt1id as term_taxonomy_id, term_order FROM {$wpdb->term_relationships}
                JOIN (
                    SELECT tt1.term_taxonomy_id as tt1id, tt2.term_taxonomy_id as tt2id FROM {$wpdb->term_taxonomy} as tt1
                    JOIN {$wpdb->term_taxonomy} as tt2 ON (";
                $join_list = array("(tt1.term_id = tt2.term_id)");
                foreach($hierarchy as $term_id => $term_child) {
                    if( count($term_child) > 1 || $term_id != $term_child[0] ) {
                        $current = array_search($term_id, $term_child);
                        if( $current !== false ) {
                            unset($term_child[$current]);
                        }
                        $join_list[] = "(tt1.term_id = '{$term_id}' AND tt2.term_id IN('".implode("','", $term_child)."'))";
                    }
                }
                $join_query .= implode('
                 OR 
                 ', $join_list);
                $join_query .= ") ) as term_taxonomy 
                ON {$wpdb->term_relationships}.term_taxonomy_id = term_taxonomy.tt2id ) as term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
                $query['join']['term_relationships'] = $join_query;
            }
        }
        return $query;
    }
    static function search_query($query, $taxonomy_data, $terms) {
        extract($taxonomy_data);
        if( ! empty($use_filters) ) {
            $wc_main_query = WC_Query::get_main_query();
            if( ! empty($wc_main_query) ) {
                $search = WC_Query::get_main_search_query_sql();
                if ( $search ) {
                    $query['where']['search'] = 'AND ' . $search;
                }
            }
        }
        return $query;
    }
    static function wpml_query($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( defined( 'WCML_VERSION' ) && defined('ICL_LANGUAGE_CODE') ) {
            $query['join']['wpml']  = " INNER JOIN {$wpdb->prefix}icl_translations as wpml_lang ON ( {$wpdb->posts}.ID = wpml_lang.element_id )";
            $query['where']['wpml'] = " AND wpml_lang.language_code = '".ICL_LANGUAGE_CODE."' AND wpml_lang.element_type = 'post_product'";
        }
        return $query;
    }
    static function get_all_taxonomies($taxonomy = FALSE) {
        if( empty($taxonomy) ) {
            $attributes = wc_get_attribute_taxonomies();
            $taxonomy = array();
            foreach($attributes as $attribute) {
                $taxonomy[] = 'pa_'.$attribute->attribute_name;
            }
        } elseif( ! is_array($taxonomy) ) {
            $taxonomy = array($taxonomy);
        }
        return $taxonomy;
    }
    static function get_terms($taxonomy) {
        if( ! empty($taxonomy) ) {
            $terms = get_terms(array('taxonomy' => $taxonomy) );
        } else {
            $taxonomy = self::get_all_taxonomies();
            $terms = get_terms(array('taxonomy' => $taxonomy) );
        }
        return $terms;
    }
    static function stock_status_query($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( $taxonomy == '_stock_status' ) {
            $join_query = apply_filters('berocket_aapf_recount_stock_status_query', '', $query, $taxonomy_data, $terms);
            if( empty($join_query) ) {
                $outofstock = wc_get_product_visibility_term_ids();
                if( empty($outofstock['outofstock']) ) {
                    $outofstock = get_term_by( 'slug', 'outofstock', 'product_visibility' );
                    $outofstock = $outofstock->term_taxonomy_id;
                } else {
                    $outofstock = $outofstock['outofstock'];
                }
                $join_query = "INNER JOIN (SELECT {$wpdb->posts}.ID as object_id, IF({$wpdb->term_relationships}.term_taxonomy_id = {$outofstock}, 2, 1) as term_taxonomy_id, 0 as term_order FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id AND {$wpdb->term_relationships}.term_taxonomy_id = {$outofstock}
                WHERE {$wpdb->posts}.post_type = 'product') as term_relationships
                ON {$wpdb->posts}.ID = term_relationships.object_id";
            }
            $query['join']['term_relationships'] = $join_query;
        }
        return $query;
    }
    static function onsale_query($query, $taxonomy_data, $terms) {
        global $wpdb;
        extract($taxonomy_data);
        if( $taxonomy == '_sale' ) {
            $join_query = "INNER JOIN (";
            /*if( ! empty($wpdb->wc_product_meta_lookup) ) {
                $join_query .= "SELECT {$wpdb->wc_product_meta_lookup}.product_id as object_id, IF({$wpdb->wc_product_meta_lookup}.onsale = 1, 1, 2) as term_taxonomy_id, 0 as term_order FROM {$wpdb->wc_product_meta_lookup}";
            } else {*/
                $products_id = wc_get_product_ids_on_sale();
                $products_id[] = 0;
                $join_query .= "SELECT {$wpdb->posts}.ID as object_id, IF({$wpdb->posts}.ID IN (".implode(',', $products_id)."), 1, 2) as term_taxonomy_id, 0 as term_order FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_type = 'product'";
            //}
            $join_query .= ") as term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
            $query['join']['term_relationships'] = $join_query;
        }
        return $query;
    }
}
new BeRocket_AAPF_faster_attribute_recount();
