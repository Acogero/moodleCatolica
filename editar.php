<?php

/* Página criada para apresentar o formulário de inscrição 
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once('./classes/Formulario.class.php');

global $DB, $CFG, $PAGE;

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
$context_course = context_course::instance($course -> id);

// DEFININDO LINK PARA PÁGINA DO USUÁRIO.
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
$PAGE->set_heading($course->fullname);


// Link para retornar ao view.php
define('VIEW_URL_LINK', "view.php?id=" . $id);
define('VIEW_URL', $protocol . $path ."/". VIEW_URL_LINK);

$proj = htmlspecialchars($_GET['data']);
//INSTANCIAÇÃO DO OBJETO FORMULÁRIO
$mform = new Formulario("cadastro_sepex.php?id={$id}", array('cod_curso' => 'ENF', 'email' => $proj));
 
if ($mform->is_cancelled()):
  // Manipular a operação de cancelamento do formulário, se o botão Cancelar estiver presente no formulário
elseif($dados = $mform->get_data()):

//   header("Location:". VIEW_URL_LINK);
else:
  // este ramo é executado se o formulário é enviado, mas os dados não são validados eo formulário deve ser exibido novamente
  // ou na primeira exibição do formulário.
    echo $OUTPUT->header();
    //Titulo
    echo $OUTPUT->heading(format_string($sepex->name), 2);
    echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
    
    $mform->set_data($toform); // Definir dados padrão (se houver)
    $mform->display(); // exibe o formulário        
  
    echo $OUTPUT->footer();
endif;






 