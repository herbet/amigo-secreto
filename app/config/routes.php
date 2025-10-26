<?php

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/GroupController.php';
require_once __DIR__ . '/../controllers/ParticipantController.php';

// ----------------------
// Rotas relacionadas ao usuário
// ----------------------

// Página inicial
if ($_SERVER['REQUEST_URI'] === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    UserController::index();
    exit;
}

// Página de login
if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    UserController::login();
    exit;
}

// Processar login
if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    UserController::authenticate();
    exit;
}

// Página de registro
if ($_SERVER['REQUEST_URI'] === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    UserController::registerForm();
    exit;
}

// Processar registro
if ($_SERVER['REQUEST_URI'] === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    UserController::register();
    exit;
}

// Fazer logout
if ($_SERVER['REQUEST_URI'] === '/logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    UserController::logout();
    exit;
}

// Página do dashboard
if ($_SERVER['REQUEST_URI'] === '/dashboard' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    UserController::dashboard();
    exit;
}

// Página de perfil
if ($_SERVER['REQUEST_URI'] === '/profile' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    UserController::profile();
    exit;
}

// Atualizar perfil
if ($_SERVER['REQUEST_URI'] === '/profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    UserController::updateProfile();
    exit;
}

// ----------------------
// Rotas relacionadas aos grupos
// ----------------------

// Página de criação de grupo
if ($_SERVER['REQUEST_URI'] === '/group/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    GroupController::groupForm();
    exit;
}

// Processar criação de grupo
if ($_SERVER['REQUEST_URI'] === '/group/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    GroupController::createGroup();
    exit;
}

// Página de configurações do grupo
if (preg_match('/\/group\/(\d+)\/settings/', $_SERVER['REQUEST_URI'], $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    GroupController::groupSettings($matches[1]);
    exit;
}

// Página de detalhes do grupo
if (preg_match('/\/group\/(\d+)\/details/', $_SERVER['REQUEST_URI'], $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    GroupController::groupDetails($matches[1]);
    exit;
}

// Realizar sorteio do grupo
if ($_SERVER['REQUEST_URI'] === '/group/draw' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    GroupController::draw();
    exit;
}

// Excluir grupo
if ($_SERVER['REQUEST_URI'] === '/group/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    GroupController::delete();
    exit;
}

// ----------------------
// Rotas relacionadas aos participantes
// ----------------------

// Adicionar participante
if ($_SERVER['REQUEST_URI'] === '/participant/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ParticipantController::addParticipant();
    exit;
}

// Editar participante
if ($_SERVER['REQUEST_URI'] === '/participant/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ParticipantController::editParticipant();
    exit;
}

// Excluir participante
if ($_SERVER['REQUEST_URI'] === '/participant/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ParticipantController::deleteParticipant();
    exit;
}

// Enviar e-mail individual do participante
if ($_SERVER['REQUEST_URI'] === '/participant/send-email' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ParticipantController::sendEmail();
    exit;
}
