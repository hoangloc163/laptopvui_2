<?php
require_once "database.php";

class donhang extends database
{
    public function demDonHang(): int
    {
        return (int)$this->conn->query("SELECT COUNT(id_dh) FROM donhang")->fetchColumn();
    }

    public function tongDoanhThu(): int
    {
        return (int)$this->conn->query(
            "SELECT COALESCE(SUM(soluong * gia), 0) FROM donhangchitiet"
        )->fetchColumn();
    }

    public function danhSachDonHang($pageNum = 1, $pageSize = 10): array
    {
        $offset = (max(1, (int)$pageNum) - 1) * max(1, (int)$pageSize);
        $limit = max(1, (int)$pageSize);
        $stmt = $this->conn->prepare(
            "SELECT dh.id_dh, dh.hoten, dh.email, dh.diachi, dh.dienthoai,
                    COALESCE(SUM(ct.soluong), 0) AS tong_soluong,
                    COALESCE(SUM(ct.soluong * ct.gia), 0) AS tong_tien
             FROM donhang dh
             LEFT JOIN donhangchitiet ct ON ct.id_dh = dh.id_dh
             GROUP BY dh.id_dh, dh.hoten, dh.email, dh.diachi, dh.dienthoai
             ORDER BY dh.id_dh DESC
             LIMIT :offset, :limit"
        );
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function chiTietDonHang($idDh): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT dh.id_dh, dh.hoten, dh.email, dh.diachi, dh.dienthoai,
                    COALESCE(SUM(ct.soluong), 0) AS tong_soluong,
                    COALESCE(SUM(ct.soluong * ct.gia), 0) AS tong_tien
             FROM donhang dh
             LEFT JOIN donhangchitiet ct ON ct.id_dh = dh.id_dh
             WHERE dh.id_dh = :id_dh
             GROUP BY dh.id_dh, dh.hoten, dh.email, dh.diachi, dh.dienthoai
             LIMIT 1"
        );
        $stmt->execute(['id_dh' => (int)$idDh]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function sanPhamTrongDonHang($idDh): array
    {
        $stmt = $this->conn->prepare(
            "SELECT id_sp, ten_sp, soluong, gia, (soluong * gia) AS thanh_tien
             FROM donhangchitiet WHERE id_dh = :id_dh ORDER BY ten_sp"
        );
        $stmt->execute(['id_dh' => (int)$idDh]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
