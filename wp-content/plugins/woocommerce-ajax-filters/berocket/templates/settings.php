<?php
$plugin_info   = get_plugin_data( $this->cc->info[ 'plugin_file' ] );
if( ! empty($this->plugin_version_capability) && $this->plugin_version_capability > 10 ) {
    $meta_data = '?utm_source=paid_plugin&utm_medium=plugins&utm_campaign='.$this->info['plugin_name'];
} else {
    $meta_data = '?utm_source=free_plugin&utm_medium=plugins&utm_campaign='.$this->info['plugin_name'];
}
$dplugin_name  = $this->cc->info['full_name'];
$dplugin_link  = 'https://berocket.com/' . $this->cc->values['premium_slug'] . $meta_data;
$dplugin_price = $this->cc->info['price'];
$dplugin_desc  = $plugin_info['Description'];
$options       = $this->get_option();

?>
<div class="wrap br_framework_settings br_<?php echo $this->cc->info['plugin_name']?>_settings">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php echo $this->cc->info['full_name'] . ' ' . __( 'Settings', 'BeRocket_domain' )?></h2>
    <?php do_action('berocket_above_admin_settings', berocket_isset($this->plugin_version_capability), $this); ?>
    <?php settings_errors(); ?>
    <?php $this->cc->admin_settings() ?>
</div>

<?php
include 'settings_footer.php';
?>
