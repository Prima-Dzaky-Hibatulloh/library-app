<?php 
include '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Ambil kategori
$categoryQuery = "SELECT * FROM categories ORDER BY name ASC";
$categoryStmt = $conn->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT books.id, books.title, books.author, books.published_year, books.genre, categories.name as category 
          FROM books
          LEFT JOIN book_categories ON books.id = book_categories.book_id
          LEFT JOIN categories ON book_categories.category_id = categories.id
          ORDER BY books.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        body { background: #f5f7fa; }
        .navbar { background: linear-gradient(to right, #1e3c72, #2a5298); }
        .navbar-brand, .nav-link { color: #fff !important; }
        .table { background: #fff; border-radius: 10px; }
        .card { box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .btn-primary { background: #1976d2; border: none; }
        .btn-primary:hover { background: #1565c0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="#">📖 Perpustakaan Digital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-bs-toggle="dropdown">📂 Kategori</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="filterCategory('')">Semua</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="#" onclick="filterCategory('<?= $category['name']; ?>')"> <?= $category['name']; ?> </a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="add.php">➕ Tambah Buku</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card p-4">
        <h2 class="mb-4 text-center text-primary">📚 Daftar Buku 📚</h2>
        <table class="table table-striped table-bordered text-center" id="booksTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun Terbit</th>
                    <th>Genre</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book['id']; ?></td>
                        <td><strong><?= $book['title']; ?></strong></td>
                        <td><?= $book['author']; ?></td>
                        <td><?= $book['published_year']; ?></td>
                        <td><span class="badge bg-info text-dark"> <?= $book['genre']; ?> </span></td>
                        <td class="category"> <span class="badge bg-warning text-dark"> <?= $book['category'] ?: '-'; ?> </span></td>
                        <td>
                            <a href="edit.php?id=<?= $book['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="deleteBook(<?= $book['id']; ?>)">🗑️ Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#booksTable').DataTable();
    });

    function filterCategory(category) {
        var table = $('#booksTable').DataTable();
        table.column(5).search(category).draw();
    }

    function deleteBook(bookId) {
        if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            $.ajax({
                url: 'delete.php?id=' + bookId,
                type: 'GET',
                success: function(response) {
                    location.reload();
                }
            });
        }
    }
</script>

</body>
</html>
