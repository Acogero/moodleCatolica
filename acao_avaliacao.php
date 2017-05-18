<?php

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

$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));

$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();

$PAGE->set_url('/mod/sepex/Avaliacao.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

$acao = htmlspecialchars($_POST['acao']);
$check = $_POST['qtdAlunos'];
$alunosPresentes = array();

//FOR PARA SABER QUAIS ALUNOS ESTÃO PRESENTES
for($check; $check > -1; $check = $check - 1){
    if(!$_POST['CheckAluno'.$check]){
        continue;
    }else{
        array_push($alunosPresentes, htmlspecialchars($_POST['CheckAluno'.$check]));  
    }
}

//SWITCH PARA SABER QUAL TIPO DE TRABALHO:
switch($acao){
    case 1:
        $local  = htmlspecialchars($_POST['item01']);
        $local2 = htmlspecialchars($_POST['item02']);
        $local3 = htmlspecialchars($_POST['item03']);
        $local4 = htmlspecialchars($_POST['item04']);
        $resultado = $local + $local2 + $local3 + $local4;
    break;
    case 2:
        $local  = htmlspecialchars($_POST['case20']);
        $local2 = htmlspecialchars($_POST['case21']);
        $local3 = htmlspecialchars($_POST['case22']);
        $local4 = htmlspecialchars($_POST['case23']);
        $local5 = htmlspecialchars($_POST['case24']);
        $resultado = $local + $local2 + $local3 + $local4 + $local5;
    break;
    case 3:
        $local  = htmlspecialchars($_POST['item01']);
        $local2 = htmlspecialchars($_POST['item02']);
        $local3 = htmlspecialchars($_POST['item03']);
        $local4 = htmlspecialchars($_POST['item04']);
        $local5 = htmlspecialchars($_POST['item05']);
        $resultado = $local + $local2 + $local3 + $local4 + $local5;
    break;
    case 4:
        $local  = htmlspecialchars($_POST['item01']);
        $local2 = htmlspecialchars($_POST['item02']);
        $local3 = htmlspecialchars($_POST['item03']);
        $local4 = htmlspecialchars($_POST['item04']);
        $local5 = htmlspecialchars($_POST['item05']);
        $resultado = $local + $local2 + $local3 + $local4 + $local5;
    break;
    case 5:
        $local  = htmlspecialchars($_POST['item01']);
        $local2 = htmlspecialchars($_POST['item02']);
        $local3 = htmlspecialchars($_POST['item03']);
        $local4 = htmlspecialchars($_POST['item04']);
        $local5 = htmlspecialchars($_POST['item05']);
        $resultado = $local + $local2 + $local3 + $local4 + $local5;
    break;
}
//-----------------------------------------
echo $OUTPUT->header();

$formulario = html_writer::start_tag('form', array('id' => 'avalicaoSepex', 'action'=> "acao_avaliacao.php?id={$id}", 'method'=>"post"));
    $linkForm = html_writer::start_tag('div', array('id' => 'cabeçalho', 'style' => 'margin-top: 10%;border-style: solid;', 'class="container-fluid"'));
    
    //TÍTULO
        $linkForm .= html_writer::start_tag('header', array('class' => 'row;'));
            $linkForm .= html_writer::start_tag('div', array('class' => 'page-header'));
                $linkForm .= html_writer::start_tag('center');
                $linkForm .= html_writer::start_tag('h1');
                $linkForm .= 'Resultado da Avaliação';
            $linkForm .= html_writer::end_tag('div'); 
        $linkForm .= html_writer::end_tag('header');
    //----------------------------------------------------------------

        $linkForm .= html_writer::start_tag('div', array('class' => 'main'));
            
            $linkForm .= html_writer::start_tag('hr');
                $linkForm .= html_writer::start_tag('div', array('class' => 'container-fluid'));
                    $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                
                    //NOTA
                        $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                            $linkForm .= html_writer::start_tag('div', array('class' => 'input-group'));
                                $linkForm .= html_writer::start_tag('table', array('class' => 'table table-responsive'));
                                    $linkForm .= html_writer::start_tag('thead');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Nota: ' . $resultado;
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                    $linkForm .= html_writer::end_tag('thead');
                        //------------------------------------------------------------------                

                        //ALUNOS PRESENTES
                                    $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                        $linkForm .= 'Alunos presentes';
                                    $linkForm .= html_writer::end_tag('th');

                                    $linkForm .= html_writer::start_tag('tfoot');
                                    $linkForm .= html_writer::end_tag('tfoot');

                                    
                                    $linkForm .= html_writer::end_tag('table');
                                
                                foreach ($alunosPresentes as $aluno){
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-11'));
                                        $linkForm .= html_writer::start_tag('label');
                                            $linkForm .= $aluno;
                                        $linkForm .= html_writer::end_tag('label');                                
                                    $linkForm .= html_writer::end_tag('div');
                                }
                        //-------------------------------------------------------------------------------
                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
                        //BOTÕES
                                $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                                    $linkForm .= html_writer::start_tag('input', array('type' => 'submit', 'class' => 'btn btn-active btn-lg', 'value' => 'Voltar'));
                                    $linkForm .= html_writer::end_tag('div');
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-3'));
                                        $linkForm .= html_writer::start_tag('input', array('type' => 'submit', 'class' => 'btn btn-active btn-lg', 'value' => 'Enviar'));
                                    $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
                        //-----------------------------------------------------------------------
                            $linkForm .= html_writer::end_tag('div');
                        $linkForm .= html_writer::end_tag('div');
                    $linkForm .= html_writer::end_tag('div');
                $linkForm .= html_writer::end_tag('div');
            $linkForm .= html_writer::end_tag('hr');
        $linkForm .= html_writer::end_tag('div');
    $linkForm .= html_writer::end_tag('div'); //segunda DIV

        $formulario .= $linkForm;
 $formulario .= html_writer::end_tag('form');

    echo $formulario;

echo $OUTPUT->footer();