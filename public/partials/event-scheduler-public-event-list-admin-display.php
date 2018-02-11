<table>
    <tr>
        <td><a href="<?php echo $this->activateEventUrl($offset); ?>"><?php _e('Activate event', 'event-scheduler');?></a></td>
        <?php if ($alternateLocation): ?>
        <td><a href="<?php echo $this->changeEventLocationUrl($offset); ?>"><?php _e('Change location', 'event-scheduler');?></a></td>
        <?php endif; ?>
        <td><a href="<?php echo $this->deactivateEventUrl($offset); ?>"><?php _e('Deactivate event', 'event-scheduler');?></a></td>
    </tr>
</table>