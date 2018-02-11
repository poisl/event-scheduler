<table>
    <tr>
        <td><a href="<?php echo $this->activateEventUrl($offset); ?>"><?php _e('Activate event', 'event_scheduler');?></a></td>
        <?php if ($alternateLocation): ?>
        <td><a href="<?php echo $this->changeEventLocationUrl($offset); ?>"><?php _e('Change location', 'event_scheduler');?></a></td>
        <?php endif; ?>
        <td><a href="<?php echo $this->deactivateEventUrl($offset); ?>"><?php _e('Deactivate event', 'event_scheduler');?></a></td>
    </tr>
</table>