<?php

/* Formulário com filtro para definir as salas para cada projeto 
 */

/**
 * Campos de que irão filtar os projetos para serem exibidos na pagina definicaoSala
 *
  * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require_once ("../../../config.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');


class FormularioFiltro extends moodleform{
    function definition() {
        global $DB, $PAGE;
        
        $mform = $this->_form; 
    
        $area = array(
            '' => 'Escolher',
            '1' => 'Ciências Sociais e Aplicadas',
            '2' => 'Exatas',
            '3' => 'Saúde'            
        );
        $mform->addElement('select', 'area_curso', get_string('area', 'sepex'), $area);       
        $mform->addRule('area_curso', get_string('area', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('area_curso', 'area', 'sepex');        
        $mform->setDefault('area_curso',$this->_customdata['area_curso']);
        //TURNO
         $turnos = array(
            '' => 'Escolher',
            'Matutino' => 'Matutino',
            'Noturno' => 'Noturno',
        );
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turnos);        
        $mform->addRule('turno', get_string('turno', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('turno', 'turno', 'sepex');        
        $mform->setDefault('turno',$this->_customdata['turno']);
        
         $categorias = array(
            '' => 'Escolher',
            '1' => 'Egressos',
            '2' => 'Estágios',
            '3' => 'Iniciação Científica',
            '4' => 'Inovação',
            '5' => 'Projeto de Extensão',
            '6' => 'Projeto Integrador',
            '7' => 'Responsábilidade Social',
            '8' => 'Temas Livres',
            '9' => 'Trabalho de Conclusão de Curso',
            '10' => 'Mostra de Vídeos',
            '11' => 'Concurso de Fotografia'
        ); 
        
        $mform->addElement('select', 'cod_categoria', get_string('categoria', 'sepex'), $categorias);       
        $mform->addRule('cod_categoria', get_string('categoria', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('cod_categoria', 'categoria', 'sepex');        
        $mform->setDefault('cod_categoria',$this->_customdata['cod_categoria']);
        
        $this->add_action_buttons($cancel = true, $submitlabel = get_string('listarprojetos', 'sepex'));
    }
    
    function validation($data, $files) {
        return array();
    }
    
}
