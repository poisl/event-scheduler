<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_Scheduler
 * @subpackage Event_Scheduler/public/partials
 */
?>

<?php if (is_user_logged_in()) : ?>

    <?php if ($events[0]->active): ?>
        <h4 style="color: green"><?php _e('Event is scheduled for', $this->plugin_name); ?>
            <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('d.m.Y H:i'); ?>
            <?php _e('at', $this->plugin_name); ?> <?php echo $events[0]->location; ?>.</h4>
    <?php endif; ?>
    <?php if (!$events[0]->active): ?>
        <h4 style="color: red"><?php _e('Event on', $this->plugin_name); ?>
            <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('d.m.Y H:i'); ?>
            <?php _e('is canceled because', $this->plugin_name); ?>: <?php echo $events[0]->inactive_reason; ?></h4>
    <?php endif; ?>

    <?php if ($offset >= 0): ?>
        <table>
            <tr>
                <td><a href="<?php echo $this->acceptEventUrl($offset); ?>">
                        <button><?php echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/check.svg'); ?>
                            <?php _e('Accept', $this->plugin_name); ?>
                        </button>
                    </a></td>
                <td><a href="<?php echo $this->declineEventUrl($offset); ?>">
                        <button><?php echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/times.svg'); ?>
                            <?php _e('Decline', $this->plugin_name); ?>
                        </button>
                    </a></td>
            </tr>
        </table>
    <?php endif; ?>

    <table width=40%>
        <tr>
            <td>
                <a href="<?php echo $this->lastEventUrl($offset); ?>"><< <?php _e('Last event', $this->plugin_name); ?></a>
            </td>
            <td><a href="<?php echo $this->currentEventUrl(); ?>"><?php _e('Current event', $this->plugin_name); ?></a>
            </td>
            <td><a href="<?php echo $this->nextEventUrl($offset); ?>"><?php _e('Next event', $this->plugin_name); ?>
                    >></a></td>
        </tr>
    </table>

    <?php if (current_user_can('manage_options')) {
        include_once('event-scheduler-public-event-list-admin-display.php');
    } ?>

    <?php if (count($joiningUsers) > 0): ?>
        <h4 style="color: green"><?php echo count($joiningUsers); ?> <?php _e('Participants accepted', $this->plugin_name); ?>
            :</h4>

        <table>
            <?php foreach ($joiningUsers as $row) {
                echo "<tr>";
                $user_info = get_userdata($row->participant_user_id);
                echo "<td>" . $user_info->first_name . " " . $user_info->last_name . ", <a href=\"mailto:" . $user_info->user_email . "\">" . $user_info->user_email . "</a></td>";
                echo "<td>(" . $div->convertUtcStringToLocalTimezoneString($row->time) . ")</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>

    <?php if (count($decliningUsers) > 0): ?>
        <h4 style="color: red"><?php echo count($decliningUsers); ?> <?php _e('Participants declined', $this->plugin_name); ?>
            :</h4>

        <table>
            <?php foreach ($decliningUsers as $row) {
                echo "<tr>";
                $user_info = get_userdata($row->participant_user_id);
                echo "<td>" . $user_info->first_name . " " . $user_info->last_name . ", <a href=\"mailto:" . $user_info->user_email . "\">" . $user_info->user_email . "</a></td>";
                echo "<td>(" . $div->convertUtcStringToLocalTimezoneString($row->time) . ")</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>

    <?php if (count($findUndecidedParticipants) > 0): ?>
        <h4><?php echo count($findUndecidedParticipants); ?> <?php _e('Participation unknown', $this->plugin_name); ?>
            :</h4>

        <table>
            <?php foreach ($findUndecidedParticipants as $row) {
                echo "<tr>";
                $user_info = get_userdata($row->ID);
                echo "<td>" . $user_info->first_name . " " . $user_info->last_name . ", <a href=\"mailto:" . $user_info->user_email . "\">" . $user_info->user_email . "</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.', $this->plugin_name); ?></div>
<?php endif; ?>