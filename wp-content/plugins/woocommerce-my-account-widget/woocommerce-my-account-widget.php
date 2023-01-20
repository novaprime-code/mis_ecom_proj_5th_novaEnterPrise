<?php
/*
* Plugin Name: WooCommerce My Account Widget
* Plugin URI: http://wordpress.org/extend/plugins/woocommerce-my-account-widget/
* Description: WooCommerce My Account Widget shows order & account data.
* Author: PEP
* Author URI: https://www.pepbc.nl/
* Version: 0.6.6
* WC requires at least: 3.3.0
* WC tested up to: 3.8.0
*/

class WooCommerceMyAccountWidget extends WP_Widget
{
	function __construct()
	{
		$widget_ops = array('classname' => 'WooCommerceMyAccountWidget', 'description' => __( 'WooCommerce My Account Widget shows order & account data', 'woocommerce-my-account-widget' ) );
		parent::__construct('WooCommerceMyAccountWidget', __( 'WooCommerce My Account Widget', 'woocommerce-my-account-widget' ), $widget_ops);
	}
	
	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$show_cartlink = isset( $instance['show_cartlink'] ) ? (bool) $instance['show_cartlink'] : false;
		$show_items = isset( $instance['show_items'] ) ? (bool) $instance['show_items'] : false;
		$show_upload = isset( $instance['show_upload'] ) ? (bool) $instance['show_upload'] : false;
		$show_upload_new = isset( $instance['show_upload_new'] ) ? (bool) $instance['show_upload_new'] : false;
		$show_unpaid = isset( $instance['show_unpaid'] ) ? (bool) $instance['show_unpaid'] : false;
		$show_pending = isset( $instance['show_pending'] ) ? (bool) $instance['show_pending'] : false;
		$show_logout_link = isset( $instance['show_logout_link'] ) ? (bool) $instance['show_logout_link'] : false;
		$login_with_email = isset( $instance['login_with_email'] ) ? (bool) $instance['login_with_email'] : false;
		$add_styling = isset( $instance['add_styling'] ) ? (bool) $instance['add_styling'] : false;
		if(!isset($instance['wma_redirect'])) $instance['wma_redirect']='';
	?>
	<p><label for="<?php echo $this->get_field_id('logged_out_title'); ?>"><?php _e('Logged out title:', 'woocommerce-my-account-widget') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('logged_out_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('logged_out_title') ); ?>" value="<?php if (isset ( $instance['logged_out_title'])) echo esc_attr( $instance['logged_out_title'] ); else echo __('Customer Login', 'woocommerce-my-account-widget'); ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('logged_in_title'); ?>"><?php _e('Logged in title:', 'woocommerce-my-account-widget') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('logged_in_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('logged_in_title') ); ?>" value="<?php if (isset ( $instance['logged_in_title'])) echo esc_attr( $instance['logged_in_title'] ); else echo __('Welcome %s', 'woocommerce-my-account-widget'); ?>" /></p>

   	<p> <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_cartlink') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_cartlink') ); ?>"<?php checked( $show_cartlink ); ?> />
		<label for="<?php echo $this->get_field_id('show_cartlink'); ?>"><?php _e( 'Show link to shopping cart', 'woocommerce-my-account-widget' ); ?></label><br />
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_items') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_items') ); ?>"<?php checked( $show_items ); ?> />
		<label for="<?php echo $this->get_field_id('show_items'); ?>"><?php _e( 'Show number of items in cart', 'woocommerce-my-account-widget' ); ?></label><br />

        <?php if (class_exists('WPF_Uploads')): ?>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_upload_new') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_upload_new') ); ?>"<?php checked( $show_upload_new ); ?> />
		<label for="<?php echo $this->get_field_id('show_upload_new'); ?>"><?php _e( 'Show number of uploads left', 'woocommerce-my-account-widget' ); ?></label><br />
		<?php elseif (function_exists('woocommerce_umf_admin_menu')): ?>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_upload') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_upload') ); ?>"<?php checked( $show_upload ); ?> />
		<label for="<?php echo $this->get_field_id('show_upload'); ?>"><?php _e( 'Show number of uploads left', 'woocommerce-my-account-widget' ); ?></label><br />
		<?php endif; ?>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_unpaid') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_unpaid') ); ?>"<?php checked( $show_unpaid ); ?> />
		<label for="<?php echo $this->get_field_id('show_unpaid'); ?>"><?php _e( 'Show number of unpaid orders', 'woocommerce-my-account-widget' ); ?></label><br/>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_pending') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_pending') ); ?>"<?php checked( $show_pending ); ?> />
		<label for="<?php echo $this->get_field_id('show_pending'); ?>"><?php _e( 'Show number of uncompleted orders', 'woocommerce-my-account-widget' ); ?></label><br>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_logout_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_logout_link') ); ?>"<?php checked( $show_logout_link ); ?> />
		<label for="<?php echo $this->get_field_id('show_logout_link'); ?>"><?php _e( 'Show logout link', 'woocommerce-my-account-widget' ); ?></label>
	</p>
	<p><label for="<?php echo $this->get_field_id('wma_redirect'); ?>"><?php _e('Redirect to page after login:', 'woocommerce-my-account-widget') ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name('wma_redirect') ); ?>" class="widefat">
			<option value="">
				<?php echo esc_attr( __( 'Select page','woocommerce-my-account-widget' ) ); ?></option>
				<?php
				$pages = get_pages();
				foreach ( $pages as $page ) {
					$option = '<option value="' . $page->ID . '" '.selected($instance['wma_redirect'],$page->ID,false).'>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				}
				?>
		</select>

	<p><?php _e('Other options','woocommerce-my-account-widget');?>:<br>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('login_with_email') ); ?>" name="<?php echo esc_attr( $this->get_field_name('login_with_email') ); ?>"<?php checked( $login_with_email ); ?> />
		<label for="<?php echo $this->get_field_id('login_with_email'); ?>"><?php _e( 'Login with email address', 'woocommerce-my-account-widget' ); ?></label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('add_styling') ); ?>" name="<?php echo esc_attr( $this->get_field_name('add_styling') ); ?>"<?php checked( $add_styling ); ?> />
		<label for="<?php echo $this->get_field_id('add_styling'); ?>"><?php _e( 'Add basic styling and icons', 'woocommerce-my-account-widget' ); ?></label>
	</p>

<?php
}

function update($new_instance, $old_instance)
{
    $instance = $old_instance;
	$instance['logged_out_title'] = strip_tags(stripslashes($new_instance['logged_out_title']));
	$instance['logged_in_title'] = strip_tags(stripslashes($new_instance['logged_in_title']));
	$instance['show_cartlink'] = !empty($new_instance['show_cartlink']) ? 1 : 0;
	$instance['show_items'] = !empty($new_instance['show_items']) ? 1 : 0;
	$instance['show_upload'] = !empty($new_instance['show_upload']) ? 1 : 0;
	$instance['show_upload_new'] = !empty($new_instance['show_upload_new']) ? 1 : 0;
	$instance['show_unpaid'] = !empty($new_instance['show_unpaid']) ? 1 : 0;
	$instance['show_pending'] = !empty($new_instance['show_pending']) ? 1 : 0;
	$instance['show_logout_link'] = !empty($new_instance['show_logout_link']) ? 1 : 0;
	$instance['login_with_email'] = !empty($new_instance['login_with_email']) ? 1 : 0;
	$instance['wma_redirect'] = esc_attr($new_instance['wma_redirect']);
	$instance['add_styling'] = esc_attr($new_instance['add_styling']);

	if($instance['login_with_email']==1) {
		add_option('wma_login_with_email', $new_instance['login_with_email']);
	} else {
		delete_option('wma_login_with_email');
	}

	return $instance;
}

function widget($args, $instance)
{
	extract($args, EXTR_SKIP);
	global $woocommerce;


	$logged_out_title = apply_filters( 'widget_title', empty($instance['logged_out_title']) ? __('Customer Login', 'woocommerce-my-account-widget') : $instance['logged_out_title'], $instance );
	$logged_in_title = apply_filters( 'widget_title', empty($instance['logged_in_title']) ? __('Welcome %s', 'woocommerce-my-account-widget') : $instance['logged_in_title'], $instance );

	echo $before_widget;

	$c = (isset($instance['show_cartlink']) && $instance['show_cartlink']) ? '1' : '0';
	$cart_page_id = get_option('woocommerce_cart_page_id');

	//check if user is logged in
	if ( is_user_logged_in() ) {

		$it = (isset($instance['show_items']) && $instance['show_items']) ? '1' : '0';
		$u = (isset($instance['show_upload']) && $instance['show_upload']) ? '1' : '0';
		$unew = (isset($instance['show_upload_new']) && $instance['show_upload_new']) ? '1' : '0';
		$up = (isset($instance['show_unpaid']) && $instance['show_unpaid']) ? '1' : '0';
		$p = (isset($instance['show_pending']) && $instance['show_pending']) ? '1' : '0';
		$lo = (isset($instance['show_logout_link']) && $instance['show_logout_link']) ? '1' : '0';

	// redirect url after login / logout
	if(is_multisite()) { $woo_ma_home=network_home_url(); } else {$woo_ma_home=home_url();}

		$user = get_user_by('id', get_current_user_id());
		echo '<div class=login>';
		if($user->first_name!="") { $uname=$user->first_name;} else { $uname=$user->display_name; }
		if ( $logged_in_title ) echo $before_title . sprintf( $logged_in_title, ucwords($uname) ) . $after_title;




		if($c) {echo '<p><a class="woo-ma-button cart-link woo-ma-cart-link" href="'.get_permalink(wma_lang_id($cart_page_id)) .'" title="'. __('View your shopping cart','woocommerce-my-account-widget').'">'.__('View your shopping cart','woocommerce-my-account-widget').'</a></p>';}

		$notcompleted=0;
		$uploadfile=0;
		$uploadfile_new=0;
		$notpaid=0;
		$customer_id = get_current_user_id();
		if ( version_compare( WOOCOMMERCE_VERSION, "2.2" ) < 0 ) {

            $customer_orders = get_posts( array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => 'shop_order',
                'post_status' => 'publish'
            ) );

        } else {

            $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
            	'numberposts' => -1,
            	'meta_key'    => '_customer_user',
            	'meta_value'  => get_current_user_id(),
            	'post_type'   => wc_get_order_types( 'view-orders' ),
            	'post_status' => array_keys( wc_get_order_statuses() )
            ) ) );

        }
		if ($customer_orders) {
    			foreach ($customer_orders as $customer_order) :
    				$woocommerce1=0;
    				if ( version_compare( WOOCOMMERCE_VERSION, "2.2" ) < 0 ) {
    				    $order = new WC_Order();
                        $order->populate( $customer_order );
                    } else {
                        $order = wc_get_order($customer_order->ID);
                    }

    				//$status = get_term_by('slug', $order->status, 'shop_order_status');
    				if(wma_get_order_data($order, 'status')!='completed' && wma_get_order_data($order, 'status')!='cancelled'){ $notcompleted++; }


    			/* upload files */
    		if (function_exists('woocommerce_umf_admin_menu')) {
    			if(get_max_upload_count($order) >0 ) {
    				$j=1;
    				foreach ( $order->get_items() as $order_item ) {
    					$max_upload_count=get_max_upload_count($order,$order_item['product_id']);
    					$i=1;
    					$upload_count=0;
    					while ($i <= $max_upload_count) {
    						if(get_post_meta(wma_get_order_data($order, 'id'), '_woo_umf_uploaded_file_name_' . $j, true )!="") {$upload_count++;}
    						$i++;
    						$j++;
    					}
    					/* toon aantal nog aan te leveren bestanden */
    					$upload_count=$max_upload_count-$upload_count;
    					$uploadfile+=$upload_count;
    				}
    			}
    		}


            if (class_exists('WPF_Uploads')) {

                // Uploads needed
                $uploads_needed = WPF_Uploads::order_needs_upload($order, true);
                $uploaded_count_new = WPF_Uploads::order_get_upload_count(wma_get_order_data($order, 'id'));

                $uploads_needed_left = $uploads_needed - $uploaded_count_new;

                $uploadfile_new = $uploadfile_new + $uploads_needed_left;
            }


    		if (in_array(wma_get_order_data($order, 'status'), array('on-hold','pending', 'failed'))) { $notpaid++;}
    		endforeach;
		}

		$my_account_id=wma_lang_id(get_option('woocommerce_myaccount_page_id'));

		echo '<ul class="clearfix woo-ma-list">';
			if($it) {
				//$woocommerce->cart->get_cart_url()
				echo '<li class="woo-ma-link item">
						<a class="cart-contents-new" href="'.get_permalink(wma_lang_id($cart_page_id)).'" title="'. __('View your shopping cart', 'woocommerce-my-account-widget').'">
							<span>'.$woocommerce->cart->cart_contents_count.'</span> '
							._n('product in your cart','products in your cart', $woocommerce->cart->cart_contents_count, 'woocommerce-my-account-widget' ).'
						</a>
					</li>';
			}
			if($u && function_exists('woocommerce_umf_admin_menu')) {

				echo '<li class="woo-ma-link upload">
						<a href="'.get_permalink( $my_account_id ).'" title="'. __('Upload files', 'woocommerce-my-account-widget').'">
							<span>'.$uploadfile.'</span> '
							._n('file to upload','files to upload', $uploadfile, 'woocommerce-my-account-widget' ).'
						</a>
					</li>';
			}
            if($unew && class_exists('WPF_Uploads')) {

				echo '<li class="woo-ma-link upload">
						<a href="'.get_permalink( $my_account_id ).'" title="'. __('Upload files', 'woocommerce-my-account-widget').'">
							<span>'.$uploadfile_new.'</span> '
							._n('file to upload','files to upload', $uploadfile_new, 'woocommerce-my-account-widget' ).'
						</a>
					</li>';
			}
			if($up) {
				echo '<li class="woo-ma-link paid">
						<a href="'.get_permalink( $my_account_id ).'" title="'. __('Pay orders', 'woocommerce-my-account-widget').'">
							<span>'.$notpaid.'</span> '
							._n('payment required','payments required', $notpaid, 'woocommerce-my-account-widget' ).'
						</a>
					</li>';
			}
			if($p) {
				echo '<li class="woo-ma-link pending">
						<a href="'.get_permalink( $my_account_id ).'" title="'. __('View uncompleted orders', 'woocommerce-my-account-widget').'">
							<span>'.$notcompleted.'</span> '
							._n('order pending','orders pending', $notcompleted, 'woocommerce-my-account-widget' ).'
						</a>
					</li>';
			}
		echo '</ul>';
		echo '<p><a class="woo-ma-button woo-ma-myaccount-link myaccount-link" href="'.get_permalink( $my_account_id ).'" title="'. __('My Account','woocommerce-my-account-widget').'">'.__('My Account','woocommerce-my-account-widget').'</a></p>';
		if($lo==1) { echo '<p><a class="woo-ma-button woo-ma-logout-link logout-link" href="'.wp_logout_url($woo_ma_home).'" title="'. __('Log out','woocommerce-my-account-widget').'">'.__('Log out','woocommerce-my-account-widget').'</a></p>'; }
	}
	else {
		echo '<div class=logout>';
		// user is not logged in
		if ( $logged_out_title ) echo $before_title . $logged_out_title . $after_title;
		if(isset($_GET['login']) && $_GET['login']=='failed') {
			echo '<p class="woo-ma-login-failed woo-ma-error">';
			_e('Login failed, please try again','woocommerce-my-account-widget');
			echo '</p>';
		}
		// login form
		$args = array(
			'echo' => true,
			'form_id' => 'wma_login_form',
			'label_username' => (get_option('wma_login_with_email')=='on')?__( 'Username or Email', 'woocommerce-my-account-widget' ):__( 'Username','woocommerce-my-account-widget'),
			'label_password' => __( 'Password','woocommerce-my-account-widget'),
			'label_remember' => __( 'Remember Me','woocommerce-my-account-widget' ),
			'label_log_in' => __( 'Log In','woocommerce-my-account-widget'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
			'value_remember' => false );

		if(isset($instance['wma_redirect']) && $instance['wma_redirect']!="") {
			$args['redirect']=get_permalink(wma_lang_id($instance['wma_redirect']));
		}

		wp_login_form( $args );
		echo '<a class="woo-ma-link woo-ma-lost-pass" href="'. wp_lostpassword_url().'">'. __('Lost password?', 'woocommerce-my-account-widget').'</a>';

		if(get_option('users_can_register')) {
			echo ' <a class="woo-ma-button woo-ma-register-link register-link" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Register','woocommerce-my-account-widget').'">'.__('Register','woocommerce-my-account-widget').'</a>';
		}
		if($c) {
			echo '<p><a class="woo-ma-button woo-ma-cart-link cart-link" href="'.get_permalink(wma_lang_id($cart_page_id)) .'" title="'. __('View your shopping cart','woocommerce-my-account-widget').'">'.__('View your shopping cart','woocommerce-my-account-widget').'</a></p>';
		}
	}
	echo '</div>';
	if(isset($instance['add_styling']) && $instance['add_styling']!="") {
	?>
	<style>
	#main .widget-area ul ul.woo-ma-list,
	.widget-area ul ul.woo-ma-list,
	ul.woo-ma-list {margin:0 0 30px;list-style:none;padding:0;}
	.woo-ma-link{position:relative;margin:6px 0;}
	.woo-ma-link a {display:block;padding:2px 5px 2px 10px;border:1px solid #eee;background-color:#fff;border-radius:20px;position:relative;}
	.woo-ma-link a:before {content:' ';display:inline-block;width:24px;height:24px;background:url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMy4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGFhZ18xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDI0IDExMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMTExOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8Zz4NCgk8Zz4NCgkJPHBhdGggZD0iTTExLjgsMTAuMmMtMC4yLTAuMi0wLjYtMC40LTAuOS0wLjRjLTAuMywwLTAuNywwLjEtMC45LDAuNGwtMi43LDIuOWMtMC4yLDAuMy0wLjMsMC42LTAuMiwwLjljMC4xLDAuMywwLjQsMC41LDAuOCwwLjUNCgkJCUg5djVjMCwwLjYsMC41LDEuMSwxLjEsMS4xaDEuOGMwLjYsMCwxLjEtMC41LDEuMS0xLjF2LTVoMC45YzAuMywwLDAuNy0wLjIsMC44LTAuNXMwLjEtMC43LTAuMi0wLjlMMTEuOCwxMC4yeiBNMTIuNywxMy42DQoJCQljLTAuMywwLTAuNiwwLjMtMC42LDAuNnY1LjNjMCwwLjEtMC4xLDAuMi0wLjIsMC4yaC0xLjhjLTAuMSwwLTAuMi0wLjEtMC4yLTAuMnYtNS4zYzAtMC4zLTAuMy0wLjYtMC42LTAuNkg4LjJsMi42LTIuOA0KCQkJYzAuMS0wLjEsMC4yLTAuMSwwLjItMC4xYzAsMCwwLjEsMCwwLjIsMC4xbDIuNiwyLjhIMTIuN3ogTTEyLjcsMTMuNiIvPg0KCQk8cGF0aCBkPSJNNy45LDkuMUgxNGMwLjMsMCwwLjUtMC4yLDAuNS0wLjVjMC0wLjMtMC4yLTAuNS0wLjUtMC41SDcuOWMtMC4zLDAtMC41LDAuMi0wLjUsMC41QzcuNCw4LjksNy42LDkuMSw3LjksOS4xTDcuOSw5LjF6DQoJCQkgTTcuOSw5LjEiLz4NCgkJPHBhdGggZD0iTTE3LjcsMEg3LjRDNi4yLDAsNS4zLDEsNS4zLDIuMXYyLjFMMy42LDYuMWMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMC4xYzAsMCwwLDAsMCwwYzAsMCwwLDAsMCwwYzAsMCwwLDAsMCwwYzAsMCwwLDAsMCwwDQoJCQljMCwwLDAsMCwwLDBjMCwwLDAsMCwwLDBjMCwwLDAsMCwwLDAuMWMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMC4xYzAsMCwwLDAsMCwwYzAsMCwwLDAsMCwwLjFjMCwwLDAsMCwwLDANCgkJCUMzLjIsNi45LDMuMSw3LjIsMy4xLDcuNXYxNC4yYzAsMS4yLDEsMi4zLDIuMywyLjNoMTEuMmMxLjIsMCwyLjItMSwyLjMtMi4yYzEuMiwwLDIuMS0xLDIuMS0yLjFWMy4yQzIwLjksMS40LDE5LjUsMCwxNy43LDANCgkJCUwxNy43LDB6IE03LDMuOHYyLjRjMCwwLjEtMC4xLDAuMi0wLjIsMC4ySDQuNkw3LDMuOHogTTE4LjgsNC4zYzAtMS4yLTEtMi4zLTIuMy0yLjNIOC4yYy0wLjMsMC0wLjYsMC4xLTAuOSwwLjJjMCwwLDAsMCwwLDANCgkJCWMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMwLDAsMCwwLTAuMSwwYzAsMCwwLDAsMCwwYzAsMCwwLDAtMC4xLDBjMCwwLDAsMCwwLDANCgkJCWMwLDAsMCwwLTAuMSwwYzAsMCwwLDAsMCwwYzAsMCwwLDAtMC4xLDAuMWMwLDAsMCwwLDAsMGMwLDAsMCwwLjEtMC4xLDAuMWMwLDAsMCwwLDAsMEw2LjIsMy4yVjIuMWMwLTAuNywwLjUtMS4yLDEuMi0xLjJoMTAuMw0KCQkJQzE5LDAuOSwyMCwyLDIwLDMuMnYxNi41YzAsMC42LTAuNSwxLjItMS4xLDEuMlY0LjN6IE0xNy45LDIxLjdjMCwwLjctMC42LDEuMy0xLjMsMS4zSDUuM2MtMC43LDAtMS4zLTAuNi0xLjMtMS4zVjcuNQ0KCQkJYzAsMCwwLTAuMSwwLTAuMWgyLjdjMC43LDAsMS4yLTAuNSwxLjItMS4yVjNDOCwzLDguMSwzLDguMiwzaDguNGMwLjcsMCwxLjMsMC42LDEuMywxLjNWMjEuN3ogTTIwLDE5LjciLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIGQ9Ik0xOC42LDUwLjRjMC4zLDAsMC41LTAuMiwwLjUtMC41cy0wLjItMC41LTAuNS0wLjVjLTAuMywwLTAuNSwwLjItMC41LDAuNVMxOC4zLDUwLjQsMTguNiw1MC40TDE4LjYsNTAuNHogTTE4LjYsNTAuNCIvPg0KCQk8cGF0aCBkPSJNMTAuOCw1MC40YzAuMywwLDAuNS0wLjIsMC41LTAuNXMtMC4yLTAuNS0wLjUtMC41Yy0wLjMsMC0wLjUsMC4yLTAuNSwwLjVTMTAuNSw1MC40LDEwLjgsNTAuNEwxMC44LDUwLjR6IE0xMC44LDUwLjQiLz4NCgkJPHBhdGggZD0iTTIzLjksMzUuNmMtMC4xLTAuMS0wLjItMC4yLTAuNC0wLjJINi43bC0xLjEtNC41Yy0wLjItMC42LTAuNy0xLjEtMS40LTEuMUgxLjRjLTAuOCwwLTEuNCwwLjYtMS40LDEuNHMwLjYsMS40LDEuNCwxLjQNCgkJCWgxLjdsMy41LDE0LjljMC4yLDAuNiwwLjcsMS4xLDEuNCwxLjFoMC45Yy0wLjMsMC40LTAuNSwwLjktMC41LDEuNGMwLDEuMywxLjEsMi4zLDIuMywyLjNzMi4zLTEuMSwyLjMtMi4zYzAtMC41LTAuMi0xLTAuNS0xLjQNCgkJCWg0Yy0wLjMsMC40LTAuNSwwLjktMC41LDEuNGMwLDEuMywxLjEsMi4zLDIuMywyLjNzMi4zLTEuMSwyLjMtMi4zYzAtMC41LTAuMi0xLTAuNS0xLjRoMGMwLjgsMCwxLjQtMC42LDEuNC0xLjQNCgkJCWMwLTAuOC0wLjYtMS40LTEuNC0xLjRIOS4xbC0wLjQtMS45aDEyLjNjMC42LDAsMS4yLTAuNCwxLjQtMS4xTDI0LDM2QzI0LDM1LjgsMjQsMzUuNywyMy45LDM1LjZ6IE0xMCwzNi4zbDAuNCwyLjhINy41bC0wLjctMi44DQoJCQlIMTB6IE0xMC44LDUxLjNjLTAuOCwwLTEuNC0wLjYtMS40LTEuNGMwLTAuOCwwLjYtMS40LDEuNC0xLjRzMS40LDAuNiwxLjQsMS40QzEyLjIsNTAuNywxMS42LDUxLjMsMTAuOCw1MS4zeiBNMTguNiw1MS4zDQoJCQljLTAuOCwwLTEuNC0wLjYtMS40LTEuNGMwLTAuOCwwLjYtMS40LDEuNC0xLjRzMS40LDAuNiwxLjQsMS40QzIwLDUwLjcsMTkuMyw1MS4zLDE4LjYsNTEuM3ogTTIwLjQsNDYuNmMwLjMsMCwwLjUsMC4yLDAuNSwwLjUNCgkJCWMwLDAuMy0wLjIsMC41LTAuNSwwLjVIOGMtMC4yLDAtMC40LTAuMS0wLjUtMC40TDMuOCwzMS42SDEuNGMtMC4zLDAtMC41LTAuMi0wLjUtMC41YzAtMC4zLDAuMi0wLjUsMC41LTAuNWgyLjgNCgkJCWMwLjIsMCwwLjQsMC4xLDAuNSwwLjRsMi45LDEyLjRjMCwwLDAsMCwwLDBsMC43LDMuMkgyMC40eiBNOC40LDQyLjlsLTAuNy0yLjhoMi43bDAuNCwyLjhIOC40eiBNMTEsMzYuM2gzLjR2Mi44aC0zTDExLDM2LjN6DQoJCQkgTTExLjgsNDIuOWwtMC40LTIuOGgyLjl2Mi44SDExLjh6IE0xNy44LDQyLjloLTIuNXYtMi44aDIuOUwxNy44LDQyLjl6IE0xOC4zLDM5LjFoLTN2LTIuOGgzLjRMMTguMywzOS4xeiBNMjEuNCw0Mi41DQoJCQljLTAuMSwwLjItMC4yLDAuNC0wLjUsMC40aC0yLjJsMC40LTIuOEgyMkwyMS40LDQyLjV6IE0yMi4yLDM5LjFoLTNsMC40LTIuOGgzLjNMMjIuMiwzOS4xeiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggZD0iTTguOCw2My4yYzAsMi44LDIuMyw1LjIsNS4yLDUuMmMyLjgsMCw1LjItMi4zLDUuMi01LjJjMC0yLjgtMi4zLTUuMi01LjItNS4yQzExLjEsNTgsOC44LDYwLjMsOC44LDYzLjJMOC44LDYzLjJ6DQoJCQkgTTE4LjIsNjMuMmMwLDIuMy0xLjksNC4yLTQuMiw0LjJjLTIuMywwLTQuMi0xLjktNC4yLTQuMmMwLTIuMywxLjktNC4yLDQuMi00LjJDMTYuMyw1OC45LDE4LjIsNjAuOCwxOC4yLDYzLjJMMTguMiw2My4yeg0KCQkJIE0xOC4yLDYzLjIiLz4NCgkJPHBhdGggZD0iTTUuNCw4MS45YzAuMiwwLjIsMC41LDAuMiwwLjcsMGwyLjMtMi4yYzAuNC0wLjQsMC41LTEsMC4zLTEuNWwwLjUtMC41YzAuMy0wLjMsMC42LTAuNCwxLTAuNGg2LjJjMS4xLDAsMi4yLTAuNCwyLjktMS4yDQoJCQljMCwwLTAuMiwwLjMsNC4zLTUuMWMwLjctMC44LDAuNi0yLTAuMi0yLjZjLTAuOC0wLjctMi0wLjYtMi42LDAuMmwtMi44LDIuOGMtMC4zLTAuNC0wLjktMC43LTEuNS0wLjdoLTUuMg0KCQkJYy0wLjctMC4zLTEuNS0wLjUtMi4zLTAuNWMtMi4zLDAtNC4yLDEtNS4zLDNjLTAuNC0wLjEtMC45LDAuMS0xLjMsMC40bC0yLjIsMi4yYy0wLjIsMC4yLTAuMiwwLjUsMCwwLjdMNS40LDgxLjl6IE04LjksNzEuMg0KCQkJYzAuNywwLDEuNCwwLjEsMi4xLDAuNGMwLjEsMCwwLjEsMCwwLjIsMGg1LjNjMC41LDAsMC45LDAuNCwwLjksMC45YzAsMC41LTAuNCwwLjktMC45LDAuOWgtNS44Yy0wLjMsMC0wLjUsMC4yLTAuNSwwLjUNCgkJCWMwLDAuMywwLjIsMC41LDAuNSwwLjVoNS44YzEsMCwxLjktMC44LDEuOS0xLjljMC0wLjEsMC0wLjIsMC0wLjJjMi43LTIuNywzLjEtMy4xLDMuMS0zLjJjMC4zLTAuNCwwLjktMC40LDEuMy0wLjENCgkJCWMwLjQsMC4zLDAuNCwwLjksMC4xLDEuM2wtNC4yLDVjLTAuNiwwLjYtMS40LDAuOS0yLjMsMC45aC02LjJjLTAuNiwwLTEuMiwwLjItMS42LDAuN2wtMC40LDAuNGwtMy43LTMuNw0KCQkJQzUuMyw3Mi4xLDYuOSw3MS4yLDguOSw3MS4yTDguOSw3MS4yeiBNMyw3NC4zYzAuMi0wLjIsMC40LTAuMiwwLjYtMC4xYzAuMSwwLTAuMi0wLjIsNC4xLDRjMC4yLDAuMiwwLjIsMC41LDAsMC43bC0xLjksMS45DQoJCQlsLTQuNi00LjZMMyw3NC4zeiBNMyw3NC4zIi8+DQoJCTxwYXRoIGQ9Ik0xMy40LDYwLjN2MC41Yy0wLjUsMC4yLTAuOSwwLjctMC45LDEuM2MwLDAuOCwwLjYsMS40LDEuNCwxLjRjMC4zLDAsMC41LDAuMiwwLjUsMC41YzAsMC4zLTAuMiwwLjUtMC41LDAuNQ0KCQkJYy0wLjIsMC0wLjQtMC4xLTAuNi0wLjRjLTAuMi0wLjItMC41LTAuMi0wLjctMC4xYy0wLjIsMC4yLTAuMiwwLjUtMC4xLDAuN2MwLjMsMC4zLDAuNSwwLjUsMC45LDAuNlY2NmMwLDAuMywwLjIsMC41LDAuNSwwLjUNCgkJCWMwLjMsMCwwLjUtMC4yLDAuNS0wLjV2LTAuNWMwLjUtMC4yLDAuOS0wLjcsMC45LTEuM2MwLTAuOC0wLjYtMS40LTEuNC0xLjRjLTAuMywwLTAuNS0wLjItMC41LTAuNWMwLTAuMywwLjItMC41LDAuNS0wLjUNCgkJCWMwLjIsMCwwLjMsMC4xLDAuNSwwLjJjMC4yLDAuMiwwLjUsMC4yLDAuNywwYzAuMi0wLjIsMC4yLTAuNSwwLTAuN2MtMC4yLTAuMi0wLjUtMC40LTAuNy0wLjR2LTAuNWMwLTAuMy0wLjItMC41LTAuNS0wLjUNCgkJCUMxMy42LDU5LjksMTMuNCw2MC4xLDEzLjQsNjAuM0wxMy40LDYwLjN6IE0xMy40LDYwLjMiLz4NCgk8L2c+DQoJPGc+DQoJCTxwYXRoIGQ9Ik0xOS43LDk1bDEtMWMwLjUtMC41LDAuNS0xLjQsMC0yYy0wLjUtMC41LTEuNC0wLjUtMiwwbC0xLjEsMS4xYy0xLjEtMC44LTIuNC0xLjMtMy43LTEuNnYtMS40aDAuOQ0KCQkJYzAuMywwLDAuNS0wLjIsMC41LTAuNXYtMS4yYzAtMC44LTAuNi0xLjQtMS40LTEuNGgtMy43Yy0wLjgsMC0xLjQsMC42LTEuNCwxLjR2MS4yYzAsMC4zLDAuMiwwLjUsMC41LDAuNWgwLjl2MS40DQoJCQljLTEuMywwLjMtMi42LDAuOC0zLjcsMS42TDUuMyw5MmMtMC41LTAuNS0xLjQtMC41LTIsMGMtMC41LDAuNS0wLjUsMS40LDAsMmwxLDFjLTEuNCwxLjctMi4yLDMuOS0yLjIsNi4yYzAsNS40LDQuNCw5LjgsOS44LDkuOA0KCQkJczkuOC00LjQsOS44LTkuOEMyMS44LDk4LjksMjEuMSw5Ni43LDE5LjcsOTVMMTkuNyw5NXogTTE5LjQsOTIuN2MwLjItMC4yLDAuNS0wLjIsMC43LDBjMC4yLDAuMiwwLjIsMC41LDAsMC43bC0xLDENCgkJCWMtMC4yLTAuMi0wLjQtMC40LTAuNy0wLjZMMTkuNCw5Mi43eiBNOS43LDg4LjRjMC0wLjMsMC4yLTAuNSwwLjUtMC41aDMuN2MwLjMsMCwwLjUsMC4yLDAuNSwwLjV2MC44SDkuN1Y4OC40eiBNMTIuOSw5MC4xdjEuMw0KCQkJYy0wLjYtMC4xLTEuMy0wLjEtMS45LDB2LTEuM0gxMi45eiBNNCw5Mi43YzAuMi0wLjIsMC41LTAuMiwwLjcsMGwxLDFjLTAuMiwwLjItMC41LDAuNC0wLjcsMC42bC0xLTFDMy44LDkzLjEsMy44LDkyLjgsNCw5Mi43DQoJCQlMNCw5Mi43eiBNMTIsMTEwLjFjLTQuOSwwLTguOS00LTguOS04LjljMC0yLjIsMC44LTQuMywyLjItNS45YzAuNC0wLjQsMC44LTAuOSwxLjMtMS4yYzEuMi0wLjksMi42LTEuNSw0LjEtMS43DQoJCQljMC40LTAuMSwwLjktMC4xLDEuMy0wLjFjMC40LDAsMC45LDAsMS4zLDAuMWMxLjUsMC4yLDIuOSwwLjgsNC4xLDEuN2MwLjUsMC40LDAuOSwwLjgsMS4zLDEuMmMxLjQsMS42LDIuMiwzLjcsMi4yLDUuOQ0KCQkJQzIwLjksMTA2LjEsMTYuOSwxMTAuMSwxMiwxMTAuMUwxMiwxMTAuMXogTTEyLDExMC4xIi8+DQoJCTxwYXRoIGQ9Ik0xMiwxMDIuNmMwLjgsMCwxLjQtMC42LDEuNC0xLjRjMC0wLjYtMC40LTEuMS0wLjktMS4zdi0zLjRjMC0wLjMtMC4yLTAuNS0wLjUtMC41Yy0wLjMsMC0wLjUsMC4yLTAuNSwwLjV2My40DQoJCQljLTAuNSwwLjItMC45LDAuNy0wLjksMS4zQzEwLjYsMTAxLjksMTEuMiwxMDIuNiwxMiwxMDIuNkwxMiwxMDIuNnogTTEyLDEwMC43YzAuMywwLDAuNSwwLjIsMC41LDAuNXMtMC4yLDAuNS0wLjUsMC41DQoJCQljLTAuMywwLTAuNS0wLjItMC41LTAuNVMxMS43LDEwMC43LDEyLDEwMC43TDEyLDEwMC43eiBNMTIsMTAwLjciLz4NCgkJPHBhdGggZD0iTTE3LDk2LjJDMTcsOTYuMiwxNyw5Ni4yLDE3LDk2LjJDMTcsOTYuMiwxNyw5Ni4yLDE3LDk2LjJjLTAuOS0wLjktMS45LTEuNS0zLTEuOGMtMC4yLTAuMS0wLjUsMC4xLTAuNiwwLjMNCgkJCWMtMC4xLDAuMiwwLjEsMC41LDAuMywwLjZjMC44LDAuMiwxLjYsMC43LDIuMiwxLjJsLTAuMywwLjNjLTAuMiwwLjItMC4yLDAuNSwwLDAuN2MwLjEsMC4xLDAuMiwwLjEsMC4zLDAuMWMwLjEsMCwwLjIsMCwwLjMtMC4xDQoJCQlsMC4zLTAuM2MwLjgsMSwxLjQsMi4yLDEuNSwzLjVoLTAuNWMtMC4zLDAtMC41LDAuMi0wLjUsMC41YzAsMC4zLDAuMiwwLjUsMC41LDAuNWgwLjVjLTAuMSwxLjMtMC42LDIuNS0xLjUsMy41bC0wLjMtMC4zDQoJCQljLTAuMi0wLjItMC41LTAuMi0wLjcsMGMtMC4yLDAuMi0wLjIsMC41LDAsMC43bDAuMywwLjNjLTEsMC44LTIuMiwxLjQtMy41LDEuNXYtMC41YzAtMC4zLTAuMi0wLjUtMC41LTAuNQ0KCQkJYy0wLjMsMC0wLjUsMC4yLTAuNSwwLjV2MC41Yy0xLjMtMC4xLTIuNS0wLjYtMy41LTEuNWwwLjMtMC4zYzAuMi0wLjIsMC4yLTAuNSwwLTAuN2MtMC4yLTAuMi0wLjUtMC4yLTAuNywwbC0wLjMsMC4zDQoJCQljLTAuOC0xLTEuNC0yLjItMS41LTMuNWgwLjVjMC4zLDAsMC41LTAuMiwwLjUtMC41cy0wLjItMC41LTAuNS0wLjVINS45YzAuMS0xLjMsMC42LTIuNSwxLjUtMy41bDAuMywwLjMNCgkJCWMwLjEsMC4xLDAuMiwwLjEsMC4zLDAuMWMwLjEsMCwwLjIsMCwwLjMtMC4xYzAuMi0wLjIsMC4yLTAuNSwwLTAuN0w4LDk2LjVjMC43LTAuNiwxLjQtMSwyLjItMS4yYzAuMi0wLjEsMC40LTAuMywwLjMtMC42DQoJCQljLTAuMS0wLjItMC4zLTAuNC0wLjYtMC4zYy0xLjEsMC4zLTIuMSwwLjktMywxLjdjMCwwLDAsMCwwLDBjMCwwLDAsMCwwLDBjLTEuMywxLjMtMiwzLjEtMiw0LjljMCwxLjksMC43LDMuNiwyLDQuOWMwLDAsMCwwLDAsMA0KCQkJYzAsMCwwLDAsMCwwYzEuMywxLjMsMy4xLDIsNC45LDJjMS45LDAsMy42LTAuNyw0LjktMmMwLDAsMCwwLDAsMGMwLDAsMCwwLDAsMGMxLjMtMS4zLDItMy4xLDItNC45QzE5LDk5LjMsMTguMyw5Ny41LDE3LDk2LjINCgkJCUwxNyw5Ni4yeiBNMTcsOTYuMiIvPg0KCTwvZz4NCjwvZz4NCjwvc3ZnPg0K') no-repeat 0 0 / 100% auto;position:relative;top:0.25em;margin-right:5px;}
	.woo-ma-link.item a:before {background-position:0 -31px;}
	.woo-ma-link.upload a:before {background-position:0 -2px;}
	.woo-ma-link.pending a:before {background-position:0 -89px;}
	.woo-ma-link.paid a:before {background-position:0 -60px;}
	</style>
	<?php 
	}
    echo $after_widget;
	
}

}

add_action('plugins_loaded', 'wma_load_textdomain');

function wma_load_textdomain() {

    load_plugin_textdomain('woocommerce-my-account-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

add_action( 'widgets_init', function() {
    return register_widget("WooCommerceMyAccountWidget");
} );

/**
* Redirect to homepage after failed login
* Since 0.2.3
*/
add_action('wp_login_failed', 'wma_login_fail');

function wma_login_fail($username){
    // Get the reffering page, where did the post submission come from?

    if (isset($_SERVER['HTTP_REFERER'])) {

        $referer = parse_url($_SERVER['HTTP_REFERER']);
    	$referer= '//'.$referer['host'].''.$referer['path'];

        // if there's a valid referrer, and it's not the default log-in screen or posted from the woocommerce login screen
        if(!empty($referer) && !strstr($referer,'wp-login') && !strstr($referer,'wp-admin') && !isset($_POST['woocommerce-login-nonce'])){
            // let's append some information (login=failed) to the URL for the theme to use
            wp_redirect($referer . '?login=failed');
        exit;
        }

    }
}

/**
 * Use e-mail address for login
 * Since 0.3
 */
function wma_email_login_auth( $user, $username, $password ) {
	if ( is_a( $user, 'WP_User' ) )
		return $user;

	if ( !empty( $username ) ) {
		$username = str_replace( '&', '&amp;', stripslashes( $username ) );
		$user = get_user_by( 'email', $username );
		if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
			$username = $user->user_login;
	}
	return wp_authenticate_username_password( null, $username, $password );
}

add_action( 'wp_footer', 'wma_login_validate' );

function wma_login_validate() {
?>
	<script type="text/javascript">

        jQuery('form#wma_login_form').submit(function(){

            if (jQuery(this).find('#user_login').val() == '' || jQuery(this).find('#user_pass').val() == '') {
              alert('<?php _e("Please fill in your username and password", "woocommerce-my-account-widget"); ?>');
              return false;
            }


        });

    </script>

<?php
}

if(get_option('wma_login_with_email')=='on') {
	remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
	add_filter( 'authenticate', 'wma_email_login_auth', 20, 3 );
}

/**
 * Get WPML ID
 * Since 0.3
 */
function wma_lang_id($id){
  if(function_exists('icl_object_id')) {
    return icl_object_id($id,'page',true);
  } else {
    return $id;
  }
}

    /*
     * Get order data by Order object
     *
     * Used for backward compatibility for WC < 3.0
     * @param WC_Order $order
     * @param string $data Data to retreive
     *
     * @return mixed
     */

     function wma_get_order_data($order, $data)
     {
        if( version_compare( WC_VERSION, '3.0', '<' ) ) {

            switch ($data) {

                case 'user_id':
                    return $order->user_id;
                case 'id':
                    return $order->id;
                case 'status':
                    return $order->status;

            }

        } else {

            switch ($data) {

                case 'user_id':
                    return $order->get_user_id();
                case 'id':
                    return $order->get_id();
                case 'status':
                    return $order->get_status();

            }

        }

     }
?>