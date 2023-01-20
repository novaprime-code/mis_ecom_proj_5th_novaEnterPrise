<?php

/**
 * Class for payments controller
 * 
 * Manages the payments related operations in the backend.
 *
 * @author CMSHelplive
 */
class RM_Payments_Controller
{

    public $mv_handler;

    public function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function manage($model, $service, $request, $params)
    {
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Controller_Addon')) {
            $addon_controller = new RM_Payments_Controller_Addon();
            return $addon_controller->manage($model, $service, $request, $params, $this);
        }
        $data = new stdClass();
        $filter= new RM_Payments_Filter($request,$service);
        $form_id= $filter->get_form();
        $data->forms = RM_Utilities::get_forms_dropdown($service);
        $data->fields = $service->get_all_form_fields($form_id);
        $data->filter= $filter;
        $data->rm_slug = $request->req['page'];
        $data->payments= $filter->get_records();
        $view = $this->mv_handler->setView('payments_manager');
        $view->render($data);
        
    }
    public function view($model, $service, $request, $params)
    {
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Controller_Addon')) {
            $addon_controller = new RM_Payments_Controller_Addon();
            return $addon_controller->view($model, $service, $request, $params, $this);
        }
        if (isset($request->req['rm_submission_id']))
        {

            if (!$model->load_from_db($request->req['rm_submission_id']))
            {
                $view = $this->mv_handler->setView('show_notice');
                $data = RM_UI_Strings::get('MSG_DO_NOT_HAVE_ACCESS');
                $view->render($data);
            } else
            {
                $child_id = $model->get_child_id();
                if($child_id != 0){
                    $request->req['rm_submission_id'] = $model->get_last_child();
                    return $this->view($model, $service, $request, $params);
                }
                    
                
                if (isset($request->req['rm_action']) && $request->req['rm_action'] == 'delete')
                {
                    $request->req['rm_form_id'] = $model->get_form_id();
                    $request->req['rm_selected'] = $request->req['rm_submission_id'];
                    $this->remove($model, $service, $request, $params);
                    unset($request->req['rm_selected']);
                } elseif (isset($request->req['rm_action']) && $request->req['rm_action'] == 'activate' && isset($request->req['rm_user_id']))
                {
                    $submission_id = $request->req['rm_submission_id'];
                    $user_model = new RM_User;
                    $user_id = $request->req['rm_user_id'];
                    $user_model->activate_user($user_id);
                    RM_Utilities::redirect('?page=rm_payments_view&rm_submission_id='.$submission_id);
                    
                }elseif (isset($request->req['rm_action']) && $request->req['rm_action'] == 'deactivate' && isset($request->req['rm_user_id']))
                {
                    $submission_id = $request->req['rm_submission_id'];
                    $user_model = new RM_User;
                    $user_id = $request->req['rm_user_id'];
                    $user_model->deactivate_user($user_id);
                    RM_Utilities::redirect('?page=rm_payments_view&rm_submission_id='.$submission_id);
                }else
                {
                    $settings = new RM_Options;

                    $data = new stdClass();

                    $data->submission = $model;

                    $data->payment = $service->get('PAYPAL_LOGS', array('submission_id' => $service->get_oldest_submission_from_group($model->get_submission_id())), array('%d'), 'row', 0, 99999);

                    if ($data->payment != null)
                    {
                        $data->payment->total_amount = $settings->get_formatted_amount($data->payment->total_amount, $data->payment->currency);

                        if ($data->payment->log)
                            $data->payment->log = maybe_unserialize($data->payment->log);
                    }

                    $form = new RM_Forms();
                    $form->load_from_db($model->get_form_id());
                    $data->form_id=$model->get_form_id();
                    $fields= $service->get_all_form_fields($model->get_form_id());
                    $data->email_field_id=$fields['0']->field_id;
                    $form_type = $form->get_form_type() == "1" ? __("Registration",'custom-registration-form-builder-with-submission-manager') : __("Non WP Account",'custom-registration-form-builder-with-submission-manager');
                    $data->form_type = $form_type;
                    $data->form_type_status = $form->get_form_type();
                    $data->form_name = $form->get_form_name();
                    $data->form_is_unique_token = $form->get_form_is_unique_token();
                    $data->latest_payments = RM_DBManager::get_recents_payments_by_formid($data->submission->form_id, $data->submission->submission_id);
                    $data->user_payments = RM_DBManager::get_recents_payments_by_email($data->submission->user_email, $data->submission->submission_id);
                    // Life time revenue
                    $data->total_revenue  = RM_DBManager::get_total_revenue_by_user_email($data->submission->user_email);
                    if(!$data->total_revenue){
                        $data->total_revenue = 0;
                    }
                    $data->enable_invoice = false; 
                    $data->notes = array();
                    $view = $this->mv_handler->setView('payments_view');

                    $view->render($data);
                }
            }
        } else
            throw new InvalidArgumentException(RM_UI_Strings::get('MSG_INVALID_SUBMISSION_ID'));
    }
    
    
    public function download_invoice($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Controller_Addon')) {
            $addon_controller = new RM_Payments_Controller_Addon();
            return $addon_controller->download_invoice($model, $service, $request, $params, $this);
        }
        throw new InvalidArgumentException(RM_UI_Strings::get('MSG_INVALID_SUBMISSION_ID'));
    }
    public function remove($model, RM_Services $service, $request, $params)
    {
        $form_id= (isset($request->req['rm_form_id']) && is_numeric($request->req['rm_form_id'])) ? $request->req['rm_form_id'] : null; 
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;
        if($selected !=null){
        $service->remove_submissions($selected);
        $service->remove_submission_notes($selected);
        $service->remove_submission_payment_logs($selected);
        }
        RM_Utilities::redirect('?page=rm_payments_manage&rm_form_id='.$form_id);
    }
    public function sent_invoice($model, RM_Services $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Controller_Addon')) {
            $addon_controller = new RM_Payments_Controller_Addon();
            return $addon_controller->sent_invoice($model, $service, $request, $params, $this);
        }
    }
    
    
}