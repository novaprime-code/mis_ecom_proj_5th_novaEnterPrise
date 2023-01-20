<div class="rm-user-row dbfl" id="rm_my_sub_tab" style="display: none;">
            <div class="rm-user-row rm-icon dbfl">
                <i class="material-icons rm-bg" data-rm_apply_acc_bgcolor='true' >assignment_turned_in</i>
            </div>
            <div class="rm-user-row dbfl">
                <h2><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_MY_SUBS')); ?></h2>
            </div>
            <?php
            if ($data->submission_exists === true) {
                ?>
                <table class="rm-user-data">
                    <tr>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_SR')); ?></th>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_FORM')); ?></th>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DATE')); ?></th>
                        <?php if(defined('REGMAGIC_ADDON')) { ?>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DOWNLOAD')); ?></th>
                        <?php } ?>
                    </tr>
                    <?php
                    $i = 0;
                    if ($data->submissions):
                        foreach ($data->submissions as $data_single):
                            $submission = new RM_Submissions();
                            $submission->load_from_db($data_single->submission_id);
                            $token = $submission->get_unique_token();
                            $url = add_query_arg('submission_id', $data_single->submission_id);
                            ?>  
                            <tr>
                                <td class="<?php echo defined('REGMAGIC_ADDON') ? 'rm-sr-number' : ''; ?>" width="<?php echo defined('REGMAGIC_ADDON') ? '52' : ''; ?>" id="<?php echo esc_attr($data_single->submission_id); ?>"><?php echo esc_html(++$i); ?></td>
                                <td><a href="<?php echo esc_url($url);  ?>"><?php echo esc_html($data_single->form_name); ?></a></td>
                                <td><?php echo esc_html(RM_Utilities::localize_time($data_single->submitted_on, $data->date_format)); ?></td>
                                <?php if(defined('REGMAGIC_ADDON')) { ?>
                                <td><a target="_blank" href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data_single->submission_id.'&action=rm_print_pdf&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><i class="material-icons">cloud_download</i></a></td>
                                <?php } else { ?>
                                <form id="rmsubmissionfrontform<?php echo esc_attr($data_single->submission_id); ?>" method="post">
                                    <input type="hidden" value="<?php echo esc_attr($data_single->submission_id); ?>" name="rm_submission_id">
                                </form>
                                <?php } ?>
                            </tr>
                            <?php
                        endforeach;
                    else:

                    endif;
                    ?>
                </table>
                <?php
                /*             * ********** Pagination Logic ************** */
                $max_pages_without_abb = 10;
                $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

                if ($data->total_pages_sub > 1):
                    ?>
                    <ul class="rmpagination">
                        <?php
                        if ($data->curr_page_sub > 1):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=1')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_FIRST')); ?></a></li>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo esc_html($data->curr_page_sub - 1); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PREVIOUS')); ?></a></li>
                            <?php
                        endif;
                        if ($data->total_pages_sub > $max_pages_without_abb):
                            if ($data->curr_page_sub > $max_visible_pages_near_current_page + 1):
                                ?>
                                <li><a> ... </a></li>
                                <?php
                                $first_visible_page = $data->curr_page_sub - $max_visible_pages_near_current_page;
                            else:
                                $first_visible_page = 1;
                            endif;

                            if ($data->curr_page_sub < $data->total_pages_sub - $max_visible_pages_near_current_page):
                                $last_visible_page = $data->curr_page_sub + $max_visible_pages_near_current_page;
                            else:
                                $last_visible_page = $data->total_pages_sub;
                            endif;
                        else:
                            $first_visible_page = 1;
                            $last_visible_page = $data->total_pages_sub;
                        endif;
                        for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                            if ($i != $data->curr_page_sub):
                                ?>
                                <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo esc_attr($i); ?>')"><a><?php echo esc_html($i); ?></a></li>
                            <?php else:
                                ?>
                                <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo esc_attr($i); ?>')"><a class="active"?><?php echo esc_html($i); ?></a></li>
                            <?php
                            endif;
                        endfor;
                        if ($data->total_pages_sub > $max_pages_without_abb):
                            if ($data->curr_page_sub < $data->total_pages_sub - $max_visible_pages_near_current_page):
                                ?>
                                <li><a> ... </a></li>
                                <?php
                            endif;
                        endif;
                        ?>
                        <?php
                        if ($data->curr_page_sub < $data->total_pages_sub):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo esc_html($data->curr_page_sub + 1); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_NEXT')); ?></a></li>
                            <li onclick="get_tab_and_redirect('rm_reqpage_sub=<?php echo esc_html($data->total_pages_sub); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LAST')); ?></a></li>
                            <?php
                        endif;
                        ?>
                    </ul>
                    <?php
                endif;
            } else
                echo wp_kses_post(RM_UI_Strings::get('MSG_NO_SUBMISSION_FRONT'));
            do_action('rm_extend_front_registrations_view');
            
            ?>
        </div>