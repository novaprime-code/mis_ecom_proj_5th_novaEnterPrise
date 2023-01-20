<?php 

if (!class_exists('wcmamtx_add_login_content_class')) {

    class wcmamtx_add_login_content_class {

        public function __construct() {

            add_action( 'init', array($this,'wcmamtx_add_custom_endpoint_page') );

        }


        public function wcmamtx_add_custom_endpoint_page() {

            $wcmamtx_plugin_login = get_option('wcmamtx_plugin_login');



            $add_before_login_register = isset($wcmamtx_plugin_login['wcmamtx_add_content_before_login_register']) ? $wcmamtx_plugin_login['wcmamtx_add_content_before_login_register'] : "no";




            $content_before_login_register = isset($wcmamtx_plugin_login['content_before_login_register']) ? $wcmamtx_plugin_login['content_before_login_register'] : "";




            if (isset($add_before_login_register) && ($add_before_login_register == "yes") && ($content_before_login_register != "")) {

                

                 add_action('woocommerce_before_customer_login_form',function() use ( $content_before_login_register ) {
                    
                    echo $content_before_login_register; 

                    
                 },10,1);

            }


            

            $add_before_login_start = isset($wcmamtx_plugin_login['wcmamtx_before_login_form_start']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form_start'] : "no";




            $before_login_form_start_content = isset($wcmamtx_plugin_login['wcmamtx_before_login_form_start_content']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form_start_content'] : "";


            if (isset($add_before_login_start) && ($add_before_login_start == "yes") && ($before_login_form_start_content != "")) {

                

                 add_action('woocommerce_login_form_start',function() use ( $before_login_form_start_content ) {
                    
                    echo $before_login_form_start_content; 

                    
                 },10,1);

            }


            

            $add_before_login = isset($wcmamtx_plugin_login['wcmamtx_before_login_form']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form'] : "no";




            $before_login_form_content = isset($wcmamtx_plugin_login['wcmamtx_before_login_form_content']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form_content'] : "";


            if (isset($add_before_login) && ($add_before_login == "yes") && ($before_login_form_content != "")) {

                

                 add_action('woocommerce_login_form',function() use ( $before_login_form_content ) {
                    
                    echo $before_login_form_content; 

                    
                 },10,1);

            }


            

            $add_before_login_end = isset($wcmamtx_plugin_login['wcmamtx_before_login_form_end']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form_end'] : "no";




            $before_login_form_end_content = isset($wcmamtx_plugin_login['wcmamtx_before_login_form_end_content']) ? $wcmamtx_plugin_login['wcmamtx_before_login_form_end_content'] : "";


            if (isset($add_before_login_end) && ($add_before_login_end == "yes") && ($before_login_form_end_content != "")) {

                

                 add_action('woocommerce_login_form_end',function() use ( $before_login_form_end_content ) {
                    
                    echo $before_login_form_end_content; 

                    
                 },10,1);

            }


            


            $add_before_register_start = isset($wcmamtx_plugin_login['wcmamtx_before_register_form_start']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form_start'] : "no";




            $before_register_form_start_content = isset($wcmamtx_plugin_login['wcmamtx_before_register_form_start_content']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form_start_content'] : "";

            


            if (isset($add_before_register_start) && ($add_before_register_start == "yes") && ($before_register_form_start_content != "")) {

                

                 add_action('woocommerce_register_form_start',function() use ( $before_register_form_start_content ) {
                    
                    echo $before_register_form_start_content; 

                    
                 },10,1);

            }


            

            $add_before_register = isset($wcmamtx_plugin_login['wcmamtx_before_register_form']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form'] : "no";




            $before_register_form_content = isset($wcmamtx_plugin_login['wcmamtx_before_register_form_content']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form_content'] : "";


            if (isset($add_before_register) && ($add_before_register == "yes") && ($before_register_form_content != "")) {

                

                 add_action('woocommerce_register_form',function() use ( $before_register_form_content ) {
                    
                    echo $before_register_form_content; 

                    
                 },10,1);

            }


           


            $add_before_register_end = isset($wcmamtx_plugin_login['wcmamtx_before_register_form_end']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form_end'] : "no";




            $before_register_form_end_content = isset($wcmamtx_plugin_login['wcmamtx_before_register_form_end_content']) ? $wcmamtx_plugin_login['wcmamtx_before_register_form_end_content'] : "";


            if (isset($add_before_register_end) && ($add_before_register_end == "yes") && ($before_register_form_end_content != "")) {

                

                 add_action('woocommerce_register_form_end',function() use ( $before_register_form_end_content ) {
                    
                    echo $before_register_form_end_content; 

                    
                 },10,1);

            }


            

            $add_after_login_register = isset($wcmamtx_plugin_login['wcmamtx_add_content_after_login_register']) ? $wcmamtx_plugin_login['wcmamtx_add_content_after_login_register'] : "no";




            $content_after_login_register = isset($wcmamtx_plugin_login['content_after_login_register']) ? $wcmamtx_plugin_login['content_after_login_register'] : "";


            if (isset($add_after_login_register) && ($add_after_login_register == "yes") && ($content_after_login_register != "")) {

                

                 add_action('woocommerce_after_customer_login_form',function() use ( $content_after_login_register ) {
                    
                    echo $content_after_login_register; 

                    
                 },10,1);

            }


        }


   }
}

new wcmamtx_add_login_content_class();


?>