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
 * mood.php
 *
 * @package   mod_moodtracker
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_moodtracker;

/**
 * Class mood.
 */
class mood {
    /** @var int */
    public const VERY_HAPPY = 5;

    /** @var int */
    public const HAPPY = 4;

    /** @var int */
    public const NEUTRAL = 3;

    /** @var int */
    public const SAD = 2;

    /** @var int */
    public const ANGRY = 1;

    /**
     * Method get_all.
     *
     * @return array Return value.
     */
    public static function get_all(): array {
        return [
            self::VERY_HAPPY => ["emoji" => "😁", "label" => get_string("mood_veryhappy", "mod_moodtracker")],
            self::HAPPY => ["emoji" => "🙂", "label" => get_string("mood_happy", "mod_moodtracker")],
            self::NEUTRAL => ["emoji" => "😐", "label" => get_string("mood_neutral", "mod_moodtracker")],
            self::SAD => ["emoji" => "😔", "label" => get_string("mood_sad", "mod_moodtracker")],
            self::ANGRY => ["emoji" => "😡", "label" => get_string("mood_angry", "mod_moodtracker")],
        ];
    }

    /**
     * Method is_valid.
     *
     * @param int $value Parameter value.
     * @return bool Return value.
     */
    public static function is_valid(int $value): bool {
        return array_key_exists($value, self::get_all());
    }
}
