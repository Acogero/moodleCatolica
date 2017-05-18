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
$modcontext    = context_module::instance($cm->id);

// DEFININDO LINK PARA PÁGINA DO USUÁRIO.
define('FORMULARIO_LINK', "cadastro_sepex.php?id=".$id);


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


//INSTANCIAÇÃO DO OBJETO FORMULÁRIO
//Obtendo o id do projeto via get
$codProjeto = htmlspecialchars($_GET['data']);

//Com base no id do projeto iremos definir se iremos inserir ou alterar um projeto
if($codProjeto == ''){
    $mform = new Formulario("cadastro_sepex.php?id={$id}");
}
else{
    //Para alterar um projeto estamos obtendo os dados do projeto    
    $projeto = listar_projeto_por_id($codProjeto);
    $alunos = listar_matricula_alunos_por_id_projeto($codProjeto);
    $professores = listar_professor_por_id_projeto($codProjeto);
    $modcontext    = context_module::instance($cm->id);
    $coursecontext = context_course::instance($course->id);
    
    //Instanciação de um novo formulario passando como parametro: (destino_formulario, array(informe aqui os campos e os valores dos campos)
    //Dentro do array passamos os campos e os valores que enviaremos previamente ao formulario para edição.
    $mform = new Formulario("cadastro_sepex.php?id={$id}&acao=10&idp={$codProjeto}&cod={$projeto[$codProjeto]->cod_projeto}", array('coursecontext'=>$context_course, 'modcontext'=>$modcontext, 'cod_curso'=>$projeto[$codProjeto]->curso_cod_curso,'titulo' => $projeto[$codProjeto]->titulo, 'resumo' => $projeto[$codProjeto]->resumo, 'tags' => $projeto[$codProjeto]->tags, 'aloca_mesa' => $projeto[$codProjeto]->aloca_mesa, 'cod_periodo' => $projeto[$codProjeto]->cod_periodo, 'turno' => $projeto[$codProjeto]->turno, 'cod_categoria' => $projeto[$codProjeto]->cod_categoria, 'aluno_matricula' => $alunos, 'cod_professor'=> $professores[1],'cod_professor2'=> $professores[2] ));
  
}
//Verifica-se se estamos inserindo ou adicionando um novo cadastro
$acao = htmlspecialchars($_GET['acao']);
$id_projeto = htmlspecialchars($_GET['idp']);
$codigo_projeto = htmlspecialchars($_GET['cod']);
//Esse numero 10 vem da instanciacao do formulario.
if($acao == 10){
    // Manipular a operação de cancelamento do formulário, se o botão Cancelar estiver presente no formulário
    if($dados = $mform->get_data()):        
    
       atualizar_projeto($dados,$id_projeto);

       header("Location:". VIEW_URL_LINK);
    else:
        exibir_formulario_inscricao($sepex,$cm,$mform);
    endif;
}else{
    //Envio dos dados do formulario para a função que irá guardar esses dados no banco de dados.
    if($dados = $mform->get_data()):        
       $codigo = criarCodigo($dados);
       guardar_projeto($dados,$codigo,$USER);
       enviar_email($USER);
       header("Location:". VIEW_URL_LINK);
    else:     
        exibir_formulario_inscricao($sepex,$cm,$mform);
    endif;
}





 