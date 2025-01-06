<?php

require_once __DIR__ . '/../config/database.php';

class Participant
{
    // Buscar um participante pelo ID
    public static function findById($participant_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM `participants`
            WHERE id = ?
        ");
        $stmt->execute([$participant_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar um participante pelo e-mail
    public static function findByEmail($email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM `participants`
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar participante pelo ID do usuário e ID do grupo
    public static function findByUserIdAndGroupId($user_id, $group_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT p.*
            FROM `participants` p
            INNER JOIN `group_members` gm ON p.id = gm.participant_id
            WHERE p.user_id = ? AND gm.group_id = ?
        ");
        $stmt->execute([$user_id, $group_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Adicionar participante ao grupo
    public static function addToGroup($group_id, $name, $email, $user_id = null)
    {
        $pdo = Database::connect();

        // Adicionar participante à tabela `participants` (se necessário)
        $stmt = $pdo->prepare("
            INSERT INTO `participants` (name, email, user_id)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)
        ");
        $stmt->execute([$name, $email, $user_id]);

        // Obter o ID do participante
        $participant_id = $pdo->lastInsertId();

        // Adicionar participante à tabela `group_members`
        $stmt = $pdo->prepare("
            INSERT INTO `group_members` (group_id, participant_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$group_id, $participant_id]);
    }

    // Atualizar informações de um participante
    public static function updateParticipant($participant_id, $name, $email, $user_id = null)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `participants`
            SET name = ?, email = ?, user_id = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $user_id, $participant_id]);
    }

    // Excluir participante do grupo
    public static function deleteFromGroup($participant_id, $group_id)
    {
        $pdo = Database::connect();

        // Excluir da tabela `group_members`
        $stmt = $pdo->prepare("
            DELETE FROM `group_members`
            WHERE group_id = ? AND participant_id = ?
        ");
        $stmt->execute([$group_id, $participant_id]);

        // Verificar se o participante pertence a outros grupos
        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM `group_members`
            WHERE participant_id = ?
        ");
        $stmt->execute([$participant_id]);

        // Se o participante não pertence a outros grupos, excluí-lo da tabela `participants`
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("
                DELETE FROM `participants`
                WHERE id = ?
            ");
            $stmt->execute([$participant_id]);
        }
    }

    // Excluir todos os participantes associados a um grupo específico
    public static function deleteByGroupId($group_id)
    {
        $pdo = Database::connect();

        // Excluir participantes da tabela `group_members` primeiro
        $stmt = $pdo->prepare("
            DELETE FROM `group_members`
            WHERE group_id = ?
        ");
        $stmt->execute([$group_id]);

        // Excluir participantes da tabela `participants` que não estão em outros grupos
        $stmt = $pdo->prepare("
            DELETE p
            FROM `participants` p
            LEFT JOIN `group_members` gm ON p.id = gm.participant_id
            WHERE gm.participant_id IS NULL
        ");
        $stmt->execute();
    }    
}
