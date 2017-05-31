<?php

/**
 * Formulario de avaliação dos professsores orientadores
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class FormularioOrientador extends moodleform {
    
    function definition() {
        global $DB, $PAGE;
        
        $mform = $this->_form;         
        $modcontext = $this->_customdata['modcontext'];
        $condicao = $this->_customdata['condicao'];
       
        // -------------------- RESUMO -------------------------
        $mform->addElement('header', 'resumo_header', get_string('resumo','sepex'));
        $resumo = $this->_customdata['resumo'];
        $mform->addElement('editor', 'resumo', get_string('resumo_orientador', 'sepex'), null, array('context' => $modcontext))->setValue( array('text' => $resumo));                
        $mform->addHelpButton('resumo', 'resumo_orientador', 'sepex');
        $mform->setType('resumo', PARAM_RAW);
        
        
        // -------------------- AVALIAÇÃO -------------------------
        $mform->addElement('header', 'avaliacao', get_string('avaliacao','sepex'));
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'condicao', '', get_string('aprovado','sepex'), 1);
        $radioarray[] = $mform->createElement('radio', 'condicao', '', get_string('reprovado','sepex'), 0);       
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        if($condicao != null){
            $mform->setDefault('condicao',$this->_customdata['condicao']);
        }else{
            $mform->setDefault('condicao', 1);
        }
        $mform->addElement('textarea', 'comentario', get_string("comentario", "sepex"), 'wrap="virtual" rows="5" cols="60"');
        $mform->addRule('comentario', get_string('comentario', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('comentario', 'comentario', 'sepex');
        $mform->setType('comentario', PARAM_RAW);
        $mform->setDefault('comentario',$this->_customdata['comentario']);
        
        $this->add_action_buttons();
                
        
    }
    function validation($data, $files) {
        return array();
    }
}
