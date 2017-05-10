<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of formulario
 *
 * @author roots
 */

require_once ("../../config.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once('./classes/Formulario.class.php');

class Avaliacao extends moodleform {
    
    function definition() {
        global $DB, $PAGE;
        
        $mform = $this->_form; 
        $coursecontext = $this->_customdata['coursecontext'];
        $modcontext = $this->_customdata['modcontext'];
        
        $lorenIpsum = 'Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu não só a cinco séculos, como também ao salto para a editoração eletrônica, permanecendo essencialmente inalterado. Se popularizou na década de 60, quando a Letraset lançou decalques contendo passagens de Lorem Ipsum, e mais recentemente quando passou a ser integrado a softwares de editoração eletrônica como Aldus PageMaker.';
        $mform->addElement('static', 'string_01', 'RESUMO', $lorenIpsum);

        $alunos[0] = 'nome_000001';
        $alunos[1] = 'nome_000002';
        $alunos[2] = 'nome_000003';
        $alunos[3] = 'nome_000004';

        
        $mform->addElement('static', 'aluno02', 'Alunos do grupo', null);


        foreach ($alunos as $group) {
            $mform->addElement('advcheckbox', 'aluno', null, $group);
        }

        
        
    }
}