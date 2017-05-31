<?php

/**
 * TELA APRESENTADA AO PROFESSOR AVALIADOR PARA AVALIAÇÃO DO PROJETO 
 *
 * @package    mod_sepex
 * @copyright  2017 Marcos Vinicius A. Moreira  <marcosv_3@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once('../locallib.php');
require_once ('../classes/FormularioAvaliador.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$id_projeto = optional_param('data', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}
$lang = current_language();
require_login($course, true, $cm);
$coursecontext = context_course::instance($course->id);
$event = \mod_sepex\event\course_module_viewed::create(array(
            'objectid' => $PAGE->cm->instance,
            'context' => $PAGE->context,
        ));
$PAGE->set_url('/mod/sepex/avaliacao_avaliador.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();         
echo $OUTPUT->heading(format_string('AVALIAR APRESENTAÇÃO DO PROJETO'), 2);
echo $OUTPUT->box(format_string(''), 2);  

//CHAMADA MODEL       
if (!empty($id_projeto)) {    
    $projeto = listar_projeto_por_id($id_projeto);
    $tipo = 'orientador';
    $orientadores = listar_nome_professores($id_projeto, $tipo);
}


//View header of page
$header = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));
$header .= html_writer::start_tag('h5', array('class' => 'page-header'));
$header .= $projeto[$id_projeto]->cod_projeto . ' - ' . $projeto[$id_projeto]->titulo;
$header .= html_writer::end_tag('h5');
$header .= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->curso_cod_curso . '</br>';
$header .= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->turno . '</br>';
$header .= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . $orientadores;
$header .= html_writer::end_tag('div');
echo $header;
if(isset($projeto[$id_projeto]->resumo)){
    $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
    $resumo .= html_writer::start_tag('p').$projeto[$id_projeto]->resumo.html_writer::end_tag('p');
    $resumo .= html_writer::end_tag('div');
    echo $resumo;
}
    $dados_avaliacao = listar_dados_avaliacao_avaliador($id_projeto, $USER->username);
    echo '<pre>';
        print_r($dados_avaliacao);
    echo '</pre>';
//if (!empty($add)) {
//    $mform = new FormularioAvaliador("acao_avaliacao.php?id={$id}&data={$id_projeto}", array('cod_categoria' => $projeto[$id_projeto]->cod_categoria));
//    $mform->display();
//}else if (!empty($update)) {
//    $mform = new FormularioAvaliador("acao_avaliacao.php?id={$id}&data={$id_projeto}", array('modcontext' => $modcontext, 'resumo' => $projeto[$id_projeto]->resumo));
//
//}



//Fim da página
echo $OUTPUT->footer();
