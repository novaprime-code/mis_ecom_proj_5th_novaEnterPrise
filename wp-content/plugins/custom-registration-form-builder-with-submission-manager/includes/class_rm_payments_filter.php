<?php

class RM_Payments_Filter extends RM_Filter {
    public $form_id;

    public function __construct($request, $service) {
        $params = array(
        'rm_field_to_search' => 'rm_field_to_search',
        'rm_interval' => 'rm_interval',
        'rm_fromdate' => 'rm_fromdate',
        'rm_dateupto' => 'rm_dateupto',
        'filter_tags'=>'filter_tags');
        
        $default_param_values = array('rm_interval' => 'all', 'rm_field_to_search' => null, 'rm_fromdate' => null,
        'rm_dateupto' => null,'filter_tags'=>null);
        
        parent::__constuct($request,$service, $params, $default_param_values);
        
        $this->set_form($service);
        if ((isset($this->params['rm_field_to_search']) && (int) $this->params['rm_field_to_search']) || isset($this->params['filter_tags'])) {
            $this->searched = true;
        } 
       
        $this->set_pagination();
    }

    public function set_form($service) {
        if (isset($this->request->req['rm_form_id']))
            $this->form_id = $this->request->req['rm_form_id'];
        else
            $this->form_id = $service->get('FORMS', 1, array('%d'), 'var', 0, 15, $column = 'form_id', null, true);
    }

    public function get_form() {
        return $this->form_id;
    }

    public function get_records() {
        $this->records =  RM_DBManager::get_payments($this,$this->form_id);
        return $this->records;
    }
    
    public function set_pagination(){
        $total_entries=null;
        
        $req_page = null;
        if (isset($this->request->req['rm_search_initiated']))
            $req_page = 1; //reset pagination in case a new search is initiated.
        else
            $req_page = (isset($this->request->req['rm_reqpage']) && $this->request->req['rm_reqpage'] > 0) ? $this->request->req['rm_reqpage'] : 1;
        
        
        $this->filters['rm_form_id']= $this->form_id;
        $this->pagination= new RM_Pagination($this->filters,$this->request->req['page'],0,$req_page);
        $total_entries = RM_DBManager::get_payments($this,$this->form_id,"count(*) as count");
        
        $this->pagination->set_total_entries($total_entries[0]->count);
       
        
    } 
    
}
