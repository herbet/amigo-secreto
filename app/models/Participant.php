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
    public static function findByUserEmailAndGroupId($email, $group_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT p.*
            FROM `participants` p
            INNER JOIN `group_members` gm ON p.id = gm.participant_id
            WHERE p.email = ? AND gm.group_id = ?
        ");
        $stmt->execute([$email, $group_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Adicionar participante ao grupo
    public static function addToGroup($group_id, $name, $email)
    {
        $pdo = Database::connect();

        // Adicionar participante à tabela `participants` (se necessário)
        $stmt = $pdo->prepare("
            INSERT INTO `participants` (name, email)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)
        ");
        $stmt->execute([$name, $email]);

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
    public static function updateParticipant($participant_id, $name, $email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `participants`
            SET name = ?, email = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $participant_id]);
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
