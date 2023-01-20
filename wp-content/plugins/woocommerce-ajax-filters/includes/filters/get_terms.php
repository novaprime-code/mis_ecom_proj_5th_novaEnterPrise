<?php
class BeRocket_AAPF_get_terms {
    public static $init = false;
    public static $md5_taxonomies = array();
    public static $cache_time = 12 * HOUR_IN_SECONDS;
    public static $prepared_data = array();
    public static $cached_values = array();
    function __construct() {
        if( ! self::$init ) {
            self::$init = true;
            add_filter("berocket_aapf_get_terms_filter", array(__CLASS__, 'additional_sort'), 20, 3);
            add_filter("berocket_aapf_get_terms_filter", array(__CLASS__, 'hierarchical_sort'), 30, 3);
            add_filter("berocket_aapf_get_terms_filter", array(__CLASS__, 'depth_clear'), 40, 3);
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            $option = $BeRocket_AAPF->get_option();
            add_filter('berocket_aapf_get_terms_filter_after', array(__CLASS__, 'prepared_data'), 1, 3);
            if( ! empty($option['recount_hide']) && $option['recount_hide'] != 'disable' ) {
                if( in_array($option['recount_hide'], array('removeFirst', 'removeFirst_recount')) ) {
                    add_filter("berocket_aapf_filter_terms_for_current_page", array(__CLASS__, 'show_all_values'), 10, 3);
                }
                add_filter("berocket_aapf_get_terms_filter_after", array(__CLASS__, 'hide_empty'), (in_array($option['recount_hide'], array('removeRecount')) ? 35 : 15), 3);
                if( in_array(br_get_value_from_array($option,'recount_hide'), array('recount', 'removeRecount'))
                || (in_array(br_get_value_from_array($option,'recount_hide'), array('removeFirst_recount')) && ( ! empty($option['out_of_stock_variable_reload']) && ! empty($option['out_of_stock_variable']) ) ) ) {
                    add_filter("berocket_aapf_get_terms_filter_after", array(__CLASS__, 'recount_products'), 30, 3);
                }
                if( in_array($option['recount_hide'], array('removeFirst_recount')) ) {
                    add_filter("berocket_aapf_get_terms_filter_after", array(__CLASS__, 'recount_products_if_filtered'), 80, 3);
                }
            }
        }
    }
    public static function recount_products_if_filtered($terms, $args = array(), $additional = array()) {
        if( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'get_filter_args' ) || is_filtered() ) {
            $terms = self::recount_products($terms, $args, $additional);
        }
        return $terms;
    }
    public static function get_terms($args = array(), $additional = array()) {
        $args = apply_filters('berocket_aapf_get_terms_class_args', $args, $additional);
        $additional = apply_filters('berocket_aapf_get_terms_class_additional', array_merge(array(
            'hierarchical'          => true,
            'tax_query_limit'       => array(),
            'meta_query_limit'      => array(),
            'depth'                 => 0,
            'operator'              => 'OR',
            'recount_tax_query'     => false,
            'additional_tax_query'  => false,
            'disable_recount'       => false,
            'disable_hide_empty'    => false,
        ), $additional), $args);
        $terms = apply_filters('berocket_aapf_get_terms_filter_before', false, $args, $additional);
        if( $terms !== false ) {
            return $terms;
        }
        if( empty($args['taxonomy']) || is_array($args['taxonomy']) ) {
            return get_terms($args);
        }
        if( ! empty($args['taxonomy']) && is_array($args['taxonomy']) && count($args['taxonomy']) == 1 ) {
            $args['taxonomy'] = array_pop($args['taxonomy']);
        }
        $terms = self::filter_terms_for_current_pages($args, $additional);
        if( ! empty($args['taxonomy']) && is_array($args['taxonomy']) && count($args['taxonomy']) == 1 ) {
            $args['taxonomy'] = array_pop($args['taxonomy']);
        }
        if( empty($terms) || is_wp_error($terms) || empty($args['taxonomy']) || is_array($args['taxonomy']) ) {
            $terms = apply_filters("berocket_aapf_get_terms_filter_after_not_correct", $terms, $args, $additional);
            if( ! is_array($terms) ) {
                $terms = array();
            }
            return $terms;
        }
        $terms = apply_filters("berocket_aapf_get_terms_filter_after", $terms, $args, $additional);
        
        return $terms;
    }
    public static function get_terms_for_all_pages($args = array(), $additional = array()) {
        $md5_cache = md5(json_encode($additional).json_encode($args));
        $terms = self::get_cache('term_before_recount', $md5_cache, $args['taxonomy']);
        if( empty($terms) ) {
            $taxonomy = $args['taxonomy'];
            $terms = get_terms($args);
            $args['taxonomy'] = $taxonomy;
            if( ! empty($terms) && ! is_wp_error($terms) ) {
                $terms = apply_filters("berocket_aapf_get_terms_filter", $terms, $args, $additional);
            }
            self::set_cache('term_before_recount', $terms, $md5_cache, $args['taxonomy']);
        }
        return $terms;
    }
    public static function filter_terms_for_current_pages($args = array(), $additional = array()) {
        $md5_cache = md5(json_encode($additional).json_encode($args));
        $terms = self::get_cache('term_after_recount' . self::get_page_text(), $md5_cache, $args['taxonomy']);
        if( empty($terms) ) {
            $terms = self::get_terms_for_all_pages($args, $additional);
            $terms = apply_filters('berocket_aapf_filter_terms_for_current_page', $terms, $args, $additional);
            self::set_cache('term_after_recount' . self::get_page_text(), $terms, $md5_cache, $args['taxonomy']);
        }
        return $terms;
    }
    public static function additional_sort($terms, $args = array(), $additional = array()) {
        if( ! empty($additional['orderby']) ) {
            if( $additional['orderby'] == 'name_numeric_full' ) {
                $array_sort = array();
                foreach($terms as $term) {
                    $array_sort[] = floatval($term->name);
                }
                array_multisort($array_sort, SORT_ASC, SORT_NUMERIC, $terms);
            } elseif( $additional['orderby'] == 'slug' || $additional['orderby'] == 'slug_num' ) {
                $array_sort = array();
                foreach($terms as $term) {
                    $array_sort[] = floatval($term->slug);
                }
                array_multisort($array_sort, (berocket_isset($args['order']) == 'DESC'? SORT_DESC : SORT_ASC), ($additional['orderby'] == 'slug_num' ? SORT_NUMERIC : SORT_STRING), $terms);
            }
        }
        return $terms;
    }
    public static function hierarchical_sort($terms, $args = array(), $additional = array()) {
        $taxonomy_object = get_taxonomy($args['taxonomy']);
        if( empty($additional['hierarchical']) || empty($taxonomy_object->hierarchical) ) {
            return $terms;
        }
        $sorts = array_column($terms, 'parent', 'term_id');
        $terms_parent = $terms_sorted = array();
        foreach($sorts as $term_id => $parent) {
            if( ! isset($terms_parent[$parent]) ) {
                $terms_parent[$parent] = array();
            }
            if( $parent == 0 || $parent == berocket_isset($args['child_of']) || ! array_key_exists($parent, $sorts) ) {
                $terms_sorted[$term_id] = $parent;
            } else {
                $terms_parent[$parent][$term_id] = $parent;
            }
        }
        do{
            $moved = false;
            foreach($terms_parent as $parent_id => $terms_child) {
                if(isset($terms_sorted[$parent_id])) {
                    $terms_sorted = berocket_aapf_insert_to_array($terms_sorted, $parent_id, $terms_child);
                    $moved = true;
                    unset($terms_parent[$parent_id]);
                }
            }
        }while(count($sorts) && $moved);
        foreach($terms_parent as $parent_id => $terms_child) {
            $terms_sorted += $terms_child;
        }
        foreach($terms_sorted as $term_id => $parent) {
            $terms_sorted[$term_id] = (isset($terms_sorted[$parent]) ? $terms_sorted[$parent] + 1 : 0);
        }
        $array_sort = array_flip(array_keys($sorts));
        $array_new_sort = array_flip(array_keys($terms_sorted));
        foreach($array_sort as $term_id => &$sort_number) {
            $sort_number = (isset($array_new_sort[$term_id]) ? $array_new_sort[$term_id] : 999999999);
        }
        if( isset($sort_number) ) {
            unset($sort_number);
        }
        foreach($terms as &$term) {
            $term->depth = (isset($terms_sorted[$term->term_id]) ? $terms_sorted[$term->term_id] : 0);
        }
        if( isset($term) ) {
            unset($term);
        }
        if( is_array($array_sort) && is_array($terms) && count($array_sort) == count($terms) ) {
            array_multisort($array_sort, SORT_ASC, SORT_NUMERIC, $terms);
        } else {
            BeRocket_error_notices::add_plugin_error(1, 'Hierarchical sort error', array(
                'error'         => '$array_sort != $terms.get_terms -> hierarchical_sort',
                'terms'         => $terms,
                'args'          => $args,
                'additional'    => $additional
            ));
        }
        return $terms;
    }
    public static function depth_clear($terms, $args = array(), $additional = array()) {
        $taxonomy_object = get_taxonomy($args['taxonomy']);
        if( empty($additional['hierarchical']) || empty($taxonomy_object->hierarchical) ) {
            return $terms;
        }
        if( ! empty($additional['depth']) && intval($additional['depth']) ) {
            foreach($terms as $i => $term) {
                if( isset($term->depth) && $term->depth >= $additional['depth'] ) {
                    unset($terms[$i]);
                }
            }
        }
        return $terms;
    }
    public static function prepared_data($terms, $args = array(), $additional = array()) {
        if( ! empty($additional['disable_recount']) ) return $terms;
        if( ! isset(self::$prepared_data['wc_query_data']) ) {
            self::$prepared_data['wc_query_data'] = array();
        }
        if( ! empty($additional['force_query']) || ! isset(self::$prepared_data['wc_query_data']['post__in']) || ! isset(self::$prepared_data['wc_query_data']['post__not_in']) ) {
            global $wp_query, $br_wc_query, $br_aapf_wc_footer_widget;

            $post__in = ( isset($wp_query->query_vars['post__in']) ? $wp_query->query_vars['post__in'] : array() );
            if (
                ! empty( $br_wc_query ) and
                ! empty( $br_wc_query->query ) and
                isset( $br_wc_query->query['post__in'] ) and
                is_array( $br_wc_query->query['post__in'] )
            ) {
                $post__in = array_merge( $post__in, $br_wc_query->query[ 'post__in' ] );
            }

            if( empty($post__in) || ! is_array($post__in) || count($post__in) == 0 ) {
                $post__in = false;
            }
            $post__not_in = ( isset($wp_query->query_vars['post__not_in']) ? $wp_query->query_vars['post__not_in'] : array() );
            if( empty($post__not_in) || ! is_array($post__not_in) || count($post__not_in) == 0 ) {
                $post__not_in = false;
            }
            global $braapf_not_filtered_data;
            if( isset($braapf_not_filtered_data['post__not_in']) ) {
                $post__not_in = $braapf_not_filtered_data['post__not_in'];
            }
            self::$prepared_data['wc_query_data']['post__in'] = apply_filters('berocket_aapf_get_attribute_values_post__in_outside', $post__in);
            self::$prepared_data['wc_query_data']['post__not_in'] = apply_filters('berocket_aapf_get_attribute_values_post__not_in_outside', $post__not_in);
        }
        return $terms;
    }
    public static function show_all_values($terms, $args = array(), $additional = array()) {
        if( ! empty($additional['disable_recount']) ) return $terms;
        $post__in = berocket_isset(self::$prepared_data['wc_query_data']['post__in'], false);
        $post__not_in = berocket_isset(self::$prepared_data['wc_query_data']['post__not_in'], false);
        $terms = apply_filters('berocket_aapf_recount_terms_apply', $terms, array(
            'taxonomy'              => $args['taxonomy'],
            'operator'              => $additional['operator'],
            'use_filters'           => FALSE,
            'tax_query'             => $additional['recount_tax_query'],
            'post__not_in'          => $post__not_in,
            'post__in'              => $post__in,
            'additional_tax_query'  => $additional['additional_tax_query'],
            'args'                  => $args,
            'additional'            => $additional
        ));
        if( empty($terms) || ! is_array($terms) ) {
            $terms = array();
        }
        return $terms;
    }
    public static function hide_empty($terms, $args = array(), $additional = array()) {
        if( ! empty($additional['disable_recount']) || ! empty($additional['disable_hide_empty']) ) return $terms;
        foreach($terms as $term_id => $term) {
            if( $term->count == 0 ) {
                unset($terms[$term_id]);
            }
        }
        return $terms;
    }
    public static function recount_products($terms, $args = array(), $additional = array()) {
        if( ! empty($additional['disable_recount']) ) return $terms;
        $post__in = self::$prepared_data['wc_query_data']['post__in'];
        $post__not_in = self::$prepared_data['wc_query_data']['post__not_in'];
        $terms = apply_filters('berocket_aapf_recount_terms_apply', $terms, array(
            'taxonomy'              => $args['taxonomy'],
            'operator'              => $additional['operator'],
            'use_filters'           => TRUE,
            'tax_query'             => $additional['recount_tax_query'],
            'post__not_in'          => $post__not_in,
            'post__in'              => $post__in,
            'additional_tax_query'  => $additional['additional_tax_query'],
            'args'                  => $args,
            'additional'            => $additional
        ));
        return $terms;
    }
    public static function get_cache_option($name) {
        if( ! isset(self::$cached_values[$name]) ) {
            self::$cached_values[$name] = get_option($name);
        }
        return (empty(self::$cached_values[$name]) ? false : self::$cached_values[$name]);
    }
    public static function save_cache() {
        foreach(self::$cached_values as $name => $values) {
            update_option($name, $values, false);
        }
    }
    public static function get_cache($name, $md5_cache, $taxonomy) {
        return false;
        $value = false;
        $name = apply_filters('br_aapf_md5_cache_text', 'berocket_aapf_cache_' . $name);
        $md5_cache = apply_filters('BRaapf_cache_check_md5', $md5_cache, 'br_generate_child_relation', $taxonomy);
        $newmd5 = self::get_md5_taxonomy($taxonomy);
        $values = self::get_cache_option($name);
        if( ! empty($values) && ! empty($values['values']) && berocket_isset($values['time']) > (time() - self::$cache_time) ) {
            $values_md5 = $values['values'];
            if( isset($values_md5[$md5_cache]) && berocket_isset($values_md5[$md5_cache]['md5']) == $newmd5 ) {
                $value = $values_md5[$md5_cache]['value'];
            }
        }
        return $value;
    }
    public static function set_cache($name, $value, $md5_cache, $taxonomy) {
        return false;
        $name = apply_filters('br_aapf_md5_cache_text', 'berocket_aapf_cache_' . $name);
        $md5_cache = apply_filters('BRaapf_cache_check_md5', $md5_cache, 'br_generate_child_relation', $taxonomy);
        $newmd5 = self::get_md5_taxonomy($taxonomy);
        $values = self::get_cache_option($name);
        if( empty($values) || berocket_isset($values['time']) <= (time() - self::$cache_time) ) {
            $values = array(
                'time'  => time(),
                'values'=> array()
            );
        }
        $values['values'][$md5_cache] = array(
            'value' => $value,
            'md5'   => $newmd5
        );
        self::$cached_values[$name] = $values;
        add_action('wp_footer', array(__CLASS__, 'save_cache'), 999999);
        return $values;
    }
    public static function get_md5_taxonomy($taxonomy) {
        if( ! isset(self::$md5_taxonomies[$taxonomy]) ) {
            global $wpdb;
			$wpdb->query("SET SESSION group_concat_max_len = 1000000");
            $newmd5 = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count)) FROM {$wpdb->terms} AS t 
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id 
                    WHERE tt.taxonomy IN (%s)",
                    $taxonomy
                )
            );
            self::$md5_taxonomies[$taxonomy] = $newmd5;
        }
        return self::$md5_taxonomies[$taxonomy];
    }
    public static function get_page_text() {
        global $wp_query;
        $text = '';
        $object_id = $wp_query->get_queried_object_id();
        if( $object_id == 0 ) {
            if( is_shop() ) {
                $text = 'shop';
            } elseif( is_home() ) {
                $text = 'home';
            } else {
                $text = 'other';
            }
        } else {
            if ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) {
                $text = 'taxonomy' . $object_id;
            } elseif( $wp_query->is_post_type_archive ) {
                $text = 'archive' . $object_id;
            } elseif( $wp_query->is_posts_page || ($wp_query->is_singular && ! empty( $wp_query->post )) ) {
                $text = 'post' . $object_id;
            } else {
                $text = 'other' . $object_id;
            }
        }
        return $text;
    }
}
if( ! function_exists('berocket_aapf_get_terms') ) {
    function berocket_aapf_get_terms($args = array(), $additional = array()) {
        return BeRocket_AAPF_get_terms::get_terms($args, $additional);
    }
}
