<?php
include '../config/database.php';
include '../includes/header.php';

$database = new Database();
$conn = $database->getConnection();

// Ambil daftar kategori
$categoryQuery = "SELECT * FROM categories ORDER BY name ASC";
$categoryStmt = $conn->prepare($categoryQuery);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $published_year = $_POST['published_year'];
    $genre = $_POST['genre'];
    $category_id = $_POST['category'];

    $query = "INSERT INTO books (title, author, published_year, genre) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$title, $author, $published_year, $genre])) {
        $book_id = $conn->lastInsertId();
        $categoryQuery = "INSERT INTO book_categories (book_id, category_id) VALUES (?, ?)";
        $categoryStmt = $conn->prepare($categoryQuery);
        $categoryStmt->execute([$book_id, $category_id]);
        $successMessage = "Buku berhasil ditambahkan!";
    } else {
        $errorMessage = "Gagal menambahkan buku. Coba lagi!";
    }
}
?>

<div class="container mt-4">
    <div class="card shadow-lg p-4 bg-light rounded">
        <h2 class="mb-4 text-center text-primary">📖 Tambah Buku Baru 📖</h2>
        
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $successMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $errorMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="published_year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <input type="text" name="genre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"> <?= $category['name']; ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">💾 Simpan Buku</button>
            <a href="index.php" class="btn btn-secondary btn-lg">🔙 Kembali</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>