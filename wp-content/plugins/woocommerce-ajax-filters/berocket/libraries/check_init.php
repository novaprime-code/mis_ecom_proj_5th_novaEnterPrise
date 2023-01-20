<?php
if( ! class_exists('BeRocket_framework_check_init_lib') ) {
    class BeRocket_framework_check_init_lib {
        public $check_array = array();
        public $notices = array();
        public $check_result = array('exist' => false, 'result' => true);
        function __construct($check_array = array()) {
            $this->check_array = $check_array;
            add_filter( 'berocket_display_additional_notices', array(
                $this,
                'framework_notice'
            ) );
        }
        function check() {
            $result = true;
            if( ! empty($this->check_result['exist']) ) {
                return $this->check_result['result'];
            }
            if( is_array($this->check_array) && count($this->check_array) ) {
                $result = false;
                foreach($this->check_array as $check_or) {
                    $break = false;
                    if( isset($check_or['check']) ) {
                        $check_or = $this->check_array;
                        $break = true;
                    }

                    if( is_array($check_or) ) {
                        $result_and = true;
                        foreach($check_or as $check_and) {
                            if( isset($check_and['check']) ) {
                                if( method_exists($this, 'check_'.$check_and['check']) ) {
                                    $result_and = $result_and && $this->{'check_'.$check_and['check']}( (isset($check_and['data']) ? $check_and['data'] : array()) );
                                }
                                $result_and = apply_filters('BeRocket_framework_check_init_'.$check_and['check'], $result_and, $check_and);
                            }
                        }
                        $result = $result_and;
                        if( $result_and ) {
                            $break = true;
                        }
                    }

                    if( $break ) break;
                }
            }
            $this->check_result = array('exist' => true, 'result' => $result);
            return $result;
        }
        function check_woocommerce_version($data = array()) {
            $result = ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) );
            $result = $result && $this->version_compare(br_get_woocommerce_version(), $data);
            $this->show_notice($result, $data);
            return $result;
        }
        function check_framework_version($data = array()) {
            $framework_version = ( ( ! class_exists('BeRocket_Framework') || empty(BeRocket_Framework::$framework_version) ) ? '0' : BeRocket_Framework::$framework_version );
            $result = $this->version_compare($framework_version, $data);
            $this->show_notice($result, $data);
            return $result;
        }
        function check_wordpress_version($data = array()) {
            global $wp_version;
            $result = $this->version_compare($wp_version, $data);
            $this->show_notice($result, $data);
            return $result;
        }
        function version_compare($version, $data) {
            if( ! empty($data['version']) && ! empty($data['operator']) ) {
                return version_compare($version, $data['version'], $data['operator']);
            } else {
                return true;
            }
        }
        function show_notice($result, $data) {
            if( ! $result && ! empty($data['notice']) ) {
                $this->notices[] = array(
                    'start'         => 0,
                    'end'           => 0,
                    'name'          => 'framework_init_check',
                    'html'          => '<strong>'.$data['notice'].'</strong>',
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
            }
        }
        function framework_notice($notices) {
            $notices = array_merge($this->notices, $notices);
            return $notices;
        }
    }
}
