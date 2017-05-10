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
 * A forma principal de configuração de novo módulo
  *
  * Usa o núcleo padrão Moodle formslib. Para mais informações sobre eles, por favor
  * Visite: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('./classes/formulario.class.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
$PAGE->requires->jquery();

defined('MOODLE_INTERNAL') || die();
$id = required_param('id', PARAM_INT); // Course_module ID, ou
$s  = optional_param('s', 0, PARAM_INT);  // ... Sepex instance ID - deve ser nomeado como o primeiro caractere do módulo.

if ($id) {
    $cm         = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex  = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex  = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}
$lang = current_language();
require_login($course, true, $cm);
$context_course = context_course::instance($course -> id);

echo $OUTPUT->header();
//Instantiate simplehtml_form 
$mform = new formulario();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
  //Set default data (if any)
  $mform->set_data($toform);
  //displays the form
  $mform->display();
  
}

 echo $OUTPUT->footer();