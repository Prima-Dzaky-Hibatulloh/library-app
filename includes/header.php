<?php
// Mulai session jika diperlukan
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1a237e, #0d47a1);">
    <div class="container">
        <!-- Logo Navbar -->
        <a class="navbar-brand fw-bold text-white" href="/library-app/">
            📚 Perpustakaan
        </a>
        
        <!-- Tombol Toggle (Mobile) -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu Navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold px-3" href="/library-app/books/index.php">
                        📖 Daftar Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold px-3" href="/library-app/books/add.php">
                        ➕ Tambah Buku
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-4">
