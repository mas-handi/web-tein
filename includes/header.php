<?php
// Pastikan koneksi DB dimuat
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} elseif (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
}

if (!defined('BASE_URL')) define('BASE_URL', '');

// Mengambil pengaturan website (jika belum dimuat)
if (!isset($settings)) {
    try {
        if(isset($pdo)) {
            $stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
            $stmt->execute();
            $settings_db = $stmt->fetch();
        }
        $settings = [
            'site_name' => $settings_db['website_name'] ?? 'TEIN Suspensi Indonesia',
            'phone' => $settings_db['whatsapp'] ?? '6285219463121',
            'email' => $settings_db['email'] ?? 'info@tein.id',
            'address' => $settings_db['address'] ?? 'Jakarta, Indonesia'
        ];
    } catch (Exception $e) {
        $settings = [
            'site_name' => 'TEIN Suspensi Indonesia',
            'phone' => '6285219463121',
            'email' => 'info@tein.id',
            'address' => 'Jakarta, Indonesia'
        ];
    }
}

// Menentukan variabel default jika tidak diset di halaman utama
$page_title = $page_title ?? $settings['site_name'];
$is_home = $is_home ?? false;
$current_page = basename($_SERVER['PHP_SELF']);

//$nav_categories = [];
$nav_products_grouped = [];
try {
    if(isset($pdo)) {
        // Ambil kategori
        $stmt_cat = $pdo->query("SELECT id, name, slug FROM product_categories ORDER BY name ASC");
        $nav_categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

        // Ambil produk dan kelompokkan berdasarkan kategori
        $stmt_prod = $pdo->query("SELECT id, name, slug, category_id FROM products ORDER BY name ASC");
        $all_prods = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

        foreach ($all_prods as $p) {
            $nav_products_grouped[$p['category_id']][] = $p;
        }
    } else {
        throw new Exception("PDO tidak aktif");
    }
} catch (Exception $e) {
    // Data Simulasi jika database tidak siap
    $nav_categories = [
        ['id' => 1, 'name' => 'TEIN BASIC', 'slug' => 'tein-basic'],
        ['id' => 2, 'name' => '(VIP) TEIN DAMPER GRAVEL 4X4', 'slug' => 'vip-tein-damper-gravel-4x4'],
        ['id' => 3, 'name' => 'TEIN ENDURAPRO', 'slug' => 'tein-endurapro'],
        ['id' => 4, 'name' => 'TEIN ENDURAPRO PLUS', 'slug' => 'tein-endurapro-plus']
    ];
    $nav_products_grouped = [
        1 => [['name' => 'Toyota Avanza Basic', 'slug' => 'toyota-avanza-basic']],
        3 => [['name' => 'Honda Civic Turbo', 'slug' => 'honda-civic-turbo']],
        4 => [['name' => 'Mitsubishi Pajero Sport', 'slug' => 'mitsubishi-pajero']]
    ];
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        ink:        '#0D0F0D',
                        ink2:       '#16191A',
                        tgreen:     '#00A651',
                        'tgreen-d': '#00833F',
                        'tgreen-l': '#E9F8EF',
                        'tein-green': '#00995e' 
                    }
                }
            }
        }
    </script>
    <style>
        body { overflow-x: hidden; font-family: "Plus Jakarta Sans", sans-serif; }

        <?php if($is_home): ?>
        /* ── HEADER KHUSUS BERANDA (Transparan ke Putih) ── */
        #header-wrapper { background-color: #0D0F0D; transition: background-color 0.4s ease, box-shadow 0.4s ease; }
        #header-wrapper.scrolled { background-color: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header-text { color: #fff; transition: color 0.3s ease; }
        #header-wrapper.scrolled .header-text { color: #0D0F0D; }
        .header-subtext { color: rgba(255,255,255,0.7); transition: color 0.3s ease; }
        #header-wrapper.scrolled .header-subtext { color: #4B5563; }
        .nav-link { color: #fff; font-weight: 700; font-size: 0.9rem; position: relative; transition: all 0.3s ease; }
        #header-wrapper.scrolled .nav-link { color: #16191A; }
        .nav-link:hover, .scrolled .nav-link:hover, .nav-link.active { color: #00A651 !important; }
        .menu-btn { color: #fff; transition: color 0.3s ease; }
        #header-wrapper.scrolled .menu-btn { color: #0D0F0D; }
        <?php else: ?>
        /* ── HEADER INNER PAGE (Selalu Putih) ── */
        #header-wrapper { background-color: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header-text { color: #0D0F0D; }
        .header-subtext { color: #4B5563; }
        .nav-link { color: #16191A; font-weight: 700; font-size: 0.9rem; position: relative; transition: all 0.3s ease; }
        .nav-link:hover, .nav-link.active { color: #00A651 !important; }
        .menu-btn { color: #0D0F0D; }
        <?php endif; ?>

        /* ── DROPDOWN PRODUK (MULTI-LEVEL FLYOUT) ── */
        .nav-item-product { position: relative; }
        .product-menu-content { 
            display: none; 
            position: absolute; 
            top: calc(100% + 12px); 
            left: -30px; 
            background: #fff; 
            border-radius: 8px; 
            box-shadow: 0 15px 50px rgba(0,0,0,0.15); 
            z-index: 100; 
            min-width: 300px; 
            padding: 8px 0;
            border-bottom: 4px solid #00A651;
        }
        .nav-item-product:hover .product-menu-content { display: block; }
        .product-menu-content::before { content: ''; position: absolute; top: -16px; left: 0; width: 100%; height: 16px; background: transparent; }

        /* Kategori Item (Baris di Dropdown Utama) */
        .product-category-item { position: relative; border-bottom: 1px solid #f3f4f6; }
        .product-category-item:last-child { border-bottom: none; }
        .product-category-link { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 16px 24px; font-size: 13px; font-weight: 800; color: #16191A; 
            text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s; 
        }
        .product-category-item:hover > .product-category-link { color: #00A651; background: #f9fafb; }

        /* Flyout (Sub-menu Produk Melayang ke Kanan) */
        .product-submenu { 
            display: none; position: absolute; top: 0; left: 100%; 
            background: #fff; border-radius: 8px; box-shadow: 0 15px 50px rgba(0,0,0,0.15); 
            min-width: 280px; padding: 8px 0; border-bottom: 4px solid #00A651; 
            margin-left: 2px;
        }
        .product-category-item:hover .product-submenu { display: block; }
        /* Tanda panah konektor */
        .product-submenu::before {
            content: ''; position: absolute; top: 20px; left: -6px; width: 12px; height: 12px;
            background: #fff; transform: rotate(45deg); box-shadow: -2px 2px 5px rgba(0,0,0,0.04);
        }
        .product-submenu a.prod-link { 
            display: block; padding: 12px 24px; font-size: 13px; font-weight: 600; 
            color: #4B5563; transition: all 0.2s; white-space: normal; line-height: 1.4;
        }
        .product-submenu a.prod-link:hover { color: #00A651; background: #f9f9f9; padding-left: 28px; }

        /* ── DROPDOWN NORMAL (BERITA) ── */
        .nav-dropdown { position: relative; }
        .nav-dropdown-menu { display: none; position: absolute; top: calc(100% + 12px); left: 50%; transform: translateX(-50%); background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); min-width: 200px; z-index: 100; padding: 8px 0; }
        .nav-dropdown-menu::before { content: ''; position: absolute; top: -12px; left: 0; width: 100%; height: 12px; background: transparent; }
        .nav-dropdown:hover .nav-dropdown-menu { display: block; }
        .nav-dropdown-menu a { display: block; padding: 10px 16px; font-size: 12px; font-weight: 700; color: #16191A; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; transition: background 0.2s, color 0.2s; }
        .nav-dropdown-menu a:hover { color: #00A651; background: #f9f9f9; }

        /* Utils Animasi */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.22,1,0.36,1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        #back-to-top { position: fixed; right: 20px; bottom: 20px; width: 46px; height: 46px; border-radius: 50%; background: #00A651; color: #fff; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: all 0.3s ease; z-index: 60; box-shadow: 0 4px 12px rgba(0,166,81,0.4); }
        #back-to-top.show { opacity: 1; pointer-events: auto; }
        #back-to-top:hover { background: #00833F; transform: translateY(-3px); }
    </style>
</head>
<body class="<?= $is_home ? 'bg-white' : 'bg-gray-50' ?> text-gray-900 pt-[96px] md:pt-[112px] flex flex-col min-h-screen">

    <!-- -->
    <div id="header-wrapper" class="fixed top-0 left-0 z-50 w-full">

        <!-- Top Bar -->
        <div id="top-bar" class="bg-[#00A651] text-white text-[11px] md:text-xs font-bold py-2">
            <div class="container mx-auto px-6 flex justify-end gap-6 uppercase tracking-wide">
                <a href="mailto:<?= htmlspecialchars($settings['email']) ?>" class="hover:text-white/80 transition-colors">Hubungi Kerjasama</a>
                <a href="<?= BASE_URL ?>/index.php#garansi" class="hover:text-white/80 transition-colors">Garansi</a>
            </div>
        </div>

        <header class="py-3 md:py-0 relative z-10">
            <div class="container mx-auto px-6 flex items-center justify-between relative">

                <!-- -->
                <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 group shrink-0">
                    
                <div class="relative w-[150px] h-[25px] md:w-[200px] md:h-[100px] flex-shrink-0" id="logo-wrapper">
                    <!-- Gambar Logo 1 (Putih/Default) -->
                    <img id="logo-light" src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo TEIN" 
                        class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300 opacity-100" loading="lazy">
                    
                    <!-- Gambar Logo 2 (Berwarna/Saat Scroll & Hover) -->
                    <img id="logo-dark" src="<?= BASE_URL ?>/assets/images/logo1.png" alt="Logo TEIN Alt" 
                        class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300 opacity-0" loading="lazy">
                </div>

                <!-- -->
                <nav class="hidden lg:flex items-center gap-5 xl:gap-8 text-[11px] xl:text-xs uppercase tracking-wider h-full">
                    <a href="<?= BASE_URL ?>/index.php" class="nav-link py-4 <?= $current_page=='index.php'?'active':'' ?>">Beranda</a>
                    <a href="<?= BASE_URL ?>/tentang.php" class="nav-link py-4 <?= $current_page=='tentang.php'?'active':'' ?>">Tentang Kami</a>

                    <!-- Dropdown Produk (Multi-Level / Bersarang) -->
                    <div class="nav-item-product">
                        <a href="<?= BASE_URL ?>/produk.php" class="nav-link flex items-center gap-1 py-4 <?= $current_page=='produk.php'?'active':'' ?>">
                            Produk 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        
                        <!-- Kotak Utama Kategori -->
                        <div class="product-menu-content text-left cursor-default">
                            <?php foreach($nav_categories as $cat): ?>
                            <?php 
                                // Hitung jumlah produk dalam kategori ini
                                $count = isset($nav_products_grouped[$cat['id']]) ? count($nav_products_grouped[$cat['id']]) : 0;
                            ?>
                            <div class="product-category-item group/cat">
                                <!-- Link Kategori -->
                                <a href="<?= BASE_URL ?>/produk.php?kategori=<?= htmlspecialchars($cat['slug']) ?>" class="product-category-link">
                                    <span><?= htmlspecialchars($cat['name']) ?> <span class="text-gray-400 font-medium ml-1">(<?= $count ?>)</span></span>
                                    
                                    <!-- Ikon panah muncul jika ada isinya -->
                                    <?php if($count > 0): ?>
                                    <svg class="w-4 h-4 text-gray-300 group-hover/cat:text-[#00A651] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <?php endif; ?>
                                </a>
                                
                                <!-- Flyout Sub-menu (Kotak Detail Produk) -->
                                <?php if($count > 0): ?>
                                <div class="product-submenu">
                                    <div class="px-6 py-3 border-b border-gray-100">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Model Kendaraan</span>
                                    </div>
                                    <?php foreach($nav_products_grouped[$cat['id']] as $prod): ?>
                                    <a href="<?= BASE_URL ?>/produk-detail.php?slug=<?= htmlspecialchars($prod['slug']) ?>" class="prod-link">
                                        <?= htmlspecialchars($prod['name']) ?>
                                    </a>
                                    <?php endforeach; ?>
                                    
                                    <div class="border-t border-gray-100 mt-2 pt-2 pb-1">
                                        <a href="<?= BASE_URL ?>/produk.php?kategori=<?= htmlspecialchars($cat['slug']) ?>" class="block px-6 py-3 text-[#00A651] font-bold text-[11px] uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                            Lihat Semua <?= htmlspecialchars($cat['name']) ?> &rarr;
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Dropdown Berita -->
                    <div class="nav-dropdown">
                        <a href="<?= BASE_URL ?>/berita.php" class="nav-link flex items-center gap-1 py-4 <?= $current_page=='berita.php'?'active':'' ?>">Berita <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></a>
                        <div class="nav-dropdown-menu text-left">
                            <a href="<?= BASE_URL ?>/berita.php">Berita Terbaru</a>
                            <a href="<?= BASE_URL ?>/berita.php">Event & Pameran</a>
                            <a href="<?= BASE_URL ?>/berita.php">Tips Otomotif</a>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/teknis.php" class="nav-link py-4 <?= $current_page=='teknis.php'?'active':'' ?>">Jawaban Teknis</a>
                    <a href="<?= BASE_URL ?>/dealer.php" class="nav-link py-4 <?= $current_page=='dealer.php'?'active':'' ?> relative after:content-[''] after:absolute after:bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-yellow-400">Dealer Terdekat</a>
                </nav>

                <!-- Mobile Toggle -->
                <button id="menu-toggle" class="lg:hidden p-1 menu-btn" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg id="icon-open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="icon-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </header>

        <!-- -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-100 px-6 pb-5 pt-2 shadow-xl absolute w-full top-full left-0 max-h-[80vh] overflow-y-auto">
            <nav class="flex flex-col gap-1 text-[#0D0F0D] font-bold text-sm uppercase tracking-wider text-left">
                <a href="<?= BASE_URL ?>/index.php" class="py-3 border-b border-gray-100 hover:text-[#00A651]">Beranda</a>
                <a href="<?= BASE_URL ?>/tentang.php" class="py-3 border-b border-gray-100 hover:text-[#00A651]">Tentang Kami</a>
                <a href="<?= BASE_URL ?>/produk.php" class="py-3 border-b border-gray-100 hover:text-[#00A651]">Semua Produk</a>
                <a href="<?= BASE_URL ?>/berita.php" class="py-3 border-b border-gray-100 hover:text-[#00A651]">Berita</a>
                <a href="<?= BASE_URL ?>/teknis.php" class="py-3 border-b border-gray-100 hover:text-[#00A651]">Jawaban Teknis</a>
                <a href="<?= BASE_URL ?>/dealer.php" class="py-3 text-[#00A651]">Dealer Terdekat</a>
            </nav>
        </div>
    </div>

    <!-- Back to top button -->
    <button id="back-to-top" aria-label="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    <!-- -->
 <script>
    document.addEventListener('DOMContentLoaded', function () {
        var headerWrapper = document.getElementById('header-wrapper');
        var isHome = <?= $is_home ? 'true' : 'false' ?>;

        // ── Toggle menu mobile ──
        var menuToggle = document.getElementById('menu-toggle');
        var mobileMenu = document.getElementById('mobile-menu');
        var iconOpen = document.getElementById('icon-open');
        var iconClose = document.getElementById('icon-close');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function () {
                var isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
                menuToggle.setAttribute('aria-expanded', String(!isOpen));
            });
        }

        // ── Logika Transisi Logo (HANYA SAAT SCROLL) ──
        var logoLight = document.getElementById('logo-light');
        var logoDark = document.getElementById('logo-dark');

        function updateLogoState() {
            if (!logoLight || !logoDark) return;
            
            var isScrolled = window.scrollY > 40; 
            
            // Jika bukan di halaman beranda (!isHome), header biasanya putih solid, jadi logo berwarna (logoDark) selalu tampil.
            // Jika di beranda (isHome), logo berwarna tampil HANYA JIKA di-scroll ke bawah.
            if (!isHome || isScrolled) {
                logoLight.classList.replace('opacity-100', 'opacity-0');
                logoDark.classList.replace('opacity-0', 'opacity-100');
            } else {
                logoLight.classList.replace('opacity-0', 'opacity-100');
                logoDark.classList.replace('opacity-100', 'opacity-0');
            }
        }

        // ── Header berubah warna & Update Logo saat scroll ──
        var onScroll = function () {
            if (isHome && headerWrapper) {
                if (window.scrollY > 40) {
                    headerWrapper.classList.add('scrolled');
                } else {
                    headerWrapper.classList.remove('scrolled');
                }
            }
            // Panggil fungsi update logo setiap kali layar di-scroll
            updateLogoState(); 
        };
        
        // Daftarkan event pendeteksi scroll
        window.addEventListener('scroll', onScroll);
        
        // Panggil fungsi sekali saat halaman pertama kali dimuat (untuk mengecek posisi awal layar)
        onScroll(); 

        // ── Tombol back-to-top ──
        var backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });
            backToTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ── Reveal animation saat elemen masuk viewport ──
        var revealEls = document.querySelectorAll('.reveal');
        if (revealEls.length && 'IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            revealEls.forEach(function (el) { observer.observe(el); });
        }
    });
</script>