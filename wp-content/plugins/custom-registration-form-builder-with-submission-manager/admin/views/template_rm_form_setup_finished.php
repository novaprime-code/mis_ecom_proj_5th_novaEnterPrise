
<div id="rm_form_setup" class="rm-wizard-final-step"><div id="rm-form-wizard-modal" class="rm-form-wizard-modal">
   <div class="rm-form-wizard-modal-overlay"></div> 
   <div class="rm-wizard-steps-container">
          <ul id="progressbar">
                            <li class="rm-form-wizard-step rm-form-wizard-step-1 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-2 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-3 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-4 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-5 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-6 rm-form-wizard-step-active"></li>
                          
                        </ul> 
       
   </div>
   
   <div class="rm-form-wizard-step-main">
       <div class="rm-form-wizard-step-wrap rm-form-wizard-step-6" id="step-6">
           
           <div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head"><?php _e('Success!','custom-registration-form-builder-with-submission-manager');?></div></div>
           
           <div class="rm-wizard-final-title"><strong><?php _e('Congratulations, ','custom-registration-form-builder-with-submission-manager');?></strong> <?php _e('your new form','custom-registration-form-builder-with-submission-manager');?><strong> <?php echo esc_html($data->form->form_name);?> </strong> <?php _e('is now ready for publishing!','custom-registration-form-builder-with-submission-manager');?></div>
             <div>
                             <div class="success-checkmark">
                                 <div class="check-icon">
                                     <span class="icon-line line-tip"></span>
                                     <span class="icon-line line-long"></span>
                                     <div class="icon-circle"></div>
                                     <div class="icon-fix"></div>
                                 </div>
                             </div>
                             </div>
           
           <div class="rm-wizard-links-wrap">
           
           	<?php 
		if(isset($data->form)){ $form = $data->form;?>
			<div class="rm-wizard-links">
                            <div class="rm-wizard-links-title"><?php esc_html_e("Here’s what you can do next:",'custom-registration-form-builder-with-submission-manager'); ?></div>


                            <div class="rm-form-wizard-link"><?php esc_html_e('Publish this form inside a post or a page using shortcode','custom-registration-form-builder-with-submission-manager'); ?> <span class='rm_wizard_shortcode' id="rm_wizard_login_shortcode"><strong>[RM_Form id='<?php echo esc_html($form->form_id);?>']</strong></span> <a href="javascript:void(0)" onclick="rm_copy_wizard_shortcode(document.getElementById('rm_wizard_login_shortcode'))"> <span class="rm-wizard-copy-icon"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg> </span></a><span style="display: none" class="rm-wizard-shorcode-copied"><?php _e("Copied!",'custom-registration-form-builder-with-submission-manager');?></span></div>
                            <div class="rm-form-wizard-lono-muted-text"><?php esc_html_e("Other methods of publishing forms are also available.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                            <div class="rm-form-wizard-link"><?php esc_html_e('Explore more form settings','custom-registration-form-builder-with-submission-manager'); ?> <a href="<?php echo admin_url("admin.php?page=rm_form_sett_manage&rm_form_id=".$form->form_id);?>"> <?php esc_html_e('All Settings','custom-registration-form-builder-with-submission-manager');?></a></div>
                            <div class="rm-form-wizard-link"><?php esc_html_e('Modify form layout','custom-registration-form-builder-with-submission-manager'); ?> <a href="<?php echo admin_url("admin.php?page=rm_field_manage&rm_form_id=".$form->form_id);?>"> <?php esc_html_e('Field Manager','custom-registration-form-builder-with-submission-manager');?></a></div>
                            
			</div>
		<?php }
		else{
			RM_Utilities::redirect(admin_url("admin.php?page=rm_form_setup"));
		}
		?>
           
           </div>
           
           
         <div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button rm-setup-form-previous-btn-slide-7" value="Previous" /><input type="button" name="Close" onclick="window.location.href='admin.php?page=rm_form_manage&rm_new_added_form=<?php echo esc_attr($form->form_id);?>'" class="rm-form-next-slide rm-form-action-button rm-form-wizard-close" value="Close" /></div> 
           
       </div>
   </div>
   
   <div class="rm-wizard-dashboard" onclick="window.location.href='admin.php?page=rm_dashboard_widget_dashboard'" ><span class="dashicons dashicons-arrow-left-alt2"></span>Back to WordPress Dashboard</div>
</div>
</div>



<pre class='rm-pre-wrapper-for-script-tags'>
<script>

function rm_copy_wizard_shortcode(target) {

    var text_to_copy = jQuery(target).text();

    var tmp = jQuery("<input id='pg_shortcode_input' readonly>");
    var target_html = jQuery(target).html();
    jQuery(target).html('');
    jQuery(target).append(tmp);
    tmp.val(text_to_copy).select();
    var result = document.execCommand("copy");

    if (result != false) {
        jQuery(target).html(target_html);
        jQuery(target).parents('.rm-form-wizard-link').children(".rm-wizard-shorcode-copied").fadeIn('slow');
        jQuery(target).parents('.rm-form-wizard-link').children('.rm-wizard-shorcode-copied').fadeOut('slow');
    } else {
        jQuery(document).mouseup(function (e) {
            var container = jQuery("#pg_shortcode_input");
            if (!container.is(e.target)  
                    && container.has(e.target).length === 0) 
            {
                jQuery(target).html(target_html);
            }
        });
    }
}

</script>
</pre>