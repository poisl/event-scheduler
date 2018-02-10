<?php if (is_user_logged_in()) : ?>

    <?php if (count($holidays) > 0): ?>
        <table class="table1">
            <tr>
                <th><?php _e('Beschreibung', 'event_scheduler');?></th>
                <th><?php _e('Anfang','event_scheduler');?></th>
                <th><?php _e('Ende','event_scheduler');?></th>
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
        <h4><?php _e('Keine künftigen Ferien eingetragen.','event_scheduler');?></h4>
    <?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Der Planer kann nur von angemeldeten Vereinsmitgliedern genutzt werden.','event_scheduler');?></div>
<?php endif; ?>
