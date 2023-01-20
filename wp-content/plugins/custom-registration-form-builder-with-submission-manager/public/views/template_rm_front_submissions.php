<?php
if (!defined('WPINC')) {
    die('Closed');
}
//if(defined('REGMAGIC_ADDON')) include_once(RM_ADDON_PUBLIC_DIR . 'views/template_rm_front_submissions.php'); else {
/**
 * Plugin Template File[For Front End Submission Page]
 */
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
$user_id = isset($data->user) ? $data->user->ID : null;
?>
<!-- setup initial tab -->
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    var g_rm_customtab, g_rm_acc_color;
    jQuery(document).ready(function () {

        //get accent color from theme
        g_rm_acc_color = jQuery('#rm_dummy_link_for_primary_color_extraction').css('color');
        if (typeof g_rm_acc_color == 'undefined')
            g_rm_acc_color = '#000';

        var rmagic_jq = jQuery(".rmagic");
        rmagic_jq.find("[data-rm_apply_acc_color='true']").css('color', g_rm_acc_color);
        rmagic_jq.find("[data-rm_apply_acc_bgcolor='true']").css('background-color', g_rm_acc_color);
        g_rm_customtab = new RMCustomTabs({
            container: '#rm_front_sub_tabs',
            animation: 'fade',
            accentColor: g_rm_acc_color,
            activeTabIndex: <?php echo wp_kses_post($data->active_tab_index); ?>
        });
        redirecttosametab(<?php echo wp_kses_post($data->active_tab_index); ?>);
    });

    function get_tab_and_redirect(reqpagestr) {
        var tab_index = g_rm_customtab.getActiveTabIndex();
        var curr_url = window.location.href;
        var sign = '&';
        if (curr_url.indexOf('?') === -1) {
            sign = '?';
        }
        window.location.href = curr_url + sign + reqpagestr + '&rm_tab=' + tab_index;
    }

    function resetpassword(){
        document.getElementById("rm_front_submissions_respas_form").submit();
    }
</script></pre>
<a id='rm_dummy_link_for_primary_color_extraction' style='display:none' href='#'></a>
<?php
if (!$data->payments && !$data->submissions && $data->is_user !== true) {
    ?>

    <div class="rmnotice-container"><div class="rmnotice"><?php echo wp_kses_post(RM_UI_Strings::get('MSG_NO_DATA_FOR_EMAIL')); ?></div></div>
    <?php
}
?>
<div class="rmagic" id="rm_front_sub_tabs" style="display: none;"> 

    <!-----Operationsbar Starts-->

    <div class="operationsbar">
        <!--        <div class="rmtitle">Submissions</div>-->
        <div class="nav">

            <?php
            $setting = new RM_Setting_Service();
            echo wp_kses($setting->generate_profile_tab_links(), RM_Utilities::expanded_allowed_tags());

            if(isset($data->user,$data->user->ID) && defined('REGMAGIC_ADDON'))
                echo apply_filters('rm_before_front_tabtitle_listing', '',$user_id);

            ?>

        </div>


    </div>
    <!--------Operationsbar Ends----->

    <!-------Contentarea Starts----->

    <!----Table Wrapper---->

    <?php
    // Let the extensions add any menu before action buttons.
    if(isset($data->user,$data->user->ID) && defined('REGMAGIC_ADDON'))
        echo apply_filters('rm_before_front_tabcontent_listing', '',$user_id);
    echo wp_kses($setting->rm_profile_tabs_content($data, $user_id), RM_Utilities::expanded_allowed_tags());
    
    if(isset($data->user,$data->user->ID))
        echo wp_kses(apply_filters('rm_after_front_tabcontent_listing', '',$user_id),RM_Utilities::expanded_allowed_tags());
    ?>
</div>
<?php //} ?>