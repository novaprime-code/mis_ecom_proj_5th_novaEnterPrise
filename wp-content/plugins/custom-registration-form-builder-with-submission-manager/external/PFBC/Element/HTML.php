<?php
class Element_HTML extends Element {
	public function __construct($value) {
		$properties = array("value" => $value);
		parent::__construct("", "", $properties);
	}

	public function render() { 
		echo wp_kses($this->_attributes["value"], RM_Utilities::expanded_allowed_tags());
	}
}
