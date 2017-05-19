<?php

/* EXIBE A TELA PARA ATRIBUIR UM LOCAL DE APRESENTAÇÃO - PROFESSORES AVALIADORES - DIA - HORA - PARA UM PROJETO
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
require_once ('../classes/FormularioDefinicaoProjeto.class.php');
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

$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();
$PAGE->set_url('/mod/sepex/definicao_projeto.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);
    
    //CHAMADA MODEL       
    if(isset($_GET['data'])){
        $id_projeto = htmlspecialchars($_GET['data']);                    
    }
    
    $projeto = listar_projeto_por_id($id_projeto);
    $dados_apresentacao = obter_dados_apresentacao($id_projeto);           
    $tipo = 'orientador';
    $orientadores = listar_nome_professores($id_projeto, $tipo);                   
    $locais = $DB->get_records('sepex_local_apresentacao');        
    $locais_apresentacao = array(''=>'Escolher',);
    foreach($locais as $local){                    
        $locais_apresentacao[$local->id_local_apresentacao] =  $local->nome_local_apresentacao;
    }
                      
    //VIEW          
    echo $OUTPUT->header();         
    echo $OUTPUT->heading(format_string('Definições do projeto'), 2);
    echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');   
    $header  = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));                                                     
        $header .= html_writer::start_tag('h5', array('class'=>'page-header'));
            $header.= $projeto[$id_projeto]->cod_projeto.' - '.$projeto[$id_projeto]->titulo;
        $header .= html_writer::end_tag('h5');
        $header.= '<b>'.get_string('curso', 'sepex').'</b>'.': '.$projeto[$id_projeto]->curso_cod_curso.'</br>';
        $header.= '<b>'.get_string('orientadores', 'sepex').'</b>'.': '.$orientadores;
    $header .= html_writer::end_tag('div');
    echo $header;
    
       
    $mform = new FormularioDefinicaoProjeto("acao.php?id={$id}&data={$id_projeto}");
  
    $mform->display();

    echo $OUTPUT->footer();

 