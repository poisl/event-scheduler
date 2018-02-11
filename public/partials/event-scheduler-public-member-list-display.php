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

<?php if (count($members) > 0): ?>
    <table>
        <?php foreach ($members as $member_id) {
            $user = get_userdata($member_id->ID);
            echo "<tr>";
            echo "<h4>" . $user->first_name . " " . $user->last_name . "</h4>";
            echo "<span>" . _e('Email','event-scheduler') . ": <a href=" . $user->user_email . ">" . $user->user_email . "</a></span><br>";
            if ($user->birthday) {
                echo "<span>" . _e('Birthday','event-scheduler') . ": " . DateTime::createFromFormat('Y-m-d', $user->birthday)->format('d.m.Y') . "</span><br>";
            }
            if ($user->phone1) {
                echo "<span>" . _e('Phone','event-scheduler') . ": " . $user->phone1 . "</span>";
            }
            echo "</tr>";
        }
        ?>
    </table>
<?php endif; ?>
<?php if (count($members) == 0): ?>
    <h4><?php _e('No active members exist.', 'event-scheduler');?></h4>
<?php endif; ?>

<?php else : ?>
    <div class="attention"><?php _e('Event Scheduler can only be used by active members.', 'event-scheduler');?></div>
<?php endif; ?>