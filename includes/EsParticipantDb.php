<?php

class EsParticipantDb extends EsDb
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

        $this->table_name = $wpdb->prefix . 'event_scheduler_participant';
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
            'event_id' => '',
            'participant_user_id' => '',
            'time' => '',
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
        return $this->get_participants($args, true);
    }

    /**
     * Retrieve participants from the database
     *
     * @access  public
     * @since   1.0
     * @param   array $args
     * @param   bool $count Return only the total number of results found (optional)
     * @return array|bool|int|mixed|null|object
     */
    public function get_participants($args = array(), $count = false)
    {

        global $wpdb;

        $defaults = array(
            'number' => 500,
            'offset' => 0,
            'orderby' => 'time',
            'order' => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);

        if ($args['number'] < 1) {
            $args['number'] = 999999999999;
        }

        $where = '';

        // specific referrals
        if (!empty($args['uid'])) {

            if (is_array($args['uid'])) {
                $participant_ids = implode(',', $args['uid']);
            } else {
                $participant_ids = intval($args['uid']);
            }

            $where .= "WHERE `uid` IN( {$participant_ids} ) ";

        }

        if (!empty($args['event_id'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['event_id'])) {
                $where .= " `event_id` IN('" . implode("','", $args['event_id']) . "') ";
            } else {
                $where .= " `event_id` = '" . $args['event_id'] . "' ";
            }

        }

        if (isset($args['accept'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['accept'])) {
                $where .= " `accept` IN('" . implode("','", $args['accept']) . "') ";
            } else {
                $where .= " `accept` = '" . $args['accept'] . "' ";
            }

        }

        if (!empty($args['participant_user_id'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['active'])) {
                $where .= " `participant_user_id` IN('" . implode("','", $args['participant_user_id']) . "') ";
            } else {
                $where .= " `participant_user_id` = '" . $args['participant_user_id'] . "' ";
            }

        }

        if (!empty($args['time'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }
            $where .= " `time` = '" . date_format($args['time'], ('Y-m-d H:i:s')) . "' ";
        }

        $args['orderby'] = !array_key_exists($args['orderby'], $this->get_columns()) ? $this->primary_key : $args['orderby'];

        if ('total' === $args['orderby']) {
            $args['orderby'] = 'total+0';
        } else if ('subtotal' === $args['orderby']) {
            $args['orderby'] = 'subtotal+0';
        }

        $cache_key = (true === $count) ? md5('es_participants_count' . serialize($args)) : md5('es_participants_' . serialize($args));

        $results = wp_cache_get($cache_key, 'participants');

        if (false === $results) {

            if (true === $count) {

                $results = absint($wpdb->get_var("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};"));

            } else {

                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );

            }

            wp_cache_set($cache_key, $results, 'participants', 3600);

        }

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
            'event_id' => '%d',
            'accept' => '%d',
            'participant_user_id' => '%d',
            'time' => '%s',
        );
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

        $sql = "CREATE TABLE " . $this->table_name . "   (
        uid int(11) NOT NULL auto_increment,
	    event_id int(11) unsigned DEFAULT '0' NOT NULL,
	    accept tinyint(1) unsigned DEFAULT '0' NOT NULL,
	    participant_user_id int(11) unsigned DEFAULT '0',
	    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (uid)
        ) ";

        dbDelta($sql);

        //update_option( $this->table_name . '_db_version', $this->version );
    }
}