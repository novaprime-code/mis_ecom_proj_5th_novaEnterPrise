<?php 

if (!class_exists('wcmamtx_add_frontend_class')) {

  class wcmamtx_add_frontend_class {
    
    public function __construct() {
    
    
      add_action( 'wp_enqueue_scripts', array( $this, 'wcmamtx_load_assets' ) );
      add_action( 'woocommerce_account_menu_items', array($this, 'wcmamtx_rename_my_account_menu_items'), 100, 1);
      add_action( 'woocommerce_locate_template', array($this,'wcmamtx_override_default_navigation_template'), 10, 3 );
      
      
      add_action( 'init', array($this,'wcmamtx_add_custom_endpoint_page') );

      add_action( 'wp_loaded', array($this,'wcmamtx_flush_rewrite_rules') );
      add_action( 'parse_request', array($this,'wcmamtx_my_account_default_redirect'), 10, 1);
      add_filter( 'woocommerce_get_endpoint_url', array( $this, 'wcmamtx_link_url_redirect' ), 10, 4 );

    }

    public function wcmamtx_flush_rewrite_rules() {
        flush_rewrite_rules();
    }


    /**
     * Get endpoint URL.
     *
     * Gets the URL for an endpoint, which varies depending on permalink settings.
     *
     * @param  string $endpoint  Endpoint slug.
     * @param  string $value     Query param value.
     * @param  string $permalink Permalink.
     *
     * @return string
     */
    public function wcmamtx_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
        if ( ! $permalink ) {
            $permalink = get_permalink();
        }

        // Map endpoint to options.
        
        $query_vars = WC()->query->get_query_vars();
        $endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;
        $value      = ( get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ) === $endpoint ) ? wc_edit_address_i18n( $value ) : $value;

        if ( get_option( 'permalink_structure' ) ) {
            if ( strstr( $permalink, '?' ) ) {
                $query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
                $permalink    = current( explode( '?', $permalink ) );
            } else {
                $query_string = '';
            }
            $url = trailingslashit( $permalink );

            if ( $value ) {
                $url .= trailingslashit( $endpoint ) . user_trailingslashit( $value );
            } else {
                $url .= user_trailingslashit( $endpoint );
            }

            $url .= $query_string;
        } else {
            $url = add_query_arg( $endpoint, $value, $permalink );
        }

        return apply_filters( 'woocommerce_get_endpoint_url', $url, $endpoint, $value, $permalink );
    }

    public function wcmamtx_my_account_default_redirect() {
        global $wp;
        $plugin_options = get_option('wcmamtx_plugin_options');
        $default_tab    = isset($plugin_options['default_tab']) ? $plugin_options['default_tab'] : "dashboard";

        

         


        if (is_user_logged_in() && $wp->request === 'my-account' && ($default_tab != "dashboard")) {     
            $redirect_url = wc_get_account_endpoint_url($default_tab);
            
            wp_redirect($redirect_url);

            exit;
        }
    }


    public function wcmamtx_add_custom_endpoint_page() {
        $wcmamtx_tabs = get_option('wcmamtx_advanced_settings');

        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        if (!is_array($wcmamtx_tabs)) {

            return;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return;
        } 


        foreach ($wcmamtx_tabs as $key=>$value) {

            if (!preg_match('/\b'.$key.'\b/', $core_fields )) {

                if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                    $new_key = $value['endpoint_key'];
                } else {
                    $new_key = $key;
                }

                if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "endpoint") ) {
                    add_rewrite_endpoint( $new_key, EP_ROOT | EP_PAGES );
                }
            }

        }

        $this->wcmamtx_core_endpoint_contents();

        
    }


    public function wcmamtx_override_default_navigation_template( $template, $template_name, $template_path ) {
         
        if ( strstr($template, 'navigation.php')) {
            $template = wcmamtx_plugin_path() . '/templates/myaccount/navigation.php';
        }
        
        return $template;
    }


    


    public function wcmamtx_load_assets() {

        $wcmamtx_locals = array();

        if (is_account_page()) {

            wp_enqueue_script( 'wcmamtxfrontend', ''.wcmamtx_PLUGIN_URL.'assets/js/frontend.js',array( 'jquery'), false, true);
   
            wp_enqueue_style( 'wcmamtx-frontend', ''.wcmamtx_PLUGIN_URL.'assets/css/frontend.css' );
            wp_enqueue_style( 'wcmamtx-font-awesome', ''.wcmamtx_PLUGIN_URL.'assets/css/all.min.css' );
            wp_localize_script( 'wcmamtxfrontend', 'wcmamtxfrontend', $wcmamtx_locals );


        } 
        
   
    }


    public function wcmamtx_rename_my_account_menu_items($items) {

        $wcmamtx_tabs = get_option('wcmamtx_advanced_settings');

        $core_fields_array =  array(
                         'dashboard'=>'dashboard',
                         'orders'=>'orders',
                         'downloads'=>'downloads',
                         'edit-address'=>'edit-address',
                         'edit-account'=>'edit-account',
                         'customer-logout'=>'customer-logout'
                      );
        

        if (!is_array($wcmamtx_tabs)) {
            return $items;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return $items;
        } else {
            $new_ordered_array = $this->wcmamtx_reoder_array($wcmamtx_tabs,$items);

        }

        foreach ($items as $ikey=>$ivalue) {
            if (!array_key_exists($ikey, $new_ordered_array) && !array_key_exists($ikey, $core_fields_array)) {
                $new_ordered_array[$ikey] = $ivalue;           

            }
        }

        

        return $new_ordered_array;
    }

    public function wcmamtx_reoder_array($wcmamtx_tabs,$items) {
        
        $ordered = array();
        
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        $this->endpoint_content = '';



        foreach ($wcmamtx_tabs as $key=>$value) {
            
                if (!preg_match('/\b'.$key.'\b/', $core_fields ) && (isset($value['endpoint_key']))) {
                    $new_key = $value['endpoint_key'];

                } else {
                    $new_key = $key;
                }




                if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
                    $new_value = $value['endpoint_name'];
                } else {
                    $new_value = $value;
                }


                if (isset($value['visibleto']) && ($value['visibleto'] == "specific")) {
                    
                    $allowedroles  = isset($value['roles']) ? $value['roles'] : "";

                    $is_visible = $this->wcmamtx_check_role_visibility($allowedroles);
                
                } else {

                    $is_visible = 'yes';
                }


                if (preg_match('/\b'.$key.'\b/', $core_fields )) {

                    if (isset($value['show'])) {

                        if ($value['show'] == "yes") {
                            
                            
                            if ($is_visible == 'yes') { 
                                
                                $ordered[$new_key] = $new_value;
                            }
                            
                        
                        }

                    } else {

                        

                        if ($is_visible == 'yes') {

                            $ordered[$new_key] = $new_value;
                        }
                    }

                } else {



                    if ($is_visible == 'yes') {
                        $ordered[$new_key] = $new_value;
                    }

                    if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                        $new_key = $value['endpoint_key'];
                    }

                    /*

                    if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "endpoint") ) {

                        
                        
                        $this->endpoint_key      = $new_key;

                        add_filter( 'query_vars', array( $this, 'wcmamtx_do_query_vars' ), 0 );

                        if ($content != '') {
                            add_action( 'woocommerce_account_'.$new_key.'_endpoint', function() use ( $content ) {
                            
                                 echo apply_filters('the_content',$content);
                            });
                        }
                        

                    } 
                    */

                }
                      
        }

        return $ordered;
    }


    public function wcmamtx_core_endpoint_contents() {

        

        $wcmamtx_tabs      = get_option('wcmamtx_advanced_settings');
        

        if (!is_array($wcmamtx_tabs)) {
            return;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return;
        } else {
            
            $this->extra_content_foreach($wcmamtx_tabs);
        }


    }

    public function extra_content_foreach($wcmamtx_tabs) {
        $core_content_fields       = 'downloads,edit-address,edit-account';
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        $content  = '';
        $content_settings = 'after';

        foreach ($wcmamtx_tabs as $key=>$value) {

            if (preg_match('/\b'.$key.'\b/', $core_content_fields )) {

                $content           = isset($value['content']) ? $value['content'] : "";
                $content_settings  = isset($value['content_settings']) ? $value['content_settings'] : "after";

                switch($key) {
                    case "edit-address":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_edit_account_address_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_edit_account_address_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;

                    case "downloads":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_account_downloads', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_account_downloads', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;

                    case "edit-account":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_edit_account_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_edit_account_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;
                }

            } elseif ((!preg_match('/\b'.$key.'\b/', $core_fields )) && (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "endpoint") )) {

                $content            = isset($value['content']) ? $value['content'] : "";
                $this->endpoint_key = $key;

                add_filter( 'query_vars', array( $this, 'wcmamtx_do_query_vars' ), 0 );

                $endkey             = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;

                if ($content != '') {
                    add_action( 'woocommerce_account_'.$endkey.'_endpoint', function() use ( $content ) {

                       echo apply_filters('the_content',$content);
                   });
                }

            }
        }
    }




    public function wcmamtx_do_query_vars( $vars ) {
        $vars[] = $this->endpoint_key;

        return $vars;
    }

    public function wcmamtx_link_url_redirect($url, $endpoint, $value, $permalink) {

        $wcmamtx_tabs = get_option('wcmamtx_advanced_settings');
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';


        if (!is_array($wcmamtx_tabs)) {

            return;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return;
        } 


        foreach ($wcmamtx_tabs as $key=>$value) {

            if (!preg_match('/\b'.$key.'\b/', $core_fields )) {

                if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                    $new_key = $value['endpoint_key'];
                } else {
                    $new_key = $key;
                }

                if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "link") ) {

                    $endpoint_url  = isset($value['link_inputtarget']) ? $value['link_inputtarget'] : "#";

                    if ( $endpoint == $new_key ) {


                        $url = $endpoint_url;

                    }
                }
            }

        }

        


        return $url;
    }


    public function wcmamtx_check_role_visibility($allowedroles) {
        $role_status       = 'no';

        if ($allowedroles == '') {
            $role_status       = 'no';
            return $role_status; 

        }

        if (isset($allowedroles) && is_array($allowedroles) && (!empty($allowedroles))) {
            if ( ! is_user_logged_in() ) {
                $role_status       = 'no';
                return $role_status; 
            }

            $allowedauthors = '';

            foreach ($allowedroles as $role) {
               $allowedauthors.=''.$role.',';
           }

           $allowedauthors=substr_replace($allowedauthors, "", -1);

           global $current_user;
           $user_roles = $current_user->roles;
           $user_role = array_shift($user_roles);



            if (preg_match('/\b'.$user_role.'\b/', $allowedauthors )) {
                $role_status       = 'yes';
                return $role_status;
            }

        }

        if (empty($allowedroles) && ( ! is_user_logged_in() )) {
            $role_status       = 'yes';
            return $role_status;
        }



        return $role_status; 
    }


   }
}

new wcmamtx_add_frontend_class();

?>