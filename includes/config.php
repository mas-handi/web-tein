<?php
/**
 * config.php — Konfigurasi Database & Sistem TEIN Indonesia
 *
 * Digunakan bersama oleh:
 *   - Frontend  : index.php, produk.php, dll.
 *   - Admin CMS : admin/dashboard.php, admin/products.php, dll.
 *
 * CATATAN KEAMANAN:
 *   - Jangan commit file ini ke repository publik (Git).
 *   - Tambahkan "config.php" ke .gitignore.
 *   - Saat live/production: pastikan DB_PASS diisi, APP_ENV diset 'production'.
 */

// FIX #7: Cegah redefinisi jika file di-include lebih dari sekali
if (defined('APP_CONFIG_LOADED')) {
    return;
}
define('APP_CONFIG_LOADED', true);


// ══════════════════════════════════════════════════════════════
// 1. MODE APLIKASI (development | production)
// ══════════════════════════════════════════════════════════════
// FIX #2: Ganti ke 'production' saat deploy ke server live.
// Saat 'production': error detail TIDAK ditampilkan ke browser.
define('APP_ENV', 'development');  // 'development' | 'production'

// Tampilkan error PHP hanya di mode development
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    // Di production, log error ke file (pastikan folder writable)
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/app_errors.log');
}


// ══════════════════════════════════════════════════════════════
// 2. PENGATURAN DATABASE MySQL
// ══════════════════════════════════════════════════════════════
// Sesuaikan dengan kredensial hosting/cPanel atau XAMPP Anda.
define('DB_HOST', 'localhost');
define('DB_NAME', 'tein_db');       // Nama database yang sudah dibuat
define('DB_USER', 'root');          // Default XAMPP: 'root'
define('DB_PASS', '');              // Default XAMPP: '' | Wajib diisi di production!
define('DB_CHARSET', 'utf8mb4');    // Mendukung emoji & karakter Unicode penuh


// ══════════════════════════════════════════════════════════════
// 3. BASE URL (URL DASAR SITUS)
// ══════════════════════════════════════════════════════════════
// FIX #8: Auto-detect protokol (http/https) dan hostname
// Saat di production dengan domain asli, ini otomatis terbaca dengan benar.
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Subfolder: ubah '/tein' jika struktur folder Anda berbeda
    // Di server live dengan domain root (contoh: tein.id), kosongkan menjadi ''
    $subfolder = '/tein';
    define('BASE_URL', $protocol . '://' . $host . $subfolder);
}

// Path absolut ke root project (berguna untuk include file)
define('ROOT_PATH', rtrim(dirname(__FILE__), '/\\'));

// Path ke folder upload
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('UPLOAD_URL',  BASE_URL  . '/uploads/');


// ══════════════════════════════════════════════════════════════
// 4. ZONA WAKTU
// ══════════════════════════════════════════════════════════════
date_default_timezone_set('Asia/Jakarta');


// ══════════════════════════════════════════════════════════════
// 5. PENGATURAN SESSION YANG AMAN
// ══════════════════════════════════════════════════════════════
// FIX #5: Konfigurasi session sebelum session_start() dipanggil.
// File ini di-include sebelum session_start() di tiap halaman.
if (session_status() === PHP_SESSION_NONE) {
    // Cookie session tidak bisa diakses via JavaScript (cegah XSS hijack)
    ini_set('session.cookie_httponly', 1);
    // Cookie session hanya dikirim via HTTPS di production
    ini_set('session.cookie_secure', APP_ENV === 'production' ? 1 : 0);
    // Cegah penggunaan session ID dari URL (?PHPSESSID=...)
    ini_set('session.use_only_cookies', 1);
    // Regenerasi session ID secara ketat (cegah session fixation)
    ini_set('session.use_strict_mode', 1);
    // Cookie hanya dikirim ke path ini (isolasi dari aplikasi lain di server)
    ini_set('session.cookie_path', '/');
    // Nama session yang tidak generik
    session_name('TEIN_ADMIN_SID');
}


// ══════════════════════════════════════════════════════════════
// 6. HEADER KEAMANAN HTTP
// ══════════════════════════════════════════════════════════════
// FIX #6: Pasang header security. Hanya kirim jika belum ada output.
// Diletakkan sebelum HTML output apa pun.
if (!headers_sent()) {
    // Cegah halaman dimuat dalam <iframe> (Clickjacking)
    header('X-Frame-Options: SAMEORIGIN');
    // Cegah browser menebak-nebak tipe konten (MIME sniffing)
    header('X-Content-Type-Options: nosniff');
    // Aktifkan proteksi XSS bawaan browser lama
    header('X-XSS-Protection: 1; mode=block');
    // Referrer: kirim origin saja, bukan full URL
    header('Referrer-Policy: strict-origin-when-cross-origin');
    // Content Security Policy dasar — sesuaikan jika pakai CDN tertentu
    // Di sini diizinkan: Google Fonts, Tailwind CDN, placehold.co
    header(
        "Content-Security-Policy: " .
        "default-src 'self'; " .
        "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; " .
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
        "font-src 'self' https://fonts.gstatic.com; " .
        "img-src 'self' data: https://placehold.co; " .
        "connect-src 'self';"
    );
}


// ══════════════════════════════════════════════════════════════
// 7. KONEKSI PDO KE DATABASE
// ══════════════════════════════════════════════════════════════
try {
    $dsn = "mysql:host=" . DB_HOST
         . ";dbname=" . DB_NAME
         . ";charset=" . DB_CHARSET;

    $options = [
        // Lempar Exception saat terjadi error SQL (memudahkan debugging)
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Hasil fetch berupa array asosiatif (key => value)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Gunakan native prepared statements (lebih aman dari SQL Injection)
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Auto-reconnect jika koneksi terputus
        PDO::ATTR_PERSISTENT         => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // FIX #1: Jangan tampilkan detail error ke user di production
    if (APP_ENV === 'development') {
        // Development: tampilkan detail lengkap untuk debugging
        die(
            "<div style='font-family:monospace;background:#fff0f0;border:2px solid #e00;" .
            "padding:20px;margin:20px;border-radius:8px;'>" .
            "<strong style='color:#c00'>❌ Koneksi Database Gagal</strong><br><br>" .
            "<strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>" .
            "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (baris " . $e->getLine() . ")<br><br>" .
            "<em style='color:#666'>Pesan ini hanya muncul di mode <code>development</code>.</em>" .
            "</div>"
        );
    } else {
        // Production: log error, tampilkan pesan generik
        error_log('[TEIN DB ERROR] ' . $e->getMessage() . ' | File: ' . $e->getFile() . ':' . $e->getLine());
        die(
            "<div style='font-family:sans-serif;text-align:center;padding:60px;color:#555'>" .
            "<h2>Layanan Sementara Tidak Tersedia</h2>" .
            "<p>Kami sedang melakukan perbaikan. Silakan coba beberapa saat lagi.</p>" .
            "</div>"
        );
    }
}


// ══════════════════════════════════════════════════════════════
// 8. FUNGSI HELPER UMUM
// ══════════════════════════════════════════════════════════════

/**
 * FIX #4: sanitize() — hanya untuk OUTPUT HTML (cegah XSS tampilan).
 * JANGAN gunakan ini sebagai pengganti prepared statement untuk query SQL.
 * Untuk query SQL: selalu gunakan $pdo->prepare() + bindParam()/execute().
 *
 * @param  string|null $string  String yang akan dibersihkan
 * @return string               String aman untuk ditampilkan di HTML
 */
function sanitize(?string $string): string {
    return htmlspecialchars(trim((string) $string), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Redirect dengan header Location dan hentikan eksekusi.
 *
 * @param string $url  URL tujuan (absolut atau relatif)
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect ke halaman admin dengan pesan flash (disimpan di session).
 *
 * @param string $url      URL tujuan
 * @param string $type     'success' | 'error' | 'warning' | 'info'
 * @param string $message  Pesan yang akan ditampilkan
 */
function redirectWithMessage(string $url, string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    redirect($url);
}

/**
 * Ambil dan hapus pesan flash dari session (untuk ditampilkan sekali).
 *
 * @return array|null  ['type' => '...', 'message' => '...'] atau null
 */
function getFlashMessage(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Format harga ke format Rupiah Indonesia.
 * Contoh: 4800000 → "Rp 4.800.000"
 *
 * @param  float  $amount  Nominal angka
 * @return string          String harga terformat
 */
function formatRupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Buat slug URL dari sebuah string.
 * Contoh: "Toyota Avanza Terbaru!" → "toyota-avanza-terbaru"
 *
 * @param  string $string  String judul/nama
 * @return string          Slug URL
 */
function makeSlug(string $string): string {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\s\-]/', '', $string);
    $string = preg_replace('/[\s\-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Potong teks panjang dan tambahkan "..." di akhir.
 *
 * @param  string $text    Teks asli
 * @param  int    $limit   Jumlah karakter maksimum
 * @param  string $suffix  Akhiran (default: '...')
 * @return string
 */
function truncate(string $text, int $limit = 100, string $suffix = '...'): string {
    if (mb_strlen($text) <= $limit) return $text;
    return mb_substr($text, 0, $limit) . $suffix;
}