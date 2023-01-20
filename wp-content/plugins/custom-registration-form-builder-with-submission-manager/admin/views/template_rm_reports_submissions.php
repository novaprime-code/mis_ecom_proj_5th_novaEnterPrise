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
    include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_reports_submissions.php');
}
else{
    $selected_form='all';
    if(isset($data->req->form_id)){
       $selected_form = $data->req->form_id;
    }
    $forms = '';
    foreach ($data->forms as $id => $form_title):
        if($selected_form == $id){
            $forms .= '<option value="'.$id.'" selected>'.$form_title.'</option>';
        }
        else{
            $forms .= '<option value="'.$id.'">'.$form_title.'</option>';
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
            <div class="rm-reports-dashboard rm-box-title rm-box-mb-25">
                <?php _e('Form Submissions Report','custom-registration-form-builder-with-submission-manager');?>
            </div>
            <div class="rm-reports-filters-box rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-ptb">
                    <div class="rm-filter-reports-form">
                        <form class="rm-report-forms rm-box-wrap" action="" method="GET">
                            <input type="hidden" name="page" value="rm_reports_submissions"/>
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
                                                    <label><?php _e('Select Form', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                                    <select class="" name="rm_form_id"><option value="all"><?php _e('All', 'custom-registration-form-builder-with-submission-manager'); ?></option><?php echo wp_kses($forms, RM_Utilities::expanded_allowed_tags()); ?></select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                <div class="rm-box-col-1"></div>
                                <div class="rm-box-col-3">
                                    <div class="rm-box-btn-wrap rm-box-text-right">  
                                        <button type="submit" id="rm_submit_btn" class="rm_btn rm-btn rm-btn-primary"><?php _e('Search', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                        <button type="button" id="rm_reset_btn" class="rm-btn-secondary rm-btn" onclick="window.location.href='<?php echo admin_url('?page=rm_reports_submissions'); ?>'"><?php _e('Reset', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            <div class="rm-reports-submission">
                
                <?php if(!empty($data->submissions)):?>
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
                            <th><?php _e('Email','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('Form ID','custom-registration-form-builder-with-submission-manager'); ?></th>
                            <th><?php _e('View','custom-registration-form-builder-with-submission-manager'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data->submissions as $submission):?>
                        <tr>
                            <td><?php echo date('d M, Y',strtotime($submission->submitted_on));?></td>
                            <td><?php echo esc_html($submission->user_email);?></td>
                            <td><?php echo esc_html($submission->form_id);?></td>
                            <td class="rm-reports-submission-view"><a target="__blank" href="<?php echo admin_url('?page=rm_submission_view&rm_submission_id='.$submission->submission_id);?>"><span class="material-icons"> open_in_new </span></a></td>
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
                    <div class="rm-total-record-found rm-box-col-10"><?php echo 'Total records found: '.$data->submissions_count;?></div>
                        <?php
                        $form_id='all';
                        $filter_date='';
                        if(isset($data->req->form_id) && $data->req->form_id != 'all'){
                            $form_id = $data->req->form_id;
                        }
                        if(isset($data->req->filter_date)){
                            $filter_date = $data->req->filter_date;
                        }
                        if($form_id != 'all' && $data->submissions_count):?>
                        <div class="rm-report-submission-export rm-reports-export rm-box-col-2 rm-box-btn-wrap rm-box-text-right">
                            <form action="" method="post">
                                <input type="hidden" name="rm_slug" value="rm_reports_submission_export">
                                <input type="hidden" name="rm_filter_date" value="<?php echo esc_attr($filter_date); ?>">
                                <input type="hidden" name="rm_form_id" value="<?php echo esc_attr($form_id); ?>">
                                <button type="submit" name="submit" class="rm-reports-export-btn rm-btn-primary rm-btn"><?php _e('Export All','custom-registration-form-builder-with-submission-manager'); ?></button>
                            </form>
                        </div>
                        <?php endif;?>
                        <?php if($form_id == 'all' && $data->submissions_count):?>
                        <div class="rm-report-submission-export rm-reports-export rm-box-col-2 rm-box-btn-wrap rm-box-text-right">
                            <button type="button" class="rm-reports-export-btn rm-btn-primary rm-btn rm-locked" style="opacity:.5;"><?php _e('Export All','custom-registration-form-builder-with-submission-manager'); ?></button>
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

});
</script>
<script>
jQuery(window).load(function(e){
	load_submission_chart();

});
function load_submission_chart(){
    var ctx = document.getElementById("rmSubChart");
    var myChart = new Chart(ctx, {
        type : "line",
        data: {
            datasets: [
              {
                label: 'Submissions',
                data: <?php echo json_encode($data->submissions_chart->chart_value);?>,
                borderColor: '#32b871',
                backgroundColor: 'rgb(50, 184, 112, 0.15)',
                fill: true,
                borderWidth: 1,
                tension: .5
              }
            ],
            labels: <?php echo json_encode($data->submissions_chart->chart_date);?>
        },
        options: {
            /*scales: {
                xAxes: [{
                    count:10,
                    barThickness: 6,  // number (pixels) or 'flex'
                    maxBarThickness: 8 // number (pixels)
                }]
            },*/
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