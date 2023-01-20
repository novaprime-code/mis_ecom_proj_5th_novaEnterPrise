<?php
if (!defined('WPINC')) {
    die('Closed');
}


wp_enqueue_script('chart_js');

//wp_enqueue_style('style_rm_dashboard');

if(defined('REGMAGIC_ADDON') && class_exists('RM_Dashboard_Widget_Service_Addon') ) {include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_dashboard.php'); } else {
$premium_class = 'rm-locked-section';
?>

<div class="rm-dash-head-wrap">
    <div class="rm-dash-widget-logo"><img src="<?php echo esc_url(RM_IMG_URL.'svg/rm-logo-overview.svg');?>" width="200px"><span class="rm-logo-text"></span></div>
</div>


<div class="rm-dashboard-main-container">
    
     <!---  Head Section---->
    
     <div class="rm-dashboard-header rm-box-border rm-box-white-bg rm-box-mb-25">
    <?php if (isset($data->statics)): ?>
            <div class="rm-box-row">
                <?php foreach ($data->statics as $statics): ?>
                    <div class="rm-box-col-3 rm-box-border-right">
                        <div class="rm-bullet-title">
                            <?php echo wp_kses_post($statics['title']); ?>
                        </div>
                        <div class="rm-bullet-statics">
                            <?php echo wp_kses_post($statics['state']); ?>
                        </div>
                          <div class="rm-bullet-link">
                              <a href="<?php echo admin_url("admin.php?page=".wp_kses_post($statics['link']));?>"><?php echo wp_kses_post($statics['link_label']);?> <span class="material-icons"> navigate_next </span></a>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
       
    <?php endif; ?>  
    
    
    </div>
    
    
    <!--- Ends: Head Section---->
    
    <!--- Second Row Section---->
    
        <div class="rm-box-row rm-box-mb-25">
            
            <div class="rm-box-col-9">
                <div class="rm-box-row rm-box-h-100">
                    <div class="rm-box-col-6">
                        <div class="rm-box-border rm-box-white-bg  rm-dash-counter-chart rm-box-animated">
                            <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_COUNTER')); ?></div>
                            <div class="rm-dash-counter-chart-container">                            
                                <canvas id="formCounter" width="100%" height="80%"></canvas>
                             
                            </div>
                            
                            <div class="rm-dash-demo-notice">   
                                <?php if ($data->count->demo): ?>
                                
                                    <p class="rm-dash-demo-data">
                                        <span class="material-icons"> info </span> <?php _e('Displaying demo data since there are no submissions yet.', 'custom-registration-form-builder-with-submission-manager'); ?>
                                    </p>
                                <?php endif; ?></div>
                        </div>
                    </div>
                    <div class="rm-box-col-6">          
                        <div class="rm-box-border rm-box-white-bg  rm-dash-popular-chart rm-box-animated">
                              <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_FORMS_CHART_TITLE')); ?></div>
                            <div class="rm-dash-popular-chart-container">                              
                                <canvas id="formChart"></canvas>                           
                            </div>
                            
                            <div class="rm-dash-demo-notice">                            
                                 <?php if (empty($data->popular_forms)): ?>
                                    <p class="rm-dash-demo-data">
                                        <span class="material-icons"> info </span> <?php _e('Displaying demo data since there are no submissions yet.', 'custom-registration-form-builder-with-submission-manager'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="rm-box-col-3">

                    <div class="rm-box-border rm-box-white-bg rm_dash_submissions rm-box-animated">
                        <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_WIDGET_TABLE_CAPTION')); ?></div>
                        <?php if (!empty($data->submissions)): ?>
                        
                                    <?php foreach ($data->submissions as $submission): ?>
                                        <div class="rm-submissions-box">            
                                            <div class="rm-submissions-image"> 
                                                <?php 
                                                $user = get_user_by( 'email', $submission->user_email );
                                                if($user):
                                                    if(class_exists('Profile_Magic')):
                                                        $pg_user_avatar_id = get_user_meta( $user->ID, 'pm_user_avatar', true );
                                                        if($pg_user_avatar_id):
                                                            $avatar_url = wp_get_attachment_url($pg_user_avatar_id,'thumbnail');?>
                                                            <img class="fd_img" src="<?php echo esc_url($avatar_url); ?>"><?php
                                                        else:
                                                            $avatar_url = get_avatar_url($user->ID);?>
                                                            <img class="fd_img" src="<?php echo esc_url($avatar_url); ?>">
                                                            <?php endif;
                                                    else:
                                                    $avatar_url = get_avatar_url($user->ID);?>
                                                    <img class="fd_img" src="<?php echo esc_url($avatar_url); ?>">
                                                    <?php endif;
                                                else:?>
                                                    <img class="fd_imgs" src="http://0.gravatar.com/avatar/f9085ebbab1db83bdf8394cc56cb3bb7?s=96&amp;d=mm&amp;r=g">
                                                <?php endif;?>
                                                
                                            </div>
                                            <div class="rm-form-submissions-info">
                                                
                                                <div class="rm-form-submission-email"><?php echo esc_html($submission->user_email);?></div>
                                                <div class="rm-submissions-form-title"><?php
                                                                if ($submission->name)
                                                                    echo esc_html($submission->name);
                                                                else
                                                                    echo wp_kses_post(RM_UI_Strings::get('LABEL_FORM_DELETED'));
                                                                ?>
                                                </div>
                                                <div class="rm-form-submissions-date">
                                                    <?php 
                                                    $sub_date = strtotime($submission->date);
                                                    $current = strtotime(date("Y-m-d"));
                                                    $datediff = $sub_date - $current;
                                                    $difference = floor($datediff/(60*60*24));
                                                    if($difference==0)
                                                    {
                                                       echo '<span class="rm-rep-sub-date">Today </span>';
                                                    }
                                                    else{
                                                       echo '<span class="rm-rep-sub-date">'.date('d M Y',strtotime($submission->date)).'</span>'; 
                                                    }
                                                    echo '<span class="rm-rep-sub-seprator">&#8226;</span>';
                                                    echo '<span class="rm-rep-sub-time">'.date('h:iA',strtotime($submission->date)).'</span>'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="rm-more-submissions"><a href="<?php echo admin_url("admin.php?page=rm_submission_view&rm_submission_id=".$submission->submission_id);?>"><span class="material-icons"> navigate_next </span></a></div>
                                        </div>
                                  <?php endforeach; ?>
                                <div class="rm-more-btn"><a href="<?php echo admin_url("admin.php?page=rm_submission_manage");?>"> <?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_MORE')); ?> <span class="material-icons"> navigate_next </span></a></div>
                        <?php else: ?>
                                        <div class="rm-form-no-submissions"><svg width="100%" height="100%" viewBox="0 0 501 384" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-miterlimit:1.5;">
                                            <g id="Without-Data" serif:id="Without Data" transform="matrix(1.38229,0,0,2.27989,-839.953,-1176.43)">
                                            <g transform="matrix(0.48902,0,0,0.29649,-386.974,461.924)">
                                            <path d="M2255.35,740.172C2246.47,740.172 2237.65,739.595 2228.94,738.361C2219.88,737.078 2210.94,735.084 2202.18,732.291C2105.26,705.291 2025.86,612.316 2034.58,503.069C2036.17,483.206 2038.5,463.498 2043.08,444.262C2046.96,428.516 2051.15,413.237 2057.36,397.573C2059.48,392.766 2061.14,388.269 2063.32,383.52C2066.84,375.774 2070.52,369.034 2074.32,363.484C2081.2,353.45 2089.39,345.94 2097.75,338.196C2100.41,335.727 2103.17,333.367 2106.02,331.124C2107.3,330.113 2108.56,329.124 2109.83,328.132C2126.15,315.337 2145.02,306.374 2165.09,302.331C2165.73,302.203 2166.38,302.078 2167.02,301.958C2180.01,299.613 2193.03,298.078 2206.21,298.078C2212.19,298.078 2218.21,298.394 2224.27,299.095C2244.09,300.537 2263.89,303.975 2284.22,307.862C2286.83,308.361 2289.45,308.774 2292.07,309.111C2300.15,310.148 2307.74,311.074 2315.41,311.074C2315.74,311.074 2316.06,311.072 2316.39,311.069C2317.74,311.115 2319.09,311.138 2320.43,311.138C2350.49,311.138 2376.79,299.71 2400.88,283.389C2413.47,274.867 2425.45,265.046 2437.1,254.899C2476.14,206.838 2534.73,182.391 2593.73,182.391C2646.93,182.391 2700.45,202.251 2740.31,242.583C2743.51,246.083 2745.52,249.734 2748.27,253.542C2756.67,266.209 2762.53,279.957 2765.38,295.094C2770.02,320.907 2765.27,344.955 2757.02,368.471C2754.27,376.31 2751.14,384.09 2747.83,391.857C2737.82,414.936 2725.74,438.659 2729.22,464.43C2734.44,503.088 2756.31,534.328 2769.54,571.837C2790.54,631.169 2742.62,684.541 2687.93,691.26C2679.09,692.639 2670.78,693.321 2662.01,694.324C2659.07,694.546 2656.16,694.652 2653.27,694.652C2613.03,694.652 2577.41,674.052 2539.69,658.987C2527.43,654.379 2514.62,651.509 2501.95,651.509C2489.53,651.509 2477.23,654.269 2465.71,660.855C2417.23,687.453 2374.04,718.795 2321.19,730.814C2313.56,732.867 2305.86,734.632 2298.15,736.058C2283.9,738.692 2269.55,740.172 2255.35,740.172Z" style="fill:url(#_Linear1);"/>
                                            <clipPath id="_clip2">
                                                <path d="M2255.35,740.172C2246.47,740.172 2237.65,739.595 2228.94,738.361C2219.88,737.078 2210.94,735.084 2202.18,732.291C2105.26,705.291 2025.86,612.316 2034.58,503.069C2036.17,483.206 2038.5,463.498 2043.08,444.262C2046.96,428.516 2051.15,413.237 2057.36,397.573C2059.48,392.766 2061.14,388.269 2063.32,383.52C2066.84,375.774 2070.52,369.034 2074.32,363.484C2081.2,353.45 2089.39,345.94 2097.75,338.196C2100.41,335.727 2103.17,333.367 2106.02,331.124C2107.3,330.113 2108.56,329.124 2109.83,328.132C2126.15,315.337 2145.02,306.374 2165.09,302.331C2165.73,302.203 2166.38,302.078 2167.02,301.958C2180.01,299.613 2193.03,298.078 2206.21,298.078C2212.19,298.078 2218.21,298.394 2224.27,299.095C2244.09,300.537 2263.89,303.975 2284.22,307.862C2286.83,308.361 2289.45,308.774 2292.07,309.111C2300.15,310.148 2307.74,311.074 2315.41,311.074C2315.74,311.074 2316.06,311.072 2316.39,311.069C2317.74,311.115 2319.09,311.138 2320.43,311.138C2350.49,311.138 2376.79,299.71 2400.88,283.389C2413.47,274.867 2425.45,265.046 2437.1,254.899C2476.14,206.838 2534.73,182.391 2593.73,182.391C2646.93,182.391 2700.45,202.251 2740.31,242.583C2743.51,246.083 2745.52,249.734 2748.27,253.542C2756.67,266.209 2762.53,279.957 2765.38,295.094C2770.02,320.907 2765.27,344.955 2757.02,368.471C2754.27,376.31 2751.14,384.09 2747.83,391.857C2737.82,414.936 2725.74,438.659 2729.22,464.43C2734.44,503.088 2756.31,534.328 2769.54,571.837C2790.54,631.169 2742.62,684.541 2687.93,691.26C2679.09,692.639 2670.78,693.321 2662.01,694.324C2659.07,694.546 2656.16,694.652 2653.27,694.652C2613.03,694.652 2577.41,674.052 2539.69,658.987C2527.43,654.379 2514.62,651.509 2501.95,651.509C2489.53,651.509 2477.23,654.269 2465.71,660.855C2417.23,687.453 2374.04,718.795 2321.19,730.814C2313.56,732.867 2305.86,734.632 2298.15,736.058C2283.9,738.692 2269.55,740.172 2255.35,740.172Z"/>
                                            </clipPath>
                                            <g clip-path="url(#_clip2)">
                                            <g transform="matrix(1.03576,0,0,-1.03576,2374.68,503.989)">
                                            <path d="M545.175,310.494C543.495,276.405 516.815,248.904 483.089,245.908C474.413,189.137 425.373,145.647 366.176,145.647C323.497,145.647 286.107,168.259 265.308,202.15C255.558,198.667 245.061,196.756 234.114,196.756C182.906,196.756 141.393,238.269 141.393,289.477C141.393,296.71 142.248,303.738 143.814,310.494L545.175,310.494Z" style="fill:url(#_Linear3);"/>
                                            <path d="M474.191,243.091C458.891,243.091 444.583,247.329 432.366,254.686C413.514,223.749 379.462,203.091 340.585,203.091C281.268,203.091 233.182,251.177 233.182,310.494L554.198,310.494C547.644,272.224 514.33,243.091 474.191,243.091Z" style="fill:url(#_Linear4);"/>
                                            </g>
                                            <g transform="matrix(1.39064,0,0,1.39064,5.11635,308.387)">
                                            <path d="M1798.3,257.456C1795.25,257.456 1792.24,257.609 1789.28,257.915C1779.8,178.06 1711.86,116.145 1629.44,116.145C1570.62,116.145 1519.16,147.689 1491.1,194.793C1478.79,190.184 1465.44,187.668 1451.52,187.668C1389.04,187.668 1338.4,238.308 1338.4,300.784C1338.4,304.049 1338.54,307.297 1338.81,310.494L1878.97,310.494C1865.48,279.29 1834.45,257.456 1798.3,257.456Z" style="fill:url(#_Linear5);"/>
                                            <g transform="matrix(1.0638,0,0,1.0638,-1821.51,-1035.25)">
                                            <path d="M3130,1219L3407.03,1075.57" style="fill:none;stroke:white;stroke-width:5.33px;"/>
                                            </g>
                                            <path d="M1805.3,310.494C1794.42,288.933 1773.86,272.601 1748.24,268.124C1739.14,266.533 1730.14,266.583 1721.52,268.03C1712.37,223.251 1672.75,189.563 1625.27,189.563C1604.42,189.563 1585.1,196.078 1569.19,207.157C1547.79,167.013 1505.51,139.685 1456.84,139.685C1386.57,139.685 1329.6,196.654 1329.6,266.929C1329.6,282.232 1332.3,296.903 1337.26,310.494L1805.3,310.494Z" style="fill:url(#_Linear6);"/>
                                            </g>
                                            <g transform="matrix(-1.42374,-0.401847,-0.401847,1.42374,2719.07,348.656)">
                                            <path d="M56.148,52.365L119.938,104.804L91.334,122.056L56.148,52.365Z" style="fill:white;fill-rule:nonzero;"/>
                                            <path d="M56.148,52.365L148.541,91.865L131.856,97.426L56.148,52.365Z" style="fill:white;fill-rule:nonzero;"/>
                                            <path d="M119.938,104.804L121.413,120.808L110.371,110.574L119.938,104.804Z" style="fill:rgb(204,204,204);fill-rule:nonzero;"/>
                                            <path d="M131.856,97.426L121.413,120.808L119.938,104.804L56.148,52.365L131.856,97.426Z" style="fill:rgb(230,230,230);fill-rule:nonzero;"/>
                                            </g>
                                            </g>
                                            </g>
                                            <g transform="matrix(0.48902,0,0,0.29649,-386.974,461.924)">
                                            <path d="M2478.24,702.142C2483.46,694.174 2490.96,688.635 2498.9,686.094C2506.84,683.554 2515.3,683.991 2522.49,687.966C2529.68,691.94 2534.25,698.7 2535.76,706.445C2537.27,714.191 2535.66,722.939 2530.47,730.899C2525.28,738.859 2517.46,744.534 2509.1,747.24C2500.73,749.945 2491.9,749.653 2484.7,745.674C2477.51,741.695 2473.26,734.8 2472.15,726.9C2471.04,718.999 2473.01,710.11 2478.24,702.142Z" style="fill:rgb(181,222,255);"/>
                                            </g>
                                            <g transform="matrix(0.48902,0,0,0.29649,-386.974,461.924)">
                                            <path d="M2330.84,282.418C2317.63,282.418 2304.29,276.884 2294.66,267.287C2286.99,259.474 2282.88,249.214 2284.64,238.253C2288.8,216.773 2309.74,203.135 2330.06,203.135C2332.16,203.135 2334.25,203.28 2336.31,203.576C2348.05,205.189 2357.71,211.101 2364.06,219.268C2370.41,227.436 2373.54,237.86 2371.95,248.651C2370.36,259.443 2364.36,268.575 2355.96,274.605C2348.4,279.959 2339.64,282.418 2330.84,282.418Z" style="fill:rgb(105,177,235);"/>
                                            </g>
                                            </g>
                                            <defs>
                                            <linearGradient id="_Linear1" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(78.4064,-451.186,451.186,78.4064,2469.35,654.321)"><stop offset="0" style="stop-color:rgb(181,222,255);stop-opacity:1"/><stop offset="1" style="stop-color:rgb(34,113,177);stop-opacity:1"/></linearGradient>
                                            <linearGradient id="_Linear3" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(-30.109,-234.193,234.193,-30.109,351.393,365.166)"><stop offset="0" style="stop-color:rgb(125,163,213);stop-opacity:1"/><stop offset="0.7" style="stop-color:rgb(240,246,252);stop-opacity:1"/><stop offset="1" style="stop-color:white;stop-opacity:1"/></linearGradient>
                                            <linearGradient id="_Linear4" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(6.30153e-09,140.357,-140.357,6.30153e-09,393.69,217.725)"><stop offset="0" style="stop-color:white;stop-opacity:1"/><stop offset="0.6" style="stop-color:rgb(196,214,238);stop-opacity:1"/><stop offset="1" style="stop-color:rgb(125,163,213);stop-opacity:1"/></linearGradient>
                                            <linearGradient id="_Linear5" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(-62.9251,251.565,-251.565,-62.9251,1665.93,69.7427)"><stop offset="0" style="stop-color:white;stop-opacity:1"/><stop offset="0.4" style="stop-color:rgb(223,234,247);stop-opacity:1"/><stop offset="0.9" style="stop-color:rgb(132,167,216);stop-opacity:1"/><stop offset="1" style="stop-color:rgb(125,163,213);stop-opacity:1"/></linearGradient>
                                            <linearGradient id="_Linear6" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(28.0002,-230.001,230.001,28.0002,1558.38,359.271)"><stop offset="0" style="stop-color:rgb(125,163,213);stop-opacity:1"/><stop offset="0.3" style="stop-color:rgb(198,215,239);stop-opacity:1"/><stop offset="1" style="stop-color:white;stop-opacity:1"/></linearGradient>
                                            </defs>
                                            </svg>
                                        </div>
                                
                                <div class="rm-dash-demo-notice"> <div class="rm-dash-demo-data"><span class="material-icons"> info </span><?php _e('Currently, there are 0 recorded submissions.', 'custom-registration-form-builder-with-submission-manager'); ?></div></div>
                     <?php endif; ?>
                    </div>

                </div>

        </div>
    
    <!--- Ends: Second Row Section---->
    
    <!--- Third Row Section---->
    
    
        <div class="rm-box-row rm-box-mb-25">
            
            <div class="rm-box-col-6">
                <div class="rm-box-border rm-box-white-bg rm-dash-users-chart">
                    <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_USERS_CHART_TITLE')); ?></div>
                    <div class="rm-dash-users-chart-wrap">
                        <div class="rm-center-stats-box">
                            <div class="rm-timerange-toggle">
                                <?php _e('Show data from last', 'custom-registration-form-builder-with-submission-manager'); ?>
                                <select id="rm_stat_timerange" onchange="rm_refresh_stats()">
                                    <?php
                                    $trs = array('days' => '7 Days', 'weeks' => '7 Weeks', 'months' => '12 Months', 'years' => 'All Time');
                                    foreach ($trs as $key => $tr) {
                                        echo "<option value=$key";
                                        if ($data->rm_ur == $key)
                                            echo " selected";
                                        echo '>' . ucfirst($tr) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="rm-dash-users-chart-container" >
                            <canvas id="userChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
                     
            <div class="rm-box-col-6">
                <div class="rm-box-white-bg rm-box-border rm-dashboard-users-loggedin">
                    <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_LOGIN_LOGS'));?></div>
                    <div class="rm-dash-loggin-chart-wrap">
                        <div class="rm-center-stats-box">
                            <div class="rm-timerange-toggle">
                                <?php _e('Show data from last', 'custom-registration-form-builder-with-submission-manager'); ?>
                                <select id="rm_stat_timerange_login" onchange="rm_refresh_stats_login()">
                                    <?php
                                    $login_filter = array('7' => '7 Days', '30' => '30 Days', '60' => '60 Days', '90' => '90 Days');
                                    foreach ($login_filter as $key => $tr) {
                                        echo "<option value=$key";
                                        if ($data->rm_tr == $key)
                                            echo " selected";
                                        echo '>' . ucfirst($tr) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="rm-dash-users-chart-container" >
                            <canvas class="rm-box-graph" id="rm_subs_over_time_chart_div"></canvas>
                        </div>
                        
                    </div>
                    <div class="rm-more-btn"><a href="<?php echo admin_url("admin.php?page=rm_login_analytics");?>"> <?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_MORE')); ?> <span class="material-icons"> navigate_next </span></a></div>
                </div>
            </div>
        </div>
    
    <!--- Ends: Third Row Section---->
    
    
      <!--- Fourth Row Section---->
      
      <div class="rm-box-row rm-box-mb-25">
         
          <div class="rm-box-col-9">
              
              <div class="rm-box-row">
                      <div class="rm-box-col-5">
                          <div class="rm-box-border rm-box-white-bg rm-dash-submission-card-range">
                              <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_COUNTER')); ?></div>
                              <div class="rm-card-range-list-wrap">
                              <ul class="rm-dash-list rm-dash-count-present">
                                  <li>
                                      <label class="rm-dash-list-today-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_TODAY')); ?></label>
                                      <span class="rm-dash-list-today-value"><?php echo esc_html($data->count->today); ?></span>
                                  </li>
                                  <li>
                                      <label class="rm-dash-list-week-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_THIS_WEEK')); ?></label>
                                      <span class="rm-dash-list-week-value"><?php echo esc_html($data->count->this_week); ?></span>
                                  </li>
                                  <li>
                                      <label class="rm-dash-list-month-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_THIS_MONTH')); ?></label>
                                      <span class="rm-dash-list-month-value"><?php echo esc_html($data->count->this_month); ?></span>
                                  </li>
                              </ul>
                              <ul class="rm-dash-list rm-dash-count-past">
                                  <li>
                                      <label class="rm-dash-list-today-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_YESTERDAY')); ?></label>
                                      <span class="rm-dash-list-today-value"><?php echo esc_html($data->count->yesterday); ?></span>
                                  </li>
                                   <li>
                                      <label class="rm-dash-list-today-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LAST_WEEK')); ?></label>
                                      <span class="rm-dash-list-today-value"><?php echo esc_html($data->count->last_week); ?></span>
                                  </li>
                                  <li>
                                      <label class="rm-dash-list-today-label"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LAST_MONTH')); ?></label>
                                      <span class="rm-dash-list-today-value"><?php echo esc_html($data->count->last_month); ?></span>
                                  </li>
                              </ul>
                              
                              </div>
                          </div>  
                      </div>
                  <div class="rm-box-col-7">
                      <div class="rm-box-border rm-box-white-bg rm-latest-forms">
                           <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_LATEST_FORMS')); ?></div>
                           <?php if(!empty($data->latest_forms)):?>
				<div class="rm-latest-forms-table-card">
			
				<?php foreach($data->latest_forms as $form):?>
                                    <div class="rm-latest-forms-row">
                                        <div class="rm-latest-forms-name"><?php echo wp_kses_post($form->form_name);?></div>
                                        <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode<?php echo esc_attr($form->form_id);?>"><?php echo "[RM_Form id='".esc_html($form->form_id)."']";?></span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode<?php echo esc_attr($form->form_id);?>'), this)"> content_copy </span></div>
					<div class="rm-latest-forms-field-link"><a href="<?php echo admin_url("admin.php?page=rm_field_manage&rm_form_id=".$form->form_id);?>" ><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_FIELDS')); ?> <span class="material-icons"> navigate_next </span></a></div>
                                        <div class="rm-latest-forms-dash-link"><a href="<?php echo admin_url("admin.php?page=rm_form_sett_manage&rm_form_id=".$form->form_id);?>" ><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD')); ?> <span class="material-icons"> navigate_next </span></a></div>
                                    </div>
				<?php endforeach;?>
				</div>
				<?php else:?>
                                     <div class="rm-dash-demo-notice"> <div class="rm-dash-demo-data"><span class="material-icons"> info </span><?php _e('No Form Found.','custom-registration-form-builder-with-submission-manager');?></div></div>
                                <?php endif;?>
                           
                           
                      </div>
                          
                  </div>
                  
              </div>
              
              
          </div>
          
          <div class="rm-box-col-3">
              <div class="rm-box-border rm-box-white-bg rm-important-shortcodes">
                    <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_IMP_SHORTCODES')); ?></div>
                <div class="rm-important-shortcodes-wrap">
                    <div class="rm-latest-forms-row">
                       <div class="rm-latest-forms-name"><?php _e('Login Form', 'custom-registration-form-builder-with-submission-manager'); ?></div> 
                       <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode-login">[RM_Login]</span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode-login'), this)"> content_copy </span></div>
                    </div> 
                    <div class="rm-latest-forms-row">
                       <div class="rm-latest-forms-name"><?php _e('Register Forms', 'custom-registration-form-builder-with-submission-manager'); ?></div> 
                       <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode-form">[RM_Form id='x']</span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode-form'), this)"> content_copy </span></div>
                    </div>      
                    <div class="rm-latest-forms-row">
                       <div class="rm-latest-forms-name"><?php _e('User Directory', 'custom-registration-form-builder-with-submission-manager'); ?></div> 
                       <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode-user">[RM_Users]</span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode-user'), this)"> content_copy </span></div>
                    </div>
                    <div class="rm-latest-forms-row">
                       <div class="rm-latest-forms-name"><?php _e('User Directory Form Specific', 'custom-registration-form-builder-with-submission-manager'); ?></div> 
                       <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode-user-spec">[RM_Users form_id='x']</span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode-user-spec'), this)"> content_copy </span></div>
                    </div>        
                    <div class="rm-latest-forms-row">
                       <div class="rm-latest-forms-name"><?php _e('User Area', 'custom-registration-form-builder-with-submission-manager'); ?></div> 
                       <div class="rm-latest-forms-shortcode" ><span id="rmformshortcode-submission">[RM_Front_Submissions]</span> <span class="rm-shortcode-copy-icon material-icons" onclick="rm_copy_to_clipboard_dashboard(document.getElementById('rmformshortcode-submission'), this)"> content_copy </span></div>
                    </div> 
                </div>
                <div class="rm-more-btn"><a target="__blank" href="https://registrationmagic.com/wordpress-registration-shortcodes-list/"> <?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_MORE')); ?> <span class="material-icons"> navigate_next </span></a></div>    
          </div>

      </div>
      
      </div>
      
      <!--- End Fourth Row Section---->
      
      
    
    <!--- Fifth Row Section---->
    
    <div class="rm-box-row rm-box-mb-25">
        <div class="rm-box-col-5">
                <div class="rm-box-border rm-box-white-bg rm-dashboard-users-logged-in-logs">
                        <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_LOGIN_LOGS'));?></div>
                        <table class="rm-latest-login-table">
                            <tbody>
                                <?php
                                if(isset($data->login_logs)){
                                    if (!empty($data->login_logs) && (is_array($data->login_logs) || is_object($data->login_logs))){
                                        $gopt=new RM_Options;
                                        $blocked_ips=array();
                                        $blocked_ips=$gopt->get_value_of('banned_ip');

                                        foreach ($data->login_logs as $login_log){
                                            ?>
                                            <tr class="rm-login-result <?php echo ($login_log->status==1)?'rm-login-success':'rm-login-failed'; ?>">
                                                <td><div class="rm-login-user-time-log"><?php echo esc_html(RM_Utilities::localize_time($login_log->time,'j M Y, h:i a')); ?></div></td>
                                                <td>
                                                    <div class="rm-login-form-user">
                                                        <a href="#">
                                                            <?php echo get_avatar($login_log->email)?get_avatar($login_log->email):'<img src="'.RM_IMG_URL.'default_person.png">'; ?>
                                                        </a>
                                                        <?php $user = get_user_by( 'email', $login_log->email ); ?>
                                                        <?php if(!empty($user)): ?>
                                                            <span class="rm-login-user-status <?php echo (RM_Utilities::is_user_online($user->ID))?'rm-login-user-online':'' ?>"><i class="fa fa-circle"></i></span>
                                                        <?php else: ?>
                                                            <span class="rm-login-user-status"><i class="fa fa-circle"></i></span>
                                                        <?php endif; ?>
                                                            <span class="rm-login-form-user-name" title="<?php echo ($user)?esc_attr($user->display_name):($login_log->social_type=='instagram'?esc_attr($login_log->username_used):esc_attr($login_log->email)); ?>"><?php echo ($user)?esc_attr($user->display_name):($login_log->social_type=='instagram'?esc_attr($login_log->username_used):esc_attr($login_log->email)); ?></span>
                                                    </div>
                                                </td>
                                                <td><div class="rm-login-method rm-login-<?php echo esc_attr(strtolower($login_log->type)) ?>"><?php echo esc_html($login_log->type) ?></div></td>
                                                <?php
                                                if($login_log->status==1){
                                                    $login_icon = '<i class="fa fa-unlock-alt"></i>';
                                                    if(strtolower($login_log->type)=='otp'){
                                                        $login_icon = '<i class="fa fa-unlock-alt"></i>';
                                                    }else if(strtolower($login_log->type)=='2fa' || strtolower($login_log->type)=='fa'){
                                                        $login_icon = '<i class="fa fa-unlock-alt"></i><i class="fa fa-unlock-alt"></i>';
                                                    }else if(strtolower($login_log->type)=='social'){
                                                        $login_icon = '<i class="fa fa-'.$login_log->social_type.'"></i>';
                                                    }
                                                }else{
                                                    $login_icon = '<i class="fa fa-lock"></i>';
                                                    if(strtolower($login_log->type)=='otp'){
                                                        $login_icon = '<i class="fa fa-lock"></i>';
                                                    }else if(strtolower($login_log->type)=='2fa' || strtolower($login_log->type)=='fa'){
                                                        $login_icon = '<i class="fa fa-lock"></i><i class="fa fa-lock"></i>';
                                                    }else if(strtolower($login_log->type)=='social'){
                                                        $login_icon = '<i class="fa fa-'.$login_log->social_type.'"></i>';
                                                    }
                                                }
                                                ?>
                                                <td><div class="rm-login-boolean-result <?php echo ($login_log->status==1)?'rm-login-true':'rm-login-false'; ?>"><i class="fa fa-<?php echo ($login_log->status==1)?'check':'times'; ?>"></i></div></td>
                                                
                                            </tr>
                                            <?php
                                        }
                                    }else{
                                         echo '<div class="rm-login-no-record"></div>';
                                        
                                        echo '<div class="rm-dash-demo-notice"><div class="rm-dash-demo-data"><span class="material-icons"> info </span>'.sprintf(__('Not enough data. Come back later to check login activity. <a target="_blank" href="%s">More Info</a>', 'custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/wordpress-user-login-plugin-guide/#login-analytics').'</div></div>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php if (!empty($data->login_logs)):?><div class="rm-more-btn"><a href="<?php echo admin_url("admin.php?page=rm_login_analytics");?>"> <?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_MORE')); ?> <span class="material-icons"> navigate_next </span></a></div><?php endif;?>
                    </div>
            
        </div> 
        <div class="rm-box-col-3">
            <div class="rm-box-border rm-box-white-bg rm-dashboard-setting">
                    <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_SETTINGS'));?></div>
                    
                        <div class="rm-dashboard-setting-wrap">
                            <ul>
                                <li>
                                    <a href="admin.php?page=rm_options_general">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>general-settings.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('General Settings', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="admin.php?page=rm_options_user">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>rm-user-accounts.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('User Accounts', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
                              
                                <li>
                                    <a href="admin.php?page=rm_options_user_privacy">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>user-privacy.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('Privacy', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
             
                                  <li>
                                    <a href="admin.php?page=rm_options_autoresponder">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>rm-email-notifications.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('Email Configuration', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="admin.php?page=rm_options_tabs">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>rm-tab-reorder-icon.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('User Area Layout', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="admin.php?page=rm_options_thirdparty">
                                        <div class="rm-dash-setting-icon"><img class="rm-grid-icon dibfl" src="<?php echo esc_url(RM_IMG_URL); ?>rm-third-party.png"></div>
                                        <div class="rm-dash-setting-label"><?php _e('External Integrations', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    </a>
                                </li>
                                
                            </ul>

                        </div>
                    
                    <div class="rm-more-btn"><a href="<?php echo admin_url("admin.php?page=rm_options_manage");?>"> <?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_MORE')); ?> <span class="material-icons"> navigate_next </span></a></div>
            </div> 
            
        </div>
        
        <div class="rm-box-col-4">
            
            <div class="rm-box-border rm-box-white-bg rm-dash-export-section <?php echo esc_attr($premium_class); ?>">
                    <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_EXPORT_TITLE')); ?></div>
                    <div class="rm-dash-export-submissions">
                        <form method="post" action="" name="rm_submission_manage" id="rm_submission_manager_form">
                            <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field" />
                            <select name="rm_form_id" class="rm-dash-export-field" onchange="rm_on_form_selection_change()">
                                <?php
                                foreach ($data->forms as $form) {
                                    echo '<option value="' . $form->form_id . '">' . $form->form_name . '</option>';
                                }
                                ?>
                            </select>
                            <input type="hidden" name="rm_interval" value="all" />
                        </form>
                        <form name="rm_form_manager" id="rm_form_manager_operartionbar" class="rm_static_forms" method="post" action="">
                            <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                            <input type="hidden" name="rm_selected"value="">
                            <input type="hidden" name="req_source" value="form_manager">
                            <input type="checkbox" name="rm_selected_forms[]" id="rm_form_export_id" value="35" style="display:none;" checked="checked">
                            <?php wp_nonce_field('rm_form_manager_template'); ?>
                        </form>
                        <div class="rm-dash-export-btns">
                        <div class="rm-dash-export-btn-wrap rm-export-premium">
                            <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <button class="rm-dash-export-btn" disabled>
                            <?php echo RM_UI_Strings::get("DASHBOARD_EXPORT_SUBMISSION_BUTTON"); ?>
                        </button>
                            
                            <div class="rm-dash-export-info"><?php _e('Download submissions for this
                                    form in spreadsheet-friendly
                                    CSV format.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                            </div>
                       <div class="rm-dash-export-btn-wrap">
                        <div class="rm-box-premium" style="visibility: hidden;"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <button class="rm-dash-export-btn" onclick="jQuery.rm_do_action('rm_form_manager_operartionbar', 'rm_form_export')">
                            <?php echo RM_UI_Strings::get("DASHBOARD_EXPORT_FORMS_BUTTON"); ?>
                        </button>
                           
                                <div class="rm-dash-export-info"><?php _e("Download form with it's layout and options as backup or to import inside another installation.", 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            
            
            
        </div>
    
   
    <!--- End Fifth Row Section---->
    
    <!--- Start sixth row  Section --->
    
    <div class="rm-box-row rm-box-mb-25">
        <div class="rm-box-col-6">
            <div class="rm-box-border rm-box-white-bg rm-dash-attachment">
                <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_LATEST_ATTACHMENTS')); ?></div>
                <div class="rm-dash-content-row">
                      <div class="rm-box-premium"><span class="material-icons"> <?php _e('workspace_premium', 'custom-registration-form-builder-with-submission-manager');?> </span> <?php _e('Premium', 'custom-registration-form-builder-with-submission-manager');?></div>
                      <?php if(defined('REGMAGIC_ADDON')):?>
                      <div class="rm-dash-premium-content"><?php _e('You are using an older version of RegistrationMagic Premium. Please update to view this data.', 'custom-registration-form-builder-with-submission-manager');?> </div>
                      <?php else: ?>
                      <div class="rm-dash-premium-content"><?php _e('Get a quick overview of latest files attached to different submissions.', 'custom-registration-form-builder-with-submission-manager');?> </div>
                      <?php endif;?>
                </div>
            </div>
        </div>
        <div class="rm-box-col-6">
            <div class="rm-box-border rm-box-white-bg rm-dash-payment">
                <div class="rm-dash-card-title"><?php echo wp_kses_post(RM_UI_Strings::get('DASHBOARD_LATEST_PAYMENTS')); ?></div>
                <div class="rm-dash-content-row">
                      <div class="rm-box-premium"><span class="material-icons"> <?php _e('workspace_premium', 'custom-registration-form-builder-with-submission-manager');?> </span> <?php _e('Premium', 'custom-registration-form-builder-with-submission-manager');?></div>
                      <?php if(defined('REGMAGIC_ADDON')):?>
                      <div class="rm-dash-premium-content"><?php _e('You are using an older version of RegistrationMagic Premium. Please update to view this data.', 'custom-registration-form-builder-with-submission-manager');?> </div>
                      <?php else: ?>
                      <div class="rm-dash-premium-content"> <?php _e('Keep a tab on the payments you have recently received through your forms.', 'custom-registration-form-builder-with-submission-manager');?></div>
                      <?php endif;?>
                </div>
            </div>
        </div>
    </div>
  
    <!-- End Six Row Section --->
    
    
      </div>


    <!--- Welcome Modal---->

    <div class="rmagic rm-hide-version-number">
        <div id="rm_welcome_modal" class="ep-welcome-banner rm-modal-box-main rm-modal-view" style="display:none">
            <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>

            <div class="rm-modal-box-wrap rm-form-popup-out">
                <div class="rm-modal-titlebar rm-form-template-popup-header">
                    <div class="rm-modal-title">
                        <img src="<?php echo RM_BASE_URL; ?>images/svg/rm-logo-welcomemodal.svg">                    
                    </div>
                    <span  class="rm-modal-close"><span class="material-icons">close</span>
                </div>
                <div class="rm-modal-container">                
                    <div class="rm-welcome-modal-wrap">


                        <div class="rm-welcome-modal-row">
                            <div class="rm-welcome-modal-row-title"><?php _e("Here's what you can do next:", 'custom-registration-form-builder-with-submission-manager');?></div>
                            <a href="javascript:void(0);"class="rm-welcome-modal-box-link" id="rm-welcome-overview" >
                                <div class="rm-welcome-modal-icon">
                                    <svg width="72" height="100%" viewBox="0 0 132 132" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <g transform="matrix(1.83333,0,0,1.925,-2548.33,-1089)">
                                    <circle cx="1426" cy="600" r="36" style="fill:none;"/>
                                    <clipPath id="_clip1">
                                        <circle cx="1426" cy="600" r="36"/>
                                    </clipPath>
                                    <g clip-path="url(#_clip1)">
                                    <g transform="matrix(0.244723,0,0,0.244723,977.708,541.213)">
                                    <path d="M1819.88,299.731C1814.91,299.731 1810.88,295.7 1810.88,290.727C1810.88,285.754 1814.91,281.723 1819.88,281.723C1824.85,281.723 1828.89,285.754 1828.89,290.727C1828.89,295.7 1824.85,299.731 1819.88,299.731ZM1844.68,289.84C1844.64,288.778 1844.54,287.735 1844.37,286.71C1843.2,279.54 1838.96,273.404 1833.04,269.694C1832.8,269.538 1832.54,269.386 1832.29,269.239C1832.23,269.207 1832.17,269.17 1832.11,269.143C1831.91,269.028 1831.72,268.922 1831.52,268.812C1828.05,266.96 1824.09,265.916 1819.89,265.916C1815.68,265.916 1811.72,266.964 1808.25,268.812C1807.99,268.954 1807.73,269.092 1807.48,269.239C1807.22,269.386 1806.97,269.538 1806.72,269.699C1799.96,273.941 1795.38,281.351 1795.08,289.84C1795.08,290.134 1795.07,290.428 1795.07,290.723C1795.07,291.017 1795.08,291.311 1795.08,291.605C1795.38,300.104 1799.96,307.522 1806.72,311.76C1806.97,311.912 1807.22,312.068 1807.48,312.215C1807.73,312.362 1807.99,312.5 1808.25,312.642C1811.72,314.495 1815.68,315.538 1819.89,315.538C1824.09,315.538 1828.04,314.49 1831.52,312.642C1831.78,312.509 1832.03,312.362 1832.3,312.215C1832.55,312.068 1832.8,311.912 1833.05,311.76C1839.81,307.513 1844.38,300.104 1844.68,291.605C1844.69,291.311 1844.69,291.017 1844.69,290.723C1844.69,290.428 1844.69,290.134 1844.68,289.84Z" style="fill:rgb(235,89,94);fill-rule:nonzero;"/>
                                    <path d="M1751.53,181.055L1759.6,195.032C1751.93,199.109 1744.66,204.091 1738,209.892C1726.54,219.88 1716.91,232.28 1710,246.267C1703.07,260.244 1698.88,275.798 1697.84,291.775C1696.81,275.803 1698.91,259.417 1704.23,243.877C1709.54,228.337 1718.04,213.679 1729.17,201.067C1735.76,193.612 1743.28,186.873 1751.53,181.055Z" style="fill:rgb(115,178,228);fill-rule:nonzero;"/>
                                    <path d="M1872.56,164.604C1858.05,159.535 1842.82,157.025 1827.78,157.025C1825.14,157.025 1822.5,157.103 1819.88,157.255C1802.3,158.303 1785.08,162.803 1769.59,170.368C1765.76,172.234 1762.04,174.28 1758.44,176.495C1757.85,176.858 1757.27,177.221 1756.69,177.594C1756.11,177.961 1755.53,178.334 1754.95,178.715L1763.29,193.157C1763.91,192.853 1764.53,192.559 1765.16,192.274C1765.78,191.98 1766.41,191.695 1767.05,191.419C1770.24,190.008 1773.48,188.758 1776.76,187.659C1788.22,183.822 1800.15,181.932 1811.98,181.932C1814.62,181.932 1817.26,182.029 1819.88,182.213C1834.26,183.265 1848.16,187.122 1860.62,193.423C1863.44,194.848 1866.19,196.397 1868.86,198.065L1885.22,169.734C1881.08,167.808 1876.85,166.098 1872.56,164.604Z" style="fill:rgb(53,143,216);fill-rule:nonzero;"/>
                                    <path d="M1964.81,273.951C1962.5,260.994 1958.46,248.409 1952.82,236.707C1944.65,219.682 1933.17,204.532 1919.42,192.247C1911.21,184.915 1902.19,178.605 1892.62,173.448C1892.01,173.117 1891.4,172.795 1890.79,172.478C1890.18,172.156 1889.57,171.844 1888.95,171.536L1872.33,200.327C1872.9,200.713 1873.46,201.104 1874.03,201.504C1874.59,201.899 1875.15,202.303 1875.7,202.717C1882.07,207.414 1887.86,212.801 1892.94,218.717C1901.78,229.031 1908.43,240.935 1912.47,253.433C1915.05,261.343 1916.57,269.478 1917.09,277.618C1917.59,285.588 1924.23,291.775 1932.21,291.775L1949.89,291.775C1959.32,291.775 1966.47,283.244 1964.81,273.951Z" style="fill:rgb(34,113,177);fill-rule:nonzero;"/>
                                    <path d="M1891.18,241.655L1835.72,295.034C1835.67,295.084 1835.59,295.075 1835.55,295.017L1821.27,274.545C1821.23,274.486 1821.25,274.405 1821.32,274.373L1890.58,240.794C1891.12,240.531 1891.61,241.236 1891.18,241.655Z" style="fill:rgb(235,89,94);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    </g>
                                    </svg>
                                </div>
                                <div class="rm-welcome-modal-box-title-wrap">
                                    <div class="rm-welcome-modal-box-title"><?php _e('Go to the Overview page.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    <div class="rm-welcome-modal-box-description"><?php _e('Overview is your default landing page and provides bird\'s eye view of your data.', 'custom-registration-form-builder-with-submission-manager'); ?></div>

                                </div>
                            </a>

                            <a href="admin.php?page=rm_form_setup"class="rm-welcome-modal-box-link" >
                                <div class="rm-welcome-modal-icon">
                                    <svg width="72" height="100%" viewBox="0 0 132 132" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <g transform="matrix(1.18367,0,0,1.18367,-412.277,-723.256)">
                                    <g id="holder">
                                    <g transform="matrix(1,0,0,1,395.09,714.517)">
                                    <path d="M0,1.927C0.357,4.292 2.377,6.112 4.843,6.112C7.306,6.112 9.33,4.292 9.686,1.927C12.91,1.737 15.472,-0.913 15.472,-4.185L-5.8,-4.185C-5.8,-0.911 -3.23,1.74 0,1.927" style="fill:rgb(58,73,85);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,409.317,706.361)">
                                    <path d="M0,-5.268L-18.781,-5.268C-20.235,-5.268 -21.415,-4.088 -21.415,-2.634C-21.415,-1.18 -20.235,0 -18.781,0L0,0C1.454,0 2.635,-1.18 2.635,-2.634C2.635,-4.088 1.454,-5.268 0,-5.268" style="fill:rgb(58,73,85);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    <g id="glow">
                                    <g transform="matrix(1,0,0,1,399.932,614.761)">
                                    <path d="M0,15.195C1.943,15.195 3.513,13.622 3.513,11.683L3.513,3.512C3.513,1.572 1.943,0 0,0C-1.941,0 -3.511,1.572 -3.511,3.512L-3.511,11.683C-3.511,13.622 -1.941,15.195 0,15.195" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,376.885,623.864)">
                                    <path d="M0,11.209C0.69,12.156 1.76,12.659 2.848,12.659C3.561,12.659 4.284,12.44 4.908,11.988C6.476,10.849 6.826,8.653 5.687,7.084L0.885,0.471C-0.257,-1.097 -2.449,-1.45 -4.022,-0.308C-5.589,0.831 -5.939,3.027 -4.8,4.596L0,11.209Z" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,422.975,696.834)">
                                    <path d="M0,-10.275C-1.139,-11.843 -3.334,-12.19 -4.906,-11.051C-6.472,-9.911 -6.822,-7.715 -5.68,-6.146L-0.875,0.467C-0.19,1.412 0.881,1.915 1.969,1.915C2.684,1.915 3.405,1.697 4.029,1.244C5.597,0.103 5.947,-2.093 4.805,-3.662L0,-10.275Z" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,368.332,650.582)">
                                    <path d="M0,-3.727L-7.773,-6.255C-9.635,-6.855 -11.599,-5.845 -12.2,-4C-12.801,-2.157 -11.792,-0.174 -9.947,0.426L-2.174,2.954C-1.811,3.071 -1.448,3.128 -1.088,3.128C0.394,3.128 1.769,2.184 2.253,0.699C2.853,-1.144 1.845,-3.127 0,-3.727" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,441.477,674.357)">
                                    <path d="M0,-3.734L-7.771,-6.258C-9.617,-6.854 -11.596,-5.847 -12.196,-4.004C-12.796,-2.158 -11.785,-0.177 -9.94,0.423L-2.167,2.947C-1.807,3.064 -1.444,3.12 -1.084,3.12C0.398,3.12 1.773,2.175 2.257,0.692C2.856,-1.154 1.846,-3.134 0,-3.734" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,366.161,676.878)">
                                    <path d="M0,-8.779L-7.776,-6.258C-9.62,-5.66 -10.633,-3.679 -10.032,-1.834C-9.552,-0.349 -8.174,0.596 -6.692,0.596C-6.335,0.596 -5.967,0.539 -5.607,0.423L2.167,-2.098C4.013,-2.696 5.025,-4.678 4.424,-6.522C3.828,-8.366 1.862,-9.375 0,-8.779" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,432.622,643.734)">
                                    <path d="M0,9.975C0.36,9.975 0.723,9.918 1.084,9.802L8.855,7.277C10.701,6.677 11.711,4.696 11.112,2.851C10.511,1.007 8.522,0 6.688,0.596L-1.085,3.12C-2.93,3.72 -3.941,5.702 -3.341,7.546C-2.858,9.03 -1.482,9.975 0,9.975" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,376.889,696.823)">
                                    <path d="M0,-10.266L-4.81,-3.657C-5.952,-2.088 -5.604,0.109 -4.034,1.249C-3.413,1.703 -2.689,1.921 -1.973,1.921C-0.885,1.921 0.185,1.42 0.871,0.476L5.68,-6.133C6.822,-7.702 6.475,-9.899 4.904,-11.04C3.341,-12.187 1.134,-11.835 0,-10.266" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    <g transform="matrix(1,0,0,1,422.097,634.599)">
                                    <path d="M0,-10.264L-4.802,-3.652C-5.941,-2.082 -5.591,0.114 -4.023,1.253C-3.399,1.705 -2.676,1.924 -1.963,1.924C-0.875,1.924 0.196,1.421 0.884,0.474L5.686,-6.139C6.825,-7.708 6.475,-9.904 4.907,-11.043C3.329,-12.188 1.142,-11.835 0,-10.264" style="fill:rgb(255,223,187);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    <g id="bulb" transform="matrix(1,0,0,1,425.299,671.696)">
                                    <path d="M0,-10.75C0,-24.762 -11.358,-36.122 -25.369,-36.122C-39.383,-36.122 -50.742,-24.762 -50.742,-10.75C-50.742,-4.839 -48.709,0.589 -45.312,4.898C-42.336,8.674 -39.518,12.797 -37.075,16.945L-37.075,25.372L-13.808,25.372L-13.808,17.168C-11.388,13.133 -8.259,8.488 -5.432,4.902C-2.033,0.592 0,-4.835 0,-10.75" style="fill:rgb(255,187,0);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    </svg>


                                </div>
                                <div class="rm-welcome-modal-box-title-wrap">
                                    <div class="rm-welcome-modal-box-title"> <?php _e('Create a new form.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    <div class="rm-welcome-modal-box-description"><?php _e('Build a new form quickly using step-by-step creation wizard.', 'custom-registration-form-builder-with-submission-manager'); ?></div>

                                </div>
                            </a>



                            <a href="https://registrationmagic.com/wordpress-registration-shortcodes-list/?utm_source=rm_plugin&utm_medium=welcome_modal&utm_campaign=onboarding" target="_blank" class="rm-welcome-modal-box-link">
                                <div class="rm-welcome-modal-icon">
                                    <svg width="72" height="100%" viewBox="0 0 132 132" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <g transform="matrix(2.37444,0,0,2.37444,-3322.49,-1356.65)">
                                    <g transform="matrix(0.545455,0,0,0.545455,639.455,48.5455)">
                                    <rect x="1413" y="982" width="55" height="60" style="fill:rgb(115,178,228);"/>
                                    </g>
                                    <g transform="matrix(0.545455,0,0,0.545455,639.455,48.5455)">
                                    <rect x="1417" y="979" width="14" height="62" style="fill:rgb(53,143,216);"/>
                                    </g>
                                    <g transform="matrix(0.0290909,0,0,0.0290909,1338.73,570.909)">
                                    <path d="M2663.79,1310.34C2706.58,1310.34 2741.38,1345.15 2741.38,1387.93C2741.38,1430.71 2706.58,1465.52 2663.79,1465.52C2621.01,1465.52 2586.21,1430.71 2586.21,1387.93C2586.21,1345.15 2621.01,1310.34 2663.79,1310.34ZM2741.38,1000C2741.38,1042.78 2706.58,1077.59 2663.79,1077.59C2621.01,1077.59 2586.21,1042.78 2586.21,1000C2586.21,957.22 2621.01,922.41 2663.79,922.41C2706.58,922.41 2741.38,957.22 2741.38,1000ZM2663.79,689.66C2621.01,689.66 2586.21,654.85 2586.21,612.07C2586.21,569.29 2621.01,534.48 2663.79,534.48C2706.58,534.48 2741.38,569.29 2741.38,612.07C2741.38,654.85 2706.58,689.66 2663.79,689.66ZM3362.08,663.79L2896.55,663.79C2867.99,663.79 2844.83,640.63 2844.83,612.07C2844.83,583.5 2867.99,560.34 2896.55,560.34L3362.08,560.34C3390.67,560.34 3413.79,583.5 3413.79,612.07C3413.79,640.63 3390.67,663.79 3362.08,663.79ZM3413.79,1387.93C3413.79,1416.5 3390.67,1439.66 3362.08,1439.66L2896.55,1439.66C2867.99,1439.66 2844.83,1416.5 2844.83,1387.93C2844.83,1359.37 2867.99,1336.21 2896.55,1336.21L3362.08,1336.21C3390.67,1336.21 3413.79,1359.37 3413.79,1387.93ZM3362.08,1051.72L2896.55,1051.72C2867.99,1051.72 2844.83,1028.56 2844.83,1000C2844.83,971.44 2867.99,948.28 2896.55,948.28L3362.08,948.28C3390.67,948.28 3413.79,971.44 3413.79,1000C3413.79,1028.56 3390.67,1051.72 3362.08,1051.72ZM3491.38,250L2508.63,250C2451.57,250 2405.17,296.4 2405.17,353.45L2405.17,1646.55C2405.17,1703.6 2451.57,1750 2508.63,1750L3491.38,1750C3548.41,1750 3594.83,1703.6 3594.83,1646.55L3594.83,353.45C3594.83,296.4 3548.41,250 3491.38,250Z" style="fill:rgb(166,206,238);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    </svg>
                                </div>
                                <div class="rm-welcome-modal-box-title-wrap">
                                    <div class="rm-welcome-modal-box-title"><?php _e('Checkout frontend shortcodes list.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    <div class="rm-welcome-modal-box-description"><?php _e('Learn how to publish forms and other views on the frontend.', 'custom-registration-form-builder-with-submission-manager'); ?></div>

                                </div>
                            </a>


                            <a href="admin.php?page=rm_options_manage"class="rm-welcome-modal-box-link">
                                <div class="rm-welcome-modal-icon">
                                    <svg width="72" height="100%" viewBox="0 0 132 132" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                    <g transform="matrix(0.14957,0,0,0.14957,-18.6893,-9.83046)">
                                    <g>
                                    <path d="M404.122,638.005C323.878,638.005 258.618,572.733 258.618,492.507C258.618,412.284 323.878,347.009 404.122,347.009C484.343,347.009 549.608,412.284 549.608,492.507C549.608,572.733 484.343,638.005 404.122,638.005ZM638.564,571.84C644.877,553.261 648.985,533.758 650.668,513.662L610.549,494.229C610.594,487.726 610.361,481.174 609.774,474.593C609.611,472.317 609.305,470.084 609.048,467.82L646.789,443.969C642.864,424.158 636.624,405.233 628.327,387.543L583.747,390.778C579.399,383.056 574.539,375.635 569.259,368.609L590.006,329.147C576.9,314.241 562.015,300.941 545.747,289.536L508.784,314.591C501.212,310.128 493.342,306.119 485.184,302.609L483.432,258.066C464.858,251.756 445.34,247.641 425.271,245.913L405.829,286.068C399.332,286.011 392.815,286.265 386.202,286.831C383.909,287.031 381.667,287.318 379.424,287.587L355.609,249.804C335.773,253.766 316.846,260.008 299.137,268.291L302.384,312.867C294.671,317.23 287.242,322.069 280.234,327.371L240.744,306.609C225.811,319.742 212.511,334.612 201.157,350.904L226.234,387.846C221.744,395.426 217.701,403.291 214.221,411.424L169.656,413.174C163.348,431.753 159.259,451.235 157.552,471.358L197.693,490.788C197.65,497.297 197.862,503.84 198.428,510.427C198.631,512.703 198.915,514.927 199.218,517.196L161.435,541.029C165.408,560.844 171.62,579.769 179.915,597.462L224.476,594.233C228.846,601.949 233.685,609.339 238.968,616.39L218.212,655.84C231.321,670.798 246.234,684.088 262.495,695.46L299.44,670.401C307.032,674.907 314.909,678.929 323.04,682.408L324.795,726.961C343.369,733.255 362.832,737.379 382.928,739.077L402.415,698.937C408.892,698.976 415.458,698.755 422.049,698.189C424.312,698.008 426.554,697.693 428.775,697.408L452.608,735.222C472.471,731.252 491.372,725.033 509.089,716.751L505.867,672.159C513.549,667.769 520.955,662.942 528.017,657.662L567.483,678.414C582.389,665.281 595.685,650.42 607.066,634.134L582.035,597.189C586.481,589.597 590.497,581.72 594.027,573.578L638.564,571.84Z" style="fill:rgb(137,162,182);fill-rule:nonzero;"/>
                                    <path d="M505.788,483.628C510.672,539.813 469.131,589.313 412.979,594.188C356.829,599.102 307.314,557.509 302.408,501.373C297.549,445.216 339.094,395.708 395.241,390.833C451.416,385.927 500.931,427.499 505.788,483.628ZM269.016,492.507C269.016,566.996 329.604,627.607 404.122,627.607C478.62,627.607 539.207,566.996 539.207,492.507C539.207,418.012 478.62,357.398 404.122,357.398C329.604,357.398 269.016,418.012 269.016,492.507Z" style="fill:rgb(137,162,182);fill-rule:nonzero;"/>
                                    <path d="M539.207,492.507C539.207,566.996 478.62,627.607 404.122,627.607C329.604,627.607 269.016,566.996 269.016,492.507C269.016,418.012 329.604,357.398 404.122,357.398C478.62,357.398 539.207,418.012 539.207,492.507ZM258.618,492.507C258.618,572.733 323.878,638.005 404.122,638.005C484.343,638.005 549.608,572.733 549.608,492.507C549.608,412.284 484.343,347.009 404.122,347.009C323.878,347.009 258.618,412.284 258.618,492.507Z" style="fill:white;fill-rule:nonzero;"/>
                                    </g>
                                    <g>
                                    <path d="M838.467,651.208C858.329,684.79 847.212,728.129 813.629,747.998C779.607,768.105 735.654,756.44 716.118,721.898C697.799,689.456 708.783,646.74 740.469,627.126C774.212,606.234 818.353,617.261 838.467,651.208ZM661.698,686.672C660.364,750.513 711.216,803.541 775.047,804.875C838.887,806.218 891.909,755.357 893.244,691.532C894.594,627.695 843.75,574.667 779.886,573.329C716.048,571.992 663.027,622.835 661.698,686.672Z" style="fill:rgb(58,73,85);fill-rule:nonzero;"/>
                                    <path d="M774.935,809.886C708.341,808.494 655.292,753.172 656.696,686.566C658.073,619.972 713.386,566.92 780.004,568.318C846.601,569.71 899.638,625.032 898.264,691.638C896.86,758.238 841.556,811.29 774.935,809.886ZM963.551,707.458C964.861,693.986 964.746,680.414 963.136,666.833L931.632,659.299C929.977,651.041 927.637,642.804 924.69,634.733L947.776,611.954C945.001,605.826 941.869,599.783 938.365,593.885C934.9,588.008 931.12,582.383 927.075,576.988L895.995,586.274C890.345,579.757 884.266,573.774 877.771,568.378L886.375,537.138C875.251,529.192 863.401,522.535 850.973,517.203L828.656,540.806C820.616,538.021 812.364,535.903 804.003,534.448L795.754,503.011C782.316,501.709 768.72,501.854 755.212,503.513L747.613,535.002C739.344,536.605 731.167,538.935 723.102,541.928L700.301,518.861C694.225,521.608 688.128,524.701 682.239,528.223C676.348,531.694 670.719,535.486 665.341,539.483L674.595,570.618C668.101,576.276 662.116,582.344 656.717,588.825L625.477,580.25C617.512,591.334 610.873,603.193 605.544,615.591L629.123,637.929C626.342,645.978 624.287,654.23 622.813,662.591L591.409,670.833C590.081,684.276 590.217,697.875 591.83,711.428L623.376,718.933C624.989,727.236 627.298,735.428 630.246,743.538L607.181,766.308C609.941,772.442 613.042,778.455 616.55,784.322C620.054,790.241 623.818,795.891 627.837,801.253L658.959,791.99C664.594,798.484 670.719,804.463 677.167,809.853L668.61,841.129C679.715,849.061 691.535,855.749 703.92,861.011L726.286,837.45C734.35,840.255 742.523,842.346 750.96,843.778L759.182,875.209C772.599,876.482 786.222,876.398 799.773,874.776L807.277,843.245C815.568,841.645 823.769,839.311 831.833,836.325L854.711,859.404C860.786,856.672 866.812,853.54 872.703,850.047C878.61,846.562 884.196,842.782 889.592,838.733L880.341,807.653C886.841,802.006 892.823,795.9 898.216,789.439L929.48,798.006C937.427,786.903 944.111,775.047 949.371,762.653L925.792,740.288C928.552,732.292 930.698,724.062 932.147,715.665L963.551,707.458Z" style="fill:rgb(58,73,85);fill-rule:nonzero;"/>
                                    <path d="M893.244,691.532C891.909,755.357 838.887,806.218 775.047,804.875C711.216,803.541 660.364,750.513 661.698,686.672C663.027,622.835 716.048,571.992 779.886,573.329C843.75,574.667 894.594,627.695 893.244,691.532ZM656.696,686.566C655.292,753.172 708.341,808.494 774.935,809.886C841.556,811.29 896.86,758.238 898.264,691.638C899.638,625.032 846.601,569.71 780.004,568.318C713.386,566.92 658.073,619.972 656.696,686.566Z" style="fill:rgb(137,162,182);fill-rule:nonzero;"/>
                                    </g>
                                    </g>
                                    </svg>

                                </div>
                                <div class="rm-welcome-modal-box-title-wrap">
                                    <div class="rm-welcome-modal-box-title"><?php _e('Configure Global Settings.', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                                    <div class="rm-welcome-modal-box-description"><?php _e('Modify plugin level settings based on your requirements.', 'custom-registration-form-builder-with-submission-manager'); ?></div>

                                </div>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--- End Welcome Modal---->

    
<?php } ?>
<pre class='rm-pre-wrapper-for-script-tags'>
<script>
jQuery(window).load(function(e){
	load_form_counter();
	load_popular_forms();
	load_user_charts();
        drawTimewiseStat();
});
	function load_form_counter(){
		var formCounter = document.getElementById("formCounter");
		var counterData = {
		    labels: [["Today /", " Yesterday"], ["This Week /", " Last Week"],["This Month /", " Last Month"]],
		    datasets: [
                            {   label: "Submission",
                                barPercentage: 0.5,
                                data: <?php echo json_encode($data->count->chart_1); ?>,
                                backgroundColor: ["#358FD8"],
                               
                            },
                            {   
                                label: "Submission",
                                barPercentage: 0.5,
                                data: <?php echo json_encode($data->count->chart_2); ?>,
                                backgroundColor: ["#32B870"]
                            }
                        ]
		};
		var Counter = new Chart(formCounter, {
		  type: 'bar',
		  data: counterData,
		  options: {
		    responsive: true,
		    plugins: {
		      legend: {
                        display: false
		      },
		      title: {
		        display: false,
		        text: '<?php echo RM_UI_Strings::get("DASHBOARD_COUNTER_CHART_TITLE");?>'
		      },
                      tooltip: {
                        callbacks: {
                           title : () => null
                        }
                      }
		    },
                    
                    scale: {
                        y:{
                            ticks: {
                                precision: 0
                            },
                            min: 0
                        }
                    },
                    
		  }
		});
	}

	function load_popular_forms(){
		var canvas = document.getElementById("formChart");
		var ctx = canvas.getContext('2d');
		var data = {
		    labels: <?php echo json_encode($data->top_forms_label);?>,
		      datasets: [
		        {
		            label: "Submit",
		            fill: true,
		            backgroundColor: [
		                '#2271B1',
		                '#A6CEEE',
		                '#73B2E4','#358FD8','#2E5E84'],
                            borderWidth: 0,
		            data: <?php echo json_encode($data->top_forms_count);?>
		        }
		    ]
		};
		var options = {
                        cutout : '30%',
		        responsive: true,
			    plugins: {
			      legend: {
			        position: 'left',
                                fontColor:'#A6CEEE',
                                labels: {
                                  //color: 'rgb(255, 99, 132)',  
                              }
			      },
			      title: {
			        display: false,
			        text: '<?php echo RM_UI_Strings::get("DASHBOARD_FORMS_CHART_TITLE");?>'
			      }
			    }
			  };
		var myBarChart = new Chart(ctx, {
		    type: 'pie',
		    data: data,
		    options: options 
		});
	}
	function load_user_charts(){
		var canvas = document.getElementById("userChart");
		var ctx = canvas.getContext('2d');
		var data = {
		    labels: <?php echo json_encode($data->users['date']);?>,
		      datasets: [
		        	{
				    label: 'User',
				    data: <?php echo json_encode($data->users['count']);?>,
				    borderColor: '#358FD8',
                                    backgroundColor: 'rgba(220, 238, 253, 0.5)',
                                    fill: true,
                                    borderWidth: 2,
                                    tension: .5
				}
		    	]
		};
		var options = {
                            responsive: true,
			    plugins: {
			      legend: {
                                  display:false,
			        position: 'bottom',
			      },
			      title: {
			        display: false,
			        text: '<?php echo RM_UI_Strings::get("DASHBOARD_USERS_CHART_TITLE");?>'
			      }
			    },
                            scale: {
                                y:{
                                    ticks: {
                                      precision: 0
                                    },
                                    min: 0
                                }
                            }
			  };

		// Chart declaration:
		var myBarChart = new Chart(ctx, {
		    type: 'line',
		    data: data,
		    options: options 
		});
	}
	function rm_refresh_stats(){
	    var form_id = jQuery('#rm_form_dropdown').val();
	    var trange = jQuery('#rm_stat_timerange').val();
	    if(typeof trange == 'undefined')
	        trange = <?php echo esc_html($data->rm_ur); ?>;
            var url = new URL(document.location.href);
            url.searchParams.set('rm_ur', trange);
            window.location = url;
	    //window.location = '?page=rm_dashboard_widget_dashboard&rm_ur='+trange;
	}
        function rm_refresh_stats_login(){
            var trange = jQuery('#rm_stat_timerange_login').val();
            if(typeof trange == 'undefined')
                trange = 60;
            var url = new URL(document.location.href);
            url.searchParams.set('rm_tr', trange);
            window.location = url;
            //window.location = '?page=rm_dashboard_widget_dashboard&rm_tr='+trange;
        }
        function rm_on_form_selection_change(){
            var form_id = jQuery("select.rm-dash-export-field").val();
            jQuery('#rm_form_export_id').val(form_id);
        }
        
        
        function drawTimewiseStat()
        {
        <?php
        $data_string_fail = array();
        $formatted_date = array();
        foreach ($data->day_wise_stat as $date => $per_day) {
            $formatted_date[] = $date;
            $data_string_success[] = $per_day->success;
            $data_string_fail[] = $per_day->fail;
        }
        ?>
        var canvas = document.getElementById("rm_subs_over_time_chart_div");
		var ctx = canvas.getContext('2d');
		var data = {
		    labels: <?php echo json_encode($formatted_date);?>,
		      datasets: [
		        	{
				    label: 'Login Success',
				    data: <?php echo json_encode($data_string_success);?>,
				    borderColor: '#32b871',
                                    backgroundColor: 'rgb(50, 184, 112, 0.15)',
                                    fill: true,
                                    borderWidth: 2,
                                    tension: .5
				}
		    	]
		};
		var options = {
		        responsive: true,
			    plugins: {
			      legend: {
                                  display:false,
			        position: 'bottom'
			      },
			      title: {
			        display: false,
			        text: '<?php echo RM_UI_Strings::get("DASHBOARD_USERS_CHART_TITLE");?>'
			      }
			    },
                            scale: {
                                y:{
                                    ticks: {
                                      precision: 0
                                    },
                                    min: 0
                                }
                            }
			  };

		// Chart declaration:
		var myBarChart = new Chart(ctx, {
		    type: 'line',
		    data: data,
		    options: options 
		});
        }
    
    
    
    jQuery(document).ready(function($){
        var rmDash_top_head = $( '.rm-dash-head-wrap' );
        $( '#wpbody-content' ).prepend( rmDash_top_head );
        rmDash_top_head.delay( 0 ).slideDown();
        
        //var rmDash_header = $( '.rm-dashboard-header' );
         //rmDash_header.delay( 1000 ).slideDown();
            //rmDash_header.slideDown();
         
           //rmDash_header.delay( 6000 ).css("opacity", "0");
           
             //rmDash_header.delay(1200).animate({opacity:1},3000);
             //jQuery(".rm-dash-counter-chart").delay(1200).animate({opacity:1},5000);
             //jQuery(".rm-dash-popular-chart").delay(1200).animate({opacity:1},7000);
              //jQuery(".rm_dash_submissions").delay(1200).animate({opacity:1},9000);
                
       setTimeout(function() {
           jQuery(".rm-dash-counter-chart").removeClass("rm-box-animated");
           jQuery(".rm-dash-popular-chart").removeClass("rm-box-animated"); 
           jQuery(".rm_dash_submissions").removeClass("rm-box-animated"); 
        }, 3000);
        
        
         

    });

    
function rm_copy_to_clipboard_dashboard(target,click_ele) {

    var text_to_copy = jQuery(target).text();

    var tmp = jQuery("<input id='fd_form_shortcode_input' readonly>");
    var target_html = jQuery(target).html();
    jQuery(target).html('');
    jQuery(target).append(tmp);
    tmp.val(text_to_copy).select();
    var result = document.execCommand("copy");

    if (result != false) {
        jQuery(target).html(target_html);
        jQuery(click_ele).text('done');
        setTimeout(function(){
            jQuery(click_ele).text('content_copy');
        },1000);
    } else {
        jQuery(document).mouseup(function (e) {
            var container = jQuery("#fd_form_shortcode_input");
            if (!container.is(e.target) // if the target of the click isn't the container... 
                    && container.has(e.target).length === 0) // ... nor a descendant of the container 
            {
                jQuery(target).html(target_html);
            }
        });
    }
}

function rm_Welcome_Modal(){
    //event.preventDefault();
    jQuery('#rm_welcome_modal').toggle();
    jQuery('.rm-modal-box-wrap').removeClass('rm-form-popup-out');
    jQuery('.rm-modal-box-wrap').addClass('rm-form-popup-in');
}

jQuery(document).ready(function () {
    jQuery('.rm-modal-close, .rm-modal-overlay, #rm-welcome-overview').click(function () {
        setTimeout(function () {
            //jQuery(this).parents('.rm-modal-view').hide();
            jQuery('.rm-modal-box-main').hide();
        }, 400);
    });        

    
    jQuery('.rm-modal-close, .rm-modal-overlay, #rm-welcome-overview').click(function () {
        jQuery('.rm-modal-box-wrap').removeClass('rm-form-popup-in');
        jQuery('.rm-modal-box-wrap').addClass('rm-form-popup-out');
        var data = {
            'action': 'rm_update_welcome_modal_option',
            'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
        };
        jQuery.post(ajaxurl, data, function (response) {});
    });

    <?php if(empty(get_site_option('rm_hide_welcome_modal'))) { ?>
    rm_Welcome_Modal();
    <?php } ?>

});
    
</script>
</pre>