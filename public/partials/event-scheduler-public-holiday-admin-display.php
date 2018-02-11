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

    <h4><?php _e('Create new holiday', $this->plugin_name); ?></h4>
    <form method="get">
        <?php _e('Description', $this->plugin_name); ?>: <input type="text" name="description" required autofocus>
        <?php _e('Begin', $this->plugin_name); ?>: <input type="date" name="start" required>
        <?php _e('End', $this->plugin_name); ?>: <input type="date" name="end" required><br>
        <button type="submit" name="action" value="create"><?php _e('Create', $this->plugin_name); ?></button>
    </form>

    <?php if (count($holidays) > 0): ?>
        <table>
            <tr>
                <th><?php _e('Description', $this->plugin_name); ?></th>
                <th><?php _e('Begin', $this->plugin_name); ?></th>
                <th><?php _e('End', $this->plugin_name); ?></th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach ($holidays as $row) {
                echo "<tr>";
                echo "<form method=\"get\">";
                echo "<input type=\"hidden\" name=\"uid\" value=\"" . $row->uid . "\">";
                echo "<td><input type=\"text\" name=\"description\" value=\"" . $row->description . "\" style=\"min-width:160px;\" required></td>";
                echo "<td><input type=\"date\" name=\"start\" value=\"" . $row->start . "\" style=\"max-width:175px;\" required></td>";
                echo "<td><input type=\"date\" name=\"end\" value=\"" . $row->end . "\" style=\"max-width:175px;\" required></td>";
                echo "<td><button class=\"edit\" type=\"submit\" name=\"action\" value=\"update\" style=\"padding-top: 12px; padding-left: 15px; padding-right: 15px; padding-bottom: 8px\">";
                echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/pencil.svg');
                echo "</button></td>";
                echo "<td><button class=\"trash\" type=\"submit\" name=\"action\" value=\"delete\" style=\"padding-top: 12px; padding-left: 15px; padding-right: 15px; padding-bottom: 8px\">";
                echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/trash.svg');
                echo "</button></td>";
                echo "</form>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>
    <?php if (count($holidays) == 0): ?>
        <h4><?php _e('No upcoming holidays exist.', $this->plugin_name); ?></h4>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.', $this->plugin_name); ?></div>
<?php endif; ?>