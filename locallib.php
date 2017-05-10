<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//Icons by:
//<div>Icons made by <a href="http://www.flaticon.com/authors/madebyoliver" title="Madebyoliver">Madebyoliver</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
/**
 * Biblioteca interna de funções para módulo sepex
 *
 * Todas as funções específicas do sepex, necessárias para implementar o módulo
 * Lógica, deve ir aqui. Nunca inclua este arquivo do seu lib.php!
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once('./classes/Projeto.class.php');

defined('MOODLE_INTERNAL') || die();

function criarCodigo($dados) {
    global $DB;
    $numero = $DB->count_records('sepex_projeto');
    $categoria = [
        "1" => "EGR",
        "2" => "EST",
        "3" => "INC",
        "4" => "INO",
        "5" => "PE",
        "6" => "PI",
        "7" => "RS",
        "8" => "TL",
        "9" => "TCC",
    ];

    if ($numero != 0):
        $numero++;
        $codigo = 'SEP17' . $dados->cod_curso . $categoria[$dados->cod_categoria] . '0' . $numero;
    else:
        $codigo = 'SEP17' . $dados->cod_curso . $categoria[$dados->cod_categoria] . '01';
    endif;

    return $codigo;
}

function guardar_formulario($dados, $codigo) {
    global $DB;

    //INSERIR NA TABELA DO PROJETO.
    //buscando a data atual do envio
    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());

    $projeto = new stdClass();
    $projeto->cod_projeto = $codigo;
    $projeto->titulo = $dados->titulo;
    $projeto->resumo = $dados->resumo[text];
    $projeto->status = null;
    $projeto->data_cadastro = $dataAtual;
    $projeto->email = $dados->email;
    $projeto->tags = $dados->tags;
    $projeto->cod_periodo = $dados->periodo;
    $projeto->turno = $dados->turno;
    $projeto->cod_categoria = $dados->cod_categoria;
    $id = $DB->insert_record("sepex_projeto", $projeto, $returnid = true);

    $curso = new stdClass();
    $curso->curso_cod_curso = $dados->cod_curso;
    $curso->projeto_id_projeto = $id;
    $DB->insert_record("sepex_projeto_curso", $curso);

    $aluno = new stdClass();
    $alunos = explode(";", $dados->aluno_matricula);
    foreach ($alunos as $i) {
        $aluno->aluno_matricula = $i;
        $aluno->id_projeto = $id;
        $DB->insert_record("sepex_aluno_projeto", $aluno);
    }

    $professor = new stdClass();
    $professor->id_projeto = $id;
    $professor->professor_cod_professor = $dados->cod_professor;
    $professor->tipo = 'orientador';
    $DB->insert_record("sepex_projeto_professor", $professor);

    $professor2 = new stdClass();
    $professor2->id_projeto = $id;
    $professor2->professor_cod_professor = $dados->cod_professor2;
    $professor2->tipo = 'orientador';
    if ($dados->cod_professor2 != 0) {
        $DB->insert_record("sepex_projeto_professor", $professor2);
    }
}

/**
 * Método responsável por atualizar as tabelas de cadastro de resumo sepex
 * @global type $DB
 * @param type $dados
 * @param type $codigo
 * @param type $id_projeto
 */
function atualizar_formulario($dados, $codigo, $id_projeto) {

    //Infelizmente o código do moodle não me permitiu realizar um update na tabela, então a maneira que encontrei,
    //foi realizar um delete nas tabelas e inserir novamente. Posteriormente estudarei uma maneira de realizar um update.
    global $DB;
    $DB->delete_records("sepex_projeto", array('id_projeto' => $id_projeto));
    $DB->delete_records("sepex_projeto_curso", array('projeto_id_projeto' => $id_projeto));
    $DB->delete_records("sepex_aluno_projeto", array('id_projeto' => $id_projeto));
    $DB->delete_records("sepex_projeto_professor", array('id_projeto' => $id_projeto));

    guardar_formulario($dados, $codigo);
}

/**
 * método responsável por exibir um botão que irá redirecionar para o formulário de inscrição 
 * @return button link
 */
function link_formulario($id) {

    //Botão que chama o formulario
    $linkForm = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:10%;'));
    $linkForm .= html_writer::start_tag('a', array('href' => 'cad-form.php?id=' . $id,));
    $linkForm .= html_writer::start_tag('img', array('src' => 'pix/form.png'));
    $linkForm .= get_string('inscricao', 'sepex');
    $linkForm .= html_writer::end_tag('a');
    $linkForm .= html_writer::end_tag('div');
    return $linkForm;
}

/* * Método responsável por trazer do banco as informações sobre os projetos de um aluno
 * @param type $usuario -> matricula do aluno que deseja listar as informações
 * @return aluno
 */

function select_projetos_aluno($usuario) {
    global $DB;
//Exibir os projetos do aluno
    $resultado = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.titulo,
            sp.cod_projeto,
            sp.cod_categoria,
            sp.data_cadastro
            FROM mdl_sepex_aluno_projeto sap
            INNER JOIN mdl_sepex_projeto sp ON sp.id_projeto = sap.id_projeto
            WHERE sap.aluno_matricula=?", array($usuario));
    return $resultado;
}

function select_projetos_professor($usuario) {
    global $DB;
//Exibir os projetos do aluno
    $resultado = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.titulo,
            sp.cod_projeto,
            sp.cod_categoria,
            sp.data_cadastro,
            sap.tipo
            FROM mdl_sepex_projeto_professor sap
            INNER JOIN mdl_sepex_projeto sp ON sp.id_projeto = sap.id_projeto
            WHERE sap.professor_cod_professor=? ", array($usuario));
    return $resultado;
}

/**
 * Método responsável por criar uma tabela listando os projetos de determinado aluno
 * @param type $usuario = este usuario é o aluno que queremos listar os projetos.
 * @param type $id é o id da página para enviar ao delete_form.php
 * ATENÇÃO -- Na tag table estou usando uma classe do plugin 'forum' para receber tratamentos de css e js,
 * em caso de anomalias na exibição - tente remover essa classe forumheaderlist.
 */
function listar_projetos_aluno($usuario, $id) {
    global $PAGE;

    echo link_formulario($id);

    $resultado = select_projetos_aluno($usuario);

    if ($resultado != null || $resultado != ''):
        //Caso o moodle tenha o plugin módulo use o css dele através da classe forumheaderlist
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('envio', 'sepex') . '</th>';
        echo '<th>' . get_string('editar', 'sepex') . '</th>';
        echo '<th>' . get_string('apagar', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($resultado as $projeto) {
            echo '<tr>';
            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';
            echo'<td><a>' . $projeto->data_cadastro . '</a></td>';
            $editar = html_writer::start_tag('td');
            //$editar .= html_writer::start_tag('a', array('id'=> 'btnEdit','href'=> 'acao_form.php?id='.$id.'&proj='.$projeto->id_projeto.'&acao=1', ));
            $editar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            $editar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
            $editar .= html_writer::end_tag('a');
            $editar .= html_writer::end_tag('td');
            echo $editar;
            $delete = html_writer::start_tag('td');
            $delete .= html_writer::start_tag('a', array('href' => 'acao_form.php?id=' . $id . '&proj=' . $projeto->id_projeto . '&acao=2',));
            $delete .= html_writer::start_tag('img', array('src' => 'pix/delete.png'));
            $delete .= html_writer::end_tag('a');
            $delete .= html_writer::end_tag('td');
            echo $delete;
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    endif;
}

function listar_projetos_professor($usuario, $id) {
    global $PAGE;
    $resultado = select_projetos_professor($usuario);
    if ($resultado != null || $resultado != ''):
        //Caso o moodle tenha o plugin módulo use o css dele através da classe forumheaderlist
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('projetosorientador', 'sepex') . '</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('envio', 'sepex') . '</th>';
        echo '<th>' . get_string('editar', 'sepex') . '</th>';
        echo '<th>' . get_string('avaliarresumo', 'sepex') . '</th>';
        echo '<th>' . get_string('apagar', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        //echo '<pre>';
        //print_r($resultado);
        //echo '</pre>';
        foreach ($resultado as $projeto) {
            if (strcmp($projeto->tipo, 'orientador') == 0) {
                echo '<tr>';
                $titulo = html_writer::start_tag('td');
                $titulo .= html_writer::start_tag('a', array('href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                $titulo .= $projeto->titulo;
                $titulo .= html_writer::end_tag('a');
                $titulo .= html_writer::end_tag('td');
                echo $titulo;
                echo'<td><a>' . $projeto->cod_projeto . '</a></td>';
                echo'<td><a>' . $projeto->data_cadastro . '</a></td>';
                $editar = html_writer::start_tag('td');
                //$editar .= html_writer::start_tag('a', array('id'=> 'btnEdit','href'=> 'acao_form.php?id='.$id.'&proj='.$projeto->id_projeto.'&acao=1', ));
                $editar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                $editar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
                $editar .= html_writer::end_tag('a');
                $editar .= html_writer::end_tag('td');
                echo $editar;
                $avaliar = html_writer::start_tag('td');
                $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                $avaliar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
                $avaliar .= html_writer::end_tag('a');
                $avaliar .= html_writer::end_tag('td');
                echo $avaliar;
                $delete = html_writer::start_tag('td');
                $delete .= html_writer::start_tag('a', array('href' => 'acao_form.php?id=' . $id . '&proj=' . $projeto->id_projeto . '&acao=2',));
                $delete .= html_writer::start_tag('img', array('src' => 'pix/delete.png'));
                $delete .= html_writer::end_tag('a');
                $delete .= html_writer::end_tag('td');
                echo $delete;
                echo '</tr>';
            }
        }
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('projetosavaliador', 'sepex') . '</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('envio', 'sepex') . '</th>';
        echo '<th>' . get_string('editar', 'sepex') . '</th>';
        echo '<th>' . get_string('avaliarapresentacao', 'sepex') . '</th>';
        echo '<th>' . get_string('apagar', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $resultado = select_projetos_professor($usuario);
         foreach ($resultado as $projeto) {
            if(strcmp($projeto->tipo, 'avaliador') == 0){
                    echo '<tr>';
                    $titulo = html_writer::start_tag('td');
                    $titulo .= html_writer::start_tag('a', array('href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                    $titulo .= $projeto->titulo;
                    $titulo .= html_writer::end_tag('a');
                    $titulo .= html_writer::end_tag('td');
                    echo $titulo;
                    echo'<td><a>' . $projeto->cod_projeto . '</a></td>';
                    echo'<td><a>' . $projeto->data_cadastro . '</a></td>';
                    $editar = html_writer::start_tag('td');
                    //$editar .= html_writer::start_tag('a', array('id'=> 'btnEdit','href'=> 'acao_form.php?id='.$id.'&proj='.$projeto->id_projeto.'&acao=1', ));
                    $editar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                    $editar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
                    $editar .= html_writer::end_tag('a');
                    $editar .= html_writer::end_tag('td');
                    echo $editar;
                    $avaliar = html_writer::start_tag('td');
                    $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => 'cad-form.php?id=' . $id . '&data=' . $projeto->id_projeto,));
                    $avaliar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
                    $avaliar .= html_writer::end_tag('a');
                    $avaliar .= html_writer::end_tag('td');
                    echo $avaliar;
                    $delete = html_writer::start_tag('td');
                    $delete .= html_writer::start_tag('a', array('href' => 'acao_form.php?id=' . $id . '&proj=' . $projeto->id_projeto . '&acao=2',));
                    $delete .= html_writer::start_tag('img', array('src' => 'pix/delete.png'));
                    $delete .= html_writer::end_tag('a');
                    $delete .= html_writer::end_tag('td');
                    echo $delete;
                    echo '</tr>';
                }
        }
        echo '</tbody>';
        echo '</table>';
    endif;
}

function apagar_formulario($id_projeto) {
    global $DB;
    $DB->delete_records('sepex_aluno_projeto', array("id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto_curso', array("projeto_id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto_professor', array("id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto', array("id_projeto" => $id_projeto));
}

function consultarProjeto($codProjeto) {
    global $DB;
    //Exibir os projetos do aluno
    $query = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.cod_projeto,
            sp.titulo,
            sp.resumo,
            sp.email,
            sp.tags,
            sp.cod_periodo,
            sp.turno,
            sp.cod_categoria,
            spc.curso_cod_curso
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_curso spc ON spc.projeto_id_projeto  = sp.id_projeto
            WHERE sp.id_projeto=?", array($codProjeto));
    return $query;
}

function consultarAlunos($codProjeto) {
    global $DB;
    //Exibir os projetos do aluno
    $query = $DB->get_records("sepex_aluno_projeto", array("id_projeto" => $codProjeto));
    $alunos = array();
    foreach ($query as $aluno) {
        $alunos[$aluno->id_aluno_projeto] = $aluno->aluno_matricula;
    }
    //Apos obter os alunos serão separados utilizando ;
    $retorno = implode(";", $alunos);

    return $retorno;
}

function consultarProfessores($codProjeto) {
    global $DB;
    //Exibir os projetos do aluno
    $query = $DB->get_records("sepex_projeto_professor", array("id_projeto" => $codProjeto));

    $orientadores = array();
    $i = 0;
    foreach ($query as $orientador) {
        $i++;
        $orientadores[$i] = $orientador->professor_cod_professor;
    }
    return $orientadores;
}
