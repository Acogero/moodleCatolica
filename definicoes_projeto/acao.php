<?php

/* OBTEM OS DADOS DE DEFINICAO DO PROJETO E GRAVA NO BANCO DE DADOS
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

require_login($course, true, $cm);

if(isset($_GET['data'])){
    $id_projeto = htmlspecialchars($_GET['data']);                    
}
  
$mform = new FormularioDefinicaoProjeto();

if ($data = $mform->get_data()) {
    $tipo = 'avaliador';
    guardar_professor($id_projeto,$data->avaliador,$tipo);
    guardar_professor($id_projeto,$data->avaliador2,$tipo);
    guardar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);    
}else{    
    alterar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
    guardar_professor($id_projeto,$data->avaliador,$tipo);
    guardar_professor($id_projeto,$data->avaliador2,$tipo);
}

header("Location: view.php?id={$id}");