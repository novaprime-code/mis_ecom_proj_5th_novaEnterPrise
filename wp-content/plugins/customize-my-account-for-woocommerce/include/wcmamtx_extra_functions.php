<?php

/**
 * Get default wp editor for content.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_wp_editor')) {



	function wcmamtx_get_wp_editor( $content = '', $editor_id, $options = array() ) {
		ob_start();

		wp_editor( $content, $editor_id, $options );

		
	}

}

/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_item_classes')) {

	function wcmamtx_get_account_menu_item_classes( $endpoint,$value ) {

		global $wp;

		$core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

		$icon_source       = isset($value['icon_source']) ? $value['icon_source'] : "default";

		switch($icon_source) {

			case "default":
			   $extra_li_class = '';
			break;

			case "noicon":
			   $extra_li_class = 'wcmamtx_no_icon';
			break;

			case "custom":
			   $extra_li_class = 'wcmamtx_custom_icon';
			break;

		}
        
        

        $classes = array(
        	'woocommerce-MyAccount-navigation-link',
        	'woocommerce-MyAccount-navigation-link--' . $endpoint,
        	''.$extra_li_class.''
        );
        
        
		

	    // Set current item class.
		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
		    $current = true; // Dashboard is not an endpoint, so needs a custom check.
	    } elseif ( 'orders' === $endpoint && isset( $wp->query_vars['view-order'] ) ) {
		    $current = true; // When looking at individual order, highlight Orders list item (to signify where in the menu the user currently is).
	    } elseif ( 'payment-methods' === $endpoint && isset( $wp->query_vars['add-payment-method'] ) ) {
		    $current = true;
	    }
 
	    if ( $current ) {
		    $classes[] = 'is-active';
	    }

	    $classes = apply_filters( 'woocommerce_account_menu_item_classes', $classes, $endpoint );

	    return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
    }
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_li_html')) {

	function wcmamtx_get_account_menu_li_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { ?>

		<li class="<?php echo wcmamtx_get_account_menu_item_classes( $key , $value ); ?> <?php echo $extraclass; ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?>">
			<a href="<?php echo wcmamtx_get_account_endpoint_url( $key ); ?>" <?php if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "link") && (isset($value['link_targetblank'])) && ($value['link_targetblank'] == 01) ) { echo 'target="_blank"'; } ?>>
				<?php 
				if ($icon_source == "custom") {
					$icon       = isset($value['icon']) ? $value['icon'] : "";

					if ($icon != '') { ?>
						<i class="<?php echo $icon; ?>"></i>
					<?php }
				}
				?>
				<?php echo esc_html( $name ); ?>
			</a>
		</li>

	<?php }
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_endpoint_url')) {

	function wcmamtx_get_account_endpoint_url($key) {

		$core_url = esc_url(wc_get_account_endpoint_url($key));

		return apply_filters('wcmamtx_override_endpoint_url',$core_url,$key);

	}
}


/**
 * Get account group html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_group_html')) {

	function wcmamtx_get_account_menu_group_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { ?>

		<li class="<?php echo wcmamtx_get_account_menu_item_classes( $key , $value ); ?> <?php echo $extraclass; ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?> <?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01" )) { echo 'open'; } else { echo 'closed'; } ?>">
			<a href="#" class="wcmamtx_group">
				<?php 
				if ($icon_source == "custom") {
					$icon       = isset($value['icon']) ? $value['icon'] : "";

					if ($icon != '') { ?>
						<i class="<?php echo $icon; ?>"></i>
					<?php }
				}
				?>
				<?php echo esc_html( $name ); ?>
			</a>
			<?php
			$all_keys  = get_option('wcmamtx_advanced_settings'); 
			$plugin_options = get_option('wcmamtx_plugin_options'); 

			$matches   = wcmamtx_get_child_li($all_keys, $key);


			$m_icon_position  = 'right';
            $m_icon_extra_class = '';

            if (isset($plugin_options['icon_position']) && ($plugin_options['icon_position'] != '')) {
            	$m_icon_position = $plugin_options['icon_position'];
            }



            switch($m_icon_position) {
            	case "right":
            	$m_icon_extra_class = "wcmamtx_custom_right";
            	break;

            	case "left":
            	$m_icon_extra_class = "wcmamtx_custom_left";
            	break;

            	default:
            	$m_icon_extra_class = "wcmamtx_custom_right";
            	break;
            }
            
            
			

			if (sizeof($matches) > 0) { ?>
				<ul class="wcmamtx_sub_level" style="<?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01" )) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
					<?php
					foreach ($matches as $mkey=>$mvalue) {
						
						if (isset($mvalue['endpoint_name']) && ($mvalue['endpoint_name'] != '')) {
							$liname = $mvalue['endpoint_name'];
						} else {
							$liname = $mvalue;
						}

						$should_show = 'yes';



						if (isset($mvalue['show']) && ($mvalue['show'] == "no")) {

							$should_show = 'no';

						}

						$icon_source_child       = isset($mvalue['icon_source']) ? $mvalue['icon_source'] : "default";

						if (isset($mvalue['class']) && ($mvalue['class'] != '')) {
							$mextraclass = str_replace(',',' ', $mvalue['class']);
						} else {
							$mextraclass = '';
						}


						if ($should_show == "yes") {

							wcmamtx_get_account_menu_li_html( $liname, $mkey ,$mvalue ,$m_icon_extra_class,$mextraclass,$icon_source_child );
					    }
					}
					?>
				</ul>
			<?php } ?>
			
		</li>

	<?php }
}


/**
 * Get parent li items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_child_li')) {


	function wcmamtx_get_child_li($array, $key) {

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

/**
 * Show user avatar before natigation items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_myaccount_customer_avatar')) {

    function wcmamtx_myaccount_customer_avatar() {
	    $current_user = wp_get_current_user();

	    $plugin_options = get_option('wcmamtx_plugin_options');

	    $show_avatar    = isset($plugin_options['show_avatar']) ? $plugin_options['show_avatar'] : "no";
	    $avatar_size    = isset($plugin_options['avatar_size']) ? $plugin_options['avatar_size'] : 200;

	    if (isset($show_avatar) && ($show_avatar == "yes")) {
	    	echo '<div class="wcmamtx_myaccount_avatar">' . get_avatar( $current_user->user_email, $avatar_size , '', $current_user->display_name ) . '</div>';
	    }
    }
}
 
add_action( 'wcmamtx_before_account_navigation', 'wcmamtx_myaccount_customer_avatar', 5 );


function wcmtxka_find_string_match($string,$array) {

	foreach ($array as $key=>$value) {

	$endpoint_key = $value['endpoint_key'];
    
    if ($endpoint_key == $string) { // Yoshi version
    	
    	return 'found';
    }
}

return 'notfound';


}

?>