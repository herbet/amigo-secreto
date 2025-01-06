<?php

require_once __DIR__ . '/../config/database.php';

class Group
{
    // Buscar um grupo pelo ID
    public static function findById($group_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT 
                g.id, 
                g.name, 
                g.created_by, 
                g.draw_completed, 
                u.name AS created_by_name
            FROM `groups` g
            LEFT JOIN `users` u ON g.created_by = u.id
            WHERE g.id = ?
        ");
        $stmt->execute([$group_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar todos os grupos criados por um usuário
    public static function findByCreator($user_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM `groups`
            WHERE created_by = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar grupos nos quais um usuário está participando
    public static function findGroupsByParticipant($user_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT DISTINCT g.*
            FROM `groups` g
            INNER JOIN `group_members` gm ON g.id = gm.group_id
            INNER JOIN `participants` p ON gm.participant_id = p.id
            WHERE p.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Criar um novo grupo
    public static function create($name, $created_by)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO `groups` (name, created_by) 
            VALUES (?, ?)
        ");
        $stmt->execute([$name, $created_by]);
        return $pdo->lastInsertId();
    }

    // Função para excluir um grupo por ID
    public static function delete($group_id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM `groups` WHERE id = :id");
        $stmt->execute(['id' => $group_id]);
    }    

    // Obter participantes do grupo
    public static function getMembers($group_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT p.* 
            FROM `participants` p
            INNER JOIN `group_members` gm ON p.id = gm.participant_id
            WHERE gm.group_id = ?
        ");
        $stmt->execute([$group_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Definir o amigo secreto para um participante
    public static function setSecretFriend($participant_id, $secret_friend_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `group_members`
            SET secret_friend_id = ?
            WHERE participant_id = ?
        ");
        $stmt->execute([$secret_friend_id, $participant_id]);
    }

    // Marcar o sorteio como concluído
    public static function markDrawCompleted($group_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `groups`
            SET draw_completed = 1
            WHERE id = ?
        ");
        $stmt->execute([$group_id]);
    }

    // Verificar se o sorteio é válido (ninguém tirou a si mesmo)
    public static function isValidDraw($ids, $shuffled_ids)
    {
        foreach ($ids as $index => $id) {
            if ($id == $shuffled_ids[$index]) {
                return false; // Sorteio inválido
            }
        }
        return true; // Sorteio válido
    }

    // Obter o amigo secreto de um participante
    public static function getUserSecretFriend($group_id, $participant_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT p.*
            FROM `participants` p
            INNER JOIN `group_members` gm ON p.id = gm.secret_friend_id
            WHERE gm.group_id = ? AND gm.participant_id = ?
        ");
        $stmt->execute([$group_id, $participant_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
