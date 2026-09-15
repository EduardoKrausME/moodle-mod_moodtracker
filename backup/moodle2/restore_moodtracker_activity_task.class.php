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
 * restore_moodtracker_activity_task.class.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * restore_moodtracker_activity_task
 */
class restore_moodtracker_activity_task extends restore_activity_task {
    /**
     * define_my_settings
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * define_my_steps
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(new restore_moodtracker_activity_structure_step("moodtracker_structure", "moodtracker.xml"));
    }

    /**
     * define_decode_contents
     *
     * @return restore_decode_content[]
     */
    public static function define_decode_contents() {
        return [new restore_decode_content("moodtracker", ["intro"], "moodtracker")];
    }

    /**
     * define_decode_rules
     *
     * @return array
     */
    public static function define_decode_rules() {
        return [];
    }
}
