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
class View_UserForm extends View_SideBySide{
    
    public function render()
    {
        $this->_form->appendAttribute("class", $this->class);
        $onsubmit_callback = 'gotonext_'.$this->_form->getAttribute('id');
        echo '<form novalidate onsubmit="return '.wp_kses_post($onsubmit_callback).'()" autocomplete="off" ', wp_kses_post($this->_form->getAttributes()), '><fieldset>';
        $this->_form->getErrorView()->render();
        echo '<input type="hidden" name="rm_form_sub_id" value='.wp_kses_post($this->_form->getAttribute('id')).'>';
        echo '<input type="hidden" name="rm_form_sub_no" value='.wp_kses_post($this->_form->getAttribute('number')).'>';
        echo '<input type="hidden" name="rm_cond_hidden_fields" id="rm_cond_hidden_fields" value="">';
        $elements = $this->_form->getElements();
        $elementSize = sizeof($elements);
        $elementCount = 0;
        for ($e = 0; $e < $elementSize; ++$e)
        {
            $element = $elements[$e];
            $ele_adv_opts = $element->getAdvanceAttr();
            $row_class = trim("rmrow ".$ele_adv_opts['exclass_row']);
            $input_class = trim("rminput ".$ele_adv_opts['exclass_input']);

            if ($element instanceof Element_Button || $element instanceof Element_HTMLL)
            {
                if ($e == 0 || (!$elements[($e - 1)] instanceof Element_Button && !$elements[($e - 1)] instanceof Element_HTMLL))
                    echo '<div class="buttonarea">';
                else
                    echo ' ';

                $element->render();

                if (($e + 1) == $elementSize || (!$elements[($e + 1)] instanceof Element_Button && !$elements[($e + 1)] instanceof Element_HTMLL))
                    echo '</div>';
            }else
            {
                $this->renderElement($element);
                if (!($element instanceof Element_Hidden) && !($element instanceof Element_HTML))
                    ++$elementCount;
            }
        }

        echo '</fieldset></form>';
    }
    
    public function renderElement(Element $element)
    {
        $ele_adv_opts = $element->getAdvanceAttr();
        $row_class = trim("rmrow ".$ele_adv_opts['exclass_row']);
        $input_class = trim("rminput ".$ele_adv_opts['exclass_input']);

        if ($element instanceof Element_Hidden || $element instanceof Element_HTML)
            $element->render();
        elseif ($element instanceof Element_HTMLH || $element instanceof Element_HTMLP)
        {               
             echo '<div class="'.esc_attr($row_class).'">', wp_kses_post($element->render()), '', wp_kses_post($this->renderDescriptions($element)), '</div>';            
        } elseif($element instanceof Element_Map )
        {
            $ele_id = $element->getAttribute('id');
            $unique_ele_id = $ele_id."_".$this->_form->getAttribute('id')."_".$this->_form->getAttribute('id');
            $element->setAttribute('id',$unique_ele_id);
            echo '<div class="'.esc_attr($row_class).'">', wp_kses_post($this->renderLabel($element)), '<div class="'.esc_attr($input_class).'">', wp_kses_post($element->render()), '</div>', wp_kses_post($this->renderDescriptions($element)), '</div>';
            
            
        }
        elseif($element instanceof Element_Captcha )
        {
            echo '<div class="'.esc_attr($row_class).' rm_captcha_fieldrow">', wp_kses_post($this->renderLabel($element)), '<div class="'.esc_attr($input_class).'">', wp_kses_post($element->render()), '</div>', wp_kses_post($this->renderDescriptions($element)), '</div>';            
        }
        else
        {
            echo '<div class="'.esc_attr($row_class).'">', wp_kses_post($this->renderLabel($element));
            echo '<div class="'.esc_attr($input_class).'">', wp_kses_post($element->render());
            if($ele_adv_opts['sub_element'] && $ele_adv_opts['sub_element'] instanceof Element && !($element instanceof Element_Checkbox))
                $this->renderElement($ele_adv_opts['sub_element']);
            echo '</div>';
            echo wp_kses_post($this->renderDescriptions($element)), '</div>';
        }
    }
    
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
