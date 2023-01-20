<?php
if (!defined('WPINC')) {
    die('Closed');
}

wp_enqueue_script('script_jquery_easing');

if(defined('REGMAGIC_ADDON1')) include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_form_setup.php'); else {

$registration_template ='';
if(isset($data->reg_template)){
    foreach ($data->reg_template as $key => $template) {
        $reg_icon='';
        if($template['create_user']){
            $reg_icon .= '<span class="material-icons">person</span>';
        }
        if($template['multipage']){
            $reg_icon .= '<span class="material-icons">auto_stories</span>';
        }
        if($template['ver']=='premium'){
            $registration_template .= '<li class="form-contact form_temp_bullet" data-type="addon">
                                <label for="'.$template['id'].'" >
                                    <input type="radio" value="'.$template['id'].'" name="template_type" id="'.$template['id'].'">
                                    <div class="rm-form-template-box">
                                        <h3 class="rm-temp-header-title">'.$template['title'].'</h3>
                                        <div class="rm-template-label-icon">'.$reg_icon.'</div>
                                        <p>'.$template["description"].'</p>
                                        <div class="rm-form-template-link"><a href="#" class="rm-form-template-demo">Live Preview<span class="material-icons"> launch </span></a></div>
                                    </div>
                                </label>
                            </li>';
        } else{
            $registration_template .= '<li class="form-contact form_temp_bullet" data-type="basic">
                                <label for="'.$template['id'].'" >
                                    <input type="radio" value="'.$template['id'].'" name="template_type" id="'.$template['id'].'">
                                    <div class="rm-form-template-box">    
                                        <h3 class="rm-temp-header-title">'.$template['title'].'</h3>
                                        <div class="rm-template-label-icon">'.$reg_icon.'</div>
                                        <p>'.$template["description"].'</p>
                                        <div class="rm-form-template-link"><a href="#" class="rm-form-template-demo">Live Preview<span class="material-icons"> launch </span></a></div>
                                    </div>
                                </label>
                            </li>';
        }
    }
}

$tem_type = '';
if(defined('REGMAGIC_ADDON')){
  $tem_type= '<li><span class="material-icons">auto_stories</span><label> '.__('= Multi-page Forms','custom-registration-form-builder-with-submission-manager').'</label></li>';  
}
$template_filter = '<div class="rm-template-filter">
                    <div class="rm-temp-filter-input">
                        <input id="rm-input-filterTemplate" type="text" placeholder="Search Templates">
                        <span class="material-icons">search</span>
                    </div>
                    <div class="rm-temp-filter-suggestion">
                        <ul>
                            <li><span class="material-icons">person</span><label> '.__('= Creates User Accounts','custom-registration-form-builder-with-submission-manager').'</label></li>
                            '.$tem_type.'
                        </ul>
                    </div>
                </div>';


$form = new RM_PFBC_Form("rm_form_setup");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $progressbar = '<div id="rm-form-wizard-modal" class="rm-form-wizard-modal"> 
                            <div class="rm-form-wizard-modal-overlay"></div> 
                            <div class="rm-wizard-steps-container">
                            <ul id="progressbar">
                            <li class="rm-form-wizard-step rm-form-wizard-step-1 rm-form-wizard-step-active"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-2"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-3"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-4"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-5"></li>
                            <li class="rm-form-wizard-step rm-form-wizard-step-6"></li>
                        </ul>  
                        </div>';
        $form->addElement(new Element_HTML($progressbar));
        
        //Step Container Start here 
        
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-main">'));

        //Step 0
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-0" id="step-0"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('Start Blank or With a Readymade Template?', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-templates_list">'));
        $form->addElement(new Element_HTML($template_filter));
        $form->addElement(new Element_HTML('<div class="rm-registration-form-template" id="regForm">'));
        $form->addElement(new Element_HTML('<ul class="form_temp">'.$registration_template.'</ul>'));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button rm-setup-form-previous-btn-slide-1" value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button" value="Next" /></div>'));

        $form->addElement(new Element_HTML('</div>'));
        
        
        
        //Step 1
        /*$form->addElement(new Element_HTML('<article class="rm-step-1"><h2 class="rm-form-wizard-head">'.__('Form Type', 'custom-registration-form-builder-with-submission-manager').'</h2>'));
        $form->addElement(new Element_HTML('<div class="rm-form-name-input rm-dbfl"><input type="checkbox" value="" name="rm_form_type" id="rm_form_type" /><label for="rm_form_type">'.__("Form creates WP User account?","custom-registration-form-builder-with-submission-manager").'</label></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button" value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button" value="Next" /></div>'));
        $form->addElement(new Element_HTML('</article>'));*/

        //Step 2
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-1" id="step-1"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('Basic Form Details', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row">'));
        $form->addElement(new Element_Textbox(__('Form Name <span class="rm-req-text">(required)</span>', 'custom-registration-form-builder-with-submission-manager'), "form_name", array("id" => "form_name", "class" => "required-field", "value" =>'Blank Form',"required" => "1", 'longDesc'=>__('Name of your form.','custom-registration-form-builder-with-submission-manager') )));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row">'));
        $form->addElement(new Element_Textarea("<b>" . __('Form Description', 'custom-registration-form-builder-with-submission-manager') . "</b>", "form_description", array("class" => "form_description", "longDesc" => __('Description can be helpful in recalling purpose of your form. It is optional and can be left blank.'))));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button " value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button" value="Next" /></div>'));
        $form->addElement(new Element_HTML('<input type="hidden" name="form_type" value="rm_contact_form" id="temp_form_type"/>'));
        $form->addElement(new Element_HTML('<input type="hidden" name="rm_slug" value="rm_form_add_setup" id="rm_form_template_slug">'));
        $form->addElement(new Element_HTML('<input type="hidden" name="type" value="basic" id="temp_type">'));

        $form->addElement(new Element_HTML('</div>'));

        //Step 3
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-2" id="step-2"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('Submit Button Label and Limit', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row">'));
        $form->addElement(new Element_Textbox(__('Submit Button Label <span class="rm-req-text">(required)</span>', 'custom-registration-form-builder-with-submission-manager'), "form_submit_btn_label", array("id" => "form_submit_btn_label", "class" => "required-field", "required"=>"1", "value" =>__('Send','custom-registration-form-builder-with-submission-manager'), 'longDesc'=>__('The text on the button which your users will click to submit this form.','custom-registration-form-builder-with-submission-manager') )));
        $form->addElement(new Element_HTML('</div>'));
        //Enable Limit
        
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row"><div class="rm-form-name-input rm-dbfl"><label for="rm_form_type">'.__('Limit Submissions','custom-registration-form-builder-with-submission-manager').'</label><input type="checkbox" value="1" name="form_should_auto_expire" id="form_should_auto_expire" /></div><div class="rmnote"><div class="rmnotecontent">'.__('Limit the number of submissions you wish to receive for this form. On reaching its limit, the form will display a message to the new users. You can customize this message later. Leave unchecked for no limits.','custom-registration-form-builder-with-submission-manager').'</div></div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row enableLimit" id="enableLimit" style="display:none;">'));
        $form->addElement(new Element_HTML('<input type="hidden" name="form_expired_by" value="submissions"/>'));
        
        $form->addElement(new Element_Number("<b>" . __('Limit', 'custom-registration-form-builder-with-submission-manager') . "</b>", "form_submissions_limit", array("id" => "form_submissions_limit", "value" =>'','longDesc'=>__('The maximum number of submissions allowed for this form.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button" value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button show-review" value="Next" /></div>'));
        $form->addElement(new Element_HTML('</div>'));
        
        //Step 4
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-3" id="step-3"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('What happens next?', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row">'));
        $form->addElement(new Element_TinyMCEWP("<b>" . __('Success Message','custom-registration-form-builder-with-submission-manager') . "</b>", __('Thank you') , "form_success_message", array('editor_class' => 'rm_TinydMCE',  "required" => "1", 'editor_height' => '100px'), array('longDesc'=>__('On successfully submitting this form, users will see this message. You can use this to provide useful information to the users about what happens next.','custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div">'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row"><div class="rm-form-redirection-wrap"><div class="rmfield rm-redirection-label"><label><b>'.__('Redirect Users', 'custom-registration-form-builder-with-submission-manager').'</b></label></div><label for="rm-form-redirection-0" class="rm-form-redirection-label"><input id="rm-form-redirection-0" type="radio" name="form_redirect" class="rm_" onclick="hide_show_redirection(this);" value="none" checked="checked"><div class="rm-form-redirection-content"><span class="material-icons"> block </span><div class="rm-form-redirection-label"><span>Do Not Redirect</span><p>The user will not be redirected and the page will continue to display the success message.</p></div></div></label><label for="rm-form-redirection-1" class="rm-form-redirection-label"><input id="rm-form-redirection-1" type="radio" name="form_redirect" class="rm_" onclick="hide_show_redirection(this);" required="" value="url"><div class="rm-form-redirection-content"><span class="material-icons"> airline_stops </span><div class="rm-form-redirection-label"><span>Redirect to Another Page</span><p>The user will first see the success message before being redirected to the URL you specify below. </p></div></div></label><div class="rmnote"><div class="rmnotecontent">'.__('You can redirect users to a different page on successful submission. This can be next the step in user journey on your website or simply an FAQ page.', 'custom-registration-form-builder-with-submission-manager').' </div></div></div></div>'));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm__childfieldsrow" style="display:none" >'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row rm_form_url">'));
        $form->addElement(new Element_Url("<b>" . __('URL', 'custom-registration-form-builder-with-submission-manager') . "</b>", "form_redirect_to_url", array("id" => "form_redirect_to_url", "value" =>'', 'longDesc'=>__('URL of the page where you wish to send the user after form submission. You can always change this later.','custom-registration-form-builder-with-submission-manager') )));        
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button" value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button show-review validate-redirect-url" value="Next" /></div>'));

        $form->addElement(new Element_HTML('</div>'));
        
        //Step 5
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-4" id="step-4"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('Sending Confirmation', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-row"><div class="rm-form-name-input rm-dbfl"><label for="rm_form_type">'.__('Autoresponder','custom-registration-form-builder-with-submission-manager').'</label><input type="checkbox" value="0" name="form_should_send_email[]" id="form_should_send_email" /></div><div class="rmnote"><div class="rmnotecontent">'.__('Send an email to the user after submitting the form. You can use this email as submission receipt and add additional relevant information. You can also add form submission data into this email using our mail merge feature. It will be available after you finish creating this form.','custom-registration-form-builder-with-submission-manager').'</div></div></div>'));

        $form->addElement(new Element_HTML('<div class="enableAuto rm-form-wizard-row" id="enableAuto" style="display:none;">'));
        $form->addElement(new Element_Textbox("<b>" . __('Subject', 'custom-registration-form-builder-with-submission-manager') . "</b>", "form_email_subject", array("id" => "form_email_subject", "value" =>__('Thank you for your submission!', 'custom-registration-form-builder-with-submission-manager'), "longDesc" =>__('Subject of your email.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_TinyMCEWP("<b>" . __('Email Content','custom-registration-form-builder-with-submission-manager') . "</b>", __('<p>Hello {{email}}</p><p>We have received your submission.</p><p>Thank you!</p>','custom-registration-form-builder-with-submission-manager') , "form_email_content", array('editor_class' => 'rm_TinydMCE',  "required" => "1", 'editor_height' => '100px'), array('longDesc'=>__('Contents of your email.','custom-registration-form-builder-with-submission-manager'))));

        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button" value="Previous" /><input type="button" name="next" class="rm-form-next-slide rm-form-action-button show-review" value="Next" /></div>'));

        $form->addElement(new Element_HTML('</div>'));

        //Step 5
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-step-wrap rm-form-wizard-step-5" id="step-5"><div class="rm-form-wizard-header"><span class="material-icons"> auto_fix_high </span><div class="rm-form-wizard-head">'.__('Quick review', 'custom-registration-form-builder-with-submission-manager').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-review"><ul>
            <li class="form-name"><label>'.__('Form Name','custom-registration-form-builder-with-submission-manager').'</label><span>Basic Registration Form</span></li>
            <li class="form-description"><label>'.__('Form Description','custom-registration-form-builder-with-submission-manager').'</label><span>Description.....</span></li>
            <li class="form-type"><label>'.__('Creates User Account','custom-registration-form-builder-with-submission-manager').'</label><span>Contact Type</span></li>
            <li class="form-template"><label>'.__('Template Used','custom-registration-form-builder-with-submission-manager').'</label><span>Simple Contact Form</span></li>
            <li class="button-label"><label>'.__('Submit Button Label','custom-registration-form-builder-with-submission-manager').'</label><span>No</span></li>
            <li class="limit-enable"><label>'.__('Limit','custom-registration-form-builder-with-submission-manager').'</label><span>No</span></li>
            <li class="success-message-enable"><label>'.__('Success Message','custom-registration-form-builder-with-submission-manager').'</label><span>No</span></li>
            <li class="redirect-type"><label>'.__('Redirect Users','custom-registration-form-builder-with-submission-manager').'</label><span>No</span></li>    
            <li class="autoloader-enable"><label>'.__('Autoresponder','custom-registration-form-builder-with-submission-manager').'</label><span>No</span></li>
            </ul></div>'));
        $form->addElement(new Element_HTML('<div class="rm-form-wizard-footer"><input type="button" name="previous" class="rm-form-previous-slide rm-form-action-button" value="Previous" /><input type="submit" name="submit" class="rm-form-submit rm-form-action-button" value="Save Form" /></div>'));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('<div class="rm-wizard-dashboard"><a href="'. admin_url("admin.php?page=rm_form_manage").'" class="rm-setup-wizard-back"><span class="dashicons dashicons-arrow-left-alt2"></span>'.__('Back to WordPress Dashboard', 'custom-registration-form-builder-with-submission-manager').'</a></div>'));
        
              //Container ends here
        $form->addElement(new Element_HTML('</div>'));
        
         //Form Wizard Modal ends here
        $form->addElement(new Element_HTML('</div>'));

        $form->render();
        
 }?>
<pre class='rm-pre-wrapper-for-script-tags'>
<script>
function hide_show_redirection(element)
    {
      var  rates = jQuery(element).val();
      var classname =jQuery(element).attr('class');
      var childclass=classname+'_childfieldsrow';
      if(rates=='url')
      {
         jQuery('#'+childclass).slideDown(); 
         jQuery('.rm_form_page').slideUp();   
         jQuery('.rm_form_url').slideDown();
         jQuery('#form_redirect_to_url').addClass('required-field');
      }
      else
      {
          jQuery('#'+childclass).slideUp();
          jQuery('#form_redirect_to_url').removeClass('required-field');
          jQuery('#form_redirect_to_url').val('');
      }
    } 

jQuery('input[type=radio][name=template_type]').change(function() {
    var type = jQuery(this).closest('li').data('type');   
    jQuery('#temp_type').val(type);
    $selected_template = jQuery(this).val();
    if($selected_template.includes('r')){
        jQuery('#temp_form_type').val('rm_reg_form');
    }
    else{
        jQuery('#temp_form_type').val('rm_contact_form');
    }
    jQuery('#form_name').val(jQuery("input[type='radio'][name='template_type']:checked").closest('li').find('h3').text());
});

jQuery('#form_should_send_email').click(function(){
    if(jQuery(this).is(":checked")){
        jQuery('#enableAuto').show();
        jQuery('#form_email_subject').addClass('required-field');
    }
    else{
        jQuery('#enableAuto').hide();
        jQuery('#form_email_subject').addClass('required-field');
    }
});
jQuery('#form_should_auto_expire').click(function(){
    if(jQuery(this).is(":checked")){
        jQuery('#enableLimit').show();
        jQuery('#form_submissions_limit').addClass('required-field');
    }
    else{
        jQuery('#enableLimit').hide();
        jQuery('#form_submissions_limit').removeClass('required-field');
    }
});

jQuery(document).ready(function() {
  jQuery(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
  jQuery("#c0").prop("checked", true);
});


jQuery(document).ready(function() {
    var current_fs, next_fs, previous_fs;
    var opacity;
    var animating;
    function isValidURL(string) {
        var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        return (res !== null)
    }
    function validate(parent){
        var parentClass = parent.attr('id');
        var error = [];
        jQuery('#'+ parentClass + ' .required-field').each(function () {
            if(jQuery(this).val() ==''){
                jQuery(this).css('border','1px solid #ebebeb');
                error.push(jQuery(this).attr('name'));
                jQuery(this).closest('.rmrow').find('.rmprenote').text('This is a required field.');
            }
            else{
                if(jQuery(this).attr('type')==='url'){
                    var res = isValidURL(jQuery(this).val());
                    if(res){
                        
                        jQuery(this).css('border','');
                        jQuery(this).closest('.rmrow').find('.rmprenote').text('');
                        
                    }else{
                        jQuery(this).css('border','1px solid red');
                        error.push(jQuery(this).attr('name'));
                        jQuery(this).closest('.rmrow').find('.rmprenote').text('Please enter valid url.');
                    }
                }
                else{
                    jQuery(this).css('border','');
                    jQuery(this).closest('.rmrow').find('.rmprenote').text('');
                }
                
            }
        });
        if(error.length === 0){
            return true;
        }
        return false;
    }
    jQuery(".rm-form-next-slide").click(function(){
        
	current_fs = jQuery(this).closest(".rm-form-wizard-step-wrap");
	next_fs = jQuery(this).closest(".rm-form-wizard-step-wrap").next();
        
        if( validate(current_fs) == false){
            return false;
        }
        next_fs = jQuery(this).parent().parent().next();
        jQuery(".rm-wizard-steps-container ul li").eq(jQuery(".rm-form-wizard-step-wrap").index(next_fs)).addClass("rm-form-wizard-step-active");
        next_fs.show();
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			opacity = 1 - now;
			current_fs.css({

      });
			next_fs.css({'opacity': opacity});
		}, 
		duration: 00, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
	});
    });

    jQuery(".rm-form-previous-slide").click(function(){
        if(animating) return false;
        animating = true;
        //current_fs = jQuery(this).parent().parent();
        //previous_fs = jQuery(this).parent().parent().prev();
        current_fs = jQuery(this).closest(".rm-form-wizard-step-wrap");
	previous_fs = jQuery(this).closest(".rm-form-wizard-step-wrap").prev();
        
        
        jQuery(".rm-wizard-steps-container ul li").eq(jQuery(".rm-form-wizard-step-wrap").index(current_fs)).removeClass("rm-form-wizard-step-active");
        previous_fs.show();
        current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                opacity = 1 - now;
                current_fs.css({});
                previous_fs.css({'opacity': opacity});
            }, 
            duration: 00, 
            complete: function(){
                current_fs.hide();
                animating = false;
            }
           
        });
    });

    jQuery(".submit").click(function(){
        current_fs = jQuery(this).parent().parent();
        
        if( validate(current_fs) == false){
            return false;
        }
    });
    jQuery('.show-review').click(function(e){
        var form_name = jQuery('input#form_name').val();
        var form_desc = jQuery('textarea[name="form_description"]').val();
        var template_type = jQuery('input#temp_form_type').val();
        var template_name = jQuery("input[type='radio'][name='template_type']:checked").closest('li').find('h3').text();
        var button_label = jQuery('#form_submit_btn_label').val();
        var cross = '<i class="material-icons rm-setup-cross">close</i>';
        var done = '<i class="material-icons rm-setup-done">done</i>';
        var description = cross;
        if(form_desc){
            description = done;
        }
        if(template_type == 'rm_contact_form'){
            template_type = cross;
        } else{
            template_type = done;    
        }
        var autoresponder = cross;
        if(jQuery('#form_should_send_email').is(':checked')){
            autoresponder = done;
        }
        var limit = cross;
        if(jQuery('#form_should_auto_expire').is(':checked')){
            limit = jQuery('#form_submissions_limit').val();
        }
        var success = cross;
        var success_message ='';
        if (jQuery("#wp-form_success_message-wrap").hasClass("tmce-active")){
            success_message = tinyMCE.get('form_success_message').getContent( { format : 'html' } );
        }else{
            success_message = jQuery('[name="form_success_message"]').val();
        }
        if(success_message){
            success = done;
        }
        var redirect = cross;
        var redirectType = jQuery('input[name="form_redirect"]:checked').val();
        if(redirectType !== 'none'){
            redirect = jQuery('#form_redirect_to_url').val();
        }
        jQuery('.rm-form-review ul li.form-name span').text(form_name);
        jQuery('.rm-form-review ul li.form-description span').html(description);
        jQuery('.rm-form-review ul li.form-type span').html(template_type);
        jQuery('.rm-form-review ul li.form-template span').text(template_name);
        jQuery('.rm-form-review ul li.button-label span').text(button_label);
        jQuery('.rm-form-review ul li.limit-enable span').html(limit);
        jQuery('.rm-form-review ul li.success-message-enable span').html(success);
        jQuery('.rm-form-review ul li.redirect-type span').html(redirect);
        jQuery('.rm-form-review ul li.autoloader-enable span').html(autoresponder);
    });
    jQuery(document).ready(function() {
        jQuery("#rm-input-filterTemplate").on("keyup", function() {
          var value = jQuery(this).val().toLowerCase();
          jQuery(".form_temp li").filter(function() {
            jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
});
</script>
</pre>