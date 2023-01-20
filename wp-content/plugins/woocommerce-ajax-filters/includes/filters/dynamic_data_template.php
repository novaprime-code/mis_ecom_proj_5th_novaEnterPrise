<?php
class BeRocket_AAPF_dynamic_data_template {
    public $options;
    function __construct() {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $this->options = $BeRocket_AAPF->get_option();
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'autocomplete_off'), 900, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'products_count'), 1000, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'autocomplete_off_global'), 400, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'hide_show_filter'), 500, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'description'), 600, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'custom_scroll'), 700, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'title_icon'), 1200, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'css_class'), 1300, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'child_parent'), 1500, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'remove_empty_header'), 9900, 1);
        //Checkbox data
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'checkbox_checked'), 10, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'value_icon'), 500, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'show_hide_button'), 500, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'hierarhical'), 1000, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'hierarhical_hide_child'), 1005, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'values_per_row'), 1100, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'hide_attributes'), 1200, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'show_hide_button'), 1300, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'hide_empty_widgets'), 1400, 4);
        //Select data
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'option_selected'), 10, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'select_multiple'), 10, 4);
        //Slider data
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'new_attribute_slider'), 1, 3);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'number_style'), 500, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'value_icon_slider'), 600, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'text_before_after'), 700, 4);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'value_icon_new_slider'), 800, 4);
        //Color/Image
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'color_image_text'), 1100, 3);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'color_image_custom_checked'), 1300, 3);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'color_size'), 1050, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'color_image_text_single'), 1100, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'color_image_text_single_tooltip'), 1101, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'color_image_icon_before_after'), 1200, 4);
        //Elements
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_additional'), 100, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_hide_show_filter'), 500, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_description'), 600, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_custom_scroll'), 700, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_title_icon'), 1200, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'element_css_class'), 1300, 2);
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'remove_empty_header'), 9900, 1);
        //Selected Filters Area
        add_filter('BeRocket_AAPF_template_full_element_content', array($this, 'selected_filters_hide_empty'), 1100, 2);
    }
    function checkbox_checked($element, $term, $i, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
            extract($berocket_query_var_title);
            $child_parent = berocket_isset($child_parent);
            $is_child_parent = $child_parent == 'child';
            $is_child_parent_or = ( $child_parent == 'child' || $child_parent == 'parent' );
            $child_parent_depth = ( $child_parent == 'parent' ? 0 : intval(berocket_isset($child_parent_depth, false, 0)) );
            if( br_is_term_selected( $term, true, $is_child_parent_or, $child_parent_depth, array('berocket_query_var_title' => $berocket_query_var_title) ) != '' ) {
                $element = self::create_element_arrays($element, array('attributes', 'class'));
                $element['attributes']['class'][] = 'checked';
                $element = self::create_element_arrays($element, array('content', 'checkbox', 'attributes'));
                $element['content']['checkbox']['attributes']['checked'] = 'checked';
                if( ! empty($this->options['hide_value']['sel']) ) {
                    $element['attributes']['class']['hiden'] = 'bapf_hide';
                }
            }
            if( $term->count == 0 && ! empty($this->options['hide_value']['o']) ) {
                $element['attributes']['class']['hiden'] = 'bapf_hide';
            }
        }
        return $element;
    }
    function select_multiple($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['single_selection'] ) {
            if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
                $template_content['template']['attributes']['class'][] = 'bapf_asradio';
            } elseif( $berocket_query_var_title['new_template'] == 'select' ) {
                $template_content['template']['attributes']['data-op'] = 'AND';
            }
        } else {
            if( $berocket_query_var_title['new_template'] == 'select' ) {
                $template_content['template']['content']['filter']['content']['list']['attributes']['multiple'] = 'multiple';
                unset($template_content['template']['content']['filter']['content']['list']['content']['element_any']);
            }
        }
        return $template_content;
    }
    function option_selected($element, $term, $i, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'select' ) {
            extract($berocket_query_var_title);
            $child_parent = berocket_isset($child_parent);
            $is_child_parent = $child_parent == 'child';
            $is_child_parent_or = ( $child_parent == 'child' || $child_parent == 'parent' );
            $child_parent_depth = ( $child_parent == 'parent' ? 0 : intval(berocket_isset($child_parent_depth, false, 0)) );
            if( br_is_term_selected( $term, true, $is_child_parent_or, $child_parent_depth, array('berocket_query_var_title' => $berocket_query_var_title) ) != '' ) {
                $element['attributes']['selected'] = 'selected';
            }
        }
        return $element;
    }
    function value_icon($element, $term, $i, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
            if( ! empty($berocket_query_var_title['icon_before_value']) ) {
                $icon = $berocket_query_var_title['icon_before_value'];
                $element['content']['label']['content'] = berocket_insert_to_array(
                    $element['content']['label']['content'],
                    'name',
                    array('icon_before_value' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) ),
                    true
                );
            }
            if( ! empty($berocket_query_var_title['icon_after_value']) ) {
                $icon = $berocket_query_var_title['icon_after_value'];
                $element['content']['label']['content'] = berocket_insert_to_array(
                    $element['content']['label']['content'],
                    'name',
                    array('icon_after_value' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) )
                );
            }
        }
        return $element;
    }
    function autocomplete_off($element, $term, $i, $berocket_query_var_title) {
        if( empty($this->options['seo_friendly_urls']) ) {
            if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
                $element['content']['checkbox']['attributes']['autocomplete'] = 'off';
            }
        }
        return $element;
    }
    function autocomplete_off_global($template_content, $terms, $berocket_query_var_title) {
        if( empty($this->options['seo_friendly_urls']) ) {
            if( $berocket_query_var_title['new_template'] == 'select' ) {
                $template_content['template']['content']['filter']['content']['list']['attributes']['autocomplete'] = 'off';
            }
        }
        return $template_content;
    }
    function products_count($element, $term, $i, $berocket_query_var_title) {
        if( $berocket_query_var_title['show_product_count_per_attr'] ) {
            $qty_text = $term->count;
			$base_text = '(qty)';
			if( strpos($berocket_query_var_title['product_count_per_attr_style'], 'space') !== false ) {
				$base_text = str_replace('(', '&nbsp;(', $base_text);
			}
			if( strpos($berocket_query_var_title['product_count_per_attr_style'], 'value') !== false ) {
				$base_text = str_replace(array('(', ')'), array('(&nbsp;', '&nbsp;)'), $base_text);
			}
			$count_style = array('', '');
            if($this->options['styles_input']['product_count'] == 'round') {
				$count_style = array('(', ')');
            } elseif($this->options['styles_input']['product_count'] == 'quad') {
				$count_style = array('[', ']');
            }
			$base_text = str_replace(array('(', ')'), $count_style, $base_text);
			$qty_text = str_replace('qty', $qty_text, $base_text);
            if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
                $element['content']['qty'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(),
                    'content'       => array($qty_text)
                );
                if( ! empty($berocket_query_var_title['product_count_style']) ) {
                    $element['content']['qty']['attributes']['class'] = array('pcs' => $berocket_query_var_title['product_count_style']);
                }
            } elseif( $berocket_query_var_title['new_template'] == 'select' ) {
                $element['content']['qty'] = $qty_text;
            }
        }
        return $element;
    }
    public static function create_element_arrays($element, $arrays) {
        $check = array_shift($arrays);
        if( $check !== NULL ) {
            if( ! isset($element[$check]) || ! is_array($element[$check]) ) {
                $element[$check] = array();
            }
            $element[$check] = self::create_element_arrays($element[$check], $arrays);
        }
        return $element;
    }
    function show_hide_button($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' && empty($template_content['template']['content']['filter']['content']['show_hide']) ) {
            $elements = $template_content['template']['content']['filter']['content']['list']['content'];
            $has_hidden = 0;
            foreach($elements as $element) {
                if( berocket_isset($element['attributes']['class']['hiden']) == 'bapf_hide' ) {
                    $has_hidden++;
                    break;
                }
            }
            if( $has_hidden ) {
                $template_content['template']['attributes']['class']['hiden'] = 'bapf_fhide';
                if( $berocket_query_var_title['attribute_count_show_hide'] == 'visible' ) {
                    $show_text = __('Show value(s)', 'BeRocket_AJAX_domain');
                    $hide_text = __('Hide value(s)', 'BeRocket_AJAX_domain');
                    $template_content['template']['content']['filter']['content']['show_hide'] = array(
                        'type'          => 'tag',
                        'tag'           => 'span',
                        'attributes'    => array(
                            'class'         => 'bapf_show_hide',
                            'data-show'     => $show_text,
                            'data-hide'     => $hide_text
                        ),
                        'content'       => array(
                            'name'          => $show_text
                        )
                    );
                }
            }
        }
        return $template_content;
    }
    function hierarhical($template_content, $terms, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['hide_child_attributes']) ) {
            if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
                $elements = $template_content['template']['content']['filter']['content']['list']['content'];
                $new_elements = array();
                $parent_ids = array();
                foreach($terms as $i => $term) {
                    if( ! isset($term->depth) || $term->depth == 0) {
                        $parent_ids[] = $term->term_id;
                        $new_elements['element_'.$i] = $elements['element_'.$i];
                    } else {
                        if( ! in_array($term->parent, $parent_ids) ) continue;
                        $parent_ids[] = $term->term_id;
                        if( isset($temp) ) unset($temp);
                        if( isset($last_el) ) unset($last_el);
                        end( $new_elements ); $last_key = key( $new_elements );
                        $temp = $last_el = &$new_elements[$last_key];
                        for($j = 1; $j < $term->depth; $j++) {
                            unset($temp);
                            $temp = &$last_el;
                            unset($last_el);
                            end( $temp['content']['child']['content'] ); $last_key = key( $temp['content']['child']['content'] );
                            $last_el = &$temp['content']['child']['content'][$last_key];
                        }
                        if( empty($last_el['content']['child']) ) {
                            $last_el['content']['child'] = array(
                                'type'          => 'tag',
                                'tag'           => 'ul',
                                'attributes'    => array(),
                                'content'       => array()
                            );
                        }
                        $last_el['content']['child']['content']['element_'.$i] = $elements['element_'.$i];
                    }
                }
                $template_content['template']['content']['filter']['content']['list']['content'] = $new_elements;
            } elseif( $berocket_query_var_title['new_template'] == 'select' ) {
                $styles = array(
                    's' => '&nbsp;',
                    '2s' => '&nbsp;&nbsp;',
                    '4s' => '&nbsp;&nbsp;&nbsp;&nbsp;'
                );
                $BeRocket_AAPF = BeRocket_AAPF::getInstance();
                $option = $BeRocket_AAPF->get_option();
                $prefix = '-';
                if( array_key_exists($option['child_pre_indent'], $styles) ) {
                    $prefix = $styles[$option['child_pre_indent']];
                }
                $new_elements = array();
                foreach($terms as $i => $term) {
                    if( isset($term->depth) && $term->depth != 0) {
                        for($j = 0; $j < $term->depth; $j++) {
                            array_unshift($template_content['template']['content']['filter']['content']['list']['content']['element_'.$i]['content'], $prefix);
                        }
                    }
                }
            }
        }
        return $template_content;
    }
    function element_hide_show_filter($template_content, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['widget_collapse']) && ! empty($template_content['template']['content']['header']) ) {
            $widget_is_hide = ! empty($berocket_query_var_title['widget_is_hide']);
            if( ! empty($berocket_query_var_title['attribute']) && ( empty($berocket_query_var_title['additional_data_options']) || empty($berocket_query_var_title['additional_data_options']['widget_is_hide_on_load']) ) ) {
                $widget_is_hide = br_widget_is_hide($berocket_query_var_title['attribute'], $widget_is_hide);
            }
            $widget_collapse = $berocket_query_var_title['widget_collapse'];
            $template_content['template']['attributes']['class'][] = ($widget_is_hide ? 'bapf_ocolaps' : 'bapf_ccolaps');
            $template_content['template']['content']['header']['attributes'] = self::create_element_arrays($template_content['template']['content']['header']['attributes'], array('class'));
            $template_content['template']['content']['header']['attributes']['class'][] = 'bapf_colaps_togl';
            if( $widget_is_hide ) {
                $template_content['template']['content']['filter'] = self::create_element_arrays($template_content['template']['content']['filter'], array('attributes', 'style'));
                if( ! in_array('display:none;', $template_content['template']['content']['filter']['attributes']['style']) ) {
                    $template_content['template']['content']['filter']['attributes']['style'][] = 'display:none;';
                }
            }
            if( in_array($widget_collapse, array('with_arrow', 'without_arrow_mobile')) ) {
                $template_content['template']['content']['header']['content']['title'] = self::create_element_arrays($template_content['template']['content']['header']['content']['title'], array('attributes', 'class'));
                $template_content['template']['content']['header']['content']['title']['attributes']['class']['bapf_hascolarr'] = 'bapf_hascolarr';
                $template_content['template']['content']['header']['content']['title']['content']['show_hide_button'] = array(
                    'type'          => 'tag',
                    'tag'           => 'i',
                    'attributes'    => array(
                        'class'         => array(
                            'bapf_colaps_smb',
                            'fa',
                            ($widget_is_hide ? 'fa-chevron-down' : 'fa-chevron-up')
                        )
                    ),
                    'content'       => array(),
                );
                if( in_array($widget_collapse, array('without_arrow_mobile')) ) {
                    $template_content['template']['content']['header']['content']['title']['content']['show_hide_button']['attributes']['class'][] = 'bapf_hide_mobile';
                }
            }
        }
        return $template_content;
    }
    function hide_show_filter($template_content, $terms, $berocket_query_var_title) {
        $template_content = $this->element_hide_show_filter($template_content, $berocket_query_var_title);
        return $template_content;
    }
    function element_description($template_content, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['description']) ) {
            $filter_unique_class = 'bapf_'.$berocket_query_var_title['unique_filter_id'];
            $template_content['template']['content']['header']['content']['title'] = self::create_element_arrays($template_content['template']['content']['header']['content']['title'], array('attributes', 'class'));
            $template_content['template']['content']['header']['content']['title']['attributes']['class']['bapf_hasdesc'] = 'bapf_hasdesc';
            $template_content['template']['content']['header']['content']['title']['content']['description'] = array(
                'type'          => 'tag',
                'tag'           => 'span',
                'attributes'    => array(
                    'class'         => array(
                        'bapf_desci',
                    ),
                    'id'            => $filter_unique_class . '_tippy'
                ),
                'content'       => array(
                    'icon' => array(
                        'type'          => 'tag',
                        'tag'           => 'i',
                        'attributes'    => array(
                            'class'         => array(
                                'fa',
                                'fa-info'
                            )
                        ),
                        'content'       => array(),
                    ),
                )
            );
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            $options = $BeRocket_AAPF->get_option();
            $trigger = br_get_value_from_array($options, array('description', 'show'), 'click');
            if( $trigger == 'hover' ) {
                $trigger = 'mouseenter';
            }
            BeRocket_tooltip_display::add_tooltip(
                array(
                    'appendTo'      => 'document.getElementById("bapf_footer_description")',
                    'arrow'         => true,
                    'interactive'   => true, 
                    'placement'     => 'right',
                    'trigger'       => '"' . $trigger . '"',
                    'theme'         => '"'.(empty($options['tippy_description_theme']) ? 'light' : $options['tippy_description_theme']).'"',
                    'popperOptions' => '{styles:{"z-index":99999999999999}}'
                ),
                $berocket_query_var_title['description'],
                '#'.$filter_unique_class . '_tippy'
            );
            add_action('bapf_wp_footer', array($this, 'description_footer'));
        }
        return $template_content;
    }
    function description($template_content, $terms, $berocket_query_var_title) {
        $template_content = $this->element_description($template_content, $berocket_query_var_title);
        return $template_content;
    }
    function element_custom_scroll($template_content, $berocket_query_var_title) {
        if( in_array(berocket_isset($berocket_query_var_title['new_template']), array('select', 'slider', 'new_slider')) ) {
            return $template_content;
        }
        if( ! empty($berocket_query_var_title['height']) && intval($berocket_query_var_title['height']) > 10 ) {
            BeRocket_AAPF::wp_enqueue_script( 'berocket_aapf_widget-scroll-script' );
            BeRocket_AAPF::wp_enqueue_style( 'berocket_aapf_widget-scroll-style' );
            $template_content['template']['content']['filter'] = self::create_element_arrays($template_content['template']['content']['filter'], array('attributes', 'class'));
            $template_content['template']['content']['filter']['attributes']['data-mcs-theme'] = berocket_isset($berocket_query_var_title['scroll_theme']);
            $template_content['template']['content']['filter']['attributes']['data-mcs-h'] = intval($berocket_query_var_title['height']);
            $template_content['template']['content']['filter']['attributes']['class']['height_control'] = 'bapf_mcs';
        }
        return $template_content;
    }
    function custom_scroll($template_content, $terms, $berocket_query_var_title) {
        $template_content = $this->element_custom_scroll($template_content, $berocket_query_var_title);
        return $template_content;
    }
    function element_css_class($template_content, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['css_class']) ) {
            $template_content['template']['attributes']['class']['custom_css_class'] = $berocket_query_var_title['css_class'];
        }
        return $template_content;
    }
    function css_class($template_content, $terms, $berocket_query_var_title) {
        $template_content = $this->element_css_class($template_content, $berocket_query_var_title);
        return $template_content;
    }
    function hierarhical_hide_child($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' && berocket_isset($berocket_query_var_title['hide_child_attributes']) == "1" ) {
            $template_content['template']['content']['filter']['content']['list']['content'] = $this->hierarhical_add_open_close_button($template_content['template']['content']['filter']['content']['list']['content']);
        }
        return $template_content;
    }
    function hierarhical_add_open_close_button($elements) {
        foreach($elements as &$element) {
            if( ! empty($element['content']['child']) && count($element['content']['child']['content']) ) {
                $element['content'] = berocket_insert_to_array($element['content'], 'child', array(
                    'open_child' => array(
                        'type'          => 'tag',
                        'tag'           => 'i',
                        'attributes'    => array(
                            'class'         => array(
                                'fa',
                                'fa-plus',
                                'bapf_ochild',
                            ),
                        ),
                        'content'       => array()
                    ),
                ), true);
                $element['content']['child']['attributes'] = self::create_element_arrays($element['content']['child']['attributes'], array('style'));
                if( ! in_array('display:none;', $element['content']['child']['attributes']['style']) ) {
                    $element['content']['child']['attributes']['style'][] = 'display:none;';
                }
                $element['content']['child']['content'] = $this->hierarhical_add_open_close_button($element['content']['child']['content']);
            }
        }
        if( isset($element) ) {
            unset($element);
        }
        return $elements;
    }
    function values_per_row($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' && ! empty($berocket_query_var_title['values_per_row']) ) {
            $template_content['template']['attributes']['class'][] = 'bapf_vpr_'.$berocket_query_var_title['values_per_row'];
        }
        return $template_content;
    }
    function hide_attributes($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' ) {
            if( ! empty($berocket_query_var_title['attribute_count']) && $berocket_query_var_title['attribute_count'] > 0 ) {
                $i = 0;
                foreach($template_content['template']['content']['filter']['content']['list']['content'] as $key => $value) {
                    if( $i >= $berocket_query_var_title['attribute_count'] ) {
                        $template_content['template']['content']['filter']['content']['list']['content'][$key] = self::create_element_arrays($template_content['template']['content']['filter']['content']['list']['content'][$key], array('attributes', 'class'));
                        $template_content['template']['content']['filter']['content']['list']['content'][$key]['attributes']['class']['hiden'] = 'bapf_hide';
                    }
                    if( berocket_isset($value['attributes']['class']['hiden']) != 'bapf_hide' ) {
                        $i++;
                    }
                }
            }
        }
        return $template_content;
    }
    function hide_empty_widgets($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'checkbox' && $berocket_query_var_title['hide_empty_value'] ) {
            $is_empty_widget = true;
            foreach($template_content['template']['content']['filter']['content']['list']['content'] as $key => $value) {
                if( berocket_isset($value['attributes']['class']['hiden']) != 'bapf_hide' ) {
                    $is_empty_widget = false;
                    break;
                }
            }
            if( $is_empty_widget ) {
                $template_content['template']['attributes']['class']['hide_widget'] = 'bapf_filter_hide';
            }
        }
        return $template_content;
    }
    function element_title_icon($template_content, $berocket_query_var_title) {
        extract($berocket_query_var_title);
        if( ! empty($berocket_query_var_title['icon_before_title']) ) {
            $icon = $berocket_query_var_title['icon_before_title'];
            $template_content['template']['content']['header']['content']['title']['content'] = berocket_insert_to_array(
                $template_content['template']['content']['header']['content']['title']['content'],
                'title',
                array('icon_before_title' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) ),
                true
            );
        }
        if( ! empty($berocket_query_var_title['icon_after_title']) ) {
            $icon = $berocket_query_var_title['icon_after_title'];
            $template_content['template']['content']['header']['content']['title']['content'] = berocket_insert_to_array(
                $template_content['template']['content']['header']['content']['title']['content'],
                'title',
                array('icon_after_title' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) )
            );
        }
        return $template_content;
    }
    function title_icon($template_content, $terms, $berocket_query_var_title) {
        $template_content = $this->element_title_icon($template_content, $berocket_query_var_title);
        return $template_content;
    }
    function child_parent($template_content, $terms, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['child_parent']) ) {
            $child_position = 1;
            if( $berocket_query_var_title['child_parent'] == 'child' ) {
                $child_position = intval($berocket_query_var_title['child_parent_depth']) + 1;
                if( $child_position < 2 ) {
                    $child_position = 2;
                }
            }
            $template_content['template']['attributes']['class']['child_parent'] = 'bapf_child_'.$child_position;
            $template_content['template']['attributes']['data-child'] = $child_position;
        }
        return $template_content;
    }
    function remove_empty_header($template_content) {
        if( isset($template_content['template']['content']['header'])
            && (
                empty($template_content['template']['content']['header']['content'])
                || (
                    count($template_content['template']['content']['header']['content']) == 1
                    && isset($template_content['template']['content']['header']['content']['title'])
                    && (
                        empty($template_content['template']['content']['header']['content']['title'])
                        || ! is_array($template_content['template']['content']['header']['content']['title']) 
                        || empty($template_content['template']['content']['header']['content']['title']['content']) 
                        ||(
                            count($template_content['template']['content']['header']['content']['title']['content']) == 1
                            && isset($template_content['template']['content']['header']['content']['title']['content']['title'])
                            && $template_content['template']['content']['header']['content']['title']['content']['title'] == ''
                        )
                    )
                )
            )
        ) {
            unset($template_content['template']['content']['header']);
        }
        return $template_content;
    }
    function new_attribute_slider($template_content, $terms, $berocket_query_var_title) {
        if( in_array($berocket_query_var_title['new_template'], array('slider', 'new_slider')) && count($terms) > 1 ) {
            $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['class']['bapf_slidr_type'] = 'bapf_slidr_arr';
            $slider_data = array();
            foreach($terms as $term) {
                $slider_data[] = array('v' => $term->value, 'n' => $term->name);
            }
            $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-attr'] = json_encode($slider_data);
        }
        return $template_content;
    }
    function number_style($template_content, $terms, $berocket_query_var_title) {
        if( in_array($berocket_query_var_title['new_template'], array('slider', 'new_slider')) ) {
            foreach($terms as $term){break;}
            if( ! empty($berocket_query_var_title['number_style']) && ( (count($terms) == 1 && isset($term->min) && isset($term->max)) || $term->taxonomy == 'price' ) ) {
                $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-number_style'] = json_encode($berocket_query_var_title['number_style']);
            }
        }
        return $template_content;
    }
    function text_before_after($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'slider' ) {
            if( ! empty($berocket_query_var_title['text_before_price']) ) {
                $text_element = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'         => array(
                            'bapf_tbprice'
                        )
                    ),
                    'content' => array(
                        $berocket_query_var_title['text_before_price']
                    )
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'],
                    'input',
                    array(
                        'text_before_price' => $text_element,
                    ),
                    true
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'],
                    'input',
                    array(
                        'text_before_price' => $text_element,
                    ),
                    true
                );
            }
            if( ! empty($berocket_query_var_title['text_after_price']) ) {
                $text_element = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'         => array(
                            'bapf_taprice'
                        )
                    ),
                    'content' => array(
                        $berocket_query_var_title['text_after_price']
                    )
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'],
                    'input',
                    array(
                        'text_after_price' => $text_element
                    )
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'],
                    'input',
                    array(
                        'text_after_price' => $text_element
                    )
                );
            }
        } elseif( $berocket_query_var_title['new_template'] == 'new_slider' ) {
            if( ! empty($berocket_query_var_title['text_before_price']) ) {
                $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-prefix'] = 
                $berocket_query_var_title['text_before_price'] . br_get_value_from_array($template_content, array('template','content','filter','content','slider_all','content','slider','attributes','data-prefix') );
            }
            if( ! empty($berocket_query_var_title['text_after_price']) ) {
                $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-postfix'] = 
                br_get_value_from_array($template_content, array('template','content','filter','content','slider_all','content','slider','attributes','data-postfix') ) . $berocket_query_var_title['text_after_price'];
            }
        }
        return $template_content;
    }
    function value_icon_slider($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'slider' ) {
            if( ! empty($berocket_query_var_title['icon_before_value']) ) {
                $icon = $berocket_query_var_title['icon_before_value'];
                $icon_element = array(
                    'type'          => 'tag',
                    'tag'           => 'i',
                    'attributes'    => array(
                        'class'         => array(
                            'fa'
                        )
                    ),
                    'content' => array()
                );
                if( substr( $icon, 0, 3) == 'fa-' ) {
                    $icon_element['attributes']['class']['icon'] = $icon;
                } else {
                    $icon_element['content']['icon'] = array(
                        'type'          => 'tag_open',
                        'tag'           => 'img',
                        'attributes'    => array(
                            'class'         => array(
                                'berocket_widget_icon'
                            ),
                            'src'           => $icon,
                            'alt'           => ''
                        )
                    );
                }
                $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'],
                    'input',
                    array(
                        'icon_before_price' => $icon_element,
                    ),
                    true
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'],
                    'input',
                    array(
                        'icon_before_price' => $icon_element,
                    ),
                    true
                );
            }
            if( ! empty($berocket_query_var_title['icon_after_value']) ) {
                $icon = $berocket_query_var_title['icon_after_value'];
                $icon_element = array(
                    'type'          => 'tag',
                    'tag'           => 'i',
                    'attributes'    => array(
                        'class'         => array(
                            'fa'
                        )
                    ),
                    'content' => array()
                );
                if( substr( $icon, 0, 3) == 'fa-' ) {
                    $icon_element['attributes']['class']['icon'] = $icon;
                } else {
                    $icon_element['content']['icon'] = array(
                        'type'          => 'tag_open',
                        'tag'           => 'img',
                        'attributes'    => array(
                            'class'         => array(
                                'berocket_widget_icon'
                            ),
                            'src'           => $icon,
                            'alt'           => ''
                        )
                    );
                }
                $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['from']['content'],
                    'input',
                    array(
                        'icon_after_price' => $icon_element
                    )
                );
                $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'] = berocket_insert_to_array (
                    $template_content['template']['content']['filter']['content']['slider_all']['content']['to']['content'],
                    'input',
                    array(
                        'icon_after_price' => $icon_element
                    )
                );
            }
        }
        return $template_content;
    }
    function value_icon_new_slider($template_content, $terms, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'new_slider' ) {
            if( ! empty($berocket_query_var_title['icon_before_value']) ) {
                $icon = $berocket_query_var_title['icon_before_value'];
                $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-prefix'] = 
                ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) . br_get_value_from_array($template_content, array('template','content','filter','content','slider_all','content','slider','attributes','data-prefix') );
            }
            if( ! empty($berocket_query_var_title['icon_after_value']) ) {
                $icon = $berocket_query_var_title['icon_after_value'];
                $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-postfix'] = 
                br_get_value_from_array($template_content, array('template','content','filter','content','slider_all','content','slider','attributes','data-postfix') ) . ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' );
            }
        }
        return $template_content;
    }
    
    function element_additional($template_content, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'selected_filters' ) {
            $template_content = $this->hide_show_filter($template_content, array(), $berocket_query_var_title);
        }
        return $template_content;
    }
    //Color/Image
    function color_size($element, $term, $i, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style'])
            && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image'))
        ) {
            $element['content']['label']['content']['color']['attributes']['class']['size'] = berocket_isset($berocket_query_var_title['color_image_block_size']);
            if( berocket_isset($berocket_query_var_title['color_image_block_size']) == 'hxpx_wxpx' ) {
                if( empty($berocket_query_var_title['color_image_block_size_height']) ) {
                    $berocket_query_var_title['color_image_block_size_height'] = 50;
                }
                if( empty($berocket_query_var_title['color_image_block_size_width']) ) {
                    $berocket_query_var_title['color_image_block_size_width'] = 50;
                }
                $element['content']['label']['content']['color']['attributes']['style']['width'] = 'width:'.$berocket_query_var_title['color_image_block_size_width'].'px;';
                $element['content']['label']['content']['color']['attributes']['style']['height'] = 'height:'.$berocket_query_var_title['color_image_block_size_height'].'px;';
                $element['content']['label']['content']['color']['attributes']['style']['line-height'] = 'line-height:'.$berocket_query_var_title['color_image_block_size_height'].'px;';
                if( ! empty($element['content']['label']['content']['color']['content']['icon']) ) {
                    $font_size = min($berocket_query_var_title['color_image_block_size_width'], $berocket_query_var_title['color_image_block_size_height']);
                    $font_size = intval($font_size * 0.8);
                    $element['content']['label']['content']['color']['content']['icon']['attributes']['style']['font-size'] = 'font-size:'.$font_size.'px;';
                }
            }
        }
        return $element;
    }
    function color_image_text($template_content, $terms, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style'])
            && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image'))
            && ! empty($berocket_query_var_title['use_value_with_color'])
        ) {
            $text_position = $berocket_query_var_title['use_value_with_color'];
            $template_content['template']['attributes']['class']['text_position'] = 'bapf_clr_txt_'.$text_position;
            if( in_array($text_position, array('left', 'right')) ) {
                unset($template_content['template']['attributes']['class']['inline_color']);
            }
        }
        return $template_content;
    }
    function color_image_custom_checked($template_content, $terms, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style']) && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image')) ) {
            if( berocket_isset($berocket_query_var_title['color_image_checked']) == 'brchecked_custom' ) {
                $template_content['template']['attributes']['class']['checked_type'] = 'brchecked_custom_'.$berocket_query_var_title['unique_filter_id'];
                if( ! empty($berocket_query_var_title['color_image_checked_custom_css']) ) {
                    $styles = '.bapf_sfilter.bapf_stylecolor.brchecked_custom_' . $berocket_query_var_title['unique_filter_id'] . ' input[type="checkbox"]:checked + label .bapf_clr_span,
.bapf_sfilter.bapf_styleimage.brchecked_custom_' . $berocket_query_var_title['unique_filter_id'] . ' input[type="checkbox"]:checked + label .bapf_img_span {'
                    . $berocket_query_var_title['color_image_checked_custom_css'] . '}';
                    $template_content['template']['content']['checkboxstyle'] = array(
                        'type'          => 'tag',
                        'tag'           => 'style',
                        'content'       => array(
                            $styles
                        )
                    );
                }
            } else {
                $template_content['template']['attributes']['class']['checked_type'] = (empty($berocket_query_var_title['color_image_checked']) ? 'brchecked_default' : $berocket_query_var_title['color_image_checked']);
            }
        }
        return $template_content;
    }
    function color_image_text_single_tooltip($element, $term, $i, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style']) && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image'))
        && ! empty($berocket_query_var_title['use_value_with_color']) && $berocket_query_var_title['use_value_with_color'] == 'tooltip' ) 
        {
            $filter_unique_class = 'bapf_'.$berocket_query_var_title['unique_filter_id'].'_'.$term->term_id.'_label';
            $element['attributes']['id'] = $filter_unique_class;
            $tooltip_text = $term->name;
            if( ! empty($berocket_query_var_title['icon_before_value']) ) {
                $icon = $berocket_query_var_title['icon_before_value'];
                $tooltip_text = ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) . $tooltip_text;
            }
            if( ! empty($berocket_query_var_title['icon_after_value']) ) {
                $icon = $berocket_query_var_title['icon_after_value'];
                $tooltip_text = $tooltip_text . ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' );
            }
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            $options = $BeRocket_AAPF->get_option();
            
            BeRocket_tooltip_display::add_tooltip(
                array(
                    'appendTo'      => 'document.getElementById("bapf_footer_clrimg")',
                    'allowHTML'     => true,
                    'arrow'         => false,
                    'interactive'   => false,
                    'offset'        => '[0,0]',
                    'placement'     => 'top',
                    'theme'         => '"' . (empty($options['tippy_color_img_theme']) ? 'light' : $options['tippy_color_img_theme']) . '"',
                ),
                $tooltip_text,
                '#'.$filter_unique_class
            );
            add_action('bapf_wp_footer', array($this, 'clrimg_footer'));
            $element = $this->color_move_qty_to_color_inside($element, $term, $i, $berocket_query_var_title);
        }
        return $element;
    }
    function color_image_text_single($element, $term, $i, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style']) && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image')) ) {
            $element['content']['label']['attributes']['aria-label'] = $term->name;
            if( ! empty($berocket_query_var_title['use_value_with_color']) ) {
                $text_position = $berocket_query_var_title['use_value_with_color'];
                if( in_array($text_position, array('top', 'left', 'bottom', 'right')) ) {
                    $text_element = array(
                        'type'          => 'tag',
                        'tag'           => 'span',
                        'attributes'    => array(
                            'class'         => array(
                                'main'          => 'bapf_clr_text',
                            ),
                        ),
                        'content' => array(
                            'name' => $term->name
                        )
                    );
                    if( ! empty($element['content']['qty']) ) {
                        $text_element['content']['qty'] = $element['content']['qty'];
                        unset($element['content']['qty']);
                    }
                    $element['content']['label']['content'] = berocket_aapf_insert_to_array(
                        $element['content']['label']['content'],
                        'color',
                        array(
                            'text' => $text_element
                        ),
                        in_array($text_position, array('top', 'left'))
                    );
                }
            } elseif( ! empty($element['content']['qty']) ) {
                $element = $this->color_move_qty_to_color_inside($element, $term, $i, $berocket_query_var_title);
            }
        }
        return $element;
    }
    function color_move_qty_to_color_inside($element, $term, $i, $berocket_query_var_title) {
        if( ! empty($element['content']['qty']) ) {
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            $options = $BeRocket_AAPF->get_option();
            if( ! in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('image')) || empty($options['styles_input']['product_count_position_image']) ) {
                $element['content']['qty']['content'] = array($term->count);
                if( isset($element['content']['qty']['attributes']['class']['pcs']) ) {
                    unset($element['content']['qty']['attributes']['class']['pcs']);
                }
                $element['content']['label']['content']['color']['content']['span'] = BeRocket_AAPF_dynamic_data_template::create_element_arrays($element['content']['label']['content']['color']['content']['span'], array('content'));
                $element['content']['label']['content']['color']['content']['span']['content']['qty'] = $element['content']['qty'];
                unset($element['content']['qty']);
            }
        }
        return $element;
    }
    function color_image_icon_before_after($element, $term, $i, $berocket_query_var_title) {
        if( ! empty($berocket_query_var_title['new_style']) && in_array(berocket_isset($berocket_query_var_title['new_style']['specific']), array('color', 'image')) ) {
            if( ! empty($element['content']['label']['content']['text']) ) {
                if( ! empty($berocket_query_var_title['icon_before_value']) ) {
                    $icon = $berocket_query_var_title['icon_before_value'];
                    $element['content']['label']['content']['text']['content'] = berocket_insert_to_array(
                        $element['content']['label']['content']['text']['content'],
                        'name',
                        array('icon_before_value' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) ),
                        true
                    );
                }
                if( ! empty($berocket_query_var_title['icon_after_value']) ) {
                    $icon = $berocket_query_var_title['icon_after_value'];
                    $element['content']['label']['content']['text']['content'] = berocket_insert_to_array(
                        $element['content']['label']['content']['text']['content'],
                        'name',
                        array('icon_after_value' => ( substr( $icon, 0, 3) == 'fa-' ? '<i class="fa '.$icon.'"></i>' : '<i class="fa"><img class="berocket_widget_icon" src="'.$icon.'" alt=""></i>' ) )
                    );
                }
            }
        }
        return $element;
    }
    //Selected Filters Area
    function selected_filters_hide_empty($template_content, $berocket_query_var_title) {
        if( $berocket_query_var_title['new_template'] == 'selected_filters' ) {
            if( empty($berocket_query_var_title['selected_area_show']) ) {
                $template_content['template']['attributes']['class']['hide_empty'] = 'bapf_sfa_mt_hide';
            }
        }
        return $template_content;
    }
    //Footer elements for tippy
    function description_footer() {
        echo '<div id="bapf_footer_description"></div>';
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! empty($options['tippy_description_fontsize']) && intval($options['tippy_description_fontsize']) > 5 ) {
            echo '<style>#bapf_footer_description .tippy-content{
                font-size: '.$options['tippy_description_fontsize'].'px;
            }</style>';
        }
    }
    function clrimg_footer() {
        echo '<div id="bapf_footer_clrimg"></div>';
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! empty($options['tippy_color_img_fontsize']) && intval($options['tippy_color_img_fontsize']) > 5 ) {
            echo '<style>#bapf_footer_clrimg .tippy-content{
                font-size: '.$options['tippy_color_img_fontsize'].'px;
            }</style>';
        }
    }
}