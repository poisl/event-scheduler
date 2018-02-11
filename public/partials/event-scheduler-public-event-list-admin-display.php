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

<table>
    <tr>
        <td>
            <a href="<?php echo $this->activateEventUrl($offset); ?>"><?php _e('Activate event', $this->plugin_name); ?></a>
        </td>
        <?php if ($alternateLocation): ?>
            <td>
                <a href="<?php echo $this->changeEventLocationUrl($offset); ?>"><?php _e('Change location', $this->plugin_name); ?></a>
            </td>
        <?php endif; ?>
        <td>
            <a href="<?php echo $this->deactivateEventUrl($offset); ?>"><?php _e('Deactivate event', $this->plugin_name); ?></a>
        </td>
    </tr>
</table>