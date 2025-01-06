<?php

require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/BaseController.php';

class ParticipantController extends BaseController
{
    // Adicionar participante ao grupo
    public static function addParticipant()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $group_id = $_POST['group_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($group_id) || empty($name) || empty($email)) {
            self::showError("Todos os campos são obrigatórios.");
            exit;
        }

        // Verificar se o grupo existe e se o usuário é o criador
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $_SESSION['user_id']) {
            self::showError("Acesso negado.");
            exit;
        }

        // Verificar se já existe um usuário com esse e-mail
        $existing_user = User::findByEmail($email);

        // Adicionar o participante com ou sem associação ao usuário
        if ($existing_user) {
            Participant::addToGroup($group_id, $name, $email, $existing_user['id']);
        } else {
            Participant::addToGroup($group_id, $name, $email, null);
        }

        header("Location: /group/{$group_id}/settings");
        exit;
    }
 
     // Processar edição de participante
     public static function editParticipant()
     {
         //session_start();
         if (!isset($_SESSION['user_id'])) {
             header("Location: /login");
             exit;
         }
 
         // Dados recebidos do formulário
         $participant_id = $_POST['participant_id'] ?? null;
         $group_id = $_POST['group_id'] ?? null;
         $name = trim($_POST['name'] ?? '');
         $email = trim($_POST['email'] ?? '');
 
         if (!$participant_id || !$group_id || empty($name) || empty($email)) {
             self::showError("Todos os campos são obrigatórios.");
         }
 
         // Verificar se o e-mail pertence a um usuário já cadastrado
         $user = User::findByEmail($email);
         $user_id = $user ? $user['id'] : null;
 
         // Atualizar o participante
         Participant::updateParticipant($participant_id, $name, $email, $user_id);
 
         // Redirecionar para a página de configurações do grupo
         header("Location: /group/$group_id/settings");
         exit;
     }

    // Excluir participante do grupo
    public static function deleteParticipant()
    {
        //session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $participant_id = $_POST['participant_id'] ?? null;
        $group_id = $_POST['group_id'] ?? null;

        if (empty($participant_id) || empty($group_id)) {
            self::showError("ID do participante e do grupo são obrigatórios.");
            exit;
        }

        // Verificar se o grupo existe e se o usuário é o criador
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $_SESSION['user_id']) {
            self::showError("Acesso negado.");
            exit;
        }

        // Excluir o participante
        Participant::deleteFromGroup($participant_id, $group_id);

        header("Location: /group/{$group_id}/settings");
        exit;
    }
}
