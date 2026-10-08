# Moodle Mood Tracker activity

`mod_moodtracker` adds a class mood check-in activity to Moodle. Students select how they feel from five options, and teachers see an aggregated dashboard showing the class distribution, response count, eligible participants and pending responses. The activity does not publish an individual's response to classmates.

Teachers may allow students to change their response after submission or lock it after the first check-in. Only the most recent answer for each student and activity is retained.

## Installation

Requires Moodle 4.1 or newer (and the PHP version supported by your Moodle release).

Download **moodtracker.zip** from the [GitHub Releases](https://github.com/EduardoKrausME/moodle-mod_moodtracker/releases) page and use *Site administration > Plugins > Install plugins*, or copy the plugin files into `mod/moodtracker` and complete the Moodle upgrade.

**Do not install GitHub's automatically generated "Source code (zip)" archive directly.** Its enclosing folder is not named `moodtracker`. The attached `moodtracker.zip` release package uses the correct root directory.

## Features

- Five mood choices: Very good, Good, Neutral, Not good and Frustrated.
- Student check-in with optional response changes.
- Teacher dashboard with counts, percentages and pending participants.
- Moodle activity access controls, view events, view-based activity completion, backup/restore, and privacy export/deletion support.

## Privacy

Each activity stores a student's latest mood value, user ID, and creation/update timestamps. The dashboard shows aggregates to users who have the report capability. Moodle's Privacy API supports exporting and deleting student responses, including deletion of an approved list of users in an activity.

## Description for the Moodle Plugins directory

**Class Mood Tracker** is a Moodle activity for quickly checking how students are feeling during a course. Students choose one of five mood options, while teachers view the aggregated distribution in a class dashboard, including how many learners have responded and how many are still pending. Teachers control whether responses can be changed after submission. Only the latest response for each learner is stored, and classmates cannot see individual answers.

The plugin supports Moodle capabilities, standard activity completion by viewing, course log events, backup and restore, and Moodle Privacy API export and deletion requests. Requires Moodle 4.1 or later. Install the correctly structured `moodtracker.zip` release package into `mod/moodtracker`.

## Screenshots for the Moodle Plugins directory

The following images are available for upload to the external listing. Linking or displaying them here **does not** publish them to the Plugins directory.

**Student mood check-in**

![Mood Tracker activity screenshot](https://raw.githubusercontent.com/EduardoKrausME/marketplace-plugins/master/screenshots/mod_moodtracker/new-1.png)

**Second preview**

![Mood Tracker second screenshot](https://raw.githubusercontent.com/EduardoKrausME/marketplace-plugins/master/screenshots/mod_moodtracker/new-2.png)

Image sources: [new-1.png](https://github.com/EduardoKrausME/marketplace-plugins/blob/master/screenshots/mod_moodtracker/new-1.png) and [new-2.png](https://github.com/EduardoKrausME/marketplace-plugins/blob/master/screenshots/mod_moodtracker/new-2.png).
