<?php

/**
 * Form row model
 * 
 * This class represents the model for a form's rows and has the properties 
 * of a row and also have the DB operations for the model 
 *
 * @author cmshelplive
 */
class RM_Rows extends RM_Base_Model {

    public $row_id;
    public $form_id;
    public $page_no;
    public $columns;
    public $class;
    public $gutter;
    public $bmargin;
    public $width;
    public $heading;
    public $subheading;
    public $field_ids;
    public $row_options;
    public $row_order;
    public $valid_columns;
    private $valid_options;

    //private $initialized;
    //errors of field data validation
    private $errors;

    public function __construct() {
        $this->row_id = NULL;
        $this->row_order = 99999999;
        $this->valid_columns = array(
            '1' => 1,
            '1:1' => 2,
            '2:1' => 2,
            '1:1:1' => 3,
            '1:1:1:1' => 4
        );
        $this->valid_options = array();
        $this->field_ids = array();
        $this->row_options = new stdClass;
        foreach ($this->valid_options as $valid_option)
            $this->row_options->$valid_option = null;
    }

    /***getters***/
    
    public function get_valid_options() {
        return $this->valid_options;
    }
    
    public static function get_identifier() {
        return 'ROWS';
    }

    public function get_row_id() {
        return $this->row_id;
    }

    public function get_form_id() {
        return $this->form_id;
    }

    public function get_row_heading() {
        return $this->heading;
    }
    
    public function get_row_subheading() {
        return $this->subheading;
    }
    
    public function get_row_class() {
        return $this->class;
    }
    
    public function get_row_width() {
        return $this->width;
    }
    
    public function get_row_bmargin() {
        return $this->bmargin;
    }
    
    public function get_row_gutter() {
        return $this->gutter;
    }
    
    public function get_row_columns() {
        return $this->columns;
    }
    
    public function get_field_ids() {
        $ids = maybe_unserialize($this->field_ids);
        return $ids;
    }

    public function get_row_options() {
        return $this->row_options;
    }
    
    public function get_page_no() {
        return $this->page_no;
    }
    
    public function get_row_order()
    {
        return $this->row_order;
    }

    
    /***setters***/
    
    public function set_page_no($page_no) {
        $this->page_no = $page_no;
    }
        
    public function set_row_columns($row_columns) {
        $this->columns = $row_columns;
    }

    public function set_row_gutter($row_gutter) {
        $this->gutter = $row_gutter;
    }
    
    public function set_row_width($row_width) {
        $this->width = $row_width;
    }
    
    public function set_row_bmargin($row_bmargin) {
        $this->bmargin = $row_bmargin;
    }
    
    public function set_row_class($row_class) {
        $this->class = $row_class;
    }

    public function set_row_heading($row_heading) {
        $this->heading = $row_heading;
    }
    
    public function set_row_subheading($row_subheading) {
        $this->subheading = $row_subheading;
    }

    public function set_form_id($form_id) {
        $this->form_id = $form_id;
    }
    
    public function set_field_ids($field_ids) {
        $this->field_ids = $field_ids;
    }
    
    public function set_row_options($options) {
        $this->row_options = RM_Utilities::merge_object($options, $this->row_options);
    }
    
    public function set_row_order($order)
    {
        $this->row_order = $order;
    }

    public function set(array $request) {   
        foreach ($request as $property => $value)
        {
            $set_property_method = 'set_' . $property;

            if (method_exists($this, $set_property_method)) {
                $this->$set_property_method($value);
            } elseif (in_array($property, $this->valid_options, true)) {
                if (is_array($value)) {
                     $value= count($value);
                }
                   
                $this->field_options->$property = $value;
            }
        }
        
        return $this->initialized = true;
    }

    
    /****Validations****/

    private function validate_form_id() {
        if (empty($this->form_id)) {
            $this->errors['FORM_ID'] = __("No Form ID defined",'custom-registration-form-builder-with-submission-manager');
        }
        if (!is_int($this->form_id)) {
            $this->errors['FORM_ID'] = __("Not a valid Form ID",'custom-registration-form-builder-with-submission-manager');
        }
    }

    private function validate_value()
    {
        //validations for value of field; 
    }

    private function validate_order()
    {
        if (empty($this->row_order))
        {
            $this->errors['ORDER'] = __("Row order can not be empty.",'custom-registration-form-builder-with-submission-manager');
        }
        if (is_int($this->row_order))
        {
            $this->errors['ORDER'] = __("Invalid row order.",'custom-registration-form-builder-with-submission-manager');
        }
    }

    public function is_valid() {
        $this->validate_form_id();
        $this->validate_order();

        return count($this->errors) === 0;
    }

    public function errors() {
        return $this->errors;
    }

    
    /****Database Operations****/

    public function insert_into_db() {

        if ($this->row_id) {
            return false;
        }

        $data = array(
            'form_id' => $this->form_id,
            'page_no' => $this->page_no,
            'columns' => $this->columns,
            'class' => $this->class,
            'gutter' => $this->gutter,
            'bmargin' => $this->bmargin,
            'width' => $this->width,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'field_ids' => maybe_serialize($this->field_ids),
            'row_order' => $this->row_order,
            'row_options' => maybe_serialize($this->get_row_options()),
        );

        $data_specifiers = array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
        );

        $result = RM_DBManager::insert_row('ROWS', $data, $data_specifiers);

        if (!$result) {
            return false;
        }

        $this->row_id = $result;

        return $result;
    }

    public function update_into_db() {
        
        if (!$this->row_id) {
            return false;
        }
        
        $data = array(
            'form_id' => $this->form_id,
            'page_no' => $this->page_no,
            'columns' => $this->columns,
            'class' => $this->class,
            'gutter' => $this->gutter,
            'bmargin' => $this->bmargin,
            'width' => $this->width,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'field_ids' => maybe_serialize($this->field_ids),
            'row_order' => $this->row_order,
            'row_options' => maybe_serialize($this->get_row_options()),
        );

        $data_specifiers = array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
        );

        $result = RM_DBManager::update_row('ROWS', $this->row_id, $data, $data_specifiers);

        if (!$result) {
            return false;
        }

        return true;
    }

    public function load_from_db($row_id, $should_set_id = true) {

        $result = RM_DBManager::get_row('ROWS', $row_id);

        if (null !== $result) {
            if ($should_set_id)
                $this->row_id = $row_id;
            else
                $this->row_id = null;
            $this->form_id = absint($result->form_id);
            $this->page_no = absint($result->page_no);
            $this->row_order = absint($result->row_order);
            $this->columns = sanitize_text_field($result->columns);
            $this->class = sanitize_text_field(strtolower($result->class));
            $this->gutter = absint($result->gutter);
            $this->bmargin = absint($result->bmargin);
            $this->width = absint($result->width);
            $this->heading = sanitize_text_field($result->heading);
            $this->subheading = sanitize_text_field($result->subheading);
            $this->field_ids = maybe_unserialize($result->field_ids);
            $this->set_row_options(maybe_unserialize($result->row_options));
        } else {
            return false;
        }
        return true;
    }

    public function remove_from_db() {
        return RM_DBManager::remove_row('ROWS', $this->row_id);
    }

}