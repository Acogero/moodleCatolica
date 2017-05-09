@mod @mod_sepex
Feature: A instalação é bem-sucedida
  Para usar este plugin
    Como usuário
    Preciso da instalação para trabalhar

  Cenário: Verifique a descrição geral dos Plugins para o nome deste plugin
     Como eu me registro como "admin"
     E navegar para "Plugins visão geral" em "Administração do site> Plugins"
     Em seguida, o seguinte deve existir na tabela "plugins-painel de controle":
         Nome do plugin |
         | Mod_sepex |
