<?php

/* PÁGINA DE EXIBIÇÃO DO FORMULÁRIO DE CADASTRO SEPEX
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
require_once('../classes/Formulario.class.php');

global $DB, $CFG, $PAGE;

$id = required_param('id', PARAM_INT); // Modulo do curso
$s  = optional_param('s', 0, PARAM_INT);  // id da instância do plugin sepex

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

define('VIEW_URL_LINK', "../view.php?id=" . $id);

    //INSTANCIAÇÃO DO OBJETO FORMULÁRIO - Obtendo o id do projeto via get

    if(isset($_GET['data'])){
        $codProjeto = htmlspecialchars($_GET['data']);        
        $projeto = listar_projeto_por_id($codProjeto);
        $alunos = listar_matricula_alunos_por_id_projeto($codProjeto);
        $professores = listar_professor_por_id_projeto($codProjeto);
        $modcontext    = context_module::instance($cm->id);
        $coursecontext = context_course::instance($course->id);

        //Instanciação de um novo formulario passando como parametro: (destino_formulario, array(informe aqui os campos e os valores dos campos)        
        $mform = new Formulario("cadastro_sepex.php?id={$id}&acao=1&idp={$codProjeto}&cod={$projeto[$codProjeto]->cod_projeto}", array('modcontext'=>$modcontext, 'cod_curso'=>$projeto[$codProjeto]->curso_cod_curso,'titulo' => $projeto[$codProjeto]->titulo, 'resumo' => $projeto[$codProjeto]->resumo, 'tags' => $projeto[$codProjeto]->tags, 'aloca_mesa' => $projeto[$codProjeto]->aloca_mesa, 'cod_periodo' => $projeto[$codProjeto]->cod_periodo, 'turno' => $projeto[$codProjeto]->turno, 'cod_categoria' => $projeto[$codProjeto]->cod_categoria, 'aluno_matricula' => $alunos, 'cod_professor'=> $professores[1],'cod_professor2'=> $professores[2] ));
    }
    else{
        $mform = new Formulario("cadastro_sepex.php?id={$id}");
    }

    //AÇÃO SOBRE O FORMULÁRIO DE CADASTRO - Verifica-se se a ação é inserir ou editar um formulario de cadastro.
    if(isset($_GET['acao'])){ 

        $id_projeto = htmlspecialchars($_GET['idp']);
        $codigo_projeto = htmlspecialchars($_GET['cod']);

        if($dados = $mform->get_data()):        
            atualizar_projeto($dados,$id_projeto);
            header("Location:". VIEW_URL_LINK);
        else:
            exibir_formulario_inscricao($sepex,$cm,$mform);
        endif;
        
    }else{
        if($dados = $mform->get_data()):        
            $codigo = criarCodigo($dados);
            guardar_projeto($dados,$codigo,$USER);
            enviar_email($USER);
            header("Location:". VIEW_URL_LINK);
        else:     
            exibir_formulario_inscricao($sepex,$cm,$mform);
        endif;
    }





 