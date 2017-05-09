<?php

/**
 * Responsável por manipular informações dos cursos
 *
 * @author Carlos Eduardo Vieira
 */
class Curso {
    private $cod_curso;
    
    function getCod_curso() {
        return $this->cod_curso;
    }

    function setCod_curso($cod_curso) {
        $this->cod_curso = $cod_curso;
    }

        
    public function Curso($dados)
    {
        $this->cod_curso = $dados->cod_curso;
    }
    //$DB->insert_record("sepex_projeto_curso", $curso);
}
