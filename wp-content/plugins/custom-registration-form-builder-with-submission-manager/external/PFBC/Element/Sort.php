<?php
class Element_Sort extends OptionElement {
    public $jQueryOptions;

	public function getCSSFiles() {
		return array(
			$this->_form->getPrefix() . "://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css"
		);
	}

	public function getJSFiles() {
		return array(
			'jquery-ui-min' => $this->_form->getPrefix() . "://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"
		);
	}

    public function jQueryDocumentReady() {
        echo 'jQuery("#', wp_kses_post($this->_attributes["id"]), '").sortable(', wp_kses_post($this->jQueryOptions()), ');';
        echo 'jQuery("#', wp_kses_post($this->_attributes["id"]), '").disableSelection();';
    }

    public function render() {
        if(substr($this->_attributes["name"], -2) != "[]")
            $this->_attributes["name"] .= "[]";

        echo '<ul id="', esc_attr($this->_attributes["id"]), '">';
        foreach($this->options as $value => $text) {
            $value = $this->getOptionValue($value);
            echo '<li class="ui-state-default"><input type="hidden" name="', esc_attr($this->_attributes["name"]), '" value="', esc_attr($value), '"/>', wp_kses_post($text), '</li>';
        }
        echo "</ul>";
    }

    public function renderCSS() {
        echo '#', wp_kses_post($this->_attributes["id"]), ' { list-style-type: none; margin: 0; padding: 0; cursor: pointer; max-width: 400px; }';
        echo '#', wp_kses_post($this->_attributes["id"]), ' li { margin: 0.25em 0; padding: 0.5em; font-size: 1em; }';
    }
}
