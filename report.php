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
 * report.php
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
require_capability("mod/moodtracker:viewreport", $context);

$PAGE->set_url("/mod/moodtracker/report.php", ["id" => $cm->id]);
$PAGE->set_title(get_string("reporttitle", "mod_moodtracker"));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->add_body_class("mod-moodtracker");

$summary = \mod_moodtracker\response_manager::get_summary($moodtracker->id);
$total = array_sum($summary);
$max = $summary ? max($summary) : 0;
$rows = [];

foreach (\mod_moodtracker\mood::get_all() as $value => $mood) {
    $count = $summary[$value] ?? 0;
    $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
    $barwidth = $max > 0 ? round(($count / $max) * 100, 1) : 0;
    $rows[] = [
        "emoji" => $mood["emoji"],
        "label" => $mood["label"],
        "count" => $count,
        "percentage" => $percentage,
        "barwidth" => $barwidth,
    ];
}

$participants = count_enrolled_users($context, "mod/moodtracker:respond", 0, true);
$pending = max(0, $participants - $total);
$template = [
    "rows" => $rows,
    "total" => $total,
    "participants" => $participants,
    "pending" => $pending,
    "viewurl" => (new moodle_url("/mod/moodtracker/view.php", ["id" => $cm->id]))->out(false),
];

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($moodtracker->name));
if ($moodtracker->intro) {
    echo $OUTPUT->box(format_module_intro("moodtracker", $moodtracker, $cm->id), "generalbox mod_introbox");
}
echo $OUTPUT->render_from_template("mod_moodtracker/report", $template);
echo $OUTPUT->footer();
