<table>
    <tr>
        <td><a href="<?php echo $this->activateEventUrl($offset); ?>"><?php _e('Event aktivieren', 'event_scheduler');?></a></td>
        <?php if ($alternateLocation): ?>
        <td><a href="<?php echo $this->changeEventLocationUrl($offset); ?>"><?php _e('Ort ändern', 'event_scheduler');?></a></td>
        <?php endif; ?>
        <td><a href="<?php echo $this->deactivateEventUrl($offset); ?>"><?php _e('Event deaktivieren', 'event_scheduler');?></a></td>
    </tr>
</table>