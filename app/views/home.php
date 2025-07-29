<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Blog Kita - Buat Blog Anda Sendiri!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        form { margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea { width: calc(100% - 22px); padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: #218838; }
        .error-message { color: red; margin-bottom: 10px; }
        .success-message { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Platform Blog Kami!</h1>
        <p>Buat blog unik Anda sendiri dengan subdomain kustom!</p>

        <?php if (!empty($message)): ?>
            <p class="<?php echo ($message_type === 'success' ? 'success-message' : 'error-message'); ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <h2>Buat Blog Baru Anda</h2>
        <form action="/" method="POST">
            <label for="subdomain_name">Subdomain Anda (misal: "myblog" untuk myblog.blog.lumbungdata.com):</label>
            <input type="text" id="subdomain_name" name="subdomain_name" required pattern="[a-z0-9-]+" title="Hanya huruf kecil, angka, dan tanda hubung (-)">.blog.lumbungdata.com<br><br>

            <label for="blog_title">Judul Blog:</label>
            <input type="text" id="blog_title" name="blog_title" required><br><br>

            <label for="blog_content">Konten Blog Awal:</label>
            <textarea id="blog_content" name="blog_content" rows="10" required></textarea><br><br>

            <input type="submit" value="Buat Blog">
        </form>

        <hr>
        <h3>Daftar Blog Contoh:</h3>
        <ul>
            <li><a href="https://contohblog.blog.lumbungdata.com">contohblog.blog.lumbungdata.com</a></li>
            <li><a href="https://myjourney.blog.lumbungdata.com">myjourney.blog.lumbungdata.com</a></li>
            <li><a href="https://resepmasakan.blog.lumbungdata.com">resepmasakan.blog.lumbungdata.com</a></li>
            <li>* Pastikan Anda sudah mengatur DNS wildcard dan konfigurasi server.</li>
        </ul>
    </div>
</body>
</html>