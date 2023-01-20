<?php
class BeRocket_aapf_variations_tables_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'add_table';
    public $position_data;
    public $run_additional_tables = false;
    function __construct() {
        $this->position_data = array(
            0 => array(
                'percentage' => 0,
                'execute'    => array($this, 'increment_create_position'),
                'ajax_only'  => false
            ),
            1 => array(
                'percentage' => 4,
                'execute'    => array($this, 'create_all_tables'),
                'ajax_only'  => true
            ),
            2 => array(
                'percentage' => 13,
                'execute'    => array($this, 'insert_table_braapf_product_stock_status_parent'),
                'ajax_only'  => true
            ),
            3 => array(
                'percentage' => 70,
                'execute'    => array($this, 'insert_table_braapf_product_variation_attributes'),
                'ajax_only'  => true
            ),
            4 => array(
                'percentage' => 3,
                'execute'    => array($this, 'insert_table_braapf_variable_attributes'),
                'ajax_only'  => true
            ),
        );
        parent::__construct();
        add_action('init', array($this, 'init_tables'), 3);
        add_action('admin_footer', array($this, 'init_tables'), 3);
    }
    function init_tables() {
        if( $this->run_additional_tables ) {
            return false;
        }
        $this->run_additional_tables = true;
        $active_addons = apply_filters('berocket_addons_active_'.$this->plugin_name, array());
        $create_position = $this->get_current_create_position();
        $status = 'start';
        if( in_array($this->addon_file, $active_addons) ) {
            $this->position_data = apply_filters('BeRocket_aapf_variations_tables_addon_position_data', $this->position_data, $this);
            if( is_admin() ) {
                $this->is_table_exist();
                $create_position = $this->get_current_create_position();
            }
            if( strpos($create_position, 'ended') !== FALSE && $create_position != $this->get_end_position() ) {
                $this->reset_all_table();
                $create_position = false;
            }
            if( strpos($create_position, 'ended') === FALSE ) {
                add_action( "braapf_additional_table_cron", array( $this, 'cron' ), 10 );
                $this->init_activate();
                $create_position = $this->get_current_create_position();
                if( strpos($create_position, 'ended') === FALSE ) {
                    add_action('admin_init', array($this, 'activate_hooks'));
                }
                add_action( "admin_footer", array( $this, 'destroy_table_wc_regeneration' ) );
                add_action( 'br-filters/addon/add-table/destroy', array($this, 'destroy_table') );
                $status = 'generating';
            } else {
                $status = 'ready';
                if(is_admin()) {
                    if( ! empty($create_position) ) {
                        add_action( "admin_footer", array( $this, 'destroy_table_wc_regeneration' ) );
                        add_action( 'br-filters/addon/add-table/destroy', array($this, 'destroy_table') );
                    }
                }
            }
        } else {
            $create_position = $this->get_current_create_position();
            $status = 'remove';
            delete_option('BeRocket_aapf_additional_tables_addon_position');
            delete_option('BeRocket_aapf_additional_tables_addon_position_data');
            if( ! empty($create_position) ) {
                $this->deactivate();
            }
        }
        do_action('BeRocket_aapf_variations_tables_addon_status', $status, $create_position, $this);
    }
    function is_table_exist() {
        $create_position = $this->get_current_create_position();
        if( strpos($create_position, 'ended') !== FALSE ) {
            global $wpdb;
            $result = $wpdb->get_col("SHOW TABLES;");
            $tables = apply_filters('BeRocket_aapf_variations_tables_addon_check_table_list', array(
                'braapf_product_stock_status_parent',
                'braapf_product_variation_attributes',
                'braapf_variable_attributes',
                'braapf_term_taxonomy_hierarchical'
            ));
            foreach($tables as $table) {
                $table_name = $wpdb->prefix . $table;
                if( ! in_array($table_name, $result) ) {
                    $this->reset_all_table();
                    break;
                }
            }
        }
    }
    function get_charset_collate() {
        global $wpdb;
        $collate = '';
        $result = $wpdb->get_row("SHOW TABLE STATUS where name like '{$wpdb->posts}'");
        if( ! empty($result) && ! empty($result->Collation) ) {
            $collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset . ' COLLATE ' . $result->Collation;
        } else {
            if ( $wpdb->has_cap( 'collation' ) ) {
                $collate = $wpdb->get_charset_collate();
            }
        }
        return $collate;
    }
    function cron() {
        $start_time = time();
        $time_limit = ( (function_exists('ini_get') && (int)ini_get('max_execution_time')) ? (int)ini_get('max_execution_time') : 30 );
        if( $time_limit > 10 ) {
            $time_limit = $time_limit/2;
        } else {
            $time_limit = 0;
        }
        do {
            $this->activate(-1, true);
            $end_time = time();
            $create_position = $this->get_current_create_position();
        } while( $time_limit > ($end_time - $start_time) && strpos($create_position, 'ended') === FALSE && strpos($create_position, 'final') === FALSE );
    }
    function init_activate() {
        $this->activate();
    }
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Additional Tables (BETA)', 'BeRocket_AJAX_domain'),
            'tooltip'       => __('Create 4 additional tables.<ul><li>Table to speed up hierarchical taxonomies recount: <strong>Product categories</strong>, <strong>Brands</strong> etc</li><li>3 tables to speed up functions for variation filtering</li></ul>', 'BeRocket_AJAX_domain'),
        ));
    }
    function check_init() {
        $create_position = get_option('BeRocket_aapf_additional_tables_addon_position');
        if( strpos($create_position, 'ended') !== FALSE ) {
            parent::check_init();
        }
    }
    function get_current_create_position() {
        $current_position = get_option('BeRocket_aapf_additional_tables_addon_position');
        if( empty($current_position) ) {
            $current_position = 1;
        }
        return $current_position;
    }
    function set_current_create_position($position) {
        update_option('BeRocket_aapf_additional_tables_addon_position', $position);
    }
    function increment_create_position() {
        $current_position = $this->get_current_create_position();
        $this->set_current_create_position($current_position+1);
    }
    function get_current_create_position_data() {
        return get_option('BeRocket_aapf_additional_tables_addon_position_data');
    }
    function set_current_create_position_data($data) {
        update_option('BeRocket_aapf_additional_tables_addon_position_data', $data);
    }
    function activate($current_position = -1, $brajax = false) {
        if( function_exists('wc_update_product_lookup_tables_is_running')  && wc_update_product_lookup_tables_is_running() ) {
            return;
        }
        if( $current_position == -1 ) {
            $current_position = $this->get_current_create_position();
        }
        if( ! empty($this->position_data[$current_position]) ) {
            if( empty($this->position_data[$current_position]['ajax_only']) || $brajax ) {
                call_user_func($this->position_data[$current_position]['execute'], $current_position, $this);
            }
        } else {
            $this->set_current_create_position('final');
            $this->table_generation_end();
        }
        if( empty($current_position) || empty($this->position_data[$current_position]) || empty($this->position_data[$current_position]['end_pos']) ) {
            wp_schedule_single_event(time(), 'braapf_additional_table_cron');
        }
    }
    function get_end_position() {
        return apply_filters('braapf_additional_table_ended_position', 'ended');
    }
    function table_generation_end() {
        if( class_exists('berocket_information_notices') ) {
            new berocket_information_notices(array(
                'name'  => $this->plugin_name.'_additional_table_status_end',
                'html'  => '<strong>BeRocket AJAX Product Filters</strong> '.__('Additional tables was succesfully generated. They will be used automatically when needed.', 'BeRocket_AJAX_domain'),
                'righthtml'  => '<a class="berocket_no_thanks">Got it</a>',
                'rightwidth'  => 50,
                'nothankswidth'  => 50,
                'contentwidth'  => 400,
                'subscribe'  => false,
                'height'  => 50,
            ));
        }
        $this->set_current_create_position($this->get_end_position());
        wp_clear_scheduled_hook('braapf_additional_table_cron');
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        $options['purge_cache_time'] = time();
        update_option( 'br_filters_options', $options );
    }
    function activate_hooks() {
        if( function_exists('wc_update_product_lookup_tables_is_running') && ! wc_update_product_lookup_tables_is_running() ) {
            //Notices
            add_action( "wp_ajax_braapf_additional_table_status", array( $this, 'get_global_status_ajax' ) );
            add_action( "wp_footer", array( $this, 'script_update' ) );
            add_action( "admin_footer", array( $this, 'script_update' ) );
        }
        add_filter('berocket_display_additional_notices', array($this, 'status_notice'));
    }
    function status_notice($notices) {
        if( ! function_exists('wc_update_product_lookup_tables_is_running') ) {
            $text = __('WooCommerce do not have needed table for Additional Table add-on. Add-on required WooCommerce 3.6 or newer', 'BeRocket_AJAX_domain');
        } elseif( wc_update_product_lookup_tables_is_running() ) { 
            $text = __('WooCommerce <strong>Product lookup tables</strong> right now regenerating', 'BeRocket_AJAX_domain');
        } else {
            $current_status = $this->get_current_global_status();
            $text = sprintf(__('Additional tables are generating. They will be used after generation is completed. Current status is <strong><span class="braapf_additional_table_status">%d</span>%s</strong>', 'BeRocket_AJAX_domain'), $current_status, '%');
            $current_position = $this->get_current_create_position();
            if( $current_position == 2 ) {
                $run_data = $this->get_current_create_position_data();
                if ( ! empty($run_data) && is_array($run_data) && isset($run_data['min_id']) && isset($run_data['max_id']) 
                    && ( intval($run_data['max_id']) - intval($run_data['min_id']) ) > 1000000 ) {
                    $url = admin_url('admin.php?page=wc-status&tab=tools');
                    global $wpdb;
                    $text .= '<p>' . __('Seems you have some issue with Product lookup tables. Please try to remove all data from table', 'BeRocket_AJAX_domain') . ' <strong>'.$wpdb->prefix.'wc_product_meta_lookup</strong> ' . __('and regenerate it in ', 'BeRocket_AJAX_domain'). '<a href="'.$url.'">WooCommerce -> Status -> Tools</a></p>';
                }
            }
        }
        $notices[] = array(
            'start'         => 0,
            'end'           => 0,
            'name'          => $this->plugin_name.'_additional_table_status',
            'html'          => '<strong>BeRocket AJAX Product Filters</strong> '.$text,
            'righthtml'     => '',
            'rightwidth'    => 0,
            'nothankswidth' => 0,
            'contentwidth'  => 1600,
            'subscribe'     => false,
            'priority'      => 10,
            'height'        => 70,
            'repeat'        => false,
            'repeatcount'   => 1,
            'image'         => array(
                'local'  => '',
                'width'  => 0,
                'height' => 0,
                'scale'  => 1,
            )
        );
        return $notices;
    }
    function script_update() {
        echo '<script>
        if( jQuery(".braapf_additional_table_status").length ) {
            var braapf_additional_table_status = setInterval(function() {
                jQuery.get(ajaxurl, {action:"braapf_additional_table_status"}, function(data) {
                    data = parseInt(data);
                    jQuery(".braapf_additional_table_status").text(data);
                    if( data >= 100 ) {
                        clearInterval(braapf_additional_table_status);
                    }
                }).error(function() {
                    clearInterval(braapf_additional_table_status);
                    jQuery(".braapf_additional_table_status").parents(".berocket_admin_notice").remove();
                });
            }, 4000);
        }
        </script>';
    }
    function get_global_status_ajax() {
        echo $this->get_current_global_status();
        if ( function_exists( 'fastcgi_finish_request' ) && version_compare( phpversion(), '7.0.16', '>=' ) ) {
            fastcgi_finish_request();
        }
        $run_data = $this->get_current_create_position_data();
        if( ! empty($run_data) && ! empty($run_data['run']) ) {
            if( ! empty($run_data['ajax_status_check']) ) {
                if( intval($run_data['ajax_status_check']) > time() ) {
                    $run_data['run'] = false;
                    unset($run_data['ajax_status_check']);
                }
            } else {
                $run_data['ajax_status_check'] = time() + 30;
            }
            $this->set_current_create_position_data($run_data);
        }
        $this->activate(-1, true);
        wp_die();
    }
    function get_current_global_status($current_position = -1) {
        if( $current_position == -1 ) {
            $current_position = $this->get_current_create_position();
        }
        $position_data = $this->get_current_create_position_data();
        $position_status = br_get_value_from_array($position_data, 'status', 0);
        $global_status = 0;
        $global_status_full = 0;
        foreach($this->position_data as $position_i => $position_data_arr) {
            if( $position_i < $current_position ) {
                $global_status += $position_data_arr['percentage'];
            } elseif( $position_i == $current_position ) {
                $global_status += ( $position_data_arr['percentage'] / 100 * $position_status );
            }
            $global_status_full += $position_data_arr['percentage'];;
        }
        $global_status = (100 / $global_status_full) * $global_status;
        $global_status = intval($global_status);
        return $global_status;
    }
    function save_query_error($query, $error = false) {
        global $wpdb;
        if( $error === false ) {
            $error = $wpdb->last_error;
        }
        BeRocket_error_notices::add_plugin_error(1, 'Additional tables generation', array(
            'query' => $query,
            'error' => $error,
            'cron'  => (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ? 'DISABLED' : 'ENABLED')
        ));
    }
    function create_all_tables() {
        $run_data = $this->get_current_create_position_data();
        if( ! empty($run_data) && ! empty($run_data['run']) ) {
            return false;
        }
        global $wpdb;
        $this->set_current_create_position_data(array(
            'status' => 0,
            'run' => true,
        ));
        $this->create_table_braapf_term_taxonomy_hierarchical();
        $this->create_table_braapf_product_stock_status_parent();
        $this->create_table_braapf_variable_attributes();
        $this->create_table_braapf_product_variation_attributes();
        
        $sql = "SELECT MIN({$wpdb->prefix}wc_product_meta_lookup.product_id) as min, MAX({$wpdb->prefix}wc_product_meta_lookup.product_id) as max FROM {$wpdb->prefix}wc_product_meta_lookup";
        $product_data = $wpdb->get_row($sql);
        $this->save_query_error($sql, $product_data);
        if( ! empty($product_data) && ! empty($product_data->min) && ! empty($product_data->max) ) {
            $this->set_current_create_position(2);
            $this->set_current_create_position_data(array(
                'status' => 0,
                'run' => false,
                'start_id' => $product_data->min,
                'min_id' => $product_data->min,
                'max_id' => $product_data->max
            ));
        } else {
            $sql = "SELECT MIN({$wpdb->postmeta}.meta_id) as min, MAX({$wpdb->postmeta}.meta_id) as max FROM {$wpdb->postmeta}";
            $postmeta_data = $wpdb->get_row($sql);
            if( ! empty($postmeta_data) && ! empty($postmeta_data->min) && ! empty($postmeta_data->max) ) {
                $this->set_current_create_position(3);
                $this->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                    'start_id' => $postmeta_data->min,
                    'min_id' => $postmeta_data->min,
                    'max_id' => $postmeta_data->max
                ));
            } else {
                $sql = "SELECT MIN({$wpdb->posts}.ID) as min, MAX({$wpdb->posts}.ID) as max FROM {$wpdb->posts}";
                $postmeta_data = $wpdb->get_row($sql);
                $this->set_current_create_position(4);
                $this->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                    'start_id' => $postmeta_data->min,
                    'min_id' => $postmeta_data->min,
                    'max_id' => $postmeta_data->max
                ));
            }
        }
    }
    function reset_table($table_name) {
        global $wpdb;
        $result = $wpdb->get_col("SHOW TABLES;");
        if( in_array($table_name, $result) ) {
            $sql = "TRUNCATE TABLE {$table_name};";
            $wpdb->query($sql);
            $sql = "ALTER TABLE {$table_name} AUTO_INCREMENT = 1;";
            $wpdb->query($sql);
        }
    }
    function create_table_braapf_term_taxonomy_hierarchical() {
        global $wpdb;
        $collate = $this->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $table_name = $wpdb->prefix . 'braapf_term_taxonomy_hierarchical';
        $this->reset_table($table_name);
        $sql = "CREATE TABLE $table_name (
        term_taxonomy_id bigint(20) NOT NULL,
        term_id bigint(20) NOT NULL,
        term_taxonomy_child_id bigint(20) NOT NULL,
        term_child_id bigint(20) NOT NULL,
        taxonomy varchar(32) NOT NULL,
        INDEX term_taxonomy_id (term_taxonomy_id),
        INDEX term_taxonomy_child_id (term_taxonomy_child_id),
        INDEX child_parent_id (term_taxonomy_id, term_taxonomy_child_id),
        UNIQUE uniqueid (term_taxonomy_id, term_id, term_taxonomy_child_id, term_child_id)
        ) $collate;";
        $query_status = dbDelta( $sql );
        $this->save_query_error($sql, $query_status);
    }
    function create_table_braapf_product_stock_status_parent() {
        global $wpdb;
        $collate = $this->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $table_name = $wpdb->prefix . 'braapf_product_stock_status_parent';
        $this->reset_table($table_name);
        $sql = "CREATE TABLE $table_name (
        post_id bigint(20) NOT NULL,
        parent_id bigint(20) NOT NULL,
        stock_status tinyint(2),
        PRIMARY KEY (post_id),
        INDEX stock_status (stock_status)
        ) $collate;";
        $query_status = dbDelta( $sql );
        $this->save_query_error($sql, $query_status);
    }
    function create_table_braapf_variable_attributes() {
        global $wpdb;
        $collate = $this->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $table_name = $wpdb->prefix . 'braapf_variable_attributes';
        $this->reset_table($table_name);
        $sql = "CREATE TABLE $table_name (
        post_id bigint(20) NOT NULL,
        attribute varchar(32) NOT NULL,
        INDEX post_id (post_id),
        INDEX attribute (attribute),
        UNIQUE uniqueid (post_id, attribute)
        ) $collate;";
        $query_status = dbDelta( $sql );
        $this->save_query_error($sql, $query_status);
    }
    function create_table_braapf_product_variation_attributes() {
        global $wpdb;
        $collate = $this->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $table_name = $wpdb->prefix . 'braapf_product_variation_attributes';
        $this->reset_table($table_name);
        $sql = "CREATE TABLE $table_name (
        post_id bigint(20) NOT NULL,
        parent_id bigint(20) NOT NULL,
        meta_key varchar(32) NOT NULL,
        meta_value_id bigint(20) NOT NULL,
        stock_status tinyint(2),
        INDEX post_id (post_id),
        INDEX meta_key (meta_key),
        INDEX meta_value_id (meta_value_id),
        UNIQUE uniqueid (post_id, meta_key, meta_value_id)
        ) $collate;";
        $query_status = dbDelta( $sql );
        $this->save_query_error($sql, $query_status);
    }
    function insert_table_braapf_product_stock_status_parent() {
        $run_data = $this->get_current_create_position_data();
        if( empty($run_data) || ! empty($run_data['run']) ) {
            return false;
        }
        $run_data['run'] = true;
        $this->set_current_create_position_data($run_data);
        $start_id = intval($run_data['start_id']);
        $min_id = intval($run_data['min_id']);
        $max_id = intval($run_data['max_id']);
        $end_id = $start_id + apply_filters('berocket_insert_table_braapf_product_stock_status_parent_end', 5000);
        BeRocket_error_notices::add_plugin_error(1, 'insert_table_braapf_product_stock_status_parent', array(
            'start_id' => $start_id,
            'end_id' => $end_id,
        ));
        global $wpdb;
        $table_name = $wpdb->prefix . 'braapf_product_stock_status_parent';
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $sql_select = "SELECT {$wpdb->posts}.ID as post_id, {$wpdb->posts}.post_parent as parent_id, IF({$wpdb->prefix}wc_product_meta_lookup.stock_status = 'instock', 1, 0) as stock_status FROM {$wpdb->prefix}wc_product_meta_lookup
        JOIN {$wpdb->posts} ON {$wpdb->prefix}wc_product_meta_lookup.product_id = {$wpdb->posts}.ID
        WHERE {$wpdb->prefix}wc_product_meta_lookup.product_id >= {$start_id} AND {$wpdb->prefix}wc_product_meta_lookup.product_id < {$end_id}";
        $test_row = $wpdb->get_row($sql_select);
        BeRocket_error_notices::add_plugin_error(1, 'insert_table_braapf_product_stock_status_parent test', array(
            'start_id' => $start_id,
            'end_id' => $end_id,
            'min_id' => $min_id,
            'max_id' => $max_id,
            'result' => $test_row,
        ));
        if( ! empty($test_row) ) {
            $sql = "INSERT IGNORE INTO {$table_name} {$sql_select}";
            $query_status = $wpdb->query($sql);
            if( $query_status === FALSE ) {
                $this->save_query_error($sql);
            }
        }
        $status = max(0, min(100, (($end_id - $min_id) / (($max_id - $min_id) == 0 ? 1 : ($max_id - $min_id)) * 100)));
        if( $end_id <= $max_id ) {
            $this->set_current_create_position_data(array(
                'status' => $status,
                'run' => false,
                'start_id' => $end_id,
                'min_id' => $min_id,
                'max_id' => $max_id
            ));
        } else {
            $sql = "SELECT MIN({$wpdb->postmeta}.meta_id) as min, MAX({$wpdb->postmeta}.meta_id) as max FROM {$wpdb->postmeta}";
            $postmeta_data = $wpdb->get_row($sql);
            if( ! empty($postmeta_data) && isset($postmeta_data->min) && isset($postmeta_data->max) ) {
                $this->set_current_create_position(3);
                $this->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                    'start_id' => $postmeta_data->min,
                    'min_id' => $postmeta_data->min,
                    'max_id' => $postmeta_data->max
                ));
            } else {
                $sql = "SELECT MIN({$wpdb->posts}.ID) as min, MAX({$wpdb->posts}.ID) as max FROM {$wpdb->posts}";
                $postmeta_data = $wpdb->get_row($sql);
                $this->set_current_create_position(4);
                $this->set_current_create_position_data(array(
                    'status' => 0,
                    'run' => false,
                    'start_id' => $postmeta_data->min,
                    'min_id' => $postmeta_data->min,
                    'max_id' => $postmeta_data->max
                ));
            }
        }
    }
    function insert_table_braapf_product_variation_attributes() {
        $run_data = $this->get_current_create_position_data();
        if( empty($run_data) || ! empty($run_data['run']) ) {
            return false;
        }
        $run_data['run'] = true;
        $this->set_current_create_position_data($run_data);
        $start_id = intval($run_data['start_id']);
        $min_id = intval($run_data['min_id']);
        $max_id = intval($run_data['max_id']);
        $end_id = $start_id + apply_filters('berocket_insert_table_braapf_product_variation_attributes_end', 10000);
        global $wpdb;
        $table_name = $wpdb->prefix . 'braapf_product_variation_attributes';
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $sql_select = "SELECT meta_key FROM {$wpdb->postmeta}
                       WHERE meta_key LIKE 'attribute_pa_%'
                       GROUP BY CAST(meta_key AS binary)";
        $postmeta_vars = $wpdb->get_col($sql_select);
        $postmeta_vars_diff = array();
        foreach($postmeta_vars as $postmeta_var) {
            $postmeta_var_new = urldecode($postmeta_var);
            if( $postmeta_var != $postmeta_var_new ) {
                $postmeta_vars_diff[$postmeta_var] = $postmeta_var_new;
            }
        }
        $join_tables = array("CONCAT('attribute_', {$wpdb->term_taxonomy}.taxonomy) = {$wpdb->postmeta}.meta_key");
        foreach($postmeta_vars_diff as $postmeta_from => $postmeta_to) {
            $postmeta_to = str_replace("attribute_pa_", 'pa_', $postmeta_to);
            $join_tables[] = "({$wpdb->term_taxonomy}.taxonomy = '{$postmeta_to}' AND {$wpdb->postmeta}.meta_key = '{$postmeta_from}')";
        }
        $join_tables = implode(' OR ', $join_tables);
        $sql_select = "SELECT 
            {$wpdb->postmeta}.post_id as post_id, 
            {$wpdb->posts}.post_parent as parent_id, 
            {$wpdb->term_taxonomy}.taxonomy as meta_key, 
            {$wpdb->terms}.term_id as meta_value_id,
            IF({$wpdb->wc_product_meta_lookup}.stock_status = 'instock' OR {$wpdb->wc_product_meta_lookup}.stock_status = 'onbackorder', 1, 0) as stock_status
        FROM {$wpdb->postmeta}
        JOIN {$wpdb->term_taxonomy} ON {$join_tables}
        JOIN {$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id AND {$wpdb->postmeta}.meta_value = {$wpdb->terms}.slug
        JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
        JOIN {$wpdb->wc_product_meta_lookup} ON {$wpdb->posts}.ID = {$wpdb->wc_product_meta_lookup}.product_id
        WHERE {$wpdb->postmeta}.meta_id >= {$start_id} AND {$wpdb->postmeta}.meta_id < {$end_id}
        AND {$wpdb->postmeta}.meta_key LIKE 'attribute_pa_%'";
        $test_row = $wpdb->get_row($sql_select);
        if( ! empty($test_row) ) {
            $sql = "INSERT IGNORE INTO {$table_name} {$sql_select}";
            $query_status = $wpdb->query($sql);
            if( $query_status === FALSE ) {
                $this->save_query_error($sql);
            }
        }
        $sql_select = "SELECT 
            {$wpdb->posts}.ID as post_id, 
            {$wpdb->posts}.post_parent as parent_id, 
            {$wpdb->term_taxonomy}.taxonomy as meta_key, 
            {$wpdb->term_taxonomy}.term_id as meta_value_id,
            IF({$wpdb->wc_product_meta_lookup}.stock_status = 'instock' OR {$wpdb->wc_product_meta_lookup}.stock_status = 'onbackorder', 1, 0) as stock_status
        FROM {$wpdb->postmeta}
        JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
        JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.post_parent = {$wpdb->term_relationships}.object_id
        JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id 
        JOIN {$wpdb->wc_product_meta_lookup} ON {$wpdb->posts}.ID = {$wpdb->wc_product_meta_lookup}.product_id
            AND CONCAT('attribute_', {$wpdb->term_taxonomy}.taxonomy) = {$wpdb->postmeta}.meta_key
        WHERE {$wpdb->postmeta}.meta_id >= {$start_id} AND {$wpdb->postmeta}.meta_id < {$end_id}
        AND {$wpdb->postmeta}.meta_key LIKE 'attribute_pa_%' AND {$wpdb->postmeta}.meta_value = ''";
        $test_row = $wpdb->get_row($sql_select);
        if( ! empty($test_row) ) {
            $sql = "INSERT IGNORE INTO {$table_name} {$sql_select}";
            $query_status = $wpdb->query($sql);
            if( $query_status === FALSE ) {
                $this->save_query_error($sql);
            }
        }
        $status = max(0, min(100, (($end_id - $min_id) / (($max_id - $min_id) == 0 ? 1 : ($max_id - $min_id)) * 100)));
        if( $end_id <= $max_id ) {
            $this->set_current_create_position_data(array(
                'status' => $status,
                'run' => false,
                'start_id' => $end_id,
                'min_id' => $min_id,
                'max_id' => $max_id
            ));
        } else {
            $sql = "SELECT MIN({$wpdb->posts}.ID) as min, MAX({$wpdb->posts}.ID) as max FROM {$wpdb->posts}";
            $postmeta_data = $wpdb->get_row($sql);
            $this->set_current_create_position(4);
            $this->set_current_create_position_data(array(
                'status' => 0,
                'run' => false,
                'start_id' => $postmeta_data->min,
                'min_id' => $postmeta_data->min,
                'max_id' => $postmeta_data->max
            ));
        }
    }
    function insert_table_braapf_variable_attributes() {
        $run_data = $this->get_current_create_position_data();
        if( empty($run_data) || ! empty($run_data['run']) ) {
            return false;
        }
        $run_data['run'] = true;
        $variable_taxonomy = get_term_by('slug', 'variable', 'product_type');
        $this->set_current_create_position_data($run_data);
        $page = (isset($run_data['page']) ? $run_data['page'] : 0);
        $page_step = apply_filters('berocket_insert_table_braapf_variable_attributes_end', 1000);
        BeRocket_error_notices::add_plugin_error(1, 'insert_table_braapf_variable_attributes', array(
            'page'   => $page
        ));
        global $wpdb;
        $table_name = $wpdb->prefix . 'braapf_variable_attributes';
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $sql_select = "SELECT {$wpdb->posts}.ID as id, {$wpdb->postmeta}.meta_value as value FROM {$wpdb->posts}
        JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
        JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id
        WHERE {$wpdb->postmeta}.meta_key = '_product_attributes' AND {$wpdb->term_relationships}.term_taxonomy_id = {$variable_taxonomy->term_taxonomy_id}
        LIMIT ".($page * $page_step).", {$page_step}";
        $results = $wpdb->get_results($sql_select);
        $has_result = false;
        if( ! empty($results) && is_array($results) && count($results) > 0 ) {
            $has_result = true;
            $insert_values = array();
            foreach($results as $product) {
                $product_attribute = maybe_unserialize($product->value);
                if( is_array($product_attribute) ) {
                    foreach($product_attribute as $attribute) {
                        if( ! empty($attribute['is_variation']) ) {
                            $insert_values[] = "({$product->id}, '".$attribute['name']."')";
                        }
                    }
                }
            }
            if( ! empty($insert_values) ) {
                $sql = "INSERT IGNORE INTO {$table_name} (post_id, attribute) 
                    VALUES ".implode(',', $insert_values);
                $query_status = $wpdb->query($sql);
                if( $query_status === FALSE ) {
                    $this->save_query_error($sql);
                }
            }
        }
        if( $has_result ) {
            $this->set_current_create_position_data(array(
                'run' => false,
                'page'   => ++$page
            ));
        } else {
            $this->set_current_create_position(5);
            $this->set_current_create_position_data(array(
                'status' => 0,
                'run' => false,
            ));
        }
    }
    function get_table_list() {
        return apply_filters('BeRocket_aapf_variations_tables_addon_table_list', array(
            'braapf_product_stock_status_parent',
            'braapf_product_variation_attributes',
            'braapf_variation_attributes',
            'braapf_variable_attributes',
            'braapf_term_taxonomy_hierarchical'
        ));
    }
    function deactivate() {
        global $wpdb;
        $tables_drop = $this->get_table_list();
        foreach($tables_drop as $table_drop) {
            $table_name = $wpdb->prefix . $table_drop;
            $sql = "DROP TABLE IF EXISTS {$table_name};";
            $wpdb->query($sql);
        }
        $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%br_custom_table_hierarhical_%';");
        $this->set_current_create_position(false);
        $this->set_current_create_position_data(array(
            'status' => 0,
            'run' => false,
        ));
        do_action('BeRocket_aapf_variations_tables_addon_destroy_table', $this);
    }
    function destroy_table_wc_regeneration() {
        if ( apply_filters( 'br-filters/addon/add-table/wc-regenerate-destroy', function_exists('wc_update_product_lookup_tables_is_running') && wc_update_product_lookup_tables_is_running() ) ) {
            $this->reset_all_table();
        }
    }
    function destroy_table() {
        delete_option('BeRocket_aapf_additional_tables_addon_position');
        delete_option('BeRocket_aapf_additional_tables_addon_position_data');
        $this->deactivate();
    }
    function reset_all_table() {
        delete_option('BeRocket_aapf_additional_tables_addon_position');
        delete_option('BeRocket_aapf_additional_tables_addon_position_data');
        $this->set_current_create_position(false);
        $this->set_current_create_position_data(array(
            'status' => 0,
            'run' => false,
        ));
    }
}
new BeRocket_aapf_variations_tables_addon();
