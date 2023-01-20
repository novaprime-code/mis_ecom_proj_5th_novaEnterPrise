<?php
if( ! class_exists('BeRocket_framework_feature_tab') ) {
    class BeRocket_framework_feature_tab {
        public $info;
        public $values;
        public $options;
        public $hook_name;
        public $FILE;
        function __construct($info, $values, $options) {
            $this->info = $info;
            $this->values = $values;
            $this->options = $options;
            if( ! empty($info['feature_template']) ) {
                $this->FILE = $info['feature_template'];
            } else {
                $this->FILE = $info['plugin_dir'] . "/templates/features.php";
            }
            if( file_exists($this->FILE) ) {
                add_action( 'admin_menu', array( $this, 'admin_menu' ) );
                add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post'), 5 );
            }
        }
        public function admin_menu() {
            register_setting($this->values[ 'option_page' ].'_upgrade', $this->values[ 'settings_name' ].'_upgrade');
            if ( method_exists( 'BeRocket_updater', 'get_plugin_count' ) ) {
                add_submenu_page(
                    'berocket_account',
                    __( 'Upgrade to Premium ', 'BeRocket_domain' ) . ' ' . $this->info[ 'norm_name' ] ,
                    __( 'Upgrade', 'BeRocket_domain' ),
                    'manage_berocket',
                    $this->values[ 'option_page' ].'_upgrade',
                    array( $this, 'option_form' )
                );

                return false;
            }

            return true;
        }
        public function option_form() {
            echo '<div class="wrap">';
            echo '<h3>' . __( 'Premium', 'BeRocket_domain' ) . ' ' . $this->info[ 'norm_name' ] . ' ' . __( 'Features', 'BeRocket_domain' ).'</h3>';
            include $this->FILE;
            echo '</div>';
        }
        public function menu_order_custom_post($compatibility) {
            $compatibility[$this->values[ 'option_page' ].'_upgrade'] = $this->values[ 'option_page' ];
            return $compatibility;
        }
    }
}
