<?php if (is_user_logged_in()) : ?>

<?php if (count($members) > 0): ?>
    <table>
        <?php foreach ($members as $member_id) {
            $user = get_userdata($member_id->ID);
            echo "<tr>";
            echo "<h4>" . $user->first_name . " " . $user->last_name . "</h4>";
            echo "<span>" . _e('E-Mail:','event_scheduler') . " <a href=" . $user->user_email . ">" . $user->user_email . "</a></span><br>";
            if ($user->birthday) {
                echo "<span>" . _e('Geburtstag:','event_scheduler') . " " . DateTime::createFromFormat('Y-m-d', $user->birthday)->format('d.m.Y') . "</span><br>";
            }
            if ($user->phone1) {
                echo "<span>" . _e('Telefon:','event_scheduler') . " " . $user->phone1 . "</span>";
            }
            echo "</tr>";
        }
        ?>
    </table>
<?php endif; ?>
<?php if (count($members) == 0): ?>
    <h4><?php _e('Keine Mitglieder vorhanden.', 'event_scheduler');?></h4>
<?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Der Planer kann nur von angemeldeten Vereinsmitgliedern genutzt werden.', 'event_scheduler');?></div>
<?php endif; ?>