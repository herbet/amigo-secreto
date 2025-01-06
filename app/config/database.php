<?php
class Database {
    private static $host = 'db';
    private static $dbname = 'amigo_secreto';
    private static $username = 'user';
    private static $password = 'password';
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro ao conectar com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>
