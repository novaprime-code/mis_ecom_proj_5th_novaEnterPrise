<?php

/* 
 * Creates an object of a form after loading from databse.
 */

class RM_Form_Factory
{
    public $form_id;
    public $backend_form;
    public $backend_field;
    public $frontend_form;
    public $service;
    
    public function __construct()
    {
        $this->form_id = null;
        $this->backend_form = null;
        $this->backend_field = null;
        $this->frontend_form = null;
        $this->service = new RM_Front_Form_Service;
    }
    
    public function create_form($form_id)
    {
        //Load form from database
        $this->backend_form = new RM_Forms;
        $this->backend_form->load_from_db($form_id);
        
        //Update form diary
        global $rm_form_diary;
        if(isset($rm_form_diary[$form_id]))
            $rm_form_diary[$form_id]++;
        else
            $rm_form_diary[$form_id] = 1;
        
        $rm_service= new RM_Services();
        $primary_field_req_names = array();
        //Load corresponding fields from db
        $rows = array();
        $fields = array();
        $db_rows = $this->service->get_all_form_rows($form_id);
        $db_fields = $this->service->get_all_form_fields($form_id);
        if(!empty($db_rows)) {
            foreach($db_rows as $db_row) {
                $db_fields_for_row = $this->service->get_all_fields_by_row($db_row);
                $fields_for_row = array();
                if(!empty($db_fields_for_row)) {
                    foreach($db_fields_for_row as $index => $db_field) {
                        if(empty($db_field)) {
                            $fields_for_row['EmptyField_'.$index] = NULL;
                            continue;
                        }
                        $field_options = maybe_unserialize($db_field->field_options);
                        if(!is_object($field_options)){
                            $field_options = new StdClass();
                        }
                        $form_options = $this->backend_form->get_form_options();

                        //if(isset($form_options->style_textfield)&& $form_options->style_textfield!=""){
                        if(isset($form_options->style_textfield)){  
                            $field_options->style_textfield = $form_options->style_textfield;
                        }
                        if(isset($form_options->style_label)){
                            $field_options->style_label = $form_options->style_label;
                        }

                        $opts = $this->service->set_properties($field_options);
                        $field_factory= new RM_Field_Factory($db_field,$opts);
                        $field_name= $db_field->field_type."_".$db_field->field_id;
                        $field_type= str_replace('-','',strtolower($db_field->field_type));
                        
                        //remove required check if file field is being edited
                        if($field_type === 'file'){
                            if(isset($opts['required']))
                                unset($opts['required']);
                        }

                        $field_factory= new RM_Field_Factory($db_field,$opts);

                        // Check if this is primary email field
                        if($field_type=='email'){
                            if($db_field->is_field_primary){
                                $primary_field_req_names['user_email'] =  $db_field->field_type."_".$db_field->field_id;
                                $email_fields= $field_factory->create_email_field();
                                if(is_array($email_fields)) {
                                    $fields_for_row[$primary_field_req_names['user_email']]= $email_fields[0];
                                    if(isset($email_fields[1])){
                                        $fields_for_row['email_confirmation']= $email_fields[1];
                                    }
                                    continue;
                                }
                            }
                        }

                        //if((is_user_logged_in() || !empty($form_options->hide_username)) && $field_type=='username'){
                            //if(!(is_super_admin() && !empty($_GET['form_prev']))){
                        if(!empty($form_options->hide_username) && $field_type=='username'){
                            //if(empty($_GET['form_prev'])){
                                continue;
                            //}
                        }

                        if($field_type=='userpassword'){
                            //if(is_user_logged_in()){
                                //if(!(is_super_admin() && !empty($_GET['form_prev']))){
                            //if(!empty($_GET['form_prev'])){
                                //continue;
                            //}
                            $password_fields=   $field_factory->create_userpassword_field();
                            if(is_array($password_fields)){
                                $fields_for_row['pwd']= $password_fields[0];
                                if(isset($password_fields[1])){
                                    $fields_for_row['password_confirmation']=   $password_fields[1];
                                }
                            }
                            continue;
                        } else if($field_type=='wcbillingphone'){
                            /*
                            $billing_phone_fields =   $field_factory->create_wcbillingphone_field();
                            if(is_array($billing_phone_fields)){
                                //echo '<pre>';print_r($field_options);echo '</pre>';
                                $i=1;
                                foreach($billing_phone_fields as $billing_phone_field){
                                    $fields['billingphone_'.$i]= $billing_phone_field;
                                    $i++;
                                }
                            }
                            continue;
                            */
                        } else if($field_type=='wcbilling' && !class_exists( 'WooCommerce' )){
                            continue;
                        } else if($field_type=='wcshipping' && !class_exists( 'WooCommerce' )){
                            continue;
                        } else if($field_type=='pgavatar' && !class_exists( 'Profile_Magic' )){
                            continue;
                        }

                        if(isset($field_options->rm_widget) && $field_options->rm_widget==1){ 
                            if(is_callable(array($field_factory,"create_".$field_type."_widget")))
                             $fields_for_row[$field_name] = call_user_func_array(array($field_factory,"create_".$field_type."_widget"),array());
                            continue;
                        }
                        /*
                        if(isset($field_options->field_is_admin_only) && !current_user_can('manage_options')) {
                            continue;
                        }
                        */
                        if(is_callable(array($field_factory,"create_".$field_type."_field"))):
                            $fields_for_row[$field_name] = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
                        else:
                            $fields_for_row[$field_name] = call_user_func_array(array($field_factory,"create_default_field"),array());
                        endif;
                    }
                }
                $db_row->fields = $fields_for_row;
            }
        }
        if($db_fields)
        {
            foreach($db_fields as $db_field)
            {
                $field_options = maybe_unserialize($db_field->field_options);
                if(!is_object($field_options)){
                    $field_options = new StdClass();
                }
                $form_options = $this->backend_form->get_form_options();

                if(isset($form_options->style_textfield)){
                    $field_options->style_textfield = $form_options->style_textfield;
                }
                if(isset($form_options->style_label)){
                    $field_options->style_label = $form_options->style_label;
                }
                
                $opts = $this->service->set_properties($field_options);
                $field_name= $db_field->field_type."_".$db_field->field_id;
                $field_type= str_replace('-','',strtolower($db_field->field_type));
                
                //remove required check if file field is being edited
                if($field_type === 'file'){
                    if(isset($opts['required']))
                        unset($opts['required']);
                }
                
                $field_factory= new RM_Field_Factory($db_field,$opts);
                
                // Check if this is primary email field
                if($field_type=='email'){
                    if($db_field->is_field_primary){
                        $primary_field_req_names['user_email'] =  $db_field->field_type."_".$db_field->field_id;
                        $email_fields= $field_factory->create_email_field();
                        if(is_array($email_fields)) {
                            $fields[$primary_field_req_names['user_email']]= $email_fields[0];
                            if(isset($email_fields[1])){
                                $fields['email_confirmation']= $email_fields[1];
                            }
                            continue;
                        }
                    }
                }
                
                if((is_user_logged_in() || !empty($form_options->hide_username)) && $field_type=='username'):
                   if(!(is_super_admin() && !empty($_GET['form_prev']))){
                        continue;
                   }   
                endif;
                
                if($field_type=='userpassword'){
                    if(is_user_logged_in()){
                        if(!(is_super_admin() && !empty($_GET['form_prev']))){
                            continue;
                        }
                    }
                    $password_fields=   $field_factory->create_userpassword_field();
                    if(is_array($password_fields)){
                        $fields['pwd']= $password_fields[0];
                        if(isset($password_fields[1])){
                            $fields['password_confirmation']=   $password_fields[1];
                        }
                    }
                    continue;
                } else if($field_type=='wcbillingphone'){
                    /*
                    $billing_phone_fields =   $field_factory->create_wcbillingphone_field();
                    if(is_array($billing_phone_fields)){
                        //echo '<pre>';print_r($field_options);echo '</pre>';
                        $i=1;
                        foreach($billing_phone_fields as $billing_phone_field){
                            $fields['billingphone_'.$i]= $billing_phone_field;
                            $i++;
                        }
                    }
                    continue;
                    */
                }
                else if($field_type=='wcbilling' && !class_exists( 'WooCommerce' )){
                    continue;
                }
                else if($field_type=='wcshipping' && !class_exists( 'WooCommerce' )){
                    continue;
                }
                else if($field_type=='pgavatar' && !class_exists( 'Profile_Magic' )){
                    continue;
                }
                
                if(isset($field_options->rm_widget) && $field_options->rm_widget==1){ 
                    if(is_callable(array($field_factory,"create_".$field_type."_widget")))
                        $fields[$field_name] = call_user_func_array(array($field_factory,"create_".$field_type."_widget"),array());
                    continue;
                }
                /*
                if(isset($field_options->field_is_admin_only) && !current_user_can('manage_options')) {
                    continue;
                }
                */
                if(is_callable(array($field_factory,"create_".$field_type."_field"))){
                    $field = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
                    if($field)
                        $fields[$field_name] = $field;
                } else {
                    $field = call_user_func_array(array($field_factory,"create_default_field"),array());
                    if($field)
                        $fields[$field_name] = $field;
                } //end if
            } //end foreach          
        }
        
        switch($this->backend_form->get_form_type()) {
            case RM_REG_FORM:                    
                $this->frontend_form = new RM_Frontend_Form_Reg($this->backend_form);
                $primary_field_req_names['username'] = 'username';
                $primary_field_req_names['password'] = 'password';
                $this->frontend_form->set_primary_field_index($primary_field_req_names);
                break;

            //Contact form is default case to keep compatibility with previous code
            default:
                //$this->frontend_form = new RM_Frontend_Form_Multipage($this->backend_form);                    
                $this->frontend_form = new RM_Frontend_Form_Contact($this->backend_form);
                $this->frontend_form->set_primary_field_index($primary_field_req_names);
                break;
        }              

        $this->frontend_form->add_rows_array($db_rows);
        $this->frontend_form->add_fields_array($fields);
        $this->frontend_form->set_form_number($rm_form_diary[$form_id]);
        
        //Set up FE form object
        
        //Return  new FE form
        return $this->frontend_form;
    }
    
    public function create_form_prefilled($form_id,$submission_id){
        //Load form from database
        $this->backend_form = new RM_Forms;
        $this->backend_form->load_from_db($form_id);
        
        //load submission form database
        $submission = new RM_Submissions;
        $submission->load_from_db($submission_id);
        
        //load previous submission data to fill 
        $prev_sub_data = $submission->get_data();
        
        //Update form diary
        global $rm_form_diary;
        if(isset($rm_form_diary[$form_id]))
            $rm_form_diary[$form_id]++;
        else
            $rm_form_diary[$form_id] = 1;
        
        
        $primary_field_req_names = array();
        //Load corresponding fields from db
        $fields = array();
        $db_fields = $this->service->get_editable_fields($form_id);
        $db_rows = $this->service->get_editable_rows($form_id);
        $primary = $this->service->get('FIELDS', array('field_type' => 'Email', 'is_field_primary' => 1, 'form_id' => $form_id), array('%s','%d','%d'), 'row');
        
        $db_fields[$primary->field_id] = $primary;
        
        if($db_fields)
        {
            foreach($db_fields as $db_field)
            {
                if(strtolower($db_field->field_type)=='username' || strtolower($db_field->field_type)=='userpassword')
                {
                    continue;
                }
                
                if(isset($prev_sub_data[$db_field->field_id])){
                	$prev_entry = $prev_sub_data[$db_field->field_id];
                }else{
                	$prev_entry = new stdClass;
                	$prev_entry->type = null;
                	$prev_entry->value= null;
                	$prev_entry->label= null;
                }
                $field_options = maybe_unserialize($db_field->field_options);
                if(!is_object($field_options)){
                    $field_options = new StdClass();
                }
                $form_options = $this->backend_form->get_form_options();

                if(isset($form_options->style_textfield)){
                    $field_options->style_textfield = $form_options->style_textfield;
                }
                if(isset($form_options->style_label)){
                    $field_options->style_label = $form_options->style_label;
                }
                
                $opts = $this->service->set_properties($field_options);
                $opts['value'] = $prev_entry->value;
                $field_name= $db_field->field_type."_".$db_field->field_id;
                $field_type= strtolower($db_field->field_type);
                
                //Check if type of the field has not been changed
                /*if($field_type !== $prev_entry->type)
                    continue;*/
                
                // Check if this is primary email field
                if($field_type=='email'):
                    if($db_field->is_field_primary):
                        $primary_field_req_names['user_email'] =  $db_field->field_type."_".$db_field->field_id;
                        $opts['readonly'] = true;
                    endif;     
                endif;
                $field_factory= new RM_Field_Factory($db_field,$opts, true);
                $field_type = str_replace('-', '', $field_type);
                
                if(isset($field_options->rm_widget) && $field_options->rm_widget==1){ 
                    if(is_callable(array($field_factory,"create_".$field_type."_widget")))
                     $fields[$field_name] = call_user_func_array(array($field_factory,"create_".$field_type."_widget"),array());
                    continue;
                }
                /*
                if(isset($field_options->field_is_admin_only) && !current_user_can('manage_options')) {
                    continue;
                }
                */
                if(is_callable(array($field_factory,"create_".$field_type."_field"))){
                     $field = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
                     if($field)
                        $fields[$field_name] = $field;
                }else{
                    $field = call_user_func_array(array($field_factory,"create_default_field"),array());
                    if($field)
                     $fields[$field_name] = $field;
                } //end if
            } //end foreach
        }  
            
        switch($this->backend_form->get_form_type())
        {
            case RM_REG_FORM:                    
                $this->frontend_form = new RM_Frontend_Form_Reg($this->backend_form);
                $primary_field_req_names['username'] = 'username';
                $primary_field_req_names['password'] = 'password';
                $this->frontend_form->set_primary_field_index($primary_field_req_names);
                break;

            //Contact form is default case to keep compatibility with previous code
            default:
                //$this->frontend_form = new RM_Frontend_Form_Multipage($this->backend_form);                    
                $this->frontend_form = new RM_Frontend_Form_Contact($this->backend_form, $is_admin);
                $this->frontend_form->set_primary_field_index($primary_field_req_names);
                break;
        }              

        $this->frontend_form->add_fields_array($fields);      
        $this->frontend_form->set_form_number($rm_form_diary[$form_id]);
        
        //Set up FE form object
        
        //Return  new FE form
        return $this->frontend_form;
    }
}
