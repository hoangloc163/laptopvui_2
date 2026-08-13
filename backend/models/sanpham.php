<?php
require_once "database.php";

class sanpham extends database
{
    public function detail($idSp = 0): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT id_sp, id_loai, ten_sp, gia, gia_km, hinh, soluotxem, ngay, hot, anhien, mota
             FROM sanpham WHERE id_sp = :id_sp LIMIT 1"
        );
        $stmt->execute(['id_sp' => (int)$idSp]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function tangLuotXem($idSp): void
    {
        $stmt = $this->conn->prepare("UPDATE sanpham SET soluotxem = COALESCE(soluotxem, 0) + 1 WHERE id_sp = :id_sp");
        $stmt->execute(['id_sp' => (int)$idSp]);
    }

    public function sanphamTrongLoai($idLoai = 0, $pageNum = 1, $pageSize = 9): array
    {
        $offset = (max(1, (int)$pageNum) - 1) * max(1, (int)$pageSize);
        $limit = max(1, (int)$pageSize);
        $stmt = $this->conn->prepare(
            "SELECT id_sp, id_loai, ten_sp, gia, gia_km, hinh, ngay
             FROM sanpham
             WHERE id_loai = :id_loai AND anhien = 1
             ORDER BY ngay DESC
             LIMIT :offset, :limit"
        );
        $stmt->bindValue(':id_loai', (int)$idLoai, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTenLoai($idLoai = 0): ?string
    {
        $stmt = $this->conn->prepare("SELECT ten_loai FROM loai WHERE id_loai = :id_loai LIMIT 1");
        $stmt->execute(['id_loai' => (int)$idLoai]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['ten_loai'] ?? null;
    }

    public function demSPTrongLoai($idLoai = 0): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(id_sp) FROM sanpham WHERE id_loai = :id_loai AND anhien = 1");
        $stmt->execute(['id_loai' => (int)$idLoai]);
        return (int)$stmt->fetchColumn();
    }

    public function layListLoai(): array
    {
        $stmt = $this->conn->query("SELECT id_loai, ten_loai, thutu, anhien FROM loai WHERE anhien = 1 ORDER BY thutu, ten_loai");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTatCaLoai(): array
    {
        $stmt = $this->conn->query("SELECT id_loai, ten_loai, thutu, anhien FROM loai ORDER BY thutu, ten_loai");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sanphamXemNhieu($soSp = 9): array
    {
        $stmt = $this->conn->prepare(
            "SELECT id_sp, id_loai, ten_sp, gia, gia_km, hinh, soluotxem
             FROM sanpham WHERE anhien = 1
             ORDER BY soluotxem DESC, id_sp DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', max(1, (int)$soSp), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sanphamNoiBat($soSp = 9): array
    {
        $stmt = $this->conn->prepare(
            "SELECT id_sp, id_loai, ten_sp, gia, gia_km, hinh, ngay
             FROM sanpham WHERE hot = 1 AND anhien = 1
             ORDER BY ngay DESC, id_sp DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', max(1, (int)$soSp), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function taoDonHang($hoten, $email, $diachi, $dienthoai, array $cart): int
    {
        $this->conn->beginTransaction();
        try {
            $stmtOrder = $this->conn->prepare(
                "INSERT INTO donhang (hoten, email, diachi, dienthoai) VALUES (:hoten, :email, :diachi, :dienthoai)"
            );
            $stmtOrder->execute([
                'hoten' => $hoten,
                'email' => $email,
                'diachi' => $diachi,
                'dienthoai' => $dienthoai,
            ]);
            $idDh = (int)$this->conn->lastInsertId();

            $stmtItem = $this->conn->prepare(
                "INSERT INTO donhangchitiet (id_dh, id_sp, ten_sp, soluong, gia)
                 VALUES (:id_dh, :id_sp, :ten_sp, :soluong, :gia)"
            );

            $savedItems = 0;
            foreach ($cart as $idSp => $soLuong) {
                $sp = $this->detail((int)$idSp);
                $soLuong = max(0, (int)$soLuong);
                if (!$sp || $soLuong === 0 || (int)$sp['anhien'] !== 1) {
                    continue;
                }
                $donGia = ((int)$sp['gia_km'] > 0 && (int)$sp['gia_km'] < (int)$sp['gia'])
                    ? (int)$sp['gia_km']
                    : (int)$sp['gia'];
                $stmtItem->execute([
                    'id_dh' => $idDh,
                    'id_sp' => (int)$idSp,
                    'ten_sp' => $sp['ten_sp'],
                    'soluong' => $soLuong,
                    'gia' => $donGia,
                ]);
                $savedItems++;
            }

            if ($savedItems === 0) {
                throw new RuntimeException("Không có sản phẩm hợp lệ trong giỏ hàng.");
            }

            $this->conn->commit();
            return $idDh;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    public function timKiemSanPham(
        $keyword,
        $pageNum = 1,
        $pageSize = 10,
        $sort = 'ten_sp',
        $order = 'ASC',
        $onlyVisible = true
    ): array {
        $allowedSorts = ['ten_sp', 'gia', 'ngay'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'ten_sp';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $offset = (max(1, (int)$pageNum) - 1) * max(1, (int)$pageSize);
        $limit = max(1, (int)$pageSize);
        $visibleSql = $onlyVisible ? ' AND anhien = 1' : '';

        $sql = "SELECT * FROM sanpham
                WHERE ten_sp LIKE :keyword {$visibleSql}
                ORDER BY {$sort} {$order}
                LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . trim($keyword) . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function luusanpham($idLoai, $tenSp, $ngay, $gia, $giaKm, $anHien, $hot, $moTa, $filePath): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO sanpham (id_loai, ten_sp, gia, gia_km, ngay, anhien, hot, mota, hinh)
             VALUES (:id_loai, :ten_sp, :gia, :gia_km, :ngay, :anhien, :hot, :mota, :hinh)"
        );
        $stmt->execute([
            'id_loai' => (int)$idLoai,
            'ten_sp' => $tenSp,
            'gia' => (int)$gia,
            'gia_km' => (int)$giaKm,
            'ngay' => $ngay,
            'anhien' => (int)$anHien,
            'hot' => (int)$hot,
            'mota' => $moTa,
            'hinh' => $filePath,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function capnhatsanpham($idSp, $idLoai, $tenSp, $ngay, $gia, $giaKm, $anHien, $hot, $moTa, $filePath): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE sanpham SET
             id_loai = :id_loai, ten_sp = :ten_sp, gia = :gia, gia_km = :gia_km,
             ngay = :ngay, anhien = :anhien, hot = :hot, mota = :mota, hinh = :hinh
             WHERE id_sp = :id_sp"
        );
        return $stmt->execute([
            'id_loai' => (int)$idLoai,
            'ten_sp' => $tenSp,
            'gia' => (int)$gia,
            'gia_km' => (int)$giaKm,
            'ngay' => $ngay,
            'anhien' => (int)$anHien,
            'hot' => (int)$hot,
            'mota' => $moTa,
            'hinh' => $filePath,
            'id_sp' => (int)$idSp,
        ]);
    }

    public function deleteSanPham($idSp): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM sanpham WHERE id_sp = :id_sp");
        return $stmt->execute(['id_sp' => (int)$idSp]);
    }

    public function danhsachsanpham($pageNum = 1, $pageSize = 9, $sort = '', $order = ''): PDOStatement
    {
        $allowedSorts = ['ten_sp', 'gia', 'ngay'];
        $sql = "SELECT * FROM sanpham";
        if (in_array($sort, $allowedSorts, true)) {
            $sql .= " ORDER BY {$sort} " . (strtoupper($order) === 'DESC' ? 'DESC' : 'ASC');
        } else {
            $sql .= " ORDER BY id_sp DESC";
        }

        $offset = (max(1, (int)$pageNum) - 1) * max(1, (int)$pageSize);
        $limit = max(1, (int)$pageSize);
        $sql .= " LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function demSP($keyword = '', $onlyVisible = false): int
    {
        $where = [];
        $values = [];
        if (trim($keyword) !== '') {
            $where[] = 'ten_sp LIKE :keyword';
            $values['keyword'] = '%' . trim($keyword) . '%';
        }
        if ($onlyVisible) {
            $where[] = 'anhien = 1';
        }
        $sql = "SELECT COUNT(id_sp) FROM sanpham" . ($where ? ' WHERE ' . implode(' AND ', $where) : '');
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($values);
        return (int)$stmt->fetchColumn();
    }
}
