<?php
include 'koneksi.php';

// Ambil kategori dari query string
if(isset($_GET['kategori'])){
    $kategori = $_GET['kategori'];
    $stmt = $conn->prepare("SELECT * FROM berita WHERE kategori=? ORDER BY tanggal DESC");
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Kategori <?= htmlspecialchars($kategori) ?> - ENT PENS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-color: #fd7e14;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
            padding-top: 56px;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 10px 10px;
        }
        
        .news-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            height: 100%;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .news-card img {
            height: 200px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .news-card:hover img {
            transform: scale(1.05);
        }
        
        .news-card .card-body {
            padding: 1.5rem;
        }
        
        .news-card .card-title {
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .news-meta {
            color: var(--secondary-color);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        
        .news-meta i {
            margin-right: 0.3rem;
            color: var(--accent-color);
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--accent-color);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 500;
            z-index: 10;
        }
        
        .read-more-btn {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .read-more-btn:hover {
            color: #0a58ca;
        }
        
        .read-more-btn i {
            margin-left: 0.3rem;
            transition: var(--transition);
        }
        
        .read-more-btn:hover i {
            transform: translateX(3px);
        }
        
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        
        .back-btn {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 30px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .back-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .footer {
            background-color: var(--dark-bg);
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 2rem 0;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-newspaper me-2"></i>ENT PENS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            Kategori
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kategori.php?kategori=Teknologi">Teknologi</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=Olahraga">Olahraga</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=Politik">Politik</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=Kesehatan">Kesehatan</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=Ekonomi">Ekonomi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Berita Kategori: <?= htmlspecialchars($kategori) ?></h1>
            <p class="lead">Temukan berita terkini seputar <?= htmlspecialchars($kategori) ?> di sini</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($kategori) ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if($result->num_rows > 0): ?>
            <div class="row g-4">
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="news-card card h-100">
                        <?php if($row['gambar']): ?>
                        <div class="position-relative">
                            <img src="uploads/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['judul'] ?>">
                            <span class="category-badge"><?= $row['kategori'] ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $row['judul'] ?></h5>
                            <div class="news-meta">
                                <div><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                <div><i class="far fa-clock"></i> <?= date('H:i', strtotime($row['tanggal'])) ?> WIB</div>
                            </div>
                            <p class="card-text flex-grow-1"><?= substr(strip_tags($row['isi']), 0, 120) ?>...</p>
                            <a href="#" class="read-more-btn mt-auto">
                                Baca selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-folder-open"></i>
                <h3 class="mb-3">Belum ada berita di kategori ini</h3>
                <p class="text-muted mb-4">Maaf, saat ini belum ada berita yang tersedia untuk kategori <?= htmlspecialchars($kategori) ?>.</p>
                <a href="index.php" class="back-btn btn">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold">ENT PENS</h5>
                    <p>Portal berita terkini dan terpercaya yang menyajikan informasi aktual dari berbagai kategori.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold">Kategori Berita</h5>
                    <ul class="list-unstyled">
                        <li><a href="kategori.php?kategori=Teknologi" class="text-white text-decoration-none">Teknologi</a></li>
                        <li><a href="kategori.php?kategori=Olahraga" class="text-white text-decoration-none">Olahraga</a></li>
                        <li><a href="kategori.php?kategori=Politik" class="text-white text-decoration-none">Politik</a></li>
                        <li><a href="kategori.php?kategori=Kesehatan" class="text-white text-decoration-none">Kesehatan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Teknik Kimia, Keputih, Surabaya</li>
                        <li><i class="fas fa-phone me-2"></i> (031) 1234567</li>
                        <li><i class="fas fa-envelope me-2"></i> info@entpens.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 ENT PENS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>