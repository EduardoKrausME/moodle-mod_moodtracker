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
 * response_manager.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_moodtracker;

/**
 * Class response_manager.
 */
class response_manager {
    /**
     * Method get_user_response.
     *
     * @param int $moodtrackerid Parameter moodtrackerid.
     * @param int $userid Parameter userid.
     * @return ?\stdClass Return value.
     */
    public static function get_user_response(int $moodtrackerid, int $userid): ?\stdClass {
        global $DB;

        $record = $DB->get_record("moodtracker_responses", [
            "moodtrackerid" => $moodtrackerid,
            "userid" => $userid,
        ]);

        return $record ?: null;
    }

    /**
     * Method save_response.
     *
     * @param \stdClass $moodtracker Parameter moodtracker.
     * @param int $userid Parameter userid.
     * @param int $moodvalue Parameter moodvalue.
     * @return void Return value.
     */
    public static function save_response(\stdClass $moodtracker, int $userid, int $moodvalue): void {
        global $DB;

        if (!mood::is_valid($moodvalue)) {
            throw new \moodle_exception("invalidmood", "mod_moodtracker");
        }

        $existing = self::get_user_response((int) $moodtracker->id, $userid);
        if ($existing && empty($moodtracker->allowchange)) {
            throw new \moodle_exception("responsecannotchange", "mod_moodtracker");
        }

        $now = time();
        if ($existing) {
            $existing->moodvalue = $moodvalue;
            $existing->timemodified = $now;
            $DB->update_record("moodtracker_responses", $existing);
            return;
        }

        $record = (object) [
            "moodtrackerid" => $moodtracker->id,
            "userid" => $userid,
            "moodvalue" => $moodvalue,
            "timecreated" => $now,
            "timemodified" => $now,
        ];
        $DB->insert_record("moodtracker_responses", $record);
    }

    /**
     * Method get_summary.
     *
     * @param int $moodtrackerid Parameter moodtrackerid.
     * @return array Return value.
     */
    public static function get_summary(int $moodtrackerid): array {
        global $DB;

        $sql = "SELECT moodvalue, COUNT(1) AS total
                  FROM {moodtracker_responses}
                 WHERE moodtrackerid = :moodtrackerid
              GROUP BY moodvalue";
        $records = $DB->get_records_sql($sql, ["moodtrackerid" => $moodtrackerid]);

        $summary = array_fill(1, 5, 0);
        foreach ($records as $record) {
            $summary[(int) $record->moodvalue] = (int) $record->total;
        }

        return $summary;
    }
}
