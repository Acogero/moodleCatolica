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
        $area = htmlspecialchars($_GET['are']);
        $turno = htmlspecialchars($_GET['tur']);
        $categoria = htmlspecialchars($_GET['cat']);
    }
    
    $projeto = listar_projeto_por_id($id_projeto);
    $dados_apresentacao = obter_dados_apresentacao($id_projeto);           
    $tipo = 'orientador';
    $orientadores = listar_nome_professores($id_projeto, $tipo);
    $tipo2 = 'avaliador';
    $avaliadores = listar_professor_por_id_projeto($id_projeto,$tipo2);
    
    
    //VIEW          
   
    if(isset($dados_apresentacao[$id_projeto]->id_local_apresentacao)):
        $mform = new FormularioDefinicaoProjeto("definicao_projeto.php?id={$id}&data={$id_projeto}&acao=1&are={$area}&tur={$turno}&cat={$categoria}", array('data_apresentacao'=>$dados_apresentacao[$id_projeto]->data_apresentacao, 'localapresentacao'=>$dados_apresentacao[$id_projeto]->id_local_apresentacao, 'avaliador'=>$avaliadores[0], 'avaliador2'=>$avaliadores[1],));          
    else:        
        $mform = new FormularioDefinicaoProjeto("definicao_projeto.php?id={$id}&data={$id_projeto}&are={$area}&tur={$turno}&cat={$categoria}");          
    endif;    
        
    if(isset($_GET['acao'])){
        if($mform->is_cancelled()):
            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
        elseif ($data = $mform->get_data()):
            alterar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
            guardar_professor($id_projeto,$data->avaliador,$tipo2);
            if($data->avaliador2!= null && $data->avaliador2 != '' ){
                guardar_professor($id_projeto,$data->avaliador2,$tipo2);
            }
            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
        else:
            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
        endif;        
    }else{
        if($mform->is_cancelled()):
            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
        elseif ($data = $mform->get_data()):           
            guardar_professor($id_projeto,$data->avaliador,$tipo2);
            if($data->avaliador2!= null && $data->avaliador2 != ''){
                guardar_professor($id_projeto,$data->avaliador2,$tipo2);
            }
            guardar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
        else:
            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
        endif;
    }           
    
   

 