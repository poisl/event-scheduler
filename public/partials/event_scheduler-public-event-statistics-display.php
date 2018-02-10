<?php if (is_user_logged_in()) : ?>

    <table>
        <tr>
            <td><?php _e('Jahr','event_scheduler');?></td>
            <td><?php _e('Spieltage','event_scheduler');?></td>
            <td><?php _e('Abgesagte Spieltermine','event_scheduler');?></td>
            <td><?php _e('Zusagen durchschnittlich','event_scheduler');?></td>
            <td><?php _e('Zusagen minimal','event_scheduler');?></td>
            <td><?php _e('Zusagen maximal','event_scheduler');?></td>
            <td><?php _e('Absagen durchschnittlich','event_scheduler');?></td>
            <td><?php _e('Absagen minimal','event_scheduler');?></td>
            <td><?php _e('Absagen maximal','event_scheduler');?></td>
            <td><?php _e('Meiste Zusagen','event_scheduler');?></td>
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
                foreach ($row['tops'] as $top) {
                    $user_info = get_userdata($top['participantId']);
                    echo $user_info->first_name . " " . $user_info->last_name . " (" . $top['eventsAccepted'] . ") <br>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        <?php endif; ?>
    </table>

<?php else : ?>
    <div class="attention"><?php _e('Der Planer kann nur von angemeldeten Vereinsmitgliedern genutzt werden.', 'event_scheduler');?></div>
<?php endif; ?>

