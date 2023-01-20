<?php

/**
 * 
 */

class RM_Dashboard_Widget_Controller
{

     public $mv_handler;

    function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function display($model, $service, $request, $params)
    {
        $data = new stdClass;

        $submissions = $service->get('SUBMISSIONS', 1, null, 'results', 0, 10, '*', 'submitted_on', true);

        $sub_data = array();

        if($submissions)
        {
            foreach ($submissions as $submission)
            {
               //echo "<br>ID: ".$submission->form_id." : ".RM_Utilities::localize_time($submission->submitted_on, 'M dS Y, h:ia')." : ";
               $name = $service->get('FORMS', array('form_id' => $submission->form_id), array('%d'), 'var', 0, 10, 'form_name');
               $date = RM_Utilities::localize_time($submission->submitted_on, 'd M Y'); //Previously "M dS Y, h:ia".
               $payment_status = $service->get('PAYPAL_LOGS', array('submission_id' => $submission->submission_id), array('%d'), 'var', 0, 10, 'status');

               $sub_data[] = (object)array('submission_id'=>$submission->submission_id, 'name'=>$name, 'date'=>$date, 'payment_status'=>$payment_status);
            }
            
            $data->total_sub = count($submissions);
        }     

        $data->submissions = $sub_data; 
        $data->count = $service->get_count_summary();

        $view = $this->mv_handler->setView("dashboard_widget");
        $view->render($data);
    }
    public function dashboard($model, $service, $request, $params){
        $data = new stdClass;
        $data->forms = RM_DBManager::get_all('FORMS');
        $submissions = $service->get('SUBMISSIONS', 1, null, 'results', 0, 5, '*', 'submitted_on', true);
        $sub_data = array();
        if($submissions)
        {
            foreach ($submissions as $submission)
            {
               $name = $service->get('FORMS', array('form_id' => $submission->form_id), array('%d'), 'var', 0, 10, 'form_name');
               $date = RM_Utilities::localize_time($submission->submitted_on, 'd M Y, h:iA'); //Previously "M dS Y, h:ia".
               $payment_status = $service->get('PAYPAL_LOGS', array('submission_id' => $submission->submission_id), array('%d'), 'var', 0, 10, 'status');

               $sub_data[] = (object)array('submission_id'=>$submission->submission_id, 'user_email'=>$submission->user_email,'name'=>$name, 'date'=>$date, 'payment_status'=>$payment_status);
            }
            
            $data->total_sub = count($submissions);
        }     

        $data->submissions = $sub_data; 
        $data->count = $service->get_count_summary();
        $data->popular_forms = $service->get_popular_forms();
        $top_forms_label =array();
        $top_forms_count = array();
        if(!empty($data->popular_forms)){
            $count =1;
            $top_forms = array();
            foreach ($data->popular_forms as $key => $form) {
                $top_forms_label[] = $form['form_name'];
                $top_forms_count[] = $form['count'];
                if($count>=5) break;
                $count++;
            }
        }
        else{
            $top_forms_label =array("Form 1","Form 2","Form 3","Form 4","Form 5");
            $top_forms_count = array(1,2,3,4,5);
        }
        $interval = 'days';
        if(isset($request->req['rm_ur'])){
            $interval = $request->req['rm_ur'];
        }
        $login_interval = 7;
        if(isset($request->req['rm_tr'])){
           $login_interval = $request->req['rm_tr']; 
        }
        $users = $service->get_user_statics($interval);
        $data->users = $users;
        $data->top_forms_label = $top_forms_label;
        $data->top_forms_count = $top_forms_count; 
        $data->latest_forms = $service->get('FORMS', 1, null, 'results', 0, 5, '*', null, true);
        $data->latest_users= $service->get_latest_users();
        $data->rm_ur = $interval;
        $data->rm_tr = $login_interval;
        $data->statics = $service->get_dashboard_statics();
        $data->feature = $service->get_feature_data($login_interval);
        $data->login_logs = $service->get_logins_data();
        $data->day_wise_stat = $service->get_login_logs_stats($login_interval);
        $data->latest_attachments = $service->get_latest_attachments();
        $data->latest_payments = $service->get_latest_payments();
        $view = $this->mv_handler->setView('dashboard');
        $view->render($data);
    }
}
