<?php
class BeRocket_AAPF_compat_product_table {
    function __construct() {
        add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ), 1 );
        add_action('bapf_class_ready', array($this, 'init'), 10, 1);
    }
    public function init($BeRocket_AAPF) {
        $filter_nn_name = apply_filters('berocket_aapf_filter_variable_name_nn', 'filters');
        if(defined('DOING_AJAX') && DOING_AJAX && !empty($_POST['action']) && $_POST['action'] == 'wcpt_load_products') {
            if( ! empty($_POST[$filter_nn_name]) ) {
                $options = $BeRocket_AAPF->get_option();
                if( empty($options['seo_uri_decode']) ) {
                    $_GET[$filter_nn_name] = $_POST[$filter_nn_name];
                } else {
                    $_GET[$filter_nn_name] = urldecode($_POST[$filter_nn_name]);
                }
            }
            $table_id = filter_input( INPUT_POST, 'table_id', FILTER_SANITIZE_STRING );
            $table_transient = get_transient( $table_id );
            unset($table_transient['total_posts']);
            unset($table_transient['total_filtered_posts']);
            set_transient( $table_id, $table_transient, DAY_IN_SECONDS );
        }
    }
	public static function check_old_version() {
		return ( class_exists('WC_Product_Table_Plugin')
        && ( function_exists( 'Barn2\Plugin\WC_Product_Table\wc_product_table' )
             || function_exists( 'Barn2\Plugin\WC_Product_Table\wpt' ) 
             || (function_exists( 'wc_product_table' ) && version_compare(WC_Product_Table_Plugin::VERSION, '2.1.3', '>')) ) );
	}
	public static function check_new_version() {
		return (function_exists( 'Barn2\Plugin\WC_Product_Table\wpt' ) && ! empty(Barn2\Plugin\WC_Product_Table\PLUGIN_VERSION)
		&& version_compare(Barn2\Plugin\WC_Product_Table\PLUGIN_VERSION, '2.1.3', '>')); 
	}
    public static function plugins_loaded() {
        if( self::check_old_version() || self::check_new_version() ) {
            add_filter('aapf_localize_widget_script', array( __CLASS__, 'aapf_localize_widget_script' ));
            add_action( 'wc_product_table_get_table', array( __CLASS__, 'wc_product_table_get_table' ), 10, 1 );
            add_action( 'wc_product_table_after_get_table', array( __CLASS__, 'wc_product_table_get_table' ), 10, 1 );
            add_action( 'wp_footer', array( __CLASS__, 'set_scripts' ), 9000 );
            self::not_ajax_functions();
            $wcpt_shortcode_defaults = get_option('wcpt_shortcode_defaults');
            $wcpt_shortcode_defaults['berocket_ajax'] = '1';
            update_option('wcpt_shortcode_defaults', $wcpt_shortcode_defaults);
        }
    }
    public static function wc_product_table_get_table($table) {
        $table_args = $table->args->get_args();
        $table->query->get_total_products();
        if( ! empty($table_args['berocket_ajax'])
        && method_exists($table->data_table, 'add_above')
        && method_exists($table->data_table, 'add_below') ) {
            $table->data_table->add_above('<div class="berocket_product_table_compat">');
            $table->data_table->add_below('</div>');
        }
    }
    public static function not_ajax_functions() {
        add_filter( 'wc_product_table_query_args', array( __CLASS__, 'woocommerce_shortcode_products_query' ), 100, 2 );
    }
    public static function woocommerce_shortcode_products_query( $query_vars, $table ) {
        $table_args = $table->args->get_args();
        if( empty($table_args['berocket_ajax']) ) {
            return $query_vars;
        }
        $query_vars = apply_filters('bapf_uparse_apply_filters_to_query_vars_save', $query_vars);
        global $berocket_parse_page_obj;
        $berocket_parse_page_obj->save_shortcode_query_vars($query_vars);
        return $query_vars;
    }
    public static function aapf_localize_widget_script($localize) {
        $localize['products_holder_id'] .= ( empty($localize['products_holder_id']) ? '' : ', ' ) . '.berocket_product_table_compat';
        return $localize;
    }
    public static function set_scripts() {
        $html = '<script>function bapf_barn2_product_table_reinit() {
            try {
                if( typeof(jQuery(".berocket_product_table_compat .wc-product-table").productTable) == "function" && ! jQuery(".berocket_product_table_compat > .dataTables_wrapper").length ) {jQuery(".berocket_product_table_compat .wc-product-table").productTable();}
            } catch(err){}
        };jQuery(document).on("berocket_ajax_products_loaded", bapf_barn2_product_table_reinit);</script>';
        echo $html;
    }
}
