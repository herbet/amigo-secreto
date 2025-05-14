<?php

class BaseController
{
    // Função para exibir a página de erro
    public static function showError($message, $returnUrl = '/dashboard')
    {
        $message = htmlspecialchars($message); // Sanitizar a mensagem para evitar XSS
        $returnUrl = htmlspecialchars($returnUrl); // Sanitizar a URL para evitar XSS
        include __DIR__ . '/../views/error.php';
        exit;
    }
}
