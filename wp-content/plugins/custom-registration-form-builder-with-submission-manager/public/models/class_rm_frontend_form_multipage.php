<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Multipage extends RM_Frontend_Form_Base
{

    public $form_pages;
    public $ordered_form_pages;

    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);

        if ($this->form_options->form_pages == null)
        {
            $this->form_pages = array('Page 1');
            if(defined('REGMAGIC_ADDON'))
                $this->ordered_form_pages = array(0);
        }
        else
        {
            $this->form_pages = $this->form_options->form_pages;
            if(defined('REGMAGIC_ADDON')) {
                if ($this->form_options->ordered_form_pages == null)
                    $this->ordered_form_pages = array_keys($this->form_pages);
                else
                    $this->ordered_form_pages = $this->form_options->ordered_form_pages;
            }
        }
    }

    public function get_form_pages()
    {
        return $this->form_pages;
    }

    public function pre_sub_proc($request, $params)
    {
        return true;
    }

    public function post_sub_proc($request, $params)
    {
        return true;
    }
    
    //Following two methods can be overloaded by child classes in order to add custom fields to any page of the form.
    public function hook_pre_field_addition_to_page($form, $page_no)
    {
        
    }
    
    public function hook_post_field_addition_to_page($form, $page_no, $editing_sub=null)
    {
        
    }
    
    public function render($data = array())
    {
        global $rm_form_diary;
        if(defined('REGMAGIC_ADDON'))
            $editing_sub=false;
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        $class= "rm_theme_{$theme} rm_layout_{$layout}";

        if(!empty($this->rows)) {
            $view = new View_UserRowsForm;
        } else {
            $view = ($layout == 'two_columns') ? new View_UserFormTwoCols: new View_UserForm;
        }
        
        $btn_align_class = "rmagic-form-btn-".(isset($this->form_options->form_btn_align)?$this->form_options->form_btn_align:"center");
        echo '<div class="rmagic '.esc_attr($class).'">';
        
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "", /*add_query_arg('rmcb', time()),*/
            "class" => "rmagic-form $btn_align_class",
            "name"=>"rm_form",
            "view" => $view,
            "name" => "rm_form",
            "number" => $this->form_number,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

        //check if form has expired
        $edit_submission= false;
        if($_POST && !empty($_POST['rm_slug']) && $_POST['rm_slug']=='rm_user_form_edit_sub'){
             $edit_submission= true;
        }
        if (!$this->preview && empty($edit_submission) && $this->is_expired())
        {
            if ($this->form_options->form_message_after_expiry)
                echo wp_kses_post($this->form_options->form_message_after_expiry);
            else
                echo '<div class="rm-no-default-from-notification">'.wp_kses_post(RM_UI_Strings::get('MSG_FORM_EXPIRY')).'</div>';
            echo '</div>';
            return;
        }

        if (isset($data['stat_id']) && $data['stat_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
            $form->addElement(new Element_Textbox('RM_Stats', 'stat_id', array('value' => $data['stat_id'], 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
            if(defined('REGMAGIC_ADDON'))
                $editing_sub=false;
        }
        
        if (isset($data['submission_id']) && $data['submission_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
            $form->addElement(new Element_Textbox('RM_Slug', 'rm_slug', array('value' => 'rm_user_form_edit_sub', 'style' => 'display:none')));
            $form->addElement(new Element_Textbox('RM_form_id', 'form_id', array('value' => $this->form_id, 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
            if(defined('REGMAGIC_ADDON'))
                $editing_sub=true;
        }
        
        parent::pre_render();
        if(defined('REGMAGIC_ADDON')) {
            //$this->base_render($form,$editing_sub);
            $this->prepare_fields_for_render($form,$edit_submission);

            if (get_option('rm_option_enable_captcha') == "yes" && $this->form_options->enable_captcha[0]=='yes')
                $form->addElement(new Element_Captcha());

            $this->prepare_button_for_render($form,$edit_submission);

            if (count($this->fields) !== 0)
                $form->render();
            else
                echo wp_kses_post(RM_UI_Strings::get('MSG_NO_FIELDS'));
        } else {
            $this->base_render($form);
        }
        parent::post_render();
        echo '</div>';
    }
    
    public function get_form_object($data = array())
    {
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        $class= "rm_theme_{$theme} rm_layout_{$layout}";

        if(!empty($this->rows)) {
            $view = new View_UserRowsForm;
        } else {
            $view = ($layout == 'two_columns') ? new View_UserFormTwoCols: new View_UserForm;
        }
       
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form_model = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);

        $form_model->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "",
            "class" => "rmagic-form",
            "name" => "rm_form",
            "number" => $this->form_number,
            "view" => $view,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form_model->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

        $form_model->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
        $form_model->addElement(new Element_Textbox('RM_Stats', 'stat_id', array('value' => "__form_model", 'style' => 'display:none')));
        $form_model->addElement(new Element_HTML('</div>'));
        
        //Since pre-render only adds style and expiry countdown no need to call it.
        //parent::pre_render();
        //$this->base_render($form_model);
        $this->prepare_fields_for_render($form_model);
        if (get_option('rm_option_enable_captcha') == "yes" && $this->form_options->enable_captcha[0]=='yes')
            $form_model->addElement(new Element_Captcha());
        //$this->prepare_button_for_render($form_model);
        //Nothing special in post render for now, do not call.
        //parent::post_render();
        return $form_model;
    }
    
    public function prepare_fields_for_render($form,$editing_sub=null)
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_multipage = new RM_Frontend_Form_Multipage_Addon();
            return $addon_form_multipage->prepare_fields_for_render($form,$this,$editing_sub);
        }
        
        if(isset($_GET['form_prev']) && current_user_can('manage_options')) {
            if($this->form_type == 0) {
                $form->addElement(new Element_HTML("<div class=\"rm-form-preview-notice\">".__("Since you are already logged in, Email Field cannot be edited. <span>This message is only visible to site admin.</span>", 'custom-registration-form-builder-with-submission-manager')."</div>"));
            } else {
                if($this->form_options->hide_username == 1 && isset($this->fields['pwd'])) {
                    $form->addElement(new Element_HTML("<div class=\"rm-form-preview-notice\">".__("Since you are already logged in, Email and Password Fields cannot be edited. <span>This message is only visible to site admin.</span>", 'custom-registration-form-builder-with-submission-manager')."</div>"));
                } elseif($this->form_options->hide_username == 0 && isset($this->fields['pwd'])) {
                    $form->addElement(new Element_HTML("<div class=\"rm-form-preview-notice\">".__("Since you are already logged in, Username, Email and Password Fields cannot be edited. <span>This message is only visible to site admin.</span>", 'custom-registration-form-builder-with-submission-manager')."</div>"));
                } elseif($this->form_options->hide_username == 1 && !isset($this->fields['pwd'])) {
                    $form->addElement(new Element_HTML("<div class=\"rm-form-preview-notice\">".__("Since you are already logged in, Email Field cannot be edited. <span>This message is only visible to site admin.</span>", 'custom-registration-form-builder-with-submission-manager')."</div>"));
                } elseif($this->form_options->hide_username == 0 && !isset($this->fields['pwd'])) {
                    $form->addElement(new Element_HTML("<div class=\"rm-form-preview-notice\">".__("Since you are already logged in, Username and Email Fields cannot be edited. <span>This message is only visible to site admin.</span>", 'custom-registration-form-builder-with-submission-manager')."</div>"));
                }
            }
        }
        
        //foreach ($this->form_pages as $k => $page)
        {$i = 1;//actual page no.
            //if ($i == 1)
            {$n=1;
                $form->addElement(new Element_HTML("<div class=\"rm_form_page rmformpage_form_".$this->form_id."_".$this->form_number."\" id=\"rm_form_page_form_".$this->form_id ."_".$this->form_number. "_".$n."\">"));
                $form->addElement(new Element_HTML("<fieldset class='rmfieldset'>"));
                $this->hook_pre_field_addition_to_page($form, $i);
                    if(!empty($this->rows)) {
                        foreach($this->rows as $row) {
                            /*
                            if($row->page_no != $i) {
                                continue;
                            }
                            */
                            if(!empty($row->fields)) {
                                $class_attr = !empty($row->class) ? 'rmagic-row ' . $row->class : 'rmagic-row';
                                $style_attr = '';
                                if(!empty($row->bmargin))
                                    $style_attr = $style_attr . 'margin-bottom:'. $row->bmargin . 'px;';
                                if(!empty($row->width))
                                    $style_attr = $style_attr . 'max-width:'. $row->width . 'px;';
                                $form->addElement(new Element_HTML('<div class="'.$class_attr.'" style="'.$style_attr.'" data-bmargin="'. $row->bmargin . '">'));
                                if(!empty($row->heading))
                                    $form->addElement(new Element_HTML('<div class="rmagic-heading">'.$row->heading.'</div>'));
                                if(!empty($row->subheading))
                                    $form->addElement(new Element_HTML('<div class="rmagic-subheading">'.$row->subheading.'</div>'));
                                if($row->gutter == 10)
                                    $form->addElement(new Element_HTML('<div class="rmagic-fields-wrap">'));
                                else
                                    $form->addElement(new Element_HTML('<div class="rmagic-fields-wrap rm-gutter-'.$row->gutter.'">'));
                                switch($row->columns) {
                                    case '1':
                                        $col_class = 'rmagic-col-12';
                                        break;
                                    case '1:1':
                                        $col_class = 'rmagic-col-6';
                                        break;
                                    case '2:1':
                                        $col_class = 'rmagic-col-4';
                                        break;
                                    case '1:1:1':
                                        $col_class = 'rmagic-col-4';
                                        break;
                                    case '1:1:1:1':
                                        $col_class = 'rmagic-col-3';
                                        break;
                                    default:
                                        $col_class = 'rmagic-col-3';
                                        break;
                                }
                                $field_counter = 1;
                                foreach($row->fields as $field) {
                                    if($row->columns == '2:1' && $field_counter == 1)
                                        $form->addElement(new Element_HTML('<div class="rmagic-col rmagic-col-8">'));
                                    else
                                        $form->addElement(new Element_HTML('<div class="rmagic-col '.$col_class.'">'));
                                    if(empty($field)) {
                                        $form->addElement(new Element_HTML("</div>"));
                                        $field_counter++;
                                        continue;
                                    }
                                    if(is_array($field)) {
                                       foreach($field as $single_field) {
                                           $pf = $single_field->get_pfbc_field();
                                           if ($pf === null || $single_field->get_page_no() != $i)
                                                continue;
                                           if (is_array($pf)) {
                                               foreach ($pf as $f) {
                                                   if (!$f)
                                                       continue;
                                                   $form->addElement($f);
                                                }
                                            } else
                                                $form->addElement($pf);
                                       }
                                       continue;
                                    }
                                    $pf = $field->get_pfbc_field();                            
                                    if (!$pf)
                                        continue;

                                    if (is_array($pf)) {
                                        foreach ($pf as $f) {
                                            if (!$f)
                                                continue;
                                            $form->addElement($f);
                                        }
                                    } else
                                        $form->addElement($pf);
                                    $form->addElement(new Element_HTML("</div>"));
                                    $field_counter++;
                                }
                                $form->addElement(new Element_HTML("</div>"));
                                $form->addElement(new Element_HTML("</div>"));
                            }
                        }
                    } else {
                        foreach($this->fields as $field) {
                            if(is_array($field)) {
                               foreach($field as $single_field) {
                                   $pf = $single_field->get_pfbc_field();
                                   if ($pf === null)
                                       continue;

                                   if (is_array($pf)) {
                                       foreach ($pf as $f) {
                                           if (!$f)
                                               continue;
                                           $form->addElement($f);
                                       }
                                   } else
                                        $form->addElement($pf);
                               }
                               continue;
                            }
                            $pf = $field->get_pfbc_field();                            
                            if ($pf === null || $field->get_page_no() != $i)
                                continue;

                            if (is_array($pf)) {
                                foreach ($pf as $f) {
                                    if (!$f)
                                        continue;
                                    $form->addElement($f);
                                }
                            } else
                                $form->addElement($pf);
                        }
                    }
                    
                    $this->hook_post_field_addition_to_page($form, $i);
                    $form->addElement(new Element_HTML("</fieldset>"));
                    $form->addElement(new Element_HTML("</div>")); 
            } 
            
        }

        
    }
    
    public function prepare_button_for_render($form,$editing_sub=null)
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_multipage = new RM_Frontend_Form_Multipage_Addon();
            return $addon_form_multipage->prepare_button_for_render($form,$this,$editing_sub);
        }
        if ($this->service->get_setting('theme') != 'matchmytheme')
        {
            if(isset($this->form_options->style_btnfield))
                unset($this->form_options->style_btnfield);
        }
        
        $sub_btn_label = $this->form_options->form_submit_btn_label ? $this->form_options->form_submit_btn_label : "Submit";
        if(isset($_POST['rm_slug']) && $_POST['rm_slug']=='rm_user_form_edit_sub'){
            $sub_btn_label = __('Update','custom-registration-form-builder-with-submission-manager');
        }

        $form->addElement(new Element_Button(stripslashes($sub_btn_label), "submit", array(
"style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null,"name"=>"rm_sb_btn","id"=>"rm_next_form_page_button_".$this->form_id.'_'.$this->form_number,"class"=>"rm_next_btn")));
        $form->addElement(new Element_Button(stripslashes($sub_btn_label), "submit", array(
"style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null,"name"=>"rm_sb_btn","class"=>"rm_noscript_btn")));
        $this->insert_JS($form);
    }
    
    public function get_jqvalidator_config_JS()
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_multipage = new RM_Frontend_Form_Multipage_Addon();
            return $addon_form_multipage->get_jqvalidator_config_JS($this);
        }
        $str = "jQuery.validator.setDefaults({errorClass: 'rm-form-field-invalid-msg',
            ignore:':hidden,.ignore,:not(:visible),.rm_untouched', wrapper:'div',
            errorPlacement: function(error, element) {
                var elementId= element.attr('id');
                if(elementId){
                    var target_element_id= elementId.replace('-error','');
                    var target_element= jQuery(\"#\" + target_element_id);
                    if(target_element.length>0){
                        if(target_element.hasClass('rm_untouched')){
                            return true;
                        }
                    }
                }
                error.appendTo(element.closest('div'));
            }
        });";
        return $str;
    }

    public function insert_JS($form)
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_multipage = new RM_Frontend_Form_Multipage_Addon();
            return $addon_form_multipage->insert_JS($form,$this);
        }
        if(is_admin()) // Restricting front js loading in dashboard.
            return;
        
        $max_page_count = 1;
        $form_identifier = "form_".$this->get_form_id();
        $form_id = $this->get_form_id();
        $validator_js = $this->get_jqvalidator_config_JS();
        
        $jqvalidate = RM_Utilities::enqueue_external_scripts('rm_jquery_validate', RM_BASE_URL."public/js/jquery.validate.min.js", array('jquery'));
        $jqvalidate .= RM_Utilities::enqueue_external_scripts('rm_jquery_validate_add', RM_BASE_URL."public/js/additional-methods.min.js", array('jquery'));
        $jq_front_form_script = RM_Utilities::enqueue_external_scripts('rm_front_form_script', RM_BASE_URL."public/js/rm_front_form.js", array('jquery'));
        wp_enqueue_script('rm_front');
        wp_enqueue_script('rm_jquery_conditionalize');
        echo $jqvalidate.$jq_front_form_script;
        echo '<pre class="rm-pre-wrapper-for-script-tags"><script>
                
if (typeof window[\'rm_multipage\'] == \'undefined\') {

    rm_multipage = {
        global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).': 1
    };

}
else
 rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).' = 1;

function gotonext_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'(){
        /* Making sure action attribute is empty */
        jQuery("form.rmagic-form").attr("action","");
        maxpage = '.esc_html($max_page_count).' ;
        var form_object= jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).').closest("form");
        var submit_btn= form_object.find("[type=submit]:not(.rm_noscript_btn)");
        '.$validator_js.'
        form_object.find(\'input.rm_price_field_quantity\').each(function() {
            if(typeof jQuery(this).attr(\'min\') == \'undefined\') {
                jQuery(this).attr(\'min\', 0);
            }
        });
        if(form_object.find(\'.rm_privacy_cb\').is(\':visible\')) {
            if(!form_object.find(\'.rm_privacy_cb\').prop(\'checked\')){
                form_object.find(\'.rm_privacy_cb\').trigger(\'change\');
                return false;
            }
        }
        if(jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input").length > 0)
        {
            var elements_to_validate= jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input");
            var valid = elements_to_validate.valid();
            elements_to_validate.each(function(){
            var if_mobile= jQuery(this).attr(\'data-mobile-intel-field\');
                if(if_mobile){
                    var tel_error= rm_toggle_tel_error(jQuery(this).intlTelInput(\'isValidNumber\'),jQuery(this),jQuery(this).data(\'error-message\'));
                    if(tel_error){
                        valid= false;
                    }
                    else
                    {
                        jQuery(this).val(jQuery(this).intlTelInput(\'getNumber\'));
                    }
                }
            });
                
            if(!valid)
            {
                setTimeout(function(){ submit_btn.prop(\'disabled\',false); }, 1000);
                var error_element= jQuery(document).find("input.rm-form-field-invalid-msg")[0];
                if(error_element){
                    error_element.focus();
                }
                return false;
            }
        }
        
        /* Server validation for Username and Email field */
        for(var i=0;i<rm_validation_attr.length;i++){
            var validation_flag= true;
            jQuery("[" + rm_validation_attr[i] + "=0]").each(function(){
               validation_flag= false;
               return false;
            });
            
            if(!validation_flag)
              return;
        }
        
        
        rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'++;
        
        /*skip blank form pages*/
        while(jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input").length == 0)
        {
        
            if(maxpage <= rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).')
            {
                    if(jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input").length == 0){
                        jQuery("#rm_next_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'type\',\'submit\');
                        jQuery("#rm_prev_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'disabled\',true);
                        return;
                    }        
                    else
                        break;
            }
        
            rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'++;
        }
            
	
	if(maxpage < rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).')
	{
		rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).' = maxpage;
		jQuery("#rm_next_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'type\',\'submit\');
                jQuery("#rm_prev_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'disabled\',true);
		return;
	}
	jQuery(".rmformpage_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'").each(function (){
		var visibledivid = "rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).';
		if(jQuery(this).attr(\'id\') == visibledivid)
			jQuery(this).show();
		else
			jQuery(this).hide();
	});
        jQuery(\'html, body\').animate({
            scrollTop: (jQuery(\'.rmformpage_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'\').first().offset().top)
        },500);
        jQuery("#rm_prev_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'disabled\',false);
        rmInitGoogleApi();
}
function gotoprev_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'(){
	
	maxpage = '.esc_html($max_page_count).' ;
        var form_object= jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).').closest("form");
	rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'--;
        jQuery("#rm_next_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").attr(\'type\',\'button\');        
        if(form_object.find(\'.rm_privacy_cb\').is(\':visible\')) {
            if(!form_object.find(\'.rm_privacy_cb\').prop(\'checked\')){
                form_object.find(\'.rm_privacy_cb\').trigger(\'change\');
                return false;
            }
        } 
        /*skip blank form pages*/
        while(jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input").length == 0)
        {
            if(1 >= rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).')
            {
                    if(jQuery("#rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'+" :input").length == 0){
                        rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).' = 1;
                        jQuery("#rm_prev_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'disabled\',true);
                        return;
                    }        
                    else
                        break;
            }
        
            rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'--;
        }
        
	jQuery(".rmformpage_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'").each(function (){
		var visibledivid = "rm_form_page_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'_"+rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).';
		if(jQuery(this).attr(\'id\') == visibledivid)
			jQuery(this).show();
		else
			jQuery(this).hide();
	});
        
        if(rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).' <= 1)
        {
            rm_multipage.global_page_no_'.esc_html($form_identifier).'_'.esc_html($this->form_number).' = 1;
            jQuery("#rm_prev_form_page_button_'.esc_html($form_id).'_'.esc_html($this->form_number).'").prop(\'disabled\',true);
        }
        jQuery(\'html, body\').animate({
            scrollTop: (jQuery(\'.rmformpage_'.esc_html($form_identifier).'_'.esc_html($this->form_number).'\').first().offset().top)
        },500);
}         
</script></pre>';
        //$str = $jqvalidate.$jq_front_form_script.$str;
        //$form->addElement(new Element_HTML($str));
    }

}
