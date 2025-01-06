<?php

require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/BaseController.php';

class GroupController extends BaseController
{
    // Página de configurações do grupo (apenas para o criador)
    public static function groupSettings($group_id)
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Verificar se o usuário é o criador do grupo
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $user_id) {
            self::showError("Acesso negado. Apenas o criador do grupo pode acessar esta página.");
            exit;
        }

        // Obter os participantes do grupo
        $participants = Group::getMembers($group_id);

        // Carregar a view de configurações do grupo
        include './app/views/group_settings.php';
    }

    // Página de detalhes do grupo (apenas para participantes)
    public static function groupDetails($group_id)
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Obter o grupo
        $group = Group::findById($group_id);
        if (!$group) {
            self::showError("Grupo não encontrado.");
            exit;
        }

        // Verificar se o usuário é participante do grupo
        $participant = Participant::findByUserIdAndGroupId($user_id, $group_id);
        if (!$participant) {
            self::showError("Acesso negado. Apenas participantes do grupo podem acessar esta página.");
            exit;
        }

        // Obter informações sobre o amigo secreto
        $secret_friend = null;
        if ($group['draw_completed']) {
            $secret_friend = Group::getUserSecretFriend($group_id, $participant['id']);
        }

        // Carregar a view de detalhes do grupo
        include './app/views/group_details.php';
    }

    // Formulário para criar um grupo
    public static function createForm()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // Carregar a view do formulário de criação
        include './app/views/group_create.php';
    }

    // Criar um novo grupo
    public static function create()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            self::showError("O nome do grupo é obrigatório.");
            exit;
        }

        Group::create($name, $user_id);

        header("Location: /dashboard");
        exit;
    }

    public static function delete()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    
        $group_id = $_POST['group_id'] ?? null;
    
        if (!$group_id) {
            self::showError("ID do grupo não fornecido.");
            exit;
        }
    
        // Verifica se o grupo existe e se pertence ao usuário logado
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $_SESSION['user_id']) {
            self::showError("Grupo não encontrado ou você não tem permissão para excluí-lo.");
            exit;
        }
    
        // Verifica se o sorteio já foi realizado
        if ($group['draw_completed']) {
            self::showError("Não é possível excluir um grupo após o sorteio ser realizado.");
            exit;
        }
    
        // Excluir todos os participantes associados ao grupo
        Participant::deleteByGroupId($group_id);
    
        // Excluir o grupo
        Group::delete($group_id);
    
        header("Location: /dashboard");
        exit;
    }    

    // Realizar o sorteio do grupo
    public static function draw()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $group_id = $_POST['group_id'] ?? null;

        // Verificar se o grupo existe e se o usuário é o criador
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $user_id) {
            self::showError("Acesso negado.");
            exit;
        }

        // Verificar se o sorteio já foi realizado
        if ($group['draw_completed']) {
            self::showError("O sorteio já foi realizado.");
            exit;
        }

        // Realizar o sorteio
        $participants = Group::getMembers($group_id);
        if (count($participants) < 2) {
            self::showError("É necessário pelo menos dois participantes para realizar o sorteio.");
            exit;
        }

        // Embaralhar os participantes
        $ids = array_column($participants, 'id');
        $shuffled_ids = $ids;
        do {
            shuffle($shuffled_ids);
        } while (!Group::isValidDraw($ids, $shuffled_ids));

        // Associar os amigos secretos
        foreach ($ids as $index => $participant_id) {
            $secret_friend_id = $shuffled_ids[$index];
            Group::setSecretFriend($participant_id, $secret_friend_id);
        }

        // Marcar o sorteio como concluído
        Group::markDrawCompleted($group_id);

        header("Location: /group/{$group_id}/settings");
        exit;
    }
}