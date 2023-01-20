<?php
$name_for_condition = $name;
if( ! empty($additional['hook_name']) ) {
    $hook_name = $additional['hook_name'];
} else {
    return false;
}
echo '<div class="submitbox" id="submitpost">';
$condition_types = apply_filters($hook_name.'_types', array());
?>
    <div class="berocket_conditions_block">
        <div class="br_condition_example" style="display:none;">
            <div class="br_cond_select" data-current="1">
                <span>
                    <select class="br_cond_type">
                        <?php
                        foreach($condition_types as $condition_type_slug => $condition_type_name) {
                            echo '<option value="', $condition_type_slug, '">', $condition_type_name, '</option>';
                        }
                        ?>
                    </select>
                </span>
                <span class="button berocket_remove_condition"><i class="fa fa-minus"></i></span>
                <div class="br_current_cond">
                </div>
            </div>
            <span class="button berocket_add_condition"><i class="fa fa-plus"></i></span>
            <span class="button br_remove_group"><i class="fa fa-minus"></i></span>
        </div>
        <div class="br_cond_example" style="display:none;">
            <?php
            foreach($condition_types as $condition_type_slug => $condition_type_name) {
                $condition_html = apply_filters($hook_name . '_type_' . $condition_type_slug, '', '%name%[%id%][%current_id%]', array('is_example' => true));
                if( ! empty($condition_html) ) {
                    echo '<div class="br_cond br_cond_', $condition_type_slug, '">
                    ', $condition_html, '
                    <input type="hidden" name="%name%[%id%][%current_id%][type]" value="', $condition_type_slug, '">
                    </div>';
                }
            }
            ?>
        </div>
        <div class="br_conditions">
            <?php
            $last_id = 0;
            foreach($value as $id => $data) {
                $current_id = 1;
                ob_start();
                foreach($data as $current => $conditions) {
                    if( $current > $current_id ) {
                        $current_id = $current;
                    }
                    ?>
                    <div class="br_cond_select" data-current="<?php echo $current; ?>">
                        <span>
                            <select class="br_cond_type">
                                <?php
                                foreach($condition_types as $condition_type_slug => $condition_type_name) {
                                    echo '<option value="', $condition_type_slug, '"', ( isset($conditions['type']) && $conditions['type'] == $condition_type_slug ? ' selected' : '' ) , '>', $condition_type_name, '</option>';
                                }
                                ?>
                            </select>
                        </span>
                        <span class="button berocket_remove_condition"><i class="fa fa-minus"></i></span>
                        <div class="br_current_cond">
                        </div>
                    <?php 
                    $condition_html = apply_filters($hook_name . '_type_' . $conditions['type'], '', $name_for_condition . '[' . $id . '][' . $current . ']', $conditions);
                    if( ! empty($condition_html) ) {
                        echo '<div class="br_cond br_cond_', $conditions['type'], '">
                        ', $condition_html, '
                        <input type="hidden" name="' . $name_for_condition . '[' . $id . '][' . $current . '][type]" value="', $conditions['type'], '">
                        </div>';
                    }
                    ?>
                    </div>
                    <?php
                }
                ?>
                <span class="button berocket_add_condition"><i class="fa fa-plus"></i></span>
                <span class="button br_remove_group"><i class="fa fa-minus"></i></span>
                <?php
                $html = ob_get_clean();
                echo '<div class="br_html_condition" data-id="'.$id.'" data-current="'.$current_id.'">';
                echo $html;
                echo '</div>';
                if( $id > $last_id ) {
                    $last_id = $id;
                }
            }
            $last_id++;
            ?>
            <span class="button br_add_group"><i class="fa fa-plus"></i></span>
            <?php
            echo '<span class="br_condition_data"y
                    data-last_id="'.$last_id.'" 
                    data-condition_name="'.$name_for_condition.'">
                </span>';
            ?>
        </div>
    </div>
</div>
