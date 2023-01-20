<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This class creates multiple sortable text fields which can be appended.
 * 
 * @internal You must have create an disabled text field with this field with attribute onClick = rm_append_field('li','rm_sortable_elements')
 *
 * @author CMSHelplive
 */
class Element_Textboxsortable extends Element
{

    public $_attributes = array("type" => "text");
    public $prepend;
    public $append;
    public $others;

    public function __construct($label, $name, array $properties = null, array $others = array())
    {
        $configuration = array(
            "label" => $label,
            "name" => $name
        );

        $this->others = $others;

        /* Merge any properties provided with an associative array containing the label
          and name properties. */
        if (is_array($properties))
            $configuration = array_merge($configuration, $properties);
        
        $this->configure($configuration);
    }

    public function render()
    {
        $addons = array();
        if (!empty($this->prepend))
            $addons[] = "input-prepend";
        if (!empty($this->append))
            $addons[] = "input-append";
        if (!empty($addons))
            echo '<div class="', wp_kses_post(implode(" ", $addons)), '">';
        
        $suffix = mt_rand(1, 500);
        $this->renderSortable('start',$suffix);
        $i = 0;
        if(is_array($this->_attributes['value']) && !empty($this->_attributes['value'])) {
            foreach ($this->_attributes['value'] as $key => $value)
            {
                $this->renderSortable("prepend",$suffix);
                $this->renderAddOn("prepend",$suffix);
                $this->setAttribute('value', $value);
                parent::render();
                if (!empty($this->others))
                    $this->renderOthers($this->others, $i);

                $this->renderAddOn("append",$suffix);
                $this->renderSortable("append",$suffix);
                $i++;
            }
        } else {
            $this->renderSortable("prepend",$suffix);
            $this->renderAddOn("prepend",$suffix);
            parent::render();
            if (!empty($this->others))
                $this->renderOthers($this->others, $i);

            $this->renderAddOn("append",$suffix);
            $this->renderSortable("append",$suffix);
        }
        
        $this->renderSortable('close',$suffix);
        $this->renderSortable('add_action',$suffix);
        $this->renderSortable('extra_option',$suffix);

        if (!empty($addons))
            echo '</div>';
    }

    public function renderAddOn($type = "prepend")
    {
        if (!empty($this->$type))
        {
            $span = true;
            if (strpos($this->$type, "<button") !== false)
                $span = false;

            if ($span)
                echo '<span class="add-on">';

            echo wp_kses_post($this->$type);

            if ($span)
                echo '</span>';
        }
    }

    public function renderSortable($type = "prepend",$suffix='')
    {
        if ($type === "start")
            echo '<ul class = "rm_sortable_elements" id = "rm_sortable_elements_'.esc_attr($suffix).'">';
        if ($type === "prepend")
            echo '<li class="appendable_options rm-deletable-options"><span class="rm_sortable_handle"><img alt="" src="'.  esc_url(plugin_dir_url(dirname(dirname(dirname(__FILE__))))).'images/rm-drag-label.png"></span>';
        if ($type === "append")
            echo '<div class="rm_actions" onClick ="rm_delete_appended_field(this,rm_sortable_elements_'.esc_attr($suffix).')"><a href="javascript:void(0)">' . wp_kses_post(RM_UI_Strings::get("LABEL_DELETE")) . '</a></div></li>';
        if ($type === "close")
            echo '</ul>';
        if($type === "add_action")
            echo '<div class="rm_action_container" id="rm_action_container_id"><div class="rm_action" id="rm_action_field_container" onclick="rm_append_field(\'li\',this)"><input type="text" name="rm_dump" id="rm_append_option" class="rm_action_field" required="" readonly="true" value="' .wp_kses_post(RM_UI_Strings::get("VALUE_CLICK_TO_ADD")). ' "></div><div id="rmaddotheroptiontextdiv" style="display:none"><div onclick="jQuery.rm_append_textbox_other(this)">'.wp_kses_post(RM_UI_Strings::get('LABEL_ADD_OTHER')).'</div></div></div>';
    }

    public function renderOthers(array $others, $curr_index)
    {
        $str = "";
        if (count($others) >= 1 && isset($others[0]))
            foreach ($others as $other_one)
            {
                $str = "<input ";
                if(is_array($other_one)){
                foreach ($other_one as $key => $value)
                {
                    if($key == 'value')
                    {
                        if(is_array($value))
                        {
                             if(!empty($value))
								$str .= $key . " = '" . $value[$curr_index] . "' ";
							else
								$str .= $key . " = '' ";
                             continue;
                        }
                    }

                    $str .= $key . " = '" . $value . "' ";
                }
                $str .= ">";}
                else
                    $str = "";
            } else
        {

            $str = "<input ";
            foreach ($others as $key => $value)
            {
                if(!is_int($key))
                {
                    if($key == 'value')
                    {
                        if(is_array($value))
                        {
                             if(!empty($value))
								$str .= $key . " = '" . $value[$curr_index] . "' ";
							else
								$str .= $key . " = '' ";
                             continue;
                        }
                    }
                    
                    $str .= $key . " = '" . $value . "' ";
                }
            }
            $str .= ">";
        }
        echo wp_kses($str, RM_Utilities::expanded_allowed_tags());
    }

}
