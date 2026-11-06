# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Visão geral

Aplicação web de amigo secreto em PHP 8.3 puro (sem framework), MySQL 8, Bootstrap 5 via CDN e PHPMailer para envio de e-mail. Todos os textos da interface, comentários e mensagens de commit são em português do Brasil — mantenha novos textos e comentários em pt-BR.

## Comandos

```bash
composer install            # vendor/ está no .gitignore; o Dockerfile NÃO executa o composer
docker-compose up --build   # app + MySQL + phpMyAdmin + MailHog
```

- App: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (root/root)
- MailHog (visualizar e-mails enviados): http://localhost:8025
- MySQL: localhost:3306, banco `amigo_secreto`, usuário `user` / `password`

A raiz do projeto é montada como volume no container PHP, então alterações no código têm efeito imediato, sem rebuild.

Não há suíte de testes, linter nem etapa de build. Use `php -l <arquivo>` para checar sintaxe e valide o comportamento manualmente pelo navegador.

**Schema:** o `db/init.sql` é montado em `/docker-entrypoint-initdb.d/` e só roda quando o MySQL inicializa com `db/data/` vazio. Alterações no schema não são aplicadas a um banco já existente: rode o SQL manualmente (`docker exec -i amigo-secreto-db mysql -uroot -proot < db/init.sql`, ou pelo phpMyAdmin) ou apague `db/data/` (no .gitignore) para zerar o banco. Como o `init.sql` usa `CREATE TABLE` sem `IF NOT EXISTS`, rodá-lo de novo sobre um banco existente falha.

## Arquitetura

**Fluxo da requisição:** o `.htaccess` do Apache reescreve tudo para `index.php` → `session_start()` → `app/config/routes.php`, que é uma lista simples de blocos `if` comparando `$_SERVER['REQUEST_URI']` + método HTTP, chamando um método estático do controller e depois `exit`. Para criar uma rota, adicione um bloco `if` nesse arquivo.
- As rotas comparam a URI exata (`===`), então query strings quebram o match; parâmetros de caminho usam `preg_match` (ex.: `/group/(\d+)/settings`).
- O `routes.php` é incluído *antes* do `vendor/autoload.php` no `index.php`, e toda rota termina com `exit`, então o autoload do Composer nunca chega a rodar. Dependências precisam ser carregadas com `require_once` explícito (veja como `app/helpers/MailHelper.php` inclui diretamente os arquivos-fonte do PHPMailer).

**Controllers** (`app/controllers/`) são classes com métodos estáticos que estendem `BaseController`. Cada ação repete sua própria verificação de autenticação (`$_SESSION['user_id']`, senão redireciona para `/login`) e de permissão (`$group['created_by'] != $_SESSION['user_id']`). Mensagens de erro/sucesso passam por `self::showError($msg, $returnUrl)` / `self::showSuccess(...)`, que renderizam `error.php`/`success.php` e encerram a execução. Não há proteção contra CSRF.

**Models** (`app/models/`) são métodos estáticos que obtêm um PDO compartilhado via `Database::connect()` (`app/config/database.php`, credenciais fixas no código; o host do banco é o nome do serviço `db` no compose) e retornam arrays associativos. Os nomes das tabelas ficam entre crases porque `groups` é palavra reservada do MySQL.

**Views** (`app/views/`) são templates PHP simples incluídos pelos controllers com `include`, por isso leem as variáveis locais do controller (`$group`, `$participants`, …). Os includes usam caminhos relativos ao diretório atual, como `./app/views/...`, que só funcionam porque o cwd é a raiz do projeto.

## Modelo de domínio

- `users` são as contas (login). `participants` são entradas de nome+e-mail adicionadas aos grupos pelo criador do grupo; **não** têm chave estrangeira para `users`. O usuário logado é associado às suas participações pelo **e-mail** (`Participant::findByEmailAndGroupId`, `Group::findGroupsByParticipant`).
- `group_members` liga grupos e participantes e guarda o resultado do sorteio em `secret_friend_id`.
- Só o criador gerencia o grupo (`/group/{id}/settings`); os participantes veem o resultado em `/group/{id}/details`.
- Sorteio (`GroupController::draw`): embaralha os membros até ninguém tirar a si mesmo, grava `secret_friend_id`, envia e-mail a cada participante via `MailHelper` (SMTP para `mailhog:1025`) e marca `groups.draw_completed = 1`. Depois do sorteio, editar/excluir participantes e excluir o grupo ficam bloqueados; o criador pode reenviar e-mails individuais (`/participant/send-email`).

Peculiaridades conhecidas ao mexer nessa área:
- `participants.email` não tem índice UNIQUE, então o `ON DUPLICATE KEY UPDATE` em `Participant::addToGroup` nunca dispara — cada inclusão cria uma nova linha de participante.
- `Group::setSecretFriend` atualiza `group_members` filtrando apenas por `participant_id` (sem filtro por `group_id`).
