<?php

// public/index.php

// Tentukan root aplikasi
define('APP_ROOT', dirname(__DIR__));

// Muat konfigurasi database
$db_config = require APP_ROOT . '/app/config/database.php';

// Muat model Blog
require_once APP_ROOT . '/app/models/Blog.php';

// --- KONEKSI DATABASE ---
$mysqli_conn = new mysqli(
    $db_config['host'],
    $db_config['username'],
    $db_config['password'],
    $db_config['dbname']
);

if ($mysqli_conn->connect_error) {
    die("Koneksi database gagal: " . $mysqli_conn->connect_error);
}

// Inisialisasi model Blog
$blog_model = new Blog($mysqli_conn);

// --- EKSTRAKSI SUBDOMAIN ---
$host = $_SERVER['HTTP_HOST'];
// Ganti 'blog.lumbungdata.com' dengan domain utama Anda yang sebenarnya!
$domain_utama = 'blog.lumbungdata.com';

// Hapus port jika ada (misal: localhost:8080)
$host_without_port = explode(':', $host)[0];
$parts = explode('.', $host_without_port);

$subdomain = '';
$is_subdomain_request = false;

// Logika untuk mengekstrak subdomain:
// Jika jumlah bagian lebih dari 2 (misal: [nama-blog, blog, lumbungdata, com])
// Dan bagian terakhir adalah domain utama Anda (misal: blog.lumbungdata.com)
// Perhatikan: $parts[count($parts)-3] . '.' . $parts[count($parts)-2] . '.' . $parts[count($parts)-1] untuk domain 3 tingkat
if (count($parts) > 3 && ($parts[count($parts)-3] . '.' . $parts[count($parts)-2] . '.' . $parts[count($parts)-1]) === $domain_utama) {
    $subdomain = $parts[0];
    $is_subdomain_request = true;
} else if (count($parts) === 3 && ($parts[count($parts)-2] . '.' . $parts[count($parts)-1]) === $domain_utama) { // Jika domain utama hanya 2 bagian (contoh.com)
     // Ini akan menangani kasus blog.lumbungdata.com tanpa subdomain tambahan di depannya,
     // atau jika ada subdomain seperti www.blog.lumbungdata.com tapi `blog` sudah dianggap sebagai bagian dari domain utama
     // Logika ini perlu disesuaikan jika ingin mengizinkan subdomain di bawah "blog.lumbungdata.com" seperti "blogsaya.blog.lumbungdata.com"
     // dan juga agar "blog.lumbungdata.com" itu sendiri tidak dianggap sebagai subdomain
     $is_subdomain_request = false; // Asumsikan ini adalah domain utama
}

// Abaikan subdomain 'www' karena sering diarahkan ke domain utama
if ($subdomain === 'www') {
    $is_subdomain_request = false;
}

// --- LOGIKA UTAMA ---

$message = ''; // Pesan untuk pengguna (sukses/error)
$message_type = ''; // 'success' atau 'error'

// Tangani permintaan POST untuk membuat blog baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_subdomain_request) {
    $new_subdomain_name = isset($_POST['subdomain_name']) ? strtolower(trim($_POST['subdomain_name'])) : '';
    $new_blog_title = isset($_POST['blog_title']) ? trim($_POST['blog_title']) : '';
    $new_blog_content = isset($_POST['blog_content']) ? trim($_POST['blog_content']) : '';

    // Validasi input
    if (empty($new_subdomain_name) || empty($new_blog_title) || empty($new_blog_content)) {
        $message = "Semua kolom harus diisi.";
        $message_type = 'error';
    } elseif (!preg_match('/^[a-z0-9-]+$/', $new_subdomain_name)) {
        $message = "Subdomain hanya boleh mengandung huruf kecil, angka, dan tanda hubung (-).";
        $message_type = 'error';
    } elseif (in_array($new_subdomain_name, ['www', 'admin', 'api', 'blog'])) { // Tambahkan 'blog' ke daftar cadangan
        $message = "Nama subdomain ini tidak diizinkan.";
        $message_type = 'error';
    } else {
        if ($blog_model->isSubdomainAvailable($new_subdomain_name)) {
            $blog_model->subdomain_name = $new_subdomain_name;
            $blog_model->blog_title = $new_blog_title;
            $blog_model->blog_content = $new_blog_content;

            if ($blog_model->create()) {
                $message = "Blog Anda berhasil dibuat! Kunjungi di: https://" . htmlspecialchars($new_subdomain_name) . "." . htmlspecialchars($domain_utama);
                $message_type = 'success';
            } else {
                $message = "Gagal membuat blog. Silakan coba lagi.";
                $message_type = 'error';
            }
        } else {
            $message = "Subdomain '" . htmlspecialchars($new_subdomain_name) . "' sudah digunakan. Pilih yang lain.";
            $message_type = 'error';
        }
    }
}


// Tentukan view mana yang akan dimuat
if ($is_subdomain_request) {
    // Ini adalah permintaan subdomain (misal: nama-blog.blog.lumbungdata.com)
    if ($blog_model->getBySubdomain($subdomain)) {
        // Blog ditemukan, siapkan data untuk view
        $blog_data = [
            'blog_title' => $blog_model->blog_title,
            'blog_content' => $blog_model->blog_content,
            'created_at' => $blog_model->created_at
        ];
        require_once APP_ROOT . '/app/views/blog.php';
    } else {
        // Blog tidak ditemukan, tampilkan halaman 404
        header("HTTP/1.0 404 Not Found");
        require_once APP_ROOT . '/app/views/404.php';
    }
} else {
    // Ini adalah permintaan ke domain utama (blog.lumbungdata.com)
    // Tampilkan halaman pendaftaran atau halaman utama platform
    require_once APP_ROOT . '/app/views/home.php';
}

// Tutup koneksi database
$mysqli_conn->close();

?>