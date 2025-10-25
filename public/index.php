<?php
// File: public/index.php

// Definisikan BASE_URL untuk aset, link, dan redirection
define('BASE_URL', 'http://localhost:8000/'); 

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'index';
}

// Gunakan jalur absolut yang aman untuk Controller
include __DIR__ . '/../controllers/TodoController.php'; 

$todoController = new TodoController();
switch ($page) {
    case 'index':
        $todoController->index();
        break;
    case 'detail': // ROUTE BARU
        $todoController->detail();
        break;
    case 'create':
        $todoController->create();
        break;
    case 'update':
       $todoController->update();
        break;
    case 'delete':
        $todoController->delete();
        break;
}