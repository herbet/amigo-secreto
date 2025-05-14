<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/BaseController.php';

class UserController extends BaseController
{
    // Página inicial (landing page)
    public static function index()
    {
        //session_start();
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit;
        }

        include './app/views/index.php';
    }

    // Exibe o formulário de login
    public static function login()
    {
        //session_start();
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit;
        }

        include './app/views/login.php';
    }

    // Processa a autenticação
    public static function authenticate()
    {
        //session_start();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            self::showError("E-mail e senha são obrigatórios.");
            exit;
        }

        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            self::showError("E-mail ou senha inválidos.");
            exit;
        }

        // Login bem-sucedido
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: /dashboard");
        exit;
    }

    // Exibe o formulário de registro
    public static function registerForm()
    {
        //session_start();
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit;
        }

        include './app/views/register.php';
    }

    // Processa o registro de um novo usuário
    public static function register()
    {
        //session_start();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            self::showError("Todos os campos são obrigatórios.");
            exit;
        }

        if ($password !== $confirm_password) {
            self::showError("As senhas não coincidem.");
            exit;
        }

        if (User::findByEmail($email)) {
            self::showError("Este e-mail já está registrado.");
            exit;
        }

        $user_id = User::create($name, $email, $password);

        // Login automático após registro
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;

        header("Location: /dashboard");
        exit;
    }

    // Faz logout do usuário
    public static function logout()
    {
        //session_start();
        session_destroy();
        header("Location: /");
        exit;
    }

    // Exibe o dashboard do usuário logado
    public static function dashboard()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        // Busca informações do usuário
        $email = User::findById($user_id)['email'];
        if (!$email) { 
            self::showError("E-mail não encontrado.");
            exit;            
        }

        // Buscar grupos criados pelo usuário
        $created_groups = Group::findByCreator($user_id);

        // Buscar grupos nos quais o usuário está participando
        $participating_groups = Group::findGroupsByParticipant($email);

        include './app/views/dashboard.php';
    }

    // Exibe a página de perfil do usuário
    public static function profile()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $user = User::findById($user_id);

        include './app/views/profile.php';
    }

    // Atualiza os dados do perfil do usuário
    public static function updateProfile()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email)) {
            self::showError("Nome e e-mail são obrigatórios.", "/profile");
            exit;
        }

        if (!empty($password) || !empty($confirm_password)) {
            if ($password !== $confirm_password) {
                self::showError("As senhas não coincidem.", "/profile");
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            User::updateWithPassword($user_id, $name, $email, $hashed_password);
        } else {
            User::updateWithoutPassword($user_id, $name, $email);
        }

        // Atualizar a sessão com o novo nome
        $_SESSION['user_name'] = $name;

        // Definir mensagem de sucesso na sessão
        $_SESSION['success_message'] = "Perfil atualizado com sucesso!";        

        header("Location: /profile");
        exit;
    }
}
