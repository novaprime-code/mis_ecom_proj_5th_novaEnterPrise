<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Contact extends RM_Frontend_Form_Multipage//RM_Frontend_Form_Base
{

    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);
        $this->set_form_type(RM_CONTACT_FORM);
    }

    public function pre_sub_proc($request, $params)
    {
        return true;
    }

    public function post_sub_proc($request, $params)
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_contact = new RM_Frontend_Form_Contact_Addon();
            return $addon_form_contact->post_sub_proc($request, $params, $this);
        }
        if(isset($params['paystate']) && $params['paystate'] != 'post_payment')      
            if ($this->service->get_setting('enable_mailchimp') == 'yes')
            {
                if($this->form_options->form_is_opt_in_checkbox == 1 || (isset($this->form_options->form_is_opt_in_checkbox[0]) && $this->form_options->form_is_opt_in_checkbox[0] == 1))
                {
                    if(isset($request['rm_subscribe_mc']))
                        $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
                }
                else
                    $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
            }

        return null;
    }

    public function hook_post_field_addition_to_page($form, $page_no, $editing_sub=null)
    {
        if(defined('REGMAGIC_ADDON')) {
            $addon_form_contact = new RM_Frontend_Form_Contact_Addon();
            return $addon_form_contact->hook_post_field_addition_to_page($form, $page_no, $this, $editing_sub);
        }
        //if (count($this->form_pages) == $page_no)
        { 
            if ($this->has_price_field())
                $this->add_payment_fields($form);
            
            if (get_option('rm_option_enable_captcha') == "yes")
                $form->addElement(new Element_Captcha());
            if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->form_is_opt_in_checkbox == 1)
            {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                if($this->form_options->form_opt_in_default_state == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                else 
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));
            
                $form->addElement(new Element_HTML('</div>'));
            }
            
            if($this->form_options->show_total_price && $this->has_price_field())
            {
                $gopts = new RM_Options;
                $total_price_localized_string = RM_UI_Strings::get('FE_FORM_TOTAL_PRICE');
                $curr_symbol = $gopts->get_currency_symbol();
                $curr_pos = $gopts->get_value_of('currency_symbol_position');
                $price_formatting_data = json_encode(array("loc_total_text" => $total_price_localized_string, "symbol" => $curr_symbol, "pos" => $curr_pos));
                $form->addElement(new Element_HTML("<div class='rmrow rm_total_price' style='{$this->form_options->style_label}' data-rmpriceformat='$price_formatting_data'></div>"));
            }
            
        }
    }

    public function base_render($form,$editing_sub=null)
    {
        $this->prepare_fields_for_render($form,$editing_sub);
        
        $this->prepare_button_for_render($form,$editing_sub);

        if (count($this->fields) !== 0)
            $form->render();
        else
            echo wp_kses_post(RM_UI_Strings::get('MSG_NO_FIELDS'));
    }

    public function get_prepared_data_primary($request)
    {
        $data = array();

        foreach ($this->fields as $field)
        {
            if(is_array($field) && $field[0]->is_primary())
                $field = $field[0];
            if ($field->get_field_type() == 'Email' && $field->is_primary())
            {
                $field_data = $field->get_prepared_data($request);

                $data['user_email'] = (object) array('label' => $field_data->label,
                            'value' => $field_data->value,
                            'type' => $field_data->type);

                break;
            }
        }
        return $data;
    }

    public function get_prepared_data_dbonly($request,$fields=null)
    {
        $data = array();

        if(!empty($this->rows)) {
            foreach ($this->rows as $row) {
                if(!empty($row->fields)) {
                    foreach($row->fields as $field) {
                        if(!empty($field)) {
                            //if (in_array($field->get_field_type(),array('Spacing','Timer')))
                            if (in_array($field->get_field_type(),RM_Utilities::csv_excluded_widgets()))
                            {
                                continue;
                            }
                            $field_data = $field->get_prepared_data($request);

                            if ($field_data === null)
                                continue;

                            $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                                                                          'value' => $field_data->value,
                                                                          'type' => $field_data->type);
                        }
                    }
                }
            }
        } else {
            foreach ($this->fields as $field)
            {
                if(!empty($field)) {
                    //if (in_array($field->get_field_type(),array('Spacing','Timer')))
                    if (in_array($field->get_field_type(),RM_Utilities::csv_excluded_widgets()))
                    {
                        continue;
                    }

                    $field_data = $field->get_prepared_data($request);
                   /* if($field->get_field_type()=="HTMLCustomized"){
                       $html_field= new RM_Fields();
                       $html_field->load_from_db($field->get_field_id());
                       $field_data->value= $html_field->get_field_value();

                       if(strtolower($html_field->get_field_type())=="link")
                       {    
                            $field_options=  $html_field->field_options;
                            $field_data->value= $html_field->field_options->link_type=="url" ? $html_field->field_options->link_href : get_permalink($html_field->field_options->link_page);
                       }
                    }*/

                    if ($field_data === null)
                        continue;

                    $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                                                                  'value' => $field_data->value,
                                                                  'type' => $field_data->type);
                }
            }
        }

        return $data;
    }
    
    // Adding tax for contact forms
    public function get_pricing_detail($request) {
        $data = parent::get_pricing_detail($request);
        $price_flag = false;

        if ($data === null) {
            $data = new stdClass;
            $data->billing = array();
            $data->total_price = 0.0;
            $data->tax = 0.0;
        } else
            $price_flag = true;
        
        $tax_enabled = get_site_option('rm_option_enable_tax', null);
        if($tax_enabled == 'yes' && $data->total_price > 0) {
            $tax_type = get_site_option('rm_option_tax_type', null);
            if($tax_type == 'fixed') {
                $data->tax = round(floatval(get_site_option('rm_option_tax_fixed', null)),2);
                $data->total_price = round($data->total_price + $data->tax, 2);
            } elseif($tax_type == 'percentage') {
                $tax_per = round(floatval(get_site_option('rm_option_tax_percentage', null)),2);
                $data->tax = round(($data->total_price * $tax_per)/100, 2);
                $data->total_price = round($data->total_price + $data->tax, 2);
            }
        } else {
            $data->tax = 0.0;
        }

        return $price_flag ? $data : null;
    }
    
    public function get_jqvalidator_config_JS() {
        if(!is_user_logged_in()) {
            $email_match_error = RM_UI_Strings::get("ERR_EMAIL_MISMATCH");
            $rm_service= new RM_Services();
            $email_field= $rm_service->get_primary_field_options('email',$this->form_id);

            if(!empty($email_field)){
                $field_options= maybe_unserialize($email_field->field_options);
                if(!empty($field_options->email_mismatch_err))
                    $email_match_error=$field_options->email_mismatch_err;
            }

            $form_num = $this->form_number;
            $form_id = $this->form_id;
            $form_counter= RM_Public::$form_counter;
            $str = "jQuery.validator.setDefaults({errorClass: 'rm-form-field-invalid-msg',
                ignore:':hidden,.ignore,:not(:visible),.rm_untouched',wrapper:'div',
                errorPlacement: function(error, element) {
                    //error.appendTo(element.closest('.rminput'));
                    error.appendTo(element.closest('div'));
                },
                rules: {
                    email_confirmation: {
                        required: true,
                        equalTo: \"#rm_reg_form_email_{$form_id}_{$form_counter}\"
                    }
                },
                messages: {
                    email_confirmation: {
                        equalTo: \"{$email_match_error}\"
                    }
                }
            });";
            return $str;
        } else {
            return parent::get_jqvalidator_config_JS();
        }
    }

}