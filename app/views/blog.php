</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog_data['blog_title']); ?> - Blogspot Clone</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        pre { background: #eee; padding: 10px; border-radius: 5px; overflow-x: auto; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($blog_data['blog_title']); ?></h1>
        <hr>
        <div>
            <?php echo nl2br(htmlspecialchars($blog_data['blog_content'])); ?>
        </div>
        <p><small>Dipublikasikan pada: <?php echo date('d M Y H:i', strtotime($blog_data['created_at'])); ?></small></p>
        <p><a href="https://blog.lumbungdata.com">Kembali ke Halaman Utama</a></p>
    </div>
</body>
</html>