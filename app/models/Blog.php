<?php

// app/models/Blog.php

class Blog {
    private $conn;
    private $table_name = "blogs";

    public $id;
    public $subdomain_name;
    public $blog_title;
    public $blog_content;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk mendapatkan blog berdasarkan subdomain_name
    public function getBySubdomain($subdomain) {
        $query = "SELECT id, subdomain_name, blog_title, blog_content, created_at, updated_at
                  FROM " . $this->table_name . "
                  WHERE subdomain_name = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $subdomain);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->subdomain_name = $row['subdomain_name'];
            $this->blog_title = $row['blog_title'];
            $this->blog_content = $row['blog_content'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Fungsi untuk mengecek ketersediaan subdomain (untuk pendaftaran)
    public function isSubdomainAvailable($subdomain) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE subdomain_name = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $subdomain);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result->num_rows == 0); // True jika tidak ada, False jika sudah ada
    }

    // Fungsi untuk membuat blog baru (contoh sederhana)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (subdomain_name, blog_title, blog_content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->subdomain_name = htmlspecialchars(strip_tags($this->subdomain_name));
        $this->blog_title = htmlspecialchars(strip_tags($this->blog_title));
        $this->blog_content = $this->blog_content; // Konten bisa lebih kompleks, jadi hati-hati dengan strip_tags

        $stmt->bind_param("sss", $this->subdomain_name, $this->blog_title, $this->blog_content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}