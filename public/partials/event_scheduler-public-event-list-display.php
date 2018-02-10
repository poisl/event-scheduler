<?php if (is_user_logged_in()) : ?>

    <?php if ($events[0]->active): ?>
        <h4 style="color: green"><?php _e('Der Event findet statt,
            am', 'event_scheduler');?> <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('d.m.Y H:i'); ?>
            <?php _e('im', 'event_scheduler');?> <?php echo $events[0]->location; ?>.</h4>
    <?php endif; ?>
    <?php if (!$events[0]->active): ?>
        <h4 style="color: red"><?php _e('Der Event
            am', 'event_scheduler');?> <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $events[0]->start)->format('d.m.Y H:i'); ?> <?php _e('wurde
            aus
            folgendem Grund abgesagt:', 'event_scheduler');?> <?php echo $events[0]->inactive_reason; ?></h4>
    <?php endif; ?>

    <?php if ($offset >= 0): ?>
        <table>
            <tr>
                <td><a href="<?php echo $this->acceptEventUrl($offset); ?>">
                        <button><?php echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/check.svg'); ?>
                            <?php _e('Zusagen', 'event_scheduler');?>
                        </button>
                    </a></td>
                <td><a href="<?php echo $this->declineEventUrl($offset); ?>">
                        <button><?php echo file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'partials/times.svg'); ?>
                            <?php _e('Absagen', 'event_scheduler');?>
                        </button>
                    </a></td>
            </tr>
        </table>
    <?php endif; ?>

    <table width=40%>
        <tr>
            <td><a href="<?php echo $this->lastEventUrl($offset); ?>"><< <?php _e('Event zurück', 'event_scheduler');?></a></td>
            <td><a href="<?php echo $this->currentEventUrl(); ?>"><?php _e('Aktueller Event', 'event_scheduler');?></a></td>
            <td><a href="<?php echo $this->nextEventUrl($offset); ?>"><?php _e('Nächster Event', 'event_scheduler');?> >></a></td>
        </tr>
    </table>

    <?php if (current_user_can('manage_options')) {
        include_once('event_scheduler-public-event-list-admin-display.php');
    } ?>

    <?php if (count($joiningUsers) > 0): ?>
        <h4 style="color: green"><?php echo count($joiningUsers); ?> <?php _e('Teilnehmer die zugesagt haben:', 'event_scheduler');?></h4>

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
        <h4 style="color: red"><?php echo count($decliningUsers); ?> <?php _e('Teilnehmer die abgesagt haben:','event_scheduler');?></h4>

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
        <h4><?php echo count($findUndecidedParticipants); ?> <?php _e('Teilnahme unklar:','event_scheduler');?></h4>

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
    <div class="attention"><?php _e('Der Planer kann nur von angemeldeten Vereinsmitgliedern genutzt werden.','event_scheduler');?></div>
<?php endif; ?>