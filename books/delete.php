<?php
include '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    die("ID buku tidak valid.");
}

// Hapus kategori buku terlebih dahulu
$query = "DELETE FROM book_categories WHERE book_id = :book_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':book_id', $book_id);
$stmt->execute();

// Hapus buku dari tabel books
$query = "DELETE FROM books WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $book_id);
$stmt->execute();

header("Location: ../index.php?message=Buku+berhasil+dihapus");
exit;