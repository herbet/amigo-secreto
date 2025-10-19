<?php

// Iniciar a sessão
session_start();

// Configuração das rotas
require_once __DIR__ . '/app/config/routes.php';
require_once __DIR__ . '/vendor/autoload.php';

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
//$dotenv->load();
