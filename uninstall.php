<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_scheduler
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$event_table = $wpdb->prefix . "event_scheduler_event";
$participant_table = $wpdb->prefix . "event_scheduler_participant";
$holiday_table = $wpdb->prefix . "event_scheduler_holiday";

//Remove custom tables (if it exists)
$wpdb->query("DROP TABLE IF EXISTS $event_table");
$wpdb->query("DROP TABLE IF EXISTS $participant_table");
$wpdb->query("DROP TABLE IF EXISTS $holiday_table");

/*Remove any other options your plug-in installed and clear any plug-in cron jobs */
$options = delete_option($this->plugin_name);

if (wp_next_scheduled('event_scheduler_mail_notification')) {
    $timestamp = wp_next_scheduled('event_scheduler_mail_notification');
    wp_unschedule_event($timestamp, 'event_scheduler_mail_notification');
}

