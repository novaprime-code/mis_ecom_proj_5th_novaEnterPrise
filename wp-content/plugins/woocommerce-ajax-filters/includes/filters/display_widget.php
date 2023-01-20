<?php

/**
 * BeRocket_AAPF_Widget - main filter widget. One filter for any needs
 */
class BeRocket_AAPF_Widget {

    public static $defaults = array(
        'br_wp_footer'                  => false,
        'widget_type'                   => '',
        'title'                         => '',
        'filter_type'                   => 'attribute',
        'attribute'                     => '',
        'custom_taxonomy'               => 'product_cat',
        'type'                          => '',
        'select_first_element_text'     => '',
        'operator'                      => 'OR',
        'order_values_by'               => '',
        'order_values_type'             => '',
        'text_before_price'             => '',
        'text_after_price'              => '',
        'enable_slider_inputs'          => '',
        'parent_product_cat'            => '',
        'depth_count'                   => '0',
        'widget_collapse'               => '',
        'widget_is_hide'                => '0',
        'show_product_count_per_attr'   => '0',
        'hide_child_attributes'         => '0',
        'use_value_with_color'          => '0',
        'values_per_row'                => '',
        'icon_before_title'             => '',
        'icon_after_title'              => '',
        'icon_before_value'             => '',
        'icon_after_value'              => '',
        'price_values'                  => '',
        'description'                   => '',
        'css_class'                     => '',
        'use_min_price'                 => '',
        'min_price'                     => '',
        'use_max_price'                 => '',
        'max_price'                     => '',
        'height'                        => '',
        'scroll_theme'                  => 'dark',
        'selected_area_show'            => '0',
        'hide_selected_arrow'           => '0',
        'selected_is_hide'              => '0',
        'slider_default'                => '0',
        'number_style'                  => '0',
        'number_style_thousand_separate'=> '',
        'number_style_decimal_separate' => '.',
        'number_style_decimal_number'   => '2',
        'is_hide_mobile'                => '0',
        'user_can_see'                  => '',
        'cat_propagation'               => '0',
        'product_cat'                   => '',
        'parent_product_cat_current'    => '0',
        'attribute_count'               => '',
        'show_page'                     => array( 'shop', 'product_cat', 'product_tag', 'product_taxonomy' ),
        'cat_value_limit'               => '0',
        'child_parent'                  => '',
        'child_parent_depth'            => '1',
        'child_parent_no_values'        => '',
        'child_parent_previous'         => '',
        'child_parent_no_products'      => '',
        'child_onew_count'              => '1',
        'child_onew_childs'             => array(
            1                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            2                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            3                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            4                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            5                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            6                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            7                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            8                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            9                               => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
            10                              => array('title' => '', 'no_product' => '', 'no_values' => '', 'previous' => ''),
        ),
        'search_box_link_type'          => 'shop_page',
        'search_box_url'                => '',
        'search_box_category'           => '',
        'search_box_count'              => '1',
        'search_box_attributes'             => array(
            1                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            2                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            3                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            4                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            5                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            6                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            7                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            8                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            9                               => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
            10                              => array('type' => 'attribute', 'attribute' => '', 'custom_taxonomy' => '', 'title' => '', 'visual_type' => 'select'),
        ),
        'search_box_style'              => array(
            'position'                      => 'vertical',
            'search_position'               => 'after',
            'search_text'                   => 'Search',
            'background'                    => 'bbbbff',
            'back_opacity'                  => '0',
            'button_background'             => '888800',
            'button_background_over'        => 'aaaa00',
            'text_color'                    => '000000',
            'text_color_over'               => '000000',
        ),
        'ranges'                        => array( 1, 10 ),
        'include_exclude_select'        => '',
        'include_exclude_list'          => array(),
    );

    /**
     * Constructor
     */
    function __construct( $instance, $args = array() ) {
        if( ! empty($args['widget_id']) ) {
            $this->id = $args['widget_id'];
            $this->number = $args['widget_id'];
        }
        if( empty($instance['widget_type']) ) {
            if( BeRocket_AAPF::$user_can_manage ) {
                echo '<div>', __('Filter do not have <strong>Widget type</strong>', 'BeRocket_AJAX_domain'), '</div>';
            }
            return false;
        }
        if( empty($this->number) || $this->number == -1 ) {
            global $berocket_aapf_shortcode_id;
            if( empty($berocket_aapf_shortcode_id) ) {
                $berocket_aapf_shortcode_id = 1;
            } else {
                $berocket_aapf_shortcode_id++;
            }
            $this->id = 'berocket_aapf_widget-s'.$berocket_aapf_shortcode_id;
            $args['widget_id'] = $this->id;
            $this->number = 's'.$berocket_aapf_shortcode_id;
        }
        
        if ( $instance['filter_type'] == 'price' ) {
            $instance['filter_type'] = 'attribute';
            $instance['attribute'] = 'price';
        }
        global $bapf_unique_id;
        $unique_filter_id = $bapf_unique_id;
        $set_query_var_title = array();
        $set_query_var_main = array();
        $set_query_var_footer = array();
        $filter_type_array = array(
            'attribute' => array(
                'name' => __('Attribute', 'BeRocket_AJAX_domain'),
                'sameas' => 'attribute',
            ),
            'tag' => array(
                'name' => __('Tag', 'BeRocket_AJAX_domain'),
                'sameas' => 'tag',
            ),
            'all_product_cat' => array(
                'name' => __('Product Category', 'BeRocket_AJAX_domain'),
                'sameas' => 'custom_taxonomy',
                'attribute' => 'product_cat',
            ),
        );
        if ( function_exists('wc_get_product_visibility_term_ids') ) {
            $filter_type_array['_rating'] = array(
                'name' => __('Rating', 'BeRocket_AJAX_domain'),
                'sameas' => '_rating',
            );
        }
        $filter_type_array = apply_filters('berocket_filter_filter_type_array', $filter_type_array, $instance);
        if( empty($instance['filter_type']) || ! array_key_exists($instance['filter_type'], $filter_type_array) ) {
            if( $instance['widget_type'] == 'filter' ) {
                return false;
            }
            foreach($filter_type_array as $filter_type_key => $filter_type_val) {
                $instance['filter_type'] = $filter_type_key;
                break;
            }
        }
        if( ! empty($instance['filter_type']) && ! empty($filter_type_array[$instance['filter_type']]) && ! empty($filter_type_array[$instance['filter_type']]['sameas']) ) {
            $sameas = $filter_type_array[$instance['filter_type']];
            $instance['filter_type'] = $sameas['sameas'];
            if( ! empty($sameas['attribute']) ) {
                if( $sameas['sameas'] == 'custom_taxonomy' ) {
                    $instance['custom_taxonomy'] = $sameas['attribute'];
                } elseif( $sameas['sameas'] == 'attribute' ) {
                    $instance['attribute'] = $sameas['attribute'];
                }
            }
        }
        //CHECK WIDGET TYPES
        list($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr) = berocket_aapf_get_filter_types();
        $select_options_variants = array();
        if ( $instance['filter_type'] == 'tag' ) {
            $select_options_variants = $berocket_admin_filter_types['tag'];
        } else if ( $instance['filter_type'] == 'product_cat' || ( $instance['filter_type'] == 'custom_taxonomy' && ( $instance['custom_taxonomy'] == 'product_tag' || $instance['custom_taxonomy'] == 'product_cat' ) ) ) {
            $select_options_variants = $berocket_admin_filter_types['product_cat'];
        } else if ( $instance['filter_type'] == '_sale' || $instance['filter_type'] == '_stock_status' || $instance['filter_type'] == '_rating' ) {
            $select_options_variants = $berocket_admin_filter_types['sale'];
        } else if ( $instance['filter_type'] == 'custom_taxonomy' ) {
            $select_options_variants = $berocket_admin_filter_types['custom_taxonomy'];
        } else if ( $instance['filter_type'] == 'attribute' ) {
            if ( $instance['attribute'] == 'price' ) {
                $select_options_variants = $berocket_admin_filter_types['price'];
            } else {
                $select_options_variants = $berocket_admin_filter_types['attribute'];
            }
        } else if ( $instance['filter_type'] == 'filter_by' ) {
            $select_options_variants = $berocket_admin_filter_types['filter_by'];
        }
        $selected = false;
        $first = false;
        foreach($select_options_variants as $select_options_variant) {
            if( ! empty($berocket_admin_filter_types_by_attr[$select_options_variant]) ) {
                if( $instance['type'] == $berocket_admin_filter_types_by_attr[$select_options_variant]['value'] ) {
                    $selected = true;
                    break;
                }
                if( $first === false ) {
                    $first = $berocket_admin_filter_types_by_attr[$select_options_variant]['value'];
                }
            }
        }
        if( ! $selected ) {
            $instance['type'] = $first;
        }
        $widget_type_array = apply_filters( 'berocket_widget_widget_type_array', apply_filters( 'berocket_aapf_display_filter_type_list', array(
            'filter' => __('Filter', 'BeRocket_AJAX_domain'),
        ) ) );
        if( ! array_key_exists($instance['widget_type'], $widget_type_array) ) {
            foreach($widget_type_array as $widget_type_id => $widget_type_name) {
                $instance['widget_type'] = $widget_type_id;
                break;
            }
        }
        $instance['title'] = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id );
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        $default_language = apply_filters( 'wpml_default_language', NULL );

        global $wp_query, $wp_the_query, $wp, $sitepress, $br_wc_query;
        if( ! isset( BeRocket_AAPF::$error_log['6_widgets'] ) )
        {
            BeRocket_AAPF::$error_log['6_widgets'] = array();
        } 
        $widget_error_log             = array();
        $instance = array_merge( self::$defaults, $instance );
        foreach(array('operator') as $option_set) {
            if( empty($instance[$option_set]) ) {
                $instance[$option_set] = self::$defaults[$option_set];
            }
        }
        $instance = apply_filters('brapf_filter_instance', $instance, $args, $set_query_var_title);
        $args = apply_filters('brapf_filter_args', $args, $instance, $set_query_var_title);
        if( ! empty($instance['style']) ) {
            $style = $instance['style'];
            $all_styles = get_option('BeRocket_AAPF_getall_Template_Styles');
            if( is_array($all_styles) && isset($all_styles[$style]) ) {
                //RUN NEEDED STYLES
                if( file_exists($all_styles[$style]['file']) ) {
                    include_once($all_styles[$style]['file']);
                }
                $styles = apply_filters('BeRocket_AAPF_getall_Template_Styles', array(), array('file' => $all_styles[$style]['file'], 'slug' => $all_styles[$style]['slug']));
                $template = '';
                foreach($styles as $style_get) {
                    $style_get['this']->activate();
                    $template = $style_get['template'];
                    $instance['new_style'] = $set_query_var_title['new_style'] = $style_get;
                }
                $instance['type'] = $instance['new_template'] = $set_query_var_title['new_template'] = $template;
            }
        } else {
            if( BeRocket_AAPF::$user_can_manage ) {
                echo '<div>', __('Filter do not have <strong>Style</strong>', 'BeRocket_AJAX_domain'), '</div>';
            }
            return false;
        }

        if( BeRocket_AAPF::$debug_mode ) {
            $widget_error_log['wp_query'] = $wp_query;
            $widget_error_log['args']     = $args;
            $widget_error_log['instance'] = $instance;
        }

        if( ! empty($instance['child_parent']) && in_array($instance['child_parent'], array('child', 'parent')) ) {
            $br_options['show_all_values'] = true;
        }

        if ( isset ( $br_wc_query ) ) {
            if( ! is_a($br_wc_query, 'WP_Query') ) {
                $br_wc_query = new WP_Query( $br_wc_query );
            }
            if( class_exists('WC_Query') &&  method_exists('WC_Query', 'product_query') && method_exists('WC_Query', 'get_main_query') ) {
                $wc_query = wc()->query->get_main_query();
            }
            $old_query     = $wp_query;
            $old_the_query = $wp_the_query;
            $wp_query      = $br_wc_query;
            $wp_the_query  = $br_wc_query;
            if( class_exists('WC_Query') &&  method_exists('WC_Query', 'product_query') && method_exists('WC_Query', 'get_main_query') ) {
                $wp_query = apply_filters('braapf_wp_query_widget_start', $wp_query, $old_query, $br_options);
                wc()->query->product_query($wp_query);
            }
        }
        if( empty($set_query_var_title['new_template']) ) {
            $widget_error_log['return'] = 'Template not selected';
            $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
            return '';
        }

        if ( empty($instance['br_wp_footer']) ) {
            global $br_widget_ids;
            if ( ! isset( $br_widget_ids ) ) {
                $br_widget_ids = array();
            }
            $br_widget_ids[] = array('instance' => $instance, 'args' => $args);
        }

        $text_before_price = berocket_isset($instance['text_before_price']);
        $text_after_price = berocket_isset($instance['text_after_price']);
        $text_before_price = apply_filters('aapf_widget_text_before_price', ( isset($text_before_price) ? $text_before_price : '' ) );
        $text_after_price = apply_filters('aapf_widget_text_after_price', ( isset($text_after_price) ? $text_after_price : '' ) );
        if( ! empty($text_before_price) || ! empty($text_after_price) ) {
            $cur_symbol = get_woocommerce_currency_symbol();
            $cur_slug = get_woocommerce_currency();
            if( !empty($text_before_price) ) {
                $text_before_price = str_replace(array('%cur_symbol%', '%cur_slug%'), array($cur_symbol, $cur_slug), $text_before_price);
            }
            if( !empty($text_after_price) ) {
                $text_after_price = str_replace(array('%cur_symbol%', '%cur_slug%'), array($cur_symbol, $cur_slug), $text_after_price);
            }
            wp_localize_script(
                'berocket_aapf_widget-script',
                'br_price_text',
                array(
                    'before'  => (isset($text_before_price) ? $text_before_price : ''),
                    'after'   => (isset($text_after_price) ? $text_after_price : ''),
                )
            );
        }
        $instance['text_before_price'] = $text_before_price;
        $instance['text_after_price'] = $text_after_price;
        extract( $args );
        extract( $instance );
        $set_query_var_title['text_before_price']           = $text_before_price;
        $set_query_var_title['text_after_price']            = $text_after_price;
        $set_query_var_title['widget_type']                 = $widget_type;
        $set_query_var_title['unique_filter_id']            = $unique_filter_id;
        $set_query_var_title['description']                 = (isset($description) ? $description : null);
        $set_query_var_title['title']                       = apply_filters( 'berocket_aapf_widget_title', berocket_isset($filter_title) );
        $set_query_var_title['css_class']                   = apply_filters( 'berocket_aapf_widget_css_class', (isset($css_class) ? $css_class : '') );
        $set_query_var_title['icon_before_title']           = (isset($icon_before_title) ? $icon_before_title : null);
        $set_query_var_title['icon_after_title']            = (isset($icon_after_title) ? $icon_after_title : null);
        $set_query_var_title['widget_is_hide']              = ! empty($widget_is_hide);
        $set_query_var_title['widget_collapse']             = berocket_isset($widget_collapse);
        $set_query_var_title['height']                      = berocket_isset($height);
        $set_query_var_title['scroll_theme']                = $scroll_theme;
        $set_query_var_title['reset_hide']                  = berocket_isset($reset_hide);
        $set_query_var_title['selected_area_show']          = ! empty($selected_area_show);
        if( in_array(br_get_value_from_array($set_query_var_title, 'new_template'), array('slider', 'new_slider')) ) {
            $instance['type'] = 'slider';
            $type = 'slider';
        }
        if ( empty($order_values_by) ) {
            $order_values_by = 'Default';
        }

        if ( ! empty($filter_type) && ( in_array($filter_type, array('product_cat', '_stock_status', '_sale', '_rating', 'tag')) ) ) {
            if( $filter_type == 'tag' ) {
                $attribute   = 'product_tag';
            } else {
                $attribute   = $filter_type;
            }
            $filter_type = 'attribute';
        }
        if( apply_filters( 'berocket_aapf_widget_display_custom_filter', false, berocket_isset($widget_type), $instance, $args, $this ) ) {
            $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
            return '';
        }

        if( ! empty($widget_type) && $custom_type_html = apply_filters('berocket_aapf_display_filter_custom_type', '', $widget_type, array('options' => $instance, 'args' => $args, 'set_query_var_title' => $set_query_var_title)) ) {
            if( $custom_type_html !== TRUE ) {
                BeRocket_AAPF::wp_enqueue_script( 'berocket_aapf_widget-script' );
                BeRocket_AAPF::wp_enqueue_style ( 'berocket_aapf_widget-style' );
                echo $custom_type_html;
            }
            $widget_error_log['return'] = $widget_type;
            $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
            return '';
        }

        $woocommerce_hide_out_of_stock_items = BeRocket_AAPF_Widget_functions::woocommerce_hide_out_of_stock_items();
        if( $woocommerce_hide_out_of_stock_items == 'yes' && $filter_type == 'attribute' && $attribute == '_stock_status' ) {
            braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'option_restriction', 'Disabled by WooCommerce option "Hide out of stock items from the catalog"');
            $widget_error_log['return'] = 'stock_status';
            $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
            return true;
        }

        if( $type == "slider" ) {
            $operator = 'OR';
        }

        $terms = $sort_terms = $price_range = array();
        list($terms_error_return, $terms_ready, $terms, $type) = apply_filters( 'berocket_widget_attribute_type_terms', array(false, false, $terms, $type), $attribute, $filter_type, $instance );
        if( $terms_ready ) {
            if( $terms_error_return === FALSE ) {
                $set_query_var_title['terms'] = apply_filters( 'berocket_aapf_widget_terms', $terms );
                if( BeRocket_AAPF::$debug_mode ) {
                    $widget_error_log['terms'] = $terms;
                }
            } else {
                $widget_error_log['terms'] = $terms;
                $widget_error_log['return'] = $terms_error_return;
                $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
                return false;
            }
        } else {
            if ( $filter_type == 'attribute' && $attribute == 'price' && $type == 'slider' ) {
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
                    $price_range = BeRocket_AAPF_Widget_functions::get_price_ranges();
                    if( ! empty($price_range) && isset($price_range['min_price']) && isset($price_range['max_price']) ) {
                        if($price_range['min_float'] == $price_range['max_float']) {
                            $price_range = array($price_range['min_price'], $price_range['min_price']);
                        } else {
                            $price_range = array($price_range['min_price'], $price_range['max_price']);
                        }
                        $price_range = array(
                            floor(apply_filters('berocket_price_filter_widget_min_amount', apply_filters('berocket_price_slider_widget_min_amount', apply_filters( 'woocommerce_price_filter_widget_min_amount', $price_range[0] )), $price_range[0])),
                            ceil (apply_filters('berocket_price_filter_widget_max_amount', apply_filters('berocket_price_slider_widget_max_amount', apply_filters( 'woocommerce_price_filter_widget_max_amount', $price_range[1] )), $price_range[1]))
                        );
                    } else {
                        $widget_error_log['price_range'] = $price_range;
                        $widget_error_log['return'] = 'price_range < 2';
                        $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
                        return false;
                    }
                    if( (! empty($min_price) || $min_price == '0') ) {
                        $price_range[0] = $min_price;
                    }
                    if( ! empty($max_price) ) {
                        $price_range[1] = $max_price;
                    }
                }
                if( BeRocket_AAPF::$debug_mode ) {
                    $widget_error_log['price_range'] = $price_range;
                }
                if( ! empty($text_before_price) || ! empty($text_after_price) ) {
                    wp_localize_script(
                        'berocket_aapf_widget-script',
                        'br_price_text',
                        array(
                            'before'  => (isset($text_before_price) ? $text_before_price : ''),
                            'after'   => (isset($text_after_price) ? $text_after_price : ''),
                        )
                    );
                }
                $set_query_var_title['slider_display_data'] = 'wc_price';
                $terms = array((object)array(
                    'term_id'  => implode('_', $price_range),
                    'slug'     => implode('_', $price_range),
                    'value'    => implode('_', $price_range),
                    'name'     => __('Price', 'BeRocket_AJAX_domain'),
                    'count'    => 1,
                    'taxonomy' => 'price',
                    'min'      => $price_range[0],
                    'max'      => $price_range[1],
                    'step'     => '1',
                ));
                $set_query_var_title['terms'] = apply_filters( 'berocket_aapf_widget_terms', $terms );
            } elseif ( $filter_type != 'attribute' || $attribute != 'price' ) {
                $get_terms_args = array(
                    'taxonomy'      => $attribute,
                    'hide_empty'    => true,
                    'hierarchical'  => ! empty($hide_child_attributes)
                );
                $get_terms_advanced = array(
                    'operator'      => $operator,
                    'force_query'   => ! empty($br_wp_footer)
                );
                if( ! empty($cat_value_limit) ) {
                    $get_terms_advanced['additional_tax_query'] = array(
                        'field'             => 'slug',
                        'include_children'  => true,
                        'operator'          => 'IN',
                        'taxonomy'          => 'product_cat',
                        'terms'             => array($cat_value_limit)
                    );
                }
                if ( $attribute == '_rating' ) {
                    $get_terms_args['taxonomy'] = 'product_visibility';
                    $get_terms_args['slug']     = array('rated-1', 'rated-2', 'rated-3', 'rated-4', 'rated-5');
                } elseif( $filter_type == 'tag' ) {
                    $get_terms_args['taxonomy'] = 'product_tag';
                } elseif( $filter_type == 'custom_taxonomy' ) {
                    $get_terms_args['taxonomy'] = $custom_taxonomy;
                }
                if( ! empty($order_values_by) && $order_values_by == 'Alpha' ) {
                    $get_terms_args['orderby'] = 'name';
                } elseif( ! empty($order_values_by) && $order_values_by == 'Numeric' ) {
                    $get_terms_args['orderby'] = 'name_num';
                }
                if( ! empty($order_values_type) ) {
                    $get_terms_args['order'] = ($order_values_type == 'asc' ? 'ASC' : 'DESC');
                }
                $get_terms_args = apply_filters('berocket_aapf_get_terms_args', $get_terms_args, $instance, $args);
                $get_terms_advanced = apply_filters('berocket_aapf_get_terms_additional', $get_terms_advanced, $instance, $args, $get_terms_args);
                $terms = berocket_aapf_get_terms( $get_terms_args, $get_terms_advanced );
                if ( $attribute == '_rating' ) {
                    if( is_array($terms) && ! is_wp_error($terms) ) {
                        $rating_names = array(
                            'rated-1' => ( $type == 'select' ? __('1 star', 'BeRocket_AJAX_domain') : __('<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>', 'BeRocket_AJAX_domain') ),
                            'rated-2' => ( $type == 'select' ? __('2 stars', 'BeRocket_AJAX_domain') : __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>', 'BeRocket_AJAX_domain') ),
                            'rated-3' => ( $type == 'select' ? __('3 stars', 'BeRocket_AJAX_domain') : __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>', 'BeRocket_AJAX_domain') ),
                            'rated-4' => ( $type == 'select' ? __('4 stars', 'BeRocket_AJAX_domain') : __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>', 'BeRocket_AJAX_domain') ),
                            'rated-5' => ( $type == 'select' ? __('5 stars', 'BeRocket_AJAX_domain') : __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>', 'BeRocket_AJAX_domain') ),
                        );
                        foreach($terms as &$term) {
                            if( isset($rating_names[$term->slug]) ) {
                                $term->name = $rating_names[$term->slug];
                            }
                        }
                        if( isset($term) ) {
                            unset($term);
                        }
                    }
                }
                $terms = apply_filters('berocket_aapf_widget_include_exclude_items', $terms, $instance, $get_terms_args, $get_terms_advanced, $set_query_var_title);
                if ( isset($terms) && is_array($terms) && count( $terms ) < 1 ) {
                    $widget_error_log['terms'] = $terms;
                    $widget_error_log['return'] = 'terms < 1';
                    $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
                    return false;
                }
                $set_query_var_title['terms'] = apply_filters( 'berocket_aapf_widget_terms', $terms );
            }
        }

        $style = '';
        $style = br_get_value_from_array($args, 'widget_inline_style');

        if( !$scroll_theme ) $scroll_theme = 'dark';
        if( $filter_type == 'custom_taxonomy' )
            $attribute = $custom_taxonomy;
        if( ! isset($attribute_count) || $attribute_count == '' ) {
            $attribute_count = br_get_value_from_array($br_options,'attribute_count');
        }
        if( empty($attribute_count_show_hide) ) {
            $attribute_count_show_hide = ( empty($br_options['hide_value']['button']) ? 'visible' : 'hidden' );
        }

        if( in_array($set_query_var_title['new_template'], array('select', 'slider', 'new_slider')) ) {
            $values_per_row = 1;
        }

        BeRocket_AAPF::wp_enqueue_script( 'berocket_aapf_widget-script' );
        BeRocket_AAPF::wp_enqueue_style ( 'berocket_aapf_widget-style' );
        $set_query_var_title['operator']                    = $operator;
        $set_query_var_title['attribute']                   = $attribute;
        $set_query_var_title['type']                        = $type;
        $set_query_var_title['style']                       = apply_filters( 'berocket_aapf_widget_style', $style );
        $set_query_var_title['x']                           = time();
        $set_query_var_title['filter_type']                 = $filter_type;
        $set_query_var_title['show_product_count_per_attr'] = ! empty($show_product_count_per_attr);
        $set_query_var_title['product_count_per_attr_style']= berocket_isset($product_count_per_attr_style);
        $set_query_var_title['hide_child_attributes']       = berocket_isset($hide_child_attributes);
        $set_query_var_title['cat_value_limit']             = ( isset($cat_value_limit) ? $cat_value_limit : null );
        $set_query_var_title['select_first_element_text']   = ( empty($select_first_element_text) ? __('Any', 'BeRocket_AJAX_domain') : $select_first_element_text );
        $set_query_var_title['hide_o_value']                = ! empty($br_options['hide_value']['o']);
        $set_query_var_title['hide_sel_value']              = ! empty($br_options['hide_value']['sel']);
        $set_query_var_title['hide_empty_value']            = ! empty($br_options['hide_value']['empty']);
        $set_query_var_title['hide_button_value']           = ! empty($br_options['hide_value']['button']);
        $set_query_var_title['attribute_count_show_hide']   = $attribute_count_show_hide;
        $set_query_var_title['attribute_count']             = $attribute_count;
        $set_query_var_title['values_per_row']              = (isset($values_per_row) ? $values_per_row : null);
        $set_query_var_title['child_parent']                = (isset($child_parent) ? $child_parent : null);
        $set_query_var_title['child_parent_depth']          = (isset($child_parent_depth) ? $child_parent_depth : null);
        $set_query_var_title['product_count_style']         = (isset($br_options['styles_input']['product_count']) ? $br_options['styles_input']['product_count'] : '').'pcs '.(isset($br_options['styles_input']['product_count_position']) ? $br_options['styles_input']['product_count_position'] : null).'pcs';
        $set_query_var_title['styles_input']                = (isset($br_options['styles_input']) ? $br_options['styles_input'] : array());
        $set_query_var_title['child_parent_previous']       = (isset($child_parent_previous) ? $child_parent_previous : null);
        $set_query_var_title['child_parent_no_values']      = (isset($child_parent_no_values) ? $child_parent_no_values : null);
        $set_query_var_title['child_parent_no_products']    = (isset($child_parent_no_products) ? $child_parent_no_products : null);
        $set_query_var_title['before_title']                = (isset($before_title) ? $before_title : null);
        $set_query_var_title['after_title']                 = (isset($after_title) ? $after_title : null);
        $set_query_var_title['widget_id']                   = ( $this->id ? $this->id : $widget_id );
        $set_query_var_title['widget_id_number']            = ( $this->number ? $this->number : $widget_id_number );
        $set_query_var_title['slug_urls']                   = ! empty($br_options['slug_urls']);
        $set_query_var_title['first_page_jump'] = '1';
        $set_query_var_title['icon_before_value'] = (isset($icon_before_value) ? $icon_before_value : null);
        $set_query_var_title['icon_after_value'] = (isset($icon_after_value) ? $icon_after_value : null);
        $set_query_var_title['single_selection'] = ! empty($single_selection);

        // widget title and start tag ( <ul> ) can be found in templates/widget_start.php
        $set_query_var_title['use_value_with_color'] = (isset($use_value_with_color) ? $use_value_with_color : null);
        $set_query_var_title['color_image_block_size'] = berocket_isset($color_image_block_size, false, 'h2em w2em');
        $set_query_var_title['color_image_checked'] = berocket_isset($color_image_checked, false, 'brchecked_default');
        $set_query_var_title['color_image_checked_custom_css'] = berocket_isset($color_image_checked_custom_css);
        $set_query_var_title['color_image_block_size_height'] = berocket_isset($color_image_block_size_height);
        $set_query_var_title['color_image_block_size_width'] = berocket_isset($color_image_block_size_width);
        $set_query_var_title['use_value_with_color'] = berocket_isset($use_value_with_color);
        $set_query_var_title['additional_data_options'] = berocket_isset($additional_data_options);
        $set_query_var_title = apply_filters('berocket_aapf_query_var_title_filter', $set_query_var_title, $instance, $br_options);

        if( ! empty($set_query_var_title['new_template']) ) {
            $set_query_var_title = apply_filters('berocket_query_var_title_before_widget', $set_query_var_title, $type, $instance, $args, $terms);
            if( ! empty($set_query_var_title['terms']) ) {
                $set_query_var_title = apply_filters('berocket_query_var_title_before_widget_without_check', $set_query_var_title, $type, $instance, $args, $terms);
                set_query_var( 'berocket_query_var_title', $set_query_var_title);
                br_get_template_part('filters/'.$set_query_var_title['new_template']);
            }
        }

        if( ! apply_filters('bapf_isoption_ajax_site', ! empty($option['ajax_site'])) ) {
            do_action('br_footer_script');
        } else {
            echo '<script>jQuery(document).ready(function() {if(typeof(braapf_init_load) == "function") {braapf_init_load();}});
            if(typeof(braapf_init_load) == "function") {braapf_init_load();}</script>';
        }

        if( BeRocket_AAPF::$debug_mode ) {
            $widget_error_log['terms'] = (isset($terms) ? $terms : null);
        }
        $widget_error_log['return'] = 'OK';
        $this->filter_return($br_wc_query, $wp_the_query, $wp_query, $wc_query, $old_the_query, $old_query, $widget_error_log);
    }

    public function filter_return(&$br_wc_query, &$wp_the_query, &$wp_query, &$wc_query, &$old_the_query, &$old_query, $widget_error_log) {
        BeRocket_AAPF::$error_log['6_widgets'][] = $widget_error_log;
        if ( isset ( $br_wc_query ) ) {
            if ( isset ( $old_query ) ) {
                $wp_the_query = $old_the_query;
                $wp_query = $old_query;
            }
            if( ! empty($wc_query) && is_a($wc_query, 'WP_Query') && class_exists('WC_Query') &&  method_exists('WC_Query', 'product_query') && method_exists('WC_Query', 'get_main_query') ) {
                $wc_query = apply_filters('braapf_wp_query_widget_end', $wc_query, $old_query);
                wc()->query->product_query($wc_query);
            }
            wc()->query->remove_ordering_args();
        }
        do_action('berocket_aapf_filter_end_generation');
    }
    //DEPRECATED SOON
    function update( $new_instance, $old_instance ) {
        return $old_instance;
    }
    function form( $instance ) {
        include AAPF_TEMPLATE_PATH . "admin.php";
    }
}
