<?php
if( ! class_exists('BeRocket_aapf_variations_tables_postmeta_addon') ) {
    class BeRocket_aapf_variations_tables_postmeta_addon {
        public $additional_table_class = false;
        public $position_update = 1;
        public $required_update = false;
        public $position_last = 1;
        public $post_clauses_number = 1;
        function __construct() {
            //Generate tables
            add_filter('BeRocket_aapf_variations_tables_addon_table_list', array($this, 'table_list'));
            add_filter('BeRocket_aapf_variations_tables_addon_check_table_list', array($this, 'table_list'));
            add_filter('BeRocket_aapf_variations_tables_addon_position_data', array($this, 'position_data'), 100000, 2);
            add_action('updated_post_meta', array($this, 'save_filter'), 10, 4);
            add_action('added_post_meta', array($this, 'save_filter'), 10, 4);
            add_action('deleted_post_meta', array($this, 'save_filter'), 10, 4);
            add_action('braapf_additional_table_ended_position', array($this, 'add_end_string'), 100);
            //Replace 
            add_action('BeRocket_aapf_variations_tables_addon_status', array($this, 'addon_active'), 10, 3);
            add_action( 'admin_footer', array($this, 'admin_footer') );
        }
        function addon_active($status, $create_position, $instance) {
            if( is_admin() && $status == 'ready' ) {
                if( strpos($create_position, 'cpm') === FALSE ) {
                    $this->destroy_table();
                    $instance->set_current_create_position();
                    $status = 'start';
                }
            }
            if($status == 'ready') {
                add_filter('berocket_aapf_postmeta_main_query', array($this, 'postmeta_main_query'));
                add_filter('berocket_aapf_recount_postmeta_query', array($this, 'recount_postmeta_query'), 10, 3);
                add_filter('bapf_uparse_generate_meta_query_postmeta_meta_query_use', array($this, 'disable'), 10, 1);
                add_filter('bapf_uparse_generate_custom_query_each', array($this, 'custom_query_each'), 10000, 4);
                add_filter('bapf_uparse_generate_posts_in_each', array($this, 'posts_in'), 10000, 4);
            }
        }
        function disable() {
            return false;
        }
        function add_end_string($ended) {
            $ended = $ended . ' cpm ';
            return $ended;
        }
        function table_list($list) {
            $list[] = 'braapf_custom_post_meta';
            $list[] = 'braapf_product_post_meta';
            return $list;
        }
        function position_data($position_data, $instance) {
            $this->position_last = count($position_data);
            $position_data[] = array(
                'percentage' => 4,
                'execute'    => array($this, 'create_table'),
                'ajax_only'  => true
            );
            $this->additional_table_class = $instance;
            $this->position_update = count($position_data);
            $position_data[] = array(
                'percentage' => 15,
                'execute'    => array($this, 'create_post_meta'),
                'ajax_only'  => true
            );
            $position_data[] = array(
                'percentage' => 120,
                'execute'    => array($this, 'generate_post_meta'),
                'ajax_only'  => true
            );
            $this->is_required_update();
            return $position_data;
        }
        function create_table($current_position, $instance) {
            $run_data = $instance->get_current_create_position_data();
            if( ! empty($run_data) && ! empty($run_data['run']) ) {
                return false;
            }
            $instance->set_current_create_position_data(array(
                'status' => 0,
                'run' => true,
            ));
            global $wpdb;
            $charset_collate = $instance->get_charset_collate();
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            //braapf_custom_post_meta table
            $table_name = $wpdb->prefix . 'braapf_custom_post_meta';
            $instance->reset_table($table_name);
            $sql = "CREATE TABLE $table_name (
            meta_id bigint(20) NOT NULL AUTO_INCREMENT,
            meta varchar(120) NOT NULL,
            slug varchar(120) NOT NULL,
            count bigint(20) DEFAULT '1',
            name text NOT NULL,
            INDEX meta_id (meta_id),
            INDEX meta (meta),
            INDEX slug (slug),
            INDEX metaslug (meta, slug),
            UNIQUE uniqueid (meta, slug)
            ) $charset_collate;";
            $query_status = dbDelta( $sql );
            $instance->save_query_error($sql, $query_status);
            //braapf_product_post_meta table
            $table_name = $wpdb->prefix . 'braapf_product_post_meta';
            $instance->reset_table($table_name);
            $sql = "CREATE TABLE $table_name (
            meta_id bigint(20) NOT NULL,
            product_id bigint(20) NOT NULL,
            INDEX meta_id (meta_id),
            INDEX product_id (product_id),
            INDEX metaslug (meta_id, product_id),
            UNIQUE uniqueid (meta_id, product_id)
            ) $charset_collate;";
            $query_status = dbDelta( $sql );
            $instance->save_query_error($sql, $query_status);
            //get_current post meta
            $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
            $filters = $BeRocket_AAPF_single_filter->get_custom_posts();
            $postmeta = array();
            foreach($filters as $filter) {
                $filter_option = $BeRocket_AAPF_single_filter->get_option($filter);
                if( ! empty($filter_option['filter_type']) && $filter_option['filter_type'] == 'custom_postmeta' && ! empty($filter_option['custom_postmeta']) ) {
                    $postmeta[] = $filter_option['custom_postmeta'];
                }
            }
            update_option('berocket_aapf_custom_post_meta', $postmeta);
            $instance->set_current_create_position_data(array(
                'status' => 0,
                'run' => false,
            ));
            if( count($postmeta) == 0 ) {
                $instance->set_current_create_position($current_position+3);
            } else {
                $instance->increment_create_position();
            }
        }
        function create_post_meta($current_position, $instance) {
            $run_data = $instance->get_current_create_position_data();
            if( empty($run_data) || ! empty($run_data['run']) ) {
                return false;
            }
            $run_data['run'] = true;
            $instance->set_current_create_position_data($run_data);
            global $wpdb;
            $postmeta = get_option('berocket_aapf_custom_post_meta');
            if( is_array($postmeta) && count($postmeta) > 0 ) {
                foreach($postmeta as $postmeta_single) {
                    $this->regenerate_values_single_post_meta($postmeta_single);
                }
            }
            $sql = "SELECT MIN({$wpdb->postmeta}.meta_id) as min, MAX({$wpdb->postmeta}.meta_id) as max FROM {$wpdb->postmeta}";
            $postmeta_data = $wpdb->get_row($sql);
            if( ! empty($postmeta_data) && isset($postmeta_data->min) && isset($postmeta_data->max) ) {
                $instance->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                    'start_id' => $postmeta_data->min,
                    'min_id' => $postmeta_data->min,
                    'max_id' => $postmeta_data->max
                ));
                $instance->increment_create_position();
            } else {
                $instance->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                ));
                $instance->set_current_create_position($current_position+2);
            }
        }
        function generate_post_meta($current_position = false, $instance = false) {
            $run_data = $instance->get_current_create_position_data();
            if( empty($run_data) || ! empty($run_data['run']) ) {
                return false;
            }
            $run_data['run'] = true;
            $instance->set_current_create_position_data($run_data);
            $start_id = intval($run_data['start_id']);
            $min_id = intval($run_data['min_id']);
            $max_id = intval($run_data['max_id']);
            $end_id = $start_id + apply_filters('berocket_insert_table_braapf_product_variation_post_meta_end', 1000);
            $postmeta = get_option('berocket_aapf_custom_post_meta');
            if( is_array($postmeta) && count($postmeta) > 0 ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'braapf_product_post_meta';
                $sql_select = "SELECT postmeta.post_id as id, postmeta.meta_key AS name, postmeta.meta_value as val
                FROM {$wpdb->postmeta} as postmeta
                JOIN {$wpdb->posts} as posts ON posts.ID = postmeta.post_id
                WHERE postmeta.meta_key IN ('".implode("','", $postmeta)."') AND posts.post_type = 'product' AND postmeta.meta_value != ''
                AND postmeta.meta_id >= {$start_id} AND postmeta.meta_id < {$end_id}
                GROUP BY postmeta.meta_key, postmeta.meta_value, postmeta.post_id";
                $result = $wpdb->get_results($sql_select);
                $product_metas = array();
                foreach($result as $post_meta_val) {
                    $name = $this->sanitize_name($post_meta_val->name);
                    $val = $this->sanitize_name($post_meta_val->val);
                    if( ! isset($product_metas[$name]) ) {
                        $product_metas[$name] = array();
                    }
                    if( ! isset($product_metas[$name][$val]) ) {
                        $product_metas[$name][$val] = array();
                    }
                    $product_metas[$name][$val][] = $post_meta_val->id;
                }
                $table_name_meta = $wpdb->prefix . 'braapf_custom_post_meta';
                $product_meta_insert = array();
                foreach($product_metas as $name => $products_meta) {
                    $get_meta_id = "SELECT meta_id, slug FROM {$table_name_meta} WHERE meta = '{$name}' AND slug IN ('" . implode("','", array_keys($products_meta)) . "')";
                    $result = $wpdb->get_results($get_meta_id);
                    $post_meta_slug_id = array();
                    foreach($result as $post_meta_ids) {
                        $post_meta_slug_id[$post_meta_ids->slug] = $post_meta_ids->meta_id;
                    }
                    foreach($products_meta as $meta_val => $products_list) {
                        foreach($products_list as $product_id) {
                            if( ! empty($post_meta_slug_id[$meta_val]) ) {
                                $product_meta_insert[] = $wpdb->prepare("(%s, %s)", $post_meta_slug_id[$meta_val], $product_id);
                            }
                        }
                    }
                }
                if( count($product_meta_insert) > 0 ) {
                    $include_sql = "INSERT IGNORE INTO {$table_name} VALUES ".implode(',', $product_meta_insert).';';
                    $query_status = $wpdb->query($include_sql);
                }
            }
            $status = max(0, min(100, (($end_id - $min_id) / (($max_id - $min_id) == 0 ? 1 : ($max_id - $min_id)) * 100)));
            if( is_array($postmeta) && count($postmeta) > 0 && $end_id <= $max_id ) {
                $instance->set_current_create_position_data(array(
                    'status' => $status,
                    'run' => false,
                    'start_id' => $end_id,
                    'min_id' => $min_id,
                    'max_id' => $max_id
                ));
            } else {
                update_option('berocket_cpm_update_required', true);
                $instance->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                ));
                $instance->increment_create_position();
            }
        }
        function regenerate_values_single_post_meta($post_meta) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'braapf_custom_post_meta';
            $sql_select = $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta}
            JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
            WHERE meta_key LIKE %s AND {$wpdb->posts}.post_type = 'product' AND meta_value != ''
            GROUP BY meta_value", $post_meta);
            $result = $wpdb->get_results($sql_select);
            $terms = array();
            $exist_terms = array();
            $meta = $post_meta;
            $meta = $this->sanitize_name($meta);
            foreach($result as $meta_single) {
                $name = $meta_single->meta_value;
                $slug = $this->sanitize_name($name);
                $terms[] = $wpdb->prepare("(%s,%s,%s)", $meta, $slug, $name);
                $exist_terms[] = $slug;
            }
            $sql_remove = $wpdb->prepare("DELETE FROM {$table_name} WHERE meta LIKE %s AND slug NOT IN('". implode("','", $exist_terms) . "');", $meta);
            $wpdb->query($sql_remove);
            $insert_sql = "INSERT IGNORE INTO {$table_name}(meta, slug, name) VALUES ". implode(',', $terms) . ';';
            $wpdb->query($insert_sql);
        }
        function sanitize_name($name) {
            $name = sanitize_title($name);
            $name = mb_substr($name, 0, 100);
            return $name;
        }            
        function get_charset_collate() {
            global $wpdb;
            $result = $wpdb->get_row("SHOW TABLE STATUS where name like '{$wpdb->posts}'");
            if( ! empty($result) && ! empty($result->Collation) ) {
                $collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset . ' COLLATE ' . $result->Collation;
            } else {
                $collate = $wpdb->get_charset_collate();
            }
            return $collate;
        }
        function save_filter($meta_id, $object_id, $meta_key, $meta_value) {
            $postmeta = get_option('berocket_aapf_custom_post_meta');
            if( $meta_key == 'br_product_filter' && ! empty($meta_value) && ! empty($meta_value['filter_type']) && $meta_value['filter_type'] == 'custom_postmeta' && ! empty($meta_value['custom_postmeta']) ) {
                if( ! in_array($meta_value['custom_postmeta'], $postmeta) ) {
                    $postmeta[] = $meta_value['custom_postmeta'];
                    $postmeta = array_unique($postmeta);
                    update_option('berocket_aapf_custom_post_meta', $postmeta);
                    $this->required_update = true;
                    $this->is_required_update();
                }
            }
            if( in_array($meta_key, $postmeta) ) {
                if( get_post_type($object_id) == 'product' ) {
                    $this->update_post_meta_product($meta_key, $object_id);
                    update_option('berocket_cpm_update_required', true);
                }
            }
        }
        function update_post_meta_product($meta_key, $product_id) {
            global $wpdb;
            $sanitize_meta_key = $this->sanitize_name($meta_key);
            $sql_select = "SELECT postmeta.meta_value as val
            FROM {$wpdb->postmeta} as postmeta
            WHERE postmeta.meta_key = '{$meta_key}' AND postmeta.post_id = '{$product_id}' AND postmeta.meta_value != ''
            GROUP BY postmeta.meta_key, postmeta.meta_value, postmeta.post_id";
            $result = $wpdb->get_col($sql_select);
            $values = array();
            if( is_array($result) && count($result) > 0 ) {
                foreach($result as &$result_val) {
                    $values[$this->sanitize_name($result_val)] = $result_val;
                }
                $table_name_meta = $wpdb->prefix . 'braapf_custom_post_meta';
                $get_meta_id = "SELECT meta_id, slug FROM {$table_name_meta} WHERE meta = '{$sanitize_meta_key}' AND slug IN ('" . implode("','", array_keys($values)) . "')";
                $meta_result = $wpdb->get_results($get_meta_id);
                
                $not_exist_meta = $values;
                if( is_array($result) && count($result) > 0 ) {
                    foreach($meta_result as $meta_value) {
                        if( isset($not_exist_meta[$meta_value->slug]) ) {
                            unset($not_exist_meta[$meta_value->slug]);
                        }
                    }
                }
                if( count($not_exist_meta) > 0 ) {
                    $terms = array();
                    foreach($not_exist_meta as $meta_slug => $meta_name) {
                        $terms[] = $wpdb->prepare('(%s, %s, %s)', $sanitize_meta_key, $meta_slug, $meta_name);
                    }
                    $insert_sql = "INSERT IGNORE INTO {$table_name_meta}(meta, slug, name) VALUES ". implode(',', $terms) . ';';
                    $wpdb->query($insert_sql);
                    $meta_result = $wpdb->get_results($get_meta_id);
                }
                $product_meta_insert = array();
                if( is_array($meta_result) && count($meta_result) > 0 ) {
                    foreach($meta_result as $meta_val) {
                        $product_meta_insert[] = $wpdb->prepare('(%s, %s)', $meta_val->meta_id, $product_id);
                    }
                }
                $table_name = $wpdb->prefix . 'braapf_product_post_meta';
                $remove_sql = "DELETE FROM {$table_name} WHERE product_id = {$product_id} and meta_id IN (SELECT meta_id FROM {$table_name_meta} WHERE meta = '{$sanitize_meta_key}')";
                $wpdb->query($remove_sql);
                if( count($product_meta_insert) > 0 ) {
                    $include_sql = "INSERT IGNORE INTO {$table_name} VALUES ".implode(',', $product_meta_insert).';';
                    $query_status = $wpdb->query($include_sql);
                }
            }
        }
        function is_required_update() {
            if( $this->required_update && ! empty($this->additional_table_class) ) {
                $this->additional_table_class->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                ));
                $this->additional_table_class->set_current_create_position($this->position_update);
            }
        }       
        function postmeta_main_query($query) {
            if( ! is_admin() ) {
                global $wpdb;
                $table_name_meta = $wpdb->prefix . 'braapf_custom_post_meta';
                $query = "SELECT meta_id, slug as meta_slug, name as meta_value, count FROM {$table_name_meta}
                WHERE meta LIKE %s ORDER BY meta_id";
            }
            return $query;
        }
        function recount_postmeta_query($query, $taxonomy_data, $postmeta) {
            if( ! is_admin() ) {
                global $wpdb;
                $table_name_meta = $wpdb->prefix . 'braapf_custom_post_meta';
                $table_name = $wpdb->prefix . 'braapf_product_post_meta';
                $query['select']['elements'] = array(
                    'meta_id'    => 'brpm_meta.meta_id as meta_id',
                    'meta_value' => 'brpm_meta.slug as meta_value',
                    'meta_name'  => 'brpm_meta.name as meta_name',
                    'count'      => "count(DISTINCT {$wpdb->posts}.ID) as count"
                );
                $query['join']['brpm_recount'] = "RIGHT JOIN {$table_name} as brpm_recount ON {$wpdb->posts}.ID = brpm_recount.product_id";
                $query['join']['brpm_meta'] = "JOIN {$table_name_meta} as brpm_meta ON brpm_recount.meta_id = brpm_meta.meta_id";
                $query['group'] = 'GROUP BY brpm_recount.meta_id';
                $query['where']['brpm_recount'] = $wpdb->prepare('AND brpm_meta.meta = %s', $postmeta);
                $query['order'] = " ORDER BY meta_id";
            }
            return $query;
        }
        function is_single_filter_and($filter) {
            return ! empty($filter['val_ids']) && is_array($filter['val_ids']) && count($filter['val_ids']) > 1
            && ! empty($filter['val_arr']['op']) && $filter['val_arr']['op'] == 'AND';
        }
        function posts_in($result, $instance, $filter, $data) {
            if( $result === NULL && ! empty($filter['type']) && $filter['type'] == 'custom_postmeta' 
            && $this->is_single_filter_and($filter) ) {
                global $wpdb;
                $val_ids = $filter['val_ids'];
                $table_name = $wpdb->prefix . 'braapf_product_post_meta';
                $select_posts = "SELECT product_id, count(product_id) as count FROM {$table_name} 
                WHERE meta_id IN ('" . implode("','", $val_ids) . "')
                GROUP By product_id
                HAVING count = " . count($val_ids);
                $post_ids = $wpdb->get_col($select_posts);
                if( empty($post_ids) ) {
                    $post_ids = array('0');
                }
                $result = $filter;
                $result['posts_in'] = $post_ids;
            }
            return $result;
        }
        function custom_query_each($result, $instance, $filter, $data) {
            if( $result === NULL && ! empty($filter['type']) && $filter['type'] == 'custom_postmeta'
            && ! $this->is_single_filter_and($filter) ) {
                $result = $filter;
                $result['custom_query'] = array($this, 'post_clauses');
                $result['custom_query_line'] = 'sale:'.$filter['val'];
            }
            return $result;
        }
        function post_clauses($args, $filter) {
            global $wpdb;
            $table_name_custom = 'brpm_filter_' . $this->post_clauses_number;
            $this->post_clauses_number = $this->post_clauses_number + 1;
            $table_name = $wpdb->prefix . 'braapf_product_post_meta';
            $taxonomy = substr($filter['taxonomy'],4);
            $val_ids = $filter['val_ids'];
            $args['join'] .= " JOIN {$table_name} as {$table_name_custom} ON {$wpdb->posts}.ID = {$table_name_custom}.product_id ";
            $args['where'] .= " AND {$table_name_custom}.meta_id IN ('" . implode("','", $val_ids) . "')";
            return $args;
        }
        function admin_footer() {
            $update = get_option('berocket_cpm_update_required');
            if( ! empty($update) ) {
                global $wpdb;
                $table_name_meta = $wpdb->prefix . 'braapf_custom_post_meta';
                $table_name = $wpdb->prefix . 'braapf_product_post_meta';
                $query = "UPDATE {$table_name_meta} as setable
                LEFT JOIN ( 
                    SELECT meta_id, count(product_id) as count
                    FROM {$table_name}
                    GROUP BY meta_id
                ) as getable ON setable.meta_id= getable.meta_id
                SET setable.count  = getable.count";
                $wpdb->query($query);
                delete_option('berocket_cpm_update_required');
            }
        }
    }
}