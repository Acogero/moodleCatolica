<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
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
$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();
$PAGE->set_url('/mod/sepex/local_apresentacao/acao.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));


$acao = htmlspecialchars($_GET['acao']);
$id_local= htmlspecialchars($_GET['local']);

echo $OUTPUT->header();
   
if($acao == 1):    
    
    echo $OUTPUT->confirm(get_string("delete_local", "sepex"),
    "view.php?id=$id&acao=$acao&local=$id_local",
    $CFG->wwwroot.'/mod/sepex/local_apresentacao/view.php?id='.$id);
endif;


echo $OUTPUT->footer();