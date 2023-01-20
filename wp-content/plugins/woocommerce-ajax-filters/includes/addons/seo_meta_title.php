<?php
if( ! function_exists('BeRocket_AAPF_wcseo_title_visual1') ) {
    function BeRocket_AAPF_wcseo_title_visual1($filters, $text, $section, $terms_filtered) {
        $filters = array();
        foreach($terms_filtered as $filter_attr => $filter_values) {
            $last_item = '';
            if( count($filter_values['values']) > 1 ) {
                $last_item = array_pop($filter_values['values']);
            }
            $filters[] =  ( empty($filter_values['name']) ? '' : $filter_values['name'] . ' ' )
                . implode(', ', $filter_values['values'])
                . ( empty($last_item) ? '' :
                    (isset($filter_values['operator']) && $filter_values['operator'] == 'AND' 
                        ? __(' and ', 'BeRocket_AJAX_domain') 
                        : __(' or ', 'BeRocket_AJAX_domain')
                    ) . $last_item
                );
        }
        $filters = implode(__(' and ', 'BeRocket_AJAX_domain') , $filters);
        $text_return = $text;
        if( ! empty($filters) ) {
            $text_return .= __(' with ', 'BeRocket_AJAX_domain') . $filters;
        }
        return $text_return;
    }
}
if( ! function_exists('BeRocket_AAPF_wcseo_title_visual2') ) {
    function BeRocket_AAPF_wcseo_title_visual2($filters, $text, $section, $terms_filtered) {
        $filters = array();
        foreach($terms_filtered as $filter_attr => $filter_values) {
            $filters[] =  ( ! empty($filter_values['name']) ? $filter_values['name'] . ': ' : ''). implode(', ', $filter_values['values']);
        }
        $filters = implode('; ', $filters);
        $text_return = $text;
        if( ! empty($filters) ) {
            $text_return .= ' '. $filters;
        }
        return $text_return;
    }
}
if( ! function_exists('BeRocket_AAPF_wcseo_title_visual3') ) {
    function BeRocket_AAPF_wcseo_title_visual3($filters, $text, $section, $terms_filtered) {
        $filters = array();
        $first_attribute = '';
        foreach($terms_filtered as $filter_attr => $filter_values) {
            $last_item = '';
            if( count($filter_values['values']) > 1 ) {
                $last_item = array_pop($filter_values['values']);
            }
            $attributes_text = implode(', ', $filter_values['values'])
            . ( empty($last_item) ? '' :
                (isset($filter_values['operator']) && $filter_values['operator'] == 'AND' 
                    ? __(' and ', 'BeRocket_AJAX_domain') 
                    : __(' or ', 'BeRocket_AJAX_domain')
                ) . $last_item
            );
            if( empty($first_attribute) && empty($filter_values['has_slider']) && empty($filter_values['is_price']) ) {
                $first_attribute = $attributes_text;
            } else {
                $filters[] =  ( empty($filter_values['name']) ? '' : $filter_values['name'] . ' ' ) . $attributes_text;
            }
        }
        $filters = implode(__(' and ', 'BeRocket_AJAX_domain') , $filters);
        $text_return = $text;
        if( ! empty($first_attribute) ) {
            $text_return = $first_attribute . ' ' . $text_return;
        }
        if( ! empty($filters) ) {
            $text_return .= __(' with ', 'BeRocket_AJAX_domain') . $filters;
        }
        return $text_return;
    }
}
if( ! function_exists('BeRocket_AAPF_wcseo_title_visual4') ) {
    function BeRocket_AAPF_wcseo_title_visual4($filters, $text, $section, $terms_filtered) {
        $filters = array();
        foreach($terms_filtered as $filter_attr => $filter_values) {
            $last_item = '';
            if( count($filter_values['values']) > 1 ) {
                $last_item = array_pop($filter_values['values']);
            }
            $filters[] = implode(', ', $filter_values['values'])
                . ( empty($last_item) ? '' :
                    (isset($filter_values['operator']) && $filter_values['operator'] == 'AND' 
                        ? __(' and ', 'BeRocket_AJAX_domain') 
                        : __(' or ', 'BeRocket_AJAX_domain')
                    ) . $last_item
                );
        }
        $filters = implode(__(' / ', 'BeRocket_AJAX_domain') , $filters);
        $text_return = $text;
        if( ! empty($filters) ) {
            $text_return .= __(' - ', 'BeRocket_AJAX_domain') . $filters;
        }
        return $text_return;
    }
}
if( ! function_exists('BeRocket_AAPF_wcseo_title_visual5') ) {
    function BeRocket_AAPF_wcseo_title_visual5($filters, $text, $section, $terms_filtered) {
        $filters = array();
        foreach($terms_filtered as $filter_attr => $filter_values) {
            $filters[] =  ( ! empty($filter_values['name']) ? $filter_values['name'] . ': ' : ''). implode(', ', $filter_values['values']);
        }
        $filters = implode('; ', $filters);
        $text_return = $text;
        if( ! empty($filters) ) {
            $text_return = $filters . ' - ' . $text_return;
        }
        return $text_return;
    }
}
if( ! class_exists('BeRocket_AAPF_addon_woocommerce_seo_title') ) {
    class BeRocket_AAPF_addon_woocommerce_seo_title {
        public $terms_filtered = array();
        public $page_title = '';
        public $ready_elements =  array('title' => false, 'description' => false, 'header' => false);
        function __construct() {
            if( ! is_admin() ) {
                add_action('wp', array($this, 'plugins_loaded'), 99999999);
            }
        }
        function plugins_loaded() {
            add_action('get_header', array($this, 'get_header'));
            add_filter('document_title_parts', array($this, 'document_title_parts'));
            add_filter('wpseo_title', array($this, 'wpseo_title'), 10, 1);
            do_action('braapf_seo_meta_title', $this);
            $options = $this->get_options();
            if( ! empty($options['seo_element_header']) ) {
                add_filter('the_title', array($this, 'the_title'), 10, 2);
                add_filter('woocommerce_page_title', array($this, 'woocommerce_page_title'), 10, 2);
                do_action('braapf_seo_meta_header', $this);
            }
            if( ! empty($options['seo_element_description']) ) {
                add_filter('wpseo_metadesc', array($this, 'meta_description'));
                add_filter('aioseop_description_full', array($this, 'meta_description'));
                add_action('wp_head', array($this, 'wp_head_description'), 9000);
                do_action('braapf_seo_meta_description', $this);
            }
            if( ! function_exists($options['seo_meta_title_visual']) ) {
                $options['seo_meta_title_visual'] = 'BeRocket_AAPF_wcseo_title_visual1';
            }
            add_filter('berocket_aapf_seo_meta_filters_text_before', $options['seo_meta_title_visual'], 5, 4);
        }
        function get_header() {
            global $wp_query;
            global $berocket_parse_page_obj;
            $data = $berocket_parse_page_obj->get_current();
            $terms_name = array();
            if( isset($data['filters']) && is_array($data['filters']) ) {
                foreach($data['filters'] as $filter) {
                    if( in_array($filter['type'], array('taxonomy', 'attribute')) ) {
                        if( ! isset($terms_name[$filter['taxonomy']]) ) {
                            $taxonomy = get_taxonomy($filter['taxonomy']);
                            if( ! empty($taxonomy->labels->singular_name) ) {
                                $taxonomy_label = $taxonomy->labels->singular_name;
                            } else {
                                $taxonomy_label = $taxonomy->label;
                            }
                            $terms_name[$filter['taxonomy']] = array(
                                'name' => apply_filters('berocket_aapf_seo_meta_filtered_taxonomy_label', $taxonomy_label, $taxonomy, $filter), 
                                'values' => array(),
                                'operator' => ( empty($filter['val_arr']['op']) ? 'OR' : $filter['val_arr']['op'] )
                            );
                        }
                        if( ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER' && isset($filter['val_arr']['from']) && isset($filter['val_arr']['to']) ) {
                            $from = $filter['val_arr']['from'];
                            $to   = $filter['val_arr']['to'];
                            $from = ( isset($filter['val_ids'][$from]) && isset($filter['terms'][$filter['val_ids'][$from]]) 
                                ? apply_filters('berocket_aapf_seo_meta_filtered_term_label', $filter['terms'][$filter['val_ids'][$from]]->name, $filter['terms'][$filter['val_ids'][$from]], $filter) 
                                : $from 
                            );
                            $to   = ( isset($filter['val_ids'][$to]) && isset($filter['terms'][$filter['val_ids'][$to]]) 
                                ? apply_filters('berocket_aapf_seo_meta_filtered_term_label', $filter['terms'][$filter['val_ids'][$to]]->name, $filter['terms'][$filter['val_ids'][$to]], $filter) 
                                : $to 
                            );
                            $terms_name[$filter['taxonomy']]['values'][] = $from.' - '.$to;
                        } else {                                
                            if( ! empty($filter['terms']) && is_array($filter['terms']) ) {
                                foreach($filter['terms'] as $term) {
                                    $terms_name[$filter['taxonomy']]['values'][$term->slug] = apply_filters('berocket_aapf_seo_meta_filtered_term_label', $term->name, $term, $filter);
                                }
                            }
                        }
                    } elseif($filter['type'] == 'price') {
                        $new_terms_name = array(
                            'name' => apply_filters('berocket_aapf_seo_meta_filtered_taxonomy_price_label', __('Price', 'woocommerce')),
                            'is_price' => TRUE
                        );
                        if( isset($filter['val_arr']['from']) && isset($filter['val_arr']['to']) ) {
                            $from = $this->wc_price($filter['val_arr']['from']);
                            $to   = $this->wc_price($filter['val_arr']['to']);
                            $new_terms_name['values'] = array(
                                'price' => apply_filters('berocket_aapf_seo_meta_filtered_price_label', wc_format_price_range($from, $to), $filter, array($filter['val_arr']['from'], $filter['val_arr']['to']))
                            );
                        } elseif( ! empty($filter['val_arr']) && is_array($filter['val_arr']) ) {
                            $new_terms_name['values'] = ( empty($filter['val_arr']['op']) ? 'OR' : $filter['val_arr']['op'] );
                            if( isset($filter['val_arr']['op']) ) {
                                unset($filter['val_arr']['op']);
                            }
                            $values = array();
                            foreach($filter['val_arr'] as $val_arr) {
                                if( isset($val_arr['from']) && isset($val_arr['to']) ) {
                                    $from = $this->wc_price($val_arr['from']);
                                    $to   = $this->wc_price($val_arr['to']);
                                    $values[] = apply_filters('berocket_aapf_seo_meta_filtered_price_label', wc_format_price_range($from, $to), $filter, array($val_arr['from'], $val_arr['to']));
                                }
                            }
                            if( ! empty($values ) ) {
                                $new_terms_name['values'] = $values;
                            }
                        }
                        if( ! empty($new_terms_name['values']) ) {
                            $terms_name['wc_price'] = $new_terms_name;
                        }
                    } elseif(in_array($filter['type'], array('sale', 'stock_status'))) {
                        if( ! isset($terms_name[$filter['taxonomy']]) ) {
                            $terms_name[$filter['taxonomy']] = array(
                                'name' => '', 
                                'values' => array(),
                                'operator' => ( empty($filter['val_arr']['op']) ? 'OR' : $filter['val_arr']['op'] ),
                                'is_price' => true
                            );
                        }
                        if( ! empty($filter['terms']) && is_array($filter['terms']) ) {
                            foreach($filter['terms'] as $term) {
                                $terms_name[$filter['taxonomy']]['values'][$term->slug] = apply_filters('berocket_aapf_seo_meta_filtered_term_label', $term->name, $term, $filter);
                            }
                        }
                    }
                }
            }
            $this->terms_filtered = apply_filters('berocket_aapf_seo_meta_filtered_terms', $terms_name);
        }
        public static function wc_price($price) {
            $decimal_separator  = wc_get_price_decimal_separator();
            $thousand_separator = wc_get_price_thousand_separator();
            $decimals           = wc_get_price_decimals();
            $price_format       = get_woocommerce_price_format();
            $currency           = get_woocommerce_currency_symbol();
            $price = number_format( $price, $decimals, $decimal_separator, $thousand_separator );
            return sprintf($price_format, $currency, $price);
        }
        function get_filters_string($text, $section = 'title') {
            if( empty($this->terms_filtered) ) {
                return $text;
            }
            $filters = apply_filters('berocket_aapf_seo_meta_filters_text_before', '', $text, $section, $this->terms_filtered);
            if( empty($filters) ) {
                $filters = $text;
            } else {
                return $filters;
            }
            return apply_filters('berocket_aapf_seo_meta_filters_text_return', $filters, $text, $section, $this->terms_filtered);
        }
        function the_title($title, $id = 0) {
            if( get_queried_object_id() === $id && ! $this->the_title_backtrace_exclude() ) {
                $title = $this->get_filters_string($title, 'header');
                remove_filter('the_title', array($this, 'the_title'), 10, 2);
                remove_filter('woocommerce_page_title', array($this, 'woocommerce_page_title'), 10, 2);
                $this->ready_elements['header'] = true;
            }
            return $title;
        }
        function the_title_backtrace_exclude() {
            $exclude_functions = array(
                'wp_setup_nav_menu_item',
                'wp_nav_menu'
            );
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            foreach($backtrace as $call_func) {
                if( isset($call_func['function']) && in_array($call_func['function'], $exclude_functions) ) {
                    return true;
                }
            }
            return false;
        }
        function woocommerce_page_title($title) {
            $title = $this->get_filters_string($title, 'header');
            remove_filter('the_title', array($this, 'the_title'), 10, 2);
            remove_filter('woocommerce_page_title', array($this, 'woocommerce_page_title'), 10, 2);
            $this->ready_elements['header'] = true;
            return $title;
        }
        function document_title_parts($title) {
            $options = $this->get_options();
            if( isset($title['title']) ) {
                $this->page_title = $title['title'];
            }
            if( ! empty($options['seo_element_title']) ) {
                $title['title'] = $this->get_filters_string($title['title'], 'title');
                $this->ready_elements['title'] = true;
            }
            return $title;
        }
        function wpseo_title($title) {
            $options = $this->get_options();
            $this->page_title = $title;
            if( ! empty($options['seo_element_title']) ) {
                $title = $this->get_filters_string($title, 'title');
            }
            $this->ready_elements['title'] = true;
            return $title;
        }
        function meta_description($description) {
            remove_action('wp_head', array($this, 'wp_head_description'));
            $description = $this->get_filters_string($description, 'description');
            $this->ready_elements['description'] = true;
            return trim($description);
        }
        function wp_head_description() {
            if( ! $this->ready_elements['description'] ) {
                $description = $this->page_title;
                $description = trim($this->get_filters_string($description, 'description'));
                if( ! empty($description) ) {
                    echo '<meta name="description" content="'.$description.'">';
                }
            }
        }
        function get_options() {
            return BeRocket_AAPF::get_aapf_option();
        }
    }
    new BeRocket_AAPF_addon_woocommerce_seo_title();
}
