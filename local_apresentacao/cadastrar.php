<?php

/* Pagina criada apenas para cadastro
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
require_login($course, true, $cm);
    
    //Cadastrar local de apresentacao.
    $nome = htmlspecialchars($_POST['local_apresentacao']);
    if($nome != '' && $nome != null){
        criar_local_apresentacao($nome);
        header("Location: view.php?id={$id}");
    }