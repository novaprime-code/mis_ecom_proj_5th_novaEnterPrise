<?php

// -- Getting values from setting panel
function woomatrix_envato_options( $option, $section, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

// -- Shortcode [gs_envato]
add_shortcode('woomatrix_envato_portfolio','woomatrix_envato_shortcode');

function woomatrix_envato_shortcode( $atts ) {

	$gs_envato_user = woomatrix_envato_options('gs_envato_user', 'gs_envato_settings', 'themeum');
	$gs_envato_items = woomatrix_envato_options('gs_envato_items', 'gs_envato_settings', 10 );
	$gs_marketplace = woomatrix_envato_options('gs_envato_market', 'gs_envato_settings', 'themeforest');
	$gs_order_by = 'number_of_sell';
	$gs_sorting = 'descending';
	$gs_referral_user = woomatrix_envato_options('gs_referral_user', 'gs_envato_settings', '');
	$gs_envato_theme = woomatrix_envato_options('gs_envato_theme', 'gs_envato_settings', 'gs_envato_theme1');
	$gs_envato_link_tar = woomatrix_envato_options('gs_envato_link_tar', 'gs_envato_settings', '_blank');
	$gs_envato_cols = woomatrix_envato_options('gs_envato_cols', 'gs_envato_settings', 4 );
	
	$atts = shortcode_atts(
		array(
			'userid'		=> $gs_envato_user,
			'count' 		=> $gs_envato_items,
			'orderby'   	=> $gs_order_by,
			'sorting'     	=> $gs_sorting,
			'market'    	=> $gs_marketplace,
			'referral_user' => $gs_referral_user,
			'theme'			=> $gs_envato_theme,
			'cols'			=> $gs_envato_cols
    ), $atts );

	$sorting = $atts['sorting'];
	$count 	 = $atts['count'];
	$ref 	 = !empty($atts['referral_user']) ? '?ref='.$atts['referral_user'] : '';
	$columns = $atts['cols'];
	 
	// $gs_envato_url = "http://marketplace.envato.com/api/v3/new-files-from-user:".$atts['userid'].",".$atts['market'].".json";
	$args='';
	$defaults = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . 'EbzVPDKfW9R9IwFSSYqOssnMJ6Tf4mj6',
					// 'User-Agent'    => 'WordPress - Envato Market ',
				),
				// 'timeout' => 14,
			);
			$args     = wp_parse_args( $args, $defaults );
			$token = trim( str_replace( 'Bearer', '', $args['headers']['Authorization'] ) );
	 
	$gs_envato_url = "https://api.envato.com/v1/market/new-files-from-user:".$atts['userid'].",".$atts['market'].".json";
	
    $gs_envato_response = wp_remote_get( $gs_envato_url, $args );
    $gs_envato_xml = wp_remote_retrieve_body( $gs_envato_response );
    $gs_envato_json = json_decode( $gs_envato_xml ,true);
	$gs_envato_items = $gs_envato_json;
	

	foreach ($gs_envato_items as  $gs_envato_item) {

		if($atts['orderby']=='number_of_sell' && $atts['sorting'] =='descending' ) {
			if(! function_exists('sort_by_order')) {
				function sort_by_order ($a, $b) {
					return $b['sales'] - $a['sales'];
				}
			usort($gs_envato_item, 'sort_by_order');

			}
		} elseif($atts['orderby']=='price' && $atts['sorting'] =='descending'){
			if(! function_exists('sort_by_order')){
				function sort_by_order ($a, $b) {
				    return $b['cost'] - $a['cost'];
				}

				usort($gs_envato_item, 'sort_by_order');
			}
		} elseif($atts['orderby']=='rating' && $atts['sorting'] =='descending') {
			if(! function_exists('sort_by_order')){
				function sort_by_order ($a, $b) {
				    return $b['rating'] - $a['rating'];
				}

				usort($gs_envato_item, 'sort_by_order');
			}
		}
	}

	$gs_envato_items = $gs_envato_item;

	$output = '';
	$output .= '<div class="gs_envato_area '.$atts['theme'].'">';

		if ( $atts['theme'] == 'gs_envato_theme1' ) {
			include 'gs_envato_theme1_grid.php';
		} else {
        echo('<h4 style="text-align: center;">Select correct Theme or Upgrade to <a href="https://www.gsplugins.com/product/wordpress-envato-plugin" target="_blank">Pro version</a><br>For more Options <a href="http://envato.gsplugins.com" target="_blank">Chcek available demos</a></h4>');
      }

	 $output .= '</div>'; // 
	return $output;
} // end function