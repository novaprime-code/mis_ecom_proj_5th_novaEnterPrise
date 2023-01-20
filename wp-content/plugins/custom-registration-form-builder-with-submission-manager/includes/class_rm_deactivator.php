<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://registration_magic.com
 * @since      1.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/includes
 * @author     CMSHelplive
 */
class RM_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
            rm_stop_cron(); //stop cron
            do_action("registrationmagic_deactivated");
            if (method_exists('RM_Chronos_Service', 'delete_cron_on_deactivate_plugin')){
                $cron_service = new RM_Chronos_Service;
                $cron_service->delete_cron_on_deactivate_plugin();
            }
	}

}