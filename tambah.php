<?php
session_start();
// Perbaikan pengecekan session - sesuaikan dengan login.php
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$judul = $kategori = $isi = $tanggal = "";
$judul_err = $kategori_err = $isi_err = $tanggal_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $judul = trim($_POST["judul"]);
    $kategori = trim($_POST["kategori"]);
    $isi = trim($_POST["isi"]);
    $tanggal = trim($_POST["tanggal"]);

    if(empty($judul)) $judul_err = "Judul wajib diisi";
    if(empty($kategori)) $kategori_err = "Kategori wajib diisi";
    if(empty($isi)) $isi_err = "Isi berita wajib diisi";
    if(empty($tanggal)) $tanggal_err = "Tanggal wajib diisi";

    $gambar = "";
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $gambar = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar;
        
        // Validasi tipe file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
        } else {
            $gambar_err = "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        }
    }

    if(empty($judul_err) && empty($kategori_err) && empty($isi_err) && empty($tanggal_err)){
        $stmt = $conn->prepare("INSERT INTO berita (judul, kategori, isi, tanggal, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $kategori, $isi, $tanggal, $gambar);
        
        if($stmt->execute()){
            $_SESSION['success_message'] = "Berita berhasil ditambahkan!";
            header("Location: admin.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan. Silakan coba lagi.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita - ENT PENS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fc;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .error {
            color: #e74a3b;
            font-size: 0.875rem;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
                <i class="bi bi-newspaper me-2"></i>ENT PENS Admin
            </a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?>
                </span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Berita Baru
                        </h6>
                        <a href="admin.php" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" id="beritaForm">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
                                        <input type="text" name="judul" class="form-control <?php echo !empty($judul_err) ? 'is-invalid' : ''; ?>" 
                                               value="<?php echo htmlspecialchars($judul); ?>" placeholder="Masukkan judul berita">
                                        <div class="error"><?php echo $judul_err; ?></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Isi Berita <span class="text-danger">*</span></label>
                                        <textarea name="isi" class="form-control <?php echo !empty($isi_err) ? 'is-invalid' : ''; ?>" 
                                                  rows="8" placeholder="Tulis isi berita di sini"><?php echo htmlspecialchars($isi); ?></textarea>
                                        <div class="error"><?php echo $isi_err; ?></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="kategori" class="form-control <?php echo !empty($kategori_err) ? 'is-invalid' : ''; ?>" 
                                               value="<?php echo htmlspecialchars($kategori); ?>" placeholder="Contoh: Teknologi, Pendidikan">
                                        <div class="error"><?php echo $kategori_err; ?></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" 
                                            name="tanggal" 
                                            class="form-control <?php echo !empty($tanggal_err) ? 'is-invalid' : ''; ?>" 
                                            value="<?php echo date('Y-m-d'); ?>" 
                                            min="<?php echo date('Y-m-d'); ?>" 
                                            max="<?php echo date('Y-m-d'); ?>" 
                                            readonly>
                                        <div class="error"><?php echo $tanggal_err; ?></div>
                                    </div>

                                    
                                    <div class="mb-4">
                                        <label class="form-label">Gambar</label>
                                        <input type="file" name="gambar" class="form-control" accept="image/*">
                                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                                        <?php if(isset($gambar_err)): ?>
                                            <div class="error"><?php echo $gambar_err; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle me-2"></i>Simpan Berita
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Tips Menulis Berita</h6>
                    <ul class="mb-0">
                        <li>Gunakan judul yang menarik dan deskriptif</li>
                        <li>Pastikan isi berita informatif dan mudah dipahami</li>
                        <li>Pilih kategori yang sesuai dengan konten berita</li>
                        <li>Gunakan gambar yang relevan dengan topik berita</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi form sebelum submit
        document.getElementById('beritaForm').addEventListener('submit', function(e) {
            let isValid = true;
            const judul = document.querySelector('input[name="judul"]');
            const kategori = document.querySelector('input[name="kategori"]');
            const isi = document.querySelector('textarea[name="isi"]');
            const tanggal = document.querySelector('input[name="tanggal"]');
            
            // Reset error states
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.error').forEach(el => el.textContent = '');
            
            // Validasi judul
            if (!judul.value.trim()) {
                judul.classList.add('is-invalid');
                judul.nextElementSibling.textContent = 'Judul wajib diisi';
                isValid = false;
            }
            
            // Validasi kategori
            if (!kategori.value.trim()) {
                kategori.classList.add('is-invalid');
                kategori.nextElementSibling.textContent = 'Kategori wajib diisi';
                isValid = false;
            }
            
            // Validasi isi
            if (!isi.value.trim()) {
                isi.classList.add('is-invalid');
                isi.nextElementSibling.textContent = 'Isi berita wajib diisi';
                isValid = false;
            }
            
            // Validasi tanggal
            if (!tanggal.value) {
                tanggal.classList.add('is-invalid');
                tanggal.nextElementSibling.textContent = 'Tanggal wajib diisi';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
</body>
</html>