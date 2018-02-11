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

    <?php if ($statistics): ?>
        <table>
            <tr>
                <td><?php _e('Year', $this->plugin_name); ?></td>
                <td><?php _e('Events', $this->plugin_name); ?></td>
                <td><?php _e('Canceled events', $this->plugin_name); ?></td>
                <td><?php _e('Average accepts', $this->plugin_name); ?></td>
                <td><?php _e('Minimal accepts', $this->plugin_name); ?></td>
                <td><?php _e('Maximal accepts', $this->plugin_name); ?></td>
                <td><?php _e('Average declines', $this->plugin_name); ?></td>
                <td><?php _e('Minimal declines', $this->plugin_name); ?></td>
                <td><?php _e('Maximal declines', $this->plugin_name); ?></td>
                <td><?php _e('Top participants', $this->plugin_name); ?></td>
            </tr>

            <?php if (count($statistics) > 0): ?>
                <?php foreach ($statistics as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td style='color: green'>" . $row['activeEvents'] . "</td>";
                    echo "<td style='color:red'>" . $row['inactiveEvents'] . "</td>";
                    echo "<td style='color: green'>" . $row['averageAccepts'] . "</td>";
                    echo "<td style='color: green'>" . $row['minimumAccepts'] . "</td>";
                    echo "<td style='color: green'>" . $row['maximumAccepts'] . "</td>";
                    echo "<td style='color:red'>" . $row['averageCancels'] . "</td>";
                    echo "<td style='color:red'>" . $row['minimumCancels'] . "</td>";
                    echo "<td style='color:red'>" . $row['maximumCancels'] . "</td>";
                    echo "<td style='font-size:8px'>";
                    if ($row['tops']) {
                        foreach ($row['tops'] as $top) {
                            $user_info = get_userdata($top['participantId']);
                            echo $user_info->first_name . " " . $user_info->last_name . " (" . $top['eventsAccepted'] . ") <br>";
                        }
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            <?php endif; ?>
        </table>

    <?php elseif (!$statistics) : ?>
        <?php _e('No events existing.', $this->plugin_name); ?>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.', $this->plugin_name); ?></div>
<?php endif; ?>

