<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * DB base class
 *
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
class Div
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
    }

    /**
     * Get next event date
     *
     * @param int $offset
     * @return \DateTime $nextEventDate
     */
    public function nextEventDate($offset = 0)
    {
        // Initialize required plugin options
        $options = get_option($this->plugin_name);
        $option_event_start_time = $options['event_start_time'];
        $option_event_repeat_interval = $options['event_repeat_interval'];

        $eventStartTime = $option_event_start_time;
        $repeatEventInterval = $option_event_repeat_interval;
        $daysUntilNextEvent = $this->daysUntilNextEvent();

        $nextEventDate = new \DateTime(
            date("Y-m-d H:i:s",
                mktime(
                    substr($eventStartTime,
                        0,
                        2),
                    substr($eventStartTime,
                        3,
                        2),
                    '00',
                    date("m"),
                    (int)date("d")
                    + $daysUntilNextEvent
                    + intval($offset)
                    * intval($repeatEventInterval)
                    * intval(7),
                    date("Y")
                )
            )
        );

        return $nextEventDate;
    }

    /**
     * Checks how many days it is until the next event starts
     *
     * @return int $daysUntilNextEvent
     */
    public function daysUntilNextEvent()
    {
        // Initialize required plugin otions
        $options = get_option($this->plugin_name);
        $option_event_start_date = $options['event_start_date'];
        $option_event_repeat_interval = $options['event_repeat_interval'];

        $eventStartDate = new \DateTime(date('Y-m-d', strtotime($option_event_start_date)));
        $today = new \DateTime(date('Y-m-d'));
        $repeatEventInterval = $option_event_repeat_interval;

        for ($nextDate = $eventStartDate; $nextDate < $today; $nextDate->modify("+" . $repeatEventInterval . " week")) {
        }

        $diff = $today->diff($nextDate);

        return $diff->days;
    }

    /**
     * Returns the year of the oldest event
     *
     * @return int $year_of_oldest_event
     */
    public function getOldestEventYear()
    {
        $event_db = new EsEventDb();

        $events = $event_db->get_events();

        if (count($events) > 0) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('Y');
        }
        return '';
    }

    /**
     * Average of accepts or cancels per active or inactive event for a given year
     *
     * @param $year
     * @param string $active
     * @return array|bool|int|mixed|null|object
     */
    public function findEventsOfYearByActive($year, $active = '0')
    {
        $event_db = new EsEventDb();

        $start_of_year = new \DateTime(date('Y-m-d H:i:s', strtotime(($year . '-01-01 00:00:00'))));
        $end_of_year = new \DateTime(date('Y-m-d H:i:s', strtotime(($year . '-12-31 23:59:59'))));

        if ($year == date('Y')) {
            $end_of_year = new \DateTime('now');
        }

        $events = $event_db->get_events(array('active' => $active, 'start' => array('start' => $start_of_year, 'end' => $end_of_year)));
        return $events;
    }

    /**
     * Checks how many events in the given year are active or inactive
     *
     * @param $year
     * @param string $active
     * @return array|bool|int|mixed|null|object
     */
    public function calculateAverageAcceptsPerEventByYear($year, $active = '0')
    {
        $event_db = new EsEventDb();

        $events = $event_db->calculateAverageAcceptsPerEventByYearFromDb($year, $active);
        return $events;
    }

    /**
     * Top 3 participants of a given year
     *
     * @param $year
     * @return array
     */
    public function topParticipantsByYear($year)
    {
        $event_db = new EsEventDb();

        $events = $event_db->topParticipantsByYearFromDb($year);
        return $events;
    }

    /**
     * Create event
     *
     * @param \DateTime $eventStartDate
     * @return void
     */
    public function createEvent($eventStartDate)
    {
        $utcTimeZone = new \DateTimeZone('UTC');
        $eventStartDate->setTimezone($utcTimeZone);

        // Initialize required plugin otions
        $options = get_option($this->plugin_name);
        $eventStartTime = $options['event_start_time'];
        $eventEndTime = $options['event_end_time'];
        $eventLocation = $options['event_default_location'];
        $eventEndDate = clone $eventStartDate;
        $eventEndDate->setTimezone($utcTimeZone);
        $eventStartDate->setTime(substr($eventStartTime, 0, 2), substr($eventStartTime, 3, 2), '00');
        $eventEndDate->setTime(substr($eventEndTime, 0, 2), substr($eventEndTime, 3, 2), '00');
        $active = 1;
        $inactive_reason = '';

        $event_inside_holiday = $this->dateInsideHoliday($eventStartDate);

        if (!empty($event_inside_holiday)) {
            $active = 0;
            $inactive_reason = $event_inside_holiday;
        }
        $event = array(
            'start' => date_format($eventStartDate, "Y-m-d H:i:s"),
            'end' => date_format($eventEndDate, "Y-m-d H:i:s"),
            'location' => $eventLocation,
            'active' => $active,
            'inactive_reason' => $inactive_reason,
            'participants' => 0,
        );
        $events_db = new EsEventDb();
        $events_db->insert($event);
    }

    /**
     * Check if even date is inside holiday (if not FALSE, else description of the holiday is returned)
     *
     * @param \DateTime $eventDate
     * @return string $dateInsideHoliday
     */
    public function dateInsideHoliday($eventDate)
    {
        $holiday_db = new EsHolidayDb();

        $holidays = $holiday_db->get_holidays(array('date_in_holiday' => $eventDate));

        if (count($holidays) > 0) {
            return $holidays[0]->description;
        }
        return '';
    }

    /**
     * Finds holidays that are scheduled after a certain date
     *
     * @param $date
     * @return array|bool|int|mixed|null|object
     */
    public function findHolidayAfterDate($date)
    {
        $holiday_db = new EsHolidayDb();

        $holidays = $holiday_db->get_holidays(array('start' => array('start' => $date)));
        return $holidays;
    }

    /**
     * Finds accepting participants
     *
     * @param $event_id
     * @return array|bool|int|mixed|null|object
     */
    public function findAcceptingParticipants($event_id)
    {
        $participant_db = new EsParticipantDb();

        $participants = $participant_db->get_participants(array('event_id' => $event_id, 'accept' => 1));
        return $participants;
    }

    /**
     * Finds declining participants
     *
     * @param $event_id
     * @return array|bool|int|mixed|null|object
     */
    public function findDecliningParticipants($event_id)
    {
        $participant_db = new EsParticipantDb();

        $participants = $participant_db->get_participants(array('event_id' => $event_id, 'accept' => '0'));
        return $participants;
    }

    /**
     * Finds undecided participants
     *
     * @param $event_id
     * @return array|bool|int|mixed|null|object
     */
    public function findUndecidedParticipants($event_id)
    {
        $participant_db = new EsParticipantDb();
        // Get array with IDs of accepting and declining participants of an event
        $decided_participants_ids = array_column($participant_db->get_participants(array('event_id' => $event_id, 'active' => array('0', '1'))), 'participant_user_id');

        $undecided_participants = $this->getWordpressUsers($decided_participants_ids);

        return $undecided_participants;
    }

    /**
     * Gets wordpress user IDs with an additional array of user IDs to exclude
     *
     * @param array $exclude_user_ids
     * @return array|bool|int|mixed|null|object
     */
    public function getWordpressUsers($exclude_user_ids = array())
    {
        if (is_plugin_active('wp-members/wp-members.php')) {
            // WP-Members plugin is active, so we only get active users
            $wordpress_user_ids = get_users(array(
                'fields' => array('ID'),
                'exclude' => $exclude_user_ids,
                'meta_key' => 'active',
                'meta_value' => '1',
                'meta_compare' => '=',
            ));
        } else {
            $wordpress_user_ids = get_users(array(
                'fields' => array('ID'),
                'exclude' => $exclude_user_ids,
            ));
        }
        return $wordpress_user_ids;
    }

    /**
     * Create holiday
     *
     * @param $description
     * @param $holidayStartDate
     * @param $holidayEndDate
     * @return void
     */
    public function createHoliday($description, $holidayStartDate, $holidayEndDate)
    {
        $holiday_db = new EsHolidayDb();

        $holiday = array(
            'description' => $description,
            'start' => $holidayStartDate,
            'end' => $holidayEndDate,
        );
        $holiday_db->insert($holiday);
    }

    /**
     * Activate event
     *
     * @param $uid
     * @return void
     */
    public function activateEvent($uid)
    {
        $event_db = new EsEventDb();

        $event = array(
            'active' => 1,
            'inactive_reason' => '',
        );
        $event_db->update($uid, $event);
    }

    /**
     * Deactivate event
     *
     * @param $uid
     * @return void
     */
    public function deactivateEvent($uid)
    {
        $event_db = new EsEventDb();

        $event = array(
            'active' => 0,
            'inactive_reason' => 'Administrator',
        );
        $event_db->update($uid, $event);
    }

    /**
     * Set event location
     *
     * @param $uid
     * @param $location
     * @return void
     */
    public function setEventLocation($uid, $location)
    {
        $event_db = new EsEventDb();

        $event = array(
            'location' => $location,
        );
        $event_db->update($uid, $event);
    }

    /**
     * Accept event
     *
     * @param $user_id
     * @param $event_id
     * @return void
     */
    public function acceptEvent($user_id, $event_id)
    {
        $participant_db = new EsParticipantDb();
        $participants = $participant_db->get_participants(array('participant_user_id' => $user_id, 'event_id' => $event_id));

        $participant = array(
            'participant_user_id' => $user_id,
            'event_id' => $event_id,
            'time' => date('Y-m-d H:i:s'),
            'accept' => 1,
        );

        if (count($participants) == 0) {
            $participant_db->insert($participant);
        } else {
            if ($participants[0]->accept == 0) {
                $participant_db->update($participants[0]->uid, $participant);
            }
        }
    }

    /**
     * Decline event
     *
     * @param $user_id
     * @param $event_id
     * @return void
     */
    public function declineEvent($user_id, $event_id)
    {
        $participant_db = new EsParticipantDb();
        $participants = $participant_db->get_participants(array('participant_user_id' => $user_id, 'event_id' => $event_id));

        $participant = array(
            'participant_user_id' => $user_id,
            'event_id' => $event_id,
            'time' => date('Y-m-d H:i:s'),
            'accept' => 0,
        );

        if (count($participants) == 0) {
            $participant_db->insert($participant);
        } else {
            if ($participants[0]->accept) {
                $participant_db->update($participants[0]->uid, $participant);
            }
        }
    }

    /**
     * Update holiday
     *
     * @param $uid
     * @param $description
     * @param $holidayStartDate
     * @param $holidayEndDate
     * @return void
     */
    public function updateHoliday($uid, $description, $holidayStartDate, $holidayEndDate)
    {
        $holiday_db = new EsHolidayDb();

        $holiday = array(
            'description' => $description,
            'start' => $holidayStartDate,
            'end' => $holidayEndDate,
        );
        $holiday_db->update($uid, $holiday);
    }

    /**
     * Delete holiday
     *
     * @param $uid
     * @return void
     */
    public function deleteHoliday($uid)
    {
        $holiday_db = new EsHolidayDb();

        $holiday_db->delete($uid);
    }

    /**
     * Gets the request parameter.
     *
     * @param      string $key The query parameter
     * @param      string $default The default value to return if not found
     *
     * @return     string  The request parameter.
     */
    function get_request_parameter($key, $default = '')
    {
        // If not request set
        if (!isset($_REQUEST[$key]) || empty($_REQUEST[$key])) {
            return $default;
        }

        // Set so process it
        return strip_tags((string)wp_unslash($_REQUEST[$key]));
    }

    /**
     * Converts a given UTC string to a string that is using the default wordpress timezone.
     *
     * @param $utc_string_date
     * @param string $format
     * @return     string  The request parameter.
     */
    function convertUtcStringToLocalTimezoneString($utc_string_date, $format = 'd.m.Y H:i')
    {
        $utc_timezone = new DateTimeZone("UTC");
        $wordpress_timezone = get_option('timezone_string');

        $local_timezone = new DateTimeZone("$wordpress_timezone");
        $date = new DateTime($utc_string_date, $utc_timezone);
        $date->setTimezone($local_timezone);
        return $date->format($format);
    }
}