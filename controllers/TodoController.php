<?php
// File: controllers/TodoController.php

require_once (__DIR__ . '/../models/TodoModel.php');
class TodoController
{
    private static $message = ''; 

    public function index()
    {
        $todoModel = new TodoModel();
        
        $filter = $_GET['filter'] ?? 'all';
        $search = $_GET['search'] ?? '';

        $todos = $todoModel->getAllTodos($filter, $search);
        $message = self::$message;
        self::$message = ''; // Bersihkan pesan setelah ditampilkan
        
        include (__DIR__ . '/../views/TodoView.php');
    }
    
    public function detail()
    {
        $todoModel = new TodoModel();
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $todo = $todoModel->getTodoById($id);
            if ($todo) {
                include (__DIR__ . '/../views/TodoDetailView.php'); 
                return;
            }
        }
        self::$message = 'Error: Todo tidak ditemukan.';
        header('Location: ' . BASE_URL);
        exit;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            
            $todoModel = new TodoModel();
            $result = $todoModel->createTodo($title, $description);

            if ($result === false) {
                self::$message = 'Gagal: Judul "' . htmlspecialchars($title) . '" sudah ada atau terjadi kesalahan database.';
            } else {
                self::$message = 'Sukses: Todo baru berhasil ditambahkan.';
            }
        }
        header('Location: ' . BASE_URL);
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $is_finished = (bool)($_POST['is_finished'] ?? 0); 
            
            $todoModel = new TodoModel();
            $result = $todoModel->updateTodo($id, $title, $description, $is_finished);

            if ($result === false) {
                 self::$message = 'Gagal: Judul "' . htmlspecialchars($title) . '" sudah ada atau terjadi kesalahan database.';
            } else {
                self::$message = 'Sukses: Todo berhasil diperbarui.';
            }
        }
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public function delete()
    {
         if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $todoModel = new TodoModel();
            $todoModel->deleteTodo($id);
            self::$message = 'Sukses: Todo berhasil dihapus.';
        }
        header('Location: ' . BASE_URL);
        exit;
    }
    
    // FUNGSI UNTUK DRAG & DROP AJAX (Kebutuhan 6)
    public function sort()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['order'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
            exit;
        }

        $order = explode(',', $_POST['order']);
        $todoModel = new TodoModel();
        $result = $todoModel->updateSortOrder($order);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Sort order updated.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error during update.']);
        }
        exit;
    }
}