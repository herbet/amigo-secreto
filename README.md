
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

- **PHP 8.1**
- **MySQL**
- **PhpMyAdmin**
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

2. **Suba os containers com Docker Compose:**

   ```bash
   docker-compose up --build
   ```

3. **Acesse o sistema no navegador:**

   ```text
   http://localhost:8080
   ```

4. **PhpMyAdmin:**

   ```text
   http://localhost:8081
   ```

---

## 🗃️ **Banco de Dados**

As tabelas do banco de dados são geradas automaticamente quando o container MySQL é inicializado. Se precisar executar os scripts manualmente, eles estão no arquivo **`db/init.sql`**.

---

## 🔐 **Login Padrão**

Para facilitar o acesso inicial, você pode registrar um usuário diretamente pelo formulário de registro na página principal.

---

## 🛠️ **Como Personalizar o Projeto para Produção**

Para colocar o sistema em produção, você precisará:

1. Ajustar o arquivo **`config/database.php`** para apontar para um banco de dados de produção.

---

## 📜 **Licença**

Este projeto está disponível sob a licença **MIT**. Você pode usá-lo e modificá-lo como desejar.

---

## ✨ **Contribuições**

Sinta-se à vontade para contribuir com melhorias para este projeto! 💻

1. Faça um **fork** do repositório.
2. Crie uma nova **branch** para suas alterações.
3. Envie um **pull request**.
