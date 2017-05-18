<?php

/* Nesta página serão definidos os locais de apresentação de cada projeto
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once('./classes/FiltroProjeto.class.php');

global $DB, $PAGE;

$id = required_param('id', PARAM_INT); // Modulo do curso
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

$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));

$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();

$PAGE->set_url('/mod/sepex/cadastro_sepex.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

    echo $OUTPUT->header();        
    echo $OUTPUT->heading(format_string('Definição de salas'), 2);
    echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');

        $local = htmlspecialchars($_POST['local_apresentacao']);
        $dia = htmlspecialchars($_POST['dia_apresentacao']);
        $hora = htmlspecialchars($_POST['hora_apresentacao']);
        
        $mform = new FiltroProjeto();
        $dados = $mform->get_data(); 
        
        $id_projeto = projetos_filtrados($dados,$id);
        if($local != null){    
            guardar_local_apresentacao($id_projeto,$local,$dia,$hora);
        }
    echo $OUTPUT->footer();

