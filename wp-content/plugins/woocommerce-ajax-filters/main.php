<?php
define( "BeRocket_AJAX_domain", 'BeRocket_AJAX_domain');
define( "BeRocket_AJAX_cache_expire", '21600' );
define( "AAPF_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_AJAX_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once dirname( __FILE__ ) . '/wizard/main.php';
include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/product-table.php");
include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/jet_smart_filters.php");
include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/wp-rocket.php");
$br_aapf_debugs = array();
include_once(plugin_dir_path( __FILE__ ) . "libraries/link_parser.php");
include_once(plugin_dir_path( __FILE__ ) . 'includes/filters/get_terms.php');
include_once(plugin_dir_path( __FILE__ ) . 'includes/filters/get_terms_additional.php');

class BeRocket_AAPF extends BeRocket_Framework {
    public static $user_can_manage = false;
    public static $current_page_filters = array('added' => array());
    public static $settings_name = 'br_filters_options';
    public $info, $defaults, $values, $notice_array, $conditions;
    protected static $instance;
    protected $disable_settings_for_admin = array(
        array('javascript', 'berocket_ajax_filtering_start'),
        array('javascript', 'berocket_ajax_filtering_on_update'),
        array('javascript', 'berocket_ajax_products_loaded'),
    );
    static $the_ajax_script = array();
    public static $debug_mode = false;
    public static $error_log = array();
    public static $the_ajax_script_initialized = false;
    public $default_permalink = array (
        'variable' => 'filters',
        'value'    => '/values',
        'split'    => '/',
    );
    public $default_nn_permalink = array (
        'variable' => 'filters',
        'value'    => '[values]',
        'split'    => '|',
    );
    protected $check_init_array = array(
        array(
            'check' => 'woocommerce_version',
            'data' => array(
                'version' => '3.0',
                'operator' => '>=',
                'notice'   => 'Plugin WooCommerce AJAX Products Filter required WooCommerce version 3.0 or higher'
            )
        ),
        array(
            'check' => 'framework_version',
            'data' => array(
                'version' => '2.5.6',
                'operator' => '>=',
                'notice'   => 'Please update all BeRocket plugins to the most recent version. WooCommerce AJAX Products Filter is not working correctly with older versions.'
            )
        ),
    );
    function __construct () {
        global $berocket_unique_value, $bapf_unique_id;
        $berocket_unique_value = 1;
        $bapf_unique_id = 0;
        $this->info = array(
            'id'                => 1,
            'version'           => BeRocket_AJAX_filters_version,
            'plugin'            => '',
            'slug'              => '',
            'key'               => '',
            'name'              => '',
            'plugin_name'       => 'ajax_filters',
            'full_name'         => __('WooCommerce AJAX Products Filter', 'BeRocket_AJAX_domain'),
            'norm_name'         => __('Product Filters', 'BeRocket_AJAX_domain'),
            'price'             => '',
            'domain'            => 'BeRocket_AJAX_domain',
            'templates'         => AAPF_TEMPLATE_PATH,
            'plugin_file'       => BeRocket_AJAX_filters_file,
            'plugin_dir'        => __DIR__,
            'feature_template'  => __DIR__ . '/templates/free/features.php'
        );
        $this->defaults = array(
            'plugin_key'                      => '',
            'pos_relative'                    => '1',
            'products_holder_id'              => 'ul.products',
            'woocommerce_result_count_class'  => '.woocommerce-result-count',
            'woocommerce_ordering_class'      => 'form.woocommerce-ordering',
            'woocommerce_pagination_class'    => '.woocommerce-pagination',
            'woocommerce_removes'             => array(
                'result_count'                => '',
                'ordering'                    => '',
                'pagination'                  => '',
                'pagination_ajax'             => '',
            ),
            'attribute_count'                 => '',
            'control_sorting'                 => '1',
            'seo_friendly_urls'               => '1',
            'seo_uri_decode'                  => '',
            'recount_hide'                    => 'removeRecount',
            'slug_urls'                       => '',
            'seo_meta_title'                  => '',
            'seo_element_title'               => '',
            'seo_element_header'              => '',
            'seo_element_description'         => '',
            'seo_meta_title_visual'           => 'BeRocket_AAPF_wcseo_title_visual1',
            'filters_turn_off'                => '',
            'hide_value'                      => array(
                'o'                           => '1',
                'sel'                         => '',
                'empty'                       => '1',
                'button'                      => '1',
            ),
            'use_select2'                     => '',
            'fixed_select2'                   => '',
            'scroll_shop_top'                 => '',
            'scroll_shop_top_px'              => '-180',
            'selected_area_show'              => '',
            'selected_area_hide_empty'        => '',
            'products_only'                   => '1',
            'out_of_stock_variable'           => '',
            'out_of_stock_variable_reload'    => '',
            'page_same_as_filter'             => '',
            'styles_in_footer'                => '',
            
            'styles_input'                    => array(
                'checkbox'               => array( 'bcolor' => '', 'bwidth' => '', 'bradius' => '', 'fcolor' => '', 'backcolor' => '', 'icon' => '', 'fontsize' => '', 'theme' => '' ),
                'radio'                  => array( 'bcolor' => '', 'bwidth' => '', 'bradius' => '', 'fcolor' => '', 'backcolor' => '', 'icon' => '', 'fontsize' => '', 'theme' => '' ),
                'slider'                 => array( 'line_color' => '', 'line_height' => '', 'line_border_color' => '', 'line_border_width' => '', 'button_size' => '',
                                                   'button_color' => '', 'button_border_color' => '', 'button_border_width' => '', 'button_border_radius' => '' ),
                'pc_ub'                  => array( 'back_color' => '', 'border_color' => '', 'font_size' => '', 'font_color' => '', 'show_font_size' => '', 'close_size' => '',
                                                   'show_font_color' => '', 'show_font_color_hover' => '', 'close_font_color' => '', 'close_font_color_hover' => '' ),
                'product_count'          => 'round',
                'product_count_position' => '',
            ),
            'child_pre_indent'       => '',
            'ajax_load_icon'                  => '',
            'ajax_load_text'                  => array(
                'top'                         => '',
                'bottom'                      => '',
                'left'                        => '',
                'right'                       => '',
            ),
            'description'                     => array(
                'show'                        => 'click',
                'hide'                        => 'click',
            ),
            'javascript'                       => array(
                'berocket_ajax_filtering_start'     => '',
                'berocket_ajax_filtering_on_update' => '',
                'berocket_ajax_products_loaded'     => '',
            ),
            'custom_css'                      => '',
            'user_custom_css'                 => '',
            'br_opened_tab'                   => 'general',
            'tags_custom'                     => '1',
            'ajax_site'                       => '',
            'search_fix'                      => '1',
            'use_tax_for_price'               => '',
            'disable_font_awesome'            => '',
            'debug_mode'                      => '',
            'fontawesome_frontend_disable'    => '',
            'fontawesome_frontend_version'    => '',
            'addons'                          => array(
                DIRECTORY_SEPARATOR . 'additional_tables' . DIRECTORY_SEPARATOR . 'additional_tables.php'
            )
        );
        $this->values = array(
            'settings_name' => 'br_filters_options',
            'option_page'   => 'br-product-filters',
            'premium_slug'  => 'woocommerce-ajax-products-filter',
            'free_slug'     => 'woocommerce-ajax-filters',
        );
        if( version_compare(self::$framework_version, '2.5.5.2', '<') ) {
            unset($this->defaults['addons']);
            $option_fix = get_option($this->values['settings_name']);
            if( is_array($option_fix) && isset($option_fix['addons']) && is_array($option_fix['addons']) && (! count($option_fix['addons']) || empty($option_fix['addons'][0])) ) {
                $option_fix['addons'] = '';
            }
            update_option($this->values['settings_name'], $option_fix);
            unset($option_fix);
        }
        $this->feature_list = array();
        $this->framework_data['fontawesome_frontend'] = true;
        $this->active_libraries = array('addons', 'feature', 'tippy', 'popup', 'tutorial');

        if( method_exists($this, 'include_once_files') ) {
            $this->include_once_files();
        }
        if ( $this->init_validation() ) {
            //INIT ADITIONAL CLASSES
            BeRocket_AAPF_single_filter::getInstance();
            BeRocket_AAPF_group_filters::getInstance();
            new BeRocket_AAPF_compat_JetSmartFilter();
            add_action('vc_before_init', 'berocket_filter_vc_before_init', 100000);
            //----------------------
        }
        parent::__construct( $this );

        if ( $this->init_validation() ) {
            new BeRocket_AAPF_get_terms();
            new BeRocket_AAPF_get_terms_additionals();
            new BeRocket_AAPF_compat_product_table();
        }
        if ( ! function_exists('is_network_admin') || ! is_network_admin() ) {
            if( $this->check_framework_version() ) {
                if ( $this->init_validation() ) {
                    //NEW features
                    $this->parse_header_info();
                    //OLD features
                    $last_version = get_option('br_filters_version');
                    if( $last_version === FALSE ) $last_version = 0;
                    if ( version_compare($last_version, BeRocket_AJAX_filters_version, '<') ) {
                        $this->update_from_older ( $last_version );
                    }
                    unset($last_version);

                    $option = $this->get_option();
                    if( class_exists('BeRocket_updater') && property_exists('BeRocket_updater', 'debug_mode') ) {
                        self::$debug_mode = ! empty(BeRocket_updater::$debug_mode);
                    }
                    add_filter( 'BeRocket_updater_error_log', array( $this, 'add_error_log' ) );
                    if ( self::$debug_mode ) {
                        self::$error_log['1_settings'] = $option;
                    }
                    add_action( 'wp', array($this, 'register_frontend_assets'));
                    if ( isset($_GET['legacy-widget-preview'], $_GET['legacy-widget-preview']['idBase']) && in_array($_GET['legacy-widget-preview']['idBase'], array('berocket_aapf_single', 'berocket_aapf_group')) ) {
                        add_action( 'admin_init', array($this, 'register_frontend_assets'));
                    } else {
                        add_action( 'admin_init', array($this, 'register_admin_assets'));
                    }

                    add_action( 'divi_extensions_init', array($this, 'divi_extensions_init') );
                    add_action( 'admin_init', array( $this, 'admin_init' ) );
                    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
                    add_action( 'init', array( $this, 'create_metadata_table' ), 999999999 );
                    add_action( 'br_footer_script', array( $this, 'include_all_scripts' ) );
                    add_action( 'delete_transient_wc_products_onsale', array( $this, 'delete_products_not_on_sale' ) );
                    add_action( 'bapf_select2_load', array($this, 'select2_load') );
                    add_action( 'bapf_include_all_tempate_styles', array($this, 'include_all_tempate_styles'), 900 );

                    add_action ( 'widgets_init', array( $this, 'widgets_init' ), 1);
                    if ( defined('DOING_AJAX') && DOING_AJAX ) {
                        $this->ajax_functions();
                    }
                    if ( self::where_load_styles_scripts() ) {
                        if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
                            $this->not_ajax_functions();
                        }
                        if ( ! empty($option['selected_area_show']) ) {
                            add_action ( br_get_value_from_array($option, 'elements_position_hook', 'woocommerce_archive_description'), array($this, 'selected_area'), 1 );
                        }
                        if( empty($option['styles_in_footer']) ) {
                            add_action( 'wp_enqueue_scripts', array( $this, 'include_all_styles' ) );
                        }
                        add_filter( 'is_active_sidebar', array($this, 'is_active_sidebar'), 10, 2);
                        if( ! empty($option['page_same_as_filter']) ) {
                            include_once( dirname( __FILE__ ) . '/includes/addons/page-same-as-filter.php' );
                            new BeRocket_AAPF_addon_page_same_as_filter($option['page_same_as_filter']);
                        }
                        add_action('plugins_loaded', array($this, 'plugins_loaded'));
                    }
                    if( ! empty($option['products_only']) ) {
                        add_filter('woocommerce_is_filtered', array($this, 'woocommerce_is_filtered'));
                    }
                    if( ! empty($option['search_fix']) ) {
                        add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
                    }
                    if( ! empty($option['out_of_stock_variable']) ) {
                        include_once( dirname( __FILE__ ) . '/includes/addons/new-woocommerce-variation.php' );
                    }
                    if( ! empty($option['seo_meta_title']) ) {
                        include_once( dirname( __FILE__ ) . '/includes/addons/seo_meta_title.php' );
                    }
                    if( ! empty($option['pagination_fix']) ) {
                        include_once( dirname( __FILE__ ) . '/includes/fixes/pagination.php' );
                    }
                    $plugin_base_slug = plugin_basename( __FILE__ );
                    add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
                    add_filter( 'plugin_action_links_' . $plugin_base_slug, array( $this, 'plugin_action_links' ) );
                    add_filter( 'berocket_aapf_widget_terms', array($this, 'wpml_attribute_slug_translate'));
                    add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post') );
                    add_action('woocommerce_before_template_part', array($this, 'no_products_block_before'), 1, 1);
                    add_action('woocommerce_after_template_part', array($this, 'no_products_block_after'), 999999, 1);
                    add_action('braapf_wp_enqueue_style_after', array($this, 'custom_user_css'), 10, 1);
                    add_action('wp_footer', array($this, 'bapf_wp_footer'), 900000);
                } else {
                    if( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
                        add_action( 'admin_notices', array( $this, 'update_woocommerce' ) );
                    } else {
                        add_action( 'admin_notices', array( $this, 'no_woocommerce' ) );
                    }
                }
                do_action('bapf_class_ready', $this);
            } else {
                add_filter( 'berocket_display_additional_notices', array(
                    $this,
                    'old_framework_notice'
                ) );
            }
            add_filter('BRaapf_cache_check_md5', array($this, 'BRaapf_cache_check_md5'));
        }
    }
    public function br_get_template_part( $name = '' ) {
        $template = '';

        // Look in your_child_theme/woocommerce-%PLUGINNAME%/name.php
        if ( $name && strpos($name, 'old_templates') !== FALSE ) {
            $new_name = str_replace('old_templates/', '', $name);
            $template = locate_template( "woocommerce-" . $this->info[ 'plugin_name' ] . "/{$new_name}.php" );
        }

        // Look in your_child_theme/woocommerce-%PLUGINNAME%/name.php
        if ( ! $template && $name ) {
            $template = locate_template( "woocommerce-" . $this->info[ 'plugin_name' ] . "/{$name}.php" );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( $this->info[ 'templates' ] . "{$name}.php" ) ) {
            $template = $this->info[ 'templates' ] . "{$name}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( $this->info[ 'plugin_name' ] . '_get_template_part', $template, $name );

        if ( $template ) {
            load_template( $template, false );
        }
    }
    function include_once_files() {
        parent::include_once_files();
    }
    function init_validation() {
        return parent::init_validation() && ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) &&
                                              br_get_woocommerce_version() >= 2.1 );
    }
    function check_framework_version() {
        return ( ! empty(BeRocket_Framework::$framework_version) && version_compare(BeRocket_Framework::$framework_version, 2.1, '>=') );
    }
    function old_framework_notice($notices) {
        $notices[] = array(
            'start'         => 0,
            'end'           => 0,
            'name'          => $this->info[ 'plugin_name' ].'_old_framework',
            'html'          => __('<strong>Please update all BeRocket plugins to the most recent version. WooCommerce AJAX Products Filter is not working correctly with older versions.</strong>', 'BeRocket_AJAX_domain'),
            'righthtml'     => '',
            'rightwidth'    => 0,
            'nothankswidth' => 0,
            'contentwidth'  => 1600,
            'subscribe'     => false,
            'priority'      => 10,
            'height'        => 50,
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
    public function register_admin_assets() {
        wp_register_style( 'berocket_aapf_widget-admin-style',
        plugins_url( 'assets/admin/css/admin.css', __FILE__ ),
        "",
        $this->info['version'] );
        wp_register_style( 'brjsf-ui',
            plugins_url( 'assets/admin/css/brjsf.css', __FILE__ ),
            "",
            $this->info['version'] );
        wp_register_script( 'brjsf-ui',
            plugins_url( 'assets/admin/js/brjsf.js', __FILE__ ),
            array( 'jquery' ),
            $this->info['version'] );
        wp_register_script( 'berocket_aapf_widget-admin',
            plugins_url( 'assets/admin/js/admin.js', __FILE__ ),
            array( 'jquery' ),
            $this->info['version'],
            false );
        wp_localize_script(
            'berocket_aapf_widget-admin',
            'aapf_admin_text',
            array(
                'checkbox_text' => __('Checkbox', 'BeRocket_AJAX_domain'),
                'radio_text' => __('Radio', 'BeRocket_AJAX_domain'),
                'select_text' => __('Select', 'BeRocket_AJAX_domain'),
                'color_text' => __('Color', 'BeRocket_AJAX_domain'),
                'image_text' => __('Image', 'BeRocket_AJAX_domain'),
                'slider_text' => __('Slider', 'BeRocket_AJAX_domain'),
                'tag_cloud_text' => __('Tag cloud', 'BeRocket_AJAX_domain'),
            )
        );
        
        wp_register_script('braapf-javascript-hide',
            plugins_url( '/assets/admin/js/javascript_hide.js', BeRocket_AJAX_filters_file ),
            array('jquery') );
        wp_register_script('braapf-single-filter-edit',
            plugins_url( '/assets/admin/js/single_filter_edit.js', BeRocket_AJAX_filters_file ),
            array('jquery', 'braapf-javascript-hide') );
        wp_register_style( 'braapf-single-filter-edit',
            plugins_url( '/assets/admin/css/single_filter_edit.css', BeRocket_AJAX_filters_file ));
        
        wp_register_script( 'berocket_wizard_autoselect',
            plugins_url( 'wizard/wizard.js', __FILE__ ),
            array( 'jquery' ) );
        wp_register_style( 'berocket_wizard_autoselect',
            plugins_url( 'wizard/wizard.css', __FILE__ ) );
        wp_register_style( 'wizard-setup',
            plugins_url( 'wizard/admin.css', __FILE__ ) );
        
        BeRocket_AAPF::wp_enqueue_style( 'berocket_aapf_widget-admin-style' );
    }
    public static $concat_enqueue_files = false;
    public function register_frontend_assets() {
        self::$concat_enqueue_files = ( file_exists(__DIR__ . '/assets/frontend/js/main.min.js') && file_exists(__DIR__ . '/assets/frontend/css/main.min.css') );
        $option = $this->get_option();
        wp_register_script( 'berocket_aapf_jquery-slider-fix',
            plugins_url( 'assets/frontend/js/jquery.ui.touch-punch.min.js', __FILE__ ),
            array( 'jquery-ui-slider' ),
            $this->info['version'] );
        wp_register_script( 'select2',
            plugins_url( 'assets/frontend/js/select2.min.js', __FILE__ ),
            array( 'jquery' ) );
        wp_register_script( 'berocket_aapf_widget-scroll-script',
            plugins_url( 'assets/frontend/js/Scrollbar.concat.min.js', __FILE__ ),
            array( 'jquery' ),
            $this->info['version'] );
        wp_register_style ( 'select2',
            plugins_url( 'assets/frontend/css/select2.min.css', __FILE__ ) );
        wp_register_style ( 'br_select2',
            plugins_url( 'assets/frontend/css/select2.fixed.css', __FILE__ ) );
        wp_register_style ( 'jquery-ui-datepick',
            plugins_url( 'assets/frontend/css/jquery-ui.min.css', __FILE__ ) );
        wp_register_style ( 'berocket_aapf_widget-scroll-style',
            plugins_url( 'assets/frontend/css/Scrollbar.min.css', __FILE__ ),
            "",
            $this->info['version'] );
        wp_register_style( 'berocket_aapf_widget-themes',
            plugins_url( (self::$concat_enqueue_files ? 'assets/frontend/css/themes.min.css' : 'assets/frontend/css/themes.css'), __FILE__ ),
            "" );
        if( self::$concat_enqueue_files && apply_filters('bapf_isoption_ajax_site', ! empty($option['ajax_site'])) ) {
            wp_register_script( 'berocket_aapf_widget-script',
                plugins_url( 'assets/frontend/js/fullmain.min.js', __FILE__ ),
                array( 'jquery-ui-slider', 'jquery-ui-datepicker' ),
                $this->info['version'] );
        } else {
            wp_register_script( 'berocket_aapf_widget-script',
            plugins_url( ( self::$concat_enqueue_files ? 'assets/frontend/js/main.min.js' : 'assets/frontend/js/widget.min.js'), __FILE__ ),
            array( 'jquery', 'jquery-ui-slider' ),
            $this->info['version'] );
        }
        do_action('braapf_register_frontend_assets');
        if( self::where_load_styles_scripts() && apply_filters('bapf_isoption_ajax_site', ! empty($option['ajax_site'])) ) {
            self::require_all_scripts();
            do_action('br_footer_script');
        }
        if( self::$concat_enqueue_files && empty($option['styles_in_footer']) ) {
        wp_register_style ( 'berocket_aapf_widget-style',
            plugins_url( 'assets/frontend/css/fullmain.min.css', __FILE__ ),
            "",
            $this->info['version'] );
        } else {
            wp_register_style ( 'berocket_aapf_widget-style',
                plugins_url( ( self::$concat_enqueue_files ? 'assets/frontend/css/main.min.css' : 'assets/frontend/css/widget.css'), __FILE__ ),
                "",
                $this->info['version'] );
        }
        if( self::where_load_styles_scripts() && empty($option['styles_in_footer']) ) {
            self::require_all_styles();
        }
    }
    public static function where_load_styles_scripts() {
        return (! is_admin() || ( isset($_GET['legacy-widget-preview'], $_GET['legacy-widget-preview']['idBase']) && in_array($_GET['legacy-widget-preview']['idBase'], array('berocket_aapf_single', 'berocket_aapf_group')) ) );
    }
    public static function require_all_scripts($old = false) {
        $scripts = apply_filters('bapf_require_all_scripts_array', array('berocket_aapf_widget-script', 'berocket_aapf_jquery-slider-fix', 'select2', 'berocket_aapf_widget-scroll-script'), $old);
        foreach($scripts as $script) {
            if( $old ) {
                wp_enqueue_script( $script );
            } else {
                self::wp_enqueue_script( $script );
            }
        }
        do_action('bapf_include_all_tempate_styles');
        $styles = apply_filters('BeRocket_AAPF_getall_Template_Styles', array());
        $templates = array();
        foreach($styles as $style_id => $style_data) {
            $style_data['this']->enqueue_all();
        }
    }
    public static function require_all_styles($old = false) {
        $styles = array('berocket_aapf_widget-style', 'select2', 'jquery-ui-datepick', 'berocket_aapf_widget-scroll-style', 'berocket_aapf_widget-themes');
        foreach($styles as $style) {
            if( $old ) {
                wp_enqueue_style( $style );
            } else {
                self::wp_enqueue_style( $style );
            }
        }
    }
    public static function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {
        $this_instance = self::getInstance();
        $option = $this_instance->get_option();
        if( apply_filters('bapf_isoption_ajax_site', ! empty($option['ajax_site'])) && self::$concat_enqueue_files 
        && ( in_array($handle, array('berocket_aapf_jquery-slider-fix', 'select2', 'berocket_aapf_widget-scroll-script'))
        || strpos($handle, 'BeRocket_AAPF_script-') !== FALSE ) ) {
            self::wp_enqueue_script( 'berocket_aapf_widget-script');
        } else {
            do_action('braapf_wp_enqueue_script_before', $handle, $src, $deps, $ver, $in_footer);
            wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
            do_action('braapf_wp_enqueue_script_after', $handle, $src, $deps, $ver, $in_footer);
        }
    }
    public static function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {
        $this_instance = self::getInstance();
        $option = $this_instance->get_option();
        if( empty($option['styles_in_footer']) && self::$concat_enqueue_files
        && ( in_array($handle, array('select2', 'jquery-ui-datepick', 'berocket_aapf_widget-scroll-style', 'berocket_aapf_widget-themes')) 
        || strpos($handle, 'BeRocket_AAPF_style-') !== FALSE ) ) {
            self::wp_enqueue_style( 'berocket_aapf_widget-style');
        } else {
            do_action('braapf_wp_enqueue_style_before', $handle, $src, $deps, $ver, $media);
            wp_enqueue_style($handle, $src, $deps, $ver, $media);
            do_action('braapf_wp_enqueue_style_after', $handle, $src, $deps, $ver, $media);
        }
    }
    public function init () {
        parent::init();
        $option = $this->get_option();
        self::$user_can_manage = current_user_can( 'manage_berocket_aapf' );
        if( self::$user_can_manage && ! is_admin() && empty($option['disable_admin_bar']) ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/admin/admin_bar.php");
        }
        if( ! empty($option['use_tax_for_price']) ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/addons/price_include_tax.php");
        }
        if( ! empty($option['disable_font_awesome']) ) {
            wp_dequeue_style( 'font-awesome' );
        }
    }
    public function plugins_loaded() {
        include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/divi-theme-builder.php");
        include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/beaver-builder.php");
        if( defined( 'ELEMENTOR_PRO_VERSION') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/elementor-pro.php");
        }
        if( class_exists('RankMath') ) {
            include(plugin_dir_path( __FILE__ ) . "includes/compatibility/rank_math_seo.php");
        }
        if( function_exists('wmc_get_price') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/woo-multi-currency.php");
        }
        if( defined('WOOCS_VERSION') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/woocs.php");
        }
        if ( ((defined( 'WCML_VERSION' ) || defined('POLYLANG_VERSION')) && defined( 'ICL_LANGUAGE_CODE' )) || function_exists('wpm_get_language') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/wpml.php");
        }
        if( class_exists('WCPBC_Pricing_Zones') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/price-based-on-country.php");
        }
        if( defined( 'DE_DB_WOO_VERSION' ) ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/bodycommerce.php");
        }
        if( defined( 'WCJ_PLUGIN_FILE' ) ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/woojetpack.php");
        }
        if( function_exists('relevanssi_do_query') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/relevanssi.php");
        }
        if( function_exists('premmerce_multicurrency') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/premmerce-multicurrency.php");
        }
        if( ! empty($GLOBALS['woocommerce-aelia-currencyswitcher']) ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/aelia-currencyswitcher.php");
        }
        if( defined( 'SEARCHWP_WOOCOMMERCE_VERSION') ) {
            include_once(plugin_dir_path( __FILE__ ) . "includes/compatibility/wpsearch_wc_compatibility.php");
        }
        if( apply_filters('BeRocket_AAPF_widget_load_file', true) ) {
            foreach (glob(__DIR__ . "/includes/display_filter/*.php") as $filename)
            {
                include_once($filename);
            }
            include_once(__DIR__ . "/includes/filters/dynamic_data_template.php");
            new BeRocket_AAPF_dynamic_data_template();
            require_once dirname( __FILE__ ) . '/includes/filters/display_widget.php';
        }
    }
    public function register_admin_scripts(){
        wp_enqueue_script( 'brjsf-ui');
        wp_enqueue_style( 'brjsf-ui' );
        wp_enqueue_style( 'font-awesome' );
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        do_action('bapf_include_all_tempate_styles');
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                    'name' => __( 'General', "BeRocket_AJAX_domain" )
                ),
                'Elements' => array(
                    'icon' => 'bars',
                    'name' => __( 'Elements', "BeRocket_AJAX_domain" )
                ),
                'Selectors' => array(
                    'icon' => 'circle-o',
                    'name' => __( 'Selectors', "BeRocket_AJAX_domain" )
                ),
                'SEO' => array(
                    'icon' => 'html5',
                    'name' => __( 'SEO', "BeRocket_AJAX_domain" )
                ),
                'Advanced' => array(
                    'icon' => 'cogs',
                    'name' => __( 'Advanced', "BeRocket_AJAX_domain" )
                ),
                'Design' => array(
                    'icon' => 'eye',
                    'name' => __( 'Design', "BeRocket_AJAX_domain" )
                ),
                'JavaScript/CSS' => array(
                    'icon' => 'css3',
                    'name' => __( 'JavaScript/CSS', "BeRocket_AJAX_domain" )
                ),
                'Addons' => array(
                    'icon' => 'plus',
                    'name' => __( 'Add-ons', "BeRocket_AJAX_domain" )
                ),
                'Tutorials' => array(
                    'icon' => 'question',
                    'name' => __( 'Tutorials', "BeRocket_AJAX_domain" )
                ),
                'Filters' => array(
                    'icon' => 'plus-square',
                    'link' => admin_url( 'edit.php?post_type=br_product_filter' ),
                    'name' => __( 'Filters', "BeRocket_AJAX_domain" )
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'link' => admin_url( 'admin.php?page=berocket_account' ),
                    'name' => __( 'License', "BeRocket_AJAX_domain" )
                ),
            ),
            array(
                'General' => array(
                    /*'setup_wizard' => array(
                        "section"   => "setup_wizard",
                        "value"     => "",
                    ),*/
                    'attribute_count' => array(
                        "label"     => __( 'The number of Attribute Values', "BeRocket_AJAX_domain" ),
                        "type"      => "number",
                        "name"      => "attribute_count",
                        "value"     => $this->defaults["attribute_count"],
                        'label_for' => __( 'Number of Attribute values that will be displayed. Other values will be hidden and can be displayed by pressing the button. Option <strong>Hide the Show/Hide value(s) button in the filters</strong> must be disabled', 'BeRocket_AJAX_domain' ),
                    ),
                    'scroll_shop_top' => array(
                        "label"     => __( 'Scroll top', "BeRocket_AJAX_domain" ),
                        "items"     => array(
                            'scroll_shop_top' => array(
                                "label"     => __( 'Selected filters position', "BeRocket_AJAX_domain" ),
                                "name"     => "scroll_shop_top",
                                "type"     => "selectbox",
                                "class"     => "br_scroll_shop_top",
                                "options"  => array(
                                    array('value' => '0', 'text' => __('Disable', 'BeRocket_AJAX_domain')),
                                    array('value' => '1', 'text' => __('Mobile and Desktop', 'BeRocket_AJAX_domain')),
                                    array('value' => '2', 'text' => __('Mobile', 'BeRocket_AJAX_domain')),
                                    array('value' => '3', 'text' => __('Desktop', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => 'woocommerce_archive_description',
                            ),
                            array(
                                "type"      => "number",
                                "name"      => "scroll_shop_top_px",
                                "class"     => "br_scroll_shop_top_px",
                                "value"     => $this->defaults["scroll_shop_top_px"],
                                'label_for' => __("px from products top.", 'BeRocket_AJAX_domain') . ' ' . __('Use this to fix top scroll.', 'BeRocket_AJAX_domain'),
                            )
                        ),
                    ),
                    'recount_hide' => array(
                        "label"     => __( 'Values count and output', "BeRocket_AJAX_domain" ) . '<span id="braapf_recount_hide_info" class="dashicons dashicons-editor-help"></span>',
                        "name"     => "recount_hide",
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => 'disable',             'text' => __('All non-empty values are displayed; standard recounting is applied', 'BeRocket_AJAX_domain')),
                            array('value' => 'removeFirst',         'text' => __('All empty values are removed based on page (categories/tags/ etc.)', 'BeRocket_AJAX_domain')),
                            array('value' => 'recount',             'text' => __('All non-empty values are displayed; filters are applied in recounting attribute values', 'BeRocket_AJAX_domain')),
                            array('value' => 'removeFirst_recount', 'text' => __('All empty values are removed based on page (categories/tags/ etc.); filters are applied in recounting attribute values; all empty values based on applied filters will be hidden', 'BeRocket_AJAX_domain')),
                            array('value' => 'removeRecount',       'text' => __('Filters are applied in recounting attribute values; empty values are removed on the server-side', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                        "class"    => 'berocket_aapf_recount_hide'
                    ),
                    'hide_values' => array(
                        'label' => __('Hide values', 'BeRocket_AJAX_domain'),
                        'items' => array(
                            'hide_value_o' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'o'),
                                "value"     => '1',
                                'label_for'  => __("Hide values without products", 'BeRocket_AJAX_domain'),
                            ),
                            'hide_value_sel' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'sel'),
                                "value"     => '1',
                                'label_for'  => __("Hide selected values", 'BeRocket_AJAX_domain'),
                            ),
                            'hide_value_empty' => array(
                                "type"      => "checkbox",
                                "name"      => array("hide_value", 'empty'),
                                "value"     => '1',
                                'label_for'  => __("Hide empty widgets", 'BeRocket_AJAX_domain'),
                            ),
                        ),
                    ),
                    'header_part_variable' => array(
                        'section' => 'header_part',
                        "value"   => __('Variable Products and Variations options', 'BeRocket_AJAX_domain'),
                    ),
                    'out_of_stock_variable' => array(
                        "label"     => __( 'Hide out of stock <br>variations', "BeRocket_AJAX_domain" ). '<span id="braapf_out_of_stock_variable_info" class="dashicons dashicons-editor-help"></span>',
                        "tr_class"  => "tr_out_of_stock_variable",
                        "items" => array(
                            "out_of_stock_variable" => array(
                                "type"      => "selectbox",
                                "name"      => 'out_of_stock_variable',
                                "options"  => apply_filters('berocket_aapf_seo_meta_filters_hooks_list', array(
                                    array('value' => '0', 'text' => __('Disabled', 'BeRocket_AJAX_domain')),
                                    array('value' => '1', 'text' => __('Enabled', 'BeRocket_AJAX_domain')),
                                    array('value' => '2', 'text' => __('Controlled by "Out of stock" filter', 'BeRocket_AJAX_domain')),
                                )),
                                "value"     => '',
                                "class"     => "out_of_stock_variable",
                                'label_for' => __('Hide variable products, if variations with selected filters are out of stock', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'out_of_stock_variable_reload' => array(
                                "type"      => "checkbox",
                                "name"      => "out_of_stock_variable_reload",
                                "value"     => '1',
                                "class"     => "out_of_stock_variable_reload",
                                'label_for' => __('Use it for attributes values to display more correct count', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                    ),
                    'filter_price_variation' => array(
                        "label"     => __( 'Search variation price', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "filter_price_variation",
                        "value"     => '1',
                        'label_for' => __('Use variation price instead of product price. IMPORTANT! It can slow down filtering by price', 'BeRocket_AJAX_domain'),
                    ),
                ),
                'Elements' => array(
                    'elements_position_hook' => array(
                        "label"     => __( 'Selected filters position', "BeRocket_AJAX_domain" ),
                        "name"     => "elements_position_hook",
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => 'woocommerce_archive_description', 'text' => __('WooCommerce Description(in header)', 'BeRocket_AJAX_domain')),
                            array('value' => 'woocommerce_before_shop_loop', 'text' => __('WooCommerce Before Shop Loop', 'BeRocket_AJAX_domain')),
                            array('value' => 'woocommerce_after_shop_loop', 'text' => __('WooCommerce After Shop Loop', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => 'woocommerce_archive_description',
                    ),
                    'selected_area' => array(
                        "label"     => __( 'Display Selected Filters', "BeRocket_AJAX_domain" ) . '<span id="braapf_selected_area_show_info" class="dashicons dashicons-editor-help"></span>',
                        'items' => array(
                            'selected_area_show' => array(
                                "type"      => "checkbox",
                                "name"      => "selected_area_show",
                                "class"     => "br_selected_area_show",
                                "value"     => '1',
                                'label_for'  => __("Show selected filters above products", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'selected_area_hide_empty' => array(
                                "type"      => "checkbox",
                                "name"      => "selected_area_hide_empty",
                                "class"     => "br_selected_area_hide_empty",
                                "value"     => '1',
                                'label_for'  => __("Hide selected filters area if nothing selected(affect only area above products)", 'BeRocket_AJAX_domain'),
                            ),
                        )
                    ),
                ),
                'Selectors' => array(
                    'disable_ajax' => array(
                        "label"     => __( 'Disable AJAX loading', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "disable_ajax_loading",
                        "value"     => '1',
                        'class'     => 'berocket_disable_ajax_loading'
                    ),
                    'autoselector_set' => array(
                        "section"   => "autoselector",
                        "value"     => "",
                        "tr_class"  => "berocket_disable_ajax_loading_hide"
                    ),
                    'products_holder_id' => array(
                        "label"     => __( 'Products Selector', "BeRocket_AJAX_domain" ),
                        "type"      => "text",
                        "name"      => 'products_holder_id',
                        "value"     => $this->defaults["products_holder_id"],
                        "class"     => "berocket_aapf_products_selector",
                        "tr_class"  => "berocket_disable_ajax_loading_hide",
                        'label_for' => '<br>' . __("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain'),
                    ),
                    'result_count' => array(
                        "label"     => __( 'Products Quantity Selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_result_count_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_result_count_class',
                                "value"     => $this->defaults["woocommerce_result_count_class"],
                                "class"     => "berocket_aapf_product_count_selector",
                                'label_for' => '<br>' . __('Selector for tag with product result count("Showing 1–8 of 61 results"). Don\'t change this if you don\'t know what it is', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                        "tr_class"  => "berocket_disable_ajax_loading_hide"
                    ),
                    'ordering' => array(
                        "label"     => __( 'Products Sorting Selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_ordering_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_ordering_class',
                                "value"     => $this->defaults["woocommerce_ordering_class"],
                                'label_for' => '<br>' . __("Selector for order by form with drop down menu. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'control_sorting' => array(
                                "label"     => __( 'Sorting drop-down control', "BeRocket_AJAX_domain" ),
                                "type"      => "checkbox",
                                "name"      => "control_sorting",
                                "value"     => '1',
                                'label_for'  => __("Take control over WooCommerce's sorting selectbox?", 'BeRocket_AJAX_domain'),
                            ),
                        ),
                        "tr_class"  => "berocket_disable_ajax_loading_hide"
                    ),
                    'pagination' => array(
                        "label"     => __( 'Products Pagination Selector', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "woocommerce_pagination_class" => array(
                                "type"      => "text",
                                "name"      => 'woocommerce_pagination_class',
                                "value"     => $this->defaults["woocommerce_pagination_class"],
                                "class"     => "berocket_aapf_pagination_selector",
                                'label_for' => '<br>' . __("Selector for tag that is holding products. Don't change this if you don't know what it is", 'BeRocket_AJAX_domain') . '<br>',
                            ),
                            'pagination_ajax' => array(
                                "type"      => "checkbox",
                                "name"      => array("woocommerce_removes", "pagination_ajax"),
                                "value"     => '1',
                                'label_for' => __('Disable AJAX Pagination', 'BeRocket_AJAX_domain') . '<br>',
                            ),
                        ),
                        "tr_class"  => "berocket_disable_ajax_loading_hide"
                    ),
                ),
                'SEO' => array(
                    'seo_friendly_urls' => array(
                        "label"     => __( 'Refresh URL when filtering', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "seo_friendly_urls",
                        "value"     => '1',
                        'class'     => 'berocket_seo_friendly_urls',
                        'label_for' => __("If this option is on URL will be changed when filter is selected/changed", 'BeRocket_AJAX_domain'),
                    ),
                    'slug_urls' => array(
                        "label"     => __( 'Use slug in URL', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "slug_urls",
                        "value"     => '1',
                        'class'     => 'berocket_use_slug_in_url',
                        'label_for' => __("Use attribute slug instead ID", 'BeRocket_AJAX_domain'),
                    ),
                    'seo_uri_decode' => array(
                        "label"     => __( 'URL decoding', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "seo_uri_decode",
                        "value"     => '1',
                        'class'     => 'berocket_uri_decode',
                        'label_for' => __("Decode all symbols in URL to prevent errors on server side", 'BeRocket_AJAX_domain'),
                    ),
                    'seo_meta_title' => array(
                        "label"     => __( 'SEO Meta, Title', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "seo_meta_title",
                        "value"     => '1',
                        'class'     => 'berocket_seo_meta_title',
                        'label_for' => __("Meta Description, Page title and Page Header with filters", 'BeRocket_AJAX_domain'),
                    ),
                    'seo_meta_title_elements' => array(
                        "label"     => __( 'SEO Elements', "BeRocket_AJAX_domain" ),
                        "tr_class"  => "berocket_seo_meta_title_elements",
                        "items" => array(
                            "seo_element_title" => array(
                                "type"      => "checkbox",
                                "name"      => 'seo_element_title',
                                "value"     => '1',
                                'label_for' => __('Title', 'BeRocket_AJAX_domain'),
                            ),
                            'seo_element_header' => array(
                                "type"      => "checkbox",
                                "name"      => "seo_element_header",
                                "value"     => '1',
                                'label_for' => __('Header', 'BeRocket_AJAX_domain'),
                            ),
                            'seo_element_description' => array(
                                "type"      => "checkbox",
                                "name"      => "seo_element_description",
                                "value"     => '1',
                                'label_for' => __('Description', 'BeRocket_AJAX_domain'),
                            ),
                        ),
                    ),
                    'seo_meta_title_visual' => array(
                        "label"     => __( 'SEO elements structure', "BeRocket_AJAX_domain" ),
                        "tr_class"  => "berocket_seo_meta_title_elements",
                        "name"     => "seo_meta_title_visual",
                        "type"     => "selectbox",
                        "options"  => apply_filters('berocket_aapf_seo_meta_filters_hooks_list', array(
                            array('value' => 'BeRocket_AAPF_wcseo_title_visual1', 'text' => __('{title} with [attribute] [values] and [attribute] [values]', 'BeRocket_AJAX_domain')),
                            array('value' => 'BeRocket_AAPF_wcseo_title_visual2', 'text' => __('{title} [attribute]:[values];[attribute]:[values]', 'BeRocket_AJAX_domain')),
                            array('value' => 'BeRocket_AAPF_wcseo_title_visual3', 'text' => __('[attribute 1 values] {title} with [attribute] [values] and [attribute] [values]', 'BeRocket_AJAX_domain')),
                            array('value' => 'BeRocket_AAPF_wcseo_title_visual4', 'text' => __('{title} - [values] / [values]', 'BeRocket_AJAX_domain')),
                            array('value' => 'BeRocket_AAPF_wcseo_title_visual5', 'text' => __('[attribute]:[values];[attribute]:[values] - {title}', 'BeRocket_AJAX_domain')),
                        )),
                        "value"    => $this->defaults["seo_meta_title_visual"],
                    ),
                ),
                'Advanced' => array(
                    'framework_products_per_page' => array(
                        "label"     => __( 'Products per page', "BeRocket_AJAX_domain" ),
                        "type"      => "number",
                        "name"      => "framework_products_per_page",
                        "value"     => '',
                        'extra'     => 'placeholder="'.__( 'From WooCommerce', "BeRocket_AJAX_domain" ).'"'
                    ),
                    'products_only' => array(
                        "label"     => __( 'Display products', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "products_only",
                        "value"     => '1',
                        'label_for' => __('Always displays products, when filters are selected. Use the option when have categories and subcategories on the pages of your shop, and you want to display products when filtering.', 'BeRocket_AJAX_domain'),
                    ),
                    'use_tax_for_price' => array(
                        "label"    => __( 'Use Taxes in Price Filters', "BeRocket_AJAX_domain" ),
                        "label_for"=> __( 'Only Standard tax rates will be applied for prices', "BeRocket_AJAX_domain" ),
                        "name"     => "use_tax_for_price",
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => '', 'text' => __('Do not use (price from regular/sale field)', 'BeRocket_AJAX_domain')),
                            array('value' => 'var1', 'text' => __('Use taxes', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                    ),
                    'page_same_as_filter' => array(
                        "label"    => __( 'Page same as filter', "BeRocket_AJAX_domain" ) . '<span id="braapf_page_same_as_filter_info" class="dashicons dashicons-editor-help"></span>',
                        "name"     => "page_same_as_filter",
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => '', 'text' => __('Default', 'BeRocket_AJAX_domain')),
                            array('value' => 'remove', 'text' => __('Delete value', 'BeRocket_AJAX_domain')),
                            array('value' => 'leave', 'text' => __('Leave only one value', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                        "label_for" => __('On Category, Tag, Attribute page filter for it will remove value or leave only one value', 'BeRocket_AJAX_domain'),
                    ),
                    'reload_changed_filters' => array(
                        "label"     => __( 'Load products when URL changed', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "reload_changed_filters",
                        "value"     => '1',
                        'label_for' => __('Load products again when some filters not exist after filtering', 'BeRocket_AJAX_domain'),
                    ),
                    'header_part_tools' => array(
                        'section' => 'header_part',
                        "value"   => __('Tools', 'BeRocket_AJAX_domain'),
                    ),
                    'filters_turn_off' => array(
                        "label"     => __( 'Disable all filters', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "filters_turn_off",
                        "value"     => '1',
                        'label_for' => __("If you want to hide filters without losing current configuration just turn them off", 'BeRocket_AJAX_domain'),
                    ),
                    'disable_admin_bar' => array(
                        "label"     => __( 'Disable admin bar', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "disable_admin_bar",
                        "value"     => '1',
                        'label_for' => __("Disable panel in WordPress Admin Bar", 'BeRocket_AJAX_domain'),
                    ),
                    'purge_cache' => array(
                        "section"   => "purge_cache",
                        "value"     => "",
                    ),
                    'header_part_fixes' => array(
                        'section' => 'header_part',
                        "tr_class"  => "bapf_incompatibility_fixes_header",
                        "value"   => __('Incompatibility Fixes', 'BeRocket_AJAX_domain').'<i class="fa fa-chevron-down"></i>',
                    ),
                    'styles_in_footer' => array(
                        "tr_class"  => "bapf_incompatibility_fixes bapf_incompatibility_fixes_hide",
                        "label"     => __( 'Display styles only for pages with filters', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "styles_in_footer",
                        "value"     => '1',
                        'label_for' => __('On some sites it can cause visual problems on page load', 'BeRocket_AJAX_domain'),
                    ),
                    'ajax_site' => array(
                        "tr_class"  => "bapf_incompatibility_fixes bapf_incompatibility_fixes_hide",
                        "label"     => __( 'Fix for site with AJAX', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "ajax_site",
                        "value"     => '1',
                        'label_for' => __('Add Javascript files to all pages', 'BeRocket_AJAX_domain'),
                    ),
                    'pagination_fix' => array(
                        "tr_class"  => "bapf_incompatibility_fixes bapf_incompatibility_fixes_hide",
                        "label"     => __( 'Pagination replacement', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "pagination_fix",
                        "value"     => '1',
                        'label_for' => __('Add pagination replacement to fix pagiantion position', 'BeRocket_AJAX_domain'),
                    ),
                    'search_fix' => array(
                        "tr_class"  => "bapf_incompatibility_fixes bapf_incompatibility_fixes_hide",
                        "label"     => __( 'Fix for search page', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "search_fix",
                        "value"     => '1',
                        'label_for' => __('Disable redirection, when a search returns only one product', 'BeRocket_AJAX_domain'),
                    ),
                    'fixed_select2' => array(
                        "tr_class"  => "bapf_incompatibility_fixes bapf_incompatibility_fixes_hide",
                        "label"     => __( 'Select2 CSS', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "fixed_select2",
                        "class"     => "br_fixed_select2",
                        "value"     => '1',
                        'label_for' => __("Fixed CSS styles for Select2 (do not enable if Select2 work correct. Option can break Select2 in other plugins or themes)", 'BeRocket_AJAX_domain'),
                    ),
                ),
                'Design' => array(
                    'ajax_load_icon' => array(
                        "label"     => __( 'Loading icon', "BeRocket_AJAX_domain" ),
                        "type"      => "image",
                        "name"      => "ajax_load_icon",
                        "value"     => '',
                    ),
                    'ajax_load_text' => array(
                        "label"     => __( 'Loading icon text', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "top" => array(
                                "type"      => "text",
                                "name"      => array('ajax_load_text', 'top'),
                                "value"     => '1',
                                'label_be_for' => __('Above:', 'BeRocket_AJAX_domain'),
                            ),
                            "bottom" => array(
                                "type"      => "text",
                                "name"      => array('ajax_load_text', 'bottom'),
                                "value"     => '1',
                                'label_be_for' => __('Below:', 'BeRocket_AJAX_domain'),
                            ),
                            "left" => array(
                                "type"      => "text",
                                "name"      => array('ajax_load_text', 'left'),
                                "value"     => '1',
                                'label_be_for' => __('Before:', 'BeRocket_AJAX_domain'),
                            ),
                            "right" => array(
                                "type"      => "text",
                                "name"      => array('ajax_load_text', 'right'),
                                "value"     => '1',
                                'label_be_for' => __('After:', 'BeRocket_AJAX_domain'),
                            ),
                        ),
                    ),
                    'description_show' => array(
                        "label"    => __( 'Show and hide description', "BeRocket_AJAX_domain" ),
                        "name"     => array('description', 'show'),
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => 'click', 'text' => __('Click', 'BeRocket_AJAX_domain')),
                            array('value' => 'hover', 'text' => __('Hovering over the icon', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                        "label_be_for" => __('Show when user:', 'BeRocket_AJAX_domain'),
                    ),
                    'styles_input' => array(
                        "label"     => __( 'Style for number of products', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "product_count" => array(
                                "name"     => array('styles_input', 'product_count'),
                                "type"     => "selectbox",
                                "options"  => array(
                                    array('value' => '', 'text' => __('4', 'BeRocket_AJAX_domain')),
                                    array('value' => 'round', 'text' => __('(4)', 'BeRocket_AJAX_domain')),
                                    array('value' => 'quad', 'text' => __('[4]', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => '',
                            ),
                            "product_count_position" => array(
                                "name"     => array('styles_input', 'product_count_position'),
                                "type"     => "selectbox",
                                "options"  => array(
                                    array('value' => '', 'text' => __('Normal', 'BeRocket_AJAX_domain')),
                                    array('value' => 'right', 'text' => __('Right', 'BeRocket_AJAX_domain')),
                                    array('value' => 'right2em', 'text' => __('Right from name', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => '',
                                "label_be_for" => __('Position:', 'BeRocket_AJAX_domain'),
                            ),
                            "product_count_position_image" => array(
                                "name"     => array('styles_input', 'product_count_position_image'),
                                "type"     => "selectbox",
                                "options"  => array(
                                    array('value' => '', 'text' => __('Normal', 'BeRocket_AJAX_domain')),
                                    array('value' => 'right', 'text' => __('Right', 'BeRocket_AJAX_domain')),
                                ),
                                "value"    => '',
                                "label_be_for" => __('Position on Image:', 'BeRocket_AJAX_domain'),
                            ),
                        ),
                    ),
                    'child_pre_indent' => array(
                        "label"    => __( 'Indent for hierarchy in Drop-Down', "BeRocket_AJAX_domain" ),
                        "name"     => 'child_pre_indent',
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => '', 'text' => __('-', 'BeRocket_AJAX_domain')),
                            array('value' => 's', 'text' => __('space', 'BeRocket_AJAX_domain')),
                            array('value' => '2s', 'text' => __('2 spaces', 'BeRocket_AJAX_domain')),
                            array('value' => '4s', 'text' => __('tab', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                    ),
                    'header_part_tooltip' => array(
                        'section' => 'header_part',
                        "value"   => __('Tooltips Options', 'BeRocket_AJAX_domain'),
                    ),
                    'description_design' => array(
                        "label"     => __( 'Filters Description', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "tippy_theme" => array(
                                "type"      => "selectbox",
                                "name"      => 'tippy_description_theme',
                                "options"  => array(
                                    array('value' => 'light', 'text' => __('Light', 'BeRocket_AJAX_domain')),
                                    array('value' => 'dark', 'text' => __('Dark', 'BeRocket_AJAX_domain')),
                                    array('value' => 'translucent', 'text' => __('Translucent', 'BeRocket_AJAX_domain')),
                                ),
                                "value"     => '',
                                'label_be_for' => __('Tooltip Theme', 'BeRocket_AJAX_domain'),
                            ),
                            'tippy_fontsize' => array(
                                "type"         => "number",
                                "name"         => "tippy_description_fontsize",
                                "value"        => '',
                                'label_be_for' => __('Tooltip Font Size', 'BeRocket_AJAX_domain'),
                                'extra'        => 'placeholder="' . __('From Theme', 'BeRocket_AJAX_domain') . '"',
                            ),
                        ),
                    ),
                    'color_img_tooltip_design' => array(
                        "label"     => __( 'Color/Image Tooltip Name', "BeRocket_AJAX_domain" ),
                        "items" => array(
                            "tippy_theme" => array(
                                "type"      => "selectbox",
                                "name"      => 'tippy_color_img_theme',
                                "options"  => array(
                                    array('value' => 'light', 'text' => __('Light', 'BeRocket_AJAX_domain')),
                                    array('value' => 'dark', 'text' => __('Dark', 'BeRocket_AJAX_domain')),
                                    array('value' => 'translucent', 'text' => __('Translucent', 'BeRocket_AJAX_domain')),
                                ),
                                "value"     => '',
                                'label_be_for' => __('Tooltip Theme', 'BeRocket_AJAX_domain'),
                            ),
                            'tippy_fontsize' => array(
                                "type"         => "number",
                                "name"         => "tippy_color_img_fontsize",
                                "value"        => '',
                                'label_be_for' => __('Tooltip Font Size', 'BeRocket_AJAX_domain'),
                                'extra'        => 'placeholder="' . __('From Theme', 'BeRocket_AJAX_domain') . '"',
                            ),
                        ),
                    ),
                    'design_title_styles' => array(
                        'section' => 'design_title_styles',
                        "value"   => "",
                    ),
                ),
                'JavaScript/CSS' => array(
                    'global_font_awesome_disable' => array(
                        "label"     => __( 'Disable Font Awesome', "BeRocket_AJAX_domain" ),
                        "type"      => "checkbox",
                        "name"      => "fontawesome_frontend_disable",
                        "value"     => '1',
                        'label_for' => __('Don\'t loading css file for Font Awesome on site front end. Use it only if you doesn\'t uses Font Awesome icons in widgets or you have Font Awesome in your theme.', 'BeRocket_AJAX_domain'),
                    ),
                    'global_fontawesome_version' => array(
                        "label"    => __( 'Font Awesome Version', "BeRocket_AJAX_domain" ),
                        "name"     => "fontawesome_frontend_version",
                        "type"     => "selectbox",
                        "options"  => array(
                            array('value' => '', 'text' => __('Font Awesome 4', 'BeRocket_AJAX_domain')),
                            array('value' => 'fontawesome5', 'text' => __('Font Awesome 5', 'BeRocket_AJAX_domain')),
                        ),
                        "value"    => '',
                        "label_for" => __('Version of Font Awesome that will be used on front end. Please select version that you have in your theme', 'BeRocket_AJAX_domain'),
                    ),
                    'before_update' => array(
                        "label"     => __( 'Before Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("javascript", "berocket_ajax_filtering_start"),
                        "value"     => $this->defaults["javascript"]["berocket_ajax_filtering_start"],
                        "label_for" => __( "If you want to add own actions on filter activation, eg: alert('1');", "BeRocket_AJAX_domain" ),
                    ),
                    'on_update' => array(
                        "label"     => __( 'During Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("javascript", "berocket_ajax_filtering_on_update"),
                        "value"     => $this->defaults["javascript"]["berocket_ajax_filtering_on_update"],
                        "label_for" => __( "If you want to add own actions right on products update. You can manipulate data here, try: data.products = 'Ha!';", "BeRocket_AJAX_domain" ),
                    ),
                    'after_update' => array(
                        "label"     => __( 'After Update:', "BeRocket_AJAX_domain" ),
                        "type"      => "textarea",
                        "name"      => array("javascript", "berocket_ajax_products_loaded"),
                        "value"     => $this->defaults["javascript"]["berocket_ajax_products_loaded"],
                        "label_for" => __( "If you want to add own actions after products updated, eg: alert('1');", "BeRocket_AJAX_domain" ),
                    ),
                    'custom_css' => array(
                        'section' => 'custom_css',
                        'name'    => 'user_custom_css',
                        "value"   => "",
                    ),
                ),
                'Addons' => array(
                    'addons' => array(
                        'section' => 'addons',
                        "value"   => "",
                    ),
                ),
                'Tutorials' => array(
                    'tutorials_tab' => array(
                        "section"   => "tutorials",
                        "value"     => "",
                    ),
                ),
            )
        );
        $tooltip_text = '<strong>' . __('Variation must be added to product with stock status out of stock.', 'BeRocket_AJAX_domain') . '</strong>'
        . '<p>' . __('If product do not have variation, then it cannot be detected as out of stock/in stock and will be displayed as without this option', 'BeRocket_AJAX_domain') . '</p>'.
        '<p>'.__('Slow down filtering.', 'BeRocket_AJAX_domain').'</p>';
        self::add_tooltip('#braapf_out_of_stock_variable_info', $tooltip_text);
        
        $tooltip_text = '<strong>' . __('Will be displayed only on default WooCommerce page.', 'BeRocket_AJAX_domain') . '</strong>'
        . '<p>' . __('Default WooCommerce page are: shop page, category page, tag page, attribute page etc.', 'BeRocket_AJAX_domain') . '</p>'
        . '<p>' . __('Also it can does not work on WooCommerce pages edited with help of any page builders (Divi Builder, Elementor Builder etc.)', 'BeRocket_AJAX_domain') . '</p>';
        self::add_tooltip('#braapf_selected_area_show_info', $tooltip_text);
           
        $tooltip_text = '<strong>' . __('Please read this before asking support.', 'BeRocket_AJAX_domain') . '</strong>'
        . '<p>' . __('Any option except first will slow down page load, because required some additional queries to database', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">'.__('All non-empty values are shown and use basic counting', 'BeRocket_AJAX_domain').'</strong>' 
        . ' - ' . __('plugin do not recount anything. Only attribute values, that do not have products for full shop will be removed (fastest variant)', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">'.__('Remove empty values based on page(category/tag/etc)', 'BeRocket_AJAX_domain').'</strong>' 
        . ' - ' . __('plugin recount products for attribute values based on page where displayed. Attribute values, that do not have products for current page will be removed', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">'.__('All non-empty values are shown and filters are considered while counting attribute values', 'BeRocket_AJAX_domain').'</strong>'
        . ' - ' . __('plugin recount products only after filtering. Only attribute values, that do not have products for full shop will be removed. You can hide other empty values after filtering with help of option', 'BeRocket_AJAX_domain') . ' <strong>'.__('Hide values', 'BeRocket_AJAX_domain').'</strong></p>'
        . '<p><strong style="color:#0085ba;">'.__('Remove empty values based on page(category/tag/etc). Filters are considered while counting attribute values and empty values based on filters are hidden', 'BeRocket_AJAX_domain').'</strong>'
        . ' - ' . __('uses previous two option together. Works slower, because recounts twice for each attribute values (not recommended)', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">'.__('Filters are considered while counting attribute values. Empty values are removed server side', 'BeRocket_AJAX_domain').'</strong>' 
        . ' - ' . __('plugin recount products on page load and after filtering. All empty values will be removed based on page and selected filters', 'BeRocket_AJAX_domain') . '</p>';
        self::add_tooltip('#braapf_recount_hide_info', $tooltip_text);
        
        $tooltip_text = '<strong>' . __('On products archive page (attribute/category/tag pages) change how filters for the same taxonomy (attribute/category/tag) are displayed.', 'BeRocket_AJAX_domain') . '</strong>'
        . '<p><strong style="color:#0085ba;">' . __('Default', 'BeRocket_AJAX_domain') . '</strong> - '
        . __('Display filter same as it is displayed on any other page', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">' . __('Delete value', 'BeRocket_AJAX_domain') . '</strong> - '
        . __('filters for same taxonomy will be removed from page (Example: On page of Product category "Jeans" the filter for Product category will be removed)', 'BeRocket_AJAX_domain') . '</p>'
        . '<p><strong style="color:#0085ba;">' . __('Leave only one value', 'BeRocket_AJAX_domain') . '</strong> - '
        . __('filters for same taxonomy will be displayed with single value, that same as current page (Example: On page of Product category "Jeans" the filter for Product category will be displayed only with the value "Jeans")', 'BeRocket_AJAX_domain') . '</p>';
        self::add_tooltip('#braapf_page_same_as_filter_info', $tooltip_text);
    }
    public static function add_tooltip($selector, $text) {
        BeRocket_tooltip_display::add_tooltip(
            array(
                'appendTo'      => 'document.body',
                'arrow'         => true,
                'interactive'   => true,
                'maxWidth'      => '"none"'
            ),
            $text,
            $selector
        );
    }
    public function section_tutorials ( $item, $options ) {
        if( ! function_exists('berocket_tutorial_tab') ) {
            include_once('berocket/includes/tutorial.php');
        }
        ob_start();
        include AAPF_TEMPLATE_PATH.'settings/tutorial_tab.php';
        $html = '</table>'.ob_get_clean().'<table class="framework-form-table berocket_framework_menu_tutorial">';
        return $html;
    }
    public function section_setup_wizard ( $item, $options ) {
        $html = '';
        if( apply_filters('br_filters_options-setup_wizard-show', true) ) {
            $html .= '<tr>
                <th scope="row">' . __('SETUP WIZARD', 'BeRocket_AJAX_domain') . '</th>
                <td>
                    <a class="button" href="' . admin_url( 'admin.php?page=br-aapf-setup' ) . '">' . __('RUN SETUP WIZARD', 'BeRocket_AJAX_domain') . '</a>
                    <div>
                        ' . __('Run it to setup plugin options step by step', 'BeRocket_AJAX_domain') . '
                    </div>
                </td>
            </tr>';
        }
        return $html;
    }
    public function section_autoselector ( $item, $options ) {
        do_action('BeRocket_wizard_javascript', array(
            'creating_products' => __('Creating products', 'BeRocket_AJAX_domain'),
            'getting_selectors' => __('Gettings selectors', 'BeRocket_AJAX_domain'),
            'removing_products' => __('Removing products', 'BeRocket_AJAX_domain'),
            'error'             => __('Error:', 'BeRocket_AJAX_domain')
        ));
        $output_text = array(
            'important'             => __('IMPORTANT: It will generate some products on your site. Please disable all SEO plugins and plugins, that doing anything on product creating.', 'BeRocket_AJAX_domain'),
            'was_runned'            => __('Script was runned, but page closed until end. Please stop it to prevent any problems on your site', 'BeRocket_AJAX_domain'),
            'run_button'            => __('Auto-Selectors', 'BeRocket_AJAX_domain'),
            'was_runned_stop'       => __('Stop', 'BeRocket_AJAX_domain'),
            'steps'                 => __('Steps:', 'BeRocket_AJAX_domain'),
            'step_create_products'  => __('Creating products', 'BeRocket_AJAX_domain'),
            'step_get_selectors'    => __('Gettings selectors', 'BeRocket_AJAX_domain'),
            'step_remove_product'   => __('Removing products', 'BeRocket_AJAX_domain')
        );
        $html = '<tr>
            <th scope="row">' . __('Get selectors automatically', 'BeRocket_AJAX_domain') . '</th>
            <td>
                <h4>' . __('How it work:', 'BeRocket_AJAX_domain') . '</h4>
                <ol>
                    <li>' . __('Run Auto-selector', 'BeRocket_AJAX_domain') . '</li>
                    <li>' . __('Wait until end <strong style="color:red;">do not close this page</strong>', 'BeRocket_AJAX_domain') . '</li>
                    <li>' . __('Save settings with new selectors', 'BeRocket_AJAX_domain') . '</li>
                </ol>
                ' . BeRocket_wizard_generate_autoselectors(array('products' => '.berocket_aapf_products_selector', 'pagination' => '.berocket_aapf_pagination_selector', 'result_count' => '.berocket_aapf_product_count_selector'), array(), $output_text) . '
            </td>
        </tr>';
        return $html;
    }
    public function section_purge_cache ( $item, $options ) {
        $html = '<tr>
            <th scope="row">' . __('Purge Cache', 'BeRocket_AJAX_domain') . '</th>
            <td>';
        $old_filter_widgets = get_option('widget_berocket_aapf_widget');
        if( ! is_array($old_filter_widgets) ) {
            $old_filter_widgets = array();
        }
        foreach ($old_filter_widgets as $key => $value) {
            if (!is_numeric($key)) {
                unset($old_filter_widgets[$key]);
            }
        }
        $html .= '
                <span class="button berocket_purge_cache" data-time="'.time().'">
                    <input class="berocket_purge_cache_input" type="hidden" name="br_filters_options[purge_cache_time]" value="'.br_get_value_from_array($options, 'purge_cache_time').'">
                    ' . __('Purge Cache', 'BeRocket_AJAX_domain') . '
                </span>
                <p>' . __('Clears the attribute/custom taxonomy cache for plugin', 'BeRocket_AJAX_domain') . '</p>
                <script>
                    jQuery(".berocket_purge_cache").click(function() {
                        var $this = jQuery(this);
                        if( ! $this.is(".berocket_ajax_sending") ) {
                            $this.attr("disabled", "disabled");
                            var time = $this.data("time");
                            $this.parents(".br_framework_submit_form").addClass("br_reload_form");
                            $this.find(".berocket_purge_cache_input").val(time).submit();
                        }
                    });
                </script>
            </td>
        </tr>';
        return $html;
    }
    public function section_custom_css ( $item, $options ) {
        if( empty($item) ) return '';
        $html = '</table>
            <table class="form-table bapf_custom_css_admin">
                <tr>
                    <td>
                        <h3>'.__('Custom CSS Style:', 'BeRocket_AJAX_domain').'</h3>
                        <textarea style="width: 100%; min-height: 400px; height:820px" name="br_filters_options[user_custom_css]">' . htmlentities(br_get_value_from_array($options, 'user_custom_css')) . '</textarea>
                    </td>
                    <td style="width:350px;"><div class="berocket_css_examples"style="max-width:300px;">
                        <h4>Replacements</h4>
<div style="background-color:white;">
<p><strong>#widget#</strong> - block that contain all filter elements</p>
<p><strong>#widget-title#</strong> - filter title</p>
<p><strong>#widget-ckboxlabel#</strong> - Value text for checkbox filter</p>
<p><strong>#widget-ckboxlabel-checked#</strong> - Selected value text for checkbox filter</p>
<p><strong>#widget-button#</strong> - Update and reset buttons</p>
</div>
                        <h4>Add border to widget</h4>
<div style="background-color:white;"><pre>#widget#{
    border:2px solid #FF8800;
}</pre></div>
                        <h4>Set font size and font color for title</h4>
<div style="background-color:white;"><pre>#widget-title#{
    font-size:36px!important;
    color:orange!important;
}</pre></div>
                        <h4>Display all inline</h4>
<div style="background-color:white;"><pre>#widget# li{
    display: inline-block;
}</pre></div>
                        <h4>Use block for slider handler instead image</h4>
<div style="background-color:white;"><pre>#widget# .ui-slider-handle {
    background:none!important;
    border-radius:50px!important;
    background-color:white!important;
    border: 2px solid black!important;
    outline:none!important;
}
#widget# .ui-slider-handle.ui-state-active {
    border: 3px solid black!important;
}</pre></div>
<style>
.berocket_css_examples {
    width:300px;
    overflow:visible;
}
.berocket_css_examples div{
    background-color:white;
    width:100%;
    min-width:100%;
    overflow:hidden;
    float:right;
    border:1px solid white;
    padding: 2px;
}
.berocket_css_examples div:hover {
    position:relative;
    z-index: 9999;
    width: initial;
    border:1px solid #888;
}
</style>
                    </div></td>
                </tr>
            </table>
            <table>';
        $html .= "
<script>
function out_of_stock_variable_reload_hide() {
    if( (jQuery('.berocket_aapf_recount_hide').val() == 'recount' || jQuery('.berocket_aapf_recount_hide').val() == 'removeFirst_recount' || jQuery('.berocket_aapf_recount_hide').val() == 'removeRecount') && parseInt(jQuery('.out_of_stock_variable').val()) ) {
        jQuery('.out_of_stock_variable_reload').parent().show();
    } else {
        jQuery('.out_of_stock_variable_reload').parent().hide();
    }
}
out_of_stock_variable_reload_hide();
jQuery('.berocket_aapf_recount_hide, .out_of_stock_variable').on('change', out_of_stock_variable_reload_hide);
function load_fix_ajax_request_load() {
    if( jQuery('.load_fix_ajax_request_load').prop('checked') ) {
        jQuery('.load_fix_use_get_query').parent().show();
        jQuery('.ajax_request_load_style').parent().show();
    } else {
        jQuery('.load_fix_use_get_query').parent().hide();
        jQuery('.ajax_request_load_style').parent().hide();
    }
}
load_fix_ajax_request_load();
jQuery(document).on('change', '.load_fix_ajax_request_load', load_fix_ajax_request_load);
function br_scroll_shop_top() {
    if( parseInt(jQuery('.br_scroll_shop_top').val()) ) {
        jQuery('.br_scroll_shop_top_px').parent().show();
    } else {
        jQuery('.br_scroll_shop_top_px').parent().hide();
    }
}
br_scroll_shop_top();
jQuery(document).on('change', '.br_scroll_shop_top', br_scroll_shop_top);

function br_selected_area_show() {
    if( jQuery('.br_selected_area_show').prop('checked') ) {
        jQuery('.br_selected_area_hide_empty').parent().show();
    } else {
        jQuery('.br_selected_area_hide_empty').parent().hide();
    }
}
br_selected_area_show();
jQuery(document).on('change', '.br_selected_area_show', br_selected_area_show);

function berocket_disable_ajax_loading() {
    if( jQuery('.berocket_disable_ajax_loading').prop('checked') ) {
        jQuery('.berocket_disable_ajax_loading_hide').hide();
        jQuery('.berocket_wizard_autoselectors').closest('tr').hide();
    } else {
        jQuery('.berocket_disable_ajax_loading_hide').show();
        jQuery('.berocket_wizard_autoselectors').closest('tr').show();
    }
}
berocket_disable_ajax_loading();
jQuery(document).on('change', '.berocket_disable_ajax_loading', berocket_disable_ajax_loading);
</script>";
        return $html;
    }
    public function section_design_title_styles($item, $options) {
        $designables = br_aapf_get_styled();
        ob_start();
        include AAPF_TEMPLATE_PATH.'settings/design_title_styles.php';
        $html = '</table>'.ob_get_clean().'<table class="framework-form-table berocket_framework_menu_design">';
        $tooltip_text = '<strong>' . __('Those design settings change only the styles for filters inside a Group with enabled option', 'BeRocket_AJAX_domain') . ' <span style="color:#0085ba;">' . __('Show title only', 'BeRocket_AJAX_domain') . '</span></strong>';
        self::add_tooltip('#braapf_design_title_styles', $tooltip_text);
        return $html;
    }
    public function section_header_part($item, $options) {
        $class = $extra = '';

        if ( isset($item['class']) && trim( $item['class'] ) ) {
            $class = " class='" . trim( $item['class'] ) . "'";
        }

        if ( isset($item['extra']) && trim( $item['extra'] ) ) {
            $extra = " " . trim( $item['extra'] );
        }
        $html = '<th colspan="2"' . $class . $extra . '><h3 style="padding-top:50px;">'.$item['value'].'</h3></th>';
        return $html;
    }
    public function admin_init () {
        if(! empty($_GET['settings-updated']) && br_get_value_from_array($_GET,'page') == 'br-product-filters' ) {
            wp_cache_delete($this->values[ 'settings_name' ], 'berocket_framework_option');
            delete_option( 'rewrite_rules' );
            flush_rewrite_rules();
        }
        if( apply_filters('BeRocket_AAPF_widget_load_file', true) ) {
            $plugins = get_option('BeRocket_Framework_plugins_version_check');
            if( empty($plugins) || ! is_array($plugins) ) {
                $plugins = array();
            }
            if( ! isset($plugins[$this->info['plugin_name']]) ) {
                $plugins[$this->info['plugin_name']] = '0';
            }
            if( version_compare($plugins[$this->info['plugin_name']], '2.9', '>') || ( version_compare($plugins[$this->info['plugin_name']], '1.5', '>=') && version_compare($plugins[$this->info['plugin_name']], '2', '<')) ) {
                $filters_converted = get_option('braapf_new_filters_converted');
                if( empty($filters_converted) ) {
                    do_action('bapf_include_all_tempate_styles');
                    require_once dirname( __FILE__ ) . '/fixes/replace_filters.php';
                    update_option('braapf_new_filters_converted', true);
                }
            }
        }
        parent::admin_init();
        add_action('berocket_fix_WC_outofstock', array($this, 'fix_WC_outofstock'), 10, 1);
        $this->create_berocket_term_table();
        register_setting( 'br_filters_plugin_options', 'br_filters_options', array( $this, 'sanitize_aapf_option' ) );
    }
    public function is_active_sidebar($is_active_sidebar, $index) {
        if( $is_active_sidebar ) {
            $sidebars_widgets = wp_get_sidebars_widgets();
            $sidebars_widgets = $sidebars_widgets[$index];
            global $wp_registered_widgets;
            $test = $wp_registered_widgets;
            if( is_array($sidebars_widgets) && count($sidebars_widgets) ) {
                foreach($sidebars_widgets as $widgets) {
                    if( strpos($widgets, 'berocket_aapf_group') === false && strpos($widgets, 'berocket_aapf_single') === false ) {
                        return $is_active_sidebar;
                    }
                }
                foreach($sidebars_widgets as $widgets) {
                    $widget_id = br_get_value_from_array($wp_registered_widgets, array($widgets, 'params', 0));
                    if( empty($widget_id) ) continue;
                    if( strpos($widgets, 'berocket_aapf_group') === false ) {
                        $widget_instances = get_option('widget_berocket_aapf_single');
                        $filters = br_get_value_from_array($widget_instances, $widget_id);
                        if( BeRocket_new_AAPF_Widget_single::check_widget_by_instance($filters) ) {
                            return $is_active_sidebar;
                        }
                    } else {
                        $widget_instances = get_option('widget_berocket_aapf_group');
                        $filters = br_get_value_from_array($widget_instances, $widget_id);
                        if( BeRocket_new_AAPF_Widget::check_widget_by_instance($filters) ) {
                            return $is_active_sidebar;
                        }
                    }
                }
                $is_active_sidebar = false;
            }
        }
        return $is_active_sidebar;
    }
    public function wpml_attribute_slug_translate($terms) {
        if( ! empty($terms) && is_array($terms) ) {
            foreach($terms as &$term) {
                $taxonomy = berocket_isset($term, 'taxonomy');
                if( ! empty($taxonomy) ) {
                    $taxonomy = preg_replace( '#^pa_#', '', $taxonomy );
                    $wpml_taxonomy = berocket_wpml_attribute_translate($taxonomy);
                    if( $taxonomy != $wpml_taxonomy ) {
                        $term->wpml_taxonomy = 'pa_'.$wpml_taxonomy;
                    }
                }
            }
            if( isset($term) ) {
                unset($term);
            }
        }
        return $terms;
    }
    function ajax_functions() {
        add_action( 'setup_theme', array( $this, 'WPML_fix' ) );
        add_action( "wp_ajax_aapf_color_set", array ( 'BeRocket_AAPF_Widget_functions', 'color_listener' ) );
        BeRocket_AAPF_Widget_functions::br_widget_ajax_set();
    }
    function not_ajax_functions() {
        $shortcode_types = array(
            'products',
            'product',
            'sale_products',
            'recent_products',
            'best_selling_products',
            'top_rated_products',
            'featured_products',
            'product_attribute',
            'product_category',
        );
        foreach($shortcode_types as $shortcode_type) {
            add_action( "woocommerce_shortcode_{$shortcode_type}_loop_no_results", array( $this, 'woocommerce_shortcode_no_result' ), 10, 1 );
        }
        add_filter( 'shortcode_atts_sale_products', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_featured_products', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_best_selling_products', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_recent_products', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_product_attribute', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_top_rated_products', array($this, 'shortcode_atts_products'), 10, 3);
        add_filter( 'shortcode_atts_products', array($this, 'shortcode_atts_products'), 10, 3);
    }
    function shortcode_atts_products($out, $pairs, $atts) {
        if( ! empty($atts['berocket_aapf']) ) {
            if( $atts['berocket_aapf'] == 'false' || $atts['berocket_aapf'] == '0' ) {
                $out['berocket_aapf'] = false;
                $out['class'] = (empty($out['class']) ? '' : $out['class'] . ' ') . 'berocket_aapf_false';
            }
            if( $atts['berocket_aapf'] == 'true' || $atts['berocket_aapf'] == '1' ) {
                $out['cache'] = false;
                $out['berocket_aapf'] = true;
                $out['class'] = (empty($out['class']) ? '' : $out['class'] . ' ') . 'berocket_aapf_true';
            }
        }
        return $out;
    }
    public function widgets_init() {
        register_widget("BeRocket_new_AAPF_Widget");
        register_widget("BeRocket_new_AAPF_Widget_single");
    }
    public function woocommerce_is_filtered($filtered) {
        if ( apply_filters( 'berocket_aapf_is_filtered_page_check', ! empty($_GET['filters']), 'woocommerce_is_filtered' ) ) {
            $filtered = true;
        }
        return $filtered;
    }
    public function include_all_styles() {
        BeRocket_AAPF::wp_enqueue_style( 'berocket_aapf_widget-style' );
    }
    public function include_all_scripts() {
        /* theme scripts */
        if( ! self::$the_ajax_script_initialized ) {
            if( apply_filters('brapf_the7_compat', (defined('THE7_VERSION') && THE7_VERSION && version_compare(THE7_VERSION, '9.8', '<=') ) ) ) {
                add_filter('berocket_aapf_time_to_fix_products_style', '__return_false');
                BeRocket_AAPF::wp_enqueue_script( 'berocket_ajax_fix-the7', plugins_url( 'assets/themes/the7.js', __FILE__ ), array( 'jquery' ), BeRocket_AJAX_filters_version );
            }
            global $wp_query, $wp, $sitepress, $wp_rewrite;
            $br_options = apply_filters( 'berocket_aapf_listener_br_options', $this->get_option() );

            $wp_query_product_cat     = '-1';
            $wp_check_product_cat     = '1q1main_shop1q1';
            if ( ! empty($wp_query->query['product_cat']) ) {
                $wp_query_product_cat = explode( "/", $wp_query->query['product_cat'] );
                $wp_query_product_cat = $wp_query_product_cat[ count( $wp_query_product_cat ) - 1 ];
                $wp_check_product_cat = $wp_query_product_cat;
            }

            if ( ! empty($sitepress) && method_exists($sitepress, 'get_current_language') ) {
                $current_language = $sitepress->get_current_language();
            } else {
                $current_language = '';
            }

            $current_page_url = preg_replace( "~paged?/[0-9]+/?~", "", home_url( $wp->request ) );
            $current_page_url = apply_filters('berocket_aapf_current_page_url', $current_page_url, $br_options);
            if( strpos($current_page_url, '?') !== FALSE ) {
                $current_page_url = explode('?', $current_page_url);
                $current_page_url = $current_page_url[0];
            }

            $permalink_structure = get_option('permalink_structure');
            if ( $permalink_structure ) {
                $permalink_structure = substr($permalink_structure, -1);
                if ( $permalink_structure == '/' ) {
                    $permalink_structure = true;
                } else {
                    $permalink_structure = false;
                }
            } else {
                $permalink_structure = false;
            }

            $product_taxonomy = '-1';
            if ( is_product_taxonomy() ) {
                $product_taxonomy = (empty($wp_query->query_vars['taxonomy']) ? '' : $wp_query->query_vars['taxonomy']).'|'.(empty($wp_query->query_vars['term']) ? '' : $wp_query->query_vars['term']);
            }
            $default_sorting = get_option('woocommerce_default_catalog_orderby');
            $default_sorting = (empty($default_sorting) ? "menu_order" : $default_sorting);

            ob_start();
            wc_no_products_found();
            $no_products = ob_get_clean();

            self::$the_ajax_script = apply_filters('aapf_localize_widget_script', array(
                'disable_ajax_loading'                 => ! empty($br_options['disable_ajax_loading']),
                'url_variable'                         => 'filters',
                'url_mask'                             => '%t%[%v%]',
                'url_split'                            => '|',
                'nice_url_variable'                    => '',
                'nice_url_value_1'                     => '',
                'nice_url_value_2'                     => '',
                'nice_url_split'                       => '',
                'version'                              => BeRocket_AJAX_filters_version,
                'number_style'                         => array('', '.', '2'),
                'current_language'                     => $current_language,
                'current_page_url'                     => $current_page_url,
                'ajaxurl'                              => admin_url( 'admin-ajax.php' ),
                'product_cat'                          => $wp_query_product_cat,
                'product_taxonomy'                     => $product_taxonomy,
                's'                                    => ( ! empty( $_GET['s'] ) ? $_GET['s'] : '' ),
                'products_holder_id'                   => ( empty($br_options['products_holder_id']) ? 'ul.products' : $br_options['products_holder_id'] ),
                'result_count_class'                   => ( ! empty($br_options['woocommerce_result_count_class']) ? $br_options['woocommerce_result_count_class'] : $this->defaults['woocommerce_result_count_class'] ),
                'ordering_class'                       => ( ! empty($br_options['woocommerce_ordering_class']) ? $br_options['woocommerce_ordering_class'] : $this->defaults['woocommerce_ordering_class'] ),
                'pagination_class'                     => ( ! empty($br_options['woocommerce_pagination_class']) ? $br_options['woocommerce_pagination_class'] : $this->defaults['woocommerce_pagination_class'] ),
                'control_sorting'                      => ( empty($br_options['control_sorting']) ? '' : $br_options['control_sorting'] ),
                'seo_friendly_urls'                    => ( empty($br_options['seo_friendly_urls']) ? '' : $br_options['seo_friendly_urls'] ),
                'seo_uri_decode'                       => ( empty($br_options['seo_uri_decode']) ? '' : $br_options['seo_uri_decode'] ),
                'slug_urls'                            => ( empty($br_options['slug_urls']) ? '' : $br_options['slug_urls'] ),
                'nice_urls'                            => '',
                'ub_product_count'                     => '',
                'ub_product_text'                      => '',
                'ub_product_button_text'               => '',
                'default_sorting'                      => $default_sorting,
                'first_page'                           => '1',
                'scroll_shop_top'                      => ( empty($br_options['scroll_shop_top']) ? '' : $br_options['scroll_shop_top'] ),
                'ajax_request_load'                    => '1',
                'ajax_request_load_style'              => 'jquery',
                'use_request_method'                   => 'get',
                'no_products'                          => $no_products,
                'recount_products'                     => braapf_filters_must_be_recounted(),
                'pos_relative'                         => ( empty($br_options['pos_relative']) ? '' : $br_options['pos_relative'] ),
                'woocommerce_removes'                  => array(
                    'result_count' => ( empty($br_options['woocommerce_removes']['result_count']) ? '' : $br_options['woocommerce_removes']['result_count'] ),
                    'ordering'     => ( empty($br_options['woocommerce_removes']['ordering']) ? '' : $br_options['woocommerce_removes']['ordering'] ),
                    'pagination'   => ( empty($br_options['woocommerce_removes']['pagination']) ? '' : $br_options['woocommerce_removes']['pagination'] ),
                    'pagination_ajax'   => empty($br_options['woocommerce_removes']['pagination_ajax']),
                ),
                'pagination_ajax'                      => empty($br_options['woocommerce_removes']['pagination_ajax']),
                'description_show'                     => ( ! empty($br_options['description']['show']) ? $br_options['description']['show'] : 'click' ),
                'description_hide'                     => ( ! empty($br_options['description']['hide']) ? $br_options['description']['hide'] : 'click' ),
                'hide_sel_value'                       => ( empty($br_options['hide_value']['sel']) ? '' : $br_options['hide_value']['sel'] ),
                'hide_o_value'                         => ( empty($br_options['hide_value']['o']) ? '' : $br_options['hide_value']['o'] ),
                'use_select2'                          => ! empty($br_options['use_select2']),
                'hide_empty_value'                     => ( empty($br_options['hide_value']['empty']) ? '' : $br_options['hide_value']['empty'] ),
                'hide_button_value'                    => '',
                'scroll_shop_top_px'                   => ( ! empty( $br_options['scroll_shop_top_px'] ) ? $br_options['scroll_shop_top_px'] : $this->defaults['scroll_shop_top_px'] ),
                'load_image'                           => braapf_get_loader_element(),
                'translate'                            => array(
                    'show_value'        => __('Show value(s)', 'BeRocket_AJAX_domain'),
                    'hide_value'        => __('Hide value(s)', 'BeRocket_AJAX_domain'),
                    'unselect_all'      => __('Unselect all', 'BeRocket_AJAX_domain'),
                    'nothing_selected'  => __('Nothing is selected', 'BeRocket_AJAX_domain'),
                    'products'          => __('products', 'BeRocket_AJAX_domain'),
                ),
                'trailing_slash'                       => $permalink_structure,
                'pagination_base'                      => $wp_rewrite->pagination_base,
                'reload_changed_filters'               => ( empty($br_options['reload_changed_filters']) ? false : true),
            ) );
            self::$the_ajax_script_initialized = TRUE;
        }
        $localized = wp_localize_script(
            'berocket_aapf_widget-script',
            'the_ajax_script',
            self::$the_ajax_script
        );
    }
    public function select2_load() {
        if( ! empty($br_options['fixed_select2']) ) {
            BeRocket_AAPF::wp_enqueue_style( 'br_select2' );
        } else {
            BeRocket_AAPF::wp_enqueue_style( 'select2' );
        }
        BeRocket_AAPF::wp_enqueue_script( 'select2' );
    }
    public function add_error_log( $error_log ) {
        $error_log[plugin_basename( __FILE__ )] =  self::$error_log;
        return $error_log;
    }
    public function update_from_older( $version ) {
        $option = $this->get_option();
        $version_index = 8;
        if( version_compare($version, '2.0', '>') ) {
            if ( version_compare($version, '2.0.4', '<') ) {
                $version_index = 1;
            } elseif ( version_compare($version, '2.0.5', '<') ) {
                $version_index = 2;
            } elseif ( version_compare($version, '2.0.9.7', '<') ) {
                $version_index = 3;
            } elseif ( ! empty($version) && version_compare($version, '2.1', '<') ) {
                $version_index = 4;
            } elseif ( ! empty($version) && version_compare($version, '2.2', '<') ) {
                $version_index = 5;
            } elseif ( ! empty($version) && version_compare($version, '2.2.2.5', '<') ) {
                $version_index = 6;
            }
        }
        if( version_compare($version, '1.3.4.2', '<') || (version_compare($version, '2.0', '>') && version_compare($version, '2.3.0.2', '<') ) ) {
            $version_index = 7;
        }

        if( $version_index <= 1 ) {
            update_option('berocket_filter_open_wizard_on_settings', true);
        }
        if( $version_index <= 2 ) {
            update_option( 'berocket_permalink_option', $this->default_permalink );
        }
        if( $version_index <= 3 ) {
            $new_filter_widgets = get_option('widget_berocket_aapf_group');
            if( is_array($new_filter_widgets) ) {
                foreach($new_filter_widgets as &$new_filter_widget) {
                    if( is_array($new_filter_widget) && isset($new_filter_widget['title']) ) {
                        $new_filter_widget['title'] = '';
                    }
                }
                if( isset($new_filter_widget) ) {
                    unset($new_filter_widget);
                }
                update_option('widget_berocket_aapf_group', $new_filter_widgets);
            }
        }
        if( $version_index <= 5 ) {
            if( ! empty($version) ) {
                $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
                $filters = $BeRocket_AAPF_single_filter->get_custom_posts();
                foreach($filters as $filter) {
                    $filter_option = $BeRocket_AAPF_single_filter->get_option($filter);
                    if( empty($filter_option['widget_collapse_disable']) ) {
                        $filter_option['widget_collapse_enable'] = '1';
                    } else {
                        $filter_option['widget_collapse_enable'] = '';
                    }
                    $filter_post = get_post($filter);
                    $_POST[$BeRocket_AAPF_single_filter->post_name] = $filter_option;
                    $BeRocket_AAPF_single_filter->wc_save_product_without_check($filter, $filter_post);
                }
            }
        }
        if( $version_index <= 6 ) {
            update_option( 'berocket_nn_permalink_option', $this->default_nn_permalink );
        }
        if( $version_index <= 7 ) {
            $option['purge_cache_time'] = time();
        }

        update_option( 'br_filters_options', $option );
        update_option( 'br_filters_version', BeRocket_AJAX_filters_version );
    }
    public function no_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Activate WooCommerce plugin before', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }
    public function update_woocommerce() {
        echo '
        <div class="error">
            <p>' . __( 'Update WooCommerce plugin', 'BeRocket_AJAX_domain' ) . '</p>
        </div>';
    }
    public function apply_filter_to_shortcode($enable) {
        remove_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'apply_filter_to_shortcode'));
        return true;
    }
    public function not_apply_filter_to_shortcode($enable) {
        remove_filter('berocket_aapf_wcshortcode_is_filtering', array($this, 'not_apply_filter_to_shortcode'));
        return false;
    }
    public function woocommerce_shortcode_no_result($atts) {
        if( ! empty($atts['berocket_aapf']) ) {
            wc_no_products_found();
        }
    }
    public function display_products() {
        return '';
    }
    public function delete_products_not_on_sale($transient) {
        delete_transient( 'wc_products_notonsale' );
    }
    public function convert_styles_to_string(&$style) {
        if( empty($style) || ! is_array($style) ) {
            return '';
        }
        $style_line = '';
        if ( ! empty($style['bcolor']) ) {
            $style_line .= 'border-color: ';
            if ( $style['bcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['bcolor'].'!important;';
        }
        if ( isset($style['bwidth']) && strlen($style['bwidth']) )
            $style_line .= 'border-width: '.$style['bwidth'].'px!important;';
        if ( isset($style['bradius']) && strlen($style['bradius']) )
            $style_line .= 'border-radius: '.$style['bradius'].'px!important;';
        if ( isset($style['fontsize']) && strlen($style['fontsize']) )
            $style_line .= 'font-size: '.$style['fontsize'].'px!important;';
        if ( ! empty($style['fcolor']) ) {
            $style_line .= 'color: ';
            if ( $style['fcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['fcolor'].'!important;';
        }
        if ( ! empty($style['backcolor']) ) {
            $style_line .= 'background-color: ';
            if ( $style['backcolor'][0] != '#' ) {
                $style_line .= '#';
            }
            $style_line .= $style['backcolor'].'!important;';
        }
        return $style_line;
    }
    function custom_user_css($handle) {
        if($handle == 'berocket_aapf_widget-style') {
            add_action('wp_footer', array($this, 'footer_css'));
        }
    }
    public function footer_css() {
        if ( $user_css = $this->br_custom_user_css() ) {
            echo '<style>', $user_css, '</style>';
        }
    }
    public function br_custom_user_css() {
        $options     = $this->get_option();
        $replace_css = apply_filters('braapf_custom_user_css_replacement', array(
            '#widget#'       => 'div.bapf_sfilter',
            '#widget-title#' => 'div.bapf_sfilter .bapf_head h3',
            '#widget-ckboxlabel#' => 'div.bapf_sfilter.bapf_ckbox .bapf_body label',
            '#widget-ckboxlabel-checked#' => 'div.bapf_sfilter.bapf_ckbox .bapf_body input:checked + label',
            '#widget-button#' => 'div.bapf_sfilter .bapf_button',
        ));
        $result_css = str_replace(array('<style>', '</style>', '<'), '', $options[ 'user_custom_css' ]);
        foreach ( $replace_css as $key => $value ) {
            $result_css = str_replace( $key, $value, $result_css );
        }
        $result_css = trim($result_css);
        $uo = br_aapf_converter_styles( (isset($options['styles']) ? $options['styles'] : array()) );
        if( ! empty($uo['style']['selected_area']) ) {
            $result_css .= 'div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a, div.berocket_aapf_selected_area_block a{'.$uo['style']['selected_area'].'}';
        }
        if( ! empty($uo['style']['selected_area_hover']) ) {
            $result_css .= 'div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a.br_hover *, div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a.br_hover, div.berocket_aapf_selected_area_block a.br_hover{'.$uo['style']['selected_area_hover'].'}';
        }
        if ( ! empty($options['styles_input']['checkbox']['icon']) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="checkbox"] + .berocket_label_widgets:before {display:inline-block;}';
            $result_css .= '.berocket_aapf_widget input[type="checkbox"] {display: none;}';
        }
        $add_css = $this->convert_styles_to_string($options['styles_input']['checkbox']);
        if( ! empty($add_css) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="checkbox"] + .berocket_label_widgets:before {'.$add_css.'}';
        }
        if ( ! empty($options['styles_input']['checkbox']['icon']) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="checkbox"]:checked + .berocket_label_widgets:before {';
            $result_css .= 'content: "\\'.$options['styles_input']['checkbox']['icon'].'";';
            $result_css .= '}';
        }
        if ( ! empty($options['styles_input']['radio']['icon']) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="radio"] + .berocket_label_widgets:before {display:inline-block;}';
            $result_css .= '.berocket_aapf_widget input[type="radio"] {display: none;}';
        }
        $add_css = $this->convert_styles_to_string($options['styles_input']['radio']);
        if( ! empty($add_css) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="radio"] + .berocket_label_widgets:before {' . $add_css . '}';
        }
        if ( ! empty($options['styles_input']['radio']['icon']) ) {
            $result_css .= 'ul.berocket_aapf_widget li > span > input[type="radio"]:checked + .berocket_label_widgets:before {';
            $result_css .= 'content: "\\'.$options['styles_input']['radio']['icon'].'";';
            $result_css .= '}';
        }
        if ( ! empty($options['styles_input']['slider']['line_color']) ) {
            $result_css .= '.berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content .ui-slider-range, .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content .ui-slider-range{';
            $result_css .= 'background-color: ';
            if ( $options['styles_input']['slider']['line_color'][0] != '#' ) {
                $result_css .= '#';
            }
            $result_css .= $options['styles_input']['slider']['line_color'].';';
            $result_css .= '}';
        }
        $add_css = '';
        if ( isset($options['styles_input']['slider']['line_height']) && strlen($options['styles_input']['slider']['line_height']) ) {
            $add_css .= 'height: '.$options['styles_input']['slider']['line_height'].'px;';
        }
        if ( ! empty($options['styles_input']['slider']['line_border_color']) ) {
            $add_css .= 'border-color: ';
            if ( $options['styles_input']['slider']['line_border_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['slider']['line_border_color'].';';
        }
        if ( ! empty($options['styles_input']['slider']['back_line_color']) ) {
            $add_css .= 'background-color: ';
            if ( $options['styles_input']['slider']['back_line_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['slider']['back_line_color'].';';
        }
        if ( isset($options['styles_input']['slider']['line_border_width']) && strlen($options['styles_input']['slider']['line_border_width']) ) {
            $add_css .= 'border-width: '.$options['styles_input']['slider']['line_border_width'].'px;';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content, .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content{'.$add_css.'}';
        }
        $add_css = '';
        if ( isset($options['styles_input']['slider']['button_size']) && strlen($options['styles_input']['slider']['button_size']) ) {
            $add_css .= 'font-size: '.$options['styles_input']['slider']['button_size'].'px;';
        }
        if ( ! empty($options['styles_input']['slider']['button_color']) ) {
            $add_css .= 'background-color: ';
            if ( $options['styles_input']['slider']['button_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['slider']['button_color'].';';
        }
        if ( ! empty($options['styles_input']['slider']['button_border_color']) ) {
            $add_css .= 'border-color: ';
            if ( $options['styles_input']['slider']['button_border_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['slider']['button_border_color'].';';
        }
        if ( isset($options['styles_input']['slider']['button_border_width']) && strlen($options['styles_input']['slider']['button_border_width']) ) {
            $add_css .= 'border-width: '.$options['styles_input']['slider']['button_border_width'].'px;';
        }
        if ( isset($options['styles_input']['slider']['button_border_radius']) && strlen($options['styles_input']['slider']['button_border_radius']) ) {
            $add_css .= 'border-radius: '.$options['styles_input']['slider']['button_border_radius'].'px;';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget .slide .berocket_filter_slider .ui-state-default, 
            .berocket_aapf_widget .slide .berocket_filter_price_slider .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_slider.ui-widget-content .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_price_slider.ui-widget-content .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_slider .ui-widget-header .ui-state-default,
            .berocket_aapf_widget .slide .berocket_filter_price_slider .ui-widget-header .ui-state-default
            .berocket_aapf_widget .berocket_filter_slider.ui-widget-content .ui-slider-handle,
            .berocket_aapf_widget .berocket_filter_price_slider.ui-widget-content .ui-slider-handle{'.$add_css.'}';
        }
        if( ! empty( $uo['style']['selected_area_block'] ) || ! empty( $uo['style']['selected_area_border'] ) ) {
            $result_css .= ' .berocket_aapf_selected_area_hook div.berocket_aapf_widget_selected_area .berocket_aapf_widget_selected_filter a{'
            .( ! empty( $uo['style']['selected_area_block'] ) ? 'background-'.$uo['style']['selected_area_block'] : '' )
            .( ! empty( $uo['style']['selected_area_border'] ) ? ' border-'.$uo['style']['selected_area_border'] : '' ).'}';
        }
        $add_css = '';
        if ( ! empty($options['styles_input']['pc_ub']['back_color']) ) {
            $add_css .= 'background-color: ';
            if ( $options['styles_input']['pc_ub']['back_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['back_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['border_color']) ) {
            $add_css .= 'border-color: ';
            if ( $options['styles_input']['pc_ub']['border_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['border_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['font_color']) ) {
            $add_css .= 'color: ';
            if ( $options['styles_input']['pc_ub']['font_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['font_color'].';';
        }
        if ( isset($options['styles_input']['pc_ub']['font_size']) && strlen($options['styles_input']['pc_ub']['font_size']) ) {
            $add_css .= 'font-size: '.$options['styles_input']['pc_ub']['font_size'].'px;';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc {'.$add_css.'}';
        }
        $add_css = '';
        if ( ! empty($options['styles_input']['pc_ub']['back_color']) ) {
            $add_css .= 'background-color: ';
            if ( $options['styles_input']['pc_ub']['back_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['back_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['border_color']) ) {
            $add_css .= 'border-color: ';
            if ( $options['styles_input']['pc_ub']['border_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['border_color'].';';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc > span {'.$add_css.'}';
        }
        $add_css = '';
        if ( ! empty($options['styles_input']['pc_ub']['show_font_color']) ) {
            $add_css .= 'color: ';
            if ( $options['styles_input']['pc_ub']['show_font_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['show_font_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['show_font_size']) ) {
            $add_css .= 'font-size: '.$options['styles_input']['pc_ub']['show_font_size'].'px;';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_widget_update_button {'.$add_css.'}';
        }
        if ( ! empty($options['styles_input']['pc_ub']['show_font_color_hover']) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_widget_update_button:hover {';
            $result_css .= 'color: ';
            if ( $options['styles_input']['pc_ub']['show_font_color_hover'][0] != '#' ) {
                $result_css .= '#';
            }
            $result_css .= $options['styles_input']['pc_ub']['show_font_color_hover'].';';
            $result_css .= '}';
        }
        $add_css = '';
        if ( ! empty($options['styles_input']['pc_ub']['close_font_color']) ) {
            $add_css .= 'color: ';
            if ( $options['styles_input']['pc_ub']['close_font_color'][0] != '#' ) {
                $add_css .= '#';
            }
            $add_css .= $options['styles_input']['pc_ub']['close_font_color'].';';
        }
        if ( ! empty($options['styles_input']['pc_ub']['close_size']) ) {
            $add_css .= 'font-size: '.$options['styles_input']['pc_ub']['close_size'].'px;';
        }
        if( ! empty($add_css) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_close_pc {'.$add_css.'}';
        }
        if ( ! empty($options['styles_input']['pc_ub']['close_font_color_hover']) ) {
            $result_css .= '.berocket_aapf_widget div.berocket_aapf_product_count_desc .berocket_aapf_close_pc:hover {';
            $result_css .= 'color: ';
            if ( $options['styles_input']['pc_ub']['close_font_color_hover'][0] != '#' ) {
                $result_css .= '#';
            }
            $result_css .= $options['styles_input']['pc_ub']['close_font_color_hover'].';';
            $result_css .= '}';
        }
        //ONLY TITLE STYLES
        $add_css = $this->convert_styles_to_string($options['styles_input']['onlyTitle_title']);
        if( ! empty($add_css) ) {
            $result_css .= 'div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_head,';
            $result_css .= '#berocket-ajax-filters-sidebar div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_head {'.$add_css.'}';
        }
        $add_css = $this->convert_styles_to_string($options['styles_input']['onlyTitle_titleopened']);
        if( ! empty($add_css) ) {
            $result_css .= 'div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter.bapf_ccolaps .bapf_head,';
            $result_css .= '#berocket-ajax-filters-sidebar div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter.bapf_ccolaps .bapf_head {'.$add_css.'}';
        }
        $add_css = $this->convert_styles_to_string($options['styles_input']['onlyTitle_filter']);
        if( ! empty($add_css) ) {
            $result_css .= 'div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body,';
            $result_css .= '#berocket-ajax-filters-sidebar div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body {'.$add_css.'}';
        }
        if ( ! empty($options['styles_input']['onlyTitle_filter']['fcolor']) ) {
            $result_css .= 'div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body *,';
            $result_css .= '#berocket-ajax-filters-sidebar div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body * {';
            $result_css .= 'color: ';
            if ( $options['styles_input']['onlyTitle_filter']['fcolor'][0] != '#' ) {
                $result_css .= '#';
            }
            $result_css .= $options['styles_input']['onlyTitle_filter']['fcolor'].';';
            $result_css .= '}';
            $result_css .= 'div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body input,';
            $result_css .= '#berocket-ajax-filters-sidebar div.berocket_single_filter_widget.berocket_hidden_clickable .bapf_sfilter .bapf_body input {';
            $result_css .= 'color: black;';
            $result_css .= '}';
        }
        return trim($result_css);
    }
    public function create_metadata_table() {
        $options     = $this->get_option();
        global $wpdb;
        $type        = 'berocket_term';
        $table_name  = $wpdb->prefix . $type . 'meta';
        $variable_name        = $type . 'meta';
        $wpdb->$variable_name = $table_name;
    }
    public function create_berocket_term_table() {
        global $wpdb;
        $type        = 'berocket_term';
        $table_name  = $wpdb->prefix . $type . 'meta';
        $charset_collate = '';
        if ( ! empty ( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if ( ! empty ( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE {$table_name} (
            meta_id bigint(20) NOT NULL AUTO_INCREMENT,
            {$type}_id bigint(20) NOT NULL default 0,
            meta_key varchar(255) DEFAULT NULL,
            meta_value longtext DEFAULT NULL,
            UNIQUE KEY meta_id (meta_id)
        ) {$charset_collate};";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    public function selected_area() {
        $set_query_var_title = array();
        $br_options = apply_filters( 'berocket_aapf_listener_br_options', $this->get_option() );
        $set_query_var_title['title'] = '';
        $set_query_var_title['widget_type'] = 'selected_area';
        $set_query_var_title['style'] = 'sfa_default';
        $set_query_var_title['selected_area_show'] = empty($br_options['selected_area_hide_empty']);
        $set_query_var_title = array_merge(BeRocket_AAPF_Widget::$defaults, $set_query_var_title);
        new BeRocket_AAPF_Widget($set_query_var_title);
    }
    public function WPML_fix() {
        global $sitepress;
        if ( ! empty($sitepress) && method_exists( $sitepress, 'switch_lang' )
             && isset( $_POST['current_language'] )
             && $_POST['current_language'] !== $sitepress->get_default_language()
        ) {
            $sitepress->switch_lang( $_POST['current_language'], true );
        }
    }
    public function order_by_popularity_post_clauses( $args ) {
        global $wpdb;
        $args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
        return $args;
    }
    public function order_by_rating_post_clauses( $args ) {
        global $wpdb;
        $args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";
        $args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";
        $args['join'] .= "
            LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
            LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
            ";
        $args['orderby'] = "average_rating DESC, $wpdb->posts.post_date DESC";
        $args['groupby'] = "$wpdb->posts.ID";
        return $args;
    }
    public function wcml_currency_price_fix() {
        if ( ! empty($_POST['price']) ) {
            global $woocommerce_wpml;
            $min = isset( $_POST['price'][0] ) ? floatval( $_POST['price'][0] ) : 0;
            $max = isset( $_POST['price'][1] ) ? floatval( $_POST['price'][1] ) : 9999999999;
            if( ! empty($woocommerce_wpml) && is_object($woocommerce_wpml)
                && property_exists($woocommerce_wpml, 'multi_currency') && is_object($woocommerce_wpml->multi_currency)
                && property_exists($woocommerce_wpml->multi_currency, 'prices') && is_object($woocommerce_wpml->multi_currency->prices)
                && method_exists($woocommerce_wpml->multi_currency->prices, 'unconvert_price_amount') ) {
                $min = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($min);
                $max = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($max);
            }
            if( function_exists('wmc_get_default_price') ) {
                $min = wmc_get_default_price($min);
                $max = wmc_get_default_price($max);
            }
            if( class_exists('BeRocket_AAPF_compat_WCPBC') ) {
                $min = BeRocket_AAPF_compat_WCPBC::to_base_rate($min);
                $max = BeRocket_AAPF_compat_WCPBC::to_base_rate($max);
            }
            $_POST['price'][0] = $min;
            $_POST['price'][1] = $max;
        }
    }
    public static function get_aapf_option() {
        $BeRocket_AAPF = self::getInstance();
        return $BeRocket_AAPF->get_option();
    }
    public function menu_order_custom_post($compatibility) {
        $compatibility['br_product_filter'] = 'br-product-filters';
        $compatibility['br_filters_group'] = 'br-product-filters';
        return $compatibility;
    }
    public function BRaapf_cache_check_md5($md5) {
        $options = $this->get_option();
        $md5 = $md5 . br_get_value_from_array($options, 'purge_cache_time');
        return $md5;
    }
    public function option_page_capability($capability = '') {
        return 'manage_berocket_aapf';
    }
    public function update_version($previous, $current) {
        $options = $this->get_option();
        if( $previous === '0' ) {
            update_option('berocket_filter_open_wizard_on_settings', true);
        }
        if( $previous !== '0' && ( version_compare($previous, '1.3.7', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '2.3.1', '<') ) ) ) {
            $options = berocket_sanitize_array($options);
            new berocket_admin_notices(array(
                'start' => 0,
                'end'   => 0,
                'name'  => 'aapf_security_risk',
                'html'  => 'Older versions of the AJAX Product Filters by BeRocket have critical issue that require your attention. Please read this article fully - <a href="https://docs.berocket.com/docs_section/error-on-front-end" target="_blank">Error on front-end</a>',
                'righthtml'  => '<a class="berocket_no_thanks">I read and understand the problem. Close it</a>',
                'rightwidth'  => 200,
                'nothankswidth'  => 200,
                'contentwidth'  => 400,
                'subscribe'  => false,
                'priority'  => 20,
                'height'  => 50,
                'repeat'  => false,
                'repeatcount'  => 1,
                'image'  => array(
                    'local' => plugin_dir_url( __FILE__ ) . 'images/attention.png',
                ),
            ));
        }
        if( $previous !== '0' && ( version_compare($previous, '1.3.7.1', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '2.3.1.1', '<') ) ) ) {
            if( ! is_array($options['addons']) ) {
                $options['addons'] = array();
            }
            if( ! in_array('/additional_tables/additional_tables.php', $options['addons']) ) {
                $options['addons'][] = '/additional_tables/additional_tables.php';
            }
        }
        if( $previous !== '0' && ( version_compare($previous, '1.4.1', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '2.4.1', '<') ) ) ) {
            if( ! empty($options['show_all_values']) && empty($options['recount_products']) ) {
                $options['recount_hide'] = 'disable';
            } elseif( empty($options['show_all_values']) && empty($options['recount_products']) ) {
                $options['recount_hide'] = 'removeFirst';
            } elseif( ! empty($options['show_all_values']) && ! empty($options['recount_products']) ) {
                $options['recount_hide'] = 'recount';
            } elseif( empty($options['show_all_values']) && ! empty($options['recount_products']) ) {
                $options['recount_hide'] = 'removeFirst_recount';
            }
            $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
            $filters = $BeRocket_AAPF_single_filter->get_custom_posts();
            foreach($filters as $filter) {
                $filter_option = $BeRocket_AAPF_single_filter->get_option($filter);
                if( ($filter_option['filter_type'] == 'custom_taxonomy' || ($filter_option['filter_type'] == 'attribute' && $filter_option['attribute'] != 'price')) && $filter_option['type'] == 'slider' ) {
                    $filter_option['order_values_by'] = '';
                    $filter_option['order_values_type'] = 'asc';
                    if( empty($filter_option['slider_default']) ) {
                        if( $filter_option['filter_type'] == 'custom_taxonomy' ) {
                            $terms = get_terms(array('taxonomy' => $filter_option['custom_taxonomy']));
                        } else {
                            $terms = get_terms(array('taxonomy' => $filter_option['attribute']));
                        }
                        if( ! empty($terms) && ! is_wp_error($terms) && is_array($terms) ) {
                            $slider_with_string = false;
                            $stringed_is_numeric = true;
                            foreach ( $terms as $term ) {
                                if ( ! is_numeric( $term->name ) ) {
                                    $slider_with_string = true;
                                    if ( ! is_numeric( substr( $term->name, 0, 1 ) ) ) {
                                        $stringed_is_numeric = false;
                                    }
                                }
                            }
                            if( ! $slider_with_string ) {
                                $filter_option['slider_numeric'] = '1';
                            } elseif($slider_with_string && $stringed_is_numeric) {
                                $filter_option['slider_numeric'] = '';
                                $filter_option['order_values_by'] = 'Numeric';
                            }
                        }
                    }
                    update_post_meta( $filter, $BeRocket_AAPF_single_filter->post_name, $filter_option );
                }
            }
        }
        if( $previous !== '0' && ( version_compare($previous, '1.4.9.9', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '2.9', '<') ) ) ) {
            if( ! empty($options['user_func']['before_update']) ) {
                $options['javascript']['berocket_ajax_filtering_start'] = $options['user_func']['before_update'];
            }
            if( ! empty($options['user_func']['on_update']) ) {
                $options['javascript']['berocket_ajax_filtering_on_update'] = $options['user_func']['on_update'];
            }
            if( ! empty($options['user_func']['after_update']) ) {
                $options['javascript']['berocket_ajax_products_loaded'] = $options['user_func']['after_update'];
            }
            if( ! is_array($options['addons']) ) {
                $options['addons'] = array();
            }
            $options['addons'][] = DIRECTORY_SEPARATOR . 'deprecated_filters'. DIRECTORY_SEPARATOR . 'deprecated_filters.php';
            new berocket_admin_notices(array(
                'start' => 0,
                'end'   => 0,
                'name'  => 'aapf_security_risk',
                'html'  => 'AJAX Product Filters was updated and has a lot of new features, but disable Deprecated Filters in <a href="'.admin_url('admin.php?page=br-product-filters&tab=addons').'">Plugin settings -> Add-ons tab</a> to get access for all new features',
                'righthtml'  => '<a class="berocket_no_thanks">Close notice</a>',
                'rightwidth'  => 200,
                'nothankswidth'  => 200,
                'contentwidth'  => 400,
                'subscribe'  => false,
                'priority'  => 20,
                'height'  => 50,
                'repeat'  => false,
                'repeatcount'  => 1,
                'image'  => array(
                    'local' => plugin_dir_url( __FILE__ ) . 'images/attention.png',
                ),
            ));
        }
        update_option( 'br_filters_options', $options );
        if( $previous !== '0' && ( version_compare($previous, '1.4.9.9', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '2.9', '<') ) ) ) {
            $this->replace_deprecated_with_new();
        }
        if( $previous !== '0' && ( version_compare($previous, '1.5.2.4', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '3.0.2.4', '<') ) ) ) {
            do_action('braapf_slider_data_update');
        }
        if( $previous !== '0' && ( version_compare($previous, '1.5.2.8', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '3.0.2.7', '<') ) ) ) {
            $options = $this->get_option();
            if( ! empty($options['use_filtered_variation']) || ! empty($options['use_filtered_variation_once']) ) {
                if( ! empty($options['use_filtered_variation']) && ! empty($options['use_filtered_variation_once']) ) {
                    $options['select_filter_variation'] = 'url_session';
                } elseif( ! empty($options['use_filtered_variation']) ) {
                    $options['select_filter_variation'] = 'session';
                } else {
                    $options['select_filter_variation'] = 'url';
                }
                update_option( 'br_filters_options', $options );
            }
        }
        if( $previous !== '0' && ( version_compare($previous, '1.5.6', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '3.0.5', '<') ) ) ) {
            $deprecated_filters = false;
            if( ! empty($options['addons']) && is_array($options['addons']) ) {
                foreach($options['addons'] as $i => $addon) {
                    if( strpos($addon, 'deprecated_filters.php') !== FALSE ) {
                        unset($options['addons'][$i]);
                        $deprecated_filters = true;
                        break;
                    }
                }
            }
            if($deprecated_filters) {
                new berocket_admin_notices(array(
                    'start' => 0,
                    'end'   => 0,
                    'name'  => 'aapf_remove_deprecated_filters',
                    'html'  => 'AJAX Product Filters. Deprecated Filters add-on enabled on your site, but it will be removed in near future. You can disable Deprecated Filters in <a href="'.admin_url('admin.php?page=br-product-filters&tab=addons').'">Plugin settings -> Add-ons tab</a>',
                    'righthtml'  => '<a class="berocket_no_thanks">Close notice</a>',
                    'rightwidth'  => 200,
                    'nothankswidth'  => 200,
                    'contentwidth'  => 400,
                    'subscribe'  => false,
                    'priority'  => 20,
                    'height'  => 50,
                    'repeat'  => false,
                    'repeatcount'  => 1,
                    'image'  => array(
                        'local' => plugin_dir_url( __FILE__ ) . 'images/attention.png',
                    ),
                ));
            }
        }
        if( $previous !== '0' && ( version_compare($previous, '1.6', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '3.1', '<') ) ) ) {
            $options = $this->get_option();
            if( ! empty($options['addons']) && is_array($options['addons']) ) {
                foreach($options['addons'] as $i => $addon) {
                    if( strpos($addon, 'deprecated_filters.php') !== FALSE ) {
                        unset($options['addons'][$i]);
                        break;
                    }
                }
            }
            $options['purge_cache_time'] = time();
            update_option( 'br_filters_options', $options );
            delete_option('BeRocket_aapf_additional_tables_addon_position');
        }
        if( $previous !== '0' && ( version_compare($previous, '1.6.3', '<') || (version_compare($previous, '2.0', '>') && version_compare($previous, '3.1.3', '<') ) ) ) {
            $current_position = get_option('BeRocket_aapf_additional_tables_addon_position');
            if( ! empty($current_position) && $current_position >= 6 ) {
                update_option('BeRocket_aapf_additional_tables_addon_position', 'ended');
            }
        }
    }
    public function save_settings_callback( $settings ) {
        $options = $this->get_option();
        delete_option( 'rewrite_rules' );
        flush_rewrite_rules(true);

        return parent::save_settings_callback( $settings );
    }
    public function parse_header_info() {
        global $braapf_parameters;
        $braapf_parameters = array();
        $braapf_parameters['ajax_filtering'] = ! empty($_SERVER['HTTP_X_BRAAPF']);
        $braapf_parameters['do_not_display_filters'] = false;//! empty($_SERVER['HTTP_X_BRAAPFDISABLE']);
    }
    public function no_products_block_before($teplate_name) {
        if( $teplate_name == 'loop/no-products-found.php' ) {
            echo '<div class="bapf_no_products">';
        }
    }
    public function no_products_block_after($teplate_name) {
        if( $teplate_name == 'loop/no-products-found.php' ) {
            echo '</div>';
        }
    }
    public function include_all_tempate_styles() {
		if ( file_exists( STYLESHEETPATH . '/braapf-template-styles' ) ) {
            foreach (glob(STYLESHEETPATH . '/braapf-template-styles/*.php') as $filename) {
                include_once($filename);
            }
		}
        if ( file_exists( TEMPLATEPATH . '/braapf-template-styles' ) ) {
            foreach (glob(TEMPLATEPATH . '/braapf-template-styles/*.php') as $filename) {
                include_once($filename);
            }
		}
        foreach (glob($this->info['plugin_dir'] . '/template_styles/*.php') as $filename) {
            include_once($filename);
        }
        $styles = apply_filters('BeRocket_AAPF_getall_Template_Styles', array());
        if( ! empty($styles) && is_array($styles) ) {
            foreach( $styles as &$style ) {
                if( isset($style['this']) ) {
                    unset($style['this']);
                }
            }
            if( isset($style) ) {
                unset($style);
            }
        }
        update_option('BeRocket_AAPF_getall_Template_Styles', $styles);
    }
    public function replace_deprecated_with_new() {
        require_once dirname( __FILE__ ) . '/fixes/replace_widgets.php';
    }
    public function bapf_wp_footer() {
        do_action('bapf_wp_footer');
    }
    public function divi_extensions_init() {
        include_once dirname( __FILE__ ) . '/includes/divi/DiviExtension.php';
    }
}

new BeRocket_AAPF;