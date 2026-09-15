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
 * view.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . "/../../config.php");

$id = required_param("id", PARAM_INT);
$cm = get_coursemodule_from_id("moodtracker", $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$moodtracker = $DB->get_record("moodtracker", ["id" => $cm->instance], "*", MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability("mod/moodtracker:view", $context);

$PAGE->set_url("/mod/moodtracker/view.php", ["id" => $cm->id]);
$PAGE->set_title(format_string($moodtracker->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->add_body_class("mod-moodtracker");

if (has_capability("mod/moodtracker:viewreport", $context) && !has_capability("mod/moodtracker:respond", $context)) {
    redirect(new moodle_url("/mod/moodtracker/report.php", ["id" => $cm->id]));
}

require_capability("mod/moodtracker:respond", $context);

if (optional_param("submitmood", 0, PARAM_BOOL)) {
    require_sesskey();
    $moodvalue = required_param("moodvalue", PARAM_INT);
    \mod_moodtracker\response_manager::save_response($moodtracker, $USER->id, $moodvalue);
    redirect($PAGE->url, get_string("responsesaved", "mod_moodtracker"), null, \core\output\notification::NOTIFY_SUCCESS);
}

$current = \mod_moodtracker\response_manager::get_user_response($moodtracker->id, $USER->id);
$moods = [];
foreach (\mod_moodtracker\mood::get_all() as $value => $mood) {
    $moods[] = [
        "value" => $value,
        "emoji" => $mood["emoji"],
        "label" => $mood["label"],
        "selected" => $current && (int) $current->moodvalue === $value,
        "disabled" => $current && empty($moodtracker->allowchange),
    ];
}

$template = [
    "action" => $PAGE->url->out(false),
    "sesskey" => sesskey(),
    "moods" => $moods,
    "hasresponse" => (bool) $current,
    "canchange" => empty($moodtracker->allowchange) ? false : true,
    "reporturl" => has_capability("mod/moodtracker:viewreport", $context)
        ? (new moodle_url("/mod/moodtracker/report.php", ["id" => $cm->id]))->out(false)
        : null,
];

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($moodtracker->name));
if ($moodtracker->intro) {
    echo $OUTPUT->box(format_module_intro("moodtracker", $moodtracker, $cm->id), "generalbox mod_introbox");
}
echo $OUTPUT->render_from_template("mod_moodtracker/checkin", $template);
echo $OUTPUT->footer();
