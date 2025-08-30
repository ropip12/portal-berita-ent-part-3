<?php
session_start();
// Perbaikan pengecekan session - sesuaikan dengan login.php
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Validasi parameter ID
if(!isset($_GET['id']) || empty($_GET['id'])){
    $_SESSION['error'] = "ID berita tidak valid.";
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']); // Sanitasi input

// Periksa apakah berita ada sebelum menghapus
$check_query = $conn->prepare("SELECT gambar FROM berita WHERE id = ?");
$check_query->bind_param("i", $id);
$check_query->execute();
$result = $check_query->get_result();

if($result->num_rows === 0){
    $_SESSION['error'] = "Berita tidak ditemukan.";
    header("Location: admin.php");
    exit();
}

$data = $result->fetch_assoc();

// Hapus file gambar jika ada
if($data['gambar'] && file_exists("uploads/".$data['gambar'])){
    unlink("uploads/".$data['gambar']);
}

// Hapus data dari database
$delete_query = $conn->prepare("DELETE FROM berita WHERE id = ?");
$delete_query->bind_param("i", $id);

if($delete_query->execute()){
    $_SESSION['success'] = "Berita berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus berita: " . $conn->error;
}

header("Location: admin.php");
exit();
?>