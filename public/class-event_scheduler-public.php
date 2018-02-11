<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_scheduler
 * @subpackage Event_scheduler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Event_scheduler
 * @subpackage Event_scheduler/public
 * @author     Thomas Poisl <thomas@poisl.org>
 */
class Event_scheduler_Public
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
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        /**
         * The class responsible for different helping operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/Div.php';

        /**
         * The class responsible for defining all database operations
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/EsDb.php';

        /**
         * The class responsible for defining all database operations on event table
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/EsEventDb.php';

        /**
         * The class responsible for defining all database operations on participant table
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/EsParticipantDb.php';

        /**
         * The class responsible for defining all database operations on holiday table
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/EsHolidayDb.php';

        // Add mail notification action
        add_action('event_scheduler_mail_notification', array($this, 'mail_notification'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/event_scheduler-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/event_scheduler-public.js', array('jquery'), $this->version, false);

    }

    /**
     * Registers all shortcodes at once
     *
     */

    public function register_shortcodes()
    {
        add_shortcode('event_scheduler_event_list', array($this, 'event_list'));
        add_shortcode('event_scheduler_holiday', array($this, 'holiday'));
        add_shortcode('event_scheduler_member_list', array($this, 'member_list'));
        add_shortcode('event_scheduler_event_statistics', array($this, 'event_statistics'));
    } // register_shortcodes()

    /**
     * Processes shortcode event_list
     *
     * @return mixed $output Output of the buffer
     */

    public function event_list()
    {
        ob_start();
        $div = new Div($this->plugin_name, $this->version);

        // Get current offset or initialize to 0
        $offset = $div->get_request_parameter('offset', 0);
        // Get request action
        $action = $div->get_request_parameter('action');

        // Initialize required plugin otions
        $options = get_option($this->plugin_name);
        $eventLocation = $options['event_default_location'];
        $alternateLocation = $options['event_alternate_location'];

        // Check if plugin settings are configured
        if (!$eventLocation) {
            die(__('Please configure the plugin settings first.'));
        }

        // Get next event date
        $nextEventDate = $div->nextEventDate($offset);

        // Check if event exists and create it if necessary
        $events_db = new EsEventDb;
        $events = $events_db->get_events(array('start' => $nextEventDate));
        if (count($events) == 0) {
            $div->createEvent($nextEventDate);
            $events = $events_db->get_events(array('start' => $nextEventDate));
        }

        // Process admin actions
        if (current_user_can('manage_options')) {
            if ($action == 'activate') {
                $div->activateEvent($events[0]->uid);
            } elseif ($action == 'deactivate') {
                $div->deactivateEvent($events[0]->uid);
            } elseif ($action == 'location') {
                if ($events[0]->location == $eventLocation) {
                    $div->setEventLocation($events[0]->uid, $alternateLocation);
                } else {
                    $div->setEventLocation($events[0]->uid, $eventLocation);
                }
            }
        }

        // Process participant actions
        $current_user = wp_get_current_user();

        if ($current_user instanceof WP_User) {
            if ($action == 'accept') {
                $div->acceptEvent($current_user->ID, $events[0]->uid);
            } elseif ($action == 'decline') {
                $div->declineEvent($current_user->ID, $events[0]->uid);
            }
        }

        // Refresh event in case there were some changes
        $events = $events_db->get_events(array('start' => $nextEventDate));

        // Get event participants
        $joiningUsers = $div->findAcceptingParticipants($events[0]->uid);
        $decliningUsers = $div->findDecliningParticipants($events[0]->uid);
        $findUndecidedParticipants = $div->findUndecidedParticipants($events[0]->uid);

        include_once('partials/event_scheduler-public-event-list-display.php');

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    } // event_list

    /**
     * Processes shortcode holiday
     *
     * @return mixed $output Output of the buffer
     */

    public function holiday()
    {
        ob_start();
        $div = new Div($this->plugin_name, $this->version);

        // Process admin actions
        if (current_user_can('manage_options')) {
            // Get request parameters
            $uid = $div->get_request_parameter('uid');
            $description = $div->get_request_parameter('description');
            $start = $div->get_request_parameter('start');
            $end = $div->get_request_parameter('end');
            $action = $div->get_request_parameter('action');

            if ($action == 'create' && (!empty($description) || !empty($start) || !empty($end))) {
                $div->createHoliday($description, $start, $end);
            } elseif ($action == 'update' && ($uid >= 0 || !empty($description) || !empty($start) || !empty($end))) {
                $div->updateHoliday($uid, $description, $start, $end);
            } elseif ($action == 'delete' && ($uid >= 0)) {
                $div->deleteHoliday($uid);
            }
        }

        // Fetch list of upcoming holiday
        $now = new \DateTime(date("Y-m-d"));
        $holidays = $div->findHolidayAfterDate($now);

        // Admins can manage holidays
        if (current_user_can('manage_options')) {
            include_once('partials/event_scheduler-public-holiday-admin-display.php');
        } // Normal users will only see the list of upcoming holidays
        else {
            include_once('partials/event_scheduler-public-holiday-display.php');
        }

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    } // holiday

    /**
     * Processes shortcode member_list
     *
     * @return mixed $output Output of the buffer
     */

    public function member_list()
    {
        ob_start();
        $div = new Div($this->plugin_name, $this->version);

        // Get active wordpress users ids
        $members = $div->getWordpressUsers();

        include_once('partials/event_scheduler-public-member-list-display.php');

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }// member_list

    /**
     * Processes shortcode event_statistics
     *
     * @return mixed $output Output of the buffer
     */

    public function event_statistics()
    {
        ob_start();
        $div = new Div($this->plugin_name, $this->version);

        $oldestEventYear = $div->getOldestEventYear();

        // Only make calculation if an event exists
        if (!empty($oldestEventYear)) {
            $currentYear = date("Y");

            $index = 0;
            $statistics = array();

            for ($i = $currentYear; $i >= $oldestEventYear; $i--) {
                $year = $i;

                $statistics[$index]['year'] = $year;

                $statistics[$index]['activeEvents'] = count($div->findEventsOfYearByActive($year, '1'));
                $statistics[$index]['inactiveEvents'] = count($div->findEventsOfYearByActive($year, '0'));

                $averageAccepts = $div->calculateAverageAcceptsPerEventByYear($year, '1');
                $statistics[$index]['averageAccepts'] = $averageAccepts[0]->average;
                $statistics[$index]['minimumAccepts'] = $averageAccepts[0]->minimum;
                $statistics[$index]['maximumAccepts'] = $averageAccepts[0]->maximum;

                $averageCancels = $div->calculateAverageAcceptsPerEventByYear($year, '0');
                $statistics[$index]['averageCancels'] = $averageCancels[0]->average;
                $statistics[$index]['minimumCancels'] = $averageCancels[0]->minimum;
                $statistics[$index]['maximumCancels'] = $averageCancels[0]->maximum;

                $tops = $div->topParticipantsByYear($year);
                for ($j = 0; $j <= count($tops) - 1; $j++) {
                    $statistics[$index]['tops'][$j]['participantId'] = $tops[$j]->participantId;
                    $statistics[$index]['tops'][$j]['eventsAccepted'] = $tops[$j]->eventsAccepted;
                }
                $index++;
            }
        }

        include_once('partials/event_scheduler-public-event-statistics-display.php');

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }// event_statistics

    /**
     * Processes action for mail notification of undecided members
     *
     * @return void
     */

    public function mail_notification()
    {
        $div = new Div($this->plugin_name, $this->version);

        $nextEventDate = $div->nextEventDate();

        // Check if event exists and create it if necessary
        $events_db = new EsEventDb;
        $events = $events_db->get_events(array('start' => $nextEventDate));
        if (count($events) == 0) {
            $div->createEvent($nextEventDate);
        }

        // Now check if the next event is active, if not nobody needs to be notified
        $events = $events_db->get_events(array('start' => $nextEventDate, 'active' => 1));
        if (count($events) > 0) {
            // Initialize required plugin options
            $options = get_option($this->plugin_name);
            $option_event_notification_url = $options['event_notification_url'];

            $accept_url = $option_event_notification_url . "?action=accept";
            $decline_url = $option_event_notification_url . "?action=decline";

            $notify_users = $div->findUndecidedParticipants($events[0]->uid);

            foreach ($notify_users as $row) {
                $user_info = get_userdata($row->ID);
                $receiver_email = $user_info->user_email;
                $receiver_name = $user_info->first_name . " " . $user_info->last_name;
                $mail_body = html_entity_decode(str_replace(
                    array('###name###', '###eventdate###', '###eventtime###', '###eventlocation###', '###acceptlink###', '###cancellink###'),
                    array($receiver_name, DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('d.m.Y'), $options['event_start_time'], $events[0]->location, $accept_url, $decline_url),
                    $options['event_notification_body']
                ));

                $headers = array('Content-Type: text/html; charset=UTF-8');

                // Send mail
                wp_mail($receiver_email, $options['event_notification_subject'], $mail_body, $headers);
            }

        }
    }// mail_notification

    /**
     * current event url
     *
     * @return string to current event
     */
    public function currentEventUrl()
    {
        return esc_url(remove_query_arg('action', add_query_arg('offset', 0, $_SERVER['REQUEST_URI'])));
    }

    /**
     * next event url
     *
     * @param int $offset
     * @return string to next event
     */
    public function nextEventUrl($offset)
    {
        return esc_url(remove_query_arg('action', add_query_arg('offset', ++$offset, $_SERVER['REQUEST_URI'])));
    }

    /**
     * last event url
     *
     * @param int $offset
     * @return string to last event
     */
    public function lastEventUrl($offset)
    {
        return esc_url(remove_query_arg('action', add_query_arg('offset', --$offset, $_SERVER['REQUEST_URI'])));
    }

    /**
     * accept event url
     *
     * @param int $offset
     * @return string to accept event
     */
    public function acceptEventUrl($offset)
    {
        return esc_url(add_query_arg(array(
            'action' => 'accept',
            'offset' => $offset,
        ), $_SERVER['REQUEST_URI']));
    }

    /**
     * decline event url
     *
     * @param int $offset
     * @return string to decline event
     */
    public function declineEventUrl($offset)
    {
        return esc_url(add_query_arg(array(
            'action' => 'decline',
            'offset' => $offset,
        ), $_SERVER['REQUEST_URI']));
    }

    /**
     * activate event url
     *
     * @param int $offset
     * @return string to activate event
     */
    public function activateEventUrl($offset)
    {
        return esc_url(add_query_arg(array(
            'action' => 'activate',
            'offset' => $offset,
        ), $_SERVER['REQUEST_URI']));
    }

    /**
     * deactivate event url
     *
     * @param int $offset
     * @return string to deactivate event
     */
    public function deactivateEventUrl($offset)
    {
        return esc_url(add_query_arg(array(
            'action' => 'deactivate',
            'offset' => $offset,
        ), $_SERVER['REQUEST_URI']));
    }

    /**
     * Change event location url
     *
     * @param int $offset
     * @return string to deactivate event
     */
    public function changeEventLocationUrl($offset)
    {
        return esc_url(add_query_arg(array(
            'action' => 'location',
            'offset' => $offset,
        ), $_SERVER['REQUEST_URI']));
    }
}
