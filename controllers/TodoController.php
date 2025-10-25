<?php
// File: controllers/TodoController.php

require_once (__DIR__ . '/../models/TodoModel.php');
class TodoController
{
    // Gunakan static property agar pesan bisa dibawa antar-request
    private static $message = ''; 

    public function index()
    {
        $todoModel = new TodoModel();
        
        // Ambil parameter Filter & Search (Kebutuhan 2 & 3)
        $filter = $_GET['filter'] ?? 'all';
        $search = $_GET['search'] ?? '';

        $todos = $todoModel->getAllTodos($filter, $search);
        $message = self::$message; // Kirim pesan ke view
        
        // Include View utama
        include (__DIR__ . '/../views/TodoView.php');
    }
    
    // FUNGSI BARU UNTUK TAMPILAN DETAIL (Kebutuhan 5)
    public function detail()
    {
        $todoModel = new TodoModel();
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $todo = $todoModel->getTodoById($id);
            if ($todo) {
                // Include View detail baru
                include (__DIR__ . '/../views/TodoDetailView.php'); 
                return;
            }
        }
        // Jika ID tidak ditemukan, set pesan error dan redirect
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
            // Status diubah dari 0/1 menjadi TRUE/FALSE
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
}