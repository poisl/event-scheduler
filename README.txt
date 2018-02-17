=== Event Scheduler ===
Contributors: poisl
Donate link: https://profiles.wordpress.org/poisl
Tags: event, scheduler, planner, planer, sport, participant, mail, notify
Requires at least: 4
Tested up to: 4.9.4
Stable tag: 1.0.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Event scheduler can be used for planning of recurring events.

Example: If you are in a small sports club, you could plan weekly games.
Members will be able to join or cancel, so other members can see who
participates in the game.

You can plan vacation schedules, where no events will be scheduled.

Last but not least you can notify users that have not yet accepted or
declined the event by email.

== Installation ==

1. Download and activate the "Event Scheduler" plugin.
2. Insert shortcode "event_scheduler_event_list" on a page.
3. Configure the plugins through the settings page and fill all required fields.
4. Optionally you can use the "WP-Members" plugin, so Event Scheduler will only work with active users.
5. Mail notifications can easily be triggered with the "WP Crontrol" plugin. Therefore please use the hook "event_scheduler_mail_notification".

You can use the following shortcodes on pages:
"event_scheduler_event_list" - Lists the current event, so website members can join or decline it
"event_scheduler_event_statistics" - Statistics for past events
"event_scheduler_holiday" - Manage upcoming holiday, when no events will take place
"event_scheduler_member_list" - List all active members

Event Scheduler is available in english and german language. It can easily be translated in other languages using the provided POT file.

== Changelog ==

= 1.0.1 =
* Small fix for accepting and declining events

= 1.0 =
* Initial version