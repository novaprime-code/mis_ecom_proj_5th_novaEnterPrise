<?php
if (!defined('WPINC')) {
    die('Closed');
}
$rm_premium_image_url = RM_IMG_URL . "pro/";
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
?>

<div class="rm_pr_page">
    <!---Header Area-->
    

    
    
    <!---Content Area-->
    <div class="rm_pr_content">
    <!----Main Pitch---->

         <div class="rm-box-wrap rm-premium-box-wrap">       
            <div class="rm-box-row">
                <div class="rm-box-col-12">
                    <a href="<?php echo esc_url(RM_Utilities::comparison_page_link()); ?>" target="_blank"><img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/svg/rm-premium-banner.svg'); ?>" class="aligncenter" alt="img" title="RegistrationMagic"></a>
                </div>
           
            </div>        
        </div>
        <!----Features---->
        <!----Row 1-->
        <div class="rm_pr_feature_block">
            <div class="rm_pr_feature_box_title">
                <span><?php _e("Top 49 reasons to upgrade!",'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <div class="m_pr_block_mg-logo"><img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/mg-logo.png'); ?>"></div>
            </div>
            <hr />
            <div class="rm_pr_row">
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8DD;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Manual Approvals Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style="display: block;"><?php _e("Review and approve individual users instead of default auto registrations. A quick approval link is added to the admin notifications for extra convenience.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE06F;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Submission Notes Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Attach Admin Note to your user submissions. Frontend notes are visible to the users and can be sent as notification to them.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                  <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE812;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Role Based Forms Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Bound user roles to forms so that users registering through the form will be automatically assigned the bound role. Alternatively, allow users to choose a role themselves from a list of pre-approved role selection. The option adds a new drop down field inside the form.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
             
            </div>
            <div class="rm_pr_row">
                
                  <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE638;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Token System Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Generate and provide your users unique token after form submission. The token will also be attached to the form submission in dashboard area.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                   <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE163;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("External Submission Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Forward form data to an external URL using HTTP POST method. Useful for integrating RegistrationMagic with numerous other web apps.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                   <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE0DA;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Access Control Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add conditional access control to your form. Allow users within specific age group, with secret passphrase and/or User Role to fill out forms.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
              
              
            </div>
            <div class="rm_pr_row">
               <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE262;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("56 Field Types package",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Full set of custom field types. Now you can build any type of form.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE164;</i></div>
                    <div class="rm_pr_feature_title"><?php _e('Export and Filter Ext.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e('Export all or filtered submissions as CSV. Download individual submissions as PDF. Filter submissions based on date range.', 'custom-registration-form-builder-with-submission-manager') ?></div>
                </div>
                
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE6C4;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Field Analytics Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Piecharts divided by options chosen by the users on checkbox, radio box, drop down and country fields.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>

            </div>
            
            <div class="rm_pr_row">
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8A1;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Advanced Paid Registrations Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add Selection Boxes, DropDown and User Defined price options to your paid registrations.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>
                
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8F7;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Paid User Roles Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add charges to user roles which will appear as payment on the forms bound to that role.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE2BC;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Attachments Browser Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("View all attachments as cards inside a dedicated area. You can download individual attachments or all of them as compressed zip file.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                
            </div>
            <div class="rm_pr_row">
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE429;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Global Overrides Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Not happy with same Global Settings applied to all your forms? Want to configure a single form differently? Global overrides allows you to override these settings for individual forms.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div> 
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE869;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Custom Field with RegEx Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Need a new field type that isn't listed? Want to accept only specific type of field values? Now you can use regular expression to create your own custom fields with this extension.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE06B;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("PDF Branding Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Brand your submission PDFs &amp;&nbsp;Printouts with customized logo and taglines.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                </div>
                
                <div class="rm_pr_row">
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE32A;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Advanced Security Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add extra security options to your forms including banning IPs, Spammy Domains, reserve important usernames and define password strength options.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>   
                    
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE02F;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Multi-Page Form Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Turn your forms into multi step pages with previous and next buttons. Last page submits the form. Name pages separately and show them above the form fields.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE86F;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("HTML Embed Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Embed your forms where short code cannot go - including different sites and third party pages.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>
     
                </div>
                
                <div class="rm_pr_row">
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE86D;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Customizable MagicPopup Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Use Magic Popup as navigation menu with option to add custom links.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>   
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE415;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("PDF Notification Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Form will be attached to admin notification email as PDF file.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE158;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Email Username Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Use email instead of username during new user registration. Users can now login with their emails too.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>  
      
                </div>
                
      
            
             <div class="rm_pr_row">
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE838;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Default Forms Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Assign a registration form as default form for a user role.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                 
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE880;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Saved Searches Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Save your regular submission searches as filters for quick productivity boost.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE0BA;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("User Directory Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Display registered users as directory on front end of your site.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
           
            </div>
            
            
            <div class="rm_pr_row">
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE168;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("User Inbox Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e('Logged in users can check all the messages they have received from the admin in a new "Inbox" tab on front end area.','custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8B9;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Automation Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Created automated workflows and tasks to offload manual tasks to RegistrationMagic's Automation Manager.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8A6;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Unique Values Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Accept only unique values for your form fields. No two users can submit same value for fields marked unique.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
            </div>
            
            <div class="rm_pr_row">
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE0D0;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("User Submission Cap Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Define a fixed number of times a single user can submit a specific form.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>  
                   <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE0B6;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Conditional Fields Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add conditional logic to your form fields and control their appearance based on other field values.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                  <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">account_box</i></div>
                    <div class="rm_pr_feature_title"><?php _e("User Meta Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Define User-Meta keys in field setting and save values directly in WordPress User Meta Table.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
            </div>
            
            <div class="rm_pr_row">
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8E8;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Email Verification Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Verify user's email address by sending account activation links.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE7F4;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Intelligent Contact Form Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Want to know what the user wrote to you earlier with contact form submission? Wish to have purchase history of your WooCommerce customer attached to a new support request? Need to see purchased downloads of Easy Digital Downloads buyer with form data? Time to add some intelligence to your submission notifications. Introducing, message shortcodes which dynamically fetch user information from their history on your site and provide you with deeper user insights attached to the submitted content.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                   <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">label</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Custom Status Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Now create, customize and apply custom status to submissions to comply with your registration approval process.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
            </div>
            
            <div class="rm_pr_row">
                     
                    <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons"> how_to_reg </i></div>
                    <div class="rm_pr_feature_title"><?php _e("2FA Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Add an extra layer of security and greatly reduce risk of unauthorized access by enforcing Two-Factor Authentication to your site.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    </div>
                 
                     <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons"> block </i></div>
                    <div class="rm_pr_feature_title"><?php _e("Login IP Ban Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Ban visitor IPs based on login behavior. Set rules or prompts to activate IP block.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                     
                     
                         <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons"> verified_user </i></div>
                    <div class="rm_pr_feature_title"><?php _e("Username Validation Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Allow Username field on the login for to accept both Username and Email, or only Username.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                 
                 </div>
            
            <div class="rm_pr_row">
        
           
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE8D3;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Social Login Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Allow users to log into your site using popular social networks like Google, Twitter, Microsoft and Instagram, apart from existing Facebook login.",'custom-registration-form-builder-with-submission-manager'); ?>
                    </div>
                </div>
                  
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/woocommerce.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("WooCommerce Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Combine the power of RegistrationMagic with WooCommerce for ultimate shopping experience for your customers.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div> 
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/mailpoet.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("MailPoet Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Subscribe registering users to your MailPoet lists directly from your registration forms.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                  
                  
              </div>
            
            <div class="rm_pr_row">
               <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon rm-mailpoet"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/rm-mailpoet.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("MailPoet 3 Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Subscribe registering users to latest version of MailPoet lists directly from your registration forms.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>  
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/dropbox.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("Dropbox Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Automatically upload submission PDFs to your Dropbox folder. Useful for archiving and sharing.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><i class="material-icons">&#xE55F;</i></div>
                    <div class="rm_pr_feature_title"><?php _e("Google Maps Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Make use of Google's powerful maps inside your forms. Works with Address and Map field types.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
            </div>
            
            
            <div class="rm_pr_row">
                  
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/mailchimp.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("MailChimp Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("If you are a power MailChimp user you may want to map all your custom fields with registration forms. This extension makes this&nbsp;possible.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                     
                   <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/aweber.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("Aweber Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Integrate your forms with powerful Aweber system.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                   
                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/stripe.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("Stripe Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Accept payments through ever popular Stripe payment gateway for paid registrations.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                   
            </div>
            
            
             <div class="rm_pr_row">
                
            <div class="rm_pr_block_small">
                <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/adn.png"></div>
                <div class="rm_pr_feature_title"><?php _e("Authorize.Net Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                <div class="rm_pr_feature_desc" style=""><?php _e("Connect your Authorize.Net account as payment option to start accepting credit card payments.",'custom-registration-form-builder-with-submission-manager'); ?></div>
            </div>
            <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/rm-wepay-logo.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("WePay Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc"><?php _e("Accept payments through your forms using popular WePay gateway.",'custom-registration-form-builder-with-submission-manager'); ?></div>
            </div> 
                 
            <div class="rm_pr_block_small">
                <div class="rm_pr_feature_icon"><i class="material-icons">&#xE0CE;</i></div>
                <div class="rm_pr_feature_title"><?php _e("Offline Payments Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                <div class="rm_pr_feature_desc" style=""><?php _e("Add more flexibility to your payments system by turning on offline payments. Provide users with payment instructions and activate them after receiving payments.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div> 
                 
                 
              </div>
     
 
         
            <div class="rm_pr_row">

                <div class="rm_pr_block_small">
                    <div class="rm_pr_feature_icon"><img class="rm_feature_icon" src="<?php echo esc_url(RM_IMG_URL); ?>premium/newsletter.png"></div>
                    <div class="rm_pr_feature_title"><?php _e("Newsletter Ext.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <div class="rm_pr_feature_desc" style=""><?php _e("Add users to your Newsletter’s subscriber lists right from your registration forms.",'custom-registration-form-builder-with-submission-manager'); ?></div>
                </div>
                
          
            </div>
            
            
             <hr /><br><br>
                
            </div>
        
     
        
     <div class="rm-box-wrap rm-premium-box-wrap">       
            <div class="rm-box-row">
                <div class="rm-box-col-12">
                    <a href="<?php echo esc_url(RM_Utilities::comparison_page_link()); ?>" target="_blank"><img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/svg/rm-premium-banner.svg'); ?>" class="aligncenter" alt="img" title="RegistrationMagic"></a>
                </div>
           
            </div>        
        </div>
                           
        </div>
       
        
        

        
        
        
        
</div>
<!--[if IE 8 ]>
<style>
    .rm_pr_smoke, .rm_pr_clouds_front, .rm_pr_clouds_far { display: none !important; }
</style>
<![endif]-->


