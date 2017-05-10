<?php

    require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
    require_once(dirname(__FILE__).'/locallib.php');
    require_once('./classes/Form.class.php');
    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */
    global $DB, $CFG, $PAGE;

    $PAGE->set_context(context_system::instance());
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Formulário SEPEX-2017');
    $PAGE->set_heading('Formulário SEPEX-2017');


    define('QRCODE_URL_LINK', "confirmacao.php");
    define('QRCODE_URL', $protocol . $path ."/". QRCODE_URL_LINK);

    echo $OUTPUT->header();
    
    echo $OUTPUT->footer();