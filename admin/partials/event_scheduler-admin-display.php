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

        <table class="form-table">
            <!-- Start date of the event -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_start_date">
                        <?php _e('Event Startdatum (Y-m-d), z. B. 2016-01-15', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_date"
                           name="<?php echo $this->plugin_name; ?>event_start_date"
                           value="<?php if (!empty($event_start_date)) echo $event_start_date; ?>" required/>
                </td>
            </tr>

            <!-- Start time of the event -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_start_time">
                        <?php _e('Event Startzeit (H:M), z. B. 20:00', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_time"
                           name="<?php echo $this->plugin_name; ?>event_start_time"
                           value="<?php if (!empty($event_start_time)) echo $event_start_time; ?>" required/>
                </td>
            </tr>

            <!-- End time of the event -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_end_time">
                        <?php _e('Event Endzeit (H:M), z. B. 22:00', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_end_time"
                           name="<?php echo $this->plugin_name; ?>event_end_time"
                           value="<?php if (!empty($event_end_time)) echo $event_end_time; ?>" required/>
                </td>
            </tr>

            <!-- Event repeat interval -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_repeat_interval">
                        <?php _e('Der Event wird in der hier angegebenen Zeit in Wochen wiederholt, z. B. 1', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_repeat_interval"
                           name="<?php echo $this->plugin_name; ?>event_repeat_interval"
                           value="<?php if (!empty($event_repeat_interval)) echo $event_repeat_interval; ?>"
                           required/>
                </td>
            </tr>

            <!-- Default event location -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_default_location">
                        <?php _e('Event Standort, z. B. Turnhalle', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_default_location"
                           name="<?php echo $this->plugin_name; ?>event_default_location"
                           value="<?php if (!empty($event_default_location)) echo $event_default_location; ?>"
                           required/>
                </td>
            </tr>

            <!-- Alternative event location -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_alternate_location">
                        <?php _e('(Optional) Alternativer Event Standort, z. B. Sportplatz', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_alternate_location"
                           name="<?php echo $this->plugin_name; ?>event_alternate_location"
                           value="<?php if (!empty($event_alternate_location)) echo $event_alternate_location; ?>"/>
                </td>
            </tr>
        </table>

        <p>
            <?php _e('Alle Einstellungen ab hier werden für die Mailbenachrichtigung benötigt. Mit dem Cron Hook "event_scheduler_mail_notification" kann der Mailversand automatisiert werden.', 'event_scheduler'); ?>
        </p>

        <table class="form-table">
            <!-- Full URL to the page where the event list is put on -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_url">
                        <?php _e('URL zur Seite mit dem Eventplaner mit Slash am Ende', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_notification_url"
                           name="<?php echo $this->plugin_name; ?>event_notification_url"
                           value="<?php if (!empty($event_notification_url)) echo $event_notification_url; ?>"
                           required/>
                </td>
            </tr>

            <!-- Notification mail subject -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_subject">
                        <?php _e('Betreff für die Mailbenachrichtigung, z. B. Event Benachrichtigung', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_notification_subject"
                           name="<?php echo $this->plugin_name; ?>event_notification_subject"
                           value="<?php if (!empty($event_notification_subject)) echo $event_notification_subject; ?>"
                           required/>
                </td>
            </tr>

            <!-- Notification mail body -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_subject">
                        <?php _e('Text für die Mailbenachrichtigung', 'event_scheduler'); ?></label>
                </th>
                <td>
                    <textarea class="large-text code" id="<?php echo $this->plugin_name; ?>event_notification_body"
                              name="<?php echo $this->plugin_name; ?>[event_notification_body]" rows="6" cols="20"
                              required><?php if (!empty($event_notification_body)) echo $event_notification_body; ?></textarea>
                    <p class="description"><?php _e('HTML-Tags und die folgenden dynamischen Variablen können verwendet werden:', 'event_scheduler'); ?>
                        ###name###, ###eventdate###, ###eventtime###, ###eventlocation###, ###acceptlink###,
                        ###cancellink###
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Änderungen übernehmen', 'event_scheduler'), 'primary', 'submit', TRUE); ?>

        <!-- Plugin shortcodes -->
        <p>
            <?php _e('Die folgenden Shortcodes können auf den Seiten verwendet werden:', 'event_scheduler'); ?>
            <br>
            <small><strong>[event_scheduler_event_list]
                    - <?php _e('Zeigt den aktuellen Event und ermöglicht es Webseitenbenutzern zu- oder abzusagen', 'event_scheduler'); ?>
                    <br>
                    [event_scheduler_event_statistics]
                    - <?php _e('Statistiken für die vergangenen Events', 'event_scheduler'); ?><br>
                    [event_scheduler_holiday]
                    - <?php _e('Ferienverwaltung, zu dieser Zeit finden keine Events statt', 'event_scheduler'); ?>
                    <br>
                    [event_scheduler_member_list]
                    - <?php _e('Liste mit allen aktiven Mitgliedern', 'event_scheduler'); ?></strong>
            </small>
        </p>
    </form>
</div>
