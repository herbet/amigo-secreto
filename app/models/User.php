<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    // Buscar usuário pelo ID
    public static function findById($user_id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM `users`
            WHERE id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar usuário pelo e-mail
    public static function findByEmail($email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM `users`
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Criar um novo usuário
    public static function create($name, $email, $password)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO `users` (name, email, password) 
            VALUES (?, ?, ?)
        ");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$name, $email, $hashed_password]);
        return $pdo->lastInsertId();
    }

    // Atualizar usuário com senha
    public static function updateWithPassword($user_id, $name, $email, $password)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `users`
            SET name = ?, email = ?, password = ?
            WHERE id = ?
        ");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$name, $email, $hashed_password, $user_id]);
    }

    // Atualizar usuário sem alterar a senha
    public static function updateWithoutPassword($user_id, $name, $email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE `users`
            SET name = ?, email = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $user_id]);
    }

    // Verificar se o e-mail já está registrado
    public static function emailExists($email)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM `users`
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
