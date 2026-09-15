<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * lib.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * moodtracker_supports
 *
 * @param $feature
 * @return bool|int|null
 */
function moodtracker_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_OTHER;
        case FEATURE_MOD_INTRO:
        case FEATURE_SHOW_DESCRIPTION:
        case FEATURE_BACKUP_MOODLE2:
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        default:
            return null;
    }
}

/**
 * moodtracker_add_instance
 *
 * @param $data
 * @param $mform
 * @return bool|int
 * @throws dml_exception
 */
function moodtracker_add_instance($data, $mform = null) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    return $DB->insert_record("moodtracker", $data);
}

/**
 * moodtracker_update_instance
 *
 * @param $data
 * @param $mform
 * @return bool
 * @throws dml_exception
 */
function moodtracker_update_instance($data, $mform = null) {
    global $DB;

    $data->id = $data->instance;
    $data->timemodified = time();
    return $DB->update_record("moodtracker", $data);
}

/**
 * moodtracker_delete_instance
 *
 * @param $id
 * @return bool
 * @throws dml_exception
 */
function moodtracker_delete_instance($id) {
    global $DB;

    if (!$DB->record_exists("moodtracker", ["id" => $id])) {
        return false;
    }

    $DB->delete_records("moodtracker_responses", ["moodtrackerid" => $id]);
    $DB->delete_records("moodtracker", ["id" => $id]);
    return true;
}
