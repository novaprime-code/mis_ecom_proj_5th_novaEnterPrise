<?php 
$plugin_options    = $this->plugin_options;
$advancedsettings  = $this->advanced_settings;

if (!isset($advancedsettings) || (sizeof($advancedsettings) == 1)) {
	$tabs = wc_get_account_menu_items();
} else {
	$tabs = $advancedsettings;
}
?> 

<table class="widefat">

	<tr>
		<td><label><?php echo esc_html__('Default My Account tab','customize-my-account-for-woocommerce-pro'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_default_tab_select" name="<?php  echo esc_html__($this->wcmamtx_plugin_options_key); ?>[default_tab]">
				<?php 
				foreach($tabs as $tkey=>$tvalue) { 
                    $stkey = isset($tvalue['endpoint_key']) ? $tvalue['endpoint_key'] : $tkey;
					?>
					<option value="<?php echo $stkey; ?>" <?php if (isset($plugin_options['default_tab']) && ($plugin_options['default_tab'] == $stkey)) {echo 'selected';} ?>>
						<?php 
						if (isset($tvalue['endpoint_name'])) { 
							echo $tvalue['endpoint_name']; 
						} else {
							echo $tvalue;
						}

						?>
					</option>  
				<?php }
				?>
			</select>
		</td>
	</tr>

	

	<tr>
		<td><label><?php echo esc_html__('Menu position','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_menu_position_select" name="<?php  echo esc_html__($this->wcmamtx_plugin_options_key); ?>[menu_position]">
				<option value="left" <?php if (isset($plugin_options['menu_position']) && ($plugin_options['menu_position'] != "right")) { echo 'selected'; } ?>><?php echo esc_html__('Vertical Left','customize-my-account-for-woocommerce'); ?></option>
				<option value="right" <?php if (isset($plugin_options['menu_position']) && ($plugin_options['menu_position'] == "right")) { echo 'selected'; } ?>><?php echo esc_html__('Vertical Right','customize-my-account-for-woocommerce'); ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Show avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle" data-size="xs" class="wcmamtx_show_avatar_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_options_key); ?>[show_avatar]" value="yes" <?php if (isset($plugin_options['show_avatar']) && ($plugin_options['show_avatar'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_avatar_size_tr" style="<?php if (isset($plugin_options['show_avatar']) && ($plugin_options['show_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;';  } ?>">
		<td><label><?php echo esc_html__('Avatar size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="number" name="<?php  echo esc_html__($this->wcmamtx_plugin_options_key); ?>[avatar_size]" value="<?php if (isset($plugin_options['avatar_size']) && ($plugin_options['avatar_size'] != "")) { echo $plugin_options['avatar_size']; } else { echo "200"; } ?>">px
		</td>
	</tr>
</table>