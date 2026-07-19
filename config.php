<?php

declare(strict_types=1);

session_start();
date_default_timezone_set('Asia/Jakarta');

const APP_NAME = 'PeduliKita';
const BASE_PATH = __DIR__;
const DB_PATH = BASE_PATH . '/data/pedulikita.sqlite';
const MAX_UPLOAD_SIZE = 3 * 1024 * 1024;

if (!extension_loaded('pdo_sqlite')) {
    http_response_code(500);
    exit('<h2>PDO SQLite belum aktif</h2><p>Aktifkan extension=pdo_sqlite dan extension=sqlite3 pada php.ini, lalu restart PHP/Apache.</p>');
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!is_dir(dirname(DB_PATH))) {
        mkdir(dirname(DB_PATH), 0775, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    initialize_database($pdo);
    return $pdo;
}

function initialize_database(PDO $pdo): void
{
    $pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'donor' CHECK(role IN ('admin','donor')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS campaigns (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    summary TEXT NOT NULL,
    description TEXT NOT NULL,
    category TEXT NOT NULL,
    beneficiary TEXT NOT NULL,
    location TEXT NOT NULL,
    target_amount REAL NOT NULL,
    cover_image TEXT,
    status TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('draft','active','completed','closed')),
    deadline TEXT,
    created_by INTEGER,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS donations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    campaign_id INTEGER NOT NULL,
    user_id INTEGER,
    donor_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT,
    amount REAL NOT NULL,
    anonymous INTEGER NOT NULL DEFAULT 0,
    message TEXT,
    payment_method TEXT NOT NULL,
    proof_file TEXT,
    status TEXT NOT NULL DEFAULT 'pending' CHECK(status IN ('pending','verified','rejected')),
    receipt_code TEXT NOT NULL UNIQUE,
    verified_at TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS campaign_updates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    campaign_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    image TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS fund_usages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    campaign_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    amount REAL NOT NULL,
    usage_date TEXT NOT NULL,
    proof_file TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);
SQL);

    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count === 0) {
        seed_database($pdo);
    }
}

function seed_database(PDO $pdo): void
{
    $pdo->beginTransaction();
    try {
        $user = $pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)');
        $user->execute(['Administrator PeduliKita', 'admin@pedulikita.id', password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
        $adminId = (int) $pdo->lastInsertId();
        $user->execute(['Donatur Demo', 'donor@pedulikita.id', password_hash('Donor123!', PASSWORD_DEFAULT), 'donor']);
        $donorId = (int) $pdo->lastInsertId();

        $campaign = $pdo->prepare('INSERT INTO campaigns(title,slug,summary,description,category,beneficiary,location,target_amount,status,deadline,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $campaigns = [
            ['Bantu Sekolah Pelosok Tetap Berdiri','bantu-sekolah-pelosok','Renovasi ruang kelas dan penyediaan perlengkapan belajar untuk 86 siswa.','Bangunan sekolah mengalami kerusakan pada atap dan lantai sehingga kegiatan belajar terganggu saat hujan. Dana digunakan untuk renovasi, meja belajar, papan tulis, dan paket buku.','Pendidikan','SD Harapan Nusantara','Lombok Timur, NTB',120000000,'active',date('Y-m-d', strtotime('+65 days')),$adminId],
            ['Air Bersih untuk Desa Sukamaju','air-bersih-desa-sukamaju','Pembangunan sumur bor dan tangki air bagi 240 keluarga.','Warga harus menempuh perjalanan jauh untuk memperoleh air bersih. Kampanye ini membangun sumur bor, pompa, tangki penampungan, dan jaringan distribusi sederhana.','Lingkungan','Warga Desa Sukamaju','Gunungkidul, DI Yogyakarta',85000000,'active',date('Y-m-d', strtotime('+42 days')),$adminId],
            ['Perawatan Medis untuk Aisyah','perawatan-medis-aisyah','Dukungan biaya operasi dan pemulihan anak penderita kelainan jantung.','Aisyah membutuhkan tindakan operasi dan kontrol lanjutan. Dana mencakup tindakan medis, obat, transportasi, dan kebutuhan keluarga selama masa perawatan.','Kesehatan','Aisyah Rahma','Bandung, Jawa Barat',65000000,'active',date('Y-m-d', strtotime('+28 days')),$adminId],
            ['Paket Pangan untuk Lansia','paket-pangan-lansia','Distribusi kebutuhan pokok bulanan bagi lansia yang hidup sendiri.','Program menyediakan beras, minyak, protein, susu, dan kebutuhan kebersihan kepada 100 lansia selama tiga bulan.','Kemanusiaan','100 Lansia Dhuafa','Tangerang, Banten',45000000,'active',date('Y-m-d', strtotime('+36 days')),$adminId],
        ];
        $ids=[];
        foreach($campaigns as $c){$campaign->execute($c);$ids[]=(int)$pdo->lastInsertId();}

        $don = $pdo->prepare('INSERT INTO donations(campaign_id,user_id,donor_name,email,phone,amount,anonymous,message,payment_method,status,receipt_code,verified_at,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $sample = [
            [$ids[0],$donorId,'Donatur Demo','donor@pedulikita.id','081234567890',750000,0,'Semoga bermanfaat.','Bank BCA','verified','PK-DEMO001',date('Y-m-d H:i:s',strtotime('-10 days')),date('Y-m-d H:i:s',strtotime('-10 days'))],
            [$ids[0],null,'Hamba Allah','anonymous@example.com','',2500000,1,'Untuk pendidikan Indonesia.','Bank Mandiri','verified','PK-DEMO002',date('Y-m-d H:i:s',strtotime('-8 days')),date('Y-m-d H:i:s',strtotime('-8 days'))],
            [$ids[1],null,'Siti Aminah','siti@example.com','',1250000,0,'Semoga air segera mengalir.','Bank BRI','verified','PK-DEMO003',date('Y-m-d H:i:s',strtotime('-6 days')),date('Y-m-d H:i:s',strtotime('-6 days'))],
            [$ids[2],null,'Hamba Allah','anon2@example.com','',5000000,1,'Semoga lekas sembuh.','QRIS','verified','PK-DEMO004',date('Y-m-d H:i:s',strtotime('-4 days')),date('Y-m-d H:i:s',strtotime('-4 days'))],
            [$ids[3],$donorId,'Donatur Demo','donor@pedulikita.id','081234567890',500000,0,'Untuk para lansia.','Bank BCA','pending','PK-DEMO005',null,date('Y-m-d H:i:s',strtotime('-1 day'))],
        ];
        foreach($sample as $s){$don->execute($s);}

        $upd=$pdo->prepare('INSERT INTO campaign_updates(campaign_id,title,content,created_at) VALUES(?,?,?,?)');
        $upd->execute([$ids[0],'Survei bangunan selesai','Tim telah menyelesaikan pengukuran atap dan pendataan kebutuhan ruang kelas. Proses pembelian material dimulai setelah tahap penggalangan pertama tercapai.',date('Y-m-d H:i:s',strtotime('-5 days'))]);
        $upd->execute([$ids[1],'Titik sumur telah ditentukan','Pemeriksaan awal menunjukkan titik pengeboran berada dekat permukiman dan dapat menjangkau seluruh wilayah prioritas.',date('Y-m-d H:i:s',strtotime('-3 days'))]);

        $usage=$pdo->prepare('INSERT INTO fund_usages(campaign_id,title,description,amount,usage_date) VALUES(?,?,?,?,?)');
        $usage->execute([$ids[0],'Survei teknis dan pengiriman awal','Biaya survei struktur serta pengiriman material awal.',1200000,date('Y-m-d',strtotime('-5 days'))]);
        $usage->execute([$ids[1],'Pemeriksaan geolistrik','Identifikasi lapisan air tanah untuk menentukan titik pengeboran.',750000,date('Y-m-d',strtotime('-3 days'))]);

        $pdo->commit();
    } catch(Throwable $e){$pdo->rollBack();throw $e;}
}

db();
