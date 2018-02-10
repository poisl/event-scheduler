<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="cleanup_options" action="options.php">

        <?php
        //Grab all options
        $options = get_option($this->plugin_name);

        // Cleanup
        $event_start_date = $options['event_start_date'];
        $event_start_time = $options['event_start_time'];
        $event_end_time = $options['event_end_time'];
        $event_repeat_interval = $options['event_repeat_interval'];
        $event_default_location = $options['event_default_location'];
        $event_alternate_location = $options['event_alternate_location'];
        $event_notification_url = $options['event_notification_url'];
        $event_notification_subject = $options['event_notification_subject'];
        $event_notification_body = $options['event_notification_body'];
        ?>

        <?php settings_fields($this->plugin_name); ?>

        <!-- Start date of the event -->
        <p><?php _e('Event Startdatum (Y-m-d), z. B. 2016-01-15','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_date"
               name="<?php echo $this->plugin_name; ?>[event_start_date]" value="<?php if(!empty($event_start_date)) echo $event_start_date; ?>"/>

        <!-- Start time of the event -->
        <p><?php _e('Event Startzeit (H:M), z. B. 20:00','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_time"
               name="<?php echo $this->plugin_name; ?>[event_start_time]" value="<?php if(!empty($event_start_time)) echo $event_start_time; ?>"/>

        <!-- End time of the event -->
        <p><?php _e('Event Endzeit (H:M), z. B. 22:00','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_end_time"
               name="<?php echo $this->plugin_name; ?>[event_end_time]" value="<?php if(!empty($event_end_time)) echo $event_end_time; ?>"/>

        <!-- Event repeat interval -->
        <p><?php _e('Der Event wird in der hier angegebenen Zeit in Wochen wiederholt, z. B. 1','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_repeat_interval"
               name="<?php echo $this->plugin_name; ?>[event_repeat_interval]" value="<?php if(!empty($event_repeat_interval)) echo $event_repeat_interval; ?>"/>

        <!-- Default event location -->
        <p><?php _e('Event Standort, z. B. Turnhalle','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_default_location"
               name="<?php echo $this->plugin_name; ?>[event_default_location]" value="<?php if(!empty($event_default_location)) echo $event_default_location; ?>"/>

        <!-- Alternative event location -->
        <p><?php _e('(Optional) Alternativer Event Standort, z. B. Sportplatz','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_alternate_location"
               name="<?php echo $this->plugin_name; ?>[event_alternate_location]" value="<?php if(!empty($event_alternate_location)) echo $event_alternate_location; ?>"/>

        <!-- Full URL to the page where the event list is put on -->
        <p><?php _E('URL zur Seite mit dem Eventplaner mit Slash am Ende','event_scheduler');?><br>
            <small><?php _e('Alle Einstellungen ab hier werden für die Mailbenachrichtigung benötigt. Mit der Cron Aktion "event_scheduler_mail_notification" kann der Mailversand automatisiert werden.', 'event_scheduler');?></small></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_notification_url"
               name="<?php echo $this->plugin_name; ?>[event_notification_url]" value="<?php if(!empty($event_notification_url)) echo $event_notification_url; ?>"/>

        <!-- Notification mail subject -->
        <p><?php _e('Betreff für die Mailbenachrichtigung, z. B. Event Benachrichtigung','event_scheduler');?></p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_notification_subject"
               name="<?php echo $this->plugin_name; ?>[event_notification_subject]" value="<?php if(!empty($event_notification_subject)) echo $event_notification_subject; ?>"/>

        <!-- Notification mail body -->
        <p><?php _e('Text für die Mailbenachrichtigung','event_scheduler');?>
        <br>
            <small><strong><?php _e('HTML-Tags und die folgenden dynamischen Variablen können verwendet werden:','event_scheduler');?> ###name###, ###eventdate###, ###eventtime###, ###eventlocation###, ###acceptlink###, ###cancellink###</strong></small></p>
        <textarea class="large-text code" id="<?php echo $this->plugin_name; ?>event_notification_body"
                  name="<?php echo $this->plugin_name; ?>[event_notification_body]" rows="6" cols="20"><?php if(!empty($event_notification_body)) echo $event_notification_body; ?></textarea>

        <?php submit_button(__('Änderungen übernehmen','event_scheduler'), 'primary', 'submit', TRUE); ?>

        <!-- Plugin shortcodes -->
        <p><?php _e('Die folgenden Shortcodes können auf den Seiten verwendet werden:','event_scheduler');?>
            <br>
            <small><strong>[event_scheduler_event_list] - <?php _e('Zeigt den aktuellen Event und ermöglicht es, dass Webseitenbenutzer zu- oder absagen können','event_scheduler');?><br>
                    [event_scheduler_event_statistics] - <?php _e('Statistiken für die vergangenen Events','event_scheduler');?><br>
                    [event_scheduler_holiday] - <?php _e('Ferienverwaltung, zu dieser Zeit finden keine Events statt','event_scheduler');?><br>
                    [event_scheduler_member_list] - <?php _e('Liste mit allen aktiven Mitgliedern','event_scheduler');?></strong></small></p>

    </form>

</div>
