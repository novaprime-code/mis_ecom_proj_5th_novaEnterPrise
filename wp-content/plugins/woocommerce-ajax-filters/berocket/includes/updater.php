<?php
if ( ! class_exists( 'BeRocket_updater' ) ) {
    define( "BeRocket_update_path", 'https://berocket.com/' );
    define( "BeRocket_updater_log", true );
    include_once( plugin_dir_path( __FILE__ ) . 'error_notices.php' );

    class BeRocket_updater {
        public static $plugin_info = array();
        public static $slugs       = array();
        public static $key         = '';
        public static $error_log   = array();
        public static $debug_mode  = false;

        public static function init() {
            add_action( 'admin_init', array(__CLASS__, 'admin_init') );
            $options          = self::get_options();
            self::$debug_mode = ! empty( $options[ 'debug_mode' ] );
        }

        public static function admin_init() {
            add_filter('woocommerce_addons_sections', array(__CLASS__, 'woocommerce_addons_sections'));
            if( isset($_GET['page']) && isset($_GET['section']) && $_GET['page'] == 'wc-addons' && ( $_GET['section'] == 'berocket' || ! empty($_GET['search']) ) ) {
                add_action('admin_footer', array(__CLASS__, 'woocommerce_addons_berocket'));
            }
        }

        public static function run() {
            $options          = self::get_options();
            self::$debug_mode = ! empty( $options[ 'debug_mode' ] );
            self::$key        = ( empty( $options[ 'account_key' ] ) ? '' : $options[ 'account_key' ] );

            add_action( 'admin_head', array( __CLASS__, 'scripts' ) );
            add_action( 'admin_menu', array( __CLASS__, 'main_menu_item' ), 1 );
            add_action( 'admin_menu', array( __CLASS__, 'account_page' ), 500 );
            add_action( 'network_admin_menu', array( __CLASS__, 'network_account_page' ) );
            add_action( 'admin_init', array( __CLASS__, 'account_option_register' ) );
            add_filter( 'pre_set_site_transient_update_plugins', array( __CLASS__, 'update_check_set' ) );
            add_action( 'install_plugins_pre_plugin-information', array( __CLASS__, 'plugin_info' ), 1 );
            add_action( "wp_ajax_br_test_key", array( __CLASS__, 'test_key' ) );
            add_filter( 'http_request_host_is_external', array( __CLASS__, 'allow_berocket_host' ), 10, 3 );

            if ( BeRocket_updater_log ) {
                add_action( 'admin_footer', array( __CLASS__, 'error_log' ) );
                add_action( 'wp_footer', array( __CLASS__, 'error_log' ) );
            }

            $plugin = array();
            $plugin = apply_filters( 'BeRocket_updater_add_plugin', $plugin );

            if ( ! isset( $options[ 'plugin_key' ] ) || ! is_array( $options[ 'plugin_key' ] ) ) {
                $options[ 'plugin_key' ] = array();
            }

            $update = false;
            foreach ( $plugin as $plug_id => $plug ) {
                self::$slugs[ $plug[ 'id' ] ] = $plug[ 'slug' ];

                if ( isset( $options[ 'plugin_key' ][ $plug[ 'id' ] ] ) && $options[ 'plugin_key' ][ $plug[ 'id' ] ] != '' ) {
                    $plugin[ $plug_id ][ 'key' ] = $options[ 'plugin_key' ][ $plug[ 'id' ] ];
                } elseif ( isset( $plugin[ $plug_id ][ 'key' ] ) && $plugin[ $plug_id ][ 'key' ] != '' ) {
                    $options[ 'plugin_key' ][ $plug[ 'id' ] ] = $plugin[ $plug_id ][ 'key' ];
                    $update                                   = true;
                }
            }

            self::$plugin_info = $plugin;

            if ( $update ) {
                self::set_options( $options );
            }

            add_filter( 'berocket_display_additional_notices', array(
                __CLASS__,
                'berocket_display_additional_notices'
            ) );

            if( ! is_network_admin() ) {
                add_filter( 'custom_menu_order', array( __CLASS__, 'wp_menu_order' ) );
            }

            //ADMIN NOTICE CHECK
            add_filter( 'berocket_admin_notice_is_display_notice', array( __CLASS__, 'admin_notice_is_display_notice' ), 10, 3 );
            add_filter( 'berocket_admin_notice_is_display_notice_priority', array( __CLASS__, 'admin_notice_is_display_notice' ), 10, 3 );
        }

        public static function error_log() {
            if ( self::$debug_mode ) {
                $plugins_list = self::$plugin_info;
                if( is_array($plugins_list) && count($plugins_list) > 0 ) {
                    foreach($plugins_list as &$plugin) {
                        if( ! empty($plugin['key']) ) {
                            $plugin['key'] = self::hide_key($plugin['key']);
                        }
                    }
                }
                self::$error_log                          = apply_filters( 'BeRocket_updater_error_log', self::$error_log );
                self::$error_log[ 'real_memory_usage' ]   = memory_get_peak_usage( true );
                self::$error_log[ 'script_memory_usage' ] = memory_get_peak_usage( false );
                self::$error_log[ 'plugins' ]             = $plugins_list;
                self::$error_log[ 'memory_limit' ]        = ini_get( 'memory_limit' );
                self::$error_log[ 'WP_DEBUG' ]            = 'WP_DEBUG:' . ( defined( 'WP_DEBUG' ) ? ( WP_DEBUG ? 'true' : 'false' ) : 'false' ) . '; WP_DEBUG_DISPLAY:' . ( defined( 'WP_DEBUG_DISPLAY' ) ? ( WP_DEBUG_DISPLAY ? 'true' : 'false' ) : 'false' );
                $error_log = unserialize(preg_replace('/R:\d+/', 's:18:"RECURSION DETECTED"', serialize(self::$error_log)));
                ?>
                <script>
                    console.log(<?php echo json_encode( $error_log ); ?>);
                </script>
                <?php
            }
            if( ! empty($_GET['BRvercheck']) ) {
                $plugin_versions = array();
                foreach(self::$plugin_info as $plugin_i) {
                    $plugin_versions[$plugin_i['plugin_name']] = array('name' => $plugin_i['name'], 'version' => $plugin_i['version']);
                }
                ?>
                <script>
                    console.log(<?php echo json_encode( $plugin_versions ); ?>);
                </script>
                <?php
            }
        }

        public static function wp_menu_order( $menu_ord ) {
            global $submenu;

            if( empty($submenu[ 'berocket_account' ]) || ! is_array($submenu[ 'berocket_account' ]) || count($submenu[ 'berocket_account' ]) == 0 ) {
                return $menu_ord;
            }
            $new_order_temp = array();
            $new_sub_order  = array();
            $new_order_sort = array();

            $compatibility_hack = apply_filters('BeRocket_updater_menu_order_custom_post', array());

            $BeRocket_item = false;
            $account_keys_item = false;
            foreach ( $submenu[ 'berocket_account' ] as $item ) {
                if ( $item[ 0 ] == 'BeRocket' ) {
                    $BeRocket_item = $item;
                    continue;
                } elseif ( $item[ 0 ] == __('Account Keys', 'BeRocket_domain') ) {
                    $account_keys_item = $item;
                    continue;
                }
                if ( ! empty($compatibility_hack[ str_replace( "edit.php?post_type=", "", $item[ 2 ] ) ]) ) {
                    $item_0 = "<span class='berocket_admin_menu_custom_post_submenu";
                    if ( $item[ 0 ] == 'Upgrade' ) {
                        $item_0 .= " berocket_admin_menu_custom_post_submenu_upgrade";
                        $item[ 0 ] .= " &#9889;";
                    }
                    $item[ 0 ] = $item_0 . "'>" . $item[ 0 ] . "</span>";
                    $new_sub_order[ $compatibility_hack[ str_replace( "edit.php?post_type=", "", $item[ 2 ] ) ] ][] = $item;
                } else {
                    $new_order_temp[] = $item;
                    $new_order_sort[] = $item[ 0 ];
                }
            }
            $new_sub_order = apply_filters('BeRocket_updater_menu_order_sub_order', $new_sub_order);

            array_multisort( $new_order_sort, $new_order_temp );

            $new_order = array();
            if( $BeRocket_item !== false ) {
                $new_order[] = $BeRocket_item;
            }
            foreach ( $new_order_temp as $item ) {
                $new_order[] = $item;

                if ( ! empty( $new_sub_order[ $item[ 2 ] ] ) ) {
                    foreach ( $new_sub_order[ $item[ 2 ] ] as $sub_item ) {
                        $new_order[] = $sub_item;
                    }
                }
            }
            if( $account_keys_item !== false ) {
                $new_order[] = $account_keys_item;
            }

            $submenu[ 'berocket_account' ] = $new_order;

            return $menu_ord;
        }

        public static function add_to_debug( $data, $plugin, $keys = false ) {
            if ( self::$debug_mode ) {
                if ( $keys === false ) {
                    self::$error_log[ $plugin ][] = $data;
                } elseif ( is_array( $keys ) ) {
                    if ( count( $keys ) > 0 ) {
                        $data_set = self::$error_log[ $plugin ];
                        $last_key = array_pop( $keys );

                        foreach ( $keys as $key ) {
                            if ( ! is_array( $data_set[ $key ] ) ) {
                                $data_set[ $key ] = array();
                            }

                            $new_set = &$data_set[ $key ];
                            unset( $data_set );

                            $data_set = &$new_set;
                            unset( $new_set );
                        }

                        if ( empty( $last_key ) ) {
                            $data_set[] = $data;
                        } else {
                            $data_set[ $last_key ] = $data;
                        }
                    } else {
                        self::$error_log[ $plugin ][] = $data;
                    }
                } else {
                    self::$error_log[ $plugin ][ $keys ] = $data;
                }
            }
        }

        public static function is_plugin_paid_active($plugin_id) {
            $active_plugin      = get_option( 'berocket_key_activated_plugins' );
            $active_site_plugin = get_site_option( 'berocket_key_activated_plugins' );
            if ( ! is_array( $active_plugin ) ) {
                $active_plugin = array();
            }
            if ( ! is_array( $active_site_plugin ) ) {
                $active_site_plugin = array();
            }
            return ! empty( $active_plugin[ $plugin_id ] ) || ! empty( $active_site_plugin[ $plugin_id ] );
        }

        public static function berocket_display_additional_notices( $notices ) {
            if ( ! empty( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'berocket_account' ) {
                return $notices;
            }

            $not_activated_notices = array();
            foreach ( self::$plugin_info as $plugin ) {
                if ( ! self::is_plugin_paid_active($plugin[ 'id' ]) ) {
                    $version_capability = br_get_value_from_array($plugin, array('version_capability'), 15);
                    if ( $version_capability > 5 && ! in_array($version_capability, array(15, 3, 17)) ) {
                        $meta_data = '?utm_source=paid_plugin&utm_medium=notice&utm_campaign='.$plugin['plugin_name'];
                        $not_activated_notices[] = array(
                            'start'         => 0,
                            'end'           => 0,
                            'name'          => $plugin[ 'name' ],
                            'html'          => __('Please', 'BeRocket_domain'). ' ' . __('activate plugin', 'BeRocket_domain') . ' ' . $plugin[ 'name' ] . ' ' . __('with help of plugin/account key from', 'BeRocket_domain'). ' '
                                               . '<a href="' . BeRocket_update_path . 'user' . $meta_data . '" target="_blank">' . __('BeRocket account', 'BeRocket_domain') . '</a>. '
                                               . __('You can activate plugin in', 'BeRocket_domain')
                                               . '<a class="berocket_button" href="' . ( is_network_admin() ? admin_url( 'network/admin.php?page=berocket_account' ) : admin_url( 'admin.php?page=berocket_account' ) ) . '">' . __('BeRocket Account settings', 'BeRocket_domain') . '</a>
                                ',
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
                    }
                }
            }

            $notices = array_merge( $notices, $not_activated_notices );

            return $notices;
        }

        public static function get_plugin_count() {
            $count = count( self::$plugin_info );

            return $count;
        }

        public static function allow_berocket_host( $allow, $host, $url ) {
            if ( $host == 'berocket.com' ) {
                $allow = true;
            }

            return $allow;
        }

        public static function test_key() {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            if ( ! isset( $_POST[ 'key' ] ) || ! isset( $_POST[ 'id' ] ) ) {
                $data = array(
                    'key_exist' => 0,
                    'status'    => 'Failed',
                    'error'     => 'Incorrect query for this function(ID and Key must be sended)'
                );

                $out  = json_encode( $data );
            } else {
                $key = sanitize_text_field( $_POST[ 'key' ] );
                $id  = sanitize_text_field( $_POST[ 'id' ] );
                $fast  = ! empty($_POST[ 'fast' ]);
                $site_url = get_site_url();
                $plugins  = self::$plugin_info;
                if( strpos($key, '**') !== false ) {
                    if ($id == 0 ) {
                        $key = self::$key;
                    } elseif( is_array($plugins) && count($plugins) > 0 ) {
                        foreach($plugins as $plugin_info) {
                            if($plugin_info['id'] == $id) {
                                $key = $plugin_info['key'];
                            }
                        }
                    }
                }
                

                if ( is_array( $plugins ) ) {
                    $plugins = array_keys( $plugins );
                    $plugins = implode( ',', $plugins );
                } else {
                    $plugins = '';
                }

                $response = wp_remote_post( BeRocket_update_path . 'main/account_updater', array(
                    'body'        => array(
                        'key'     => $key,
                        'id'      => $id,
                        'url'     => $site_url,
                        'plugins' => $plugins
                    ),
                    'method'      => 'POST',
                    'timeout'     => 30,
                    'redirection' => 5,
                    'blocking'    => true,
                    'sslverify'   => false
                ) );

                $options            = self::get_options();
                if ( ! is_wp_error( $response ) ) {
                    $out            = wp_remote_retrieve_body( $response );
                    $current_plugin = false;
                    $out            = json_decode( $out, true );

                    if ( ! is_array( $out ) ) {
                        $out = array();
                    }

                    $out[ 'upgrade' ] = array();

                    if( ! $fast ) {
                        if ( $id != 0 ) {
                            foreach ( self::$plugin_info as $plugin ) {
                                if ( $plugin[ 'id' ] == $id ) {
                                    $current_plugin = $plugin;
                                    break;
                                }
                            }

                            if ( $current_plugin !== false ) {
                                if ( empty( $out[ 'error' ] ) ) {
                                    $options[ 'plugin_key' ][ $id ] = $key;

                                    if ( isset( $out[ 'versions' ][ $id ] ) && version_compare( $current_plugin[ 'version' ], $out[ 'versions' ][ $id ], '<' ) ) {
                                        $upgrade_button        = '<a href="' . wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $current_plugin[ 'plugin' ], 'upgrade-plugin_' . $current_plugin[ 'plugin' ] ) . '" class="button tiny-button">Upgrade plugin</a>';
                                        $out[ 'upgrade' ][]    = array( 'id' => $id, 'upgrade' => $upgrade_button );
                                    }
                                }
                            }
                        } else {
                            foreach ( self::$plugin_info as $plugin ) {
                                if ( isset( $out[ 'versions' ][ $plugin[ 'id' ] ] ) && version_compare( $plugin[ 'version' ], $out[ 'versions' ][ $plugin[ 'id' ] ], '<' ) ) {
                                    $upgrade_button     = '<a href="' . wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $plugin[ 'plugin' ], 'upgrade-plugin_' . $plugin[ 'plugin' ] ) . '" class="button tiny-button">Upgrade plugin</a>';
                                    $out[ 'upgrade' ][] = array(
                                        'id'      => $plugin[ 'id' ],
                                        'upgrade' => $upgrade_button
                                    );
                                }
                            }
                            $options[ 'account_key' ] = $key;
                        }
                    }
                    $out = json_encode( $out );
                } else {
                    $data = array(
                        'key_exist' => 0,
                        'status'    => 'Failed',
                        'error'     => $response->get_error_message()
                    );

                    $out  = json_encode( $data );
                }
                if( ! $fast ) {
                    self::set_options( $options );
                }
            }
            echo $out;
            wp_die();
        }

        public static function scripts() {
            ?>
            <script>
                function BeRocket_key_check(key, show_correct, product_id, async_func, fast) {
                    if (typeof( product_id ) == 'undefined' || product_id == null) {
                        product_id = 0;
                    }
                    if (typeof( async_func ) == 'undefined' || async_func == null) {
                        async_func = false;
                    }
                    if (typeof( fast ) == 'undefined' || fast == null) {
                        fast = 0;
                    }
                    var async = false;
                    if( async_func !== false ) {
                        async = true;
                    }
                    data = {action: 'br_test_key', key: key, id: product_id, fast:fast};
                    is_submit = false;
                    jQuery.ajax({
                        url: ajaxurl,
                        data: data,
                        type: 'POST',
                        success: function (data) {
                            jQuery('.berocket_test_result').html(data);
                            if (data.key_exist == 1) {
                                if (show_correct) {
                                    html = '<h3>' + data.status + '</h3>';
                                    jQuery('.berocket_test_result').html(html);
                                    data.upgrade.forEach(function (el, i, arr) {
                                        jQuery('.berocket_product_key_' + el.id + '_status').html(el.upgrade);
                                    });
                                }
                                is_submit = true;
                            } else {
                                if (show_correct) {
                                    html = '<h3>' + data.status + '</h3>';
                                    html += '<p><b>Error message:</b>' + data.error + '</p>';
                                    jQuery('.berocket_test_result').html(html);
                                }
                            }
                            jQuery('.berocket_product_key_' + product_id + '_status').text(data.status);
                            if( typeof(async_func) == 'function' ) {
                                async_func(is_submit);
                            }
                        },
                        dataType: 'json',
                        async: async
                    });
                    return is_submit;
                }
                jQuery(document).on('click', '.berocket_test_account_product', function (event) {
                    event.preventDefault();
                    if (jQuery(this).data('product')) {
                        key = jQuery(jQuery(this).data('product')).val();
                    } else {
                        key = jQuery('#berocket_product_key').val();
                    }
                    BeRocket_key_check(key, true, jQuery(this).data('id'));
                });
            </script>
            <style>
                .toplevel_page_berocket_account .dashicons-before img {
                    max-width: 16px;
                }
            </style>
            <?php
        }

        public static function network_account_page() {
            add_menu_page( __('BeRocket Account Settings', 'BeRocket_domain'), __('BeRocket Account', 'BeRocket_domain'), 'manage_berocket', 'berocket_account', array(
                    __CLASS__,
                    'account_form_network'
                ), plugin_dir_url( __FILE__ ) . 'ico.png', '55.55' );
        }

        public static function main_menu_item() {
            add_menu_page( 'BeRocket Account', 'BeRocket', 'manage_berocket', 'berocket_account', array(
                    __CLASS__,
                    'account_form'
                ), plugin_dir_url( __FILE__ ) . 'ico.png', '55.55' );
        }

        public static function account_page() {
            add_submenu_page( 'berocket_account', __('BeRocket Account Settings', 'BeRocket_domain'), __('Account Keys', 'BeRocket_domain'), 'manage_berocket_account', 'berocket_account', array(
                    __CLASS__,
                    'account_form'
                ) );
        }

        public static function account_option_register() {
            register_setting( 'BeRocket_account_option_settings', 'BeRocket_account_option', array('sanitize_callback' => array(__CLASS__, 'reset_update_plugin_data')) );
        }

        public static function reset_update_plugin_data($options) {
            $options = self::restore_keys($options);
            self::update_check_set('');
            delete_site_transient( 'update_plugins' );
            return $options;
        }

        public static function account_form() {
            wp_enqueue_style( 'berocket_framework_admin_style' );
            ?>
            <div class="wrap">
                <form method="post" action="options.php" class="account_key_send br_framework_settings">
                    <?php
                    $options = get_option( 'BeRocket_account_option' );
                    self::inside_form( $options );
                    ?>
                </form>
            </div>
            <?php
        }

        public static function account_form_network() {
            ?>
            <div class="wrap">
                <form method="post" action="edit.php?page=berocket_account" class="account_key_send br_framework_settings">
                    <?php
                    if ( isset( $_POST[ 'BeRocket_account_option' ] ) ) {
                        $previous_options = get_site_option( 'BeRocket_account_option' );
                        $option = berocket_sanitize_array( $_POST[ 'BeRocket_account_option' ], array('BeRocket_account_option'), $previous_options );
                        update_site_option( 'BeRocket_account_option', $option );
                        self::update_check_set('');
                        delete_site_transient( 'update_plugins' );
                    }

                    $options = get_site_option( 'BeRocket_account_option' );
                    self::inside_form( $options );
                    ?>
                </form>
            </div>
            <?php
        }

        public static function inside_form( $options ) {
            settings_fields( 'BeRocket_account_option_settings' );
            if ( isset( $options[ 'plugin_key' ] ) && is_array( $options[ 'plugin_key' ] ) ) {
                $plugins_key = $options[ 'plugin_key' ];
            } else {
                $plugins_key = array();
            }
            $has_free = false;
            foreach(self::$plugin_info as $plugin) {
                if( $plugin['version_capability'] < 10 ) {
                    $has_free = true;
                }
            }
            if( $has_free ) {
                if ( time() > 1637841600 and time() < 1637841600+302400 ) {
                    echo "
                    <div class='berocket-above-settings-banner' style='background: #1a1a1a; padding: 0;'>
                        <a href='https://berocket.com/products?utm_source=free_plugin&utm_medium=settings&utm_campaign=account_keys&utm_content=top' target='_blank' 
                        style='background: transparent; width: auto; border: 0 none; box-shadow: none; padding: 0; margin: 0;'>
                            <img alt='BeRocket Products' src='https://berocket.ams3.cdn.digitaloceanspaces.com/g/bf21-1202x280.jpg' style='display: block;'>
                        </a>
                    </div>";
                } else if ( time() > 1637841600+302400 and time() < 1637841600+302400+518400 ) {
	                echo "
                    <div class='berocket-above-settings-banner berocket-cm21-settings-wrapper' style='background: #07002e; padding: 0;'>
                        <a href='https://berocket.com/products?utm_source=free_plugin&utm_medium=settings&utm_campaign=account_keys&utm_content=top' target='_blank' >
                            <img alt='BeRocket Products' src='https://berocket.ams3.cdn.digitaloceanspaces.com/g/cm21.jpg'>
                            <div class='berocket-cm21-settings'>
                                <div class='berocket-cm21-settings-header'>
                                    <p>Don't lose another 5% of the discount. Purchase now!</p>
                                </div>
                                <p style='top: 30%; left: 6%; '><span>Monday: <span style='padding-left: 20px; font-size: 1.25em; font-weight: bold;'>-30%</span></span></p>
                                <p style='top: 32%; left: 55%;'><span>Tuesday: <span style='padding-left: 15px; font-size: 1.2em; font-weight: bold;'>-25%</span></span></p>
                                <p style='top: 48%; left: 10%;'><span>Wednesday: <span style='padding-left: 5px; font-size: 1.15em'>-20%</span></span></p>
                                <p style='top: 50%; left: 59%;'><span>Thursday: <span style='padding-left: 10px; font-size: 1.1em'>-15%</span></span></p>
                                <p style='top: 66%; left: 16%;'><span>Friday: <span style='padding-left: 20px; font-size: 0.9em'>-10%</span></span></p>
                                <p style='top: 68%; left: 63%;'><span>Saturday: <span style='padding-left: 15px; font-size: 0.9em'>-5%</span></span></p>
                            </div>
                            <div class='berocket-cm21-settings-mobiles-title' style='display: none;'>Up to 30% off sitewide!</div>
                        </a>
                    </div>";
                }
            }
            ?>
            <h2><?php _e('BeRocket Account Settings', 'BeRocket_domain'); ?></h2>
            <div>
                <table>
                    <tr>
                        <td><h3><?php _e('DEBUG MODE', 'BeRocket_domain'); ?></h3></td>
                        <td colspan=3><label><input type="checkbox" name="BeRocket_account_option[debug_mode]"
                                                    value="1"<?php if ( ! empty( $options[ 'debug_mode' ] ) )
                                    echo ' checked' ?>><?php _e('Enable debug mode', 'BeRocket_domain'); ?></label></td>
                    </tr>
                    <tr<?php if(empty( $options[ 'account_key' ] )) { echo ' style="display:none;"';}?>>
                        <td><h3><?php _e('Account key', 'BeRocket_domain'); ?></h3></td>
                        <td><input type="text" id="berocket_account_key" name="BeRocket_account_option[account_key]"
                                   size="50"
                                   value="<?php echo( empty( $options[ 'account_key' ] ) ? '' : self::hide_key($options[ 'account_key' ]) ) ?>">
                        </td>
                        <td><input class="berocket_test_account button tiny-button" type="button" value="Test"></td>
                        <td class="berocket_product_key_0_status"></td>
                    </tr>
                    <?php
                    foreach ( self::$plugin_info as $plugin ) {
                        echo '<tr class="berocket_updater_plugin_key" data-id="', $plugin[ 'id' ], '">';
                        echo '<td><h4>';
                        if ( isset( $plugin[ 'name' ] ) ) {
                            echo $plugin[ 'name' ];
                        } else {
                            echo $plugin[ 'slug' ];
                        }
                        echo '</h4></td>';
                        echo '<td><input class="berocket_test_account_product_key" id="berocket_product_key_', $plugin[ 'id' ], '" size="50" name="BeRocket_account_option[plugin_key][', $plugin[ 'id' ], ']" type="text" value="', ( empty( $options[ 'plugin_key' ][ $plugin[ 'id' ] ] ) ? '' : self::hide_key($options[ 'plugin_key' ][ $plugin[ 'id' ] ]) ), '"></td>';
                        echo '<td><input class="berocket_test_account_product save_checked button tiny-button" data-id="', $plugin[ 'id' ], '" data-product="#berocket_product_key_', $plugin[ 'id' ], '" type="button" value="Test"></td>';
                        echo '<td class="berocket_product_key_status berocket_product_key_', $plugin[ 'id' ], '_status"></td>';
                        echo '</tr>';
                        unset( $plugins_key[ $plugin[ 'id' ] ] );
                    }
                    foreach ( $plugins_key as $key_id => $key_val ) {
                        echo '<input name="BeRocket_account_option[plugin_key][', $key_id, ']" type="hidden" value="', $key_val, '">';
                    }
                    ?>
                </table>
            </div>
            <div class="berocket_test_result"></div>
            <button type="submit" class="button"><?php _e('Save Changes', 'BeRocket_domain'); ?></button>

            <div class="berocket_debug_errors">
                <h3><?php _e('Errors', 'BeRocket_domain'); ?></h3>
                <div>
                    <?php _e('Select plugin', 'BeRocket_domain'); ?>
                    <select class="berocket_select_plugin_for_error">
                        <?php
                        foreach ( self::$plugin_info as $plugin ) {
                            echo '<option value="' . $plugin[ 'id' ] . '">' . $plugin[ 'name' ] . '</option>';
                        }
                        ?>
                    </select>
                    <button type="button" class="button tiny-button berocket_get_plugin_for_error">Get errors</button>
                    <div class="berocket_html_plugin_for_error"></div>
                </div>
            </div>
            <script>
                jQuery('.berocket_get_plugin_for_error').click(function () {
                    var plugin_id = jQuery('.berocket_select_plugin_for_error').val();
                    jQuery.post(ajaxurl, {action: 'berocket_error_notices_get', plugin_id: plugin_id}, function (data) {
                        jQuery('.berocket_html_plugin_for_error').html(data);
                    });
                });
                jQuery('.berocket_test_account').click(function (event) {
                    event.preventDefault();
                    key = jQuery('#berocket_account_key').val();
                    BeRocket_key_check(key, true);
                });
                jQuery(document).on('change', '.berocket_test_account_product_key', function() {
                    jQuery(this).parents('.berocket_updater_plugin_key').find('.berocket_test_account_product').removeClass('save_checked');
                });
                var berocket_key_checked = 0;
                var berocket_key_count = 1;
                function next_berocket_key_check(result) {
                    berocket_key_checked = berocket_key_count - jQuery('.berocket_test_account_product:not(.save_checked), #berocket_account_key:not(.save_checked)').length;
                    var button = jQuery('.account_key_send .button[type="submit"]');
                    var element = jQuery('.berocket_test_account_product:not(.save_checked)').first();
                    if( element.length ) {
                        button.html('Checking keys '+berocket_key_checked+' / '+berocket_key_count+' <i class="fa fa-refresh fa-spin"></i>');
                        if (element.data('product')) {
                            key = jQuery(element.data('product')).val();
                        } else {
                            key = jQuery('#berocket_product_key').val();
                        }
                        element.addClass('save_checked');
                        BeRocket_key_check(key, false, element.data('id'), next_berocket_key_check, 1);
                    } else {
                        button.html('Saving keys <i class="fa fa-refresh fa-spin"></i>');
                        jQuery('.account_key_send').trigger('submit');
                    }
                }
                jQuery(document).on('submit', '.account_key_send', function (event) {
                    var key_count = jQuery('.berocket_test_account_product:not(.save_checked), #berocket_account_key:not(.save_checked)').length;
                    if( ! jQuery(this).is('.saving') ) {
                        jQuery(this).addClass('saving');
                        berocket_key_checked = 0;
                        berocket_key_count = key_count;
                        if( berocket_key_count != 0 ) {
                            event.preventDefault();
                            var button = jQuery('.account_key_send .button[type="submit"]');
                            button.html('Checking keys '+berocket_key_checked+' / '+berocket_key_count+' <i class="fa fa-refresh fa-spin"></i>');
                            key = jQuery('#berocket_account_key').val();
                            jQuery('#berocket_account_key').addClass('save_checked');
                            BeRocket_key_check(key, false, null, next_berocket_key_check, 1);
                        }
                    } else if( key_count > 0 ) {
                        event.preventDefault();
                    }
                });
            </script>
			<style>.notice:not(.berocket_admin_notice){display:none!important;}</style>
            <?php
        }

        public static function update_check_set( $value ) {
            if ( is_network_admin() ) {
                $active_plugin = get_site_option( 'berocket_key_activated_plugins' );
            } else {
                $active_plugin = get_option( 'berocket_key_activated_plugins' );
            }

            $no_update_paid = array();

            foreach ( self::$plugin_info as $plugin ) {
                if ( ! empty( self::$key ) && strlen( self::$key ) == 40 ) {
                    $key = self::$key;
                }

                if ( ! empty( $plugin[ 'key' ] ) && strlen( $plugin[ 'key' ] ) == 40 ) {
                    $key = $plugin[ 'key' ];
                }

                $version = false;
                if ( ! empty( $key ) ) {
                    $version = get_transient( 'brversion_' . $plugin[ 'id' ] . '_' . $key );
                    if ( $version == false ) {
                        $site_url = get_site_url();
                        $url      = BeRocket_update_path . 'main/get_plugin_version/' . $plugin[ 'id' ] . '/' . $key;

                        $response = wp_remote_post( $url, array(
                            'body'        => array(
                                'url' => $site_url
                            ),
                            'method'      => 'POST',
                            'timeout'     => 30,
                            'redirection' => 5,
                            'blocking'    => true,
                            'sslverify'   => false
                        ) );

                        if ( ! is_wp_error( $response ) ) {
                            $out = wp_remote_retrieve_body( $response );
                            if ( ! empty( $out ) ) {
                                $out = json_decode( @ $out );
                                if ( ! empty( $out->status ) && $out->status == 'success' ) {
                                    $version = $out->version;
                                }
                            }
                        }
                        set_transient( 'brversion_' . $plugin[ 'id' ] . '_' . $key, $version, 600 );
                    }
                }

                if ( ! is_array( $active_plugin ) ) {
                    $active_plugin = array();
                }

                $responsed = false;
                if ( $version !== false ) {
                    $active_plugin[ $plugin[ 'id' ] ] = true;
                    if ( version_compare( $plugin[ 'version' ], $version, '<' ) && ! empty($value) ) {
                        $value->checked[ $plugin[ 'plugin' ] ]  = $version;
                        $val                                    = array(
                            'id'          => 'br_' . $plugin[ 'id' ],
                            'new_version' => $version,
                            'package'     => BeRocket_update_path . 'main/update_product/' . $plugin[ 'id' ] . '/' . $key,
                            'url'         => BeRocket_update_path . 'product/' . $plugin[ 'id' ],
                            'plugin'      => $plugin[ 'plugin' ],
                            'slug'        => $plugin[ 'slug' ]
                        );
                        
                        if( ! empty($plugin['free_slug']) ) {
                            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
                            $api = plugins_api( 'plugin_information', array(
                                'slug' => wp_unslash( $plugin['free_slug'] ),
                                'is_ssl' => is_ssl(),
                                'fields' => array(
                                    'banners' => true,
                                    'reviews' => false,
                                    'downloaded' => false,
                                    'active_installs' => true,
                                    'icons' => true
                                )
                            ) );
                            $api = (array)$api;
                            $val = array_merge($api, $val);
                        }
                        $val = (object)$val;
                        $value->response[ $plugin[ 'plugin' ] ] = $val;
                        $responsed = true;
                    }
                } else {
                    $active_plugin[ $plugin[ 'id' ] ] = false;
                }
                if( ! $responsed && isset($plugin[ 'version_capability' ]) && $plugin[ 'version_capability' ] >= 10 ) {
                    $val                                    = (object) array(
                        'id'          => 'br_' . $plugin[ 'id' ],
                        'new_version' => $plugin[ 'version' ],
                        'package'     => BeRocket_update_path . 'main/update_product/' . $plugin[ 'id' ] . '/' . ( empty($key) ? 'none' : $key ),
                        'url'         => BeRocket_update_path . 'product/' . $plugin[ 'id' ],
                        'plugin'      => $plugin[ 'plugin' ],
                        'slug'        => $plugin[ 'slug' ]
                    );
                    $no_update_paid[$plugin[ 'plugin' ]] = $val;
                }
            }

            if ( is_network_admin() ) {
                update_site_option( 'berocket_key_activated_plugins', $active_plugin );
            } else {
                update_option( 'berocket_key_activated_plugins', $active_plugin );
            }
            if ( ! empty($value) && isset( $value->no_update ) && is_array( $value->no_update ) ) {
                $value->no_update = array_merge($value->no_update, $no_update_paid);
                foreach ( $value->no_update as $key => $val ) {
                    if ( isset( $val->slug ) && in_array( $val->slug, self::$slugs ) ) {
                        if( ! array_key_exists($key, $no_update_paid) ) {
                            unset( $value->no_update[ $key ] );
                        }
                    }
                }
            }

            return $value;
        }

        public static function plugin_info() {
            $plugin = wp_unslash( $_REQUEST[ 'plugin' ] );

            if ( in_array( $plugin, self::$slugs ) ) {

                $plugin_id   = array_search( $plugin, self::$slugs );
                $plugin_data = self::get_plugin_data($plugin_id);
                $version_capability = br_get_value_from_array($plugin_data, array('version_capability'), 15);
                if( self::is_plugin_paid_active($plugin_id) || ($version_capability > 5 && ! in_array($version_capability, array(15, 3, 17))) ) {
                    remove_action( 'install_plugins_pre_plugin-information', 'install_plugin_information' );
                    $plugin_info = get_transient( 'brplugin_info_' . $plugin_id );

                    if ( $plugin_info == false ) {
                        $url      = BeRocket_update_path . 'main/update_info/' . $plugin_id;
                        $site_url = get_site_url();
                        $response = wp_remote_post( $url, array(
                            'body'        => array(
                                'url' => $site_url
                            ),
                            'method'      => 'POST',
                            'timeout'     => 30,
                            'redirection' => 5,
                            'blocking'    => true,
                            'sslverify'   => false
                        ) );

                        if ( ! is_wp_error( $response ) ) {
                            $plugin_info = wp_remote_retrieve_body( $response );
                            set_transient( 'brplugin_info_' . $plugin_id, $plugin_info, 600 );
                        }
                    }

                    echo $plugin_info;
                    die;
                }
            }
        }

        public static function get_options() {
            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            }

            if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
                $options = get_site_option( 'BeRocket_account_option' );
            } else {
                $options = get_option( 'BeRocket_account_option' );
            }

            if( empty($options) || ! is_array($options) ) {
                $options = array();
            }

            return $options;
        }

        public static function set_options( $options ) {
            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            }

            $options = self::restore_keys($options);
            if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
                update_site_option( 'BeRocket_account_option', $options );
            } else {
                update_option( 'BeRocket_account_option', $options );
            }
            self::update_check_set('');
            delete_site_transient( 'update_plugins' );
        }
        public static function admin_notice_is_display_notice($display_notice, $item, $search_data) {
            if( ! empty($item['for_plugin']) && is_array($item['for_plugin']) && ! empty($item['for_plugin']['id']) && ! empty($item['for_plugin']['version']) ) {
                $has_free = false;
                foreach ( self::$plugin_info as $plugin ) {
                    if( version_compare($plugin[ 'version' ], '2.0', '<') ) {
                        $has_free = true;
                    }
                    if ( $plugin[ 'id' ] == $item['for_plugin']['id'] && version_compare($plugin[ 'version' ], $item['for_plugin']['version'], '>=') ) {
                        $display_notice = false;
                        break;
                    }
                }
                if( ! $has_free && ! empty($item['for_plugin']['onlyfree']) ) {
                    $display_notice = false;
                }
            }
            return $display_notice;
        }

        public static function woocommerce_addons_sections($sections) {
            $sections[] = (object)array(
                'slug' => 'berocket',
                'label' => 'BeRocket'
            );
            return $sections;
        }
        public static function woocommerce_addons_berocket() {
            if ( false === ( $addons = get_transient( 'wc_addons_berocket' ) ) ) {
                $addons = array();
                $response = wp_remote_post( BeRocket_update_path . 'api/data/get_product_data/public', array(
                    'method'      => 'GET',
                    'timeout'     => 30,
                    'redirection' => 5,
                    'blocking'    => true,
                    'sslverify'   => false
                ) );

                if ( ! is_wp_error( $response ) ) {
                    $products  = wp_remote_retrieve_body( $response );
                    $products = json_decode($products);
                    foreach($products as $product) {
                        $addons[] = (object)array(
                            'title' => $product->name,
                            'image' => $product->mini_image,
                            'excerpt' => $product->about,
                            'link'      => $product->plugin_url,
                            'price'     => '$'.$product->price,
                            'hash'      => '',
                            'slug'      => $product->slug
                        );
                    }

                    set_transient( 'wc_addons_berocket', $addons, DAY_IN_SECONDS );
                }
                
            }
            if(! empty($_GET['search']) ) {
                $correct_addon = array();
                $search  = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
                foreach($addons as $addon) {
                    if( stripos($addon->title, $search) !== FALSE || stripos($addon->excerpt, $search) !== FALSE ) {
                        $correct_addon[] = $addon;
                    }
                }
                $addons = $correct_addon;
            }
            ?>
            <ul class="berocket_section_wc_addons" style="display: none;">
            <?php foreach ( $addons as $addon ) : ?>
                <li class="product">
                    <a href="<?php echo esc_attr( $addon->link ); ?>">
                        <?php if ( ! empty( $addon->image ) ) : ?>
                            <span class="product-img-wrap"><img src="<?php echo esc_url( $addon->image ); ?>"/></span>
                        <?php else : ?>
                            <h2><?php echo esc_html( $addon->title ); ?></h2>
                        <?php endif; ?>
                        <span class="price"><?php echo wp_kses_post( $addon->price ); ?></span>
                        <p><?php echo wp_kses_post( $addon->excerpt ); ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
            <ul class="berocket_section_wc_addons_new" style="display: none;">
            <?php 
            $class_names = array( 'product' );
            $product_details_classes = 'product-details';
            foreach( $addons as $addon ) {
                ?>
                <li class="<?php echo esc_attr( implode( ' ', $class_names ) ); ?>">
                    <div class="<?php echo esc_attr( $product_details_classes ); ?>">
                        <div class="product-text-container">
                            <a href="<?php echo esc_url( self::link_marketplace($addon->link) ); ?>">
                                <h2><?php echo esc_html( $addon->title ); ?></h2>
                            </a>
                            <div class="product-developed-by">
                                <?php
                                $vendor_url = self::link_marketplace('https://berocket.com');

                                printf(
                                /* translators: %s vendor link */
                                    esc_html__( 'Developed by %s', 'woocommerce' ),
                                    sprintf(
                                        '<a class="product-vendor-link" href="%1$s" target="_blank">%2$s</a>',
                                        esc_url_raw( $vendor_url ),
                                        esc_html( 'BeRocket' )
                                    )
                                );
                                ?>
                            </div>
                            <p><?php echo wp_kses_post( $addon->excerpt ); ?></p>
                        </div>
                        <?php if ( ! empty( $addon->image ) ) : ?>
                            <span class="product-img-wrap">
                                <?php /* Show an icon if it exists */ ?>
                                <img src="<?php echo esc_url( $addon->image ); ?>" />
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="product-footer">
                        <div class="product-price-and-reviews-container">
                            <div class="product-price-block">
                                <?php if ( $addon->price == 0 || $addon->price == '$0' ) : ?>
                                    <span class="price"><?php esc_html_e( 'Free', 'woocommerce' ); ?></span>
                                <?php else : ?>
                                    <span class="price">
                                        <?php
                                        echo wp_kses(
                                            $addon->price,
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                                'bdi'  => array(),
                                            )
                                        );
                                        ?>
                                    </span>
                                    <span class="price-suffix"><?php esc_html_e( 'one time', 'woocommerce' ); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ( ! empty( $mapped->reviews_count ) && ! empty( $mapped->rating ) ) : ?>
                                <?php /* Show rating and the number of reviews */ ?>
                                <div class="product-reviews-block">
                                    <?php for ( $index = 1; $index <= 5; ++$index ) : ?>
                                        <?php $rating_star_class = 'product-rating-star product-rating-star__' . self::get_star_class( $mapped->rating, $index ); ?>
                                        <div class="<?php echo esc_attr( $rating_star_class ); ?>"></div>
                                    <?php endfor; ?>
                                    <span class="product-reviews-count">(<?php echo (int) $mapped->reviews_count; ?>)</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a class="button" href="<?php echo esc_url( self::link_marketplace($addon->link) ); ?>">
                            <?php esc_html_e( 'View details', 'woocommerce' ); ?>
                        </a>
                    </div>
                </li>
                <?php
            }
            ?>
            </ul>
            <script>
                if( jQuery('.berocket_section_wc_addons').length && jQuery('.wc_addons_wrap .search-form').length ) {
                    jQuery('.berocket_section_wc_addons').addClass('products').show();
                    jQuery('.wc_addons_wrap .search-form').after(jQuery('.berocket_section_wc_addons'));
                }
                if( jQuery('.berocket_section_wc_addons_new').length && jQuery('.marketplace-content-wrapper').length ) {
                    jQuery('.berocket_section_wc_addons_new').show();
                    if( jQuery('.marketplace-content-wrapper .products').length > 0 ) {
                        jQuery('.marketplace-content-wrapper .products').prepend(jQuery('.berocket_section_wc_addons_new .product'));
                    } else {
                        jQuery('.berocket_section_wc_addons_new').addClass('products');
                        jQuery('.marketplace-content-wrapper').prepend(jQuery('.berocket_section_wc_addons_new'));
                    }
                }
            </script>
            <?php
        }
        public static function link_marketplace($link) {
            $link = add_query_arg( array(
                'utm_source'    => 'free_plugin',
                'utm_medium'    => 'marketplace',
                'utm_campaign'  => 'marketplace',
            ), $link );
            return $link;
        }
        public static function hide_key($key) {
            if( ! empty($key) ) {
                $part = (int)(strlen($key) / 3);
                $replace = strlen($key) - $part;
                $key = substr($key, $replace);
                for($i = 0; $i < $replace; $i++) {
                    $key = '*'.$key;
                }
            }
            return $key;
        }
        public static function restore_keys($options) {
            $options_old = self::get_options();
            if( ! empty($options['account_key']) && strpos($options['account_key'], '**') !== false && ! empty($options_old['account_key']) ) {
                $options['account_key'] = $options_old['account_key'];
            }
            if( ! empty($options['plugin_key']) && is_array($options['plugin_key']) ) {
                foreach($options['plugin_key'] as $plugin_id => $plugin_key) {
                    if( ! empty($plugin_key) && strpos($plugin_key, '**') !== false && ! empty($options_old['plugin_key']) && ! empty($options_old['plugin_key'][$plugin_id]) ) {
                        $options['plugin_key'][$plugin_id] = $options_old['plugin_key'][$plugin_id];
                    }
                }
            }
            return $options;
        }
        public static function get_plugin_data($plugin_id) {
            $data = array();
            if( is_array(self::$plugin_info) ) {
                foreach(self::$plugin_info as $plugin) {
                    if( $plugin['id'] == $plugin_id ) {
                        return $plugin;
                    }
                }
            }
            return $data;
        }
    }

    BeRocket_updater::init();
    add_action( 'plugins_loaded', array( 'BeRocket_updater', 'run' ), 999 );
}
