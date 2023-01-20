<?php
if( ! class_exists('BeRocket_url_parse_page') ) {
    class BeRocket_url_parse_page {
        public $data = false;
        public $data_current = false;
        public $taxonomies = false;
        public $main_class = false;
        public $taxonomy_md5 = false;
        public $query_vars = array();
        function __construct() {
            global $berocket_selected_filters, $berocket_parse_page_obj;
            add_action('bapf_class_ready', array($this, 'init'), 10, 1);
            $berocket_parse_page_obj = $this;
            foreach (glob(__DIR__ . "/url-parse/*.php") as $filename)
            {
                include_once($filename);
            }
            add_action('woocommerce_product_query', array($this, 'woocommerce_product_query'), 99999999, 1);
            add_filter('woocommerce_shortcode_products_query', array( $this, 'woocommerce_shortcode_products_query' ), 900000, 3);
            add_filter('woocommerce_shortcode_products_query', array( $this, 'woocommerce_shortcode_products_query_save_query_late' ), 9000000, 3);
            add_filter('bapf_uparse_apply_filters_to_query_vars', array( $this, 'query_vars_apply_filters' ), 10, 1);
            add_filter('bapf_uparse_apply_filters_to_query_vars_save', array( $this, 'query_vars_apply_filters_save' ), 10, 1);
            add_action('pre_get_posts', array($this, 'add_tax_query'), 90000000, 1);
            add_action('pre_get_posts', array($this, 'add_meta_query'), 90000001, 1);
            add_action('pre_get_posts', array($this, 'add_post_in'), 90000002, 1);
            add_action('pre_get_posts', array($this, 'add_post_not_in'), 90000003, 1);
            add_action('pre_get_posts', array($this, 'add_custom_query_line'), 90000004, 1);
            add_action('pre_get_posts', array($this, 'add_custom_args'), 90000005, 1);
            add_action('pre_get_posts', array($this, 'add_additional_args'), 90000010, 1);
            add_action('posts_clauses', array($this, 'add_custom_query'), 90000000, 2);
            add_filter('berocket_posts_clauses_recount', array($this, 'add_custom_query_without_check'), 90000000, 1);
        }
        public function init($BeRocket_AAPF) {
            $this->main_class = $BeRocket_AAPF;
        }
        function get_taxonomy_md5() {
            if( empty($this->taxonomy_md5) ) {
                global $wpdb;
                $totalmd5 = '';
                $i = 0;
                do {
                    $wpdb->query("SET SESSION group_concat_max_len = 1000000");
                    $md5 = $wpdb->get_var("SELECT MD5(GROUP_CONCAT(slug+term_id)) FROM $wpdb->terms LIMIT ".(20000*$i).', '.(20000*($i+1)) );
                    $totalmd5 .= $md5;
                    $i++;
                } while(! empty($md5));
                $this->taxonomy_md5 = $totalmd5;
            }
            return $this->taxonomy_md5;
        }
        public function woocommerce_product_query($query) {
            $bapf_apply = $query->get('bapf_apply', false);
            if( empty($bapf_apply) ) {
                $query->set('bapf_apply', true);
                $query_vars = $query->query_vars;
                $tax_query_wc_main = WC_Query::get_main_tax_query();
                if( ! empty($tax_query_wc_main) ) {
                    if( ! empty($query_vars['tax_query']) && is_array($query_vars['tax_query']) ) {
                        $tax_query_wc_main = array_merge($tax_query_wc_main, $query_vars['tax_query']);
                    }
                    $query_vars['tax_query'] = $tax_query_wc_main;
                }
                $meta_query_wc_main = WC_Query::get_main_meta_query();
                if( ! empty($meta_query_wc_main) ) {
                    if( ! empty($query_vars['meta_query']) && is_array($query_vars['meta_query']) ) {
                        $meta_query_wc_main = array_merge($meta_query_wc_main, $query_vars['meta_query']);
                    }
                    $query_vars['meta_query'] = $meta_query_wc_main;
                }
                $this->query_vars = $query_vars;
            }
        }
        public function woocommerce_shortcode_products_query($query_vars, $atts = array(), $name = 'products') {
            if( isset($atts['berocket_aapf']) && $atts['berocket_aapf'] === false ) {
                return $query_vars;
            }
            if( apply_filters('berocket_aapf_wcshortcode_is_filtering', ( (! is_shop() && ! is_product_taxonomy() && ! is_product_category() && ! is_product_tag()) || ! empty($atts['berocket_aapf']) ), $query_vars, $atts, $name ) ) {
                $query_vars['bapf_apply'] = true;
                $this->query_vars = $query_vars;
                $query_vars = $this->query_vars_apply_filters($query_vars);
            }
            return $query_vars;
        }
        public function woocommerce_shortcode_products_query_save_query_late( $query_vars, $atts = array(), $name = 'products' ) {
            if( ! empty($query_vars['bapf_apply']) ) {
                $this->save_shortcode_query_vars($query_vars);
            }
            return $query_vars;
        }
        public function save_shortcode_query_vars($query_vars) {
            $br_query_vars = $query_vars;
            if( ! empty($br_query_vars['tax_query']) && is_array($br_query_vars['tax_query']) ) {
                foreach($br_query_vars['tax_query'] as $i => $tax_query_val) {
                    if( ! empty($tax_query_val['taxonomy']) ) {
                        $br_query_vars['tax_query'][$i] = array(
                            $tax_query_val,
                        );
                    }
                }
            }
            global $br_wc_query, $br_aapf_wc_footer_widget, $br_widget_ids, $br_widget_ids_apply;
            $br_widget_ids_apply = $br_widget_ids;
            $br_query_vars['fields'] = 'ids';
            $br_wc_query = $br_query_vars;
            $br_aapf_wc_footer_widget = true;
            add_action( 'wp_footer', array( $this, 'wp_footer_widget'), 99999 );
        }
        public function wp_footer_widget() {
            global $br_widget_ids_apply, $br_wc_query;
            if( isset( $br_widget_ids_apply ) && is_array( $br_widget_ids_apply ) && count( $br_widget_ids_apply ) > 0 ) {
                echo '<div class="berocket_wc_shortcode_fix" style="display: none;">';
                foreach ( $br_widget_ids_apply as $widget ) {
                    $widget['instance']['br_wp_footer'] = true;
                    the_widget( 'BeRocket_new_AAPF_Widget_single', $widget['instance'], $widget['args']);
                }
                echo '</div>';
            }
        }
        public function query_vars_apply_filters($query_vars) {
            $args = $this->query_vars_tax_query($query_vars);
            $this->args_apply_to_query_vars($query_vars, $args);
            $args = $this->query_vars_meta_query($query_vars);
            $this->args_apply_to_query_vars($query_vars, $args);
            $args = $this->query_vars_post_in($query_vars);
            $this->args_apply_to_query_vars($query_vars, $args);
            $args = $this->query_vars_post_not_in($query_vars);
            $this->args_apply_to_query_vars($query_vars, $args);
            $args = $this->query_custom_query_line($query_vars);
            $this->args_apply_to_query_vars($query_vars, $args);
            $args_list = $this->query_custom_args($query_vars);
            foreach($args_list as $args) {
                $this->args_apply_to_query_vars($query_vars, $args);
            }
            $args = apply_filters( 'bapf_uparse_query_vars_apply_filters', array(), $query_vars, $this->get_current() );
            $this->args_apply_to_query_vars($query_vars, $args);
            do_action('bapf_uparse_query_vars_ready', $query_vars);
            return $query_vars;
        }
        public function query_vars_apply_filters_save($query_vars) {
            $query_vars['bapf_apply'] = true;
            $this->query_vars = $query_vars;
            $query_vars = $this->query_vars_apply_filters($query_vars);
            return $query_vars;
        }
        private function query_vars_tax_query($query_vars) {
            if( ! empty($query_vars['bapf_tax_applied']) ) return array();
            $data = $this->get_current();
            $args = array();
            if( ! empty($data['tax_query']) ) {
                if( ! empty($query_vars['tax_query']) && is_array($query_vars['tax_query']) ) {
                    $tax_query = array_merge($query_vars['tax_query'], $data['tax_query']);
                } else {
                    $tax_query = $data['tax_query'];
                }
                $args['tax_query'] = $tax_query;
            }
            $args['bapf_tax_applied'] = true;
            return $args;
        }
        private function query_vars_meta_query($query_vars) {
            if( ! empty($query_vars['bapf_meta_applied']) ) return array();
            $data = $this->get_current();
            $args = array();
            if( ! empty($data['meta_query']) ) {
                if( ! empty($query_vars['meta_query']) && is_array($query_vars['meta_query']) ) {
                    $meta_query = array_merge($query_vars['meta_query'], $data['meta_query']);
                } else {
                    $meta_query = $data['meta_query'];
                }
                $args['meta_query'] = $meta_query;
            }
            $args['bapf_meta_applied'] = true;
            return $args;
        }
        private function query_vars_post_in($query_vars) {
            if( ! empty($query_vars['bapf_postin_applied']) ) return array();
            $data = $this->get_current();
            $args = array();
            if( ! empty($data['posts_in']) && is_array($data['posts_in']) && count($data['posts_in']) > 0 ) {
                $args[ 'post__in' ] = $data['posts_in'];
                if( ! empty($query_vars[ 'post__in' ]) ) {
                    $args[ 'post__in' ] = array_merge($query_vars[ 'post__in' ], $args[ 'post__in' ]);
                }
            }
            $args['bapf_postin_applied'] = true;
            return $args;
        }
        private function query_vars_post_not_in($query_vars) {
            if( ! empty($query_vars['bapf_postnotin_applied']) ) return array();
            $data = $this->get_current();
            $args = array();
            if( ! empty($data['posts_not_in']) && is_array($data['posts_not_in']) && count($data['posts_not_in']) > 0 ) {
                $args[ 'post__not_in' ] = $data['posts_not_in'];
                if( ! empty($query_vars['post__in']) && is_array($query_vars['post__in']) ) {
                    $args[ 'post__in' ] = array_diff($query_vars['post__in'], $args['post__not_in']);
                }
                if( ! empty($query_vars[ 'post__not_in' ]) ) {
                    $args[ 'post__not_in' ] = array_merge($query_vars[ 'post__not_in' ], $args[ 'post__not_in' ]);
                }
            }
            $args['bapf_postnotin_applied'] = true;
            return $args;
        }
        private function query_custom_query_line($query_vars) {
            if( ! empty($query_vars['custom_query_line']) ) return array();
            $data = $this->get_current();
            $args = array();
            $custom_query_lines = array();
            foreach($data['filters'] as $filter) {
                if( ! empty($filter['custom_query_line']) ) {
                    $custom_query_lines[] = $filter['custom_query_line'];
                }
            }
            if( count($custom_query_lines) > 0 ) {
                $args['custom_query_line'] = implode(';', $custom_query_lines);
            }
            return $args;
        }
        private function query_custom_args($query_vars) {
            if( ! empty($query_vars['bapf_customargs_applied']) ) return array();
            $data = $this->get_current();
            $args_list = array();
            $custom_query_lines = array();
            foreach($data['filters'] as $filter) {
                $result = apply_filters('bapf_uparse_query_custom_args_each', null, $this, $filter, $query_vars);
                if( $result !== null ) {
                    $args_list[] = $result;
                }
            }
            $args['bapf_customargs_applied'] = true;
            return $args_list;
        }
        private function args_apply_to_query(&$query, $args) {
            if( is_array($args) ) {
                foreach($args as $name => $value) {
                    $query->set($name, $value);
                }
            }
        }
        private function args_apply_to_query_vars(&$query_vars, $args) {
            if( is_array($args) ) {
                foreach($args as $name => $value) {
                    $query_vars[$name] = $value;
                }
            }
        }
        public function add_tax_query($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            if( ! empty($query->query_vars['bapf_save_query']) ) {
                $this->query_vars = $query->query_vars;
            }
            $args = $this->query_vars_tax_query($query->query_vars);
            $this->args_apply_to_query($query, $args);
        }
        public function add_meta_query($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args = $this->query_vars_meta_query($query->query_vars);
            $this->args_apply_to_query($query, $args);
        }
        public function add_post_in($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args = $this->query_vars_post_in($query->query_vars);
            $this->args_apply_to_query($query, $args);
        }
        public function add_post_not_in($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args = $this->query_vars_post_not_in($query->query_vars);
            $this->args_apply_to_query($query, $args);
        }
        public function add_custom_query_line($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args = $this->query_custom_query_line($query->query_vars);
            $this->args_apply_to_query($query, $args);
        }
        public function add_custom_args($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args_list = $this->query_custom_args($query->query_vars);
            foreach($args_list as $args) {
                $this->args_apply_to_query($query, $args);
            }
        }
        public function add_custom_query($args, $query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return $args;
            $args = $this->add_custom_query_without_check($args);
            if( BeRocket_AAPF::$debug_mode ) {
                if( empty(BeRocket_AAPF::$error_log['filtered_queries']) || ! is_array(BeRocket_AAPF::$error_log['filtered_queries']) ) {
                    BeRocket_AAPF::$error_log['filtered_queries'] = array();
                }
                BeRocket_AAPF::$error_log['filtered_queries'][] = array(
                    'args'  => $args,
                    'query' => $query,
                );
            }
            return $args;
        }
        public function add_additional_args($query) {
            if( ! $this->is_bapf_apply($query->query_vars) ) return;
            $args = apply_filters( 'bapf_uparse_query_vars_apply_filters', array(), $query->query_vars, $this->get_current() );
            $this->args_apply_to_query($query, $args);
            do_action('bapf_uparse_query_vars_ready', $query->query_vars);
            if( ! empty($query->query_vars['bapf_save_query']) ) {
                $this->save_shortcode_query_vars($query->query_vars);
            }
        }
        public function add_custom_query_without_check($args) {
            $data = $this->get_current();
            if( ! empty($data['filters']) && is_array($data['filters']) ) {
                foreach($data['filters'] as $filter) {
                    if( ! empty($filter['custom_query']) ) {
                        $args = call_user_func($filter['custom_query'], $args, $filter);
                    }
                }
            }
            return $args;
        }
        public function get_current() {
            $data = apply_filters('bapf_uparse_get_current', null, $this);
            if( $data !== null ) {
                return $data;
            }
            if( $this->data_current === false ) {
                $this->data_current = $this->parse_line(false);
            }
            if( $this->data === false ) {
                $this->data = $this->data_current;
            }
            return apply_filters('bapf_uparse_get_current_modify', $this->data, $this);
        }
        public function data_check($data = false) {
            if( $data === false ) {
                return $this->get_current();
            }
            foreach($data['filters'] as &$filter) {
                $datafix = 5;
                if( ! isset($filter['val_arr']) ) {
                    $datafix = 1;
                } elseif( ! isset($filter['terms']) ) {
                    $datafix = 2;
                } elseif( ! isset($filter['used']) ) {
                    $datafix = 3;
                }
                switch($datafix) {
                    case 1:
                        $filter = $this->parse_filter_values_each($filter, $data);
                    case 2:
                        $filter = $this->add_terms_to_data_each($filter, $data);
                    case 3:
                        $filter = $this->data_set_filter_used($filter, $data);
                }
            }
            $data = $this->data_generate_global_filtering($data);
            return $data;
        }
        private function data_set_filter_used($filter, $data) {
            $filter = $this->generate_tax_query_each($filter, $data);
            if( ! empty($filter['tax_query']) ) {
                $filter['used'] = 'tax_query';
            } else {
                $filter = $this->generate_meta_query_each($filter, $data);
                if( ! empty($filter['meta_query']) ) {
                    $filter['used'] = 'meta_query';
                } else {
                    $filter = $this->generate_posts_in_each($filter, $data);
                    if( ! empty($filter['posts_in']) && is_array($filter['posts_in']) ) {
                        $filter['used'] = 'posts_in';
                    } else {
                        $filter = $this->generate_posts_not_in_each($filter, $data);
                        if( ! empty($filter['posts_not_in']) && is_array($filter['posts_not_in']) ) {
                            $filter['used'] = 'posts_not_in';
                        } else {
                            $filter = $this->generate_custom_query_each($filter, $data);
                            if( ! empty($filter['custom_query']) && is_array($filter['custom_query']) ) {
                                $filter['used'] = 'custom_query';
                            }
                        }
                    }
                }
            }
            return $filter;
        }
        private function data_generate_global_filtering($data) {
            $data = $this->sort_filters($data);
            $tax_query_global = array();
            $meta_query_global = array();
            $posts_not_in_global = array();
            $posts_in_global = array();
            if( ! empty($data['filters']) && is_array($data['filters']) ) {
                foreach($data['filters'] as &$filter) {
                    if( isset($filter['used']) ) {
                        switch($filter['used']) {
                            case 'tax_query':
                                $tax_query_global[] = $filter['tax_query'];
                                break;
                            case 'meta_query':
                                $meta_query_global[] = $filter['meta_query'];
                                break;
                            case 'posts_in':
                                $posts_in_global = $posts_in_global + $filter['posts_in'];
                                break;
                            case 'posts_not_in':
                                $posts_not_in_global = $posts_not_in_global + $filter['posts_not_in'];
                                break;
                            case 'custom_query':
                                $custom_query_global[] = $filter['custom_query'];
                                break;
                        }
                    }
                }
            }
            if( ! empty($tax_query_global) ) {
                $tax_query_global['relation'] = 'AND';
            }
            $data['tax_query'] = $tax_query_global;
            if( ! empty($meta_query_global) ) {
                $meta_query_global['relation'] = 'AND';
            }
            $data['meta_query'] = $meta_query_global;
            $data['posts_in'] = array_unique($posts_in_global);
            $data['posts_not_in'] = array_unique($posts_not_in_global);
            return apply_filters('bapf_uparse_data_generate_global_filtering_modify', $data, $this);
        }
        private function sort_filters($data) {
            $sort_array = array();
            foreach($data['filters'] as &$filter) {
                $sort_array[] = ( empty($filter['taxonomy']) ? $filter['attr'] : $filter['taxonomy'] );
                if( in_array($filter['type'], array('attribute', 'taxonomy')) ) {
                    if( ! empty($filter['val_arr']) ) {
                        if( isset($filter['val_arr']['op']) ) {
                            $operator = $filter['val_arr']['op'];
                            unset($filter['val_arr']['op']);
                        }
                        if( isset($operator) && $operator == 'SLIDER' ) {
                            $filter['val_arr'] = array(
                                'from' => $filter['val_arr']['from'],
                                'to'   => $filter['val_arr']['to']
                            );
                        } else {
                            sort($filter['val_arr']);
                        }
                        if( isset($operator) ) {
                            $filter['val_arr']['op'] = $operator;
                            unset($operator);
                        }
                    }
                }
                $filter = apply_filters('bapf_uparse_sort_filters_single', $filter, $data);
            }
            if( is_array($sort_array) && is_array($data['filters']) ) {
                array_multisort($sort_array, $data['filters']);
            }
            return $data;
        }
        public function parse_line($link = false) {
            $data = apply_filters('bapf_uparse_parse_line', null, $this, $link);
            if( $data !== null ) {
                return $data;
            }
            $filter_line = $this->parse_get_filter_line($link);
            $data = $this->parse_filter_line_to_array($filter_line);
            $data = $this->parse_filter_values($data);
            $data = $this->add_terms_to_data($data);
            $data = $this->generate_tax_query($data);
            $data = $this->generate_meta_query($data);
            $data = $this->generate_posts_in($data);
            $data = $this->generate_posts_not_in($data);
            $data = $this->generate_custom_query($data);
            $data = $this->data_generate_global_filtering($data);
            $data = apply_filters('bapf_uparse_parse_line_modify', $data, $link);
            if( BeRocket_AAPF::$debug_mode ) {
                if( empty(BeRocket_AAPF::$error_log['url_parse_data']) || ! is_array(BeRocket_AAPF::$error_log['url_parse_data']) ) {
                    BeRocket_AAPF::$error_log['url_parse_data'] = array();
                }
                BeRocket_AAPF::$error_log['url_parse_data'][] = $data;
            }
            return $data;
        }
        public function get_link_from_data($data = false, $link = false, $args = false) {
            if( $data === false ) {
                $data = $this->get_current();
            }
            if( $link === false ) {
                $siteurl = get_site_url();
                $sub_link = "//";
                if( strpos($siteurl, '://') !== FALSE ) {
                    $siteurl = explode('://', $siteurl);
                    if( count($siteurl) == 2 ) {
                        $sub_link = $siteurl[0].'://';
                    }
                }
                $link = $sub_link.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            }
            $result = apply_filters('bapf_uparse_get_link_from_data', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            $link = $this->remove_filters_from_link($link);
            $filters_line = $this->generate_filter_link($data, $args);
            $link = $this->add_filters_to_link($link, $filters_line);
            return $link;
        }
        public function remove_filters_from_link($link) {
            global $wp_rewrite;
            $link = remove_query_arg( apply_filters('bapf_uparse_remove_filters_from_link_arg', 'filters', $this), $link );
            $link = preg_replace( "~paged?/[0-9]+/?~", "", $link );
            $link = preg_replace( "~{$wp_rewrite->pagination_base}/[0-9]+/?~", "", $link );
            $result = apply_filters('bapf_uparse_remove_filters_from_link', null, $this, $link);
            if( $result !== null ) {
                return $result;
            }
            return $link;
        }
        public function generate_filter_link($data, $args = array()) {
            $result = apply_filters('bapf_uparse_generate_filter_link', null, $this, $data, $args);
            if( $result !== null ) {
                return $result;
            }
            $filters_lines = array();
            foreach($data['filters'] as $filter) {
                $filters_line = $this->generate_filter_link_each($filter, $data, $args);
                $filters_lines = array_merge($filters_lines, $filters_line);
            }
            $result = apply_filters('bapf_uparse_generate_filter_link_lines', null, $this, $filters_lines, $data, $args);
            if( $result !== null ) {
                return $result;
            }
            $delimiter = apply_filters('bapf_uparse_generate_filter_link_delimiter', '&', $this, $filters_lines, $data, $args);
            $filter_line = implode($delimiter, $filters_lines);
            return $filter_line;
        }
        public function generate_filter_link_each($filter, $data, $args = array()) {
            $result = apply_filters('bapf_uparse_generate_filter_link_each', null, $this, $filter, $data, $args);
            if( $result !== null ) {
                return $result;
            }
            $values_lines = array();
            if(in_array($filter['type'], array('attribute', 'taxonomy'))) {
                $values_lines = $this->generate_filter_link_each_without_check($filter, $data, $args);
            }
            return $values_lines;
        }
        public function generate_filter_link_each_without_check($filter, $data, $args = array()) {
            $values_lines = array();
            $taxonomy_name = $filter['taxonomy'];
            if($filter['type'] == 'attribute') {
                $taxonomy_name = substr($taxonomy_name, 3);
            }
            $link_elements = apply_filters('bapf_uparse_generate_filter_link_each_taxval_delimiters', array(
                'before_values'  => '[',
                'after_values'   => ']',
            ), $this, $filter, $data);
            $taxonomy_name = apply_filters('bapf_uparse_generate_filter_link_each_taxonomy_name', $taxonomy_name, $this, $filter, $data);
            if( ! empty($filter['val_arr']) ) {
                $filter_lines = $this->generate_filter_val_arr($filter['val_arr'], $filter);
                foreach($filter_lines as $filter_line) {
                    $values_line = $taxonomy_name . $link_elements['before_values'] . $filter_line . $link_elements['after_values'];
                    $values_lines[] = apply_filters('bapf_uparse_generate_filter_link_each_values_line', $values_line, $this, $filter, $data, $link_elements, array(
                        'taxonomy_name' => $taxonomy_name,
                        'filter_line'   => $filter_line
                    ));
                }
            }
            return $values_lines;
        }
        public function generate_filter_val_arr($val_arr, $filter) {
            $result = apply_filters('bapf_uparse_generate_filter_val_arr', null, $this, $val_arr, $filter);
            if( $result !== null ) {
                return $result;
            }
            $delimiter = '-';
            if( isset($val_arr['op']) ) {
                $delimiter = $this->func_operator_to_delimiter($val_arr['op']);
                unset($val_arr['op']);
            }
            $values_lines = array();
            $values = array();
            foreach($val_arr as $value) {
                if( is_array($value) ) {
                    $values_lines_add = $this->generate_filter_val_arr($value, $filter);
                    $values_lines = array_merge($values_lines, $values_lines_add);
                } else {
                    $values[] = $value;
                }
            }
            $values_lines[] = implode($delimiter, $values);
            return $values_lines;
        }
        public function add_filters_to_link($link, $filters_line) {
            $result = apply_filters('bapf_uparse_add_filters_to_link', null, $this, $link, $filters_line);
            if( $result !== null ) {
                return $result;
            }
            if ( ! empty( $filters_line ) ) {
                $link = add_query_arg( apply_filters('bapf_uparse_remove_filters_from_link_arg', 'filters', $this), $filters_line, $link );
            }
            return $link;
        }
        public function modify_data($args, $data = false) {
            if( BeRocket_AAPF::$debug_mode ) {
                if( empty(BeRocket_AAPF::$error_log['url_parse_modify_data']) || ! is_array(BeRocket_AAPF::$error_log['url_parse_modify_data']) ) {
                    BeRocket_AAPF::$error_log['url_parse_modify_data'] = array();
                }
                BeRocket_AAPF::$error_log['url_parse_modify_data'][] = array(
                    'args' => $args,
                    'data' => $data
                );
            }
            $args = array_merge(array('values' => array(), 'type' => 'revert', 'op' => 'AND', 'calculate' => TRUE), $args);
            $data = $this->data_check($data);
            $result = apply_filters('bapf_uparse_modify_data', null, $this, $args, $data);
            if( $result !== null ) {
                return $result;
            }
            $values = (is_array($args['values']) ? $args['values']: array());
            foreach($values as $value_i => $value) {
                $result = apply_filters('bapf_uparse_modify_data_each_precheck', null, $this, $value, $args, $data);
                if( $result !== null ) {
                    $data = $result;
                    unset($values[$value_i]);
                }
            }
            $type = $args['type'];
            $operator = $args['op'];
            $add_not_exist = array();
            $options = $this->main_class->get_option();
            foreach($values as $value_i => &$value) {
                $result = apply_filters('bapf_uparse_modify_data_value_each', null, $this, $value, $args, $data);
                if( $result !== null ) {
                    $value = $result;
                    continue;
                }
                if( is_string($value['value']) ) {
                    $term_add = $this->get_term_by('slug', $value['value'], $value['taxonomy']);
                } else {
                    $term_add = $this->get_term_by('id', $value['value'], $value['taxonomy']);
                }
                if( ! empty($term_add) && ! is_a($term_add, 'WP_Error') ) {
                    $value['term'] = (empty($options['slug_urls']) ? $term_add->term_id : $term_add->slug);
                    $value['id'] = $term_add->term_id;
                } else {
                    unset($values[$value_i]);
                }
            }
            if( isset($value) ) {
                unset($value);
            }
            if( ! empty($data['filters']) && is_array($data['filters']) && count($data['filters']) > 0 ) {
                foreach($data['filters'] as $filter_i => &$filter) {
                    if( ! isset($filter['taxonomy']) ) {
                        BeRocket_error_notices::add_plugin_error(1, 'Cannot detect taxonomy on Data Modify', array(
                            'filter' => $filter
                        ));
                    }
                    $result = apply_filters('bapf_uparse_modify_data_each', null, $this, $filter, $args, $data);
                    if( $result !== null ) {
                        unset($values[$value_i]);
                        $filter = $this->back_generate($result, $data, $args);
                    }
                    foreach($values as $value_i => $value) {
                        if( ! isset($value['taxonomy']) ) {
                            BeRocket_error_notices::add_plugin_error(1, 'Cannot detect taxonomy on Data Modify', array(
                                'value' => $value,
                                'args'  => $args,
                                'data'  => $data
                            ));
                        }
                        $is_modify = $filter['taxonomy'] == $value['taxonomy'] && in_array($filter['type'], array('attribute', 'taxonomy'));
                        if( apply_filters('bapf_uparse_modify_data_each_is_modify', $is_modify, $value, $filter, $args) ) {
                            $position = false;
                            if( isset($filter['val_arr']) && is_array($filter['val_arr']) ) {
                                $position = array_search($value['term'], $filter['val_arr']);
                            }
                            if(($type == 'add' || $type == 'replace' || $type == 'revert') && $position === false) {
                                if( $type == 'replace' ) {
                                    $filter['val_arr'] = array();
                                    $filter['val_ids'] = array();
                                }
                                if( ! isset($filter['val_arr']) || ! is_array($filter['val_arr']) ) {
                                    $filter['val_arr'] = array();
                                }
                                if( ! isset($filter['val_ids']) || ! is_array($filter['val_ids']) ) {
                                    $filter['val_ids'] = array();
                                }
                                $filter['val_arr'][] = $value['term'];
                                $filter['val_ids'][$value['term']] = $value['id'];
                            } elseif(($type == 'remove' || $type == 'revert' || $type == 'replace') && $position !== false) {
                                unset($filter['val_arr'][$position]);
                                if( isset($filter['val_ids']) && is_array($filter['val_ids']) ) {
                                    $position2 = array_search($value['id'], $filter['val_ids']);
                                    if( $position2 !== false ) {
                                        unset($filter['val_ids'][$position2]);
                                    }
                                }
                                if(count($filter['val_arr']) == 0 || (count($filter['val_arr']) == 1 && ! empty($filter['val_arr']['op'])) ) {
                                    unset($data['filters'][$filter_i]);
                                }
                            }
                            unset($values[$value_i]);
                            $filter = $this->back_generate($filter, $data, $args);
                        }
                    }
                }
            }
            if(($type == 'revert' || $type == 'add' || $type == 'replace') && ! empty($values)) {
                $add_values = array();
                foreach($values as $value) {
                    if( empty($add_values[$value['taxonomy']]) ) {
                        $add_values[$value['taxonomy']] = array('values' => array(), 'ids' => array());
                    }
                    $add_values[$value['taxonomy']]['values'][] = $value['term'];
                    $add_values[$value['taxonomy']]['ids'][$value['term']] = $value['id'];
                }
                foreach($add_values as $taxonomy => $add_value) {
                    $values = $add_value['values'];
                    $values['op'] = $operator;
                    $filter_arr = apply_filters('bapf_uparse_modify_data_add_value', array(
                        'val_arr' => $values,
                        'val_ids' => $add_value['ids'],
                        'taxonomy' => $taxonomy
                    ), $args);
                    $filter_arr = $this->back_generate($filter_arr, $data, $args);
                    $data['filters'][] = $filter_arr;
                }
            }
            $data = $this->data_generate_global_filtering($data);
            return $data;
        }
        public function remove_taxonomy($args, $data = false) {
            $args = array_merge(array('taxonomy' => ''), $args);
            $data = $this->data_check($data);
            if( ! empty($args['taxonomy']) && ! empty($data['filters']) && is_array($data['filters']) && count($data['filters']) > 0 ) {
                foreach($data['filters'] as $filter_i => &$filter) {
                    if(apply_filters('bapf_uparse_remove_taxonomy_each', 
                        ($filter['taxonomy'] == $args['taxonomy'] || $args['taxonomy'] === FALSE),
                        $args,
                        $data,
                        $filter
                    ) ) {
                        unset($data['filters'][$filter_i]);
                    }
                }
            }
            $data = $this->data_generate_global_filtering($data);
            return $data;
        }
        public function back_generate($filter, $data, $args = array()) {
            $args = array_merge(array('calculate' => TRUE), $args);
            if(empty($filter['attr']) || empty($filter['type'])) {
                if( substr($filter['taxonomy'], 0, 3) == 'pa_' ) {
                    if( empty($filter['attr']) ) {
                        $filter['attr'] = substr($filter['taxonomy'], 3);
                    }
                    if( empty($filter['type']) ) {
                        $filter['type'] = 'attribute';
                    }
                } else {
                    if( empty($filter['attr']) ) {
                        $filter['attr'] = $filter['taxonomy'];
                    }
                    if( empty($filter['type']) ) {
                        $filter['type'] = 'taxonomy';
                    }
                }
            }
            if(! empty($args['calculate']) ) {
                $filter = $this->add_terms_to_data_each($filter, $data);
                $filter = $this->data_set_filter_used($filter, $data);
            }
            return $filter;
        }
        public function query_vars($query_vars = array(), $data = false) {
            $data = $this->data_check($data);
            $data = apply_filters('bapf_uparse_query_vars', null, $this, $args, $data);
            if( $data !== null ) {
                return $data;
            }
            return $query_vars;
        }
        public function set_default_data($data = false) {
            $data = $this->data_check($data);
            $this->data = apply_filters('bapf_uparse_set_default_data', $data, $this, $data);
            return $data;
        }
        public function reset_data() {
            $this->get_current();
            $this->data = apply_filters('bapf_uparse_reset_data', $this->get_main_data(), $this, $this->data);
            return $this->data;
        }
        public function get_main_data() {
            $this->get_current();
            return $this->data_current;
        }
        public function get_data_errors($data = false) {
            $data = $this->data_check($data);
            $error = apply_filters('bapf_uparse_get_data_errors', null, $this, $data);
            if( $error !== null ) {
                return $error;
            }
            $error = array();
        }
        public function parse_get_filter_line($link = false) {
            $result = apply_filters('bapf_uparse_parse_get_filter_line', null, $this, $link);
            if( $result !== null ) {
                return $result;
            }
            $filter_line = '';
            $filter_var = apply_filters('bapf_uparse_parse1_filter_var', 'filters', $this, $link);
            if( $link !== false ) {
                $parsed_link = wp_parse_url($link);
                if( ! empty($parsed_link['query']) ) {
                    $query_line = explode('&', $parsed_link['query']);
                    foreach($query_line as $query_arg) {
                        $query_arg = explode('=', $query_arg, 2);
                        if( $query_arg[0] == $filter_var ) {
                            $filter_line = $query_arg[1];
                            break;
                        }
                    }
                }
            } elseif( ! empty($_GET[$filter_var]) ) {
                $filter_line = $_GET[$filter_var];
            }
            $filter_line = urlencode($filter_line);
            $filter_line = str_replace('+', urlencode('+'), $filter_line);
            $filter_line = urldecode($filter_line);
            return apply_filters('bapf_uparse_parse_get_filter_line_modify', $filter_line, $this, $link);
        }
        public function parse_filter_line_to_array($filter_line = false) {
            if( $filter_line === false ) {
                $filter_line = $this->parse_get_filter_line();
            }
            $result = apply_filters('bapf_uparse_parse_filter_line_to_array', null, $this, $filter_line);
            if( $result !== null ) {
                return $result;
            }
            $data = array(
                'fullline' => $filter_line,
                'filters' => array()
            );
            $filter_regex = $this->get_regex('filter');
            preg_match_all($filter_regex, $filter_line, $search);
            if( is_array($search) && count($search) > 0 && count($search[0]) > 0 ) {
                for($i = 0; $i < count($search[0]); $i++) {
                    $single_filter = apply_filters('bapf_uparse_parse2_single_filter', null, $this, $search, $i, $filter_line);
                    if( $single_filter !== null ) {
                        $data['filters'][] = $single_filter;
                        continue;
                    }
                    $val = str_replace(array('%20', ' '), '+', $search[3][$i]);
                    $data['filters'][] = array(
                        'line' => $search[1][$i],
                        'attr' => $search[2][$i],
                        'val'  => $val
                    );
                }
            }
            return apply_filters('bapf_uparse_parse_filter_line_to_array_modify', $data, $this, $filter_line);
        }
        public function parse_filter_values($data) {
            $result = apply_filters('bapf_uparse_parse_filter_values', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            foreach($data['filters'] as &$filter) {
                $filter = $this->parse_filter_values_each($filter, $data);
            }
            return apply_filters('bapf_uparse_parse_filter_values_modify', $data, $this);
        }
        public function parse_filter_values_each($filter, $data) {
            $result = apply_filters('bapf_uparse_parse_filter_values_each', null, $this, $filter, $data);
            if( $result !== null ) {
                $filter = $result;
            }
            $taxonomy = $this->func_check_attribute_name($filter['attr']);
            if( is_array($taxonomy) ) {
                $filter['taxonomy'] = $taxonomy['taxonomy'];
                $filter['type']     = $taxonomy['type'];
                $values = $this->func_check_attribute_values($filter['val'], $taxonomy['taxonomy'], $filter);
                if( isset($values['values']) ) {
                    $filter['val_arr'] = $values['values'];
                    $filter['val_arr']['op'] = $values['operator'];
                    $filter['val_ids'] = ( empty($values['value_ids']) ? array() : $values['value_ids'] );
                }
                if( isset($values['error']) ) {
                    if( empty($filter['errors']) || ! is_array($filter['errors']) ) {
                        $filter['errors'] = array();
                    }
                    $filter['errors'][] = $values['error'];
                }
            } elseif(is_a($taxonomy, 'WP_Error') ) {
                if( empty($filter['errors']) || ! is_array($filter['errors']) ) {
                    $filter['errors'] = array();
                }
                $filter['errors'][] = $taxonomy;
            }
            return $filter;
        }
        public function add_terms_to_data($data) {
            $result = apply_filters('bapf_uparse_add_terms_to_data', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            foreach($data['filters'] as &$filter) {
                $filter = $this->add_terms_to_data_each($filter, $data);
            }
            return apply_filters('bapf_uparse_add_terms_to_data_modify', $data, $this);
        }
        private function add_terms_to_data_each($filter, $data) {
            $result = apply_filters('bapf_uparse_add_terms_to_data_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            if( ! empty($filter['val_ids']) ) {
                $custom_terms = apply_filters('bapf_uparse_add_terms_to_data_each_terms', null, $this, $filter, $data);
                if( $custom_terms === null ) {
                    $terms = get_terms(array('include' => $filter['val_ids']));
                } else {
                    $terms = $custom_terms;
                }
                $filter['terms'] = array();
                if( is_array($terms) ) {
                    foreach($terms as $term) {
                        $filter['terms'][$term->term_id] = $term;
                    }
                }
            }
            return $filter;
        }
        public function generate_tax_query($data) {
            $result = apply_filters('bapf_uparse_generate_tax_query', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            $tax_query_global = array();
            foreach($data['filters'] as &$filter) {
                $filter = $this->generate_tax_query_each($filter, $data);
                if( ! empty($filter['tax_query']) ) {
                    $tax_query_global[] = $filter['tax_query'];
                    $filter['used'] = 'tax_query';
                }
            }
            if( ! empty($tax_query_global) ) {
                $tax_query_global['relation'] = 'AND';
            }
            $data['tax_query'] = $tax_query_global;
            return apply_filters('bapf_uparse_generate_tax_query_modify', $data, $this);
        }
        private function generate_tax_query_each($filter, $data) {
            $result = apply_filters('bapf_uparse_generate_tax_query_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            $tax_query = array();
            if( ! empty($filter['type']) && ( $filter['type'] == 'attribute' || $filter['type'] == 'taxonomy' ) ) {
                if( count($filter['val_arr']) > 0 ) {
                    $tax_query = $this->func_generate_tq_single($filter['val_arr'], $filter);
                }
                $filter['tax_query'] = $tax_query;
            }
            return $filter;
        }
        public function generate_meta_query($data) {
            $result = apply_filters('bapf_uparse_generate_meta_query', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            $meta_query_global = array();
            foreach($data['filters'] as &$filter) {
                $filter = $this->generate_meta_query_each($filter, $data);
                if( ! empty($filter['meta_query']) ) {
                    $meta_query_global[] = $filter['meta_query'];
                    $filter['used'] = 'meta_query';
                }
            }
            if( ! empty($meta_query_global) ) {
                $meta_query_global['relation'] = 'AND';
            }
            $data['meta_query'] = $meta_query_global;
            return apply_filters('bapf_uparse_generate_meta_query_modify', $data, $this);
        }
        private function generate_meta_query_each($filter, $data) {
            $result = apply_filters('bapf_uparse_generate_meta_query_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            return $filter;
        }
        public function generate_posts_in($data) {
            $result = apply_filters('bapf_uparse_generate_posts_in', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            $posts_in_global = array();
            foreach($data['filters'] as &$filter) {
                $filter = $this->generate_posts_in_each($filter, $data);
                if( ! empty($filter['posts_in']) && is_array($filter['posts_in']) ) {
                    $posts_in_global = $posts_in_global + $filter['posts_in'];
                    $filter['used'] = 'posts_in';
                }
            }
            $data['posts_in'] = array_unique($posts_in_global);
            return $data;
        }
        public function generate_posts_in_each($filter, $data) {
            $result = apply_filters('bapf_uparse_generate_posts_in_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            return $filter;
        }
        public function generate_posts_not_in($data) {
            $result = apply_filters('bapf_uparse_generate_posts_not_in', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            $posts_not_in_global = array();
            foreach($data['filters'] as &$filter) {
                $filter = $this->generate_posts_not_in_each($filter, $data);
                if( ! empty($filter['posts_not_in']) && is_array($filter['posts_not_in']) ) {
                    $posts_not_in_global = $posts_not_in_global + $filter['posts_not_in'];
                    $filter['used'] = 'posts_not_in';
                }
            }
            $data['posts_not_in'] = array_unique($posts_not_in_global);
            return $data;
        }
        public function generate_posts_not_in_each($filter, $data) {
            $result = apply_filters('bapf_uparse_generate_posts_not_in_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            return $filter;
        }
        public function generate_custom_query($data) {
            $result = apply_filters('bapf_uparse_generate_custom_query', null, $this, $data);
            if( $result !== null ) {
                return $result;
            }
            foreach($data['filters'] as &$filter) {
                $filter = $this->generate_custom_query_each($filter, $data);
                if( ! empty($filter['custom_query']) && is_array($filter['custom_query']) ) {
                    $filter['used'] = 'custom_query';
                }
            }
            return $data;
        }
        public function generate_custom_query_each($filter, $data) {
            $result = apply_filters('bapf_uparse_generate_custom_query_each', null, $this, $filter, $data);
            if( $result !== null ) {
                return $result;
            }
            return $filter;
        }

        public function func_generate_tq_single($val_arr, $filter) {
            $result = apply_filters('bapf_uparse_func_generate_tq_single', null, $this, $val_arr, $filter);
            if( $result !== null ) {
                return $result;
            }
            $operator = 'OR';
            if( isset($val_arr['op']) ) {
                $operator = $val_arr['op'];
                unset($val_arr['op']);
            }
            $tax_query = array();
            if( count($val_arr) > 0 ) {
                $term_ids = array();
                $additional = array();
                foreach($val_arr as $value) {
                    if( is_array($value) ) {
                        $element = $this->func_generate_tq_single($value, $filter);
                        if( ! empty($element) ) {
                            $additional[] = $element;
                        }
                    } elseif( isset($filter['val_ids'][$value]) ) {
                        $term_ids[] = $filter['val_ids'][$value];
                    }
                }
                if( count($additional) > 0 ) {
                    $tax_query = $additional;
                    $tax_query['relation'] = $operator;
                }
                if( count($term_ids) > 0 ) {
                    if( $operator == 'AND' ) {
                        $ids_tax_query = array('relation' => 'AND');
                        foreach($term_ids as $term_id) {
                            $ids_tax_query[] = apply_filters('bapf_uparse_func_generate_tq_single_tax_query', array(
                                'taxonomy'  => $filter['taxonomy'],
                                'field'     => 'term_id',
                                'terms'     => $term_id,
                                'operator'  => 'IN',
                            ), $this, $val_arr, $filter);
                        }
                    } else {
                        $ids_tax_query = apply_filters('bapf_uparse_func_generate_tq_single_tax_query', array(
                            'taxonomy'  => $filter['taxonomy'],
                            'field'     => 'term_id',
                            'terms'     => $term_ids,
                            'operator'  => ($operator == 'AND' ? 'AND' : 'IN')
                        ), $this, $val_arr, $filter);
                    }
                    if(empty($tax_query) ) {
                        $tax_query = $ids_tax_query;
                    } else {
                        $tax_query[] = $ids_tax_query;
                    }
                }
            }
            return $tax_query;
        }
        public function func_get_taxonomies() {
            if( $this->taxonomies === false ) {
                $taxonomies = get_object_taxonomies('product');
                $this->taxonomies = array();
                foreach($taxonomies as $taxonomy) {
                    $this->taxonomies[$taxonomy] = $taxonomy;
                }
            }
            return apply_filters('bapf_uparse_func_get_taxonomies_modify', $this->taxonomies, $this);
        }
        public function func_check_attribute_name($attribute_name) {
            $attribute_name = berocket_wpml_attribute_untranslate($attribute_name);
            $result = apply_filters('bapf_uparse_func_check_attribute_name', null, $this, $attribute_name);
            if( $result !== null ) {
                return $result;
            }
            $result = false;
            $taxonomies = $this->func_get_taxonomies();
            $taxonomy = false;
            $type = false;
            if( isset($taxonomies['pa_'.$attribute_name]) ) {
                $taxonomy = 'pa_'.$attribute_name;
                $type = 'attribute';
            } elseif( isset($taxonomies[$attribute_name]) ) {
                $taxonomy = $attribute_name;
                $type = 'taxonomy';
            } else {
                return new WP_Error( 'bapf_uparse', __('Taxonomy do not exist: ', 'BeRocket_AJAX_domain').$attribute_name );
            }
            if($taxonomy !== false) {
                $result = array(
                    'taxonomy' => $taxonomy,
                    'type'     => $type
                );
            }
            return $result;
        }
        public function func_check_attribute_values($values_line, $taxonomy, $filter, $args = array()) {
            if( ! is_array($args) ) $args = array();
            $options = $this->main_class->get_option();
            $args = array_merge(array(
                'field' => (empty($options['slug_urls']) ? 'ids' : 'slug'),
            ), $args);
            $result = apply_filters('bapf_uparse_func_check_attribute_values', null, $this, $values_line, $taxonomy, $filter, $args);
            if( $result !== null ) {
                return $result;
            }
            $custom_terms = apply_filters('bapf_uparse_func_check_attribute_values_terms', null, $this, $values_line, $taxonomy, $filter, $args);
            if($custom_terms === null) {
                $terms = $this->func_get_terms_slug_id($taxonomy);
            } else {
                $terms = $custom_terms;
            }
            $data = false;
            if( is_array($terms) && count($terms) > 0 ) {
                if( $args['field'] == 'slug' ) {
                    $terms = array_flip($terms);
                }
                $values_regex = $this->get_regex('values');
                preg_match_all($values_regex, $values_line, $values);
                if( count($values) > 0 && count($values[0]) > 0 ) {
                    $value_ids = array();
                    $new_values = $values[0];
                    $terms_correct = array();
                    $values_not_exist = array();
                    $delimiter = false;
                    $delimiter_values = $this->func_get_delimiter_operator_array();
                    do {
                        $values = $new_values;
                        $new_values = array();
                        $count_terms_correct = count($terms_correct);
                        do {
                            $check_term = implode($values);
                            $check_decoded = array(
                                $check_term,
                                strtolower($check_term),
                                urldecode($check_term),
                                urlencode($check_term),
                                strtolower(urlencode($check_term))
                            );
                            foreach($check_decoded as $check_d) {
                                if( isset($terms[$check_d]) ) {
                                    $check_term = $check_d;
                                    break;
                                }
                            }
                            if( isset($terms[$check_term]) ) {
                                $terms_correct[] = $check_term;
                                if( $args['field'] == 'slug' ) {
                                    $value_ids[$check_term] = $terms[$check_term];
                                } else {
                                    $value_ids[$check_term] = $check_term;
                                }
                                if( $delimiter === false ) {
                                    if(count($new_values) > 0) {
                                        $delimiter = array_shift($new_values);
                                        if( ! isset($delimiter_values[$delimiter]) ) {
                                            array_unshift($new_values, $delimiter);
                                            $delimiter = false;
                                        }
                                    } elseif(count($values_not_exist) > 0) {
                                        $delimiter = array_pop($values_not_exist);
                                        if( ! isset($delimiter_values[$delimiter]) ) {
                                            array_push($values_not_exist, $delimiter);
                                            $delimiter = false;
                                        }
                                    }
                                }
                                break;
                            } else {
                                array_unshift($new_values, array_pop($values));
                            }
                        } while(count($values) > 0);
                        if( count($terms_correct) == $count_terms_correct && count($new_values) > 0 ) {
                            $values_not_exist[] = array_shift($new_values);
                        }
                    } while(count($new_values) > 0);
                    if( $delimiter === false ) {
                        $delimiter = '-';
                    }
                    $data = array(
                        'values'    => $terms_correct,
                        'value_ids' => $value_ids,
                        'operator'  => $this->func_delimiter_to_operator($delimiter)
                    );
                    if( count($values_not_exist) > 0 ) {
                        $data['error'] = new WP_Error( 'bapf_uparse', __('Values not exist: ', 'BeRocket_AJAX_domain').implode($values_not_exist), array(
                            'terms' => $terms
                        ));
                    }
                }
            }
            return apply_filters('bapf_uparse_func_check_attribute_values_modify', $data, $this, $values_line, $taxonomy, $filter, $args);
        }
        public function func_get_delimiter_operator_array() {
            return apply_filters('bapf_uparse_func_delimiter_to_operator', array(
                '+' => 'AND',
                '-' => 'OR',
                '_' => 'SLIDER'
            ));
        }
        public function func_delimiter_to_operator($delimiter = '-') {
            $convert = $this->func_get_delimiter_operator_array();
            if( isset($convert[$delimiter]) ) {
                return $convert[$delimiter];
            } else {
                return false;
            }
        }
        public function func_operator_to_delimiter($operator) {
            $convert = $this->func_get_delimiter_operator_array();
            $convert = array_flip($convert);
            if( isset($convert[$operator]) ) {
                return $convert[$operator];
            } else {
                return false;
            }
        }
        public function get_term_by($field, $value, $taxonomy) {
            $result = apply_filters('bapf_uparse_get_terms', null, $this, array('taxonomy' => $taxonomy));
            if( $result !== null ) {
                if( is_array($result) && count($result) > 0 ) {
                    foreach($result as $term) {
                        if( 
                            ($field == 'id'   && $term->term_id == $value ) ||
                            ($field == 'slug' && $term->slug    == $value )
                        ) {
                            return $term;
                        }
                    }
                }
                return false;
            }
            return get_term_by($field, $value, $taxonomy);
        }
        public function get_terms($args, $custom = true) {
            if( $custom ) {
                $result = apply_filters('bapf_uparse_get_terms', null, $this, $args);
                if( $result !== null ) {
                    if(! empty($args['include'])) {
                        if( ! is_array($args['include']) ) {
                            $args['include'] = array($args['include']);
                        }
                        $terms = array();
                        foreach($result as $term) {
                            if(array_search($term->term_id, $args['include']) !== FALSE) {
                                $terms[] = $term;
                            }
                        }
                        $result = $terms;
                    }
                    if(! empty($args['exclude'])) {
                        if( ! is_array($args['exclude']) ) {
                            $args['exclude'] = array($args['exclude']);
                        }
                        $terms = array();
                        foreach($result as $term) {
                            if(array_search($term->term_id, $args['exclude']) === FALSE) {
                                $terms[] = $term;
                            }
                        }
                        $result = $terms;
                    }
                    if(! empty($args['fields'])) {
                        $result = $this->get_correct_field($result, $args['fields']);
                    }
                    return $result;
                }
            }
            if( ! empty($args['fields']) ) {
                $fields = $args['fields'];
                unset($args['fields']);
            }
            $terms = berocket_aapf_get_terms( $args, array('hierarchical' => true, 'disable_recount' => true, 'disable_hide_empty' => true) );
            if( isset($fields) ) {
                $terms = $this->get_correct_field($terms, $fields);
            }
            return $terms;
        }
        public function get_correct_field($terms, $field) {
            $result = array();
            foreach($terms as $term) {
                switch($field) {
                    case 'ids':
                        $result[] = $term->term_id;
                        break;
                    case 'names':
                        $result[] = $term->name;
                        break;
                    case 'count':
                        $result[] = $term->count;
                        break;
                    case 'id=>parent':
                        $result[$term->term_id] = $term->parent;
                        break;
                    case 'id=>slug':
                        $result[$term->term_id] = $term->slug;
                        break;
                    case 'id=>name':
                        $result[$term->term_id] = $term->name;
                        break;
                    default:
                        $result[] = $term;
                }
            }
            return $result;
        }
        public function func_get_terms_slug_id($taxonomy) {
            $terms_data = br_get_cache($taxonomy, 'bapf_uparse_get_terms');
            $md5 = $this->get_taxonomy_md5();
            if( ! empty($terms_data) ) {
                if( is_array($terms_data) && isset($terms_data['md5']) && $terms_data['md5'] == $md5 && ! empty($terms_data['data']) ) {
                    $terms = $terms_data['data'];
                }
            }
            if( empty($terms) ) {
                $terms = $this->get_terms(array(
                    'fields'    => 'id=>slug',
                    'hide_empty' => false,
                    'taxonomy'   => $taxonomy
                ));
                br_set_cache($taxonomy, array('md5' => $md5, 'data' => $terms), 'bapf_uparse_get_terms', 43200);
            }
            return $terms;
        }
        public function get_regex($return = false) {
            $regex = apply_filters('bapf_uparse_regex', array(
                'filter' => '/((%val_sym%)\[(%val_sym%)\])(?:$|\|)/u',
                'values' => '/[^%delimiters%]+|[%delimiters%]/u',
                'replacements' => array(
                    '%val_sym%'    => '[%\w+_*-]+',
                    '%delimiters%' => '\+_-'
                )
            ), $this);
            foreach($regex as $type => &$regex_single) {
                if($type != 'replacements') {
                    $regex_single = str_replace(array_keys($regex['replacements']), array_values($regex['replacements']), $regex_single);
                }
            }
            if($return === false) {
                return $regex;
            } else {
                return ( empty($regex[$return]) ? '' : $regex[$return]);
            }
        }
        public function is_bapf_apply($query_vars) {
            return ( ! empty($query_vars['bapf_apply']) && (empty($query_vars['post_type']) || $this->is_query_product($query_vars['post_type'])) );
        }
        public function is_query_product($post_type) {
            if( is_array($post_type) && count($post_type) > 0 ) {
                foreach($post_type as $type) {
                    if($type == 'product') {
                        return true;
                    }
                }
                return false;
            } else {
                return $post_type == 'product';
            }
        }
    }
    new BeRocket_url_parse_page();
}
