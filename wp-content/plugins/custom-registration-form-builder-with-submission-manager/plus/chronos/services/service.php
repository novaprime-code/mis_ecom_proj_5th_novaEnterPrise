<?php

class RM_Chronos_Service extends RM_Services {
    public function process_request($req) {
        $task_conf = array();
        if(!isset($req['rm_form_id']))
            return false;
        $task_conf['form_id'] = absint($req['rm_form_id']);
        $task_conf['name'] = sanitize_text_field($req['rmc_task_name']);
        $task_conf['desc'] = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_task_description');
        
        $user_action = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_action_user_acc', 'do_nothing');
            
        //It could have been automated, but this adds extra sanitization layer.        
        switch($user_action) {
            case 'activate':
                $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_ACTIVATE_USER;
                break;
            case 'deactivate':
                $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_DEACTIVATE_USER;
                break;
            case 'delete':
                $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_DELETE_USER;
                break;
            case 'apply_status':
                $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_APPLY_STATUS;
                break;
            case 'remove_status':
                $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_REMOVE_STATUS;
                break;
            default:
                break;
        }
       
        if(isset($req['rmc_enable_send_mail_action'])) {
            $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_SEND_EMAIL;
            $task_conf['meta'] = array('email_subject' => RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_action_send_mail_sub'),
                                       'email_template' => RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_action_send_mail_body'));                    
        }
        /*
        if(isset($req['rmc_assign_user_role_action'])) {
            $task_conf['actions'][] = RM_Chronos_Action_Interface::ACTION_TYPE_ASSIGN_ROLE;
            $task_conf['meta'] = array(
                'user_roles' => RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_assign_user_role_action'),
            );                    
        }
        */
        $task_conf['meta']['rmc_task_type'] = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_task_type');
        $task_conf['meta']['rmc_task_first_execution'] = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_task_first_execution') !== '' ? strtotime(get_gmt_from_date(RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_task_first_execution'))) : '';
        $task_conf['meta']['rmc_task_schedule'] = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_task_schedule');
        
        $rule_configs = $this->get_rule_config($req);
        if(!is_array($rule_configs))
            $rule_configs = array();
        
        $rule_ids = array();
        foreach($rule_configs as $rule_config) {
            $rule_model = new RM_Chronos_Rule_Model;
            $rule_model->create($rule_config);
            $id = $rule_model->insert_into_db();
            if($id)
                $rule_ids[] = $id;
        }
        
        $task_conf['must_rules'] = $rule_ids;
        $task_model = new RM_Chronos_Task_Model;
        if(isset($req['rmc_task_id'])) {
            $res = $task_model->load_from_db(sanitize_text_field($req['rmc_task_id']));
            if($res) {
                //delete existing rules
                if(count($task_model->must_rules) > 0) {
                    global $wpdb;                    
                    //First check if these rules are not being used in any other task (by means of duplication)
                    $rule_search_pattern = maybe_serialize($task_model->must_rules);
                    $task_table = RM_Chronos::get_table_name_for('TASKS');                    
                    $test_rules = $wpdb->get_results("SELECT `must_rules` FROM {$task_table} WHERE `must_rules` = '{$rule_search_pattern}'");
                    
                    if(!$test_rules || count($test_rules) <= 1) {
                        $ex_rule_ids = implode(",",$task_model->must_rules);
                        $rule_table = RM_Chronos::get_table_name_for('RULES');
                        $wpdb->query("DELETE FROM {$rule_table} WHERE `rule_id` IN ({$ex_rule_ids})");                    
                    }
                }
                $task_model->create($task_conf);
                $task_model->update_into_db();
                $task_id = $task_model->props['task_id'];
                $this->update_edit_delete_cron($task_id, 'edit');
                return $task_model->rule_id;
            } else
                return false;
        } else {       
            $task_model->create($task_conf);
            $task_model->insert_into_db();
            $task_id = $task_model->props['task_id'];
            $this->update_edit_delete_cron($task_id, 'add');
            return $task_model;
        }
    }
    
    public function get_rule_config($req) {
        $rules = array();
        
        if(isset($req['rmc_enable_user_account_rule'])) {
            $ua_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_USER_STATE,
                             'attr_value' => RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_rule_user_account','active'));
            
            $rules[] = $ua_rule;
        }
        
        if(isset($req['rmc_enable_sub_time_rule'])) {
            if(isset($req['rmc_enable_sub_time_rule_older_than'])) {
                $age = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_rule_sub_time_older_than_age');
                if($age) {                
                    $st_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_SUB_TIME,                             
                                     'attr_value' => $age,
                                     'operator' => ">=");

                    $rules[] = $st_rule;
                }
            }
            if(isset($req['rmc_enable_sub_time_rule_younger_than'])) {
                $age = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_rule_sub_time_younger_than_age');
                if($age) {                
                    $st_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_SUB_TIME,                             
                                     'attr_value' => $age,
                                     'operator' => "<=");

                    $rules[] = $st_rule;
                }
            }
        }
        
        if(isset($req['rmc_enable_field_value_rule'])) {
            $fv_rule_ids = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_rule_fv_fids',array());
            $fv_rule_values = RM_Chronos_Toolkit::safe_array_fetch($req, 'rmc_rule_fv_fvals',array());
            
            foreach($fv_rule_ids as $index => $fid) {
                $fval = $fv_rule_values[$index];
                $fval = trim($fval);
                if(!$fid || !$fval)
                    continue;
                $array_fval = explode("|",$fval);
                $array_fval = array_filter(array_map("trim",$array_fval));
                $final_array_fval = array();
                foreach ($array_fval as $fv)
                    $final_array_fval[$fv] = "LIKE";
                if(count($final_array_fval) > 0) {
                    $fv_rule_single = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_FIELD_VALUE,
                                     'attr_name' => $fid,
                                     'attr_value' => $final_array_fval);
                    $rules[] = $fv_rule_single;
                }
            }
        }
        
        if(isset($req['rmc_enable_pay_proc_rule'])) {
            if(isset($req['rmc_rule_pay_procs']) && is_array($req['rmc_rule_pay_procs'])) {
                //$selected_count = count($req['rmc_rule_pay_procs']);
                //Add rule only if neither all deselected nor all selected.
                //if($selected_count > 0 && $selected_count < 4) {
                    $pgw_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_GATEWAY,
                                     'attr_value' => array_map('sanitize_text_field', $req['rmc_rule_pay_procs']));
                    $rules[] = $pgw_rule;
                //}
            } else {
                $pgw_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_GATEWAY,
                                'attr_value' => array());
                $rules[] = $pgw_rule;
            }
        }
        
        if(isset($req['rmc_enable_pay_status_rule'])) {
            if(isset($req['rmc_rule_pay_status']) && is_array($req['rmc_rule_pay_status'])) {
                //$selected_count = count($req['rmc_rule_pay_status']);
                //Add rule only if neither all deselected nor all selected.
                //if($selected_count > 0 && $selected_count < 3) {
                    $pay_state_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_STATUS,
                                     'attr_value' => array_map('sanitize_text_field', $req['rmc_rule_pay_status']));
                    $rules[] = $pay_state_rule;
                //}
            } else {
                $pay_state_rule = array('type' => RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_STATUS,
                                     'attr_value' => array());
                $rules[] = $pay_state_rule;
            }
        }
        
        return $rules;
    }
    
    public function get_field_initial_config($task) {
        $data = new stdClass;
        //default values will be provided by model
        $task_model = new RM_Chronos_Task_Model;
        foreach($task_model->props as $prop => $val)
            $data->$prop = $val;
        if($task instanceof RM_Chronos_Task) {
            foreach($task as $prop => $val)
                $data->$prop = $val;
        }
        $available_rule_groups = array('g_user_account_rule','g_sub_time_rule','g_field_val_rule','g_pay_proc_rule','g_pay_status_rule','g_custom_status_rule');
        $rule_data = array();
        foreach($available_rule_groups as $rule_type) {
            $rule_data[$rule_type] = array();
            $rule_data[$rule_type]['state'] = '';
        }
        //setup defaults
        $rule_data['g_user_account_rule']['active_option_state'] =
        $rule_data['g_user_account_rule']['inactive_option_state'] = '';

        $rule_data['g_sub_time_rule']['older_than_age'] = 
        $rule_data['g_sub_time_rule']['younger_than_age'] = 
        $rule_data['g_sub_time_rule']['older_than_state'] = 
        $rule_data['g_sub_time_rule']['younger_than_state'] = '';

        $rule_data['g_field_val_rule']['rmc_rule_fv_fids'] = 
        $rule_data['g_field_val_rule']['rmc_rule_fv_fvals'] = array();

        $rule_data['g_pay_proc_rule']['pprocs'] = 
        $rule_data['g_pay_status_rule']['pay_statuses'] = array();
        $rule_data['g_custom_status_rule']['custom_status'] = array();
     
        foreach($data->must_rules as $rule) {
            if($rule instanceof RM_Chronos_Rule_Abstract) {
                $rule_type = $rule->get_type();               
                
                switch($rule_type) {
                    case RM_Chronos_Rule_Interface::RULE_TYPE_USER_STATE:
                        $rule_data['g_user_account_rule']['state'] = 'checked';                        
                        if($rule->attr_value == 'active')
                            $rule_data['g_user_account_rule']['active_option_state'] = 'checked';
                        else if($rule->attr_value == 'inactive')
                            $rule_data['g_user_account_rule']['inactive_option_state'] = 'checked';
                        break;
                    
                    case RM_Chronos_Rule_Interface::RULE_TYPE_SUB_TIME:
                        $rule_data['g_sub_time_rule']['state'] = 'checked';                        
                        if($rule->operator == '>=') {
                            $rule_data['g_sub_time_rule']['older_than_state'] = 'checked';
                            $rule_data['g_sub_time_rule']['older_than_age'] = $rule->attr_value;
                        } else if($rule->operator == '<=') {
                            $rule_data['g_sub_time_rule']['younger_than_state'] = 'checked';
                            $rule_data['g_sub_time_rule']['younger_than_age'] = $rule->attr_value;
                        }
                        break;
                    
                    case RM_Chronos_Rule_Interface::RULE_TYPE_FIELD_VALUE:
                        $rule_data['g_field_val_rule']['state'] = 'checked';
                        $rule_data['g_field_val_rule']['rmc_rule_fv_fids'][] = $rule->attr_name;
                        $rule_data['g_field_val_rule']['rmc_rule_fv_fvals'][] = implode(" | ",array_keys($rule->attr_value));
                        break;
                    
                    case RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_GATEWAY:
                        $rule_data['g_pay_proc_rule']['state'] = 'checked';
                        $rule_data['g_pay_proc_rule']['pprocs'] = $rule->attr_value;
                        break;
                    
                    case RM_Chronos_Rule_Interface::RULE_TYPE_PAYMENT_STATUS:
                        $rule_data['g_pay_status_rule']['state'] = 'checked';
                        $rule_data['g_pay_status_rule']['pay_statuses'] = $rule->attr_value;
                        break;
                }
            }
        }
        $data->rule_data = $rule_data;
        
        $action_data = array();
        $action_data['g_user_acc_action']['state'] = "";
        $action_data['g_user_acc_action']['type'] = "do_nothing";
        $action_data['g_send_email_action']['state'] = "";
        if($task instanceof RM_Chronos_Task) {
            $action_data['g_send_email_action']['sub'] = RM_Chronos_Toolkit::safe_array_fetch($task->meta,'email_subject');
            $action_data['g_send_email_action']['body'] = RM_Chronos_Toolkit::safe_array_fetch($task->meta,'email_template');
            //$action_data['g_assign_user_role_action']['roles'] = RM_Chronos_Toolkit::safe_array_fetch($task->meta,'user_roles');
        } else {
            $action_data['g_send_email_action']['sub'] = "";
            $action_data['g_send_email_action']['body'] = "";
            //$action_data['g_assign_user_role_action']['roles'] = array();
        }
            
        $user_actions = array(RM_Chronos_Action_Interface::ACTION_TYPE_ACTIVATE_USER,
                              RM_Chronos_Action_Interface::ACTION_TYPE_DEACTIVATE_USER,
                              RM_Chronos_Action_Interface::ACTION_TYPE_DELETE_USER,
                              RM_Chronos_Action_Interface::ACTION_TYPE_APPLY_STATUS,
                              RM_Chronos_Action_Interface::ACTION_TYPE_REMOVE_STATUS);
       
        if($task instanceof RM_Chronos_Task) {
            foreach($task->actions as $action) {
                if(in_array($action,$user_actions)) {
                    $action_data['g_user_acc_action']['state'] = "checked";
                    switch($action) {
                        case RM_Chronos_Action_Interface::ACTION_TYPE_ACTIVATE_USER:
                            $action_data['g_user_acc_action']['type'] = "activate";
                            break;
                        case RM_Chronos_Action_Interface::ACTION_TYPE_DEACTIVATE_USER:
                            $action_data['g_user_acc_action']['type'] = "deactivate";
                            break;
                        case RM_Chronos_Action_Interface::ACTION_TYPE_DELETE_USER:
                            $action_data['g_user_acc_action']['type'] = "delete";
                            break;
                        case RM_Chronos_Action_Interface::ACTION_TYPE_REMOVE_STATUS:
                            $action_data['g_user_acc_action']['type'] = "remove_status";
                            break;
                        case RM_Chronos_Action_Interface::ACTION_TYPE_APPLY_STATUS:
                            $action_data['g_user_acc_action']['type'] = "apply_status";
                            break;
                    }
                } else if($action == RM_Chronos_Action_Interface::ACTION_TYPE_SEND_EMAIL) {
                    $action_data['g_send_email_action']['state'] = "checked";
                }
            }
        }
        
        $data->action_data = $action_data;
        
        return $data;
    }
    
    public function remove_task($task_id) {
        global $wpdb;
        $task_table = RM_Chronos::get_table_name_for('TASKS');
        $rule_table = RM_Chronos::get_table_name_for('RULES');
        $task_model = new RM_Chronos_Task_Model;
        if($task_model->load_from_db($task_id))
        {
            $task_model->remove_from_db();
            $this->update_edit_delete_cron($task_id, 'delete');
        }
    }
    
    public function remove_tasks_batch(array $task_ids) {
        global $wpdb;
        if(count($task_ids) > 0) { 
            foreach($task_ids as $task_id) {
                $this->remove_task($task_id);
                $this->update_edit_delete_cron($task_id, 'delete');
            }
        }
    }
    
    public function duplicate_tasks_batch(array $task_ids) {
        global $wpdb;
        if(count($task_ids) > 0) {        
            $table_name = RM_Chronos::get_table_name_for('TASKS');            
            $task_ids = implode(",",$task_ids);
            $res = $wpdb->get_results("SHOW COLUMNS FROM $table_name");
            $table_columns = wp_list_pluck($res, 'Field');
            $table_columns = implode("`,`",$table_columns);
            $query = "INSERT INTO {$table_name} SELECT NULL `{$table_columns}` FROM {$table_name} WHERE `task_id` IN ($task_ids)";
            $wpdb->query($query);
        }
    }
    
    public function set_state_tasks_batch(array $task_ids, $state) {
        global $wpdb;
        $tasks_automations_id = $task_ids;
        if(count($task_ids) > 0) {        
            $table_name = RM_Chronos::get_table_name_for('TASKS');            
            $task_ids = implode(",",$task_ids);
            $is_active = ($state == 'enable') ? 1 : 0;
            $query = "UPDATE {$table_name} SET `is_active` = {$is_active} WHERE `task_id` IN ($task_ids)";
            $wpdb->query($query);
            foreach($tasks_automations_id as $task_id){
                $this->update_edit_delete_cron($task_id, 'edit');
            }
        }
    }
    
    public function update_task_order($order_list)
    {
        global $wpdb;
        if (count($order_list)) {
            $table_name = RM_Chronos::get_table_name_for('TASKS');
            $values = array_map(function($o, $i){return "($i, ".($o+1).")";}, array_keys($order_list), array_values($order_list));
            $values = implode(",",$values);
            $query = "INSERT into `{$table_name}` (task_id, task_order) VALUES {$values} ON DUPLICATE KEY UPDATE task_order = VALUES(task_order)";
            $wpdb->query($query);
        } 
    }
    public function insert_cron_on_activate_plugin(){
        $crons = new RM_Chronos;
        $tasks = $crons->get_tasks(null, 'active');
        if ( empty( $tasks ) ) {
		return ;
	}
        foreach($tasks as $task) { 
            $this->update_edit_delete_cron($task->task_id, 'add');
        }
    }
    public function delete_cron_on_deactivate_plugin(){
        $crons = new RM_Chronos;
        $tasks = $crons->get_tasks(null, 'active');
        if ( empty( $tasks ) ) {
		return ;
	}
        foreach($tasks as $task) { 
            $this->update_edit_delete_cron($task->task_id, 'delete');
        }
    }
    public function update_edit_delete_cron($task_id, $event){
        $task_factory = new RM_Chronos_Task_Factory();
        $task = $task_factory->create_task($task_id);
        $hook = 'rm_automation_task_'.$task_id;
        $timestamp = $this->get_cron_time($hook);
        if(isset($task->meta['rmc_task_type']) && $task->meta['rmc_task_type'] == 'automatic' && $task->is_active && $event=='add' ){
            if($timestamp ==''){
                $this->insert_task_cron($task_id, $task); 
            }
        }
        if(isset($task->meta['rmc_task_type']) && $task->meta['rmc_task_type'] == 'automatic' && $task->is_active && $event=='edit' ):
            $this->delete_task_cron($task_id);
            $this->insert_task_cron($task_id, $task);
        endif;
        if(isset($task->meta['rmc_task_type']) && $task->meta['rmc_task_type'] == 'automatic' && !$task->is_active && $event=='edit'){
            $this->delete_task_cron($task_id);
        }
        if($event=='delete'){
            $this->delete_task_cron($task_id);   
        }
        
    }
    public function get_cron_time($cron_name){
        $crons = _get_cron_array();
        $cron_data = array();
        foreach ( $crons as $time => $cron ) {
		foreach ( $cron as $hook => $dings ) {
                        if($hook == $cron_name){
                            return $time;
                        }
		}
	}
        return '';
    }
    public function get_cron_name($cron_name){
        $crons = _get_cron_array();
        $cron_data = array();
        foreach ( $crons as $time => $cron ) {
		foreach ( $cron as $hook => $dings ) {
                        if($hook == $cron_name){
                            return true;
                        }
		}
	}
        return false;
    }
    public function delete_task_cron($task_id){
        $crons = _get_cron_array();
        $hook = 'rm_automation_task_'.$task_id;
        $timestamp = $this->get_cron_time($hook);
        //wp_unschedule_event( $timestamp, $hook, array($task_id), true );
        if($timestamp){
            unset($crons[$timestamp]);
        }
        _set_cron_array( $crons );
    }
    public function insert_task_cron($task_id, $task){
        $hook = 'rm_automation_task_'.$task_id;
        $first_execution = isset($task->meta['rmc_task_first_execution']) ? $task->meta['rmc_task_first_execution'] : time();
        $reccurence = isset($task->meta['rmc_task_schedule']) ? $task->meta['rmc_task_schedule'] : 'daily';
        $crons = _get_cron_array();
	$key   = md5( serialize( array($task_id) ) );
        
	$crons[ $first_execution ][ $hook ][ $key ] = array(
		'schedule' => $reccurence,
		'args'     => array($task_id),
	);
	ksort( $crons );

	_set_cron_array( $crons );
        
    }
}
