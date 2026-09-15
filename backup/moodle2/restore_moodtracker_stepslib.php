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
 * restore_moodtracker_stepslib.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * restore_moodtracker_activity_structure_step
 */
class restore_moodtracker_activity_structure_step extends restore_activity_structure_step {
    /**
     * define_structure
     *
     * @return mixed
     */
    protected function define_structure() {
        $paths = [new restore_path_element("moodtracker", "/activity/moodtracker")];
        if ($this->get_setting_value("userinfo")) {
            $paths[] = new restore_path_element("moodtracker_response", "/activity/moodtracker/responses/response");
        }
        return $this->prepare_activity_structure($paths);
    }

    /**
     * process_moodtracker
     *
     * @param $data
     * @return void
     * @throws dml_exception
     */
    protected function process_moodtracker($data) {
        global $DB;

        $data = (object) $data;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $newitemid = $DB->insert_record("moodtracker", $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * process_moodtracker_response
     *
     * @param $data
     * @return void
     * @throws dml_exception
     */
    protected function process_moodtracker_response($data) {
        global $DB;

        $data = (object) $data;
        $data->moodtrackerid = $this->get_new_parentid("moodtracker");
        $data->userid = $this->get_mappingid("user", $data->userid);
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $DB->insert_record("moodtracker_responses", $data);
    }

    /**
     * after_execute
     *
     * @return void
     */
    protected function after_execute() {
        $this->add_related_files("mod_moodtracker", "intro", null);
    }
}
