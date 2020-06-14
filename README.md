# IDG 2020 (Egov2)
Baseada na IDG 2.0, versão da Identidade Digital do Governo Federal, versão ainda em desenvolvimento, cedida em parceria com Cesar da UFRB de conteúdo fiction apneas como demonstração(ipsum) para montagem de layout.

Se tiverem dificuldades, podem entrar em contato: tiagovtg@gmail.com

- DADOS DE ACESSO essa versão é auto instalável, crie seu banco, user, senha e use a versão sample_padrao_egov para gerar o conteudo.

Portal padrão em CMS Joomla 3.9.18 06/2020

Sobre esta versão
O Joomla 3.9.18 é recomendado PHP 7.2.+, mas funciona com PHP 5.3+

Acompanhe as atualizações do projeto
Participe do grupo do google chamado Joomla! e-Gov para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.

ATENÇÃO
Este projeto visa ser um quickstart para iniciar projetos próprios de portais padrão em CMS Joomla, e atende as principais especificações e módulos recomendados pela Presidência da República, mas não esgota todos os módulos e recomendações citadas nos manuais.

Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere a quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.

Configuração inicial
Altere o arquivo configuration.php manualmente, até que um novo instalador seja gerado.
Personalize o arquivo BANCO-DE-DADOS.sql substituindo "idg2019_" pelo prefixo de banco de dados desejado. OBS: existe 2 arquivos de Banco de dados atualemente, o mais atual é o Base-de-Dados-Atualizada.sql

Depois, é necessário alterar parâmetros do arquivo configuration.php como host, user, password, db e dbprefix.

Um instalador do template em separado não foi gerado porque sua correta exibição depende dos menus criados e dos módulos configurados.

IMPORTANTE: este é um projeto ainda em desenvolvimento, e a disponibilização antecipada visa a atender aos órgãos com maior pressa.
O módulo de agenda de dirigentes da página inicial ainda está pendente de desenvolvimento (está apenas com css).

Posteriormente será gerado um instalador automatico, quando a versão estiver estavel.

Desenvolvimento
Comunidade Joomla Calango

Agradecimentos especiais a (ordem alfabética):
Adriano Lima Santos; Aline Cristina Moreira; Flávio Luciano Dias; Lucas Ávila Cosso; Maurício Oliveira; Sandro Oliveira de Jesus; Tiago Garcia
