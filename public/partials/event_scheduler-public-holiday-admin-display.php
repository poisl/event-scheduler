<?php if (is_user_logged_in()) : ?>

    <h4><?php _e('Neue Ferien anlegen', 'event_scheduler');?></h4>
    <form method="get">
        <?php _e('Beschreibung:', 'event_scheduler');?> <input type="text" name="description" autofocus>
        <?php _e('Start:', 'event_scheduler');?> <input type="date" name="start">
        <?php _e('Ende:','event_scheduler');?> <input type="date" name="end"><br>
        <button type="submit" name="action" value="create"><?php _e('Anlegen', 'event_scheduler');?></button>
    </form>

    <?php if (count($holidays) > 0): ?>
        <table>
            <tr>
                <th><?php _e('Beschreibung', 'event_scheduler');?></th>
                <th><?php _e('Anfang', 'event_scheduler');?></th>
                <th><?php _e('Ende','event_scheduler');?></th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach ($holidays as $row) {
                echo "<tr>";
                echo "<form method=\"get\">";
                echo "<input type=\"hidden\" name=\"uid\" value=\"" . $row->uid . "\">";
                echo "<td><input type=\"text\" name=\"description\" value=\"" . $row->description . "\" style=\"min-width:160px;\"></td>";
                echo "<td><input type=\"date\" name=\"start\" value=\"" . $row->start . "\" style=\"max-width:175px;\"></td>";
                echo "<td><input type=\"date\" name=\"end\" value=\"" . $row->end . "\" style=\"max-width:175px;\"></td>";
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
        <h4><?php _e('Keine künftigen Ferien eingetragen.','event_scheduler');?></h4>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Der Planer kann nur von angemeldeten Vereinsmitgliedern genutzt werden.','event_scheduler');?></div>
<?php endif; ?>