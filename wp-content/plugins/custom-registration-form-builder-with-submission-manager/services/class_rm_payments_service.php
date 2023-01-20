<?php

/**
 *
 *
 * @author CMSHelplive
 */
class RM_Payments_Service extends RM_Services
{
    public function __construct($model = null) {
        parent::__construct($model);
    }
    
    public function output_pdf_for_invoice(RM_Submissions $submission, $outputconf = array('name' => 'invoice.pdf', 'type' => 'D')) {
        
        if(defined('REGMAGIC_ADDON') && class_exists('RM_Payments_Service_Addon')){
            $addon_service = new RM_Payments_Service_Addon;
            $addon_service->output_pdf_for_invoice($submission, $outputconf, $this);
        }
    }
    
    
    
}