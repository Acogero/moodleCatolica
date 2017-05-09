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
 * Biblioteca de funções de interface e constantes para módulo sepex
  *
  * Todas as funções principais do Moodle, neeeded para permitir que o módulo funcione
  * Integrado no Moodle deve ser colocado aqui.
  *
  * Todas as funções específicas do sepex, necessárias para implementar todo o módulo
  * Lógica, deve ir para locallib.php. Isso ajudará a poupar algum
  Moodle está executando ações em todos os módulos.
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

define('SEPEX_ULTIMATE_ANSWER', 42);

/* Moodle core API */

/**
 * Retorna as informações sobre se o módulo suporta um recurso
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function sepex_supports($feature) {

    switch($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 *Salva uma nova instância do sepex no banco de dados
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $sepex Submitted data from the form in mod_form.php
 * @param mod_sepex_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted sepex record
 */
function sepex_add_instance(stdClass $sepex, mod_sepex_mod_form $mform = null) {
    global $DB;

    $sepex->timecreated = time();

    // You may have to add extra stuff in here.

    $sepex->id = $DB->insert_record('sepex', $sepex);

    sepex_grade_item_update($sepex);

    return $sepex->id;
}

/**
* Atualiza uma instância do sepex no banco de dados
  *
  * Dado um objeto contendo todos os dados necessários,
  * (Definido pelo formulário em mod_form.php) esta função
  * Atualizará uma instância existente com novos dados.
 *
 * @param stdClass $sepex An object from the form in mod_form.php
 * @param mod_sepex_mod_form $mform The form instance itself (if needed)
 * @return boolean Success/Fail
 */
function sepex_update_instance(stdClass $sepex, mod_sepex_mod_form $mform = null) {
    global $DB;

    $sepex->timemodified = time();
    $sepex->id = $sepex->instance;

    // You may have to add extra stuff in here.

    $result = $DB->update_record('sepex', $sepex);

    sepex_grade_item_update($sepex);

    return $result;
}

/**
 * Esta função padrão irá verificar todas as instâncias deste módulo
 * E verifique se há eventos atualizados criados para cada um deles.
 * Se courseid = 0, então cada evento sepex no site é marcado, senão
 * Somente os eventos sepex pertencentes ao curso especificado são verificados.
 * Isso só é necessário se o módulo estiver gerando eventos de calendário.
 *
 * @param int $courseid Course ID
 * @return bool
 */
function sepex_refresh_events($courseid = 0) {
    global $DB;

    if ($courseid == 0) {
        if (!$sepex = $DB->get_records('sepex')) {
            return true;
        }
    } else {
        if (!$sepex = $DB->get_records('sepex', array('course' => $courseid))) {
            return true;
        }
    }

    foreach ($sepex as $sepex) {
        // Create a function such as the one below to deal with updating calendar events.
        // sepex_update_events($sepex);
    }

    return true;
}

/**
 * Remove uma instância do sepex do banco de dados
  *
  * Dado um ID de uma instância deste módulo,
  * Esta função irá excluir permanentemente a instância
  * E quaisquer dados que dependam dele.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function sepex_delete_instance($id) {
    global $DB;

    if (! $sepex = $DB->get_record('sepex', array('id' => $id))) {
        return false;
    }

    // Delete any dependent records here.

    $DB->delete_records('sepex', array('id' => $sepex->id));

    sepex_grade_item_delete($sepex);

    return true;
}

/**
 * Retorna um pequeno objeto com informações resumidas sobre o
 * O usuário fez com uma dada instância particular deste módulo
 * Usado para relatórios de atividade do usuário.
 *
 * $ Return-> time = o tempo que eles fizeram
 * $ Return-> info = uma breve descrição do texto
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $sepex The sepex instance record
 * @return stdClass|null
 */
function sepex_user_outline($course, $user, $mod, $sepex) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
* Imprime uma representação detalhada do que um usuário fez com
* Uma determinada instância particular deste módulo, para relatórios de atividade do usuário.
*
 * É suposto ecoar diretamente sem retornar um valor.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $sepex the module instance record
 */
function sepex_user_complete($course, $user, $mod, $sepex) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in sepex activities and print it out.
 *
 * @param stdClass $course The course record
 * @param bool $viewfullnames Should we display full names
 * @param int $timestart Print activity since this timestamp
 * @return boolean True if anything was printed, otherwise false
 */
function sepex_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;
}

/**
* Prepara os dados de atividade recentes
  *
  * Esta função callback é suposto para preencher a matriz passada com
  * Registros de atividade personalizados. Esses registros são então renderizados em HTML via
  * {@link sepex_print_recent_mod_activity ()}.
  *
  * Retorna void, adiciona itens em $ activities e aumenta $ index.
  *
  * @param array $ activities seqüencialmente indexado array de objetos com a propriedade 'cmid' adicionada
  * @param int $ indexa o índice nas atividades $ para usar para o próximo registro
  * @param int $timestart timestart anexar atividade desde este tempo
  * @param int $courseid course o id do curso que produzimos o relatório para
  * @param int $cmid id do módulo do curso cmid
  * @param int $userid userid verifica apenas a atividade de um determinado usuário, o padrão é 0 (todos os usuários)
  * @param int $groupid groupid verifica apenas a atividade de um determinado grupo, o padrão é 0 (todos os grupos)
 */
function sepex_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@link sepex_get_recent_mod_activity()}
 *
 * @param stdClass $activity activity record with added 'cmid' property
 * @param int $courseid the id of the course we produce the report for
 * @param bool $detail print detailed report
 * @param array $modnames as returned by {@link get_module_types_names()}
 * @param bool $viewfullnames display users' full names
 */
function sepex_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return boolean
 */
function sepex_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * For example, this could be array('moodle/site:accessallgroups') if the
 * module uses that capability.
 *
 * @return array
 */
function sepex_get_extra_capabilities() {
    return array();
}

/* Gradebook API */

/**
 * É uma escala dada usada pela instância de sepex?
  *
  * Esta função retorna se uma escala estiver sendo usada por um sepex
  * Se tem suporte para classificação e escalas.
 *
 * @param int $sepexid ID of an instance of this module
 * @param int $scaleid ID of the scale
 * @return bool true if the scale is used by the given sepex instance
 */
function sepex_scale_used($sepexid, $scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('sepex', array('id' => $sepexid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of sepex.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale
 * @return boolean true if the scale is used by any sepex instance
 */
function sepex_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('sepex', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given sepex instance
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $sepex instance object with extra cmidnumber and modname property
 * @param bool $reset reset grades in the gradebook
 * @return void
 */
function sepex_grade_item_update(stdClass $sepex, $reset=false) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $item = array();
    $item['itemname'] = clean_param($sepex->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;

    if ($sepex->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $sepex->grade;
        $item['grademin']  = 0;
    } else if ($sepex->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$sepex->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }

    if ($reset) {
        $item['reset'] = true;
    }

    grade_update('mod/sepex', $sepex->course, 'mod', 'sepex',
            $sepex->id, 0, null, $item);
}

/**
 * Delete grade item for given sepex instance
 *
 * @param stdClass $sepex instance object
 * @return grade_item
 */
function sepex_grade_item_delete($sepex) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/sepex', $sepex->course, 'mod', 'sepex',
            $sepex->id, 0, null, array('deleted' => 1));
}

/**
 * Update sepex grades in the gradebook
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $sepex instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 */
function sepex_update_grades(stdClass $sepex, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    // Populate array of grade objects indexed by userid.
    $grades = array();

    grade_update('mod/sepex', $sepex->course, 'mod', 'sepex', $sepex->id, 0, $grades);
}

/* File API */

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function sepex_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for sepex file areas
 *
 * @package mod_sepex
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function sepex_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the sepex file areas
 *
 * @package mod_sepex
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the sepex's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function sepex_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

/* Navigation API */

/**
 * Extends the global navigation tree by adding sepex nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the sepex module instance
 * @param stdClass $course current course record
 * @param stdClass $module current sepex instance record
 * @param cm_info $cm course module information
 */
function sepex_extend_navigation(navigation_node $navref, stdClass $course, stdClass $module, cm_info $cm) {
    // TODO Delete this function and its docblock, or implement it.
}

/**
 * Extends the settings navigation with the sepex settings
 *
 * This function is called when the context for the page is a sepex module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav complete settings navigation tree
 * @param navigation_node $sepexnode sepex administration node
 */
function sepex_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $sepexnode=null) {
    // TODO Delete this function and its docblock, or implement it.
}

