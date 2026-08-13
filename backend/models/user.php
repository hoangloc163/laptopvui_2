<?php
require_once "database.php";

class user extends database
{
    public function luuuser($hoten, $email, $matKhauMaHoa): int
    {
        $stmt = $this->conn->prepare("INSERT INTO users (hoten, email, matkhau) VALUES (:hoten, :email, :matkhau)");
        $stmt->execute([
            'hoten' => $hoten,
            'email' => strtolower(trim($email)),
            'matkhau' => $matKhauMaHoa,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function emailExists($email): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => strtolower(trim($email))]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function checkuser($email, $matkhau)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Email không tồn tại.";
        }
        if (!password_verify($matkhau, $user['matkhau'])) {
            return "Mật khẩu không đúng.";
        }
        return $user;
    }

    public function layDanhSachLoai(): array
    {
        $stmt = $this->conn->query("SELECT id_loai, ten_loai FROM loai WHERE anhien = 1 ORDER BY thutu, ten_loai");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
