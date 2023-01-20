<?php
if ( ! class_exists('BeRocket_framework_templates') ) {
    class BeRocket_framework_templates {
        public $info;
        public $values;
        public $options;
        public $hook_name;

        function __construct( $info, $values, $options ) {
            include_once( 'template_lib.php' );
            $this->info      = $info;
            $this->values    = $values;
            $this->options   = $options;
            $this->hook_name = $info[ 'plugin_name' ];

            add_filter( 'berocket_templates_active_' . $this->hook_name, array( $this, 'active_template' ) );
            add_filter( 'brfr_' . $this->hook_name . '_templates', array( $this, 'section' ), 10, 4 );
            add_filter( 'berocket_templates_info_' . $this->hook_name, array( $this, 'sort_paid_templates' ), 9000, 1 );

            $this->load_template();

            new BeRocket_framework_libraries( array( 'tooltip' ), $info, $values, $options );

            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

        function admin_init() {
            add_filter( 'BeRocket_style_template_library_additional_html_' . $this->hook_name, array(
                $this,
                'paid_only_sign'
            ), 10, 2 );
        }

        function load_template() {
            if ( ! empty( $this->options[ 'template' ] ) ) {
                if( file_exists($this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . $this->options[ 'template' ]) ) {
                    include_once( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . $this->options[ 'template' ] );
                }
            }
        }

        function get_templates() {
            $template_files = array();
            if ( is_dir( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR ) ) {
                foreach ( glob( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . DIRECTORY_SEPARATOR . '*.php' ) as $filename ) {
                    $template_files[] = str_replace( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates', '', $filename );
                }
                foreach ( glob( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR ) as $path ) {
                    $dir_name = basename( $path );
                    $filename = $path . DIRECTORY_SEPARATOR . $dir_name . '.php';
                    if ( file_exists( $filename ) ) {
                        $template_files[] = str_replace( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates', '', $filename );
                    }
                }
            }

            return $template_files;
        }

        function get_active_template_info( $template_file ) {
            $template_info = apply_filters( 'berocket_selected_templates_info_' . $this->hook_name, array() );

            return ( isset( $template_info[ $template_file ] ) ? $template_info[ $template_file ] : false );
        }

        function get_templates_info() {
            $templates = $this->get_templates();
            foreach ( $templates as $template ) {
                if( file_exists($this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . $template) ) {
                    include_once( $this->info[ 'plugin_dir' ] . DIRECTORY_SEPARATOR . 'style_templates' . $template );
                }
            }
            $template_info = apply_filters( 'berocket_templates_info_' . $this->hook_name, array() );

            return $template_info;
        }

        function sort_paid_templates( $template_info ) {
            $sorted_template_info = array();
            foreach ( $template_info as $template_i => $template ) {
                if ( ! empty( $template[ 'paid' ] ) ) {
                    $sorted_template_info[] = $template;
                    unset( $template_info[ $template_i ] );
                }
            }

            $plugin_version_capability = apply_filters( 'brfr_get_plugin_version_capability_' . $this->hook_name, 0 );
            if ( empty( $plugin_version_capability ) || $plugin_version_capability < 10 ) {
                $template_info = array_merge( $template_info, $sorted_template_info );
            } else {
                $template_info = array_merge( $sorted_template_info, $template_info );
            }
            $template_info = array_values( $template_info );

            return $template_info;
        }

        function active_template( $template = '' ) {
            if ( ! empty( $this->options[ 'template' ] ) ) {
                $template = $this->options[ 'template' ];
            }

            return $template;
        }

        function section( $html, $item, $options, $settings_name ) {
            $templates      = $this->get_templates();
            $templates_info = $this->get_templates_info();
            $html .= '<td colspan="2" class="berocket_templates_list">';
            $elements = array(
                'main' => array(
                    'title' => '',
                    'html'  => array()
                ),
            );

            foreach ( $templates_info as $template_i => $template_info ) {
                $checked = isset( $options[ 'template' ] ) && $template_info[ 'template_file' ] == $options[ 'template' ];
                $html_array = array(
                    'open_label'    => '<label class="berocket_template_label" id="berocket_template_label_' . $template_i . '">',
                    'input'         => '<input autocomplete="off" class="berocket_template_is_active" name="' . $settings_name . '[template]" type="radio" value="' . $template_info[ 'template_file' ] . '"' . ( $checked ? ' checked' : '' ) . '>',
                    'open_template_block'   => '<span class="berocket_template_block">',
                    'active'                => '<span class="berocket_template_active"><i class="fa fa-check"></i></span>',
                    'image'                 => '<img src="' . $template_info[ 'image' ] . '">',
                    'template_name'         => '<span class="berocket_template_name">' . $template_info[ 'template_name' ] . '</span>',
                    'close_template_block'  => '</span>',
                    'close_label'           => '</label>',
                );
                $html_array = apply_filters( 'BeRocket_style_template_library_additional_html_' . $this->hook_name, $html_array, $template_info, $item, $options, $settings_name );
                $elements[ 'main' ][ 'html' ][ $template_info[ 'template_file' ] ] = implode($html_array);
                if ( ! empty( $template_info[ 'tooltip' ] ) ) {
                    BeRocket_tooltip_display::add_tooltip( array(
                        'appendTo'    => 'document.body',
                        'arrow'       => true,
                        'interactive' => true,
                        'placement'   => 'top'
                    ), $template_info[ 'tooltip' ], '#berocket_template_label_' . $template_i );
                }
            }

            foreach ( $elements as $element ) {
                if ( count( $element[ 'html' ] ) ) {
                    $html .= '<div>';
                    if ( ! empty( $element[ 'title' ] ) ) {
                        $html .= '<h3>' . $element[ 'title' ] . '</h3>';
                    }
                    $html .= implode( $element[ 'html' ] );
                    $html .= '</div>';
                }
            }
            $html .= '</td>';

            return $html;
        }

        function paid_only_sign( $html_array, $template_info ) {
            $plugin_version_capability = apply_filters( 'brfr_get_plugin_version_capability_' . $this->hook_name, 0 );

            if ( ! empty( $template_info[ 'paid' ] ) && ( empty( $plugin_version_capability ) || $plugin_version_capability < 10 ) ) {
                $meta_data = '?utm_source=free_plugin&utm_medium=plugins&utm_campaign='.$this->info['plugin_name'];
                $html = '<i class="berocket_template_paid_sign fa fa-lock"></i>';
                $html .= '<div class="berocket_template_paid_get"><a target="_blank" href="https://berocket.com/' . $this->values[ 'premium_slug' ] . $meta_data . '"><span>
                <i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>
                ' . __( 'Go Premium', 'BeRocket_domain' ) . '
                <i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i>
                </span></a></div>';
                $html_array = berocket_insert_to_array($html_array, 'close_template_block', array('paid_only' => $html));
            }

            return $html_array;
        }
    }
}
