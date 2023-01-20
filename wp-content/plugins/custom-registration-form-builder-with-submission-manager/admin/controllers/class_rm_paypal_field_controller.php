<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of calss_rm_paypal_field_controller
 *
 * @author CMSHelplive
 */
class RM_PayPal_Field_Controller
{

    public $mv_handler;

    function __construct()
    {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function add($model, $service, $request, $params)
    {
        if ($this->mv_handler->validateForm("add-paypal-field"))
        {
            if (isset($request->req['multisel_name_value']))
            {
                $request->req['option_label'] = maybe_serialize($request->req['multisel_name_value']);
                $request->req['option_price'] = maybe_serialize($request->req['multisel_price_value']);
            }

            $extra_options = array();
            
            if(isset($request->req['show_on_form']))
                $extra_options['show_on_form'] = 'yes';
            else
                $extra_options['show_on_form'] = 'no';
            
            if(isset($request->req['allow_quantity']))
                $extra_options['allow_quantity'] = 'yes';
            else
                $extra_options['allow_quantity'] = 'no';

            if(isset($request->req['min_quantity']))
                $extra_options['min_quantity'] = $request->req['min_quantity'];
            else
                $extra_options['min_quantity'] = 0;

            if(isset($request->req['max_quantity']))
                $extra_options['max_quantity'] = $request->req['max_quantity'];
            else
                $extra_options['max_quantity'] = 0;
            
            $request->req['extra_options'] = $extra_options;

            $model->set($request->req);
            
            if (isset($request->req['field_id']))
                $service->update($model, $service, $request, $params);
            else
                $service->add($model, $service, $request, $params);
            RM_Utilities::redirect(admin_url('/admin.php?page=' . $params['xml_loader']->request_tree->success));
            //$this->view->render();
        }
        else
        {
             $data = new stdClass;

            // Edit for request
            if (isset($request->req['rm_field_id']))
            {
                $model->load_from_db($request->req['rm_field_id']);
                $data->show_on_form = (int)($model->get_extra_options('show_on_form') == 'yes');
                $data->allow_quantity = (int)($model->get_extra_options('allow_quantity') == 'yes');
                $data->min_quantity = $model->get_extra_options('min_quantity');
                $data->max_quantity = $model->get_extra_options('max_quantity');
            }
            else
            {
                $data->show_on_form = 1;
                $data->allow_quantity = 0;
                $data->min_quantity = 0;
                $data->max_quantity = 0;
            }

            $data->model = $model;
            $view = $this->mv_handler->setView("paypal_field_add");
            $data->selected_field = $request->req['rm_field_type'];
            $gopts= new RM_Options();
            $data->currency_symbol = $gopts->get_currency_symbol();
            $view->render($data);
        }
    }

    public function manage($model, $service, $request, $params)
    {
        if (isset($request->req['rm_action']) && $request->req['rm_action'] === 'delete')
            $this->remove_field($model, $service, $request, $params);

        $data = new stdClass;
        if(defined('REGMAGIC_ADDON'))
            $fields_data = $service->get_all($model->get_identifier(), 0, 0, '*', '', true);
        else
            $fields_data = $service->get($model->get_identifier(), array('type'=>'fixed'), array('%s'), 'results', 0, 0, '*', null, true);
        $data->fields_data = $fields_data;
        
        $view = $this->mv_handler->setView("paypal_field_manager");
        $view->render($data);
    }

    public function remove_field($model, $service, $request, $params)
    {

        if (isset($request->req['rm_field_id']))
            $result = $service->remove($request->req['rm_field_id']);
        else
            die(RM_UI_Strings::get('MSG_NO_FIELD_SELECTED'));
    }

    public function remove($model, $service, $request, $params)
    {
        $selected = isset($request->req['rm_selected']) ? $request->req['rm_selected'] : null;
        $service->remove($selected);
        $this->manage($model, $service, $request, $params);
    }

}
