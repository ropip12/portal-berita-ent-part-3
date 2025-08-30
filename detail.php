<?php
include 'koneksi.php';

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
    $id = trim($_GET['id']);
    
    $sql = "SELECT * FROM berita WHERE id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        $param_id = $id;
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $judul = $row['judul'];
                $kategori = $row['kategori'];
                $isi = $row['isi'];
                $tanggal = $row['tanggal'];
                $gambar = $row['gambar'];
            } else{
                header("Location: index.php");
                exit();
            }
        }
        $stmt->close();
    }
    $conn->close();
} else{
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul) ?> - ENT PENS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto+Slab:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ent-primary: #0d6efd;
            --ent-primary-dark: #0a58ca;
            --ent-secondary: #6c757d;
            --ent-accent: #fd7e14;
            --ent-accent-dark: #dc6500;
            --ent-dark: #2c3e50;
            --ent-light: #f8f9fa;
            --ent-success: #198754;
            --ent-gradient: linear-gradient(135deg, var(--ent-primary) 0%, var(--ent-primary-dark) 100%);
            --ent-gradient-accent: linear-gradient(135deg, var(--ent-accent) 0%, var(--ent-accent-dark) 100%);
        }
        .logo-img {
            height: 40px;
            margin-right: 12px;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .navbar {
            background: var(--ent-gradient) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }
        
        .news-hero {
            background: var(--ent-gradient);
            color: white;
            padding: 4rem 0 6rem;
            margin-bottom: -40px;
            position: relative;
            overflow: hidden;
        }
        
        .news-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,128L48,117.3C96,107,192,85,288,112C384,139,480,213,576,218.7C672,224,768,160,864,138.7C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: center bottom;
        }
        
        .news-content {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }
        
        .news-title {
            font-family: 'Roboto Slab', serif;
            font-weight: 700;
            color: var(--ent-dark);
            margin-bottom: 1.2rem;
            line-height: 1.3;
            font-size: 2.5rem;
            border-left: 5px solid var(--ent-accent);
            padding-left: 1.5rem;
        }
        
        .news-meta {
            color: var(--ent-secondary);
            margin-bottom: 2rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .news-image-container {
            position: relative;
            margin-bottom: 2.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .news-image {
            width: 100%;
            transition: transform 0.5s ease;
        }
        
        .news-image:hover {
            transform: scale(1.03);
        }
        
        .news-image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .news-image-container:hover .news-image-caption {
            opacity: 1;
        }
        
        .news-body {
            line-height: 1.9;
            font-size: 1.15rem;
            color: #444;
            text-align: justify;
        }
        
        .news-body p {
            margin-bottom: 1.8rem;
        }
        
        .news-body p:first-of-type:first-letter {
            initial-letter: 3;
            font-weight: 700;
            color: var(--ent-primary);
            margin-right: 0.5rem;
            line-height: 1;
            font-size: 4.5rem;
            float: left;
        }
        
        .category-badge {
            background: var(--ent-gradient-accent);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(253, 126, 20, 0.3);
        }
        
        .back-btn {
            background: var(--ent-gradient);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
        }
        
        .back-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.35);
        }
        
        .social-share {
            border-top: 1px solid #e9ecef;
            padding-top: 2rem;
            margin-top: 3rem;
        }
        
        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .social-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .bg-facebook { background: #3b5998; }
        .bg-twitter { background: #1da1f2; }
        .bg-linkedin { background: #0077b5; }
        .bg-whatsapp { background: #25d366; }
        
        .news-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }
        
        .author-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--ent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .related-news {
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 1px solid #e9ecef;
        }
        
        .news-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .news-card-img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        
        .news-card-body {
            padding: 1.5rem;
        }
        
        .news-card-title {
            font-family: 'Roboto Slab', serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .news-card-meta {
            font-size: 0.85rem;
            color: var(--ent-secondary);
        }
        
        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }
        
        .tag {
            background: #e9ecef;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            color: var(--ent-secondary);
            transition: all 0.3s;
        }
        
        .tag:hover {
            background: var(--ent-primary);
            color: white;
            cursor: pointer;
        }
        
        .comment-section {
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 1px solid #e9ecef;
        }
        
        .comment-card {
            border-radius: 12px;
            padding: 1.5rem;
            background: #f8f9fa;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        @media (max-width: 768px) {
            .news-content {
                padding: 1.8rem;
            }
            
            .news-title {
                font-size: 1.8rem;
                padding-left: 1rem;
                border-left-width: 4px;
            }
            
            .news-body p:first-of-type:first-letter {
                font-size: 3.5rem;
            }
            
            .news-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .news-content {
                padding: 1.8rem;
                margin-top: -15px; 
            }
            
            .news-hero {
                padding: 3.5rem 0 5.5rem; 
                margin-bottom: -25px; 
        }
        
        /* Animation effects */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadein {
            animation: fadeIn 0.8s ease forwards;
        }
    }
    
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light py-3">
        <div class="container">
             <a class="navbar-brand" href="#">
                <img src="Logo ENT.png" alt="ENT PENS News Logo" class="logo-img">
                <i class="bi bi-newspaper me-2"></i>ENT PENS News
            </a>
            <a href="index.php" class="back-btn">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="container mb-5" style="margin-top: 20px;">
        <div class="news-content animate-fadein">
            <h1 class="news-title"><?= htmlspecialchars($judul) ?></h1>
            
            <div class="news-meta">
                <span class="category-badge"><?= htmlspecialchars($kategori) ?></span>
                <span><i class="bi bi-calendar me-1"></i> <?= date('d M Y', strtotime($tanggal)) ?></span>
            </div>
            
            <?php if($gambar): ?>
            <div class="news-image-container">
                <img src="uploads/<?= $gambar ?>" class="news-image" alt="<?= htmlspecialchars($judul) ?>">
                <div class="news-image-caption">Sumber: ENT PENS</div>
            </div>
            <?php endif; ?>
            
            <div class="news-body">
                <?php 
                // Format teks berita dengan paragraf yang benar
                $paragraphs = preg_split('/\n\s*\n/', $isi);
                foreach ($paragraphs as $paragraph) {
                    if (!empty(trim($paragraph))) {
                        echo '<p>' . nl2br(htmlspecialchars(trim($paragraph))) . '</p>';
                    }
                }
                ?>
            </div>
            
            <div class="social-share">
                <p class="fw-bold mb-3">Bagikan berita ini:</p>
                <a href="#" class="social-icon bg-facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="social-icon bg-twitter">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="#" class="social-icon bg-linkedin">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="#" class="social-icon bg-whatsapp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>
            
            <div class="news-footer">
                <div class="author-info">
                    <div class="author-avatar">EP</div>
                    <div>
                        <p class="mb-0 fw-bold">Tim Redaksi ENT PENS</p>
                        <p class="mb-0 small text-muted">Editor</p>
                    </div>
                </div>
                
                <a href="index.php" class="back-btn">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
            
            
                
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">ENT PENS News</h5>
                    <p>Portal berita resmi ENT PENS yang menyajikan informasi terkini dan terpercaya seputar teknologi, elektronika, dan jaringan.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="social-icon bg-facebook" style="width: 40px; height: 40px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon bg-twitter" style="width: 40px; height: 40px;">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="social-icon bg-linkedin" style="width: 40px; height: 40px;">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="social-icon bg-whatsapp" style="width: 40px; height: 40px;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Teknologi</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Jaringan</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Elektronika</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Berita</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Artikel</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tutorial</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Event</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Politeknik Elektronika Negeri Surabaya</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@entpens.ac.id</li>
                        <li class="mb-2"><i class="bi bi-phone me-2"></i> (031) 1234567</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2023 ENT PENS News. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">EET and Network Team - Politeknik Elektronika Negeri Surabaya</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi untuk elemen saat scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate-fadein');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.visibility = 'visible';
                        entry.target.classList.add('animate-fadein');
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => {
                el.style.visibility = 'hidden';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>