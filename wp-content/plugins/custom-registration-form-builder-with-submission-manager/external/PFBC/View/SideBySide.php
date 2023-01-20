<?php

class View_SideBySide extends View
{

    public $class = "form-horizontal";

    public function render()
    {
        $this->_form->appendAttribute("class", $this->class);
       
        echo '<form', wp_kses_post($this->_form->getAttributes()), '><fieldset>';
        $this->_form->getErrorView()->render();
        echo '<input type="hidden" name="rm_form_sub_id" value='.wp_kses_post($this->_form->getAttribute('id')).'>';
        echo '<input type="hidden" name="rm_form_sub_no" value='.wp_kses_post($this->_form->getAttribute('number')).'>';
        $elements = $this->_form->getElements();
        $elementSize = sizeof($elements);
        $elementCount = 0;
        for ($e = 0; $e < $elementSize; ++$e)
        {
            $element = $elements[$e];
            
            $ele_adv_opts = $element->getAdvanceAttr();
            $row_class = trim("rmrow ".$ele_adv_opts['exclass_row']);
            $input_class = trim("rminput ".$ele_adv_opts['exclass_input']);

            if ($element instanceof Element_Hidden || $element instanceof Element_HTML)
                $element->render();
            elseif ($element instanceof Element_Button || $element instanceof Element_HTMLL)
            {
                if ($e == 0 || (!$elements[($e - 1)] instanceof Element_Button && !$elements[($e - 1)] instanceof Element_HTMLL))
                    echo '<div class="buttonarea">';
                else
                    echo ' ';

                $element->render();

                if (($e + 1) == $elementSize || (!$elements[($e + 1)] instanceof Element_Button && !$elements[($e + 1)] instanceof Element_HTMLL))
                    echo '</div>';
            }elseif ($element instanceof Element_HTMLH || $element instanceof Element_HTMLP)
            {
                echo '<div class="'.esc_attr($row_class).'">', wp_kses_post($element->render()), '', wp_kses_post($this->renderDescriptions($element)), '</div>';
                ++$elementCount;
            } elseif($element instanceof Element_Captcha )
            {
                echo '<div class="'.esc_attr($row_class).' rm_captcha_fieldrow">', wp_kses_post($this->renderLabel($element)), '<div class="'.esc_attr($input_class).'">', wp_kses_post($element->render()), '</div>', wp_kses_post($this->renderDescriptions($element)), '</div>';            
            } else
            {
                echo '<div class="'.esc_attr($row_class).'">', wp_kses_post($this->renderLabel($element)), '<div class="rminput">', wp_kses_post($element->render()), '</div>', wp_kses_post($this->renderDescriptions($element)), '</div>';
                ++$elementCount;
            }
        }

        echo '</fieldset></form>';
    }

    public function renderLabel(Element $element)
   {
        $label = $element->getLabel();
        
        if (!empty($label))
        {
            //echo '<label class="control-label" for="', $element->getAttribute("id"), '">';
            echo '<div class="rmfield" for="', esc_attr($element->getAttribute("id")), '"><label>';
            echo wp_kses_post($label);
            if ($element->isRequired())
            {
                echo '<sup class="required">* </sup>';
            }          
            echo '</label></div>';
        }
    }

}
