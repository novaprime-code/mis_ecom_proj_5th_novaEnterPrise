<?php
if( ! class_exists('BeRocket_aapf_admin_bar_debug') ) {
    class BeRocket_aapf_admin_bar_debug{
		public $footer_run = false, $admin_bar_run = false;
        function __construct() {
            if( empty($_GET['et_fb']) ) {
                add_action( 'admin_bar_menu', array($this, 'debug_admin_bar_menu'), 1000 );
                add_action( 'wp_footer', array($this, 'footer_bar'), 1000 );
            }
        }
		function footer_bar() {
			$this->footer_run = true;
            if ( ! current_user_can( 'edit_posts' ) ) return;
            $filter_data = BeRocket_AAPF::$current_page_filters;
            unset($filter_data['added']);
			if( $this->admin_bar_run && count($filter_data) > 0 ) {
				echo '<div style="display: none;">';
				echo '<div class="bapf_wp_admin_bar_replacement">';
				echo $this->get_html();
				echo '</div>';
				echo '<script>try{ jQuery(".bapf_wp_admin_bar_replace").replaceWith(jQuery(".bapf_wp_admin_bar_replacement")); } catch(e){}</script>';
				echo $this->get_javascript();
				echo $this->get_css();
				echo '</div>';
			}
		}
        function debug_admin_bar_menu() {
			$this->admin_bar_run = true;
            global $wp_admin_bar, $wpdb;
            if ( ! current_user_can( 'edit_posts' ) ) return;
            $filter_data = BeRocket_AAPF::$current_page_filters;
            unset($filter_data['added']);
            if( count($filter_data) > 0 ) {
				$html = $this->get_html();
				$html .= $this->get_javascript();
				$html .= $this->get_css();
			} elseif( ! $this->footer_run ) {
				$html = '<div class="bapf_wp_admin_bar_replace">Filters cannot be detected</div>';
			}
            if( ! empty($html) ) {
                $BeRocket_AAPF = BeRocket_AAPF::getInstance();
                $title = '<img style="width:22px;height:22px;display:inline;" src="' . plugin_dir_url( BeRocket_AJAX_filters_file ) . 'berocket/includes/ico.png" alt="">' . $BeRocket_AAPF->info['norm_name'];
                $wp_admin_bar->add_menu( array( 'id' => 'bapf_debug_bar', 'title' => $title, 'href' => FALSE ) );
                $wp_admin_bar->add_menu( array( 'id' => 'bapf_debug_bar_content', 'parent' => 'bapf_debug_bar', 'title' => $html, 'href' => FALSE ) );
            }
        }
		function get_html() {
            $filter_data = BeRocket_AAPF::$current_page_filters;
            $added_id = $filter_data['added'];
            unset($filter_data['added']);
            $html = '<div class="brapf_admin_link"><a href="https://docs.berocket.com/plugin/woocommerce-ajax-products-filter#how-do-i-check-filter-problems">'.__('How do I check filter problems?', 'BeRocket_AJAX_domain').'</a></div>';
			foreach($filter_data as $data_type => $filter_status) {
				if( count($filter_status) > 0 ) {
					$html2 = '';
					foreach($filter_status as $data_status => $filters) {
						if( count($filters) > 0 ) {
							$html2 .= '<div><h3>'.esc_html(ucfirst(trim(str_replace('_', ' ', $data_status)))).'</h3><ul>';
							foreach($filters as $filter_id => $filter_message) {
								$filter_id = intval($filter_id);
								$title = get_the_title($filter_id);
								if( ! empty($title) ) {
									$filter_message = '('.$title.')'.$filter_message;
								}
								$html2 .= '<li title="'.esc_html($filter_message).'"><a href="'.admin_url('post.php?post='.$filter_id.'&action=edit').'" target="_blank">'.esc_html($filter_id).'</a></li>';
							}
							$html2 .= '</ul></div>';
						}
					}
					if( ! empty($html2) ) {
						$html .= '<div><h2>'.esc_html(strtoupper(trim(str_replace('_', ' ', $data_type)))).'</h2>'.$html2.'</div>';
					}
				}
			}
			if( empty($html) ) {
				$html = '<h2>'.__('Filters not detected on page', 'BeRocket_AJAX_domain').'</h2>';
			}
			$html .= '<div class="bapf_adminbar_status">';
			$html .= '</div>';
			$html .= '<div class="bapf_adminbar_errors">';
			$html .= '</div>';
			return $html;
		}
		function get_javascript() {
			global $br_aapf_wc_footer_widget;
			$html = '<script>
            var berocket_admin_inited = false;
            if( typeof(braapf_admin_error_catch) != "function" ) {
                function braapf_admin_error_catch(is_error, error_name, var1, var2, var3) {
                    var correct_error = false;
                    var critical_error = false;
                    html = "";
                    if(error_name == "same_filters_multiple_times") {
                        html += \'Same filters with ID \'+var1+\' added multiple times to the page\';
                        correct_error = true;
                        critical_error = true;
                    } else if(error_name == "multiple_filters_for_same_taxonomy") {
                        html += \'Multiple filters with taxonomy \'+var1+\' added to the page\';
                        correct_error = true;
                        critical_error = true;
                    } else if(error_name ==  "error_notsame_block_qty") {
                        html += \'New page has another quantity of blocks with selector <span class="bapf_admin_error_code">\'+var1+\'</span><br>\';
                        html += \'Current page: \'+var3+\'<br>\';
                        html += \'New page: \'+var2;
                        correct_error = true;
                        critical_error = true;
                    }
                    if( correct_error ) {
                        brapf_admin_error_bar_add(html, critical_error);
                    }
                    return true;
                }
                if( typeof(berocket_add_filter) == "function" ) {
                    berocket_add_filter("berocket_throw_error", braapf_admin_error_catch, 1);
                } else {
                    jQuery(document).on("berocket_hooks_ready", function() {
                        berocket_add_filter("berocket_throw_error", braapf_admin_error_catch, 1);
                    });
                }
            }
            function brapf_admin_error_bar_add(text, critical_error) {
                if( typeof(critical_error) == "undefined" ) {
                    critical_error = false;
                }
                var html = \'<div><span class="dashicons dashicons-info-outline"></span><p>\';
                html += text;
                html += \'</p></div>\';
                jQuery("#wp-admin-bar-bapf_debug_bar .bapf_adminbar_errors").prepend(jQuery(html));
                if( critical_error ) {
                    jQuery("#wp-admin-bar-bapf_debug_bar").removeClass("brapf_admin_error_alert");
                    setTimeout(function() {jQuery("#wp-admin-bar-bapf_debug_bar").addClass("brapf_admin_error_alert")});
                    jQuery("#wp-admin-bar-bapf_debug_bar > .ab-item .dashicons").remove();
                    jQuery("#wp-admin-bar-bapf_debug_bar > .ab-item").append(jQuery(\'<span class="dashicons dashicons-info-outline"></span>\'));
                }
            }
            jQuery(document).ready(function() {
				if( ! berocket_admin_inited && typeof(the_ajax_script) != "undefined" && jQuery(".bapf_sfilter").length ) {
                    berocket_admin_inited = true;
					var html = "<h2>STATUS</h2>";
					var products_on_page = '.(is_shop() || is_product_taxonomy() || $br_aapf_wc_footer_widget ? 'true' : 'false').';
					html += "<div class=\'bapf_adminbar_status_element\'>Is WC page";
					html += "<span class=\'dashicons dashicons-'.(is_shop() || is_product_taxonomy() ? 'yes\' title=\'Yes, it is default WooCommerce archive page' : 'no\' title=\'No, it is not WooCommerce archive page').'\'></span>";
					html += "</div>";
					
					html += "<div class=\'bapf_adminbar_status_element\'>Shortcode";
					html += "<span class=\'dashicons dashicons-'.($br_aapf_wc_footer_widget ? 'yes\' title=\'Yes, WooCommerce products shortcode detected' : 'no\' title=\'No, page do not have any custom WooCommerce products').'\'></span>";
					html += "</div>";
					
					html += "<div class=\'bapf_adminbar_status_element\'>Products";
					try {
						var products_elements = jQuery(the_ajax_script.products_holder_id).length;
						var error = false;
						if( products_elements == 0 ) {
							error = "Products element not detected. Please check that selectors setuped correct";
                            if( products_on_page ) {
                                brapf_admin_error_bar_add("Page has products that will be filtered, but products selector is incorrect", true);
                            }
						} else if( products_elements > 1 ) {
							error = "Multiple Products element detected on page("+products_elements+"). It can cause issue on filtering";
						}
						if( error === false ) {
							html += "<span class=\'dashicons dashicons-yes\' title=\'Products element detected on page\'></span>";
						} else {
							html += "<span class=\'dashicons dashicons-no\' title=\'"+error+"\'></span>";
						}
					} catch(e) {
						html = +"<strong>ERROR</strong>";
						console.log(e);
					}
					html += "</div>";
					html += "<div class=\'bapf_adminbar_status_element\'>Pagination";
					try {
						var products_elements = jQuery(the_ajax_script.products_holder_id).length;
						var pagination_elements = jQuery(the_ajax_script.pagination_class).length;
						var error = false;
						if( pagination_elements == 0 ) {
							error = "Pagination element not detected. If page has pagination or infinite scroll/load more button, then Please check that selectors setuped correct";
						} else if( pagination_elements > 1 ) {
							error = "Multiple Pagination element detected on page("+pagination_elements+"). It can cause issue on filtering if pagination from different products list";
						}
						if( error === false ) {
							html += "<span class=\'dashicons dashicons-yes\' title=\'Pagination element detected on page\'></span>";
						} else {
							html += "<span class=\'dashicons dashicons-no\' title=\'"+error+"\'></span>";
						}
					} catch(e) {
						html = +"<strong>ERROR</strong>";
						console.log(e);
					}
					html += "</div>";
					jQuery(".bapf_adminbar_status").html(html);
				}
			});</script>';
			return $html;
		}
		function get_css() {
			$html = '<style>#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item {height:initial!important;line-height:1em;}
			#wp-admin-bar-bapf_debug_bar .ab-item {display: flex;align-items: center;}
			#wp-admin-bar-bapf_debug_bar.brapf_admin_error_alert .ab-item .dashicons.dashicons-info-outline {margin-left: 5px;font-family: dashicons;font-size: 24px;line-height:32px;cursor:pointer;color: red; transform: rotate(180deg);}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item *{line-height:1em;color:#ccc;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item h2{color:white;font-size: 1.5em;text-align:center;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item h3{font-weight:bold;color:#0085ba;font-size: 1.25em;text-align:center;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item ul li {display:inline-block!important;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item ul li a {height:initial;margin:0;padding:2px;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_status {text-align:center;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_status .dashicons {font-family: dashicons;font-size: 34px;line-height: 26px;display: block;cursor:pointer;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_status .dashicons-yes {color:green;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_status .dashicons-no {color:red;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_status_element {display:inline-block;text-align:center; padding:3px;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors {text-align:center; max-height: 200px; overflow: auto; margin-left: -10px; margin-right: -10px;}
            #wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors > div {display: flex; border-top: 1px solid #555;text-align:left;align-items: center;}
            #wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors > div p {padding: 3px;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors .dashicons {font-family: dashicons;font-size: 34px;line-height: 34px;display: block;cursor:pointer;}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors .dashicons-info-outline {color: red; transform: rotate(180deg);}
			#wp-admin-bar-bapf_debug_bar .ab-submenu .ab-item .bapf_adminbar_errors .bapf_admin_error_code {color: #777; background-color:#ccc; line-height:1em; display:inline-block; padding: 3px;}
            #wp-admin-bar-bapf_debug_bar .brapf_admin_link {text-align:center;}
            #wp-admin-bar-bapf_debug_bar .brapf_admin_link a {font-size: 18px;}
            @keyframes bapf_admin_alert {
              0% {
                background-color: #23282d;
              }
              10% {
                background-color: #ee3333;
              }
              20% {
                background-color: #23282d;
              }
              30% {
                background-color: #ee3333;
              }
              40% {
                background-color: #23282d;
              }
              50% {
                background-color: #ee3333;
              }
              60% {
                background-color: #23282d;
              }
              70% {
                background-color: #ee3333;
              }
              80% {
                background-color: #23282d;
              }
              90% {
                background-color: #ee3333;
              }
              100% {
                background-color: #23282d;
              }
            }
            #wp-admin-bar-bapf_debug_bar.brapf_admin_error_alert {animation-duration: 3s;animation-name: bapf_admin_alert;}
            #wp-admin-bar-bapf_debug_bar img {margin-right: 6px;}
			</style>';
			return $html;
		}
    }
    new BeRocket_aapf_admin_bar_debug();
}