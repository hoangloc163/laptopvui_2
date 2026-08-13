<?php
class database
{
    protected $conn = null;

    public function __construct()
    {
        try {
            if (DB_DRIVER === 'sqlite') {
                $dataDir = dirname(SQLITE_PATH);
                if (!is_dir($dataDir) && !mkdir($dataDir, 0775, true)) {
                    throw new RuntimeException("Không thể tạo thư mục dữ liệu demo.");
                }
                $this->conn = new PDO("sqlite:" . SQLITE_PATH);
            } else {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=3306";
                $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            }
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            if (DB_DRIVER === 'sqlite') {
                $this->initializeDemoDatabase();
            }
        } catch (Throwable $e) {
            die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
        }
    }

    private function initializeDemoDatabase(): void
    {
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS loai (id_loai INTEGER PRIMARY KEY AUTOINCREMENT, ten_loai TEXT NOT NULL UNIQUE, thutu INTEGER NOT NULL DEFAULT 0, anhien INTEGER NOT NULL DEFAULT 1);
            CREATE TABLE IF NOT EXISTS sanpham (id_sp INTEGER PRIMARY KEY AUTOINCREMENT, id_loai INTEGER NOT NULL, ten_sp TEXT NOT NULL, gia INTEGER NOT NULL DEFAULT 0, gia_km INTEGER NOT NULL DEFAULT 0, hinh TEXT NOT NULL DEFAULT '', soluotxem INTEGER NOT NULL DEFAULT 0, ngay TEXT NOT NULL, hot INTEGER NOT NULL DEFAULT 0, anhien INTEGER NOT NULL DEFAULT 1, mota TEXT NOT NULL DEFAULT '');
            CREATE TABLE IF NOT EXISTS users (id_user INTEGER PRIMARY KEY AUTOINCREMENT, hoten TEXT NOT NULL, email TEXT NOT NULL UNIQUE, matkhau TEXT NOT NULL, vaitro INTEGER NOT NULL DEFAULT 0);
            CREATE TABLE IF NOT EXISTS donhang (id_dh INTEGER PRIMARY KEY AUTOINCREMENT, hoten TEXT NOT NULL, email TEXT NOT NULL, diachi TEXT NOT NULL, dienthoai TEXT NOT NULL);
            CREATE TABLE IF NOT EXISTS donhangchitiet (id INTEGER PRIMARY KEY AUTOINCREMENT, id_dh INTEGER NOT NULL, id_sp INTEGER NOT NULL, ten_sp TEXT NOT NULL, soluong INTEGER NOT NULL, gia INTEGER NOT NULL);
        ");

        if ((int)$this->conn->query("SELECT COUNT(*) FROM loai")->fetchColumn() === 0) {
            $this->conn->exec("INSERT INTO loai (ten_loai, thutu, anhien) VALUES ('Laptop văn phòng',1,1),('Laptop gaming',2,1),('MacBook',3,1)");
        }
        if ((int)$this->conn->query("SELECT COUNT(*) FROM sanpham")->fetchColumn() === 0) {
            $stmt = $this->conn->prepare("INSERT INTO sanpham (id_loai,ten_sp,gia,gia_km,hinh,soluotxem,ngay,hot,anhien,mota) VALUES (?,?,?,?,?,?,?,?,1,?)");
            $products = [
                [1,'Dell Inspiron 15',18990000,17490000,'upload/67033e038313d.jpg',125,date('Y-m-d'),1,'Laptop mỏng nhẹ, phù hợp học tập và công việc văn phòng.'],
                [2,'ASUS TUF Gaming F15',25990000,23990000,'upload/67035e9947d7d.jpg',210,date('Y-m-d', strtotime('-1 day')),1,'Hiệu năng mạnh, màn hình tần số quét cao dành cho game thủ.'],
                [3,'MacBook Air M2',27990000,26490000,'upload/67035e9f76649.jpg',180,date('Y-m-d', strtotime('-2 days')),1,'Thiết kế cao cấp, pin lâu và hiệu năng Apple Silicon.'],
                [1,'Lenovo IdeaPad Slim 5',20990000,19490000,'upload/67033e038313d.jpg',88,date('Y-m-d', strtotime('-3 days')),0,'Cân bằng giữa hiệu năng, độ bền và tính di động.'],
                [2,'Acer Nitro V',22990000,21990000,'upload/67035e9947d7d.jpg',156,date('Y-m-d', strtotime('-4 days')),1,'Laptop gaming phổ thông với khả năng nâng cấp linh hoạt.'],
                [3,'MacBook Pro 14',45990000,43990000,'upload/67035e9f76649.jpg',142,date('Y-m-d', strtotime('-5 days')),0,'Màn hình Liquid Retina XDR và hiệu năng chuyên nghiệp.'],
            ];
            foreach ($products as $product) {
                $stmt->execute($product);
            }
        }
        if ((int)$this->conn->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
            $stmt = $this->conn->prepare("INSERT INTO users (hoten,email,matkhau,vaitro) VALUES (?,?,?,1)");
            $stmt->execute(['Quản trị demo', 'admin@demo.local', password_hash('admin123', PASSWORD_BCRYPT)]);
        }
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function queryOne($sql)
    {
        $result = $this->conn->query($sql);
        return $result->fetch() ?: null;
    }
}
