<?php

class BaseController
{
    // Função para exibir a página de erro
    public static function showError($message)
    {
        $message = htmlspecialchars($message); // Sanitizar a mensagem para evitar XSS
        include __DIR__ . '/../views/error.php';
        exit;
    }
}
