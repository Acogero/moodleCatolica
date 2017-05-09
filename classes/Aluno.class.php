<?php

/**
 * Responsável por manipular dados dos Alunos
 *
 * @author Carlos Eduardo Vieira
 */
class Aluno {
    private $aluno_matricula;
    
    function getAluno_matricula() {
        return $this->aluno_matricula;
    }

    function setAluno_matricula($aluno_matricula) {
        $this->aluno_matricula = $aluno_matricula;
    }

    public function Aluno($dados)
    {
        $this->aluno_matricula = $dados->aluno_matricula;
    }
    
   //         $DB->insert_record("sepex_aluno_projeto", $aluno);
   
}
