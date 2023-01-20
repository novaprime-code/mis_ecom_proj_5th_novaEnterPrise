<?php
if( ! class_exists('BeRocket_framework_addons') ) {
    class BeRocket_framework_addons {
        public $info;
        public $values;
        public $options;
        public $hook_name;
        function __construct($info, $values, $options) {
            include_once('addon_lib.php');
            $this->info = $info;
            $this->values = $values;
            $this->options = $options;
            $this->hook_name = $info['plugin_name'];
            add_filter('berocket_addons_active_'.$this->hook_name, array($this, 'active_addons'));
            add_filter('brfr_'.$this->hook_name.'_addons', array($this, 'section'), 10, 4);
            add_filter('berocket_addons_info_'.$this->hook_name, array($this, 'sort_deprecated_addons'), 9001, 1);
            add_filter('berocket_addons_info_'.$this->hook_name, array($this, 'sort_paid_addons'), 9000, 1);
            $this->load_addons();

            new BeRocket_framework_libraries(array('tooltip'), $info, $values, $options);

            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

        function admin_init() {
            add_filter( 'BeRocket_style_addon_library_additional_html_' . $this->hook_name, array(
                $this,
                'paid_only_sign'
            ), 10, 2 );
        }
        function load_addons() {
            $addons_exist = $this->get_addons();
            if( ! empty($this->options['addons']) && is_array($this->options['addons']) ) {
                foreach($this->options['addons'] as $addon) {
                    if( ! empty($addon) && in_array($addon, $addons_exist) && file_exists($this->info['plugin_dir']. DIRECTORY_SEPARATOR . 'addons'.$addon) ) {
                        include_once($this->info['plugin_dir']. DIRECTORY_SEPARATOR . 'addons'.$addon);
                    }
                }
            }
        }
        function get_addons() {
            $addon_files = array();
            if( is_dir($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR) ) {
                foreach (glob($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.'*.php') as $filename) {
                    $addon_files[] = str_replace($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons', '', $filename);
                }
                foreach(glob($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR ) as $path) {
                    $dir_name = basename($path);
                    $filename = $path. DIRECTORY_SEPARATOR .$dir_name.'.php';
                    if( file_exists($filename) ) {
                        $addon_files[] = str_replace($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons', '', $filename);
                    }
                }
            }
            return $addon_files;
        }
        function get_addons_info() {
            $addons = $this->get_addons();
            foreach($addons as $addon) {
                if( file_exists($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$addon) ) {
                    include_once($this->info['plugin_dir'].DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$addon);
                }
            }
            $addon_info = apply_filters('berocket_addons_info_'.$this->hook_name, array());
            return $addon_info;
        }
        function sort_paid_addons($addon_info) {
            $plugin_version_capability = apply_filters( 'brfr_get_plugin_version_capability_' . $this->hook_name, 0 );
            if ( empty( $plugin_version_capability ) || $plugin_version_capability < 10 ) {
                $sorted_addon_info = array();
                foreach ( $addon_info as $addon_i => $addon ) {
                    if ( ! empty( $addon[ 'paid' ] ) ) {
                        $sorted_addon_info[] = $addon;
                        unset( $addon_info[ $addon_i ] );
                    }
                }

                $addon_info = array_merge( $addon_info, $sorted_addon_info );
            }
            return $addon_info;
        }
        function sort_deprecated_addons($addon_info) {
            $sorted_addon_info = array();
            foreach($addon_info as $addon_i => $addon) {
                if( ! empty($addon['deprecated']) ) {
                    $sorted_addon_info[] = $addon;
                    unset($addon_info[$addon_i]);
                }
            }
            $addon_info = array_merge($addon_info, $sorted_addon_info);
            $addon_info = array_values($addon_info);
            return $addon_info;
        }
        function active_addons($addons = array()) {
            if( ! empty($this->options['addons']) && is_array($this->options['addons']) ) {
                $addons = array_merge($addons, $this->options['addons']);
            }
            return $addons;
        }
        function section($html, $item, $options, $settings_name) {
            $addons = $this->get_addons();
            $addons_info = $this->get_addons_info();
            $html .= '<td colspan="2" class="berocket_addons_list">';
            $elements = array(
                'active'    => array(
                    'title' => __( 'Active Addons', 'BeRocket_domain' ),
                    'html'  => array()
                ),
                'inactive'  => array(
                    'title' => __( 'Inactive Addons', 'BeRocket_domain' ),
                    'html'  => array()
                )
            );
            foreach($addons_info as $addon_i => $addon_info) {
                $checked = isset($options['addons']) && is_array($options['addons']) && in_array($addon_info['addon_file'], $options['addons']);
                $html_array = array(
                    'open_label'            => '<label class="berocket_addon_label" id="berocket_addon_label_'.$addon_i.'">',
                    'input'                 => '<input autocomplete="off" class="berocket_addon_is_active" name="'.$settings_name.'[addons][]" type="checkbox" value="'.$addon_info['addon_file'].'"'.($checked ? ' checked' : '').'>',
                    'open_addon_block'      => '<span class="berocket_addon_block">',
                    'active'                => '<span class="berocket_addon_active"><i class="fa fa-check"></i></span>',
                    'image'                 => '<img src="'.$addon_info['image'].'">',
                    'addon_name'            => '<span class="berocket_addon_name">'.$addon_info['addon_name'].'</span>',
                    'close_addon_block'     => '</span>',
                    'close_label'           => '</label>',
                );
                $html_array = apply_filters( 'BeRocket_style_addon_library_additional_html_' . $this->hook_name, $html_array, $addon_info, $item, $options, $settings_name );
                $elements[($checked ? 'active' : 'inactive')]['html'][$addon_info['addon_file']] = implode($html_array);
                if( ! empty($addon_info['tooltip']) ) {
                    BeRocket_tooltip_display::add_tooltip(array('appendTo'  => 'document.body', 'arrow' => true, 'interactive' => true, 'placement' => 'top'), $addon_info['tooltip'], '#berocket_addon_label_'.$addon_i);
                }
            }
            foreach($elements as $element) {
                if( count($element['html']) ) {
                    $html .= '<div>';
                    if( ! empty($element['title']) ) {
                        $html .= '<h3>'.$element['title'].'</h3>';
                    }
                    $html .= implode($element['html']);
                    $html .= '</div>';
                }
            }
            $html .= '</td>';
            return $html;
        }

        function paid_only_sign( $html_array, $addon_info ) {
            $plugin_version_capability = apply_filters( 'brfr_get_plugin_version_capability_' . $this->hook_name, 0 );

            if ( ! empty( $addon_info[ 'paid' ] ) && ( empty( $plugin_version_capability ) || $plugin_version_capability < 10 ) ) {
                $meta_data = '?utm_source=free_plugin&utm_medium=plugins&utm_campaign='.$this->info['plugin_name'];
                $html = '<i class="berocket_addon_paid_sign fa fa-lock"></i>';
                $html .= '<div class="berocket_addon_paid_get"><a target="_blank" href="https://berocket.com/' . $this->values[ 'premium_slug' ] . $meta_data . '"><span>
                ' . __( 'Go Premium', 'BeRocket_domain' ) . '
                </span></a></div>';
                $html_array = berocket_insert_to_array($html_array, 'close_addon_block', array('paid_only' => $html));
            }

            return $html_array;
        }
    }
}
