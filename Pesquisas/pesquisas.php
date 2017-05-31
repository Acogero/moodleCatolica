<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');
//require_once('./classes/Formulario.class.php');
$id = required_param('id', PARAM_INT); // Course_module ID, ou
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
$PAGE->set_url('/mod/sepex/pesquisas.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

    $idProjeto = 1;
    $projeto = listar_projeto_por_id($idProjeto);
    $categoria = retorna_categoria($projeto[$idProjeto]->cod_categoria);
    $alunos = listar_matricula_alunos_por_id_projeto($idProjeto);

//A saída começa aqui.
echo $OUTPUT->header();
$formulario = html_writer::start_tag('form', array('id' => 'avalicaoSepex', 'action'=> "acao_pesquisa.php?id={$id}", 'method'=>"POST"));
    $linkForm = html_writer::start_tag('div', array('id' => 'cabeçalho', 'style' => 'margin-top: 10%;border-style: solid;', 'class="container-fluid"'));
    
     //TÍTULO
        $linkForm .= html_writer::start_tag('header', array('class' => 'row;'));
            $linkForm .= html_writer::start_tag('div', array('class' => 'page-header'));
                $linkForm .= html_writer::start_tag('center');
                $linkForm .= html_writer::start_tag('h1');
                $linkForm .= $projeto[$idProjeto]->titulo;
            $linkForm .= html_writer::end_tag('div'); 
        $linkForm .= html_writer::end_tag('header');

    //SUBTÍTULO
        $linkForm .= html_writer::start_tag('div', array('class' => 'main'));
            $linkForm .= html_writer::start_tag('center');
            $linkForm .= html_writer::start_tag('h4');
            $linkForm .= $projeto[$idProjeto]->cod_projeto;
            $linkForm .= html_writer::end_tag('h4');
            $linkForm .= html_writer::start_tag('hr');
                $linkForm .= html_writer::start_tag('div', array('class' => 'container-fluid'));
                    $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                        $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                            $linkForm .= html_writer::start_tag('h4');
                            $linkForm .= html_writer::start_tag('label');
                                $linkForm .= 'Resumo:';
                            $linkForm .= html_writer::end_tag('label');
                            $linkForm .= html_writer::end_tag('h4');
                            $linkForm .= html_writer::start_tag('p', array('align class' => 'text-justify'));
                                $linkForm .= $projeto[$idProjeto]->resumo;
                            $linkForm .= html_writer::end_tag('p');
                        $linkForm .= html_writer::end_tag('div');
                        
                        $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                            $linkForm .= html_writer::start_tag('div', array('class' => 'input-group'));
                                $linkForm .= html_writer::start_tag('table', array('class' => 'table table-responsive'));
                                    $linkForm .= html_writer::start_tag('thead');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Data Cadastro: '. $projeto[$idProjeto]->data_cadastro;
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                        $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Email: '. $projeto[$idProjeto]->email;
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Tags: '. $projeto[$idProjeto]->tags;
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Período: '. periodo($projeto[$idProjeto]->cod_periodo);
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Turno: '. $projeto[$idProjeto]->turno;
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Mesa: '. aloca_mesa($projeto[$idProjeto]->aloca_mesa);
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Categoria: '. categoria($projeto[$idProjeto]->cod_categoria);
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                    $linkForm .= html_writer::end_tag('thead');
                                $linkForm .= html_writer::end_tag('table');

                                $linkForm .= html_writer::start_tag('tfoot');
                                $linkForm .= html_writer::end_tag('tfoot');

                                   $CheckId = 0;
                                foreach ($alunos as $aluno){
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-11'));
                                            $linkForm .= html_writer::start_tag('label', array('type' => 'label', 'name' => 'CheckAluno'."$CheckId".'', 'value' => ''."$aluno".''));
                                            $linkForm .= html_writer::end_tag('label');
                                            $linkForm .= listar_nome_aluno($aluno)->nome_aluno;
                                            $CheckId = $CheckId + 1;
                                    $linkForm .= html_writer::end_tag('div');
                                }

                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::start_tag('br');
                            //INPUT PARA ENVIAR A QUANTIDADE DE ALUNO PARA A PAGINA ACAO_AVALIACAO
                                $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => "qtdAlunos", 'value' => ''."$CheckId".''));
                                $linkForm .= html_writer::end_tag('input');
                            //--------------------------------------------------------------------
                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
                                $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-11'));
                                    $linkForm .= html_writer::start_tag('input', array('type' => 'submit', 'class' => 'btn btn-active btn-lg', 'value' => 'Voltar'));
                                    $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
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
//Fim da página
echo $OUTPUT->footer();

function periodo($id){
    switch($id){
        case 1:
            $id = 'Primeiro período';
            return $id;
        break;
        case 2:
            $id = 'Segundo período';
            return $id;
        break;
        case 3:
            $id = 'Terceiro período';
            return $id;
        break;
        case 4:
            $id = 'Quarto período';
            return $id;
        break;
        case 5:
            $id = 'Quinto período';
            return $id;
        break;
        case 6:
            $id = 'Sexto período';
            return $id;
        break;
        case 7:
            $id = 'Sétimo período';
            return $id;
        break;
        case 8:
            $id = 'Oitavo período';
            return $id;
        break;
    }
}

function aloca_mesa($id){
    switch ($id) {
        case 1:
            $id = 'Sim';
            return $id;
        break;
        case 2:
            $id = 'Não';
            return $id;
        break;
    }
}

function categoria($id){
    switch ($id) {
        case 1:
            $id = 'Egresso';
            return $id;
        break;
        case 2:
            $id = 'Estágio';
            return $id;
        break;
        case 3:
            $id = 'Iniciação científica';
            return $id;
        break;
        case 4:
            $id = 'Inovação';
            return $id;
        break;
        case 5:
            $id = 'Projeto de Extensão';
            return $id;
        break;
        case 6:
            $id = 'Projeto Integrador';
            return $id;
        break;
        case 7:
            $id = 'Responsabilidade Social';
            return $id;
        break;
        case 8:
            $id = 'Tema Livre';
            return $id;
        break;
        case 9:
            $id = 'Trabalho de Conclusão de Curso';
            return $id;
        break;
        case 10:
            $id = 'Mostra Vídeo';
            return $id;
        break;
        case 11:
            $id = 'Concurso de Fotografia';
            return $id;
        break;
        default:
            # code...
            break;
    }
}