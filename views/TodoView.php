<?php
// File: views/TodoView.php
// Variabel $filter, $search, $todos, dan $message berasal dari TodoController::index()
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP - Aplikasi Todolist</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-5">
    <div class="card shadow">
        <div class="card-body">
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Todo List</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTodo">Tambah Data</button>
            </div>
            
            <form method="GET" action="<?= BASE_URL ?>" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select class="form-select" name="filter" onchange="this.form.submit()">
                            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="finished" <?= $filter === 'finished' ? 'selected' : '' ?>>Selesai</option>
                            <option value="unfinished" <?= $filter === 'unfinished' ? 'selected' : '' ?>>Belum Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="search" class="form-control" placeholder="Cari Judul atau Deskripsi..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">Cari</button>
                    </div>
                </div>
            </form>
            <hr />
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Dibuat</th>
                        <th scope="col">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="todo-list-body"> 
                <?php if (!empty($todos)): ?>
                    <?php foreach ($todos as $i => $todo): ?>
                    <tr data-id="<?= $todo['id'] ?>"> 
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($todo['title'] ?? '') ?></td> 
                        <td>
                            <?php if ($todo['is_finished'] ?? false): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Belum Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d F Y - H:i', strtotime($todo['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>?page=detail&id=<?= $todo['id'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
                            
                            <button class="btn btn-sm btn-warning"
                                onclick="showModalEditTodo(
                                    <?= $todo['id'] ?>, 
                                    '<?= htmlspecialchars(addslashes($todo['title'] ?? '')) ?>', 
                                    '<?= htmlspecialchars(addslashes($todo['description'] ?? '')) ?>', 
                                    '<?= ($todo['is_finished'] ?? false) ? '1' : '0' ?>' 
                                )">
                                Ubah
                            </button>
                            
                            <button class="btn btn-sm btn-danger"
                                onclick="showModalDeleteTodo(<?= $todo['id'] ?>, '<?= htmlspecialchars(addslashes($todo['title'] ?? '')) ?>')">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data tersedia!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addTodo" tabindex="-1" aria-labelledby="addTodoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTodoLabel">Tambah Data Todo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?page=create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputTitle" class="form-label">Judul Todo</label>
                        <input type="text" name="title" class="form-control" id="inputTitle"
                            placeholder="Contoh: Belajar membuat aplikasi website sederhana" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputDescription" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" id="inputDescription" rows="3"
                            placeholder="Tulis deskripsi detail todo di sini (Opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editTodo" tabindex="-1" aria-labelledby="editTodoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTodoLabel">Ubah Data Todo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?page=update" method="POST">
                <input name="id" type="hidden" id="inputEditTodoId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inputEditTitle" class="form-label">Judul Todo</label>
                        <input type="text" name="title" class="form-control" id="inputEditTitle"
                            placeholder="Judul Todo" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputEditDescription" class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" id="inputEditDescription" rows="3"
                            placeholder="Deskripsi detail"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="selectEditStatus" class="form-label">Status</label>
                        <select class="form-select" name="is_finished" id="selectEditStatus">
                            <option value="0">Belum Selesai</option>
                            <option value="1">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteTodo" tabindex="-1" aria-labelledby="deleteTodoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTodoLabel">Hapus Data Todo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    Kamu akan menghapus todo <strong class="text-danger" id="deleteTodoTitle"></strong>.
                    Apakah kamu yakin?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="btnDeleteTodo" class="btn btn-danger">Ya, Tetap Hapus</a>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
// Fungsi JavaScript diperbarui untuk menangani Judul dan Deskripsi
function showModalEditTodo(todoId, title, description, is_finished) {
    document.getElementById("inputEditTodoId").value = todoId;
    document.getElementById("inputEditTitle").value = title;
    document.getElementById("inputEditDescription").value = description;
    document.getElementById("selectEditStatus").value = is_finished;
    var myModal = new bootstrap.Modal(document.getElementById("editTodo"));
    myModal.show();
}
function showModalDeleteTodo(todoId, title) {
    document.getElementById("deleteTodoTitle").innerText = title;
    document.getElementById("btnDeleteTodo").setAttribute("href", `?page=delete&id=${todoId}`);
    var myModal = new bootstrap.Modal(document.getElementById("deleteTodo"));
    myModal.show();
}

// KODE UNTUK DRAG AND DROP (SortableJS)
document.addEventListener('DOMContentLoaded', (event) => {
    var todoList = document.getElementById('todo-list-body');
    if (todoList) {
        new Sortable(todoList, {
            animation: 150,
            onEnd: function (evt) {
                var newOrder = [];
                todoList.querySelectorAll('tr').forEach((row, index) => {
                    newOrder.push(row.getAttribute('data-id'));
                });

                fetch('<?= BASE_URL ?>?page=sort', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'order=' + newOrder.join(',')
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log('Urutan berhasil diperbarui.');
                    } else {
                        console.error('Gagal memperbarui urutan:', data.message);
                    }
                })
                .catch(error => console.error('Error AJAX:', error));
            },
        });
    }
});
</script>
</body>
</html>