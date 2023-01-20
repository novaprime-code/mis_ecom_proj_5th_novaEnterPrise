<?php
class Element_Select extends OptionElement {
	public $_attributes = array();
	public $frontend = false;

	public function __construct($label, $name, array $options, array $properties = null, array $advance_opts = null, $frontend = false) {
		$this->frontend = $frontend;
		parent::__construct($label, $name, $options, $properties, $advance_opts);
	}

	public function render() { 
		if(isset($this->_attributes["value"])) {
			if(!is_array($this->_attributes["value"]))
				$this->_attributes["value"] = array($this->_attributes["value"]);
		}
		else
			$this->_attributes["value"] = array();
                 
		if(!empty($this->_attributes["multiple"]) && substr($this->_attributes["name"], -2) != "[]")
			$this->_attributes["name"] .= "[]";

		echo '<select', wp_kses_post($this->getAttributes(array("value", "selected"))), '>';
		$selected = false;

		/*
		 * Insert a default blank value in front end form generation
		 */
        
		foreach($this->options as $value => $text) {
            if(!$value && !$text){

            }
			
            $value = $this->getOptionValue($value);
            /*
            if(isset($this->_attributes["value"]) && !empty($this->_attributes["value"]) && empty($value)) {
                continue;
            }
            */
			echo '<option value="', $this->filter($value), '"';
			if(!$selected && in_array($value, $this->_attributes["value"])) {
				echo 'selected="selected"';
			}	
			echo '>', wp_kses_post($text), '</option>';
        }
		echo '</select>';
		if($this->frontend)
			echo '<script>jQuery(document).ready(function() {jQuery("select[name='.wp_kses_post($this->_attributes['name']).']").select2();});</script>'; 
	}

	public function getCSSFiles()
    {
		return array(
			'style_rm_select2' => RM_BASE_URL . 'public/css/style_rm_select2.css',
		);
    }

	public function getJSFiles()
    {
		return array(
			'style_rm_select2' => RM_BASE_URL . 'public/js/script_rm_select2.js',
		);
    }
        
    public function getOptions()
    {
        return $this->options;
    }
}
