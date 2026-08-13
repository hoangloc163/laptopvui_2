<?php
require_once "database.php";

class loai extends database
{
    public function detail($idLoai = 0): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM loai WHERE id_loai = :id_loai LIMIT 1");
        $stmt->execute(['id_loai' => (int)$idLoai]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function luuloaisanpham($tenLoai, $thuTu, $anHien): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO loai (ten_loai, thutu, anhien) VALUES (:ten_loai, :thutu, :anhien)"
        );
        $stmt->execute([
            'ten_loai' => $tenLoai,
            'thutu' => (int)$thuTu,
            'anhien' => (int)$anHien,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function isTenLoaiExists($tenLoai): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM loai WHERE ten_loai = :ten_loai");
        $stmt->execute(['ten_loai' => $tenLoai]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function capnhatloai($idLoai, $tenLoai, $thuTu, $anHien): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE loai SET ten_loai = :ten_loai, thutu = :thutu, anhien = :anhien WHERE id_loai = :id_loai"
        );
        return $stmt->execute([
            'ten_loai' => $tenLoai,
            'thutu' => (int)$thuTu,
            'anhien' => (int)$anHien,
            'id_loai' => (int)$idLoai,
        ]);
    }

    public function danhSachLoaiSP($pageNum = 1, $pageSize = 9): PDOStatement
    {
        $offset = (max(1, (int)$pageNum) - 1) * max(1, (int)$pageSize);
        $limit = max(1, (int)$pageSize);
        $stmt = $this->conn->prepare("SELECT * FROM loai ORDER BY thutu, id_loai DESC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function demLoaiSP(): int
    {
        return (int)$this->conn->query("SELECT COUNT(id_loai) FROM loai")->fetchColumn();
    }

    public function demSanPhamTrongLoai($idLoai): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(id_sp) FROM sanpham WHERE id_loai = :id_loai");
        $stmt->execute(['id_loai' => (int)$idLoai]);
        return (int)$stmt->fetchColumn();
    }

    public function deleteLoaiSP($idLoai): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM loai WHERE id_loai = :id_loai");
        return $stmt->execute(['id_loai' => (int)$idLoai]);
    }
}
