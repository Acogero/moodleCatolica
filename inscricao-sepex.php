<?php
    require_once ("../../config.php");
    $idPoll = required_param('id', PARAM_INT);
    $lang = required_param('lang', PARAM_TEXT);
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Cadastro SEPEX</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content='catolica,superior,universidade,vitoria, Salesiao,salesiana,direito,administração,fisioterapia, sistemas, sites, sites corporativos, sites institucionais'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="css/estilotag.css" rel="stylesheet" type="text/css"/>
    <link href="css/validaSepex.css" rel="stylesheet" type="text/css"/>
    <!-- include js-->
    <script src="js/validaSepex.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
    <!--<script src="teste/summernote.js"></script>-->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
    <script type="text/javascript" src="js/tagsinput.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
  </head>
  
    <body>
        <div class="col-lg-9 col-md-9 col-sm-12" id="envolve-pagina-interna">
            <div class=" box col-lg-12 fadeIn"  id="pagina-interna">
                 
                <!-- /// Inicio do formulário de inscricao ///-->
                <form id="frmInscricao" name="frmInscricao" enctype="multipart/form-data" action="inscricao-sepex-action.php" method="post" class="form-horizontal" role="form">
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12">
                            <h3>
                                <span class="contH2">Formulário de Inscrição de Resumo SEPEX</span>
                                <span class="rightBorder"></span>
                            </h3>
                        </div>
                        <div class="col-sm-12">
                            <h4 style="font-size: 14px; color: red;">Para submeter resumo utilize os navegadores Internet explorer (versão 10 ou superior), Chrome ou Mozilla Firefox</h4>
                        </div>
                    </div> 
                    <input type="hidden" name="acao" value="inscricaoSepex" />     
                    <!-- Linha 1 -->
                    <div class="form-group col-sm-12">
                        <div class="col-sm-6">
                            <label class="control-label">Curso:</label>
                            <select class="form-control" name="codCurso" id="idCurso" title="Curso">
                                <option value="">Selecione</option>
                                    <?
                                        $strSql = "SELECT codCurso, strCurso FROM curso_cadastro WHERE indNivel = 'G' ORDER BY strCurso";
                                        echo gerarOptions($strSql);
                                    ?>             
                            </select>
                            <p class="msg-erro-aux"></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Período:</label>
                            <select name="codPeriodo" title="Periodo" id="idPeriodo" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Primeiro Período</option>
                                <option value="2">Segundo Período</option>
                                <option value="3">Terceiro Período</option>
                                <option value="4">Quarto Período</option>
                                <option value="5">Quinto Período</option>
                                <option value="6">Sexto Período</option>
                                <option value="7">Sétimo Período</option>
                                <option value="8">Oitavo Período</option>
                                <option value="9">Nono Período</option>
                                <option value="10">Décimo Período</option>
                            </select>
                            <p class="msg-erro-aux"></p>
                        </div>
                    </div>      
                    <!-- Linha 2 -->
                    <div class="form-group col-sm-12">
                        <div class="col-sm-6">
                            <label class="control-label">Turno:</label>
                            <select name="indTurno" id="indTurno" class="form-control" title="Periodo">
                                <option value="">Selecione</option>
                                <option value="M">Matutino</option>
                                <option value="N">Noturno</option>
                            </select>
                            <p class="msg-erro-aux"></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Categoria:</label>
                            <select name="codCategoria" id="codCategoria" class="form-control input-form" title="Categoria">
                                <option value="">Selecione</option>
                                    <?
                                    $strSql = "SELECT codCategoria, strCategoria FROM sepex_categoria ORDER BY strCategoria";
                                    echo gerarOptions($strSql);
                                    ?>
                            </select>
                            <p class="msg-erro-aux"></p>
                        </div>
                    </div>  
                    <!-- Linha 3 -->
                    <div class="form-group col-sm-12">
                        <div class="col-sm-5">
                            <label class="control-label">Email do apresentador:</label>
                            <input type="email" class="form-control" placeholder="example@example.com" title="Email do apresentador" id="strEmailApresentador" name="strEmailApresentador">
                            <p class="msg-erro-aux"></p>
                        </div>
                        <div class="col-sm-7">
                            <label class="control-label">Título do Trabalho:</label>
                            <input type="text" class="form-control" title="Título do Trabalho" id="strTitulo" name="strTitulo" placeholder="Apenas as iniciais devem ser maiúsculas">
                            <p class="msg-erro-aux"></p>
                        </div>
                    </div>
                    <!-- Linha 4 -->
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12" id="tagsinput">
                            <label class="control-label">Matricula do(s) autor(es) Apresentador(es)</label>
                            <input type="text" class="form-control" name="strNomeApresentador" id="strNomeApresentador" data-toggle="aviso-alunos" data-placement="top" title="Atenção ao preenchimento dos nomes. Nomes incompletos e/ou que não estejam de acordo com o cadastro do aluno no Portal estão sujeitos a não terem suas ACC's computadas." placeholder="Inserir tags" />  
                            <p>Informe a matricula e pressione <b>"ENTER"</b>.</p>         
                            <p class="msg-erro-aux"></p>
                        </div>
                    </div>
                    <!--Linha 5-->
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12" id="divProfessores">
                            <label class="control-label">Nome completo dos professor(es) orientador(es):</label>
                            <select class="form-control" name="codProfessor" id="idProfessor"/>
                                <option value="">Selecione</option>
                                    <?
                                        PortalUsuario::getProfessores(false,true,false);
                                    ?>
                            </select>
                        </div>
                    </div>    
                    <!--Linha 6-->
                    <div class="form-group col-sm-12 coluna-resumo">
                        <div class="col-sm-12">
                            <label class="control-label">Resumo:</label>
                            <textarea id="strResumo" name="strResumo"></textarea>
                            <p style="margin-top: 10px;">Mínimo 200 e máximo 250 palavras, digitadas em apenas um parágrafo</p>
                            <p class="msg-erro-aux"></p>
                            <p class="msg-alerta-aux"></p>
                        </div>
                    </div>
                    <!--Linha 7-->    
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12   ">
                            <label class="control-label">Palavras-chaves:</label>
                            <input type="text" title="Palavras-chave" id="strTags" name="strTags" class="form-control"/>
                            <p style="margin-top: 10px;">3 (três) palavras-chaves, separadas com "<b>;</b>" (ponto e vírgula)</p>
                            <p class="msg-erro-aux"></p>
                        </div>
                    </div>
                    <!-- botão de envio & animação-->
                    <div class="col-sm-12 coluna-enviar">
                        <button class="btn btn-primary" id="btn-valida">Enviar</button>
                    </div>
                    <div class="col-sm-12 coluna-enviando">
                        <div id="outContato"></div>
                    </div>
                </form>
                <div class="container-fluid" id="container">
                    <div class="row">
                        <div class="container">
                            <div class="col-xs-12 col-sm-12 col-lg-4 col-md-4 ">
                                <a href="http://www.ucv.edu.br/index2.php"><span>Centro Universitário Católico de Vitória</span></a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-4 col-md-4 ">
                                <p>&REG; copyright 2017 Carlos Eduardo Vieira</p>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>  
    </body>
 </html>