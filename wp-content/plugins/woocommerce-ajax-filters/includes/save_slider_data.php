<?php
class BRAAPF_slider_data {
    function __construct() {
        add_action('berocket_custom_post_br_product_filter_wc_save_product_without_check_after', array($this, 'update_data'));
        add_action('braapf_slider_data_update', array($this, 'update_data'));
        add_filter('braapf_slider_data', array($this, 'get_data'));
    }
    function get_data($data = array()) {
        $option = get_option('braapf_sliders');
        if(! empty($option) && is_array($option)) {
            $data = $option;
        }
        return $data;
    }
    function update_data() {
        $filtersInstance = BeRocket_AAPF_single_filter::getInstance();
        $filter_ids = $filtersInstance->get_custom_posts();
        $filters_data = array();
        if( is_array($filter_ids) ) {
            foreach($filter_ids as $filter_id) {
                $filter_data = $filtersInstance->get_option($filter_id);
                $get_terms_data = $this->generate_data_for_filter($filter_data);
                if( $get_terms_data !== false ) {
                    $filters_data[$get_terms_data['get_terms_args']['taxonomy']] = $get_terms_data;
                }
            }
        }
        update_option('braapf_sliders', $filters_data);
    }
    function generate_data_for_filter($instance, $args = array()) {
        if ( $instance['filter_type'] == 'price' ) {
            $instance['filter_type'] = 'attribute';
            $instance['attribute'] = 'price';
        }
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
        $instance['title'] = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        $default_language = apply_filters( 'wpml_default_language', NULL );
        $instance = array_merge( BeRocket_AAPF_Widget::$defaults, $instance );
        foreach(array('operator') as $option_set) {
            if( empty($instance[$option_set]) ) {
                $instance[$option_set] = BeRocket_AAPF_Widget::$defaults[$option_set];
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
        }

        if( ! empty($instance['child_parent']) && in_array($instance['child_parent'], array('child', 'parent')) ) {
            $br_options['show_all_values'] = true;
        }
        if( empty($set_query_var_title['new_template']) ) {
            return false;
        }
        extract( $args );
        extract( $instance );
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
            return false;
        }

        if( ! empty($widget_type) && $custom_type_html = apply_filters('berocket_aapf_display_filter_custom_type', '', $widget_type, array('options' => $instance, 'args' => $args, 'set_query_var_title' => $set_query_var_title)) ) {
            return false;
        }
        $woocommerce_hide_out_of_stock_items = BeRocket_AAPF_Widget_functions::woocommerce_hide_out_of_stock_items();
        if( $woocommerce_hide_out_of_stock_items == 'yes' && $filter_type == 'attribute' && $attribute == '_stock_status' ) {
            return false;
        }

        if( $type == "slider" ) {
            $operator = 'OR';
        }

        $terms = $sort_terms = $price_range = array();
        list($terms_error_return, $terms_ready, $terms, $type) = apply_filters( 'berocket_widget_attribute_type_terms', array(false, false, $terms, $type), $attribute, $filter_type, $instance );
        if( $terms_ready ) {
            return false;
        } else {
            if ( $filter_type == 'attribute' && $attribute == 'price' && $type == 'slider' ) {
                return false;
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
                return array('get_terms_args' => $get_terms_args, 'get_terms_advanced' => $get_terms_advanced);
            }
        }
        return false;
    }
}
new BRAAPF_slider_data();