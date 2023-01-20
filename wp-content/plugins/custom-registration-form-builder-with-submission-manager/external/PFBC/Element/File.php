<?php
class Element_File extends Element_FileNative {
	public $_attributes = array("type" => "file");
        
   public function __construct($label, $name, array $properties = null) {
       parent::__construct($label, $name, $properties);
       if($this->isRequired())
            $this->validation[] = new Validation_File("", true);
       else
            $this->validation[] = new Validation_File;
   }    
   
    public function jQueryDocumentReady(){
        // Set form encryption type to enable the file upload
        echo "
            var formInput = jQuery(\"#".esc_html($this->_attributes['id'])."\");
            var form = jQuery(formInput[0].form);
            var enctype= jQuery(form).prop('enctype','multipart/form-data');
        ";
        $file_size = get_option('rm_option_file_size');
        $file_size_error = get_option('rm_option_file_size_error');
        $file_size_error = empty($file_size_error) ? RM_UI_Strings::get('ERROR_CUSTOM_FILE_SIZE') : $file_size_error;
        if(!empty(intval($file_size))) {
            echo "
            jQuery(\"#".esc_html($this->_attributes['id'])."\").on(\"change\", function (e) {
                    jQuery(\".".esc_html($this->_attributes['id'])."-error\").remove();
                    var files = e.currentTarget.files;
                    var file_size_error = '".esc_html($file_size_error)."';
                    for (var x in files) {
                        var filesize = (files[x].size/1024).toFixed(4);
                        if (filesize > ".esc_html($file_size).") {
                            file_size_error = file_size_error.replace('%s',files[x].name);
                            file_size_error = file_size_error.replace('%d','".esc_html($file_size)."');
                            jQuery(\"#".esc_html($this->_attributes['id'])."\").parent(\".rminput\").append('<div><label class=\"".esc_html($this->_attributes['id'])."-error rm-form-field-invalid-msg\">'+file_size_error+'</label></div>');
                        }
                    }
                });
            ";
        }
    }
	
    public function render() {
        $multiple= get_option('rm_option_allow_multiple_file_uploads');
        if($multiple=="yes" && !isset($this->_attributes['multiple'])){
            $this->_attributes['multiple']= "multiple";
            $this->_attributes['name']= $this->_attributes['name'].'[]';
        }
        elseif(isset($this->_attributes['multiple']))
        {
            $this->_attributes['name']= $this->_attributes['name'].'[]'; 
        }
        else
        {
            
        }
//        if($this->isRequired())
//            $this->validation[] = new Validation_File("", true);
//        else
//            $this->validation[] = new Validation_File;
        
        parent::render();
    }
}
