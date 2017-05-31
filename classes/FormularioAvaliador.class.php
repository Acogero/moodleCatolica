<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormularioAvaliador
 *
 * @author Lucas
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->dirroot . '/course/moodleform_mod.php');

class FormularioAvaliador extends moodleform {

    function definition() {
        global $DB, $PAGE;

        $mform = $this->_form;        
        $cod_categoria = $this->_customdata['cod_categoria'];
        //--Carregar constantes categoria
        $egresos = get_string('egressos', 'sepex');
        $estagios = get_string('estagios', 'sepex');
        $iniciacao = get_string('iniciacao', 'sepex');
        $inovacao = get_string('inovacao', 'sepex');
        $extensao = get_string('extensao', 'sepex');
        $integrador = get_string('integrador', 'sepex');        
        $temaslivres = get_string('temaslivres', 'sepex');        
        $video = get_string('video', 'sepex');
        $fotografia = get_string('fotografia', 'sepex');
                            
        //-----------Definicao dos placeholders--------------
        if($cod_categoria == $egresos || $cod_categoria == $iniciacao || $cod_categoria == $extensao || $cod_categoria == $temaslivres){
            $placeholder = '25 pontos';
        }elseif($cod_categoria == $estagios || $cod_categoria == $integrador || $cod_categoria == $inovacao){
            $placeholder = '20 pontos';
        }        
        
        if($cod_categoria == $video){
            $placeresumo = '15 pontos';
        }elseif($cod_categoria == $fotografia){
            $placeresumo = '10 pontos';
        }else{
            $placeresumo = '20 pontos';
        }
        


        
        //CAMPOS DE AVALIAÇÃO DO RESUMO PELO AVALIADOR
        $mform->addElement('header', 'resumo_orientador', get_string('resumo','sepex'),array('size' => '15'));               
             
        //Qualidade da redação e organização do texto        
        $mform->addElement('text', 'resumo1', get_string('qualidade_resumo', 'sepex'), array('placeholder'=> $placeresumo, 'size' => '15'));
        $mform->addRule('resumo1', get_string('qualidade_resumo', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('resumo1', 'qualidade_resumo', 'sepex');
        $mform->setType('resumo1', PARAM_RAW);
        
        if($cod_categoria != $video && $cod_categoria != $fotografia){
            //Objetivos claros        
            $mform->addElement('text', 'resumo2', get_string('objetivos_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
            $mform->addRule('resumo2', get_string('objetivos_resumo', 'sepex'), 'required', null, 'client');
            $mform->addHelpButton('resumo2', 'objetivos_resumo', 'sepex');
            $mform->setType('resumo2', PARAM_RAW);

            //Descrição clara da metodologia        
            $mform->addElement('text', 'resumo3', get_string('metodologia_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
            $mform->addRule('resumo3', get_string('metodologia_resumo', 'sepex'), 'required', null, 'client');
            $mform->addHelpButton('resumo3', 'metodologia_resumo', 'sepex');
            $mform->setType('resumo3', PARAM_RAW);

            //Qualidade dos resultados
            $mform->addElement('text', 'resumo4', get_string('resultados_resumo', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
            $mform->addRule('resumo4', get_string('resultados_resumo', 'sepex'), 'required', null, 'client');
            $mform->addHelpButton('resumo4', 'resultados_resumo', 'sepex');
            $mform->setType('resumo4', PARAM_RAW);

            //Adequação da conclusão aos objetivos propostos
            $mform->addElement('text', 'resumo5', get_string('conclusao_objetivos', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));
            $mform->addRule('resumo5', get_string('conclusao_objetivos', 'sepex'), 'required', null, 'client');
            $mform->addHelpButton('resumo5', 'conclusao_objetivos', 'sepex');
            $mform->setType('resumo5', PARAM_RAW);
        
            //Total
            $mform->addElement('text', 'resumo6', get_string('total_resumo', 'sepex'), array('placeholder'=> '100 pontos', 'size' => '15'));                
            $mform->setType('resumo6', PARAM_RAW);
        }
        //CAMPOS DE AVALIAÇÃO DA APRESENTAÇÃO PELO AVALIADOR
        
        $mform->addElement('header', 'header_apresentacao', get_string('header_apresentacao','sepex'));                        
        if($cod_categoria != $video && $cod_categoria != $fotografia){
            $mform->addElement('text', 'apresentacao1', get_string('exposicao_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
            $mform->addHelpButton('apresentacao1', 'exposicao_apresentacao', 'sepex');
            $mform->setType('apresentacao1', PARAM_RAW);

            $mform->addElement('text', 'apresentacao2', get_string('tempo_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
            $mform->addHelpButton('apresentacao2', 'tempo_apresentacao', 'sepex');
            $mform->setType('apresentacao2', PARAM_RAW);

            $mform->addElement('text', 'apresentacao3', get_string('conhecimento_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
            $mform->addHelpButton('apresentacao3', 'conhecimento_apresentacao', 'sepex');
            $mform->setType('apresentacao3', PARAM_RAW);

            $mform->addElement('text', 'apresentacao4', get_string('relevancia_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));        
            $mform->addHelpButton('apresentacao4', 'relevancia_apresentacao', 'sepex');
            $mform->setType('apresentacao4', PARAM_RAW);

            if($cod_categoria == $integrador ):
                $mform->addElement('text', 'apresentacao5', get_string('interdisc_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));            
                $mform->addHelpButton('apresentacao5', 'interdisc_apresentacao', 'sepex');
                $mform->setType('apresentacao5', PARAM_RAW);
            elseif($cod_categoria == $estagios):
                $mform->addElement('text', 'apresentacao5', get_string('valorizacao_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));           
                $mform->addHelpButton('apresentacao5', 'valorizacao_apresentacao', 'sepex');
                $mform->setType('apresentacao5', PARAM_RAW);
            elseif($cod_categoria == $inovacao):
                $mform->addElement('text', 'apresentacao5', get_string('viabilidade_apresentacao', 'sepex'), array('placeholder'=> $placeholder, 'size' => '15'));            
                $mform->addHelpButton('apresentacao5', 'viabilidade_apresentacao', 'sepex');
                $mform->setType('apresentacao5', PARAM_RAW);
            endif;
        }elseif($cod_categoria == $video){
            $mform->addElement('text', 'apresentacao1', get_string('qualidade_video', 'sepex'), array('placeholder'=> '30 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao1', 'qualidade_video', 'sepex');
            $mform->setType('apresentacao1', PARAM_RAW);

            $mform->addElement('text', 'apresentacao2', get_string('originalidade_video', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao2', 'originalidade_video', 'sepex');
            $mform->setType('apresentacao2', PARAM_RAW);

            $mform->addElement('text', 'apresentacao3', get_string('exposicao_apresentacao', 'sepex'), array('placeholder'=> '15 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao3', 'exposicao_apresentacao', 'sepex');
            $mform->setType('apresentacao3', PARAM_RAW);

            $mform->addElement('text', 'apresentacao4', get_string('coerencia_video', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao4', 'coerencia_video', 'sepex');
            $mform->setType('apresentacao4', PARAM_RAW);
              
        }elseif($cod_categoria == $fotografia){
            $mform->addElement('text', 'apresentacao1', get_string('adequacao_foto', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao1', 'adequacao_foto', 'sepex');
            $mform->setType('apresentacao1', PARAM_RAW);

            $mform->addElement('text', 'apresentacao2', get_string('qualidade_foto', 'sepex'), array('placeholder'=> '10 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao2', 'qualidade_foto', 'sepex');
            $mform->setType('apresentacao2', PARAM_RAW);

            $mform->addElement('text', 'apresentacao3', get_string('originalidade_video', 'sepex'), array('placeholder'=> '10 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao3', 'originalidade_video', 'sepex');
            $mform->setType('apresentacao3', PARAM_RAW);

            $mform->addElement('text', 'apresentacao4', get_string('emocao_foto', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao4', 'emocao_foto', 'sepex');
            $mform->setType('apresentacao4', PARAM_RAW);
            
            $mform->addElement('text', 'apresentacao5', get_string('exposicao_apresentacao', 'sepex'), array('placeholder'=> '10 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao5', 'exposicao_apresentacao', 'sepex');
            $mform->setType('apresentacao5', PARAM_RAW);
            
            $mform->addElement('text', 'apresentacao6', get_string('coerencia_foto', 'sepex'), array('placeholder'=> '20 pontos', 'size' => '15'));        
            $mform->addHelpButton('apresentacao6', 'coerencia_foto', 'sepex');
            $mform->setType('apresentacao6', PARAM_RAW);
        }
        
        //total
        $mform->addElement('text', 'total_apresentacao', get_string('total_apresentacao', 'sepex'), array('placeholder'=> '100 pontos', 'size' => '15'));               
        $mform->setType('total_apresentacao', PARAM_RAW);
        
        $this->add_action_buttons();
    }

    function validation($data, $files) {
        return array();
    }

}
