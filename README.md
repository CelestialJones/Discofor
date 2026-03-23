# Discofor - Documentacao do Projeto

## Visao Geral

A **Discofor** e uma plataforma web academica voltada para publicacao de artigos, interacao entre utilizadores e debates em tempo real. O sistema permite criar conteudo, acompanhar discussoes e gerir a plataforma por meio de um painel administrativo.

Nesta fase do projeto, a melhoria prioritaria da documentacao e da evolucao funcional e a **publicacao de arquivos PDF como anexo de um artigo** e a **possibilidade de download desse PDF** por utilizadores autorizados.

## Objetivo

O objetivo da Discofor e oferecer um espaco digital para partilha de conhecimento academico, permitindo:

- publicacao de artigos e textos;
- interacao por comentarios, curtidas e debates;
- administracao centralizada do conteudo;
- anexo de PDF ao artigo para leitura complementar ou distribuicao do trabalho completo;
- download do PDF publicado.

## Escopo Desta Versao

O foco desta revisao e **melhorar a documentacao existente** e **acrescentar o suporte funcional a PDFs** sem ampliar o escopo para outros modulos novos.

Entram neste escopo:

- upload de PDF no cadastro e edicao de artigos;
- validacao de tipo e tamanho do arquivo;
- armazenamento seguro do PDF;
- exibicao do PDF na pagina do artigo;
- download do PDF por utilizadores autorizados;
- preparacao do painel administrativo para moderacao de anexos.

Nao entram neste escopo:

- sistema de citacoes;
- novos tipos de ficheiros alem de PDF;
- visualizador interno avancado;
- versao publica de download para visitantes anonimos, salvo decisao futura.

## Tecnologias Utilizadas

- **Backend:** Laravel 11+ com PHP 8.2+
- **Frontend:** Blade Templates + Bootstrap 5 + JavaScript
- **Base de Dados:** MySQL
- **Autenticacao:** Laravel Breeze
- **APIs:** Laravel Sanctum
- **Tempo Real:** Laravel Echo + Pusher
- **Armazenamento de ficheiros:** Laravel Filesystem (`public` ou `private`, conforme regra adotada)
- **Controlo de versao:** Git e GitHub

## Requisitos

### Requisitos Funcionais Atuais

1. Registo, login e autenticacao de utilizadores.
2. Criacao, edicao e remocao de artigos.
3. Publicacao condicionada por aprovacao administrativa quando aplicavel.
4. Comentarios, curtidas e debates em tempo real.
5. Dashboard do utilizador.
6. Painel administrativo para moderacao.

### Requisitos Funcionais da Melhoria PDF

1. O utilizador deve poder anexar **um ficheiro PDF opcional** ao criar um artigo.
2. O utilizador deve poder substituir ou remover o PDF ao editar o artigo.
3. O sistema deve aceitar apenas ficheiros com MIME type `application/pdf`.
4. O sistema deve limitar o tamanho maximo do upload conforme configuracao definida pelo projeto.
5. A pagina do artigo deve indicar quando existe um PDF anexado.
6. O utilizador autenticado deve poder **baixar o PDF** a partir da pagina do artigo.
7. O administrador deve poder moderar artigos com anexos PDF e remover anexos indevidos.

### Requisitos Nao Funcionais

- Interface responsiva para desktop e mobile.
- Validacao rigorosa de uploads.
- Nomes e caminhos de ficheiros tratados com seguranca.
- Organizacao seguindo arquitetura MVC.
- Boa experiencia de uso no fluxo de publicacao.

## Fluxo Principal

1. O utilizador autentica-se na plataforma.
2. Cria um artigo com titulo, conteudo, imagem e tags.
3. Opcionalmente, adiciona um ficheiro PDF ao formulario.
4. O sistema valida o PDF e armazena o ficheiro.
5. O artigo e publicado ou enviado para revisao, de acordo com a regra do perfil.
6. Na pagina do artigo, o sistema apresenta a disponibilidade do anexo.
7. O utilizador autorizado pode descarregar o PDF.

## Arquitetura

O projeto segue a arquitetura MVC do Laravel:

- **Models:** `User`, `Article`, `Comment`, `Like`, `Debate`, `Message`, `Notification`, e a nova entidade `Attachment`.
- **Views:** Blade Templates para feed, formulario, artigo, dashboard e administracao.
- **Controllers:** autenticacao, artigos, comentarios, curtidas, debates, notificacoes e administracao.

### Impacto da Nova Funcionalidade

Para suportar PDF, a arquitetura deve incluir:

- uma entidade propria para anexos;
- validacao dedicada no fluxo de artigos;
- armazenamento de ficheiros pelo Laravel Filesystem;
- rota/controlador para download do PDF;
- exibicao do estado do anexo na interface do artigo.

## Modelagem de Dados Proposta

### Tabela `articles`

Campos relevantes ja existentes:

- `id`
- `user_id`
- `title`
- `slug`
- `content`
- `image`
- `status`
- `is_featured`
- `views`
- `created_at`
- `updated_at`

### Nova Tabela `attachments`

Proposta para a implementacao do anexo PDF:

- `id`
- `article_id` (FK para `articles.id`)
- `disk`
- `file_path`
- `original_name`
- `mime_type`
- `size`
- `created_at`
- `updated_at`

### Relacoes

- Um **utilizador** possui varios **artigos**.
- Um **artigo** pode possuir **um anexo PDF**.
- Um **anexo** pertence a um **artigo**.
- Um **artigo** continua podendo ter varios comentarios, curtidas e debates.

## Modulos do Sistema

### Publicacoes

- criacao e edicao de artigos;
- upload de imagem;
- associacao de tags;
- anexo opcional de PDF;
- download do PDF a partir do artigo.

### Interacoes

- comentarios;
- curtidas;
- notificacoes;
- debates em tempo real.

### Administracao

- aprovacao ou remocao de artigos;
- moderacao de comentarios;
- gestao de utilizadores;
- controlo de artigos com anexos PDF.

## Interfaces Afetadas

### Tela de Criacao de Artigo

Deve passar a incluir:

- campo de upload `Arquivo PDF (opcional)`;
- ajuda visual com formato aceite e tamanho maximo;
- mensagens de erro de validacao especificas para PDF.

### Tela de Edicao de Artigo

Deve permitir:

- manter o PDF atual;
- substituir o ficheiro;
- remover o anexo existente.

### Tela do Artigo

Deve apresentar:

- indicador visual de que o artigo possui PDF;
- nome original do ficheiro, se desejado;
- botao ou link para **Baixar PDF**.

### Painel Administrativo

Deve permitir:

- identificar artigos com PDF;
- remover anexos irregulares;
- apoiar moderacao de conteudo.

## Regras de Validacao Recomendadas

- aceitar apenas extensao e MIME de PDF;
- limitar tamanho maximo de upload;
- impedir upload quando o artigo nao estiver validado corretamente;
- remover ficheiro antigo quando um novo PDF o substituir;
- impedir download de anexos inexistentes ou removidos.

## Seguranca

- protecao CSRF nos formularios;
- autorizacao por policies para criar, editar e descarregar quando necessario;
- armazenamento de ficheiros com nomes controlados pelo sistema;
- validacao server-side do PDF;
- possibilidade de usar disco `private` se o download exigir controlo estrito de acesso.

## Estrutura Atual do Projeto

```text
app/
  Http/Controllers/
  Http/Requests/
  Models/
resources/views/
  articles/
  admin/
routes/
database/
  migrations/
```

## Estado Atual da Base de Codigo

O projeto ja possui:

- fluxo de artigos;
- comentarios e curtidas;
- debates em tempo real;
- dashboard e painel administrativo;
- validacoes basicas para criacao e edicao de artigos;
- upload de imagem em artigos.

O projeto **ainda nao possui implementacao nativa de anexos PDF e download**, e esta documentacao define precisamente essa proxima evolucao.

## Proxima Etapa de Implementacao

Para concretizar esta melhoria, o desenvolvimento deve incluir:

1. migration para a tabela `attachments`;
2. model e relacionamento do anexo com `Article`;
3. ajuste no formulario de criar/editar artigo;
4. validacao do upload PDF;
5. persistencia do ficheiro no storage;
6. rota e controller para download;
7. exibicao do anexo na pagina do artigo;
8. testes do fluxo de upload e download.

## Ambiente de Desenvolvimento

### Requisitos

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js 18+
- npm ou pnpm

### Instalacao

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
npm run dev
```

## Testes

Para executar os testes:

```bash
php artisan test
```

## Conclusao

A Discofor permanece centrada em publicacao academica e interacao social. Nesta revisao, a documentacao passa a contemplar de forma clara a evolucao para **anexar PDFs e permitir o respetivo download**, mantendo o escopo enxuto, coerente com o sistema atual e pronto para a proxima fase de implementacao.
