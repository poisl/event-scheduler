<?php

class EsHolidayDb extends EsDb
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

        $this->table_name = $wpdb->prefix . 'event_scheduler_holiday';
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
            'description' => '',
            'start' => '0000-00-00',
            'end' => '0000-00-00',
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
        return $this->get_holidays($args, true);
    }

    /**
     * Retrieve holidays from the database
     *
     * @access  public
     * @since   1.0
     * @param   array $args
     * @param   bool $count Return only the total number of results found (optional)
     * @return array|bool|int|mixed|null|object
     */
    public function get_holidays($args = array(), $count = false)
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
                $participant_ids = implode(',', $args['uid']);
            } else {
                $participant_ids = intval($args['uid']);
            }

            $where .= "WHERE `uid` IN( {$participant_ids} ) ";

        }

        if (!empty($args['description'])) {

            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }

            if (is_array($args['description'])) {
                $where .= " `description` IN(" . implode(',', $args['description']) . ") ";
            } else {
                if (!empty($args['search'])) {
                    $where .= " `description` LIKE '%%" . $args['description'] . "%%' ";
                } else {
                    $where .= " `description` = '" . $args['description'] . "' ";
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

        if (!empty($args['date_in_holiday'])) {
            if (empty($where)) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }
            $where .= " `start` <= '" . date_format($args['date_in_holiday'], ('Y-m-d H:i:s')) . "' ";
            $where .= "AND `end` >= '" . date_format($args['date_in_holiday'], ('Y-m-d H:i:s')) . "' ";
        }

        $args['orderby'] = !array_key_exists($args['orderby'], $this->get_columns()) ? $this->primary_key : $args['orderby'];

        if ('total' === $args['orderby']) {
            $args['orderby'] = 'total+0';
        } else if ('subtotal' === $args['orderby']) {
            $args['orderby'] = 'subtotal+0';
        }

        $cache_key = (true === $count) ? md5('es_holidays_count' . serialize($args)) : md5('es_holidays_' . serialize($args));

        $results = wp_cache_get($cache_key, 'holidays');

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

            wp_cache_set($cache_key, $results, 'holidays', 3600);

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
            'description' => '%s',
            'start' => '%s',
            'end' => '%s',
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
	    description varchar(255) DEFAULT '' NOT NULL,
	    start date DEFAULT '0000-00-00',
	    end date DEFAULT '0000-00-00',
        PRIMARY KEY  (uid)
        ) ";

        dbDelta($sql);

        //update_option( $this->table_name . '_db_version', $this->version );
    }
}