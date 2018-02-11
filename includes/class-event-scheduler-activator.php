<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_Scheduler
 * @subpackage Event_Scheduler/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Event_Scheduler
 * @subpackage Event_Scheduler/includes
 * @author     Thomas Poisl <thomas@poisl.org>
 */
class Event_Scheduler_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;

        $sql = array();

        //event table
        $event_table = $wpdb->prefix . "event_scheduler_event";

        $sql[] = "CREATE TABLE " . $event_table . "     (
        uid int(11) NOT NULL auto_increment,
	    start datetime DEFAULT '0000-00-00 00:00:00',
	    end datetime DEFAULT '0000-00-00 00:00:00',
	    location varchar(255) DEFAULT '' NOT NULL,
	    active tinyint(1) unsigned DEFAULT '0' NOT NULL,
	    inactive_reason varchar(255) DEFAULT '' NOT NULL,
	    participants int(11) unsigned DEFAULT '0' NOT NULL,
        PRIMARY KEY  (uid)
        ) ";

        //participant table
        $participant_table = $wpdb->prefix . "event_scheduler_participant";

        $sql[] = "CREATE TABLE " . $participant_table . "   (
        uid int(11) NOT NULL auto_increment,
	    event_id int(11) unsigned DEFAULT '0' NOT NULL,
	    accept tinyint(1) unsigned DEFAULT '0' NOT NULL,
	    participant_user_id int(11) unsigned DEFAULT '0',
	    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (uid)
        ) ";

        //holiday table
        $holiday_table = $wpdb->prefix . "event_scheduler_holiday";

        $sql[] = "CREATE TABLE " . $holiday_table . "   (
        uid int(11) NOT NULL auto_increment,
	    description varchar(255) DEFAULT '' NOT NULL,
	    start date DEFAULT '0000-00-00',
	    end date DEFAULT '0000-00-00',
        PRIMARY KEY  (uid)
        ) ";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

}
