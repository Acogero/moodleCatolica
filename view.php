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
* Imprime uma instância específica de sepex
*
* Você pode ter uma descrição mais longa do arquivo, bem como,
* Se você gosta, e pode abranger várias linhas.
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('./classes/Formulario.class.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

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
$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));

$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();

$PAGE->set_url('/mod/sepex/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));
//A saída começa aqui.
echo $OUTPUT->header();

    $showactivity = true;
  
        $timenow = time();
        if (!empty($sepex->timeavailablefrom) && $sepex->timeavailablefrom > $timenow) {
            echo $OUTPUT->notification(get_string('notopenyet', 'sepex', userdate($sepex->timeavailablefrom)));
            $showactivity = false;
        } else if (!empty($sepex->timeavailableto) && $timenow > $sepex->timeavailableto) {
            echo $OUTPUT->notification(get_string('expired', 'sepex', userdate($sepex->timeavailableto)));
            $showactivity = false;
        }

    if ($showactivity) {
        //-------------------------------- ALUNO                       
        if (!has_capability('mod/sepex:openformulario', $context_course)) {
          //Se for aluno redireciono para o formulario.            
            $usuario = $USER->username;
            listar_projetos_aluno($usuario, $id);
            if (isset($_POST['acao'])){
                $acao =  htmlspecialchars($_POST['acao']);
                $proj =  htmlspecialchars($_POST['proj']);
                if($acao == 2){
                    apagar_formulario($proj);
                    echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=view.php?id=$id'>";
                }
            }
        }
        //-------------------------------- PROFESSOR
        else if (has_capability('mod/sepex:openprofessor', $context_course)) {            
            $usuario = $USER->username;
            listar_projetos_professor($usuario,$id);
            
        }
        //-------------------------------- GERENTE
        else {
            echo $OUTPUT->heading(format_string('Organização sepex'), 2);
            echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
            viewGerente($id);
        }
        
        
    }
//Fim da página
    echo $OUTPUT->footer();