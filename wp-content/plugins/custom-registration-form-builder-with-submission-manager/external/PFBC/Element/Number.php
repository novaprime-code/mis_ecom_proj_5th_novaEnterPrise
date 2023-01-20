<?php
class Element_Number extends Element_Textbox {
	public $_attributes = array("type" => "number", "min" => 0);

	public function render() {
		$this->validation[] = new Validation_Numeric;
		parent::render();
	}
}
