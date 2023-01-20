<?php
if (!class_exists('wcmamtx_add_settings_page_class')) {

class wcmamtx_add_settings_page_class {
	
	

	private $wcmamtx_plugin_options_key   = 'wcmamtx_plugin_options';
	private $wcmamtx_plugin_login_key     = 'wcmamtx_plugin_login';
	private $wcmamtx_notices_settings_page = 'wcmamtx_advanced_settings';
	private $wcmamtx_plugin_settings_tab   = array();
	

	
	public function __construct() {
		add_action( 'init', array( $this, 'load_settings' ) );
		add_action( 'admin_init', array( $this, 'wcmamtx_register_settings_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) ,100);
		add_action( 'admin_enqueue_scripts', array($this, 'wcmamtx_register_admin_scripts'));
		add_action( 'admin_enqueue_scripts', array($this, 'wcmamtx_load_admin_menu_style'));
        add_action( 'wp_ajax_restore_my_account_tabs', array( $this, 'restore_my_account_tabs' ) );
        add_action( 'wp_ajax_wcmamtxadmin_add_new_value', array( $this, 'wcmamtxadmin_add_new_value' ) );


		
	}


	public function wcmamtx_load_admin_menu_style() {

	    wp_enqueue_style( 'woomatrix_admin_menu_css', ''.wcmamtx_PLUGIN_URL.'assets/css/admin_menu.css' );
	    wp_enqueue_script( 'woomatrix_admin_menu_js', ''.wcmamtx_PLUGIN_URL.'assets/js/admin_menu.js' );

	}



	public function wcmamtxadmin_add_new_value() {

		/* First, check nonce */
        check_ajax_referer( 'wcmamtx_nonce', 'security' );
        check_ajax_referer( 'wcmamtx_nonce_hidden', 'nonce' );
		
		if (isset($_POST['row_type'])) {
			$row_type     = sanitize_text_field($_POST['row_type']);
		}
		
        if (isset($_POST['new_row'])) {
            $new_name      = sanitize_text_field($_POST['new_row']);
        }



        $random_number  = mt_rand(100000, 999999);
        $random_number2 = mt_rand(100000, 999999);



        switch($row_type) {
        	case "endpoint":
        	    $new_key   = 'custom-endpoint-'.$random_number.'';
        	break;

        	case "link":
        	    $new_key   = 'custom-link-'.$random_number.'';
            break;

        	case "group":
        	    $new_key   = 'custom-group-'.$random_number.'';
            break;

        	default:
        	    $new_key   = 'custom-endpoint-'.$random_number.'';
            break;
        }


        $new_row_values    = array();

        $advancedsettings  = $this->advanced_settings;

        if (!isset($advancedsettings) || (sizeof($advancedsettings) == 1)) {
            $tabs  = wc_get_account_menu_items();

            foreach ($tabs as $key=>$value) {
            
                $new_row_values[$key]['endpoint_key']        = $key;
                $new_row_values[$key]['endpoint_name']       = $value;
                $new_row_values[$key]['wcmamtx_type']        = 'endpoint';
                $new_row_values[$key]['parent']              = 'none';

                $new_row_values[$key]['class']               = isset($value['class']) ? $value['class'] : "";

                
                $new_row_values[$key]['visibleto']           = isset($value['visibleto']) ? $value['visibleto'] : "all";
                $new_row_values[$key]['roles']               = isset($value['roles']) ? $value['roles'] : array();
                $new_row_values[$key]['icon_source']         = isset($value['icon_source']) ? $value['icon_source'] : "default";
                $new_row_values[$key]['icon']                = isset($value['icon']) ? $value['icon'] : "";
                $new_row_values[$key]['content']             = isset($value['content']) ? $value['content'] : "";
                $new_row_values[$key]['show']                = isset($value['show']) ? $value['show'] : "yes";


            }

        } else {
        	

        	foreach ($advancedsettings as $key2=>$value2) {
            
                $new_row_values[$key2]['endpoint_key']        = $key2;
                $new_row_values[$key2]['endpoint_name']       = $value2['endpoint_name'];
                $new_row_values[$key2]['wcmamtx_type']        = $value2['wcmamtx_type'];
                $new_row_values[$key2]['parent']              = $value2['parent'];
                
                $new_row_values[$key2]['class']               = isset($value2['class']) ? $value2['class'] : "";
                $new_row_values[$key2]['visibleto']           = isset($value2['visibleto']) ? $value2['visibleto'] : "all";
                $new_row_values[$key2]['roles']               = isset($value2['roles']) ? $value2['roles'] : array();
                $new_row_values[$key2]['icon_source']         = isset($value2['icon_source']) ? $value2['icon_source'] : "default";
                $new_row_values[$key2]['icon']                = isset($value2['icon']) ? $value2['icon'] : "";
                $new_row_values[$key2]['show']                = isset($value2['show']) ? $value2['show'] : "yes";
                

                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "link")) {
                	$new_row_values[$key2]['link_inputtarget']              = $value2['link_inputtarget'];
                	$new_row_values[$key2]['link_targetblank']              = $value2['link_targetblank'];
                }


                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "endpoint")) {
                    $new_row_values[$key2]['content']              = isset($value2['content']) ? $value2['content'] : "";
                }



                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "group")) {

                	$new_row_values[$key2]['group_open_default']   = isset($value2['group_open_default']) ? $value2['group_open_default'] : "no";

                }
                
            

            }

        }




        	if (isset($new_name) && ($new_name != '')) {
        	    $new_row_values[$new_key]['endpoint_key']        = $new_key;
                $new_row_values[$new_key]['endpoint_name']       = $new_name;
                $new_row_values[$new_key]['wcmamtx_type']        = $row_type;
                $new_row_values[$new_key]['parent']              = 'none';

            }

        



        

        if (($new_row_values != $advancedsettings) && !empty($new_row_values)) {
        	update_option($this->wcmamtx_notices_settings_page,$new_row_values);
        }



        die();
	}

	public function restore_my_account_tabs() {
	    if( current_user_can('editor') || current_user_can('administrator') ) {
	        delete_option( $this->wcmamtx_notices_settings_page );
        } 
	   die();
	}
	
	
	public function load_settings() {
		
		$this->advanced_settings = (array) get_option( $this->wcmamtx_notices_settings_page );
	    $this->plugin_options = (array) get_option( $this->wcmamtx_plugin_options_key );
	    $this->login_options = (array) get_option( $this->wcmamtx_plugin_login_key );

	}


	public function wcmamtx_get_posts_ajax_callback(){
 
	
	  $return          = array();
	  
      $post_type_array = array('product','product_variant');
	  // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
	  $search_results  = new WP_Query( array( 
		's'                   => sanitize_text_field($_GET['q']), // the search query
		'post_status'         => 'publish', // if you don't want drafts to be returned
		'ignore_sticky_posts' => 1,
		'post_type'           => $post_type_array
	  ) );
	  

	
	  if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();

		    $product_type = WC_Product_Factory::get_product_type($search_results->post->ID);	
			// shorten the title a little
			

			
				 $finaltitle='#'. $search_results->post->ID.'- '.$search_results->post->post_title.'';
				 $return[] = array( $search_results->post->ID, $finaltitle );
			
			
			  

			 // array( Post ID, Post Title )
		endwhile;
	  endif;
	   echo json_encode( $return );
	  die;
    }
	
	/*
	 * registers admin scripts via admin enqueue scripts
	 */
	public function wcmamtx_register_admin_scripts($hook) {
	    global $general_wcmamtxsettings_page;
			
		if ( $hook == $general_wcmamtxsettings_page )  {

		    
 
            wp_enqueue_style( 'wcmamtx_fontawesome', ''.wcmamtx_PLUGIN_URL.'assets/css/font-awesome.min.css');

            
            wp_enqueue_script( 'wcmamtx_bootstrap', ''.wcmamtx_PLUGIN_URL.'assets/js/bootstrap.min.js');
            wp_enqueue_script( 'wcmamtx_bootstrap_toggle', ''.wcmamtx_PLUGIN_URL.'assets/js/bootstrap4-toggle.min.js');
            wp_enqueue_style( 'wcmamtx_bootstrap', ''.wcmamtx_PLUGIN_URL.'assets/css/bootstrap.min.css');
            wp_enqueue_style( 'wcmamtx_bootstrap_toggle', ''.wcmamtx_PLUGIN_URL.'assets/css/bootstrap4-toggle.min.css');

		    wp_enqueue_script( 'select2', ''.wcmamtx_PLUGIN_URL.'assets/js/select2.js' );

		    wp_enqueue_script( 'wcmamtxadmin', ''.wcmamtx_PLUGIN_URL.'assets/js/admin.js',array('jquery-ui-accordion'), '1.0.0', true );
		
            wp_enqueue_script( 'wcmamtx-tageditor', ''.wcmamtx_PLUGIN_URL.'assets/js/tageditor.js');
		    wp_enqueue_style( 'wcmamtx-tageditor', ''.wcmamtx_PLUGIN_URL.'assets/css/tageditor.css');

	        wp_enqueue_style( 'jquery-ui-core', ''.wcmamtx_PLUGIN_URL.'assets/css/jquery-ui.css' );
            wp_enqueue_style( 'select2',''.wcmamtx_PLUGIN_URL.'assets/css/select2.css');
		 
		    wp_enqueue_style( 'wcmamtxadmin', ''.wcmamtx_PLUGIN_URL.'assets/css/admin.css' );


		 
		    $wcmamtx_js_array = array(
                'new_row_alert_text'   => esc_html__( 'Enter name for new endpoint' ,'customize-my-account-for-woocommerce'),
                'new_group_alert_text' => esc_html__( 'Enter name for new group' ,'customize-my-account-for-woocommerce'),
                'new_link_alert_text'  => esc_html__( 'Enter name for new link' ,'customize-my-account-for-woocommerce'),
                'group_mixing_text'    => esc_html__( 'Group can not be dropped into group' ,'customize-my-account-for-woocommerce'),
                'restorealert'         => esc_html__( 'Are you sure you want to restore to default my account tabs ? you can not undo this.' ,'customize-my-account-for-woocommerce'),
                'endpoint_remove_alert'   => esc_html__( "Are you sure you want to delete this ?" ,'customize-my-account-for-woocommerce'),
                'core_remove_alert'     => esc_html__( "this group has core endpoints. please move them before removing this group" ,'customize-my-account-for-woocommerce'),
                'dt_type'               => wcmamtx_get_version_type(),
                'pro_notice'            => esc_html__( 'This feature is available in pro version only.' ,'customize-my-account-for-woocommerce'),
                'empty_label_notice'    => esc_html__( 'Label can not be empty.' ,'customize-my-account-for-woocommerce'),
                'nonce'                 => wp_create_nonce( 'wcmamtx_nonce' ),
                'ajax_url'              => admin_url( 'admin-ajax.php' ),
                'wait_text'             => esc_html__( 'Adding....' ,'customize-my-account-for-woocommerce')
                
            );

            wp_localize_script( 'wcmamtxadmin', 'wcmamtxadmin', $wcmamtx_js_array );

        }
	}
	
	

	
	
	public function wcmamtx_register_settings_settings() {

		$this->wcmamtx_plugin_settings_tab[$this->wcmamtx_notices_settings_page] = esc_html__( 'Endpoints' ,'customize-my-account-for-woocommerce');
        $this->wcmamtx_plugin_settings_tab[$this->wcmamtx_plugin_options_key] = esc_html__( 'Settings' ,'customize-my-account-for-woocommerce');
        $this->wcmamtx_plugin_settings_tab[$this->wcmamtx_plugin_login_key] = esc_html__( 'Login and Register' ,'customize-my-account-for-woocommerce');

		

		register_setting( $this->wcmamtx_notices_settings_page, $this->wcmamtx_notices_settings_page );

		add_settings_section( 'wcmamtx_advance_section', '', '', $this->wcmamtx_notices_settings_page );

		add_settings_field( 'advanced_option', '', array( $this, 'linked_product_swatches_settings' ), $this->wcmamtx_notices_settings_page, 'wcmamtx_advance_section' );


		register_setting( $this->wcmamtx_plugin_options_key, $this->wcmamtx_plugin_options_key );

		add_settings_section( 'wcmamtx_general_section', '', '', $this->wcmamtx_plugin_options_key );

		add_settings_field( 'general_option', '', array( $this, 'wcmamtx_options_page' ), $this->wcmamtx_plugin_options_key, 'wcmamtx_general_section' );


		register_setting( $this->wcmamtx_plugin_login_key, $this->wcmamtx_plugin_login_key );

		add_settings_section( 'wcmamtx_login_section', '', '', $this->wcmamtx_plugin_login_key );

		add_settings_field( 'login_option', '', array( $this, 'wcmamtx_login_page' ), $this->wcmamtx_plugin_login_key, 'wcmamtx_login_section' );

	}



	/**
      * Recursive sanitation for an array
      * 
      * @param $array
      *
      * @return mixed
      */
	public function recursive_sanitize_text_field($array) {
		foreach ( $array as $key => $value ) {

			$value = sanitize_text_field( $value );

		}

		return $array;
	}
	

	

	

	/*
     * Linked product swatached settings
     * includes form field from forms folder
     */
	
	public function linked_product_swatches_settings() { 

	   include ('forms/settings_form.php');
		   
	}



	/*
     * Plugin options page
     * 
     */
	
	public function wcmamtx_options_page() { 

	   include ('forms/options_form.php');
		   
	}


	/**
     * Plugin login page
     * 
     */
	
	public function wcmamtx_login_page() { 

	   include ('forms/login_form.php');
		   
	}
	
	
	/*
     * Adds Admin Menu "cart notices"
     * global $general_wcmamtxsettings_page is used to include page specific scripts
     */

	public function add_admin_menus() {
	    global $general_wcmamtxsettings_page;
        
        add_menu_page(
          __( 'sysbasics', 'customize-my-account-for-woocommerce' ),
         'SysBasics',
         'manage_woocommerce',
         'sysbasics',
         array($this,'plugin_options_page'),
         ''.wcmamtx_PLUGIN_URL.'assets/images/icon.png',
         70
        );




	    

        $general_wcmamtxsettings_page = add_submenu_page( 'sysbasics', wcmamtx_PLUGIN_name , wcmamtx_PLUGIN_name , 'manage_woocommerce', esc_html__($this->wcmamtx_notices_settings_page), array($this, 'plugin_options_page'));


        add_submenu_page( 'sysbasics', 'Free Plugins' , 'Free Plugins' , 'manage_woocommerce', 'free_plugins_page', array($this, 'free_plugins_page'));


	         
	}


	public function free_plugins_page() {

         include('free_plugin_html.php');

         
	}




	public function plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : sanitize_text_field($this->wcmamtx_notices_settings_page);
		$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $tab;
        $dt_type = wcmamtx_get_version_type();
		
		?>
		<div class="wrap">
		   <?php $this->wcmamtx_options_tab_wrap(); ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>

				<div class="wcmamtx_buttons_section">
				    
				    <?php if (isset($current_tab) && ($current_tab == "wcmamtx_advanced_settings")) { ?>
				        <div class="wcmamtx_add_section_div">
				            <button type="button" href="#" data-toggle="modal" data-target="#wcmamtx_example_modal" data-etype="endpoint" id="wcmamtx_add_endpoint" class="btn btn-primary wcmamtx_add_group <?php if ($dt_type == "all") { echo 'wcmamtx_disabled'; } ?>">
				            	<span class="dashicons dashicons-insert"></span>
				            	<?php echo esc_html__( 'Add Endpont' ,'customize-my-account-for-woocommerce'); ?>
				            </button>

				            <button type="button" href="#" data-toggle="modal" data-target="#wcmamtx_example_modal" data-etype="link" id="wcmamtx_add_link" class="btn btn-primary wcmamtx_add_group">
				            	<span class="dashicons dashicons-insert"></span>
				            	<?php echo esc_html__( 'Add Link' ,'customize-my-account-for-woocommerce'); ?>
				            </button>

				            <button type="button" href="#" data-toggle="modal" data-target="#wcmamtx_example_modal" data-etype="group" id="wcmamtx_add_group" class="btn btn-primary wcmamtx_add_group <?php if ($dt_type == "all") { echo 'wcmamtx_disabled'; } ?>">
				            	<span class="dashicons dashicons-insert"></span>
				            	<?php echo esc_html__( 'Add Group' ,'customize-my-account-for-woocommerce'); ?>
				            </button>
				            
				        </div>
				        <div class="modal fade" id="wcmamtx_example_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				        	<div class="modal-dialog" role="document">
				        		<div class="modal-content">
				        			
				        			<div class="modal-body">
				        				
				        				<div class="form-group">
				        					<input type="text" class="form-control" id="wcmamtx_modal_label" placeholder="<?php echo esc_html__( 'Enter label' ,'customize-my-account-for-woocommerce'); ?>" value="">
				        					<input type="hidden" class="form-control" nonce="<?php echo wp_create_nonce( 'wcmamtx_nonce_hidden' ); ?>" id="wcmamtx_hidden_endpoint_type" placeholder="<?php echo esc_html__( 'Enter label' ,'customize-my-account-for-woocommerce'); ?>" value="">
				        				</div>
				        				<div class="alert alert-info wcmamtx_enter_label_alert" role="alert" style="display:none;"></div>
				        				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close' ,'customize-my-account-for-woocommerce'); ?></button>
				        				<button type="submit" class="btn btn-primary wcmamtx_new_end_point"><?php echo esc_html__( 'Add' ,'customize-my-account-for-woocommerce'); ?>
				        				    	
				        				</button>
				        				
				        			</div>
				        			<div class="modal-footer">
				        				
				        			</div>
				        		</div>
				        	</div>
				        </div>
				    <?php } ?>

                    <div class="wcmamtx_submit_section_div">

				        <input type="submit" name="submit" id="submit" class="btn btn-success wcmamtx_submit_button" value="<?php echo esc_html__( 'Save Changes' ,'customize-my-account-for-woocommerce'); ?>">

				        <?php if (isset($current_tab) && ($current_tab == "wcmamtx_advanced_settings")) { ?>

				            <input type="button" href="#" name="submit" id="wcmamtx_reset_tabs_button" class="btn btn-danger wcmamtx_reset_tabs_button" value="<?php echo esc_html__( 'Restore Default' ,'customize-my-account-for-woocommerce'); ?>">
                            
                            



				            
				        <?php } ?>

				        <?php if (($dt_type == "all") && (pro_url != '')) { ?>
                                  
                            	<a type="button" target="_blank" href="<?php echo pro_url; ?>" name="submit" id="wcmamtx_frontend_link" class="btn btn-primary wcmamtx_frontend_link" >
                            		<span class="dashicons dashicons-lock"></span>
                            		<?php echo esc_html__( 'Upgrade to pro' ,'customize-my-account-for-woocommerce'); ?>
                            	</a>

                        <?php } ?>

				        <a type="button" target="_blank" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" name="submit" id="wcmamtx_frontend_link" class="btn btn-primary wcmamtx_frontend_link" >
				        	    <span class="dashicons dashicons-welcome-view-site"></span>
				        	    <?php echo esc_html__( 'Frontend' ,'customize-my-account-for-woocommerce'); ?>
				        </a>

				    </div>

				    
				</div>
				
			</form>
		</div>
		<?php
	}


	
	public function wcmamtx_options_tab_wrap() {

		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : sanitize_text_field($this->wcmamtx_notices_settings_page);

        echo '<h2 class="nav-tab-wrapper">';

		foreach ( $this->wcmamtx_plugin_settings_tab as $tab_key => $tab_caption ) {

			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';

			echo '<a class="nav-tab ' . esc_html__($active) . '" href="?page=' . esc_html__($this->wcmamtx_notices_settings_page) . '&tab=' . esc_html__($tab_key) . '">' . esc_html__($tab_caption) . '</a>';	

		}

		echo '</h2>';

	}

    /**
     * render accordion content from $key and $value
     */

	public function get_accordion_content($key,$name,$core_fields,$value = null,$old_value = null,$third_party = null) {
	     
	    $third_party = isset($value['third_party']) ? $value['third_party'] : $third_party; 

		if (isset($third_party)) {
			$key = strtolower($key);
			$key = str_replace(' ', '_', $key);
		} 
        
        ?>
        <li keyvalue="<?php echo $key; ?>" litype="<?php if (isset($value['wcmamtx_type'])) { echo  $value['wcmamtx_type']; } ?>" class="<?php if (isset($value['show']) && ($value['show'] == "no"))  { echo "wcmamtx_disabled"; } ?> wcmamtx_endpoint <?php echo $key; ?> <?php if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "group")) { echo 'group'; } ?> <?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { echo "core"; } ?>">

            <?php $this->get_main_li_content($key,$name,$core_fields,$value,$old_value,$third_party); ?>


        </li> <?php
        
    }


    public function get_main_li_content($key,$name,$core_fields,$value = null,$old_value = null,$third_party = null) { 
         
        global $wp_roles;



        $extra_content_core_fields = 'downloads,edit-address,edit-account';
        $exclude_content_core_fields       = 'dashboard,orders,customer-logout';

        if (isset($value['wcmamtx_type'])) {

        	$wcmamtx_type = $value['wcmamtx_type'];

        } else {
        	$wcmamtx_type = 'endpoint';
       
        }


        if (isset($value['parent']) && ($value['parent'] != "")) {

        	$wcmamtx_parent = $value['parent'];
        	
        } else {

        	$wcmamtx_parent = 'none';
       
        }



        if ( ! isset( $wp_roles ) ) { 
        	$wp_roles = new WP_Roles();  

        }

        $roles    = $wp_roles->roles;


        $third_party = isset($value['third_party']) ? $value['third_party'] : $third_party;

	    
    	?>

    	<h3>
    		<div class="wcmamtx_accordion_handler">
    			<?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { ?>
    				<input type="checkbox" class="wcmamtx_accordion_onoff" parentkey="<?php echo $key; ?>"  <?php if (isset($value['show']) && ($value['show'] != "no"))  { echo "checked"; } elseif (!isset($value['show'])) { echo 'checked';} ?>>
    				<input type="hidden" class="<?php echo $key; ?>_hidden_checkbox" value='<?php if (isset($value['show']) && ($value['show'] == "no")) { echo "no"; } else { echo 'yes';} ?>' name='<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][show]'>

    			<?php } else { 
                      
    				if (isset($third_party)) {
    					$key = strtolower($key);
    					$key = str_replace(' ', '_', $key);
    				}

    				?>
    				<span type="removeicon" parentkey="<?php echo $key; ?>" class="dashicons dashicons-trash wcmamtx_accordion_remove"></span>
    			<?php } ?>
    		</div>

    		<span class="dashicons dashicons-menu-alt "></span><?php if (isset($name)) { echo $name; } ?>
    		<span class="wcmamtx_type_label">
    			<?php echo ucfirst($wcmamtx_type); ?>
    		</span>

    	</h3>

        <div class="<?php echo $wcmamtx_type; ?>_accordion_content">

        	<table class="wcmamtx_table widefat">

        		<?php if (isset($third_party)) { ?>

        			<tr>
        				<td>
                        
        				</td>
        				<td>
        					<p><?php  echo esc_html__('This is third party endpoint.Some features may not work.','customize-my-account-for-woocommerce'); ?></p>
        					<input type="hidden" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][third_party]" value="yes">
        					<input type="hidden" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_name]" value="<?php if (isset($name)) { echo $name; } ?>">
        				</td>

        			</tr>

        		<?php } ?>

                <?php if ((!preg_match('/\b'.$key.'\b/', $core_fields ) && ($wcmamtx_type == 'endpoint')) && (!isset($third_party))) { ?>   

                <tr>
                    <td>
                    	<label class="wcmamtx_accordion_label"><?php  echo esc_html__('Key','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                        <input type="text" class="wcmamtx_accordion_input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_key]" value="<?php if (isset($value['endpoint_key'])) { echo $value['endpoint_key']; } else { echo $key; } ?>">
                    </td>
            
                </tr>
                <?php } else { ?>

            	    <input type="hidden" class="wcmamtx_accordion_input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_key]" value="<?php if (isset($value['endpoint_key'])) { echo $value['endpoint_key']; } else { echo $key; } ?>">


                <?php  } ?>

        
                <input type="hidden" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][wcmamtx_type]" value="<?php echo $wcmamtx_type; ?>">

                <input type="hidden" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][parent]" class="wcmamtx_parent_field" value="<?php echo $wcmamtx_parent; ?>">

                <?php if (!isset($third_party)) { ?>

                <tr>
                    <td>
                        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Label','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>

                        <input type="text" class="wcmamtx_accordion_input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][endpoint_name]" value="<?php if (isset($value['endpoint_name'])) { echo $value['endpoint_name']; } else { if (preg_match('/\b'.$key.'\b/', $core_fields ) ) { echo $value; } } ?>">
                    </td>
            
                </tr>

                <?php } ?>
                

                <tr>
                    <td>
                        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Icon Settings','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                    	<?php 
                             if (isset($value['icon_source']) && ($value['icon_source'] != '')) {
                             	$icon_source = $value['icon_source'];
                             } else {
                             	$icon_source = 'default';
                             }
                    	?>

                    	<div class="wcmamtx_icon_settings_div">
                    		<div class="form-check wcmamtx_icon_checkbox">
                    			<input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="default" <?php if ($icon_source == "default") { echo 'checked'; } ?>>
                    			<label class="form-check-label wcmamtx_icon_checkbox_label" >
                    				<?php  echo esc_html__('Default theme Icon','customize-my-account-for-woocommerce'); ?>
                    			</label>
                    		</div>
                    		<div class="form-check wcmamtx_icon_checkbox">
                    			<input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="noicon" <?php if ($icon_source == "noicon") { echo 'checked'; } ?>>
                    			<label class="form-check-label wcmamtx_icon_checkbox_label">
                    				<?php  echo esc_html__('No icon','customize-my-account-for-woocommerce'); ?>
                    			</label>
                    		</div>
                    		<div class="form-check wcmamtx_icon_checkbox">
                    			<input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][icon_source]"  value="custom" <?php if ($icon_source == "custom") { echo 'checked'; } ?>>
                    			<label class="form-check-label wcmamtx_icon_checkbox_label">
                    				<?php  echo esc_html__('Custom icon','customize-my-account-for-woocommerce'); ?>
                    			</label>
                    		</div>
                    	</div>
                    </td>
            
                </tr>

                <tr style= "<?php if ($icon_source == "custom") { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
                    <td>
                        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Icon','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>

                        <input type="text" class="wcmamtx_iconpicker icon-class-input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][icon]" value="<?php if (isset($value['icon'])) { echo $value['icon']; } ?>">
                        <button type="button" class="btn btn-primary picker-button"><?php  echo esc_html__('Pick an Icon','customize-my-account-for-woocommerce'); ?></button>
                    </td>
            
                </tr>
            

                <?php if ($wcmamtx_type == 'link') {     
                ?>
                

                <tr>
                    <td>
                        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Link url','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                         <input class="wcmamtx_accordion_input" type="text" name="wcmamtx_advanced_settings[<?php echo $key; ?>][link_inputtarget]" value="<?php if (isset($value['link_inputtarget']) && ($value['link_inputtarget'] != '')) { echo ($value['link_inputtarget']); } else { echo '#';} ?>" size="70">
                    </td>
            
                </tr>

                <tr>
                    <td>
                    	<label class="wcmamtx_accordion_label"><?php  echo esc_html__('Open in new tab','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>    
                        <input data-toggle="toggle" data-size="sm" class="wcmamtx_accordion_input wcmamtx_accordion_checkbox checkmark" type="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][link_targetblank]" value="01" <?php if (isset($value['link_targetblank']) && ($value['link_targetblank'] == "01")) { echo 'checked'; } ?>>
                    </td>
                </tr>

                <?php } ?>


                <tr>
			        <td>
                        <label class="wcmamtxvisibleto wcmamtx_accordion_label"><?php echo esc_html__('Visible to','customize-my-account-for-woocommerce'); ?></label>
	                </td>
			        <td>
			            <select class="wcmamtxvisibleto" name="wcmamtx_advanced_settings[<?php echo $key; ?>][visibleto]">
			                <option value="all" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "all")) { echo "selected"; } ?>><?php echo esc_html__('All roles','customize-my-account-for-woocommerce'); ?></option>
				            <option value="specific" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific")) { echo "selected"; } ?>><?php echo esc_html__('Specific roles','customize-my-account-for-woocommerce'); ?></option>
			            </select>
			   
	                </td>
			    </tr>

			    <?php 

			    if (!empty($value['roles'])) { 
			    	$chosenrolls = implode(',', $value['roles']); 
			    } else { 
			    	$chosenrolls=''; 
			    } 

			    ?>
			  
			    <tr style="<?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific")) { echo "display:table-row;"; } else { echo "display:none;"; } ?>" class="wcmamtxroles">
			        <td>
                        <label class="wcmamtx_roles wcmamtx_accordion_label"><?php echo esc_html__('Select roles','customize-my-account-for-woocommerce'); ?></label>
	                </td>
			        <td>
			            <select data-placeholder="<?php echo esc_html__('Choose Roles','customize-my-account-for-woocommerce'); ?>" name="wcmamtx_advanced_settings[<?php echo $key; ?>][roles][]" class="wcmamtx_roleselect" multiple>
                            <?php foreach ($roles as $rkey => $role) { ?>
				                <option value="<?php echo $rkey; ?>" <?php if (preg_match('/\b'.$rkey.'\b/', $chosenrolls )) { echo 'selected';}?>><?php echo $role['name']; ?></option>
				            <?php } ?>
                        </select>
                    </td>
			    </tr>


			    <?php if (($wcmamtx_type == 'endpoint') && (!preg_match('/\b'.$key.'\b/', $exclude_content_core_fields )) && (!isset($third_party))) { ?>

			    <tr>
                    <td>
                        <label class="wcmamtx_accordion_label wcmamtx_custom_content_label"><?php  echo esc_html__('Custom Content','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>    
                        
                        <?php 
                            $editor_content = isset($value['content']) ? $value['content'] : "";

                            

                            $editor_id      = 'wcmamtx_content_'.$key.'';
                            $editor_name    = ''.esc_html__($this->wcmamtx_notices_settings_page).'['.$key.'][content]';

                            wp_editor( $editor_content, $editor_id, $settings = array(
                            	'textarea_name' => $editor_name,
                            	'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16
                            ) ); 
                        ?>
                    </td>
                </tr>

                <?php } ?>


                <?php if (($wcmamtx_type == 'endpoint') && (preg_match('/\b'.$key.'\b/', $extra_content_core_fields ))) { ?>

                	<tr>
                		<td>
                			<label class="wcmamtx_accordion_label"><?php  echo esc_html__('Content Settings','customize-my-account-for-woocommerce'); ?></label>
                		</td>
                		<td>
                			<?php 
                			if (isset($value['content_settings']) && ($value['content_settings'] != '')) {
                				$content_settings = $value['content_settings'];
                			} else {
                				$content_settings = 'after';
                			}
                			?>

                			<div class="wcmamtx_content_settings_div">
                				<div class="form-check wcmamtx_content_checkbox">
                					<input class="form-check-input wcmamtx_content_source_radio" type="radio" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][content_settings]"  value="after" <?php if ($content_settings == "after") { echo 'checked'; } ?>>
                					<label class="form-check-label wcmamtx_icon_checkbox_label" >
                						<?php  echo esc_html__('After Existing Content','customize-my-account-for-woocommerce'); ?>
                					</label>
                				</div>
                				<div class="form-check wcmamtx_content_checkbox">
                					<input class="form-check-input wcmamtx_content_source_radio" type="radio" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][content_settings]"  value="before" <?php if ($content_settings == "before") { echo 'checked'; } ?>>
                					<label class="form-check-label wcmamtx_icon_checkbox_label">
                						<?php  echo esc_html__('Before Existing Content','customize-my-account-for-woocommerce'); ?>
                					</label>
                				</div>
                			</div>
                		</td>

                	</tr>

                <?php } ?>


                <?php if ($wcmamtx_type == 'group') { ?>

                	<tr>
                		<td>
                			<label class="wcmamtx_accordion_label"><?php  echo esc_html__('Open by default','customize-my-account-for-woocommerce'); ?></label>
                		</td>
                		<td>    
                			<input class="wcmamtx_accordion_input wcmamtx_accordion_checkbox form-check-input" type="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][group_open_default]" <?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01")) { echo 'checked'; } ?> value="01">
                		</td>
                	</tr>

                <?php } ?>

                <tr>
                    <td>
                        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Classes','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>    
                        <input type="text" class="wcmamtx_accordion_input wcmamtx_class_input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][class]" value="<?php if (isset($value['class'])) { echo $value['class']; } ?>">
                    </td>
                </tr>

                <?php if ($wcmamtx_type != 'group') { ?>

                <?php } ?>

                
            </table>

        </div>

            <?php if (($wcmamtx_type == 'group') && ($value['parent'] == "none")) {

            	$this->get_group_content($name,$key,$value);

            } ?>


    <?php 
    
    }


        public function get_group_content($name,$key,$value) {

        	    $all_keys  = $this->advanced_settings;  
                
                $matches   = $this->wcmamtx_search($all_keys, $key);

         
    	    ?>

            	<ol class="wcmamtx_group_items">

                    <?php 
                        foreach($matches as $mkey=>$mvalue) {
                        	$mname             = $mvalue['endpoint_name'];
                        	$core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';


                            $this->get_accordion_content($mkey,$mname,$core_fields,$mvalue,null);
                        }
                    ?>
                
                </ol>
            <?php
                
        }






        public function wcmamtx_search($array, $key) {
          
            $results = array();

            
        
                foreach ($array as $subkey=>$subvalue) {

                	if (isset($subvalue['parent'])) {

                		if ($subvalue['parent'] == $key) {
                    	    $results[$subkey] = $subvalue;
                        }
                	}
                    
                }
            
            return $results;
        }
    


    }
}


new wcmamtx_add_settings_page_class();
?>