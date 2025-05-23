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

        // Verifica se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::showError("O email fornecido não é válido.");
            exit;
        }

        // Verifica se o nome é válido
        if (strlen($name) < 3 || strlen($name) > 50) {
            self::showError("O nome deve ter entre 3 e 50 caracteres.");
            exit;
        }        

        // Verificar se o grupo existe e se o usuário é o criador
        $group = Group::findById($group_id);
        if (!$group || $group['created_by'] != $_SESSION['user_id']) {
            self::showError("Acesso negado.");
            exit;
        }

        // Verifica se o participante já existe
        $existingParticipant = Participant::findByEmailAndGroupId($email, $group_id);
        if ($existingParticipant) {
            self::showError("O participante já está associado a este grupo.", "/group/{$group_id}/settings");
            exit;
        }

        // Adicionar o participante com ou sem associação ao usuário
        Participant::addToGroup($group_id, $name, $email);

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
 
         // Atualizar o participante
         Participant::updateParticipant($participant_id, $name, $email);
 
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
