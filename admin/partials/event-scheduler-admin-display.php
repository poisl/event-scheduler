<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/poisl
 * @since      1.0.0
 *
 * @package    Event_Scheduler
 * @subpackage Event_Scheduler/admin/partials
 */
?>

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
                        <?php _e('Event start date (Y-m-d), e.g. 2016-01-15', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_date"
                           name="<?php echo $this->plugin_name; ?>[event_start_date]"
                           value="<?php if (!empty($event_start_date)) echo $event_start_date; ?>" required/>
                </td>
            </tr>

            <!-- Start time of the event -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_start_time">
                        <?php _e('Event start time (H:M), e.g. 20:00', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_start_time"
                           name="<?php echo $this->plugin_name; ?>[event_start_time]"
                           value="<?php if (!empty($event_start_time)) echo $event_start_time; ?>" required/>
                </td>
            </tr>

            <!-- End time of the event -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_end_time">
                        <?php _e('Event end time (H:M), e.g. 22:00', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>event_end_time"
                           name="<?php echo $this->plugin_name; ?>[event_end_time]"
                           value="<?php if (!empty($event_end_time)) echo $event_end_time; ?>" required/>
                </td>
            </tr>

            <!-- Event repeat interval -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_repeat_interval">
                        <?php _e('Event is repeated after amount of weeks specified here, e.g. 1', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_repeat_interval"
                           name="<?php echo $this->plugin_name; ?>[event_repeat_interval]"
                           value="<?php if (!empty($event_repeat_interval)) echo $event_repeat_interval; ?>"
                           required/>
                </td>
            </tr>

            <!-- Default event location -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_default_location">
                        <?php _e('Event location, e.g. gym', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_default_location"
                           name="<?php echo $this->plugin_name; ?>[event_default_location]"
                           value="<?php if (!empty($event_default_location)) echo $event_default_location; ?>"
                           required/>
                </td>
            </tr>

            <!-- Alternative event location -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_alternate_location">
                        <?php _e('Optional alternative event location, e.g. sports ground', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_alternate_location"
                           name="<?php echo $this->plugin_name; ?>[event_alternate_location]"
                           value="<?php if (!empty($event_alternate_location)) echo $event_alternate_location; ?>"/>
                </td>
            </tr>
        </table>

        <p>
            <?php _e('The settings from here are all used for email notifications. You can setup a cron schedule with the hook "event_scheduler_mail_notification" to automate the notification process.', $this->plugin_name); ?>
        </p>

        <table class="form-table">
            <!-- Full URL to the page where the event list is put on -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_url">
                        <?php _e('Full URL with trailing slash to the page where you have included your event list', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_notification_url"
                           name="<?php echo $this->plugin_name; ?>[event_notification_url]"
                           value="<?php if (!empty($event_notification_url)) echo $event_notification_url; ?>"
                           required/>
                </td>
            </tr>

            <!-- Notification mail subject -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_subject">
                        <?php _e('Email subject for the email notification, e.g. Event notification', $this->plugin_name); ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text"
                           id="<?php echo $this->plugin_name; ?>event_notification_subject"
                           name="<?php echo $this->plugin_name; ?>[event_notification_subject]"
                           value="<?php if (!empty($event_notification_subject)) echo $event_notification_subject; ?>"
                           required/>
                </td>
            </tr>

            <!-- Notification mail body -->
            <tr>
                <th scope="row"><label
                            for="<?php echo $this->plugin_name; ?>event_notification_subject">
                        <?php _e('Email body for the email notification', $this->plugin_name); ?></label>
                </th>
                <td>
                    <textarea class="large-text code" id="<?php echo $this->plugin_name; ?>event_notification_body"
                              name="<?php echo $this->plugin_name; ?>[event_notification_body]" rows="6" cols="20"
                              required><?php if (!empty($event_notification_body)) echo $event_notification_body; ?></textarea>
                    <p class="description"><?php _e('HTML-Tags and the following dynamic variables can be used', $this->plugin_name); ?>:
                        ###name###, ###eventdate###, ###eventtime###, ###eventlocation###, ###acceptlink###,
                        ###cancellink###
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', TRUE); ?>

        <!-- Plugin shortcodes -->
        <p>
            <?php _e('You can use the following shortcodes on pages', $this->plugin_name); ?>:
            <br>
            <small><strong>[event_scheduler_event_list]
                    - <?php _e('Lists the current event, so website members can join or decline it', $this->plugin_name); ?>
                    <br>
                    [event_scheduler_event_statistics]
                    - <?php _e('Statistics for past events', $this->plugin_name); ?><br>
                    [event_scheduler_holiday]
                    - <?php _e('Manage upcoming holiday, when no events will take place', $this->plugin_name); ?>
                    <br>
                    [event_scheduler_member_list]
                    - <?php _e('List all active members', $this->plugin_name); ?></strong>
            </small>
        </p>
    </form>
</div>
