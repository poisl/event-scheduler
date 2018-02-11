<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_scheduler
 * @subpackage Event_scheduler/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Event_scheduler
 * @subpackage Event_scheduler/includes
 * @author     Thomas Poisl <thomas@poisl.org>
 */
class Event_scheduler_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'event-scheduler',
			false,
            basename( dirname( __FILE__ ) ) . '/languages'
		);

	}



}
