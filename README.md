
# 🎁 Sistema de Amigo Secreto

Um sistema web simples para organizar e gerenciar **grupos de amigo secreto**, desenvolvido em **PHP** utilizando o padrão **MVC**, **Bootstrap** para estilização, e **Docker** para o ambiente de desenvolvimento local.

## 📦 **Funcionalidades Principais**

- ✅ Cadastro de usuários.
- ✅ Login e autenticação.
- ✅ Criação de grupos de amigo secreto.
- ✅ Adição, edição e exclusão de participantes.
- ✅ Realização de sorteio de amigo secreto.
- ✅ Visualização dos detalhes do grupo e do amigo sorteado.
- ✅ Dashboard personalizado para visualizar grupos criados e participados.
- ✅ Página de perfil para editar os dados do usuário.

---

## 🚀 **Tecnologias Utilizadas**

- **PHP 8.3**
- **MySQL 8**
- **PhpMyAdmin**
- **PHPMailer** (envio de e-mails) e **MailHog** (captura de e-mails em desenvolvimento)
- **Bootstrap 5**
- **Docker e Docker Compose**

---

## 🛠️ **Configuração e Execução do Projeto Localmente**

### ✅ **Pré-requisitos**

- **Docker** e **Docker Compose** instalados.

### ⚙️ **Passo a Passo para Executar o Projeto**

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/seu-usuario/amigo-secreto.git
   cd amigo-secreto
   ```

2. **Instale as dependências do Composer** (a pasta `vendor/` não é versionada e o Dockerfile não roda o Composer):

   ```bash
   composer install
   ```

   Sem o Composer instalado na máquina, dá para usar a imagem oficial:

   ```bash
   docker run --rm -v "$(pwd)":/app composer install
   ```

3. **Suba os containers com Docker Compose:**

   ```bash
   docker-compose up --build
   ```

4. **Acesse o sistema no navegador:**

   ```text
   http://localhost:8080
   ```

5. **PhpMyAdmin:**

   ```text
   http://localhost:8081
   ```

6. **MailHog** (e-mails enviados pelo sorteio):

   ```text
   http://localhost:8025
   ```

---

## 🗃️ **Banco de Dados**

O arquivo **`db/init.sql`** é montado em `/docker-entrypoint-initdb.d/` do container MySQL, então as tabelas são criadas automaticamente **na primeira inicialização**, quando a pasta `db/data/` ainda está vazia.

Se o banco já tiver sido inicializado antes (a pasta `db/data/` já existe), o script não roda de novo. Nesse caso, você pode:

- executar o script manualmente (só funciona se as tabelas ainda não existirem):

  ```bash
  docker exec -i amigo-secreto-db mysql -uroot -proot < db/init.sql
  ```

- ou zerar o banco (**apaga todos os dados**): pare os containers, remova a pasta `db/data/` e suba novamente.

---

## 🔐 **Login Padrão**

Para facilitar o acesso inicial, você pode registrar um usuário diretamente pelo formulário de registro na página principal.

---

## 🛠️ **Como Personalizar o Projeto para Produção**

Para colocar o sistema em produção, você precisará:

1. Ajustar o arquivo **`app/config/database.php`** para apontar para um banco de dados de produção.
2. Ajustar o arquivo **`app/helpers/MailHelper.php`** para usar um servidor SMTP real (hoje ele aponta para o MailHog).

---

## 📜 **Licença**

Este projeto está disponível sob a licença **MIT**. Você pode usá-lo e modificá-lo como desejar.

---

## ✨ **Contribuições**

Sinta-se à vontade para contribuir com melhorias para este projeto! 💻

1. Faça um **fork** do repositório.
2. Crie uma nova **branch** para suas alterações.
3. Envie um **pull request**.
