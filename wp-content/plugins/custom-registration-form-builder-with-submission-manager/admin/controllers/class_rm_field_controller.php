<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of calss_rm_field_controller
 *
 * @author CMSHelplive
 */
class RM_Field_Controller {

    public $mv_handler;

    function __construct() {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function add($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id']))
            $fields_data = $service->get_all_form_fields($request->req['rm_form_id']);
        else
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
        
        if (isset($request->req['rm_form_page_no']))
            $form_page_no = $request->req['rm_form_page_no'];
        else
            $form_page_no = 1;

        if ($this->mv_handler->validateForm("add-field")) {
            $request->req['page_no'] = $form_page_no;
            $new_field_order = intval($service->get_fields_highest_order($request->req['rm_form_id'], $form_page_no)) + 1;
            $request->req['field_order'] = $new_field_order;
            
            //Setup icon props
            $f_icon = new stdClass;
            $f_icon->codepoint = $request->req['input_selected_icon_codepoint'];
            $f_icon->fg_color = $request->req['icon_fg_color'];
            $f_icon->bg_color = $request->req['icon_bg_color'];
            $f_icon->shape = $request->req['icon_shape'];
            if(defined('REGMAGIC_ADDON'))
                $f_icon->bg_alpha = $request->req['icon_bg_alpha'];
            $request->req['icon'] = $f_icon;            
            /////////////////////
            if($request->req['field_type'] === "Repeatable_M"){
                 $request->req['field_type']= 'Repeatable';
            }
            //Setup rating field props
            if($request->req['field_type'] === "Rating"){
                $rating_conf = new stdClass;
                $rating_conf->max_stars = $request->req['rating_max_stars'];
                $rating_conf->star_face = $request->req['rating_star_face'];
                $rating_conf->step_size = $request->req['rating_step_size'];
                $rating_conf->star_color = $request->req['rating_star_color'];
                $request->req['rating_conf'] = $rating_conf; 
            }
            // Reset conditions if field type changed
            $temp_field= new RM_Fields();
            if(isset($request->req['field_id'])){
                 $temp_field->load_from_db($request->req['field_id']);
                 if($temp_field->is_field_primary && $temp_field->get_field_type()=='Email'){
                    $request->req['is_deletion_allowed']= 0; 
                 }
                 else
                 {
                     $request->req['is_deletion_allowed']= 1;
                 }
            }
            else{
                $request->req['is_deletion_allowed']= 1;
            }
           
            if($temp_field->get_field_type()==$request->req['field_type']){
                $request->req['conditions']= $temp_field->get_field_conditions();
            }
            
            $model->set($request->req);
            if (isset($request->req['field_id']) && !empty($request->req['field_id'])) {
                $service->update($model, $service, $request, $params);
            } else {
                $new_field_id = $service->add($model, $service, $request, $params);
                if(isset($request->req['rm_row_id']) && isset($request->req['rm_order_in_row'])) {
                    if($request->req['rm_row_id'] == 0) {
                        $this->add_field_in_row(RM_DBManager::add_quick_row_in_form($request->req['rm_form_id'], $form_page_no), $new_field_id, intval($request->req['rm_order_in_row']));
                    } else {
                        $this->add_field_in_row(intval($request->req['rm_row_id']), $new_field_id, intval($request->req['rm_order_in_row']));
                    }
                }
            }
            RM_Utilities::sync_username_hide_option($request->req['rm_form_id']);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
            RM_Utilities::redirect(admin_url('/admin.php?page=' . $params['xml_loader']->request_tree->success . '&rm_form_id=' . $request->req["rm_form_id"] . '&rm_form_page_no=' . $form_page_no));
            //$this->view->render();
        } else {

            // Edit for request
            if (isset($request->req['rm_field_id'])) {
                $model->load_from_db($request->req['rm_field_id']);
            }

            $data = new stdClass;
            $data->model = $model;
            $data->selected_field = isset($request->req['rm_field_type']) ? $request->req['rm_field_type'] : null;
            if ($data->selected_field=="Repeatable_M") { 
                  $data->model->field_options->field_is_multiline=1;
            }
            
            if(strtolower($data->selected_field)=="mobile"){
                $data->country_fields = $service->get_country_field_dd($request->req['rm_form_id']);
            }
            
            $data->form_id = $request->req['rm_form_id'];
            $data->paypal_fields = RM_Utilities::get_paypal_field_types($service);
            if(defined('REGMAGIC_ADDON'))
                $data->validations_array = RM_Utilities::get_validations_array();
            $user_service= new RM_User_Services();
            $data->metas= $user_service->get_user_meta_dropdown();
            $view = $this->mv_handler->setView("field_add");
            $view->render($data);
        }
    }
    
    public function add_widget($model, $service, $request, $params){
        if (isset($request->req['rm_form_page_no']))
            $form_page_no = $request->req['rm_form_page_no'];
        else
            $form_page_no = 1;
        
        if ($this->mv_handler->validateForm("add-widget")){
            $request->req['page_no'] = $form_page_no;
            $new_field_order = intval($service->get_fields_highest_order($request->req['rm_form_id'], $form_page_no)) + 1;
            $request->req['field_order'] = $new_field_order;
            $model->set($request->req);
            
            /////////////////////
            if(isset($request->req['field_id'])){
                $temp_model= new RM_Fields();
                $temp_model->load_from_db($request->req['field_id']);
                $request->req['conditions']= $temp_model->get_field_conditions();
                $service->update($model, $service, $request, $params);
            } else{
                $new_field_id = $service->add($model, $service, $request, $params);
                if(isset($request->req['rm_row_id']) && isset($request->req['rm_order_in_row'])) {
                    if($request->req['rm_row_id'] == 0)
                        $this->add_field_in_row(RM_DBManager::add_quick_row_in_form($request->req['rm_form_id'], $form_page_no),$new_field_id,intval($request->req['rm_order_in_row']));
                    else
                        $this->add_field_in_row($request->req['rm_row_id'],$new_field_id,intval($request->req['rm_order_in_row']));
                }
               // die('firsttime');
            }
            
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
            RM_Utilities::redirect(admin_url('/admin.php?page=' . $params['xml_loader']->request_tree->success . '&rm_form_id=' . $request->req["rm_form_id"] . '&rm_form_page_no=' . $form_page_no));
        }
        isset($request->req['rm_field_id']) ? $model->load_from_db($request->req['rm_field_id']) : '';
        $data = new stdClass;
        $data->selected_field = isset($request->req['rm_field_type']) ? $request->req['rm_field_type'] : null;
        
        $data->form_id = $request->req['rm_form_id'];
        $data->model= $model;
        $view = $this->mv_handler->setView("add_widget");
        $view->render($data);
    }
    
    public function manage($model, $service, $request, $params) {
        $data = new stdClass;
        $request->req['rm_form_id']= absint($request->req['rm_form_id']);
        $data->active_step = isset($request->req['astep']) ? $request->req['astep'] : "build";
        $data->def_form_id = $service->get_setting('default_form_id');
        $fields_data= $service->get_all_form_fields($request->req['rm_form_id']);
        $row_eligible = false;
        
        if(!empty($request->req['rm_field_type'])){
            if($request->req['rm_field_type']=='Username' && !$service->has_user_name($request->req['rm_form_id'])){
                $service->create_default_username_field($request->req['rm_form_id']);
                RM_Utilities::sync_username_hide_option($request->req['rm_form_id']);
            } else if($request->req['rm_field_type']=='UserPassword' && !$service->has_user_password($request->req['rm_form_id'])){
                $service->create_default_password_field($request->req['rm_form_id']);
            }
        }
        
        $options= new RM_Options();
        
        if (isset($request->req['rm_action'])) {
            if ($request->req['rm_action'] === 'delete')
                $this->remove_field($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'add_page') {
                if(defined('REGMAGIC_ADDON')) {
                    $this->add_page($model, $service, $request, $params);
                    return;
                } else {
                    $data->current_page = $this->add_page($model, $service, $request, $params);
                }
            } elseif ($request->req['rm_action'] === 'delete_page')
                $this->delete_page($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'rename_page')
                $this->rename_page($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'rename_page')
                $this->rename_page($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'add_row')
                $this->add_row($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'update_row')
                $this->update_row($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'delete_row')
                $this->remove_row($model, $service, $request, $params);
            elseif ($request->req['rm_action'] === 'duplicate_row')
                $this->duplicate_row($model, $service, $request, $params);
        }
        
        /* Saving conditional fields */
        if(isset($request->req['dfield'])){
          $dField= new RM_Fields();
          $dField->load_from_db($request->req['dfield']);
          $dType= $dField->get_field_type();
          //$allowed_c_fields= RM_Utilities::get_allowed_conditional_fields(); 
          $cField= new RM_Fields();
          $dField->field_options->conditions= array("rules"=> array(),"settings"=>array());
          if(empty($request->req['cfields'])){
                $dField->field_options->conditions= array();
                $dField->field_options->conditions['settings']= array();
                $dField->update_into_db(); 
          } else{
          foreach($request->req['cfields'] as $index=>$cf_id){
                if((int)$cf_id==0 || $cf_id==$dField->field_id)
                    continue;
                $cField->load_from_db($cf_id);
                $cType= $cField->get_field_type();
                $dField->field_options->conditions['rules']['c_'.$cf_id.'_'.$index]= array("controlling_field"=>$cf_id,"op"=>$request->req['op'][$index],"values"=>explode(',',$request->req['values'][$index]));	
                $dField->field_options->conditions['settings']= array('combinator'=> isset($request->req['combinator'])?$request->req['combinator']:'OR');	
                $dField->field_options->conditions['action']= $request->req['action'];
                $dField->update_into_db(); 
               }
             }
             $data->show_conditions= true;
         }

        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) {
            $rows_data = $service->get_all_form_rows($request->req['rm_form_id']);
            $fields_data = $service->get_all_form_fields($request->req['rm_form_id']);
            if(!empty($rows_data)) {
                $row_eligible = true;
                foreach($rows_data as $row) {
                    $row->fields = $service->get_all_fields_by_row($row);
                }
            }
        } else {
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
        }
       
        $data->theme = $options->get_value_of('theme');
        $data->fields_data = $fields_data;
        $data->rows_data = $rows_data;
        $data->row_eligible = $row_eligible;
        $data->forms = RM_Utilities::get_forms_dropdown($service);
        $form = new RM_Forms();
        $form->load_from_db($request->req['rm_form_id']);
        $data->form_id = $request->req['rm_form_id'];
        $data->form= $form;
        $data->field_types = defined('REGMAGIC_ADDON') ? RM_Utilities::get_field_types(true,$form->form_type) : RM_Utilities::get_field_types();
        $data->prev_page= $options->get_value_of('front_sub_page_id');
        $fopts = $form->get_form_options();
        
        if(!defined('REGMAGIC_ADDON')) {
            $g = array_keys($data->field_types);
            if($data->fields_data && is_array($data->fields_data))
                foreach($data->fields_data as $in => $out)
                {
                    if(!in_array($out->field_type, $g))
                            unset($data->fields_data[$in]);
                }
        }
        
        $data->recent_forms = RM_Utilities::get_recent_forms($service);
        $data->popular_forms = RM_Utilities::get_popular_forms($service);

        if (!$fopts->form_pages) {
            $data->total_page = 1;
            $data->form_pages = array('Page 1');
            $data->ordered_form_pages = array(0);
        } else {
            $data->total_page = count($fopts->form_pages);
            $data->form_pages = $fopts->form_pages;
            if (!$fopts->ordered_form_pages)
            {
                $data->ordered_form_pages = array_keys($data->form_pages);
            }
            else 
                $data->ordered_form_pages = $fopts->ordered_form_pages;
        }
        
        if (!isset($data->current_page))
            $data->current_page = isset($request->req['rm_form_page_no']) ? $request->req['rm_form_page_no'] : $data->ordered_form_pages[0]+1;
        
        $data->has_dismissed_first_time_instructions = RM_Utilities::has_action_occured('dismiss_field_manager_instructions');
        
        $data->form_name = htmlentities(stripslashes($form->form_name));
        // Submit field - button related config
        $data->form_options = $fopts;
        // End submit field        
        //$view = $this->mv_handler->setView("field_manager");
        $view = $this->mv_handler->setView("formflow_main");
        $view->render($data);
    }

    public function add_page($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) {
            if(defined('REGMAGIC_ADDON')) {
                $page_no = $service->manage_form_page('add_page', $request->req['rm_form_id'], null);
                RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
                RM_Utilities::redirect("?page=rm_field_manage&rm_form_id=".$request->req['rm_form_id']."&rm_form_page_no=$page_no");
            } else
                return $service->manage_form_page('add_page', $request->req['rm_form_id'], null);
        } else
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
    }

    public function delete_page($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) {
            if (isset($request->req['rm_form_page_no'])) {
                $service->manage_form_page('delete_page', $request->req['rm_form_id'], $request->req['rm_form_page_no']);
                //$request->req['rm_form_page_no'] = 1;
                RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
            }
        } else
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
    }

    public function rename_page($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) {
            if (isset($request->req['rm_form_page_no']) && isset($request->req['rm_form_page_name'])) {
                $service->manage_form_page('rename_page', $request->req['rm_form_id'], $request->req['rm_form_page_no'], $request->req['rm_form_page_name']);
                RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
            }
        } else
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
    }
    
    public function set_page_order($model, $service, $request, $params) {
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $service->set_page_order($request->req['form_id'], $request->req['data']);
            RM_DBManager::update_form_published_pages($request->req["form_id"]);
        }
    }

    public function set_order($model, $service, $request, $params) {
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $service->set_field_order($request->req['data']);

            $form_id = RM_DBManager::get_form_id_by_field_id($request->req['data'][array_key_first($request->req['data'])]);
            RM_DBManager::update_form_published_pages($form_id);
        }
    }

    public function remove_field($model, RM_Services $service, $request, $params) {
        if (isset($request->req['rm_field_id'])) {
            $result = $service->remove($request->req['rm_field_id'], null, array());
            if(isset($request->req['rm_row_id']) && isset($request->req['rm_order_in_row'])) {
                $this->remove_field_from_row(intval($request->req['rm_row_id']),intval($request->req['rm_order_in_row']));
            }
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
            if(defined('REGMAGIC_ADDON'))
                RM_Utilities::sync_username_hide_option($request->req['rm_form_id']);
        }
        else
            die(RM_UI_Strings::get('MSG_NO_FIELD_SELECTED'));
    }

    public function duplicate($model, $service, $request, $params) {
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;
        $ids = $service->duplicate($selected);
        RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        if(defined('REGMAGIC_ADDON'))
            RM_Utilities::redirect($this->get_redirection_url_for_actions($request));
        else
            $this->manage($model, $service, $request, $params);
    }

    public function remove($model, RM_Services $service, $request, $params) {
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;
        $service->remove($selected);
        RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        if(defined('REGMAGIC_ADDON'))
            RM_Utilities::redirect($this->get_redirection_url_for_actions($request));
        else
            $this->manage($model, $service, $request, $params);
    }
    
    public function add_row($model, $service, $request, $params) {
        if (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) {
            return $service->manage_form_row('add_row', $request);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        } else
            die(RM_UI_Strings::get('MSG_NO_FORM_SELECTED'));
    }
    
    public function update_row($model, RM_Services $service, $request, $params) {
        if (isset($request->req['rm_row_id'])) {
            $result = $service->manage_form_row('update_row', $request);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        } else
            die(RM_UI_Strings::get('MSG_NO_ROW_SELECTED'));
    }
    
    public function remove_row($model, RM_Services $service, $request, $params) {
        if (isset($request->req['rm_row_id'])) {
            $row = new RM_Rows;
            $row->load_from_db(intval($request->req['rm_row_id']));
            $service->remove($row->field_ids,'FIELDS');
            $result = $service->manage_form_row('delete_row', $request);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        } else {
            die(RM_UI_Strings::get('MSG_NO_ROW_SELECTED'));
        }
    }
    
    public function duplicate_row($model, RM_Services $service, $request, $params) {
        if (isset($request->req['rm_row_id'])) {
            $service->duplicate_row($request->req['rm_row_id'], $request->req["rm_form_id"], array(), $request->req['rm_form_page_no']);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        }
    }
    
    public function add_field_in_row($row_id, $field_id, $order_in_row) {
        $row = new RM_Rows;
        $row->load_from_db($row_id);
        $row->field_ids[$order_in_row] = $field_id;
        $row->update_into_db();
    }
    
    public function remove_field_from_row($row_id, $order_in_row) {
        $row = new RM_Rows;
        $row->load_from_db($row_id);
        $row->field_ids[$order_in_row] = '';
        $row->update_into_db();
    }
    
    public function set_row_order($model, $service, $request, $params) {
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $service->set_row_order($request->req['data']);
            RM_DBManager::update_form_published_pages($request->req["rm_form_id"]);
        }
    }
    
    public function get_redirection_url_for_actions($request){
        $url  = '?page=rm_field_manage';
        if(isset($request->req['rm_form_id']))
            $url .= "&rm_form_id=".$request->req['rm_form_id'];
        if(isset($request->req['curr_page_no']))
            $url .= "&rm_form_page_no=".$request->req['curr_page_no'];
        return $url;
    }
    public function set_row_col_order($model, $service, $request, $params) {
        
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $col_lists = $request->req['data'];
            $row_id = $request->req['rm_row'];
            $page_id = $request->req['rm_page'];
            $service->set_row_column_order($col_lists, $row_id, $page_id);
            
        }
        die;
    }
    public function conditions_check($model, $service, $request, $params){	
        $result = array('fieldType'=>'','dateFormat'=>'','conditionOption'=>'', 'htmlFieldType'=>'text');	
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $model->load_from_db($request->req['field_id']);	
            $result['fieldType'] = $model->get_field_type();	
            $result['conditionOption'] = $this->conditions_check_get_condition_type($result['fieldType']);	
            $result['htmlFieldType'] = RM_Utilities::get_condition_values_fields_type($result['fieldType']);	
            if( $result['htmlFieldType'] == 'date'){	
                $valid_options = maybe_unserialize($model->get_field_options());	
                $result['dateFormat'] = isset($valid_options->date_format) ? $valid_options->date_format : 'mm/dd/yy';	
            }	
        }	
        echo wp_send_json_success($result);	
        die;	
    }	
    	
    public function conditions_check_get_field_type($model,$request ){	
        $model->load_from_db($request->req['field_id']);	
        return $model->get_field_type();	
    }	
    public function conditions_check_get_condition_type($fieldType){	
        	
        return $operators = RM_Utilities::get_condition_dropdown($fieldType);	
        /*	
         * $op = '';	
         * foreach ($operators as $key => $op) {	
           $op .= '<option value="' . esc_attr($op) . '">' . esc_html($key) . '</option>';	
        }	
        return $op;*/	
    }
}