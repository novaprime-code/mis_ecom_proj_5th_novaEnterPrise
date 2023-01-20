<?php
define('BEROCKETAAPF', 'BeRocket_AAPF_Widget');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
class BeRocket_AAPF_Widget_functions {
    function __construct() {
        add_filter('berocket_query_var_title_before_widget', array($this, 'apply_price_slider'), 10, 5);
        add_filter('berocket_aapf_is_filtered_page_check', array($this, 'is_filtered_page_check'), 10, 1);
        add_filter('braapf_generate_taxonomy_name_for_select', array($this, 'correct_taxonomy'), 10, 2);
    }
    function is_filtered_page_check($filtered) {
        if( ! empty($_GET['s']) ) {
            $filtered = true;
        }
        return $filtered;
    }
    function correct_taxonomy($args, $br_product_filter) {
        if( ! empty($br_product_filter) && ! empty($br_product_filter['filter_type']) ) {
            switch($br_product_filter['filter_type']) {
                case 'tag':
                    $args['taxonomy'] = 'product_tag';
                    break;
                case '_rating':
                    $args['taxonomy'] = 'product_visibility';
                    $args['slug']     = array('rated-1', 'rated-2', 'rated-3', 'rated-4', 'rated-5');
                    break;
                case 'tag':
                    $args['taxonomy'] = 'product_tag';
                    break;
            }
        }
        return $args;
    }
    public static function br_widget_ajax_set() {
        if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && br_get_woocommerce_version() >= 2.1 ) {
            add_action( 'wp_ajax_berocket_aapf_color_listener', array( __CLASS__, 'color_listener' ) );
            add_action( 'wp_ajax_br_include_exclude_list', array( __CLASS__, 'ajax_include_exclude_list' ) );
        }
    }
    
    public static function apply_price_slider($set_query_var_title, $type, $instance, $args = false, $terms = false) {
        if($args === false || $terms === false) {
            return $set_query_var_title;
        } 
        extract($instance);
        extract($args);
        $slider_with_string = false;
        $stringed_is_numeric = true;
        $slider_step = 1;

        if ( $filter_type == 'attribute' && $attribute == 'price' && $type == 'slider' ) {
            $min = $max   = false;
            $main_class   = 'slider';
            $slider_class = 'berocket_filter_slider';

            wp_localize_script(
                'berocket_aapf_widget-script',
                'br_price_text',
                array(
                    'before'  => (isset($text_before_price) ? $text_before_price : ''),
                    'after'   => (isset($text_after_price) ? $text_after_price : ''),
                )
            );
            if ( ! empty($price_values) ) {
                $price_range = explode( ",", $price_values );
                if( is_array($price_range) && count($price_range) ) {
                    foreach($price_range as &$price_range_value) {
                        $price_range_value = floatval(trim($price_range_value));
                    }
                    sort($price_range, SORT_NUMERIC);
                    $price_values = implode(',', $price_range);
                }
            } elseif( (! empty($min_price) || $min_price == '0') && ! empty($max_price) ) {
                $price_range = array($min_price, $max_price);
            } else {
                $price_range_data = BeRocket_AAPF_Widget_functions::get_price_ranges();
                if( ! empty($price_range_data) && isset($price_range_data['min_price']) && isset($price_range_data['max_price']) ) {
                    $price_range = array($price_range_data['min_price'], $price_range_data['max_price']);
                }
            }
            if ( ! empty($price_values) ) {
                $all_terms_name = $price_range;
                $all_terms_slug = $price_range;
                $stringed_is_numeric = true;
                $min = 0;
                $max = count( $all_terms_name ) - 1;
                $slider_with_string = true;
                $terms = array();
                foreach( $all_terms_name as $term_slug ) {
                    $terms[] = (object)array(
                        'term_id'  => $term_slug,
                        'slug'     => $term_slug,
                        'value'    => $term_slug,
                        'name'     => $term_slug,
                        'count'    => 1,
                        'taxonomy' => 'price',
                        'min'      => $min,
                        'max'      => $max,
                        'step'     => '1',
                    );
                }
                $set_query_var_title['terms'] = $terms;
                $set_query_var_title['slider_display_data'] = 'arr_attr_price';
            } else {
                if( $price_range ) {
                    foreach ( $price_range as $price ) {
                        if ( $min === false or $min > (int) $price ) {
                            $min = $price;
                        }
                        if ( $max === false or $max < (int) $price ) {
                            $max = $price;
                        }
                    }
                }
                if( $use_min_price ) {
                    $min = $min_price;
                }
                if ( $use_max_price ) {
                    $max = $max_price;
                }
            }
            $slider_value1 = $min;
            $slider_value2 = $max;
            $id = 'br_price';
            $slider_class .= ' berocket_filter_price_slider';
            $main_class .= ' price';

            $min = floor( $min );
            $max = ceil( $max );

            $wpml_id = preg_replace( '#^pa_#', '', $id );
            $wpml_id = 'pa_'.berocket_wpml_attribute_translate($wpml_id);
            $set_query_var_title['slider_value1'] = $slider_value1;
            $set_query_var_title['slider_value2'] = $slider_value2;
            $set_query_var_title['filter_slider_id'] = $wpml_id;
            $set_query_var_title['main_class'] = $main_class;
            $set_query_var_title['slider_class'] = $slider_class;
            $set_query_var_title['min'] = $min;
            $set_query_var_title['max'] = $max;
            $set_query_var_title['step'] = $slider_step;
            $set_query_var_title['slider_with_string'] = $slider_with_string;
            $set_query_var_title['all_terms_name'] = ( empty($all_terms_name) ? null : $all_terms_name );
            $set_query_var_title['all_terms_slug'] = ( empty($all_terms_slug) ? null : $all_terms_slug );
            $set_query_var_title['text_before_price'] = (isset($text_before_price) ? $text_before_price : null);
            $set_query_var_title['text_after_price'] = (isset($text_after_price) ? $text_after_price : null);
            $set_query_var_title['enable_slider_inputs'] = (isset($enable_slider_inputs) ? $enable_slider_inputs : null);
            if( ! empty($number_style) ) {
                $set_query_var_title['number_style'] = array(
                    ( empty($number_style_thousand_separate) ? '' : $number_style_thousand_separate ), 
                    ( empty($number_style_decimal_separate) ? '' : $number_style_decimal_separate ), 
                    ( empty($number_style_decimal_number) ? '' : $number_style_decimal_number )
                );
            } else {
                $set_query_var_title['number_style'] = '';
            }
        }
        return $set_query_var_title;
    }

    public static function color_listener() {
        $br_product_filter = (empty($_POST['br_product_filter']) ? array() : $_POST['br_product_filter']);
        echo self::color_image_view($br_product_filter, $_POST['type'], true);
        wp_die();
    }
    
    public static function ajax_include_exclude_list() {
        $br_product_filter = (empty($_POST['br_product_filter']) ? array() : $_POST['br_product_filter']);
        echo self::include_exclude_view($br_product_filter);
        wp_die();
    }

    public static function get_product_categories( $current_product_cat = '', $parent = 0, $data = array(), $depth = 0, $max_count = 9, $follow_hierarchy = false ) {
        return br_get_sub_categories( $parent, 'id', array( 'return' => 'hierarchy_objects', 'max_depth' => $max_count ) );
    }

    public static function get_price_ranges($price_ranges = FALSE) {
        global $wpdb;
        $options = BeRocket_AAPF::get_aapf_option();
        $use_filters = braapf_filters_must_be_recounted();
        $taxonomy_data = BeRocket_AAPF_faster_attribute_recount::get_query_for_calculate(array(
            'use_filters' => $use_filters,
            'taxonomy_remove' => 'bapf_price'
        ));
        $query = $taxonomy_data['query'];
        $query['select']['elements'] = array(
            'min_price' => "MIN(cast(FLOOR(bapf_custom_price.min_price) as decimal)) as min_price",
            'min_float' => "MIN(bapf_custom_price.min_price) as min_float",
            'max_price' => "MAX(cast(CEIL(bapf_custom_price.max_price) as decimal)) as max_price",
            'max_float' => "MAX(bapf_custom_price.max_price) as max_float"
        );
        if( ! empty($options['filter_price_variation']) && $price_ranges !== false && is_array($price_ranges) ) {
            $query['join']['product_variation'] = "INNER JOIN {$wpdb->posts} as bapf_variation_posts ON {$wpdb->posts}.ID = bapf_variation_posts.post_parent OR {$wpdb->posts}.ID = bapf_variation_posts.ID";
            $query['join']['product_meta_lookup'] = " INNER JOIN {$wpdb->wc_product_meta_lookup} as bapf_custom_price ON bapf_variation_posts.ID = bapf_custom_price.product_id ";
        } else {
            $query['join']['product_meta_lookup'] = " INNER JOIN {$wpdb->wc_product_meta_lookup} as bapf_custom_price ON $wpdb->posts.ID = bapf_custom_price.product_id";
        }
        if( $price_ranges !== false && is_array($price_ranges) ) {
            $case_values = array();
            foreach($price_ranges as $price_range) {
                $from = isset($price_range['real_from']) ? $price_range['real_from'] : $price_range['from'];
                $to = isset($price_range['real_to']) ? $price_range['real_to'] : $price_range['to'];
                $case_values[] = "WHEN bapf_custom_price.min_price >= {$from} and bapf_custom_price.max_price <= {$to} THEN '{$price_range['from']}-{$price_range['to']}'";
            }
            $query['select']['elements']['price_range'] = "CASE ". implode(" ", $case_values). " END price_range";
            $query['select']['elements']['price_range_count'] = "count(distinct($wpdb->posts.ID)) as product_count";
            $query['group'] = 'GROUP BY price_range';
        }
        $query             = apply_filters('berocket_aapf_recount_price_query', $query, $taxonomy_data, $price_ranges);
        $query['select']['elements']= implode(', ', $query['select']['elements']);
        $query['select']   = implode(' ', $query['select']);
        $query['join']     = implode(' ', $query['join']);
        $query['where']    = implode(' ', $query['where']);
        $query['join']    .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} as wc_product_meta_lookup ON {$wpdb->posts}.ID = wc_product_meta_lookup.product_id ";
        $query             = apply_filters('woocommerce_get_filtered_term_product_counts_query', $query);
        if( $use_filters ) {
            $query = apply_filters( 'berocket_posts_clauses_recount', $query );
        }
        $query_imploded    = implode( ' ', $query );
        $use_price_cache = apply_filters('berocket_recount_price_cache_use', false);
        if($use_price_cache) {
            $result        = br_get_cache(apply_filters('berocket_recount_cache_key', md5(json_encode($query_imploded)), $taxonomy_data), 'berocket_recount');
        }
        if( empty($result) ) {
            $result        = $wpdb->get_results( $query_imploded );
            if($use_price_cache) {
                br_set_cache(md5(json_encode($query_imploded)), $result, 'berocket_recount', DAY_IN_SECONDS);
            }
        }
        BeRocket_AAPF_faster_attribute_recount::restore_url_data_after_recount();
        if( empty($result) || count($result) == 0 ) {
            return FALSE;
        } else {
            if( $price_ranges === false ) {
                return (array)$result[0];
            } else {
                return (array)$result;
            }
        }
    }
    public static function get_attribute_values( $taxonomy = '', $order_by = 'id', $hide_empty = false, $count_filtering = true, $input_terms = FALSE, $product_cat = FALSE, $operator = 'OR' ) {
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', BeRocket_AAPF::get_aapf_option() );
        if ( ! $taxonomy || $taxonomy == 'price' ) return array();
        if( $taxonomy == '_rating' ) $taxonomy = 'product_visibility';
        $terms = (empty($input_terms) ? FALSE : $input_terms);

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
        if( $hide_empty ) {
            $terms = apply_filters('berocket_aapf_recount_terms_apply', $terms, array(
                'taxonomy' => $taxonomy,
                'operator' => $operator,
                'use_filters' => FALSE,
                'post__not_in' => apply_filters('berocket_aapf_get_attribute_values_post__not_in_outside', $post__not_in),
                'post__in'     => apply_filters('berocket_aapf_get_attribute_values_post__in_outside', $post__in)
            ));
        } elseif(empty($terms)) {
            $terms = get_terms( array(
                'taxonomy'     => $taxonomy,
                'hide_empty'   => true,
                'hierarchical' => true,
                'post__not_in' => apply_filters('berocket_aapf_get_attribute_values_post__not_in_outside', false),
                'post__in'     => apply_filters('berocket_aapf_get_attribute_values_post__in_outside', false)
            ) );
        }
        if( empty($terms) || ! is_array($terms) ) {
            $terms = array();
        }
        if( $hide_empty ) {
            foreach($terms as $term_id => $term) {
                if( $term->count == 0 ) {
                    unset($terms[$term_id]);
                }
            }
        }
        if ( 
            (   ! $hide_empty 
                || apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'get_filter_args', $wp_query ) 
                || ( ! empty($br_options['out_of_stock_variable_reload']) && ! empty($br_options['out_of_stock_variable']) )
                || is_filtered()
            ) && $count_filtering 
        ) {
            $terms = apply_filters('berocket_aapf_recount_terms_apply', $terms, array(
                'taxonomy' => $taxonomy,
                'operator' => $operator,
                'use_filters' => TRUE,
                'post__not_in' => apply_filters('berocket_aapf_get_attribute_values_post__not_in_outside', $post__not_in),
                'post__in'     => apply_filters('berocket_aapf_get_attribute_values_post__in_outside', $post__in)
            ));
        }
        return $terms;
    }

    public static function sort_child_parent_hierarchy($terms) {
        $terms_sort = array();
        $new_terms = $terms;
        $terms = array_reverse($terms);
        foreach($terms as $term_id => $term) {
            if(empty($term->parent)) {
                $terms_sort[] = $term->term_id;
                unset($terms[$term_id]);
            }
        }
        $length = 0;
        while(count($terms) && $length < 30) {
            foreach($terms as $term_id => $term) {
                $term_i = array_search($term->parent, $terms_sort);
                if( $term_i !== FALSE ) {
                    array_splice($terms_sort, $term_i, 0, array($term->term_id));
                    unset($terms[$term_id]);
                }
            }
            $length++;
        }
        if( count($terms) ) {
            foreach($terms as $term_id => $term) {
                $terms_sort[] = $term->term_id;
            }
        }
        $sort_array = array();
        foreach($new_terms as $terms) {
            $sort_array[] = array_search($terms->term_id, $terms_sort);
        }
        return $sort_array;
    }

    public static function sort_terms( &$terms, $sort_data ) {
        $sort_array = array();
        if ( ! empty($terms) && is_array( $terms ) && count( $terms ) ) {
            if ( ! empty($sort_data['attribute']) and in_array($sort_data['attribute'], array('product_cat', 'berocket_brand')) and ! empty($sort_data['order_values_by']) and $sort_data['order_values_by'] == 'Default' ) {
                foreach ( $terms as $term ) {
                    $element_of_sort = get_term_meta(  $term->term_id,  'order',  true );
                    if( is_array($element_of_sort) || $element_of_sort === false ) {
                        $sort_array[] = 0;
                    } else {
                        $sort_array[] = $element_of_sort;
                    }
                    if ( ! empty($term->child) ) {
                        self::sort_terms( $term->child, $sort_data );
                    }
                }
                if( BeRocket_AAPF::$debug_mode ) {
                    BeRocket_AAPF::$error_log[$sort_data['attribute'].'_sort'] = array('array' => $sort_array, 'sort' => $terms, 'data' => $sort_data );
                }
                @ array_multisort( $sort_array, $sort_data['order_values_type'], SORT_NUMERIC, $terms );
            } elseif ( ! empty($sort_data['wc_order_by']) or ! empty($sort_data['order_values_by']) ) {
                if ( ! empty($sort_data['order_values_by']) and $sort_data['order_values_by'] == 'Numeric' ) {
                    foreach ( $terms as $term ) {
                        $sort_array[] = (float)preg_replace('/\s+/', '', str_replace(',', '.', $term->name));
                        if ( ! empty($term->child) ) {
                            self::sort_terms( $term->child, $sort_data );
                        }
                    }
                    @ array_multisort( $sort_array, $sort_data['order_values_type'], SORT_NUMERIC, $terms );
                } else {
                    $get_terms_args = array( 'hide_empty' => '0', 'fields' => 'ids' );

                    if ( ! empty($sort_data['order_values_by']) and $sort_data['order_values_by'] == 'Alpha' ) {
                        $orderby = 'name';
                    } else {
                        $orderby = 'name';
                        foreach($terms as $term_sort) {
                            $orderby = wc_attribute_orderby( $term_sort->taxonomy );
                            break;
                        }
                    }

                    switch ( $orderby ) {
                        case 'name':
                            $get_terms_args['orderby']    = 'name';
                            $get_terms_args['menu_order'] = false;
                            break;
                        case 'id':
                            $get_terms_args['orderby']    = 'id';
                            $get_terms_args['order']      = 'ASC';
                            $get_terms_args['menu_order'] = false;
                            break;
                        case 'menu_order':
                            $get_terms_args['menu_order'] = 'ASC';
                            break;
                        default:
                            break;
                    }

                    if( count($terms) ) {
                        $terms_first = reset($terms);
                        $get_terms_args['taxonomy'] = $terms_first->taxonomy;
                        $terms2 = berocket_aapf_get_terms( $get_terms_args );
                        foreach ( $terms as $term ) {
                            $sort_array[] = array_search($term->term_id, $terms2);
                            if ( ! empty($term->child) ) {
                                self::sort_terms( $term->child, $sort_data );
                            }
                        }
                        @ array_multisort( $sort_array, $sort_data['order_values_type'], SORT_NUMERIC, $terms );
                    }
                }
                $sort_array = self::sort_child_parent_hierarchy($terms);
                @ array_multisort( $sort_array, SORT_DESC, SORT_NUMERIC, $terms );
            }
        }
    }

    public static function set_terms_on_same_level( $terms, $return_array = array(), $add_spaces = true ) {
        if ( ! empty($terms) && is_array( $terms ) && count( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( $add_spaces ) {
                    for ( $i = 0; $i < $term->depth; $i++ ) {
                        $term->name = "&nbsp;&nbsp;" . $term->name;
                    }
                }

                if( ! empty($term->child) ) {
                    $child = $term->child;
                    unset( $term->child );
                }

                $return_array[] = $term;

                if ( ! empty($child) ) {
                    $return_array = self::set_terms_on_same_level( $child, $return_array, $add_spaces );
                    unset($child);
                }
            }
        } else {
            $return_array = $terms;
        }
        return $return_array;
    }

    public static function woocommerce_hide_out_of_stock_items(){
        $hide = get_option( 'woocommerce_hide_out_of_stock_items', null );

        if ( is_array( $hide ) ) {
            $hide = array_map( 'stripslashes', $hide );
        } elseif ( ! is_null( $hide ) ) {
            $hide = stripslashes( $hide );
        }

        return apply_filters( 'berocket_aapf_hide_out_of_stock_items', $hide );
    }
    public static function color_image_view($br_filter, $type, $load_script = false) {
        $terms = self::get_terms_for_filter($br_filter);
        $set_query_var_color = array();
        $set_query_var_color['terms'] = $terms;
        $set_query_var_color['type'] = $type;
        $set_query_var_color['load_script'] = $load_script;
        set_query_var( 'berocket_query_var_color', $set_query_var_color );
        ob_start();
        br_get_template_part( 'color_ajax' );
        return ob_get_clean();
    }
    public static function color_image_save($br_filter, $type, $color_values) {
        if( isset( $color_values ) ) {
            if ( current_user_can( 'manage_woocommerce' ) ) {
                if( apply_filters('bapf_widget_func_color_listener_save', true, $br_filter, $type, $color_values) ) {
                    foreach( $color_values as $key => $value ) {
                        if( $type == 'color' ) {
                            foreach($value as $term_key => $term_val) {
                                if( !empty($term_val) ) {
                                    update_metadata( 'berocket_term', $term_key, $key, $term_val );
                                } else {
                                    delete_metadata( 'berocket_term', $term_key, $key );
                                }
                            }
                        } else {
                            update_metadata( 'berocket_term', $key, $type, $value );
                        }
                    }
                }
            }
        }
    }
    public static function include_exclude_view($br_filter, $selected = array()) {
        $terms = self::get_terms_for_filter($br_filter);
        $set_query_var_exclude_list = array();
        $set_query_var_exclude_list['terms'] = $terms;
        $set_query_var_exclude_list['selected'] = $selected;
        set_query_var( 'berocket_var_exclude_list', $set_query_var_exclude_list );
        ob_start();
        br_get_template_part( 'include_exclude_list' );
        return ob_get_clean();
    }
    public static function get_terms_for_filter($br_filter) {
        $args = self::get_current_terms_args($br_filter);
        $terms = berocket_aapf_get_terms( $args, array('hierarchical' => true, 'disable_recount' => true, 'disable_hide_empty' => true) );
        return $terms;
    }
    public static function get_current_terms_args($br_filter) {
        $taxonomy = braapf_get_data_taxonomy_from_post($br_filter);
        $args = array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false
        );
        return apply_filters('braapf_generate_taxonomy_name_for_select', $args, $br_filter);
    }
}
new BeRocket_AAPF_Widget_functions();
