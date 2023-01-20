<?php
class Element_Radio extends OptionElement {
	public $_attributes = array("type" => "radio");
	public $inline;
        
        
        public function jQueryDocumentReady(){
            if(isset($this->_attributes["rm_is_other_option"]) && $this->_attributes["rm_is_other_option"] == 1){
                echo <<<JS
            
                   
                   jQuery("input[name='{$this->_attributes['name']}']").change(function(){
                   var obj_op = jQuery("#{$this->_attributes['id']}_other_section");
                    if(jQuery(this).attr('id')=='{$this->_attributes["id"]}_other')
                    {
                        obj_op.slideDown();
                        obj_op.children("input[type=text]").attr('disabled', false);
                        obj_op.children("input[type=text]").attr('required', true);
                    } 
                    else
                    {
                         obj_op.slideUp();
                         obj_op.children("input[type=text]").attr('disabled', true);
                         obj_op.children("input[type=text]").attr('required', false);
                    }
                  
                 });
                    
                jQuery('#{$this->_attributes["id"]}_other_input').change(function(){
                    jQuery('#{$this->_attributes["id"]}_other').val(jQuery(this).val());
                    if(jQuery(".data-conditional").length>0){
                        jQuery(".data-conditional").conditionize({});
                    }
                }) ;     
           
JS;
            }
              
             
        }
	public function render() { 
		$labelClass = 'rmradio';//$this->_attributes["type"];
		if(!empty($this->inline))
			$labelClass .= " inline";

		$count = 0;
                //Extract color attribute so that can be applied to text as well.
                $style_str = "";
                if(isset($this->_attributes["style"]))
                {
                    $al = explode(';',$this->_attributes["style"]);                    
                    foreach($al as $a)
                    {
                        if(strpos(trim($a),"color:")=== 0)
                        {
                            $style_str ='style="'.$a.'";'; 
                            break;
                        }
                    }
                }
                echo '<ul class="' .esc_attr($labelClass). '" '.wp_kses_post($style_str).'">';
		foreach($this->options as $value => $text) {
			$value = $this->getOptionValue($value);

			//echo '<label class="', $labelClass . '"> <input id="', $this->_attributes["id"], '-', $count, '"', $this->getAttributes(array("id", "value", "checked")), ' value="', $this->filter($value), '"';
			echo '<li> <input id="', esc_attr($this->_attributes["id"]), '-', esc_attr($count), '"', wp_kses_post($this->getAttributes(array("id", "value", "checked"))), ' value="', esc_attr($this->filter($value)), '"';
			if(isset($this->_attributes["value"]) && $this->_attributes["value"] == $value)
				echo ' checked="checked"';
			//echo '/> ', $text, ' </label> ';
			echo '/><label for="', esc_attr($this->_attributes["id"]), '-', esc_attr($count),'"> ', wp_kses_post($text), '</label> </li> ';
			++$count;
		}                
                if(isset($this->_attributes["rm_is_other_option"]) && $this->_attributes["rm_is_other_option"] == 1){                       //get value of "other" field to be prefilled if provided.
                    $other_val = '';
                    if(isset($this->_attributes["value"]) && !in_array($this->_attributes["value"], array_values($this->options)))
                            $other_val = $this->_attributes["value"];
                   echo '<li>';
                    if(empty($this->_attributes["rm_textbox"])) {
                        $other_label = __('Other','custom-registration-form-builder-with-submission-manager');
                    } else {
                        $other_label = $this->_attributes["rm_textbox"];
                    }
                   if($other_val){
                     echo      '<input id="'.esc_attr($this->_attributes["id"]).'_other" type="radio" value="" name="'.wp_kses_post($this->getAttribute("name")).'" style="'.wp_kses_post($this->getAttribute("style")).'" checked><label for="'.esc_attr($this->_attributes["id"]).'_other">'.$other_label.'</label></li>'.
                        '<li id="'.esc_attr($this->_attributes["id"]).'_other_section">'.
                        '<input style="'.wp_kses_post($this->getAttribute("style")).'" type="text" id="'.esc_attr($this->_attributes["id"]).'_other_input" name="'.wp_kses_post($this->getAttribute("name")).'" value="'.esc_attr($other_val).'">';
                   }
                   else
                   {
                     echo  '<input id="'.esc_attr($this->_attributes["id"]).'_other" type="radio" value="" name="'.wp_kses_post($this->getAttribute("name")).'" style="'.wp_kses_post($this->getAttribute("style")).'"><label for="'.esc_attr($this->_attributes["id"]).'_other">'.$other_label.'</label></li>'.
                        '<li id="'.esc_attr($this->_attributes["id"]).'_other_section" style="display:none">'.
                        '<input style="'.wp_kses_post($this->getAttribute("style")).'" type="text" id="'.esc_attr($this->_attributes["id"]).'_other_input" name="'.wp_kses_post($this->getAttribute("name")).'" disabled>';

                   }
                    echo   '</li>';
                }
            echo '</ul>';
	}
}
