<?php
class Element_jQueryUIBirthDate extends Element_jQueryUIDate {
 
    public function jQueryDocumentReady($init=true) {
        //parent::jQueryDocumentReady(false);
        $jquery = "jQuery(\"#{$this->_attributes['id']}\").datepicker({dateFormat:\"{$this->_attributes['data-dateformat']}\",changeMonth:true,changeYear:true,yearRange: '1900:+50'";

        if(isset($this->_attributes['required_range']) && $this->_attributes['required_range'] == 1) {
            if(isset($this->_attributes['required_min_range'])) {
                $jquery .= ",minDate:new Date(\"{$this->_attributes['required_min_range']}\")";
            }

            if(isset($this->_attributes['required_max_range'])) {
                $jquery .= ",maxDate:new Date(\"{$this->_attributes['required_max_range']}\")";
            } else {
                $jquery .= ",maxDate:new Date()";
            }
        } else {
            $jquery .= ",maxDate:new Date()";
        }
        
        $jquery .= "});";
        
        echo wp_kses_post($jquery);
    }
    
}