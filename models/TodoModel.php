<?php
// File: models/TodoModel.php

require_once (__DIR__ . '/../config.php');

class TodoModel
{
    private $conn;

    public function __construct()
    {
        // Koneksi database yang sudah diperbaiki
        $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . 
DB_USER . " password=" . DB_PASSWORD;
        $this->conn = pg_connect($conn_string);
        
        if (!$this->conn) {
            die('Koneksi database gagal');
        }
    }

    // FUNGSI BARU: Mendapatkan semua todo dengan Filter & Search (Kebutuhan 2 & 3)
    public function getAllTodos($filter_status = 'all', $search_term = '')
    {
        $conditions = [];
        $params = [];
        $param_index = 1;

        // Logika Filter Status
        if ($filter_status === 'finished') {
            $conditions[] = "is_finished = TRUE";
        } elseif ($filter_status === 'unfinished') {
            $conditions[] = "is_finished = FALSE";
        }
        
        // Logika Pencarian
        if (!empty($search_term)) {
            // Pencarian di kolom title DAN description
            $search_condition = "(title ILIKE $" . $param_index++ . " OR description ILIKE $" . $param_index++ . ")";
            $conditions[] = $search_condition;
            $params[] = '%' . $search_term . '%';
            $params[] = '%' . $search_term . '%';
        }

        $query = 'SELECT * FROM todo';

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY updated_at DESC'; 

        $result = pg_query_params($this->conn, $query, $params);
        $todos = [];
        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $todos[] = $row;
            }
        }
        return $todos;
    }
    
    // FUNGSI BARU: Cek Keunikan Judul (Kebutuhan 4)
    public function isTitleUnique($title, $id = null)
    {
        $query = 'SELECT COUNT(*) FROM todo WHERE title = $1';
        $params = [$title];
        $param_index = 2;
        
        if ($id !== null) {
            $query .= ' AND id != $' . $param_index++;
            $params[] = $id;
        }

        $result = pg_query_params($this->conn, $query, $params);
        $count = pg_fetch_result($result, 0, 0);
        return $count == 0;
    }
    
    // FUNGSI CREATE (Diperbarui untuk title dan description)
    public function createTodo($title, $description)
    {
        if (!$this->isTitleUnique($title)) {
            return false; // Judul sudah ada
        }
        
        $query = 'INSERT INTO todo (title, description) VALUES ($1, $2)';
        $result = pg_query_params($this->conn, $query, [$title, $description]);
        return $result !== false;
    }
    
    // FUNGSI UPDATE (Diperbarui untuk title, description, dan is_finished)
    public function updateTodo($id, $title, $description, $is_finished)
    {
        if (!$this->isTitleUnique($title, $id)) {
            return false; // Judul sudah ada
        }
        
        $query = 'UPDATE todo SET title=$1, description=$2, is_finished=$3 WHERE id=$4';
        $result = pg_query_params($this->conn, $query, [$title, $description, $is_finished, $id]);
        return $result !== false;
    }
    
    // FUNGSI BARU: Ambil Detail Todo (Kebutuhan 5)
    public function getTodoById($id)
    {
        $query = 'SELECT * FROM todo WHERE id = $1';
        $result = pg_query_params($this->conn, $query, [$id]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    // FUNGSI DELETE (Tidak Berubah)
    public function deleteTodo($id)
    {
        $query = 'DELETE FROM todo WHERE id=$1';
        $result = pg_query_params($this->conn, $query, [$id]);
        return $result !== false;
    }
}