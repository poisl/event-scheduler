<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/poisl
 * @since             1.0.0
 * @package           Event_Scheduler
 *
 * @wordpress-plugin
 * Plugin Name:       Event Scheduler
 * Plugin URI:        https://github.com/poisl/event-scheduler
 * Description:       Event scheduler can be used for planning of recurring events.
 * Version:           1.0.5
 * Author:            Thomas Poisl
 * Author URI:        https://profiles.wordpress.org/poisl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       event-scheduler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EVENT_SCHEDULER_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-event-scheduler-activator.php
 */
function activate_event_scheduler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-scheduler-activator.php';
	Event_Scheduler_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-event-scheduler-deactivator.php
 */
function deactivate_event_scheduler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-scheduler-deactivator.php';
	Event_Scheduler_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_event_scheduler' );
register_deactivation_hook( __FILE__, 'deactivate_event_scheduler' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-event-scheduler.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_event_scheduler() {

	$plugin = new Event_Scheduler();
	$plugin->run();

}
run_event_scheduler();
