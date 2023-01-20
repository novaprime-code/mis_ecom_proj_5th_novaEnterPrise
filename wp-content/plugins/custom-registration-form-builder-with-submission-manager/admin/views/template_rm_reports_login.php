<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
wp_enqueue_script('chart_js');
wp_enqueue_script('script_rm_moment');
wp_enqueue_script('script_rm_daterangepicker');
wp_enqueue_style('style_rm_daterangepicker');
if(defined('REGMAGIC_ADDON')) {
    include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_reports_login.php');
}
else{
    $selected_status='all';
    if(isset($data->req->status)){
       $selected_status = $data->req->status;
    }
    $status = '';
    $status_list = array('all'=>'All','success'=>'Success','failure'=>'Failure');
    foreach ($status_list as $id => $label):
        if($selected_status === $id){
            
            $status .= '<option value="'.$id.'" selected>'.$label.'</option>';
        }
        else{
            $status .= '<option value="'.$id.'">'.$label.'</option>';
        }
    endforeach;
    $date_filter='';
    $start_date='';
    $end_date='';
    if(isset($data->req->filter_date)){
        $date_filter = $data->req->filter_date;
        $start_date = $data->req->start_date;
        $end_date = $data->req->end_date;
    }
    ?>
    <div class="rmagic">
        <div class="rmagic-reports">
            <div class="rm-reports-dashboard rm-box-title rm-box-mb-25 ">
                <?php _e('Login Records Report','custom-registration-form-builder-with-submission-manager');?>
            </div>
            <div class="rm-reports-filters-box rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-ptb">
                    <div class="rm-filter-reports-form">
                        <form class="rm-report-forms rm-box-wrap" action="" method="GET">
                                <input type="hidden" name="page" value="rm_reports_login"/>
                                <div class="rm-report-form rm-box-row rm-box-bottom">
                                    <div class="rm-box-col-8">
                                        <div class="rm-box-row">
                                            <div class="rm-box-col-6">
                                                <div class="rm-report-filter-attr">
                                                    <label><?php _e('Date', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                                    <div id="rm-reportrange">
                                                       <input type="text" name="rm_filter_date" value="<?php echo esc_attr($date_filter); ?>" onkeydown="return false;"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rm-box-col-6">    
                                                <div class="rm-report-filter-attr">
                                                    <label><?php _e('Status', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                                    <select class="" name="rm_login_status">
                                                        <?php echo wp_kses($status, RM_Utilities::expanded_allowed_tags()); ?>
                                                    </select>
                                                </div>  
                                            </div> 
                                        </div>


                                    </div>
                                    <div class="rm-box-col-1"></div>
                                    <div class="rm-box-col-3">
                                        <div class="rm-box-btn-wrap rm-box-text-right">
                                           <button type="submit" id="rm_submit_btn" class="rm-btn-primary rm-btn"><?php _e('Search', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                           <button type="button" id="rm_reset_btn" class="rm-btn-secondary rm-btn" onclick="window.location.href='<?php echo admin_url('?page=rm_reports_login'); ?>'"><?php _e('Reset', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
            </div>
            <div class="rm-reports-submission">
                
                <?php if(!empty($data->logins)):?>
                <div class="rm-reports-submission-charts rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-p">
                    <canvas id="rmSubChart"></canvas>
                </div>
                <div class="rm-reports-submissions-preview rm-report-preview">
                    <div class="rm-reports-preview-title rm-box-title rm-box-mb-25"><?php _e('Preview','custom-registration-form-builder-with-submission-manager');?></div>
                    <div class="rm-reports-preview-sub-title rm-box-sub-title rm-box-mb-25"><?php _e('This preview only displays initial few rows of the generated report. The downloaded file will have complete report data.','custom-registration-form-builder-with-submission-manager');?></div>
                    <table class="rm-report-submission-tables rm-reports-table">
                        <thead>
                        <tr>
                            <th><?php _e('Date','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('Username Used','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('Email','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('Result','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('Browser','custom-registration-form-builder-with-submission-manager'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data->logins as $login):?>
                        <tr>
                            <td><?php echo date('d M, Y',strtotime($login->time));?></td>
                            <td><?php echo esc_html($login->username_used);?></td>
                            <td><?php echo esc_html($login->email);?></td>
                            <td class="rm-login-report-status"><?php if($login->result == 'success'):
                                    echo '<span class="material-icons rm-login-status-'.$login->result.'">check</span>';
                                else:
                                    echo '<span class="material-icons rm-login-status-'.$login->result.'">block</span>';
                                endif;?>
                            </td>
                            <td><?php echo esc_html($login->browser);?></td>
                            
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <?php else:?>
                    <div class="rmagic-cards"><div class="rm-reports-no-data-found rmnotice rm-box-border rm-box-mb-25"><?php _e('No data found.','custom-registration-form-builder-with-submission-manager');?></div></div>
                <?php
                endif;?>
                    <div class="rm-total-record-wrap rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-ptb rm-box-wrap">
                        <div class="rm-box-row rm-box-center">
                            <div class="rm-total-record-found rm-box-col-10"><?php echo 'Total records found: ' . $data->login_count; ?></div>
                                <?php
                                if (isset($data->req->filter_date)) {
                                    $filter_date = $data->req->filter_date;
                                }
                                if ($data->login_count):
                                    ?>
                                <div class="rm-report-submission-export rm-reports-export rm-box-col-2 rm-box-btn-wrap rm-box-text-right">
                                    <form action="" method="post">
                                        <input type="hidden" name="rm_slug" value="rm_reports_login_download">
                                        <input type="hidden" name="rm_filter_date" value="<?php echo esc_attr($filter_date); ?>">
                                        <input type="hidden" name="rm_login_status" value="<?php echo esc_attr($selected_status); ?>">
                                        <button type="submit" name="submit" class="rm-reports-export-btn rm-btn-primary rm-btn"><?php _e('Export All', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                    </form>
                                </div>
                                <?php endif;?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">

jQuery(function() {
    var start = moment('<?php echo esc_html($start_date);?>');
    var end = moment('<?php echo esc_html($end_date);?>');
    console.log(start+ 'HHH' +end);
    function cb(start, end) {
        jQuery('#rm-reportrange input').val(start.format('YYYY/MM/DD') + '-' + end.format('YYYY/MM/DD'));
    }

    jQuery('#rm-reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: new Date(),
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }              
    }, cb);

    cb(start, end);
    
    console.log("test")

});
</script>
<script>
jQuery(window).load(function(e){
	load_submission_chart();

});
function load_submission_chart(){
    var ctx = document.getElementById("rmSubChart");
    var myChart = new Chart(ctx, {
        
        data: {
            datasets: [
              <?php if($data->chart_success):?>
              {
                type : "line",
                label: 'Success',
                data: <?php echo json_encode($data->chart_success);?>,
                borderColor: '#32b871',
                backgroundColor: 'rgb(50, 184, 112, .2)',
                fill: true,
                borderWidth: 1,
                tension: .5
              },
              <?php endif; ?>
              <?php if($data->chart_failure):?>
              {
                type : "line",
                label: 'Failure',
                data: <?php echo json_encode($data->chart_failure);?>,
                borderColor: '#d51616c7',
                backgroundColor: 'rgb(251 9 53 / 8%)',
                fill: true,
                borderWidth: 1,
                tension: .5
              }
              <?php endif; ?>
            ],
            labels: <?php echo json_encode($data->chart_date);?>
        },
        options: {
            scale: {
                    y:{
                        ticks: { 
                                precision: 0
                                },
                        min: 0
                    }
            }
        }
    });
}
</script>