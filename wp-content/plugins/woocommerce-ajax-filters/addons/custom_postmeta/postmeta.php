<?php
class BeRocket_aapf_add_postmeta_filters {
    function __construct() {
        add_filter('berocket_filter_filter_type_array', array($this, 'filter_type'), 10000, 1);
        add_action('braapf_single_filter_filter_type', array($this, 'filter_type_additional'), 10000, 2);
        add_filter('berocket_aapf_get_terms_args', array($this, 'get_terms_args'), 10000, 3);
        add_filter('berocket_aapf_get_terms_filter_before', array($this, 'get_terms'), 10000, 3);
        add_filter('bapf_uparse_func_check_attribute_name', array($this, 'check_attribute_name'), 10000, 3);
        add_filter('bapf_uparse_get_terms', array($this, 'uparse_get_terms'), 10000, 3);
        add_filter('bapf_uparse_generate_meta_query_each', array($this, 'generate_meta_query'), 10000, 4);
        add_filter('braapf_generate_taxonomy_name_for_select', array($this, 'taxonomy_name_for_select'), 10000, 2);
        add_filter('braapf_get_data_taxonomy_from_post_before', array($this, 'taxonomy_from_post_before'), 10000, 2);
        add_filter('bapf_uparse_paid_attr_slider_apply', array($this, 'attr_slider_apply'), 10000, 6);
        add_filter('bapf_uparse_paid_attr_slider_taxonomy', array($this, 'attr_slider_taxonomy'), 10000, 2);
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'slider_selected'), 15, 4);
        add_filter('bapf_uparse_add_terms_to_data_each_terms', array($this, 'uparse_terms'), 100, 4);
        add_filter('bapf_uparse_func_check_attribute_values_terms', array($this, 'ids_slug'), 100, 6);
        //Color save
        add_filter('berocket_filter_br_widget_color_types', array($this, 'widget_color_types'));
        add_filter('bapf_widget_func_color_listener_save', array($this, 'color_listener_save'), 10, 4);
        add_filter('berocket_filter_br_widget_color_name', array($this, 'color_listener_attribute'), 10, 4);
        add_filter('berocket_aapf_color_term_select_metadata', array($this, 'select_metadata'), 10, 3);
    }
    function filter_type($filter_type) {
        $filter_type = berocket_insert_to_array(
            $filter_type,
            'attribute',
            array(
                'custom_postmeta' => array(
                    'name' => __('Custom Post Meta', 'BeRocket_AJAX_domain'),
                    'sameas' => 'custom_postmeta',
                    'optionsameas' => 'custom_taxonomy',
                    'templates' => array('checkbox', 'slider', 'new_slider', 'select', 'datepicker'),
                    'specific'  => array('', 'color', 'image')
                ),
            )
        );
        return $filter_type;
    }
    function filter_type_additional($settings_name, $braapf_filter_settings) {
        $custom_postmeta_list = $this->get_custom_postmeta();
        $custom_postmeta = br_get_value_from_array($braapf_filter_settings, 'custom_postmeta', '');
        echo '<div class="braapf_custom_postmeta braapf_half_select_full">';
            echo '<label for="braapf_custom_postmeta">' . __('Custom Post Meta', 'BeRocket_AJAX_domain') . '</label>';
            echo '<select id="braapf_custom_postmeta" name="'.$settings_name.'[custom_postmeta]">';
            foreach( $custom_postmeta_list as $opt => $postmeta_list ) {
                echo '<optgroup label="'.$this->get_opt_name($opt).'">';
                foreach ( $postmeta_list as $value => $data ) {
                    echo '<option';
                    foreach($data as $data_key => $data_val) {
                        if( $data_val !== "" ) {
                            echo ' data-'.$data_key.'="'.$data_val.'"';
                        }
                    }
                    echo ( $custom_postmeta == $value ? ' selected' : '' ) . ' value="' . $value . '">' . $data['name'] . '</option>';
                }
                echo '</optgroup>';
            }
            echo '</select>';
        echo '</div>';
        ?>
        <script>
        berocket_show_element('.braapf_custom_postmeta', '{#braapf_filter_type} == "custom_postmeta"');
        jQuery(document).on('braapf_get_current_taxonomy_name', function() {
            berocket_show_element_hooked_data.push('#braapf_custom_postmeta');
        });
        </script>
        <?php
    }
    function get_custom_postmeta() {
        global $wpdb;
        $data = array();
        $acf = array();
        $remove_meta = array();
        if( function_exists('acf_get_field_groups') ) {
            $field_groups = acf_get_field_groups( array(
                'post_type' => 'product',
            ) );
            if ( is_array( $field_groups ) ) {
                foreach ( $field_groups as $group ) {
                    $fields = acf_get_fields($group);
                    if( is_array($fields) ) {
                        foreach($fields as $field) {
                            $acf[$field['name']] = array(
                                'name' => $field['label'],
                                'hierarchical' => 0
                            );
                            $remove_meta[] = $field['name'];
                            $remove_meta[] = '_'.$field['name'];
                        }
                    }
                }
            }
        }
        if( count($acf) > 0 ) {
            $data['acf'] = $acf;
        }
        $postmetas = $wpdb->get_col( "SELECT {$wpdb->postmeta}.meta_key 
                        FROM {$wpdb->postmeta} 
                        JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
                        WHERE {$wpdb->posts}.post_type = 'product'
                        GROUP BY {$wpdb->postmeta}.meta_key");
        if( is_array($postmetas) && count($postmetas) > 0 && count($remove_meta) > 0 ) {
            $postmetas = array_diff($postmetas, $remove_meta);
        }
        if( is_array($postmetas) && count($postmetas) > 0 ) {
            $data['postmeta'] = array();
            foreach($postmetas as $postmeta) {
                $data['postmeta'][$postmeta] = array(
                    'name' => $postmeta,
                    'hierarchical' => 0
                );
            }
        }
        return $data;
    }
    function get_opt_name($opt) {
        $opt_names = array(
            'acf' => __('Advanced Custom Fields', 'BeRocket_AJAX_domain'),
            'postmeta' => __('Post Meta', 'BeRocket_AJAX_domain'),
        );
        if(isset($opt_names[$opt])) {
            return $opt_names[$opt];
        } else {
            return $opt;
        }
    }
    function get_terms_args($get_terms_args, $instance, $args) {
        if( $instance['filter_type'] == 'custom_postmeta' ) {
            $get_terms_args['custom_postmeta'] = true;
            $get_terms_args['taxonomy'] = $instance['custom_postmeta'];
        }
        return $get_terms_args;
    }
    function get_terms($terms, $args, $additional) {
        if( ! empty($args['custom_postmeta']) ) {
            if( ! empty($additional) && ! empty($additional['disable_recount']) ) {
                $terms = $this->get_postmeta($args['taxonomy']);
            } else {
                $terms = $this->get_postmeta_recount($args['taxonomy'], $args, $additional);
            }
            $terms = $this->modify_get_terms($terms, $args);
        }
        return $terms;
    }
    function build_terms_list($post_metas, $name, $exclude_same = true) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $post_meta_terms = array();
        $slugs = array();
        if( is_array($post_metas) ) {
            foreach($post_metas as $post_meta) {
                $meta_name = ( property_exists($post_meta, 'meta_name') ? $post_meta->meta_name : $this->style_name($post_meta->meta_value) );
                $meta_slug = $this->style_slug($post_meta->meta_value);
                $meta_id   = ( property_exists($post_meta, 'meta_id') ? intval($post_meta->meta_id) : $meta_slug );
                if( ! in_array($meta_slug, $slugs) || ! $exclude_same ) {
                    $slugs[] = $meta_slug;
                    $meta_count = intval($post_meta->count);
                    array_push( $post_meta_terms, (object) array( 
                        'term_id'           => $meta_id,
                        'term_taxonomy_id'  => $meta_id,
                        'name'              => $meta_name,
                        'slug'              => $meta_slug,
                        'value'             => ( empty($options['slug_urls']) ? $meta_id : $meta_slug ),
                        'taxonomy'          => 'cpm_'.$name,
                        'count'             => $meta_count,
                        'meta_value'        => $post_meta->meta_value,
                        'custom_postmeta'   => true,
                        'depth'             => 0
                    ) );
                }
            }
        }
        return $post_meta_terms;
    }
    function get_postmeta_recount($name, $args, $additional) {
        $post_metas = $this->query_recount($name, $args, $additional);
        return $this->build_terms_list($post_metas, $name);
    }
    function get_postmeta($name, $exclude_same = true) {
        global $wpdb;
        $query = $this->get_main_query();
        $query = $wpdb->prepare($query, $name);
        $post_metas = $wpdb->get_results($query);
        return $this->build_terms_list($post_metas, $name, $exclude_same);
    }
    function get_main_query() {
        global $wpdb;
        return apply_filters('berocket_aapf_postmeta_main_query', "SELECT meta_value, count(meta_value) as count FROM {$wpdb->postmeta}
JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
WHERE meta_key LIKE %s AND {$wpdb->posts}.post_type = 'product'
GROUP BY meta_value ORDER BY meta_value");
    }
    function query_recount($postmeta, $args = array(), $additional = array()) {
        global $wpdb;
        $options = BeRocket_AAPF::get_aapf_option();
        $use_filters = braapf_filters_must_be_recounted();
        //NEED TO CHECK
        $taxonomy_data = BeRocket_AAPF_faster_attribute_recount::get_query_for_calculate(array(
            'use_filters' => $use_filters,
            'taxonomy_remove' => (empty($additional['operator']) || strtoupper($additional['operator']) == 'OR' ? 'cpm_'.$postmeta : FALSE),
            'add_tax_query' => ( empty($additional['additional_tax_query']) ? array() : $additional['additional_tax_query'] ),
            'taxonomy_data'   => $additional
        ));
        $query = $taxonomy_data['query'];
        $query['select']['elements'] = array(
            'meta_value' => "brpm_recount.meta_value",
            'meta_count' => "count(DISTINCT {$wpdb->posts}.ID) as count"
        );
        $query['join']['brpm_recount'] = "RIGHT JOIN {$wpdb->postmeta} as brpm_recount ON {$wpdb->posts}.ID = brpm_recount.post_id";
        $query['group'] = 'GROUP BY brpm_recount.meta_value';
        $query['where']['brpm_recount'] = $wpdb->prepare('AND brpm_recount.meta_key = %s AND brpm_recount.meta_value != ""', $postmeta);
        $query['order']    = " ORDER BY meta_value";
        $query             = apply_filters('berocket_aapf_recount_postmeta_query', $query, $taxonomy_data, $postmeta);
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
            return (array)$result;
        }
    }
    
    function modify_get_terms($terms, $args) {
        if(! empty($args['include']) || ! empty($args['exclude']) ) {
            if( ! empty($args['include']) && ! is_array($args['include']) ) {
                if( is_string($args['include']) ) {
                    $args['include'] = explode(',', $args['include']);
                } else {
                    $args['include'] = array();
                }
            }
            if( ! empty($args['exclude']) && ! is_array($args['exclude']) ) {
                if( is_string($args['exclude']) ) {
                    $args['exclude'] = explode(',', $args['exclude']);
                } else {
                    $args['exclude'] = array();
                }
            }
            $new_terms = array();
            foreach($terms as $term) {
                if(
                    ( empty($args['include']) || in_array($term->term_id, $args['include']) )
                 && ( empty($args['exclude']) || ! in_array($term->term_id, $args['exclude']) )
                ) {
                    $new_terms[] = $term;
                }
            }
            $terms = $new_terms;
        }
        if( ! empty($args['orderby']) ) {
            $order_term = array();
            foreach($terms as $term) {
                $order_term[] = $term->name;
            }
            $sort = ( $args['orderby'] == 'name' ? SORT_STRING : SORT_NUMERIC );
            array_multisort($order_term, $terms, $sort);
        }
        if( ! empty($args['order']) && $args['order'] == 'DESC' ) {
            $terms = array_reverse($terms);
        }
        return $terms;
    }
    function uparse_get_terms($terms, $instance, $args) {
        if( ! empty($args['taxonomy']) && substr($args['taxonomy'], 0, 4) == 'cpm_' ) {
            $terms = $this->get_postmeta(substr($args['taxonomy'],4));
        }
        return $terms;
    }
    function style_name($text) {
        $text = str_replace(array('_'), array(' '), $text);
        $text = trim($text);
        $text = ucfirst($text);
        return $text;
    }
    function style_slug($text) {
        $text = sanitize_title($text);
        return $text;
    }
    function convert_to_postval($taxonomy, $slug) {
        $post_metas = $this->get_postmeta($taxonomy, false);
        $lines = array();
        foreach($post_metas as $post_meta) {
            if( $post_meta->value == $slug ) {
                $lines[] = $post_meta->meta_value;
            }
        }
        return $lines;
    }
    function check_attribute_name($result, $instance, $attribute_name) {
        if( ! empty($attribute_name) && substr($attribute_name, 0, 4) == 'cpm_' ) {
            $result = array(
                'taxonomy' => $attribute_name,
                'type'     => 'custom_postmeta'
            );
        }
        return $result;
    }
    function generate_meta_query($result, $instance, $filter, $data) {
        if( apply_filters('bapf_uparse_generate_meta_query_postmeta_meta_query_use', ($result === NULL && ! empty($filter['type']) && $filter['type'] == 'custom_postmeta'), $result, $instance, $filter, $data) ) {
            $taxonomy = substr($filter['taxonomy'],4);
            $lines = array();
            foreach($filter['val_ids'] as $val_id => $val) {
                $lines = array_merge($lines, $this->convert_to_postval($taxonomy, $val_id));
            }
            if( ! empty($lines) ) {
                $operator = ( (! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'AND') ? 'AND' : 'OR' );
                $meta_query = array('relation' => $operator);
                foreach($lines as $line) {
                    $meta_query[] = array(
                        'key' => $taxonomy,
                        'value' => $line
                    );
                }
                $result = $filter;
                $result['meta_query'] = $meta_query;
            }
        }
        return $result;
    }
    function taxonomy_name_for_select($args, $br_product_filter) {
        if( ! empty($br_product_filter) && ! empty($br_product_filter['filter_type']) && $br_product_filter['filter_type'] == 'custom_postmeta' && ! empty($br_product_filter['custom_postmeta']) ) {
            $args['taxonomy'] = sanitize_text_field($br_product_filter['custom_postmeta']);
            $args['custom_postmeta'] = true;
        }
        return $args;
    }
    function taxonomy_from_post_before($result, $post_data) {
        if( $post_data['filter_type'] == 'custom_postmeta' ) {
            $result = 'cpm_'.sanitize_text_field($post_data['custom_postmeta']);
        }
        return $result;
    }
    function attr_slider_apply($is_slider, $result, $instance, $values_line, $taxonomy, $filter) {
        return $is_slider || ( $result['operator'] == 'SLIDER' && in_array($filter['type'], array('custom_postmeta')) );
    }
    function attr_slider_taxonomy($taxonomy, $filter) {
        if( $filter['type'] == 'custom_postmeta' ) {
            $taxonomy = substr($taxonomy, 4);
        }
        return $taxonomy;
    }
    function widget_color_types($types) {
        $types[] = 'custom_postmeta';
        return $types;
    }
    function color_listener_save($prevent_save, $br_product_filter, $type, $color_values) {
        if( ! empty($br_product_filter) && ! empty($br_product_filter['filter_type']) && $br_product_filter['filter_type'] == 'custom_postmeta' ) {
            global $wpdb;
            $this->create_data_table();
            $postname = sanitize_text_field($_POST['tax_color_name']);
            foreach( $color_values as $key => $value ) {
                if( $type == 'color' ) {
                    foreach($value as $term_key => $term_val) {
                        if( !empty($term_val) ) {
                            $sql = $wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}berocket_postmeta_data (postmeta, metaval, name, value) VALUES(%s, %s, %s, %s) ON DUPLICATE KEY UPDATE value=%s", $postname, $term_key, $key, $term_val, $term_val);
                            "INSERT IGNORE INTO {$wpdb->prefix}berocket_postmeta_data (postmeta, metaval, name, value) VALUES('{$postname}', '{$term_key}', '{$key}', '{$term_val}') ON DUPLICATE KEY UPDATE value='{$term_val}'";
                            $wpdb->query($sql);
                        } else {
                            $sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}berocket_postmeta_data WHERE postmeta = %s AND metaval = %s AND name = %s;", $postname, $term_key, $key);
                            $wpdb->query($sql);
                        }
                    }
                } else {
                    $sql = $wpdb->prepare("INSERT IGNORE INTO {$wpdb->prefix}berocket_postmeta_data (postmeta, metaval, name, value) VALUES(%s, %s, %s, %s) ON DUPLICATE KEY UPDATE value=%s", $postname, $key, $type, $value, $value);
                    $wpdb->query($sql);
                }
            }
            return false;
        }
        return $prevent_save;
    }
    function color_listener_attribute($attribute, $instance, $post_id, $post) {
        if( $instance['filter_type'] == 'custom_postmeta' ) {
            $attribute = $instance['custom_postmeta'];
        }
        return $attribute;
    }
    function select_metadata($value, $term, $type) {
        if( property_exists($term, 'custom_postmeta') && $term->custom_postmeta ) {
            global $wpdb;
            $postmeta = substr($term->taxonomy, 4);
            $metaval  = $term->slug;
            $sql = $wpdb->prepare("SELECT value FROM {$wpdb->prefix}berocket_postmeta_data WHERE postmeta = %s AND metaval = %s AND name = %s;", $postmeta, $metaval, $type);
            $value2 = $wpdb->get_var($sql);
            if( ! empty($value2) ) {
                $value = array($value2);
            }
        }
        return $value;
    }
    function create_data_table() {
        global $wpdb;
        $table_name  = $wpdb->prefix . 'berocket_postmeta_data';
        $charset_collate = '';
        if ( ! empty ( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if ( ! empty ( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE {$table_name} (
            postmeta varchar(110),
            metaval varchar(110) NOT NULL default 0,
            name varchar(25) DEFAULT NULL,
            value longtext DEFAULT NULL,
            INDEX postmeta (postmeta),
            INDEX metaval (metaval),
            UNIQUE KEY meta_id (postmeta, metaval, name)
        ) {$charset_collate};";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    function uparse_terms($result, $instance, $filter, $data) {
        if( $result === null && isset($filter['type']) && $filter['type'] == 'custom_postmeta' ) {
            $result = $this->get_postmeta(substr($filter['taxonomy'],4));
        }
        return $result;
    }
    public function ids_slug($result, $instance, $values_line, $taxonomy, $filter, $args) {
        if( $result === null && isset($filter['type']) && $filter['type'] == 'custom_postmeta' ) {
            $terms = $this->get_postmeta(substr($filter['taxonomy'],4));
            $result = array();
            foreach($terms as $term) {
                $result[$term->term_id] = $term->slug;
            }
        }
        return $result;
    }
    function slider_selected($template_content, $terms, $berocket_query_var_title) {
        if( in_array($berocket_query_var_title['new_template'], array('slider', 'new_slider')) ) {
            foreach($terms as $term){break;}
            if( count($terms) == 1 ) {
                global $berocket_parse_page_obj;
                $filter_data = $berocket_parse_page_obj->get_current();
                foreach($filter_data['filters'] as $filter) {
                    if( (( in_array($filter['type'], array('custom_postmeta')) && $filter['taxonomy'] == $term->taxonomy ) ) 
                    && ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'SLIDER') {
                        $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-start'] = floatval($filter['val_arr']['from']);
                        $template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-end'] = floatval($filter['val_arr']['to']);
                        break;
                    }
                }
            }
        }
        return $template_content;
    }
}
new BeRocket_aapf_add_postmeta_filters();