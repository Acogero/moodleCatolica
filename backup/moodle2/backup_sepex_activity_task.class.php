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
 * Defines backup_newmodule_activity_task class
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/sepex/backup/moodle2/backup_sepex_stepslib.php');

class backup_sepex_activity_task extends backup_activity_task {

    /**
     * Nenhuma configuração específica para esta atividade
     */
    protected function define_my_settings() {
    }

    /**
     * Define uma etapa de backup para armazenar os dados da instância no arquivo sepex.xml
     */
    protected function define_my_steps() {
        $this->add_step(new backup_sepex_activity_structure_step('sepex_structure', 'sepex.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of sepex.
        $search = '/('.$base.'\/mod\/sepex\/index.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@SEPEXINDEX*$2@$', $content);

        // Link to sepex view by moduleid.
        $search = '/('.$base.'\/mod\/sepex\/view.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@SEPEXVIEWBYID*$2@$', $content);

        return $content;
    }
}
