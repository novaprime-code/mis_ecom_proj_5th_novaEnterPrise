<?php

class RM_Reports_Controller
{

     public $mv_handler;

    function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }
    public function dashboard($model, $service, $request, $params){
        $data = new stdClass;
        $view = $this->mv_handler->setView("reports_dashboard");
        $view->render($data);
    }
    public function submissions($model, $service, $request, $params)
    {
        $data = new stdClass;
        $data->forms = RM_Utilities::get_forms_dropdown($service);
        $req_data  = new stdClass;
        if($_GET && (isset($request->req['rm_filter_date']) || isset($request->req['rm_form_id']))){
            $filter_date = $request->req['rm_filter_date'];
            if($filter_date){
                $date = explode('-',$filter_date);
                $start_date = $date[0];
                $end_date = $date[1];
            }
            $req_data->start_date = $start_date;
            $req_data->end_date = $end_date;
            $req_data->filter_date = $filter_date;
            $req_data->form_id = $request->req['rm_form_id'];
            $req_data->email = isset($request->req['rm_email']) ? $request->req['rm_email'] : '';
            $data->req = $req_data;
            $parameter = $service->generate_reports_data($req_data,5);
            $submissions_data = $service->get_submission($parameter,5);
        }else{
            $req_data->start_date = date('Y-m-d', strtotime(' -6 day'));
            $req_data->end_date = date('Y-m-d');
            $req_data->filter_date = date('Y/m/d', strtotime(' -6 day')).'-'.date('Y/m/d');
            $req_data->form_id = 'all';
            $req_data->email = '';
            $data->req = $req_data;
            $parameter = $service->generate_reports_data($req_data,5);
            $submissions_data = $service->get_submission($parameter,5);
            
        }
        $data->submissions = $submissions_data->submissions;
        $data->submissions_count = $submissions_data->submissions_count;
        $data->submissions_chart = $submissions_data->submissions_chart;
        $view = $this->mv_handler->setView("reports_submissions");
        $view->render($data);
    }
    public function submission_export($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->submission_export($model, $service, $request, $params, $this);
        }
        if($_POST && (isset($request->req['rm_filter_date']) || isset($request->req['rm_form_id']))){
            $filter_date = $request->req['rm_filter_date'];
            if($filter_date){
                $date = explode('-',$filter_date);
                $start_date = $date[0];
                $end_date = $date[1];
            }
            $req_data  = new stdClass;
            $req_data->start_date = $start_date;
            $req_data->end_date = $end_date;
            $req_data->filter_date = $filter_date;
            $req_data->form_id = $request->req['rm_form_id'];
            $parameter = $service->generate_reports_data($req_data,0);
            $submission_ids = $service->get_submission($parameter,0);
            if(empty($submission_ids->submissions)) return false;
            $submission_id = array();
            foreach($submission_ids->submissions as $submission){
               $submission_id[] = $submission->submission_id; 
            }
            $submissions = $service->prepare_submission_export_data($req_data,$submission_id);
            $csv = $service->create_csv($submissions,'rm_reports_submissions');

        $service->download_file($csv);

        unlink($csv) or die(__("Can not unlink file",'custom-registration-form-builder-with-submission-manager'));
        }
    }
    
    public function attachments($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->attachments($model, $service, $request, $params, $this);
        }
        
        $data = new stdClass;
        
        $view = $this->mv_handler->setView("reports_attachments");
        $view->render($data);
    }
    
    public function attachments_download_all($model, $service, $request, $params)
    {   
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->attachments_download_all($model, $service, $request, $params, $this);
        }
        
    }
    public function payments($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->payments($model, $service, $request, $params, $this);
        }
        $data = new stdClass;
        $view = $this->mv_handler->setView("reports_payments");
        $view->render($data);
    }
    public function payments_download($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->payments_download($model, $service, $request, $params, $this);
        }
    }
    
    public function form_compare($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->form_compare($model, $service, $request, $params, $this);
        }
        $data = new stdClass;
        $view = $this->mv_handler->setView("reports_compare");
        $view->render($data);
        
    }
    
    public function login($model, $service, $request, $params){
       $data = new stdClass;
        $data->forms = RM_Utilities::get_forms_dropdown($service);
        $req_data  = new stdClass;
        if($_GET && (isset($request->req['rm_filter_date']) || isset($request->req['rm_login_status']))){
            $filter_date = $request->req['rm_filter_date'];
            if($filter_date){
                $date = explode('-',$filter_date);
                $start_date = $date[0];
                $end_date = $date[1];
            }
            $req_data->start_date = $start_date;
            $req_data->end_date = $end_date;
            $req_data->filter_date = $filter_date;
            $req_data->status = $request->req['rm_login_status'];
            $data->req = $req_data;
            
        }else{
            $req_data->start_date = date('Y-m-d', strtotime(' -6 day'));
            $req_data->end_date = date('Y-m-d');
            $req_data->filter_date = date('Y/m/d', strtotime(' -6 day')).'-'.date('Y/m/d');
            $req_data->status = 'all';
            $data->req = $req_data;
            
        }
        $parameter = $service->generate_login_parameter($req_data);
        $login_data = $service->get_logins($parameter,5);
        $data->logins = $login_data->logins;
        $data->login_count = $login_data->login_count;
        $data->chart_success = $login_data->login_chart->chart_success;
        $data->chart_failure = $login_data->login_chart->chart_failure;
        $data->chart_date = $login_data->login_chart->chart_date;
       $view = $this->mv_handler->setView("reports_login");
       $view->render($data); 
    }
    public function login_download($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->login_dwonload($model, $service, $request, $params, $this);
        }
        $data = new stdClass;
        $data->forms = RM_Utilities::get_forms_dropdown($service);
        $req_data  = new stdClass;
        if($_GET && (isset($request->req['rm_filter_date']) || isset($request->req['rm_login_status']))){
            $filter_date = $request->req['rm_filter_date'];
            if($filter_date){
                $date = explode('-',$filter_date);
                $start_date = $date[0];
                $end_date = $date[1];
            }
            $req_data->start_date = $start_date;
            $req_data->end_date = $end_date;
            $req_data->filter_date = $filter_date;
            $req_data->status = $request->req['rm_login_status'];
            $data->req = $req_data;
            
        }else{
            $req_data->start_date = date('Y-m-d', strtotime(' -6 day'));
            $req_data->end_date = date('Y-m-d');
            $req_data->filter_date = date('Y/m/d', strtotime(' -6 day')).'-'.date('Y/m/d');
            $req_data->status = 'all';
            $data->req = $req_data;
            
        }
        $parameter = $service->generate_login_parameter($req_data);
        $login_data = $service->get_logins($parameter,0); 
        $export_data = $service->prepare_login_export_data($login_data);
        $csv = $service->create_csv($export_data, 'rm_reports_login');
        $service->download_file($csv);
    
    }
    
    public function notifications($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon') && method_exists('RM_Reports_Controller_Addon', 'notifications')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->notifications($model, $service, $request, $params, $this);
        }
        return ;
    }
    public function notification_add($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon') && method_exists('RM_Reports_Controller_Addon', 'notifications')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->notification_add($model, $service, $request, $params, $this);
        }
        return ;
    }
    public function notification_remove($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon') && method_exists('RM_Reports_Controller_Addon', 'notifications')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->notification_remove($model, $service, $request, $params, $this);
        }
        return ;
    }
    
    public function notification_enable_disable($model, $service, $request, $params){
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Reports_Controller_Addon') && method_exists('RM_Reports_Controller_Addon', 'notifications')) {
            $addon_controller = new RM_Reports_Controller_Addon();
            return $addon_controller->notification_enable_disable($model, $service, $request, $params, $this);
        }
        return ;
    }
}
