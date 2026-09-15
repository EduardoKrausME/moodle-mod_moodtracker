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
 * backup_moodtracker_stepslib.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * backup_moodtracker_activity_structure_step
 */
class backup_moodtracker_activity_structure_step extends backup_activity_structure_step {
    /**
     * define_structure
     *
     * @return mixed
     * @throws base_element_struct_exception
     */
    protected function define_structure() {
        $moodtracker = new backup_nested_element("moodtracker", ["id"], [
            "name", "intro", "introformat", "allowchange", "timecreated", "timemodified",
        ]);
        $responses = new backup_nested_element("responses");
        $response = new backup_nested_element("response", ["id"], [
            "userid", "moodvalue", "timecreated", "timemodified",
        ]);

        $moodtracker->add_child($responses);
        $responses->add_child($response);

        $moodtracker->set_source_table("moodtracker", ["id" => backup::VAR_ACTIVITYID]);
        if ($this->get_setting_value("userinfo")) {
            $response->set_source_table("moodtracker_responses", ["moodtrackerid" => backup::VAR_PARENTID]);
        }

        $response->annotate_ids("user", "userid");
        $moodtracker->annotate_files("mod_moodtracker", "intro", null);

        return $this->prepare_activity_structure($moodtracker);
    }
}
