<?php

/**
 * Classe responsável por manipular informações sobre os professores
 *
 * @author Carlos Eduardo Vieira
 */
class Professor {
    private $cod_professor;
    private $tipo_professor;
    
    function getCod_professor() {
        return $this->cod_professor;
    }

    function getTipo_professor() {
        return $this->tipo_professor;
    }

    function setCod_professor($cod_professor) {
        $this->cod_professor = $cod_professor;
    }

    function setTipo_professor($tipo_professor) {
        $this->tipo_professor = $tipo_professor;
    }

    public function Professor($dados,$tipo)
    {   
       $this->cod_Professor = $dados->cod_professor;
       $this->tipo_professor =  $tipo;
    
    //$DB->insert_record("sepex_projeto_professor", $professor);
  
    // $DB->insert_record("sepex_projeto_professor", $professor2);
        
    }
    
}
