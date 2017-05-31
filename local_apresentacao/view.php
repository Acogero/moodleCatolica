<?php

/* VISÃO DA PÁGINA DE CADASTRO DE LOCAIS DE APRESENTAÇÃO
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

$lang = current_language();
require_login($course, true, $cm);
$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();

$PAGE->set_url('/mod/sepex/local_apresentacao/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();        
echo $OUTPUT->heading(format_string('Gerenciar locais de apresentação SEPEX'), 2);
echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');

if (isset($_POST['acao'])){
    $acao =  htmlspecialchars($_POST['acao']); 
    $id_local =  htmlspecialchars($_POST['local']);
    if($acao == 1){
        apagar_local_apresentacao($id_local);
        echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=view.php?id=$id'>";
    } 
}

$formulario  = html_writer::start_tag('form', array('id' => 'formularioSepex', 'action'=> "cadastrar.php?id={$id}", 'method'=>"post", 'class'=> 'col-lg-5 col-md-5 col-sm-5'));                                                                                   
        $nome =  html_writer::start_tag('label', array('class'=> 'control-label')).format_string('Adicionar local de apresentacao').html_writer::end_tag('label');;
        $nome .= html_writer::start_tag('input', array('name'=> 'local_apresentacao', 'class'=> 'form-control', 'placeholder'=> 'Nome do local de apresentação','style' => 'margin-bottom:3%;'));   
        $btnSubmit = html_writer::start_tag('input', array('type'=>'submit', 'value'=>get_string('criar','sepex'), 'class' => 'btn btn-primary','style' => 'margin-bottom:5%;'));                                                                                                                         
    $formulario .=$nome;
    $formulario .=$btnSubmit;
$formulario .= html_writer::end_tag('form');
echo $formulario;

$link_voltar = html_writer::start_tag('a', array('href'=> '../view.php?id='.$id ,'style' => 'margin-bottom:3%;')); 
$link_voltar .= get_string('voltar_menu','sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

$locais = listar_locais_apresentacao();
echo '<table class="forumheaderlist table table-striped">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>'.get_string('localapresentacao', 'sepex').'</th>';
                    echo '<th>'.get_string('qtd_projetos', 'sepex').'</th>';                                        
                    echo '<th>'.get_string('apagar', 'sepex').'</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                foreach($locais as $local){                                                               
                   echo '<tr>';                
                        echo'<td><a>'.$local->nome_local_apresentacao.'</a></td>';                    
                        echo'<td><a>'.'</a></td>';
                        $delete  = html_writer::start_tag('td');
                        $delete .= html_writer::start_tag('a', array('href'=> 'acao.php?id='.$id.'&local='.$local->id_local_apresentacao.'&acao=1' ));
                        $delete .= get_string('apagar', 'sepex');
                        $delete .= html_writer::end_tag('a'); 
                        $delete .= html_writer::end_tag('td');
                        echo $delete;
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';

echo $OUTPUT->footer();


 