<?php
if (!defined('WPINC')) {
    die('Closed');
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$form = new RM_PFBC_Form("rm_reset_pass_form");
$form->configure(array(
    "prevent" => array("bootstrap", "jQuery", "focus"),
    "action" => ""
));
$gopts = new RM_Options();
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_OLD_PASS') . ":</b>", "old_pass", array('required' => true, 'id' => 'rm_old_pass_field')));
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_NEW_PASS') . ":</b>", "new_pass", array('required' => true, 'id' => 'rm_new_pass_field')));
if($gopts->get_value_of('enable_custom_pw_rests') == 'yes') {
    $pw_error_msg = array(
        'PWR_UC' => RM_UI_Strings::get('LABEL_PW_RESTS_PWR_UC'),
        'PWR_NUM' => RM_UI_Strings::get('LABEL_PW_RESTS_PWR_NUM'),
        'PWR_SC' => RM_UI_Strings::get('LABEL_PW_RESTS_PWR_SC'),
        'PWR_MINLEN' => RM_UI_Strings::get('LABEL_PW_MINLEN_ERR'),
        'PWR_MAXLEN' => RM_UI_Strings::get('LABEL_PW_MAXLEN_ERR')
    );
    $pw_rests = $gopts->get_value_of('custom_pw_rests');
    $patt_regex = RM_Utilities::get_password_regex($pw_rests);
    $error_str = RM_UI_Strings::get('ERR_TITLE_CSTM_PW');
    if(!empty($pw_rests->selected_rules)){
        foreach ($pw_rests->selected_rules as $rule) {
            if ($rule == 'PWR_MINLEN') {
                $x = sprintf($pw_error_msg['PWR_MINLEN'], $pw_rests->min_len);
                $error_str .= '<br> -> ' . $x;
            } elseif ($rule == 'PWR_MAXLEN') {
                $x = sprintf($pw_error_msg['PWR_MAXLEN'], $pw_rests->max_len);
                $error_str .= '<br> -> ' . $x;
            } else
                $error_str .= '<br> -> ' . $pw_error_msg[$rule];
        }
    }
    if(is_array($pw_rests->selected_rules)){
        if (in_array('PWR_MINLEN', $pw_rests->selected_rules) && isset($pw_rests->min_len) && $pw_rests->min_len)
            $minlength = $pw_rests->min_len;

        if (in_array('PWR_MAXLEN', $pw_rests->selected_rules) && isset($pw_rests->max_len) && $pw_rests->max_len)
            $maxlength = $pw_rests->max_len;
    }
    $form->addElement(new Element_HTML("<div><label id=\"rm-pass-reset-error\" class=\"rm-form-field-invalid-msg\" for=\"rm_new_pass_field\" style=\"\">$error_str</label></div>"));
}
$form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_NEW_PASS_AGAIN') . ":</b>", "new_pass_repeat", array('required' => true, 'id' => 'rm_repeat_pass_field')));
$form->addElement(new Element_Hidden("rm_slug", "rm_front_reset_pass_page"));
/*
 * Checking if recpatcha is enabled
 */
if(get_option('rm_option_enable_captcha')=="yes")
    $form->addElement(new Element_Captcha());

$form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_RESET_PASS'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit", "onclick" => "rm_validate(event)")));

/*
 * Render the form if user is not logged in
 */
?>
<div class='rmagic'>
	<div class='rmcontent'>
        <?php $form->render(); ?>
        <pre class="rm-pre-wrapper-for-script-tags">
            <script type="text/javascript">
                jQuery('#rm-pass-reset-error').parent().hide();
                jQuery('#rm-pass-reset-error').parent().css('color', 'red');
                function rm_validate(e){
                    var old_pass = jQuery('#rm_old_pass_field').val().toString().trim();
                    var new_pass = jQuery('#rm_new_pass_field').val().toString().trim();
                    var required_error= "<?php _e('This field is required','custom-registration-form-builder-with-submission-manager'); ?>";
                    var password_not_match_error=  "<?php _e('password does not match.','custom-registration-form-builder-with-submission-manager'); ?>"; 
                    var repeat_pass = jQuery('#rm_repeat_pass_field').val().toString().trim();
                    jQuery('.rminput label').remove();
                    if(old_pass === "" || !old_pass){
                        jQuery('#rm_old_pass_field').after('<label class="rm-form-field-invalid-msg" id="old_pass_error" style="display:none">'+required_error+'</label>');
                        jQuery('#old_pass_error').show();
                    }
                    if(new_pass === "" || !new_pass){
                        jQuery('#rm_new_pass_field').after('<label class="rm-form-field-invalid-msg" id="new_pass_error" style="display:none">'+required_error+'</label>');
                        jQuery('#new_pass_error').show();
                    }
                    <?php if(isset($patt_regex)) { ?>
                    var pattRegex = /<?php echo wp_kses_post($patt_regex); ?>/;
                    if(!pattRegex.test(new_pass)){
                        jQuery('#rm-pass-reset-error').parent().show();
                        e.preventDefault();
                    }
                    <?php } ?>
                    if(repeat_pass === "" || !repeat_pass){
                        jQuery('#rm_repeat_pass_field').after('<label class="rm-form-field-invalid-msg" id="repeat_pass_error" style="display:none">'+required_error+'</label>');
                        jQuery('#repeat_pass_error').show();
                    }
                    if(jQuery('#rm_new_pass_field').val() !== jQuery('#rm_repeat_pass_field').val()){
                        jQuery('#rm_new_pass_field').after('<label class="rm-form-field-invalid-msg" id="new_pass_error" style="display:none">'+password_not_match_error+'</label>');
                        jQuery('#rm_repeat_pass_field').after('<label class="rm-form-field-invalid-msg" id="repeat_pass_error" style="display:none">'+password_not_match_error+'</label>');
                        jQuery('#new_pass_error, #repeat_pass_error').show();
                        e.preventDefault();
                    }
                }
            </script>
        </pre>
	</div>
</div>