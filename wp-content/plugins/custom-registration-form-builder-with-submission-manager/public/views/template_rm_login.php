<?php
if (!defined('WPINC')) {
    die('Closed');
}

wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
    
if (!empty($data->show_otp) && defined('REGMAGIC_ADDON'))  // 2 Factor OTP form
{
    echo '<div class="rmagic"><div class="rmcontent rm-login-wrapper">';
    $form = new RM_PFBC_Form($data->otp_form_slug);
    $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => "",
        "style" => isset($data->design['style_form'])?$data->design['style_form']:null
    ));
    
    if(isset($data->design['placeholder_css'])){		
        $p_css = '<style>'.str_replace("::-", ' #'.$data->otp_form_slug.' ::-', $data->design['placeholder_css']);		
        echo str_replace("}:-", '} #'.wp_kses_post($data->otp_form_slug).' ::-', wp_kses_post($p_css)).'</style>';	
        //$form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['style_label'])){	
         echo '<style>#'.wp_kses_post($data->otp_form_slug).' .rmrow .rmfield label { '.wp_kses_post($data->design['style_label']).' }</style>';
         //$form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['text_focus_color'])){
        echo '<style>#'.wp_kses_post($data->otp_form_slug).' .rmrow .rminput input[type=text]:focus, #'.wp_kses_post($data->otp_form_slug).' .rmrow .rminput input[type=password]:focus { color: '.wp_kses_post($data->design['text_focus_color']).' !important; }</style>';
        //$form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['field_bg_focus_color'])){
        echo '<style>#'.wp_kses_post($data->otp_form_slug).' .rmrow .rminput input[type=text]:focus, #'.wp_kses_post($data->otp_form_slug).' .rmrow .rminput input[type=password]:focus { background-color: '.wp_kses_post($data->design['field_bg_focus_color']).' !important; }</style>';
        //$form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['btn_hover_color'])){
        echo '<style>#'.wp_kses_post($data->otp_form_slug).' .buttonarea input[type=submit]:hover { background-color: '.wp_kses_post($data->design['btn_hover_color']).' !important; }</style>';
        //$form->addElement(new Element_HTML($p_css));
    }
    $form->addElement(new Element_Hidden("rm_slug", "rm_login_form"));
    $form->addElement(new Element_Hidden("username", $data->username));
    if(!$data->otp_error){
        $form->addElement(new Element_HTML('<div class="rm-otp-msg">'.$data->auth_options['msg_above_otp']));
        if(!empty($data->auth_options['en_resend_otp']) && isset($data->username) && !empty($data->auth_options['otp_resend_limit'])){
            //$form->addElement(new Element_HTML('<a href="javascript:void(0)"><div class="rm-resend-otp-liml" onclick="rm_regernate_otp(this,\''.$data->username.'\')">'.$data->auth_options['otp_resend_text'].'</div></a>'));
            $form->addElement(new Element_HTML('<div class="rm-resend-otp-liml" onclick="rm_regernate_otp(this,\''.$data->username.'\')"><a>'.$data->auth_options['otp_resend_text'].'</a></div>'));
        }
        $form->addElement(new Element_HTML('</div>'));
    }
    $form->addElement(new Element_Textbox($data->auth_options['otp_field_label'], "auth_otp", array("required" => "1", "placeholder" => $data->auth_options['otp_field_label'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
    $form->addElement(new Element_HTML('<div class="rmrow fa_otp_error"></div>'));
    $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_LOGIN'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
    $form->render();
    echo '</div></div>';
} 
else // Normal form with username and password
{
    if(!empty($data->ban)){
        echo wp_kses_post($data->ban_error_msg);
        return;
    }

    $form = new RM_PFBC_Form($data->login_form_slug);
    $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => "",
         "style" => isset($data->design['style_form'])?$data->design['style_form']:null
    ));

    if(isset($data->design['placeholder_css'])){		
        $p_css = str_replace("::-", '#'.$data->login_form_slug.' ::-', $data->design['placeholder_css']);		
        echo '<style>'.str_replace("}:-", '} #'.wp_kses_post($data->login_form_slug).' ::-', wp_kses_post($p_css)).'</style>';		
        //$form->addElement(new Element_HTML($p_css));	
    }
    if(isset($data->design['style_label'])){	
        echo "<style>#".wp_kses_post($data->login_form_slug)." .rmrow .rmfield label { ".wp_kses_post($data->design['style_label'])." }</style>";
        //$form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['text_focus_color'])){
        echo "<style>#".wp_kses_post($data->login_form_slug)." .rmrow .rminput input[type=text]:focus, #".wp_kses_post($data->login_form_slug)." .rmrow .rminput input[type=password]:focus { color: ".wp_kses_post($data->design['text_focus_color'])." !important; }</style>";
        //$form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['field_bg_focus_color'])){
        echo "<style>#".wp_kses_post($data->login_form_slug)." .rmrow .rminput input[type=text]:focus, #".wp_kses_post($data->login_form_slug)." .rmrow .rminput input[type=password]:focus { background-color: ".wp_kses_post($data->design['field_bg_focus_color'])." !important; }</style>";
        //$form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['btn_hover_color'])){
        echo "<style>#".wp_kses_post($data->login_form_slug)." .buttonarea input[type=submit]:hover { background-color: ".wp_kses_post($data->design['btn_hover_color'])." !important; }</style>";
        //$form->addElement(new Element_HTML($p_css));
    }
    $form->addElement(new Element_Hidden("rm_slug", "rm_login_form"));
    if (isset($data->twitter) && defined('REGMAGIC_ADDON')) {
        include_once(RM_ADDON_EXTERNAL_DIR . "twitter/inc/twitteroauth.php");
        $connection = new TwitterOAuth($data->twitter['tw_consumer_key'], $data->twitter['tw_consumer_secret'], $_SESSION['token'], $_SESSION['token_secret']);
        $access_token = $connection->getAccessToken(sanitize_text_field($_REQUEST['oauth_verifier']));
        if ($connection->http_code == '200') {
            //Redirect user to twitter
            $_SESSION['status'] = 'verified';
            $_SESSION['request_vars'] = $access_token;

            //Insert user into the database
            $user_info = $connection->get('account/verify_credentials', array('include_email' => 'true'));
            unset($_SESSION['token']);
            unset($_SESSION['token_secret']);

            if(isset($user_info->errors)) {
                foreach($user_info->errors as $error) {
                    echo "<p>".wp_kses_post($error->message)."</p>";
                }
                echo "<p>".__('Login Failed', 'custom-registration-form-builder-with-submission-manager')."</p>";
            } else {
                $user_service = new RM_User_Services;
                $user_service->social_login_using_email_direct($user_info->email, $user_info->name, "twitter");
            }
        }
    } 
    else 
    {
        if (isset($data->fields)) 
        {
            foreach ($data->fields as $field) {
                if(!empty($field['input_selected_icon_codepoint'])){
                    $f_icon = new stdClass;
                    $f_icon->codepoint = isset($field['input_selected_icon_codepoint']) ? $field['input_selected_icon_codepoint'] : '';
                    $f_icon->fg_color = isset($field['icon_fg_color']) ? $field['icon_fg_color'] : '000000';
                    $f_icon->bg_color = isset($field['icon_bg_color']) ? $field['icon_bg_color'] : 'ffffff';
                    $f_icon->shape = isset($field['icon_shape']) ? $field['icon_shape'] : 'square';
                    $f_icon->bg_alpha = isset($field['icon_bg_alpha']) ? $field['icon_bg_alpha'] : 1.0;
                    $radius='';
                    if($f_icon->shape == 'square')
                        $radius = '0px';
                    else if($f_icon->shape == 'round')
                        $radius = '100px';
                    else if($f_icon->shape == 'sticker')
                        $radius = '4px';

                    $bg_r = intval(substr($f_icon->bg_color,0,2),16);
                    $bg_g = intval(substr($f_icon->bg_color,2,2),16);
                    $bg_b = intval(substr($f_icon->bg_color,4,2),16);
                   
                    if($field['field_label'] === '{{AUTO}}') {
                        if ($field['field_type'] == 'username') {
                            $field['field_label'] = __('Username','custom-registration-form-builder-with-submission-manager');
                        } elseif ($field['field_type'] == 'password') {
                            $field['field_label'] = __('Password','custom-registration-form-builder-with-submission-manager');
                        }
                    }
                   
                    if($field['placeholder'] === '{{AUTO}}') {
                       if ($field['field_type'] == 'username') {
                           $field['placeholder'] = __('Enter Username','custom-registration-form-builder-with-submission-manager');
                       } elseif ($field['field_type'] == 'password') {
                           $field['placeholder'] = __('Enter Password','custom-registration-form-builder-with-submission-manager');
                       }
                    }
                   
                    $icon_style = "style=\"padding:5px;color:#{$f_icon->fg_color};background-color:#{$f_icon->bg_color};border-radius:{$radius};\"";
                    $field['field_label']= '<span><i class="material-icons rm_front_field_icon" ' . $icon_style . ' id="id_show_selected_icon" data-opacity="'.$f_icon->bg_alpha.'">' . $f_icon->codepoint . ';</i></span>' . $field['field_label'];
               } 

                if ($field['field_type'] == 'username') {
                    if(isset($this->field_options)){
                        $this->x_opts = (object)array('icon' => $this->field_options->icon);
                    }
                   $form->addElement(new Element_Textbox($field['field_label'], "username", array("required" => "1","class"=>$field['field_css_class'], "placeholder" => $field['placeholder'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
                } else if ($field['field_type'] == 'password') {
                    $form->addElement(new Element_Password($field['field_label'], "pwd", array("required" => "1", "class"=>$field['field_css_class'], "placeholder" => $field['placeholder'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
                } else {
                    /* Get widget data in field options format to comply with existing field structure */
                    $login_model = new RM_Login_Fields();
                    $login_model->initialize($field['field_type']);
                    $field_object = (object) $login_model->get_as_field_options($field);
                    $field_object->field_value = isset($field_object->field_value) ? $field_object->field_value : '';
                    $field_object->field_type = $field['field_type'];

                    // Create field factory object 
                    $field_factory = defined('REGMAGIC_ADDON') ? RM_Field_Factory_Addon($field_object, array(), false, true) : new RM_Field_Factory($field_object, array(), false, true);
                    $field_factory->set_field_options($field_object);

                    $method = "create_" . strtolower($field_object->field_type) . "_field";
                    $form->addElement($field_factory->$method()->get_pfbc_field());
                }
            }
        }

        $form->addElement(new Element_HTML('<div class="rmrow"><div class="rmfield" for="rm_login_form-element-3"></div><div class="rminput"><ul class="rmradio" style="list-style:none;"><li class="rm-login-remember"> <input id="rm_login_form-element-3-0" type="checkbox" name="remember[]" value="1"><label for="rm_login_form-element-3-0"><span>'.__('Remember Me','custom-registration-form-builder-with-submission-manager').'</span></label> </li> </ul></div></div>'));


        /*
         * Checking if recpatcha is enabled
         */
        if (get_option('rm_option_enable_captcha') == "yes" && !empty($data->show_captcha))
            $form->addElement(new Element_Captcha());


        if($data->buttons['align']=='left' || $data->buttons['align']=='right'){
            if(empty($data->design['style_btnfield'])){
                $data->design['style_btnfield']='float:'.$data->buttons['align'];
            }
            else
            {
                $data->design['style_btnfield']=$data->design['style_btnfield'].';float:'.$data->buttons['align'];
            }
        }
        $option_model= new RM_Options();
        $default_registration_url= $option_model->get_value_of('default_registration_url');

        if(!empty($data->buttons['display_register']) && !empty($default_registration_url) && $data->buttons['align']=='right'){
            $reg_btn_label= !empty($data->buttons['register_btn']) ? $data->buttons['register_btn'] : RM_UI_Strings::get('LABEL_REGISTER');
            $reg_url= get_permalink($default_registration_url);
            $form->addElement(new Element_Button($reg_btn_label, "button", array("id" => "rm_register_btn", "class" => "rm_btn",'onclick'=>"location.href='$reg_url'",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
        }

        $btn_label= !empty($data->buttons['login_btn'])?$data->buttons['login_btn']:RM_UI_Strings::get('LABEL_LOGIN');
        $form->addElement(new Element_Button($btn_label, "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));


        if(!empty($data->buttons['display_register']) && !empty($default_registration_url) && $data->buttons['align']!='right'){
            $reg_btn_label= !empty($data->buttons['register_btn']) ? $data->buttons['register_btn'] : RM_UI_Strings::get('LABEL_REGISTER');
            $reg_url= get_permalink($default_registration_url);
            $form->addElement(new Element_Button($reg_btn_label, "button", array("id" => "rm_register_btn", "class" => "rm_btn",'onclick'=>"location.href='$reg_url'",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
        }


        if(!empty($data->en_pwd_recovery)){
            if($data->recovery_link_text === '{{AUTO}}')
                $data->recovery_link_text = __('Lost your password?','custom-registration-form-builder-with-submission-manager');
            $form->addElement(new Element_HTML('<div class="rm_forgot_pass"><a href="'.get_permalink($data->recovery_page).'" target="blank">' . $data->recovery_link_text . '</a></div>'));
        }


        /*
         * Render the form if user is not logged in
         */
        ?>
        <div class='rmagic'>    
            <div class='rmcontent rm-login-wrapper'>
                <?php if (!is_user_logged_in() || (isset($_GET['form_prev']) && $_GET['form_prev']==1 && isset($_GET['form_type']) && $_GET['form_type']=='login')) : ?>
                    <div class="rm-thirdp-login-button-wrap">
                    <?php
                        echo html_entity_decode(wp_kses($data->facebook_html,RM_Utilities::expanded_allowed_tags()));
                        if(defined('REGMAGIC_ADDON')) {
                            echo html_entity_decode(wp_kses($data->google_html,RM_Utilities::expanded_allowed_tags()));
                            echo html_entity_decode(wp_kses($data->linkedin_html,RM_Utilities::expanded_allowed_tags()));
                            echo html_entity_decode(wp_kses($data->windows_html,RM_Utilities::expanded_allowed_tags()));
                            echo html_entity_decode(wp_kses($data->twitter_html,RM_Utilities::expanded_allowed_tags()));
                            echo html_entity_decode(wp_kses($data->instagram_html,RM_Utilities::expanded_allowed_tags()));
                        }
                    ?>
                    </div>
                    <?php
                    $form->render();
                    ?>

                <?php else : ?> 
                    <?php include('template_rm_logged_in_view.php'); ?>
                <?php endif; ?>
            </div>
        </div> 

    <?php } ?>

<?php } ?>

<?php
/*
if(!empty($data->hidden_forms)){
    foreach($data->hidden_forms as $forms_id){
        echo '<script>jQuery(document).ready(function(){jQuery("#'.$forms_id.'").html("<div class=\'rm-login-attempted-notice\'>'.__('Note: You are already attempting login using a different login form on this page. To keep your logging experience simple and secure, this login form in no longer accessible. Please continue the login process using the form with which you attempted login before the page refresh.','custom-registration-form-builder-with-submission-manager').'</div>")});</script>';
    }
}
*/