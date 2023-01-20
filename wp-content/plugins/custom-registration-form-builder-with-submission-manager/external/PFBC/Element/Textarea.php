<?php
class Element_Textarea extends Element {
	public $_attributes = array("rows" => "5");

	public function render() {
        echo "<textarea", wp_kses_post($this->getAttributes("value")), ">";
        if(!empty($this->_attributes["value"]))
			echo esc_html($this->filter(html_entity_decode($this->_attributes["value"])));
        echo "</textarea>";
    }
}
