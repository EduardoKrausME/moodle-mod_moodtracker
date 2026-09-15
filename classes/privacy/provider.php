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
 * provider.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_moodtracker\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

/**
 * Class provider.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Method get_metadata.
     *
     * @param collection $collection Parameter collection.
     * @return collection Return value.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            "moodtracker_responses",
            [
                "moodtrackerid" => "privacy:metadata:moodtracker_responses:moodtrackerid",
                "userid" => "privacy:metadata:moodtracker_responses:userid",
                "moodvalue" => "privacy:metadata:moodtracker_responses:moodvalue",
                "timecreated" => "privacy:metadata:moodtracker_responses:timecreated",
                "timemodified" => "privacy:metadata:moodtracker_responses:timemodified",
            ],
            "privacy:metadata:moodtracker_responses"
        );
        return $collection;
    }

    /**
     * Method get_contexts_for_userid.
     *
     * @param int $userid Parameter userid.
     * @return contextlist Return value.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        $sql = "SELECT ctx.id
                  FROM {context} ctx
                  JOIN {course_modules} cm ON cm.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modname
                  JOIN {moodtracker_responses} r ON r.moodtrackerid = cm.instance
                 WHERE r.userid = :userid";
        $contextlist->add_from_sql($sql, [
            "contextlevel" => CONTEXT_MODULE,
            "modname" => "moodtracker",
            "userid" => $userid,
        ]);
        return $contextlist;
    }

    /**
     * Method export_user_data.
     *
     * @param approved_contextlist $contextlist Parameter contextlist.
     * @return void Return value.
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel !== CONTEXT_MODULE) {
                continue;
            }

            $cm = get_coursemodule_from_id("moodtracker", $context->instanceid);
            if (!$cm) {
                continue;
            }

            $response = $DB->get_record("moodtracker_responses", [
                "moodtrackerid" => $cm->instance,
                "userid" => $userid,
            ]);
            if (!$response) {
                continue;
            }

            $data = (object) [
                "moodvalue" => $response->moodvalue,
                "timecreated" => transform::datetime($response->timecreated),
                "timemodified" => transform::datetime($response->timemodified),
            ];
            writer::with_context($context)->export_data([get_string("responses", "mod_moodtracker")], $data);
        }
    }

    /**
     * Method delete_data_for_all_users_in_context.
     *
     * @param \context $context Parameter context.
     * @return void Return value.
     */
    public static function delete_data_for_all_users_in_context(\context $context): void {
        global $DB;

        if ($context->contextlevel !== CONTEXT_MODULE) {
            return;
        }
        $cm = get_coursemodule_from_id("moodtracker", $context->instanceid);
        if ($cm) {
            $DB->delete_records("moodtracker_responses", ["moodtrackerid" => $cm->instance]);
        }
    }

    /**
     * Method delete_data_for_user.
     *
     * @param approved_contextlist $contextlist Parameter contextlist.
     * @return void Return value.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        global $DB;

        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel !== CONTEXT_MODULE) {
                continue;
            }
            $cm = get_coursemodule_from_id("moodtracker", $context->instanceid);
            if ($cm) {
                $DB->delete_records("moodtracker_responses", [
                    "moodtrackerid" => $cm->instance,
                    "userid" => $userid,
                ]);
            }
        }
    }
}
