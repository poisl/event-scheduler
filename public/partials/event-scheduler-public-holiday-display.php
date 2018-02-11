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

    <?php if (count($holidays) > 0): ?>
        <table class="table1">
            <tr>
                <th><?php _e('Description', $this->plugin_name);?></th>
                <th><?php _e('Begin',$this->plugin_name);?></th>
                <th><?php _e('End',$this->plugin_name);?></th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach ($holidays as $row) {
                echo "<tr>";
                echo "<td>" . $row->description . "</td>";
                echo "<td>" . DateTime::createFromFormat('Y-m-d', $row->start)->format('d.m.Y') . "</td>";
                echo "<td>" . DateTime::createFromFormat('Y-m-d', $row->end)->format('d.m.Y') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>
    <?php if (count($holidays) == 0): ?>
        <h4><?php _e('No upcoming holidays exist.',$this->plugin_name);?></h4>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.',$this->plugin_name);?></div>
<?php endif; ?>
