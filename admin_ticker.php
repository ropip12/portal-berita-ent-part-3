<?php
// Perbaikan session_start() dengan pengecekan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Tambah ticker baru
if(isset($_POST['tambah_ticker'])){
    $teks = $conn->real_escape_string($_POST['teks']);
    $urutan = intval($_POST['urutan']);
    
    $conn->query("INSERT INTO ticker_news (teks, urutan) VALUES ('$teks', $urutan)");
    $_SESSION['success'] = "Ticker berhasil ditambahkan.";
    header("Location: admin_ticker.php");
    exit();
}

// Edit ticker
if(isset($_POST['edit_ticker'])){
    $id = intval($_POST['id']);
    $teks = $conn->real_escape_string($_POST['teks']);
    $urutan = intval($_POST['urutan']);
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    
    $conn->query("UPDATE ticker_news SET teks='$teks', urutan=$urutan, aktif=$aktif WHERE id=$id");
    $_SESSION['success'] = "Ticker berhasil diperbarui.";
    header("Location: admin_ticker.php");
    exit();
}

// Hapus ticker
if(isset($_GET['hapus_ticker'])){
    $id = intval($_GET['hapus_ticker']);
    $conn->query("DELETE FROM ticker_news WHERE id=$id");
    $_SESSION['success'] = "Ticker berhasil dihapus.";
    header("Location: admin_ticker.php");
    exit();
}

$ticker_result = $conn->query("SELECT * FROM ticker_news ORDER BY urutan ASC, tanggal_dibuat DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ticker - ENT PENS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
        }
        
        body {
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        #sidebar {
            position: fixed;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
        }
        
        #sidebar ul li a {
            padding: 15px 25px;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul li a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
        }
        
        #content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
        }
        
        .stats-card {
            border-left: 4px solid;
        }
        
        .stats-card.primary {
            border-left-color: var(--primary-color);
        }
        
        .stats-card.success {
            border-left-color: #1cc88a;
        }
        
        .stats-card.info {
            border-left-color: #36b9cc;
        }
        
        .stats-card.warning {
            border-left-color: #f6c23e;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        .user-info {
            color: #4e73df;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -var(--sidebar-width);
            }
            
            #content {
                width: 100%;
                margin-left: 0;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content.active {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
        }

        /* Ticker specific styles */
        .ticker-form .form-control {
            border-radius: 0.35rem;
        }
        
        .ticker-form .btn {
            border-radius: 0.35rem;
        }
        
        .table-responsive {
            border-radius: 0.35rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <h3>ENT PENS</h3>
            <p class="mb-0">Admin Panel</p>
        </div>
        
        <ul class="list-unstyled">
            <li>
                <a href="admin.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="admin_ticker.php" class="active">
                    <i class="bi bi-newspaper me-2"></i> Kelola Ticker
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div id="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-light mb-4">
            <div class="container-fluid">
                <button id="sidebarToggle" class="btn btn-link d-md-none">
                    <i class="bi bi-list"></i>
                </button>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="user-info"><?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Kelola Breaking News Ticker</h1>
                <a href="admin.php" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Alert Messages -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Form Tambah Ticker -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Ticker Baru</h6>
                </div>
                <div class="card-body ticker-form">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="teks" class="form-label">Teks Ticker</label>
                                    <input type="text" class="form-control" id="teks" name="teks" required maxlength="255" placeholder="Masukkan teks breaking news">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="urutan" class="form-label">Urutan Tampil</label>
                                    <input type="number" class="form-control" id="urutan" name="urutan" value="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" name="tambah_ticker" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-text">Angka urutan lebih kecil akan ditampilkan lebih awal</div>
                    </form>
                </div>
            </div>
            
            <!-- Daftar Ticker -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Ticker</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="80">Urutan</th>
                                    <th>Teks Ticker</th>
                                    <th width="100">Status</th>
                                    <th width="120">Tanggal</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($ticker_result->num_rows > 0): ?>
                                    <?php while($ticker = $ticker_result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center"><?= $ticker['urutan'] ?></td>
                                        <td><?= htmlspecialchars($ticker['teks']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $ticker['aktif'] ? 'success' : 'secondary' ?>">
                                                <?= $ticker['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($ticker['tanggal_dibuat'])) ?></td>
                                        <td class="text-center action-buttons">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTicker<?= $ticker['id'] ?>" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="?hapus_ticker=<?= $ticker['id'] ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin hapus ticker ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editTicker<?= $ticker['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <input type="hidden" name="id" value="<?= $ticker['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Ticker</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Teks Ticker</label>
                                                            <input type="text" class="form-control" name="teks" value="<?= htmlspecialchars($ticker['teks']) ?>" required maxlength="255">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Urutan</label>
                                                            <input type="number" class="form-control" name="urutan" value="<?= $ticker['urutan'] ?>">
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input" name="aktif" id="aktif<?= $ticker['id'] ?>" <?= $ticker['aktif'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="aktif<?= $ticker['id'] ?>">Aktif</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit_ticker" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                            Belum ada ticker yang ditambahkan
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>