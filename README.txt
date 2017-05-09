As etapas a seguir devem levá-lo a funcionar com
Este código de modelo de módulo.

* NÃO ENTRE EM PÂNICO!

* Descompacte o arquivo e leia este arquivo

* Renomeie o sepex / pasta para o nome do seu módulo (por exemplo, "widget").
  A pasta do módulo deve ser minúscula e não pode conter acentos. Você deve verificar o contrib CVS
  Em http://cvs.moodle.org/contrib/plugins/mod/ para se certificar de que
  Seu nome não é usado por outro módulo. Registrando o plugin
  Name @ http://moodle.org/plugins irá protegê-lo para você.

* Edite todos os arquivos neste diretório e seus subdiretórios e altere
  Todas as instâncias da string "sepex" para o nome do seu módulo
  (Por exemplo, "widget"). Se você estiver usando o Linux, você pode usar o seguinte comando
  $ Find. -tipo f -exec sed -i 's / sepex / widget / g' {} \;
  $ Find. -type f -exec sed -i 's / NEWMODULE / WIDGET / g' {} \;

  Em um mac, use:
  $ Find. -tipo f -exec sed -i 's / sepex / widget / g' {} \;
  $ Find. -tipo f -exec sed -i 's / NEWMODULE / WIDGET / g' {} \;

* Renomeie o arquivo lang / en / sepex.php para lang / en / widget.php
  Onde "widget" é o nome do seu módulo

* Renomeie todos os arquivos no backup / moodle2 / pasta, substituindo "sepex" com
  O nome do seu módulo

  No Linux, você pode executar esta e as etapas anteriores chamando:
  $ Find. -depth -name '* sepex *' -execdir bash -c 'mv -i "$ 1" "$ {1 // sepex / widget}"' bash {} \;

* Coloque a pasta de widgets na pasta / mod do moodle
  diretório.

* Modifique version.php e defina a versão inicial do seu módulo.

* Acesse Configurações> Administração do site> Notificações, você deve encontrar
  As tabelas do módulo foram criadas com sucesso

* Ir para Administração do site> Plug-ins> Módulos de atividades> Gerenciar atividades
  E você deve achar que este sepex foi adicionado à lista de
  Instalados.

* Você pode agora proceder a executar seu próprio código em uma tentativa de desenvolver
  Seu módulo. Você provavelmente vai querer modificar mod_form.php e view.php
  Como um primeiro passo. Verifique db / access.php para adicionar recursos.
  Vá para Configurações> Administração do site> Desenvolvimento> Editor XMLDB
  E modificar as tabelas do módulo.

Recomendamos que você compartilhe seu código e sua experiência - visite http://moodle.org

Boa sorte!
