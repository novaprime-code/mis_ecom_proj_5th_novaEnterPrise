<div class="rmagic-table" id="rm_my_pay_tab" style="display: none;">

            <div class="rm-user-row rm-icon dbfl">
                <i class="material-icons rm-bg" data-rm_apply_acc_bgcolor="true">credit_card</i>
            </div>
            <div class="rm-user-row dbfl">
                <h2><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PAY_HISTORY')); ?></h2>
            </div>
            <?php
            if (empty($data->payments)) {
                _e('You have not made any payment transactions yet.', 'custom-registration-form-builder-with-submission-manager');
            }
            ?>
            <?php if ($data->payments): ?>
                <table class="rm-user-data">
                    <tr>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_DATE')); ?></th>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_FORM')); ?></th>
                        <th class="rm-bg-lt"><?php _e('Unique ID', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_AMOUNT')); ?></th>
                        <th class="rm-bg-lt"><?php echo defined('REGMAGIC_ADDON') ? wp_kses_post(RM_UI_Strings::get('LABEL_TXN_ID')) : wp_kses_post(RM_UI_Strings::get('LABEL_INVOICE_SHORT')); ?></th>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_STATUS')); ?></th>
                        <?php 
                            $enable_user_invoice = get_option('enable_user_invoice');
                            $enable_invoice = get_option('enable_invoice');
                           if($enable_user_invoice=='yes' && $enable_invoice =='yes' && defined('REGMAGIC_ADDON')):
                        ?>
                        <th class="rm-bg-lt"><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_INVOICE_TH')); ?></th>
                        <?php endif;?>
                    </tr>
                    <?php
                    for ($i = $data->offset_pay; $i < $data->end_offset_this_page; $i++):
                        $submission = new RM_Submissions();
                        $submission->load_from_db($data->payments[$i]->submission_id);
                        $token = $submission->get_unique_token();
                        $url = add_query_arg('submission_id', $data->payments[$i]->submission_id);
                        ?>
                        <tr>
                            <td><?php echo esc_html(RM_Utilities::localize_time($data->payments[$i]->posted_date, $data->date_format)); ?></td>
                            <td><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($data->form_names[$data->payments[$i]->submission_id]); ?></a></td>
                            <td><?php echo !empty($token) ? esc_html($token) : ''; ?></td>
                            <td><?php echo esc_html($data->payments[$i]->total_amount); ?></td>
                            <td><?php echo esc_html($data->payments[$i]->invoice); ?></td>
                            <td><?php echo esc_html($data->payments[$i]->status); ?></td>
                            <?php if($enable_user_invoice=='yes' && $enable_invoice =='yes' && defined('REGMAGIC_ADDON')):?>
                                <td>
                                    <?php if((strtolower($data->payments[$i]->status) == 'completed' || strtolower($data->payments[$i]->status) == 'succeeded')):?>
                                    <a href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->payments[$i]->submission_id.'&action=rm_download_invoice_pdf&type=D&invoice_id='.$data->payments[$i]->invoice.'&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><span class="material-icons" > download </span> </a>
                                    <?php endif;?>
                                </td>

                            <?php endif;?>
                        </tr>
                        <?php
                    endfor;
                    ?>
                </table>

                <?php
                /*             * ********** Pagination Logic ************** */
                $max_pages_without_abb = 10;
                $max_visible_pages_near_current_page = 3; //This many pages will be shown on both sides of current page number.

                if ($data->total_pages_pay > 1):
                    ?>
                    <ul class="rmpagination">
                        <?php
                        if ($data->curr_page_pay > 1):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=1')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_FIRST')); ?></a></li>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo esc_html($data->curr_page_pay - 1); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_PREVIOUS')); ?></a></li>
                            <?php
                        endif;
                        if ($data->total_pages_pay > $max_pages_without_abb):
                            if ($data->curr_page_pay > $max_visible_pages_near_current_page + 1):
                                ?>
                                <li><a> ... </a></li>
                                <?php
                                $first_visible_page = $data->curr_page_pay - $max_visible_pages_near_current_page;
                            else:
                                $first_visible_page = 1;
                            endif;

                            if ($data->curr_page_pay < $data->total_pages_pay - $max_visible_pages_near_current_page):
                                $last_visible_page = $data->curr_page_pay + $max_visible_pages_near_current_page;
                            else:
                                $last_visible_page = $data->total_pages_pay;
                            endif;
                        else:
                            $first_visible_page = 1;
                            $last_visible_page = $data->total_pages_pay;
                        endif;
                        for ($i = $first_visible_page; $i <= $last_visible_page; $i++):
                            if ($i != $data->curr_page_pay):
                                ?>
                                <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo esc_html($i); ?>')"><a><?php echo esc_html($i); ?></a></li>
                            <?php else:
                                ?>
                                <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo esc_html($i); ?>')"><a class="active"><?php echo esc_html($i); ?></a></li>
                                <?php
                                endif;
                            endfor;
                            if ($data->total_pages_pay > $max_pages_without_abb):
                                if ($data->curr_page_pay < $data->total_pages_pay - $max_visible_pages_near_current_page):
                                    ?>
                                <li><a> ... </a></li>
                                <?php
                            endif;
                        endif;
                        ?>
                        <?php
                        if ($data->curr_page_pay < $data->total_pages_pay):
                            ?>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo esc_html($data->curr_page_pay + 1); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_NEXT')); ?></a></li>
                            <li onclick="get_tab_and_redirect('rm_reqpage_pay=<?php echo esc_html($data->total_pages_pay); ?>')"><a><?php echo wp_kses_post(RM_UI_Strings::get('LABEL_LAST')); ?></a></li>
                            <?php
                        endif;
                        ?>
                    </ul>
                <?php endif; ?>
                <!-- Pagination Ends    -->
            <?php endif; ?>
        </div> 