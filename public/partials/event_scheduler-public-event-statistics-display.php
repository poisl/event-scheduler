<?php if (is_user_logged_in()) : ?>

    <table>
        <tr>
            <td><?php _e('Year','event_scheduler');?></td>
            <td><?php _e('Events','event_scheduler');?></td>
            <td><?php _e('Canceled events','event_scheduler');?></td>
            <td><?php _e('Average accepts','event_scheduler');?></td>
            <td><?php _e('Minimal accepts','event_scheduler');?></td>
            <td><?php _e('Maximal accepts','event_scheduler');?></td>
            <td><?php _e('Average declines','event_scheduler');?></td>
            <td><?php _e('Minimal declines','event_scheduler');?></td>
            <td><?php _e('Maximal declines','event_scheduler');?></td>
            <td><?php _e('Top participants','event_scheduler');?></td>
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
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.', 'event_scheduler');?></div>
<?php endif; ?>

