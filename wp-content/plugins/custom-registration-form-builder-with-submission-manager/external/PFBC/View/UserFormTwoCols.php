<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserForm
 *
 * @author hawk
 */
class View_UserFormTwoCols extends View_UserForm{
    
    public function renderLabel(Element $element)
    {
      
        $label = $element->getLabel();
        
        if (!empty($label))
        {
            //echo '<label class="control-label" for="', $element->getAttribute("id"), '">';
            $field_class = trim("rmfield ".$element->getAdvanceAttr('exclass_field'));
            echo '<div class="'.esc_attr($field_class).'" for="', wp_kses_post($element->getAttribute("id")), '" style="',wp_kses_post($element->getAttribute("labelstyle")),'"><label>';
            
            
            echo wp_kses_post($label);
            if ($element->isRequired()  && ($element->show_asterix()=='yes'))
            {
                echo '<sup class="required">&nbsp;*</sup>';
            }
            //check if label contains a field hint (text shown after label)
            $hint = $element->getAttribute("field_hint");                               
            if($hint)
                echo "<span class='rm-field-hint'>".wp_kses_post($hint)."</span>";
            echo '</label></div>';
        }
    }
    
}
