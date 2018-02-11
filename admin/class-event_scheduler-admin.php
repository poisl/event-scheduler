<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_scheduler
 * @subpackage Event_scheduler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Event_scheduler
 * @subpackage Event_scheduler/admin
 * @author     Thomas Poisl <thomas@poisl.org>
 */
class Event_scheduler_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Event_scheduler_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Event_scheduler_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/event_scheduler-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Event_scheduler_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Event_scheduler_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/event_scheduler-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page(__('Event Scheduler - Configuration','event_scheduler'), 'Event Scheduler', 'manage_options', $this->plugin_name, array($this, 'event_scheduler_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     * @param $links
     * @return array
     */

    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __(__('Settings','event_scheduler'), $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function event_scheduler_setup_page()
    {
        include_once('partials/event_scheduler-admin-display.php');
    }

    /**
     * Update the pluigin settings.
     *
     * @since    1.0.0
     */

    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }

    /**
     * Validate the settings from the settings page.
     *
     * @since    1.0.0
     * @param $input
     * @return array
     */

    public function validate($input)
    {
        // All checkboxes inputs
        $valid = array();

        //Cleanup
        $valid['event_start_date'] = sanitize_text_field($input['event_start_date']);
        $valid['event_start_time'] = sanitize_text_field($input['event_start_time']);
        $valid['event_end_time'] = sanitize_text_field($input['event_end_time']);
        $valid['event_repeat_interval'] = sanitize_text_field($input['event_repeat_interval']);
        $valid['event_default_location'] = sanitize_text_field($input['event_default_location']);
        $valid['event_alternate_location'] = sanitize_text_field($input['event_alternate_location']);
        $valid['event_notification_url'] = sanitize_text_field($input['event_notification_url']);
        $valid['event_notification_subject'] = sanitize_text_field($input['event_notification_subject']);
        $valid['event_notification_body'] = esc_html($input['event_notification_body']);

        return $valid;
    }

}
