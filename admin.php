<?php
session_start();
// Perbaikan pengecekan session - sesuaikan dengan login.php
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
$result = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ENT PENS</title>
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
                <a href="#" class="active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="admin_ticker.php">
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
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a href="tambah.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah Berita</a>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Berita</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                            $total_news = $conn->query("SELECT COUNT(*) as total FROM berita")->fetch_assoc();
                                            echo $total_news['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-newspaper fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Berita Hari Ini</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                            $today = date('Y-m-d');
                                            $today_news = $conn->query("SELECT COUNT(*) as total FROM berita WHERE DATE(tanggal) = '$today'")->fetch_assoc();
                                            echo $today_news['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Kategori</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                            $categories = $conn->query("SELECT COUNT(DISTINCT kategori) as total FROM berita")->fetch_assoc();
                                            echo $categories['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-tags fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Admin</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                            $categories = $conn->query("SELECT COUNT(*) as total FROM admin")->fetch_assoc();
                                            echo $categories['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-person-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6>
                    <div>
                        <input type="text" class="form-control form-control-sm" placeholder="Cari berita..." id="searchInput">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">no</th>
                                    <th>Judul</th>
                                    <th width="120">Kategori</th>
                                    <th width="120">Tanggal</th>
                                    <th width="100">Gambar</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1; while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['judul'] ?></td>
                                    <td><span class="badge bg-info"><?= $row['kategori'] ?></span></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td class="text-center">
                                        <?php if($row['gambar']): ?>
                                            <img src="uploads/<?= $row['gambar'] ?>" width="60" class="img-thumbnail">
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <a href="#" class="btn btn-info btn-sm" title="Pratinjau">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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

        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[1].textContent.toLowerCase();
                const category = row.cells[2].textContent.toLowerCase();
                
                if (title.includes(searchText) || category.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>