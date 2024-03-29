﻿=== Event Scheduler ===
Contributors: poisl
Donate link: https://profiles.wordpress.org/poisl
Tags: event, scheduler, planner, planer, sport, participant, mail, notify
Requires at least: 4
Tested up to: 5.9
Stable tag: 1.0.5
Requires PHP: 7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Event Scheduler kann zur Planung von wiederkehrenden Events genutzt werden.

Beispiel: In einem kleinen Sportverein, können wöchentliche Spiele geplant werden. Mitglieder können
zu einem Event zusagen oder absagen,damit deren Mitspieler sehen, wer am Spiel teilnehmen wird.

Ferien können eingegeben werden, zu denen keine Events stattfinden.

Mitspieler, die weder zu- noch abgesagt haben, können per Mail benachrichtigt werden.

== Installation ==

1. Das Plugin "Event Scheduler" herunterladen und aktivieren.
2. Eine Seite mit dem Shortcode "event_scheduler_event_list" versehen.
3. Plugin über die Einstellungen konfigurieren und alle Pflichtfelder ausfüllen.
4. Optional kann zusätzlich das "WP-Members" Plugin verwendet werden, damit Event Scheduler nur aktiven User verwendet.
5. Optional kann die Mailbenachrichtigung mit dem "WP Crontrol" Plugin konfiguriert werden Dazu wird der Hook "event_scheduler_mail_notification" verwendet.

Die folgenden Shortcodes können auf den Seiten verwendet werden:
"event_scheduler_event_list" - Zeigt den aktuellen Event und ermöglicht es Webseitenbenutzern zu- oder abzusagen
"event_scheduler_event_statistics" - Statistiken für die vergangenen Events
"event_scheduler_holiday" - Ferienverwaltung, zu dieser Zeit finden keine Events statt
"event_scheduler_member_list" - Liste mit allen aktiven Mitgliedern

Event Scheduler ist in Deutsch und Englisch verfügbar und kann mittels POT-Datei leicht in weitere Sprachen übersetzt werden.

== Changelog ==

= 1.0.5 =
* Kompabilität mit WordPress Version aktualisiert.

= 1.0.4 =
* Kompabilität mit WordPress und PHP Versionen aktualisiert.

= 1.0.3 =
* Ein Problem wurde behoben, dass die korrekte Anzeige der Event- und Mitgliederliste verhindert hat.

= 1.0.2 =
* Fix in der Benachrichtigungsmail, zudem wird nun die Admin-Mail als Absender verwendet.

= 1.0.1 =
* Kleiner Fix beim Zu- und Absagen von Events

= 1.0 =
* Initiale Version
