<?php
include 'koneksi.php';

// Pagination logic
$limit = 9; // Jumlah berita per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total berita
$total_result = $conn->query("SELECT COUNT(*) as total FROM berita");
$total_row = $total_result->fetch_assoc();
$total_berita = $total_row['total'];
$total_pages = ceil($total_berita / $limit);

// Ambil berita dengan pagination
$result = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Berita ENT PENS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2ba7a7ff;
            --secondary-color: #1976d2;
            --accent-color: #ff5722;
            --light-bg: #f5f7f9;
            --dark-bg: #212529;
            --text-dark: #333;
            --text-light: #6c757d;
            --white: #ffffff;
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .top-bar {
            background-color: var(--dark-bg);
            color: var(--white);
            padding: 8px 0;
            font-size: 0.9rem;
        }
        
        .top-bar a {
            color: var(--white);
            text-decoration: none;
            margin-right: 15px;
        }
        
        .top-bar a:hover {
            color: var(--accent-color);
        }
        
        .navbar-brand {
            font-weight: 800;
            color: var(--primary-color) !important;
            font-size: 1.8rem;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-item {
            margin: 0 5px;
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--text-dark) !important;
            position: relative;
            padding: 10px 15px !important;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }
        
        .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15px;
            right: 15px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .search-form {
            position: relative;
        }
        
        .search-form .form-control {
            border-radius: 20px;
            padding-left: 20px;
            padding-right: 40px;
            border: 1px solid var(--border-color);
        }
        
        .search-form .btn {
            position: absolute;
            right: 5px;
            top: 5px;
            background: transparent;
            border: none;
            color: var(--text-light);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1495020689067-958852a7765e?ixlib=rb-4.0.3') center/cover no-repeat;
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 2.8rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Trending News */
        .trending-news {
            background-color: #fff8f6;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
        }
        
        .trending-label {
            font-weight: 700;
            color: var(--primary-color);
            margin-right: 15px;
            white-space: nowrap;
        }
        
        .trending-content {
            overflow: hidden;
            position: relative;
            height: 28px;
        }
        
        .trending-item {
            position: absolute;
            width: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .trending-item.active {
            opacity: 1;
        }
        
        /* News Grid */
        .section-title {
            position: relative;
            margin-bottom: 30px;
            font-weight: 700;
            padding-bottom: 15px;
            color: var(--text-dark);
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 70px;
            height: 4px;
            background: var(--primary-color);
            position: absolute;
            bottom: 0;
            left: 0;
        }
        
        .section-title.center:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        
        @media (max-width: 992px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .news-grid {
                grid-template-columns: 1fr;
            }
        }
        .logo-img {
            height: 40px;
            margin-right: 12px;
        }
        .news-card {
            background: var(--white);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .news-card:hover .card-image img {
            transform: scale(1.05);
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .news-title {
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.4;
            margin-bottom: 12px;
            font-size: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-excerpt {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }
        
        .news-meta {
            font-size: 0.85rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .news-date {
            display: flex;
            align-items: center;
        }
        
        .news-date i {
            margin-right: 5px;
        }
        
        .read-more-btn {
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s;
        }
        
        .read-more-btn:hover {
            color: var(--secondary-color);
        }
        
        .read-more-btn i {
            margin-left: 5px;
            transition: transform 0.3s;
        }
        
        .read-more-btn:hover i {
            transform: translateX(5px);
        }
        
        /* Featured News */
        .featured-news {
            margin-bottom: 40px;
        }
        
        .main-featured {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .main-featured img {
            width: 100%;
            height: 450px;
            object-fit: cover;
        }
        
        .main-featured-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: var(--white);
        }
        
        .main-featured .category-badge {
            top: 20px;
            right: 20px;
        }
        
        .main-featured-title {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 15px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
        
        .main-featured-excerpt {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 20px;
        }
        
        .sidebar-widget {
            background: var(--white);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .widget-title {
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-dark);
        }
        
        .popular-news-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .popular-news-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .popular-news-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .popular-news-img {
            width: 80px;
            height: 60px;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .popular-news-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .popular-news-content {
            flex-grow: 1;
        }
        
        .popular-news-title {
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .popular-news-date {
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .category-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .category-item:last-child {
            border-bottom: none;
        }
        
        .category-link {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }
        
        .category-link:hover {
            color: var(--primary-color);
        }
        
        .category-count {
            background: var(--light-bg);
            color: var(--text-light);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        
        /* Pagination */
        .pagination-container {
            margin-top: 50px;
        }
        
        .pagination {
            justify-content: center;
        }
        
        .page-item {
            margin: 0 5px;
        }
        
        .page-link {
            border-radius: 5px;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            font-weight: 600;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .page-link:hover {
            background-color: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--white);
        }
        
        .page-item.disabled .page-link {
            color: var(--text-light);
        }
        
        /* Footer */
        .footer {
            background-color: var(--dark-bg);
            color: var(--white);
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .footer-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
        }
        
        .footer-contact p {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .footer-contact i {
            margin-right: 15px;
            color: var(--primary-color);
            margin-top: 5px;
        }
        
        .social-links {
            display: flex;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Breaking News Ticker */
        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        .ticker-container {
            width: 100%;
            overflow: hidden;
            background: var(--primary-color);
            color: white;
            padding: 10px 0;
        }
        
        .ticker {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: ticker 30s linear infinite;
        }
        
        .ticker-item {
            display: inline-block;
            padding: 0 30px;
            font-weight: 500;
        }
        
        .ticker-item:before {
            content: "•";
            margin-right: 10px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .main-featured-title {
                font-size: 1.6rem;
            }
            
            .main-featured img {
                height: 350px;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .main-featured-title {
                font-size: 1.4rem;
            }
            
            .main-featured img {
                height: 300px;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
             <a class="navbar-brand" href="#">
                <img src="Logo ENT.png" alt="ENT PENS News Logo" class="logo-img">
                <i class="bi bi-newspaper me-2"></i>ENT PENS News
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Teknologi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Olahraga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Politik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ekonomi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kesehatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hiburan</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="login.php" class="btn btn-primary">
                        <i class="bi bi-lock-fill me-2"></i>Login Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

        <!-- Breaking News Ticker -->
    <div class="ticker-container">
        <div class="ticker">
            <?php
            // Ambil berita ticker dari database
            $ticker_result = $conn->query("SELECT * FROM ticker_news WHERE aktif = 1 ORDER BY urutan ASC, tanggal_dibuat DESC");
            while($ticker_row = $ticker_result->fetch_assoc()):
            ?>
            <span class="ticker-item"><?= htmlspecialchars($ticker_row['teks']) ?></span>
            <?php endwhile; ?>
            
            <?php if($ticker_result->num_rows == 0): ?>
                <!-- Default ticker jika belum ada data -->
                <span class="ticker-item">PENS Meraih Penghargaan Kampus Terinovatif 2023</span>
                <span class="ticker-item">Event Technopreneur 2023 Akan Digelar Bulan Depan</span>
                <span class="ticker-item">Tim Robotika PENS Berhasil Raih Juara di Kompetisi Internasional</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Featured News -->
        <div class="featured-news">
            <h2 class="section-title">Berita Utama</h2>
            
            <?php 
            // Get the latest news as featured
            $featured_result = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 1");
            if ($featured_row = $featured_result->fetch_assoc()):
            ?>
            <div class="main-featured">
                <?php if($featured_row['gambar']): ?>
                <img src="uploads/<?= $featured_row['gambar'] ?>" alt="<?= $featured_row['judul'] ?>">
                <?php endif; ?>
                <span class="category-badge"><?= $featured_row['kategori'] ?></span>
                <div class="main-featured-content">
                    <h3 class="main-featured-title"><?= $featured_row['judul'] ?></h3>
                    <p class="main-featured-excerpt"><?= substr($featured_row['isi'], 0, 200) ?>...</p>
                    <a href="detail.php?id=<?= $featured_row['id'] ?>" class="read-more-btn text-white">
                        Baca selengkapnya <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Latest News -->
                <h2 class="section-title">Berita Terbaru</h2>
                
                <div class="news-grid">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <div class="news-card">
                        <?php if($row['gambar']): ?>
                        <div class="card-image">
                            <img src="uploads/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>">
                            <span class="category-badge"><?= $row['kategori'] ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="news-title"><?= $row['judul'] ?></h3>
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                </span>
                            </div>
                            <p class="news-excerpt"><?= substr($row['isi'], 0, 150) ?>...</p>
                            <a href="detail.php?id=<?= $row['id'] ?>" class="read-more-btn">
                                Baca selengkapnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination-container">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            <?php
                            // Show page numbers
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $start_page + 4);
                            
                            if ($end_page - $start_page < 4) {
                                $start_page = max(1, $end_page - 4);
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Berita Populer</h3>
                        <ul class="popular-news-list">
                            <?php
                            // Mengubah query dari ORDER BY RAND() menjadi ORDER BY tanggal DESC
                            $popular_result = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 5");
                            while($popular_row = $popular_result->fetch_assoc()):
                            ?>
                            <li class="popular-news-item">
                                <?php if($popular_row['gambar']): ?>
                                <div class="popular-news-img">
                                    <img src="uploads/<?= $popular_row['gambar'] ?>" alt="<?= $popular_row['judul'] ?>">
                                </div>
                                <?php endif; ?>
                                <div class="popular-news-content">
                                    <h4 class="popular-news-title">
                                        <a href="detail.php?id=<?= $popular_row['id'] ?>"><?= $popular_row['judul'] ?></a>
                                    </h4>
                                    <div class="popular-news-date">
                                        <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($popular_row['tanggal'])) ?>
                                    </div>
                                </div>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Kategori</h3>
                        <ul class="category-list">
                            <?php
                            $categories_result = $conn->query("SELECT kategori, COUNT(*) as count FROM berita GROUP BY kategori");
                            while($cat_row = $categories_result->fetch_assoc()):
                            ?>
                            <li class="category-item">
                                <a href="#" class="category-link"><?= $cat_row['kategori'] ?></a>
                                <span class="category-count"><?= $cat_row['count'] ?></span>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Tentang ENT PENS News</h5>
                    <p>Portal berita resmi organisasi ENT PENS yang menyajikan informasi terkini dan terpercaya seputar kampus, teknologi, dan berbagai informasi menarik lainnya.</p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Kategori</h5>
                    <ul class="footer-links">
                        <li><a href="#">Teknologi</a></li>
                        <li><a href="#">Olahraga</a></li>
                        <li><a href="#">Politik</a></li>
                        <li><a href="#">Ekonomi</a></li>
                        <li><a href="#">Kesehatan</a></li>
                        <li><a href="#">Hiburan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-title">Tautan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Redaksi</a></li>
                        <li><a href="#">Pedoman Media</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Iklan</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="footer-title">Kontak Kami</h5>
                    <div class="footer-contact">
                        <p><i class="bi bi-geo-alt-fill"></i> Jl. Raya PENS, Sukolilo, Surabaya, Jawa Timur 60111</p>
                        <p><i class="bi bi-envelope-fill"></i> info@entpensnews.id</p>
                        <p><i class="bi bi-telephone-fill"></i> (031) 1234567</p>
                        <p><i class="bi bi-printer-fill"></i> (031) 1234567</p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p class="mb-0">© 2023 ENT PENS News. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Trending news animation
        document.addEventListener('DOMContentLoaded', function() {
            const trendingItems = document.querySelectorAll('.trending-item');
            let currentItem = 0;
            
            if (trendingItems.length > 0) {
                // Show first item
                trendingItems[currentItem].classList.add('active');
                
                // Rotate items every 5 seconds
                setInterval(() => {
                    trendingItems[currentItem].classList.remove('active');
                    currentItem = (currentItem + 1) % trendingItems.length;
                    trendingItems[currentItem].classList.add('active');
                }, 5000);
            }
        });
    </script>
</body>
</html>