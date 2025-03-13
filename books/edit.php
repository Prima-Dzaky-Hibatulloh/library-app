<?php
include '../config/database.php';
include '../includes/header.php';

$database = new Database();
$conn = $database->getConnection();

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    die("ID buku tidak valid.");
}

$query = "SELECT * FROM books WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $book_id);
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Buku tidak ditemukan.");
}

$query = "SELECT * FROM categories ORDER BY name";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT category_id FROM book_categories WHERE book_id = :book_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':book_id', $book_id);
$stmt->execute();
$current_category = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $published_year = $_POST['published_year'];
    $genre = $_POST['genre'];
    $category_id = $_POST['category'];

    $query = "UPDATE books SET title = :title, author = :author, published_year = :published_year, genre = :genre WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':published_year', $published_year);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':id', $book_id);
    $stmt->execute();

    $query = "DELETE FROM book_categories WHERE book_id = :book_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':book_id', $book_id);
    $stmt->execute();

    $query = "INSERT INTO book_categories (book_id, category_id) VALUES (:book_id, :category_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':book_id', $book_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    echo "<div class='alert alert-success text-center'>Buku berhasil diperbarui!</div>";
}
?>

<style>
    body {
        background: linear-gradient(to right, #f8fafc, #e3f2fd);
        font-family: 'Poppins', sans-serif;
    }
    .card {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        background: #ffffff;
    }
    .form-control {
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #42a5f5;
        box-shadow: 0px 0px 10px rgba(66, 165, 245, 0.5);
    }
    .btn-primary {
        background: #1e88e5;
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #1565c0;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <h2 class="text-center text-primary">✏️ Edit Buku</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="title" class="form-control" value="<?= $book['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="author" class="form-control" value="<?= $book['author']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="published_year" class="form-control" value="<?= $book['published_year']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <input type="text" name="genre" class="form-control" value="<?= $book['genre']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>" <?= ($category['id'] == $current_category) ? 'selected' : ''; ?>>
                            <?= $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">💾 Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>