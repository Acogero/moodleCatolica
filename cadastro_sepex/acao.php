<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
require_once('../classes/Formulario.class.php');
global $DB, $CFG, $PAGE;

$id = required_param('id', PARAM_INT);
$s  = optional_param('s', 0, PARAM_INT);
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
define('FORMULARIO_LINK', "cadastro_sepex.php?id=".$id);
define('FORMULARIO_URL', $protocol . $path ."/". FORMULARIO_LINK);
$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));

$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();

$PAGE->set_url('/mod/sepex/cadastro_sepex.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

$acao = htmlspecialchars($_GET['acao']);
$proj = htmlspecialchars($_GET['proj']);

echo $OUTPUT->header();
  
if($acao==2){
    echo $OUTPUT->confirm(get_string("delete", "sepex"),
    "../view.php?id=$id&acao=$acao&proj=$proj",
    $CFG->wwwroot.'/mod/sepex/view.php?id='.$id);
}
echo $OUTPUT->footer();