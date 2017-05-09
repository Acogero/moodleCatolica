<?php

/**
 * Classe responsável por manipular o formulário sepex
 *
 * @author Carlos Eduardo Vieira
 */

require_once ("../../config.php");

defined('MOODLE_INTERNAL') || die();

class Projeto {
    private $id_projeto;
    private $cod_projeto;
    private $titulo;
    private $resumo;
    private $status;
    private $data_cadastro;
    private $email;
    private $tags;
    private $cod_periodo;
    private $turno;
    private $cod_categoria;
               
    public function getId_projeto() {
        return $this->id_projeto;
    }

    public function getCod_projeto() {
        return $this->cod_projeto;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getResumo() {
        return $this->resumo;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getData_cadastro() {
        return $this->data_cadastro;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTags() {
        return $this->tags;
    }

    public function getCod_periodo() {
        return $this->cod_periodo;
    }

    public function getTurno() {
        return $this->turno;
    }

    public function getCod_categoria() {
        return $this->cod_categoria;
    }

    public function setId_projeto($id_projeto) {
        $this->id_projeto = $id_projeto;
    }

    public function setCod_projeto($cod_projeto) {
        $this->cod_projeto = $cod_projeto;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setResumo($resumo) {
        $this->resumo = $resumo;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setData_cadastro($data_cadastro) {
        $this->data_cadastro = $data_cadastro;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function setCod_periodo($cod_periodo) {
        $this->cod_periodo = $cod_periodo;
    }

    public function setTurno($turno) {
        $this->turno = $turno;
    }

    public function setCod_categoria($cod_categoria) {
        $this->cod_categoria = $cod_categoria;
    }

        
    public function Dados_projeto($dados, $codigo, $dataAtual)
    {             
        $this->cod_projeto = $codigo;
        $this->titulo = $dados->titulo;              
        $this->resumo = $dados->resumo[text];
        $this->data_cadastro = $dataAtual;
        $this->email = $dados->email;
        $this->tags = $dados->tags;
        $this->cod_periodo = $dados->periodo;
        $this->turno = $dados->turno;
        $this->cod_categoria = $dados->cod_categoria;      
        $this->Insert_projeto();
    }    
   
    public function Insert_projeto(){
        global $DB;
        $sql = "INSERT INTO sepex_projeto (cod_projeto, titulo, resumo, data_cadastro, email, tags, cod_periodo, turno, cod_categoria) VALUES('{$this->cod_projeto}','{$this->titulo}','{$this->resumo}','{$this->data_cadastro}','{$this->email}','{$this->tags}','{$this->cod_periodo},'{$this->turno}','{$this->cod_categoria}'');";
        $id = $DB->execute($sql);
        $this->id_projeto = $id;
        return $this->id;
    }   
            

        
       
}
