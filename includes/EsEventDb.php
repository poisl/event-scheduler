<?php

class EsEventDb extends EsDb
{

    /**
     * Get things started
     *
     * @access  public
     * @since   1.0
     */
    public function __construct()
    {

        global $wpdb;

        $this->table_name = $wpdb->prefix . 'event_scheduler_event';
        $this->primary_key = 'uid';
        $this->version = '1.0';

    }

    /**
     * Get default column values
     *
     * @access  public
     * @since   1.0
     */
    public function get_column_defaults()
    {
        return array(
            'start' => '0000-00-00 00:00:00',
            'end' => '0000-00-00 00:00:00',
            'location' => '',
            'inactive_reason' => '',
            'participants' => 0,
        );
    }

    /**
     * Return the number of results found for a given query
     *
     * @param  array $args
     * @return int
     */
    public function count($args = array())
    {
        return $this->get_events($args, true);
    }

    /**
     * Retrieve events from the database
     *
     * @access  public
     * @since   1.0
     * @param   array $args
     * @param   bool $count Return only the total number of results found (optional)
     * @return array|bool|int|mixed|null|object
     */
    public function get_events($args = array(), $count = false)
    {

        global $wpdb;

        $defaults = array(
            'number' => 200,
            'offset' => 0,
            'orderby' => 'start',
            'order' => 'ASC',
        );

        $args = wp_parse_args($args, $defaults);

        if ($args['number'] < 1) {
            $args['number'] = 999999999999;
        }

        $where = '';

        // specific referrals
        if (!empty($args['uid'])) {

            if (is_array($args['uid'])) {
                $event_ids = implode(',', $args['uid']);
            } else {
                $event_ids = intval($args['uid']);
            }

            $where .= "WHERE `uid` IN( {$event_ids} ) ";

        }

        if (isset($args['active'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['active'])) {
                $where .= " `active` IN('" . implode("','", $args['active']) . "') ";
            } else {
                $where .= " `active` = '" . $args['active'] . "' ";
            }

        }

        if (!empty($args['location'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['location'])) {
                $where .= " `location` IN(" . implode(',', $args['location']) . ") ";
            } else {
                if (!empty($args['search'])) {
                    $where .= " `location` LIKE '%%" . $args['location'] . "%%' ";
                } else {
                    $where .= " `location` = '" . $args['location'] . "' ";
                }
            }

        }

        if (!empty($args['inactive_reason'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['inactive_reason'])) {
                $where .= " `inactive_reason` IN(" . implode(',', $args['inactive_reason']) . ") ";
            } else {
                if (!empty($args['search'])) {
                    $where .= " `inactive_reason` LIKE '%%" . $args['inactive_reason'] . "%%' ";
                } else {
                    $where .= " `inactive_reason` = '" . $args['inactive_reason'] . "' ";
                }
            }

        }

        if (!empty($args['start'])) {
            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }
            if (is_array($args['start'])) {
                if (!empty($args['start']['start'])) {
                    $where .= " `start` >= '" . date_format($args['start']['start'], ('Y-m-d H:i:s')) . "' ";
                }

                if (!empty($args['start']['end'])) {
                    if (!empty($where)) {
                        $where .= " AND";
                    }
                    $where .= " `end` <= '" . date_format($args['start']['end'], ('Y-m-d H:i:s')) . "' ";
                }

            } else {
                $where .= " `start` = '" . date_format($args['start'], ('Y-m-d H:i:s')) . "' ";
            }

        }

        if (!empty($args['end'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }
            $where .= " `end` = '" . date_format($args['end'], ('Y-m-d H:i:s')) . "' ";
        }

        $args['orderby'] = !array_key_exists($args['orderby'], $this->get_columns()) ? $this->primary_key : $args['orderby'];

        if ('total' === $args['orderby']) {
            $args['orderby'] = 'total+0';
        } else if ('subtotal' === $args['orderby']) {
            $args['orderby'] = 'subtotal+0';
        }

        //$cache_key = (true === $count) ? md5('es_events_count' . serialize($args)) : md5('es_events_' . serialize($args));

        //$results = wp_cache_get($cache_key, 'events');

        //if (false === $results) {

          //  if (true === $count) {

            //    $results = absint($wpdb->get_var("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};"));

           // } else {

                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );

            //}

            //wp_cache_set($cache_key, $results, 'events', 3600);

        //}
        return $results;

    }

    /**
     * Get columns and formats
     *
     * @access  public
     * @since   1.0
     */
    public function get_columns()
    {
        return array(
            'uid' => '%d',
            'start' => '%s',
            'end' => '%s',
            'location' => '%s',
            'active' => '%d',
            'inactive_reason' => '%s',
            'participants' => '%d',
        );
    }

    /**
     * Average of accepts or cancels per active or inactive event for a given year
     *
     * @access  public
     * @since   1.0
     * @param $year
     * @param $accept
     * @return array|bool|int|mixed|null|object
     */
    public function calculateAverageAcceptsPerEventByYearFromDb($year, $accept)
    {
        global $wpdb;

        $args = array(
            'number' => 500,
            'offset' => 0,
        );

        $participant_table_name = $wpdb->prefix . 'event_scheduler_participant';

        $start_of_year = ($year . '-01-01 00:00:00');
        $end_of_year = ($year . '-12-31 23:59:59');

        if ($year == date('Y')) {
            $end_of_year = date("Y-m-d H:i:s");;
        }

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "
    			SELECT
    				CAST(AVG(accepted) as decimal(2,1)) as average,
    				MIN(accepted) as minimum, 
    				MAX(accepted) as maximum
    			FROM (
    				SELECT count(s.participant_user_id) AS accepted, s.event_id
    				FROM {$participant_table_name} s, {$this->table_name} e
    				WHERE e.active=1
    				AND e.start >= '$start_of_year'
    				AND e.start <= '$end_of_year'
    			    AND s.event_id=e.uid
    				AND s.accept='$accept' GROUP BY s.event_id
    			)
    			AS z
    			",
                absint($args['offset']),
                absint($args['number'])));
        return $results;
    }

    /**
     * Top 3 participants of a given year
     *
     * @access  public
     * @since   1.0
     * @param $year
     * @return array|bool|int|mixed|null|object
     */
    public function topParticipantsByYearFromDb($year)
    {
        global $wpdb;

        $args = array(
            'number' => 500,
            'offset' => 0,
        );

        $participant_table_name = $wpdb->prefix . 'event_scheduler_participant';

        $start_of_year = ($year . '-01-01 00:00:00');
        $end_of_year = ($year . '-12-31 23:59:59');

        if ($year == date('Y')) {
            $end_of_year = date("Y-m-d H:i:s");;
        }

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "
    			SELECT s.participant_user_id AS participantId, count(s.event_id) AS eventsAccepted
    				FROM {$participant_table_name} s, {$this->table_name} e
    			WHERE e.start >= '$start_of_year'
    				AND e.start <= '$end_of_year'
    				AND s.event_id=e.uid
    				AND s.accept = '1'
    				AND e.active = '1'
    			GROUP BY s.participant_user_id ORDER BY eventsAccepted
    			DESC LIMIT 3
    			",
                absint($args['offset']),
                absint($args['number'])));
        return $results;
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   1.0
     */
    public function create_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE " . $this->table_name . " (
        uid int(11) NOT NULL auto_increment,
	    start datetime DEFAULT '0000-00-00 00:00:00',
	    end datetime DEFAULT '0000-00-00 00:00:00',
	    location varchar(255) DEFAULT '' NOT NULL,
	    active tinyint(1) unsigned DEFAULT '0' NOT NULL,
	    inactive_reason varchar(255) DEFAULT '' NOT NULL,
	    participants int(11) unsigned DEFAULT '0' NOT NULL,
        PRIMARY KEY  (uid)
		)";

        dbDelta($sql);

        //update_option( $this->table_name . '_db_version', $this->version );
    }
}