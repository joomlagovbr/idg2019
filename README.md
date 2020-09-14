# IDG 2020 (Egov2)
Baseada na IDG 2.0, versão da Identidade Digital do Governo Federal, versão ainda em desenvolvimento, cedida em parceria com Cesar da UFRB de conteúdo fiction apneas como demonstração(ipsum) para montagem de layout.

Se tiverem dificuldades, podem entrar em contato: tiagovtg@gmail.com

- INSTALAÇÃO: Tem um PDF de nome Infograficoportal.pdf, sigam as instruções. ⚙

## Portal padrão em CMS Joomla 3.9.18 06/2020

Sobre esta versão
O Joomla 3.9.18 é recomendado PHP 7.2.+, mas funciona com PHP 5.3+

Acompanhe as atualizações do projeto
Participe do grupo do google chamado Joomla! e-Gov para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.

## Problemas na instalação
Se tiver problemas na instalação e travar no meio, tente alterar as variaveis de ambiente do PHP
Arquivo:
php.ini

Alterações:
max_execution_time=600
;(valor padrão 30, alterado para 600)

max_input_time=1200
;(valor padrão 60, alterado para 1200)

max_input_vars = 6000
;padrão linha comentada, descomentar esta linha
;(valor padrão 1000, alterado para 6000)

memory_limit=1280M
;(valor padrão 128M, alterado para 1280M)

Não precisa de aumentar tanto, mas pode ir testando se quiser, exemplo, memoria padrão é 128M, pode ir subindo 256M,512M, 1024M

## ATENÇÃO
Este projeto visa ser um quickstart para iniciar projetos próprios de portais padrão em CMS Joomla, e atende as principais especificações e módulos recomendados pela Presidência da República, mas não esgota todos os módulos e recomendações citadas nos manuais.

Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere a quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

## ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.

IMPORTANTE: este é um projeto ainda em desenvolvimento, e a disponibilização antecipada visa a atender aos órgãos com maior pressa.
O módulo de agenda de dirigentes da página inicial ainda está pendente de desenvolvimento (está apenas com css).

Desenvolvimento
Comunidade Joomla Calango

Agradecimentos especiais a (ordem alfabética):
Adriano Lima Santos; Aline Cristina Moreira; Flávio Luciano Dias; Lucas Ávila Cosso; Maurício Oliveira; Sandro Oliveira de Jesus; Tiago Garcia
