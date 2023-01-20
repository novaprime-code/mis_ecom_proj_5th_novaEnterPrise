<?php 
$login_options    = $this->login_options;

?> 

<table class="widefat wcmamtx_login_register_content">

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content Before Login and Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_add_content_before_login_register show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_add_content_before_login_register]" value="yes" <?php if (isset($login_options['wcmamtx_add_content_before_login_register']) && ($login_options['wcmamtx_add_content_before_login_register'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr  style="<?php if (isset($login_options['wcmamtx_add_content_before_login_register']) && ($login_options['wcmamtx_add_content_before_login_register'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content Before Login and Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$content = isset($login_options['content_before_login_register']) ? $login_options['content_before_login_register'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[content_before_login_register]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($content,'content_before_login_register',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content After Login and Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_add_content_after_login_register show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_add_content_after_login_register]" value="yes" <?php if (isset($login_options['wcmamtx_add_content_after_login_register']) && ($login_options['wcmamtx_add_content_after_login_register'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr  style="<?php if (isset($login_options['wcmamtx_add_content_after_login_register']) && ($login_options['wcmamtx_add_content_after_login_register'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content After Login and Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$content = isset($login_options['content_after_login_register']) ? $login_options['content_after_login_register'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[content_after_login_register]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($content,'content_after_login_register',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>

		<th>
			<span class="login_form_heading">
				<?php echo esc_html__('Login Form Custom Content','customize-my-account-for-woocommerce'); ?>
			</span>
		</th>

	</tr>
	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content Before Login Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_login_form_start_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_login_form_start]" value="yes" <?php if (isset($login_options['wcmamtx_before_login_form_start']) && ($login_options['wcmamtx_before_login_form_start'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_login_form_content_tr" style="<?php if (isset($login_options['wcmamtx_before_login_form_start']) && ($login_options['wcmamtx_before_login_form_start'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content Before Login Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_login_form_start_content = isset($login_options['wcmamtx_before_login_form_start_content']) ? $login_options['wcmamtx_before_login_form_start_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_login_form_start_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_login_form_start_content,'wcmamtx_before_login_form_start_content',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content Before Remember me checkbox','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_login_form_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_login_form]" value="yes" <?php if (isset($login_options['wcmamtx_before_login_form']) && ($login_options['wcmamtx_before_login_form'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_login_form_content_tr" style="<?php if (isset($login_options['wcmamtx_before_login_form']) && ($login_options['wcmamtx_before_login_form'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content Before Remeber Me Checkbox','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_login_form_content = isset($login_options['wcmamtx_before_login_form_content']) ? $login_options['wcmamtx_before_login_form_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_login_form_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_login_form_content,'wcmamtx_before_login_form_content',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content After Login Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_login_form_end_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_login_form_end]" value="yes" <?php if (isset($login_options['wcmamtx_before_login_form_end']) && ($login_options['wcmamtx_before_login_form_end'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_login_form_end_content_tr" style="<?php if (isset($login_options['wcmamtx_before_login_form_end']) && ($login_options['wcmamtx_before_login_form_end'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content After Login Form ','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_login_form_end_content = isset($login_options['wcmamtx_before_login_form_end_content']) ? $login_options['wcmamtx_before_login_form_end_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_login_form_end_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_login_form_end_content,'wcmamtx_before_login_form_end_content',$options);
            echo $html;

			?>
		</td>
	</tr>
		<tr>

		<th>
			<span class="login_form_heading">
				<?php echo esc_html__('Register Form Custom Content','customize-my-account-for-woocommerce'); ?>
			</span>
		</th>

	</tr>
	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content Before register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_register_form_start_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_register_form_start]" value="yes" <?php if (isset($login_options['wcmamtx_before_register_form_start']) && ($login_options['wcmamtx_before_register_form_start'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_register_form_content_tr" style="<?php if (isset($login_options['wcmamtx_before_register_form_start']) && ($login_options['wcmamtx_before_register_form_start'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content Before Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_register_form_start_content = isset($login_options['wcmamtx_before_register_form_start_content']) ? $login_options['wcmamtx_before_register_form_start_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_register_form_start_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_register_form_start_content,'wcmamtx_before_register_form_start_content',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content Before Register Button','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_register_form_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_register_form]" value="yes" <?php if (isset($login_options['wcmamtx_before_register_form']) && ($login_options['wcmamtx_before_register_form'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_register_form_content_tr" style="<?php if (isset($login_options['wcmamtx_before_register_form']) && ($login_options['wcmamtx_before_register_form'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content Before Register Button','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_register_form_content = isset($login_options['wcmamtx_before_register_form_content']) ? $login_options['wcmamtx_before_register_form_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_register_form_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_register_form_content,'wcmamtx_before_register_form_content',$options);
            echo $html;

			?>
		</td>
	</tr>

	<tr>
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Add Custom Content After Register Form','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_before_register_form_end_checkbox show_hide_next_tr_checkbox" name="<?php  echo esc_html__($this->wcmamtx_plugin_login_key); ?>[wcmamtx_before_register_form_end]" value="yes" <?php if (isset($login_options['wcmamtx_before_register_form_end']) && ($login_options['wcmamtx_before_register_form_end'] == "yes")) { echo 'checked'; } ?>>
		</td>
	</tr>

	<tr class="wcmamtx_before_register_form_end_content_tr" style="<?php if (isset($login_options['wcmamtx_before_register_form_end']) && ($login_options['wcmamtx_before_register_form_end'] == "yes")) { echo 'display:;'; } else { echo 'display:none;'; } ?>">
		<td width="50%">
			<label class="field_label">
				<?php echo esc_html__('Content After Register Form ','customize-my-account-for-woocommerce'); ?>		
			</label>
		</td>
		<td width="50%">
			
			<?php

			$html ='';
			$wcmamtx_before_register_form_end_content = isset($login_options['wcmamtx_before_register_form_end_content']) ? $login_options['wcmamtx_before_register_form_end_content'] : "";

			

			$options = array(
			      'textarea_name' => 'wcmamtx_plugin_login[wcmamtx_before_register_form_end_content]', // Set custom name.
		          'textarea_rows' => 10
		    );
            $html .= wcmamtx_get_wp_editor($wcmamtx_before_register_form_end_content,'wcmamtx_before_register_form_end_content',$options);
            echo $html;

			?>
		</td>
	</tr>

	


</table>