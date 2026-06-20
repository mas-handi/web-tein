<?php
// 1. Atur variabel khusus halaman
$page_title = "TEIN Suspensi Indonesia — Kualitas Jepang Teruji";
$is_home = true; // Untuk efek header transparan ke putih

// 2. Panggil file header (otomatis akan memanggil config.php juga)
require_once 'includes/header.php';

// 3. Mengambil Banner Hero Slider dari Database
try {
    $stmt = $pdo->prepare("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $banners_db = $stmt->fetchAll();
} catch (Exception $e) { $banners_db = []; }

$banners = [];
foreach($banners_db as $b) {
    $banners[] = [
        'image' => 'uploads/banners/' . $b['image'],
        'title' => $b['title'],
        'subtitle' => $b['subtitle'],
        'btn' => !empty($b['button_text']) ? $b['button_text'] : 'Lihat Produk Kami'
    ];
}

// 4. Mengambil Produk Unggulan dari Database
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.is_featured = 1 ORDER BY p.id DESC LIMIT 5");
    $stmt->execute();
    $products_db = $stmt->fetchAll();
} catch (Exception $e) { $products_db = []; }

$products = [];
foreach($products_db as $p) {
    $products[] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'slug' => $p['slug'],
        'type' => $p['category_name'] ?? 'TEIN',
        'short_description' => $p['short_description'],
        // price bisa null di DB; default ke 0 agar number_format tidak memunculkan deprecation warning.
        'price' => $p['price'] ?? 0,
        'main_image' => 'uploads/products/' . $p['main_image']
    ];
}
?>

    <!-- ── HERO SLIDER ── -->
    <section class="relative w-full h-[60vh] md:h-[75vh] min-h-[450px] overflow-hidden group bg-[#0D0F0D]">
        <?php if(!empty($banners)): ?>
        <div id="hero-slider" class="w-full h-full relative">
            <?php foreach($banners as $index => $banner): ?>
            <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/60"></div>
                <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-4">
                    <div class="slide-content">
                        <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-white mb-5 tracking-tight drop-shadow-md"><?= htmlspecialchars($banner['title']) ?></h1>
                        <p class="text-base md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto drop-shadow font-medium"><?= htmlspecialchars($banner['subtitle']) ?></p>
                        <a href="#produk" class="bg-[#00A651] hover:bg-[#00833F] text-white font-bold py-3.5 px-8 rounded transition-all shadow-lg hover:shadow-xl inline-block"><?= htmlspecialchars($banner['btn']) ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if(count($banners) > 1): ?>
        <button onclick="changeSlide(-1)" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-12 md:h-12 bg-black/50 hover:bg-black/90 text-white flex items-center justify-center rounded border border-white/10 opacity-0 group-hover:opacity-100 transition-all duration-300" aria-label="Slide sebelumnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button onclick="changeSlide(1)" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-12 md:h-12 bg-black/50 hover:bg-black/90 text-white flex items-center justify-center rounded border border-white/10 opacity-0 group-hover:opacity-100 transition-all duration-300" aria-label="Slide berikutnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        <div class="absolute bottom-6 left-0 right-0 z-30 flex justify-center gap-2">
            <?php foreach($banners as $index => $banner): ?>
            <div class="dot w-2 h-2 rounded-full <?= $index === 0 ? 'bg-white' : 'bg-white/40' ?> cursor-pointer" onclick="goToSlide(<?= $index ?>)"></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <!-- Fallback jika tidak ada banner aktif di database -->
        <div class="w-full h-full flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-5 tracking-tight">TEIN SUSPENSI INDONESIA</h1>
            <p class="text-base md:text-xl text-gray-300 mb-8 max-w-2xl mx-auto font-medium">Suspensi No.1 Jepang, teruji kualitasnya.</p>
            <a href="#produk" class="bg-[#00A651] hover:bg-[#00833F] text-white font-bold py-3.5 px-8 rounded transition-all shadow-lg hover:shadow-xl inline-block">Lihat Produk Kami</a>
        </div>
        <?php endif; ?>
    </section>

    <!-- ── FITUR TEKNOLOGI ── -->
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 lg:max-w-6xl">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">
                <!-- Konten Statis Fitur -->
                <div class="reveal text-center group cursor-pointer">
                    <div class="w-full h-24 md:h-32 mx-auto rounded-xl mb-3 flex items-center justify-center bg-gray-50 group-hover:bg-[#E9F8EF] transition-colors p-3 border border-transparent group-hover:border-[#00A651]/20">
                        <img src="https://placehold.co/300x200/transparent/00a651?text=HBS" alt="HBS" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">TEKNOLOGI</p>
                    <h4 class="text-sm md:text-base text-[#0D0F0D] font-black uppercase leading-tight group-hover:text-[#00A651] transition-colors">HBS SANGAT HALUS</h4>
                </div>
                <div class="reveal text-center group cursor-pointer" style="transition-delay:100ms">
                    <div class="w-full h-24 md:h-32 mx-auto rounded-xl mb-3 flex items-center justify-center bg-gray-50 group-hover:bg-[#E9F8EF] transition-colors p-3 border border-transparent group-hover:border-[#00A651]/20">
                        <img src="https://placehold.co/300x200/transparent/00a651?text=Tube" alt="Tube" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">TABUNG GANDA</p>
                    <h4 class="text-sm md:text-base text-[#0D0F0D] font-black uppercase leading-tight group-hover:text-[#00A651] transition-colors">PERFORMA TINGGI</h4>
                </div>
                <div class="reveal text-center group cursor-pointer" style="transition-delay:200ms">
                    <div class="w-full h-24 md:h-32 mx-auto rounded-xl mb-3 flex items-center justify-center bg-gray-50 group-hover:bg-[#E9F8EF] transition-colors p-3 border border-transparent group-hover:border-[#00A651]/20">
                        <img src="https://placehold.co/300x200/transparent/00a651?text=Body" alt="Body" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">BODI SHOCK</p>
                    <h4 class="text-sm md:text-base text-[#0D0F0D] font-black uppercase leading-tight group-hover:text-[#00A651] transition-colors">LEBIH BESAR OEM</h4>
                </div>
                <div class="reveal text-center group cursor-pointer" style="transition-delay:300ms">
                    <div class="w-full h-24 md:h-32 mx-auto rounded-xl mb-3 flex items-center justify-center bg-gray-50 group-hover:bg-[#E9F8EF] transition-colors p-3 border border-transparent group-hover:border-[#00A651]/20">
                        <img src="https://placehold.co/300x200/transparent/00a651?text=Oil" alt="Oil" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">OLI PERFORMA</p>
                    <h4 class="text-sm md:text-base text-[#0D0F0D] font-black uppercase leading-tight group-hover:text-[#00A651] transition-colors">PEREDAMAN EFEKTIF</h4>
                </div>
                <div class="reveal text-center group cursor-pointer" style="transition-delay:400ms">
                    <div class="w-full h-24 md:h-32 mx-auto rounded-xl mb-3 flex items-center justify-center bg-gray-50 group-hover:bg-[#E9F8EF] transition-colors p-3 border border-transparent group-hover:border-[#00A651]/20">
                        <img src="https://placehold.co/300x200/transparent/00a651?text=Inst" alt="Install" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">PEMASANGAN PnP</p>
                    <h4 class="text-sm md:text-base text-[#0D0F0D] font-black uppercase leading-tight group-hover:text-[#00A651] transition-colors">GARANSI 1 TAHUN</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- ── PENCARIAN ── -->
    <section class="pb-16 pt-8 bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 reveal">
            <div class="bg-[#27a844] rounded-xl p-6 md:p-8 flex flex-col lg:flex-row items-center gap-6 shadow-[0_10px_30px_rgba(39,168,68,0.3)] relative overflow-hidden">
                <div class="text-white font-black text-xl lg:text-2xl leading-tight lg:w-1/4 uppercase z-10 w-full text-center lg:text-left tracking-wide">MOBIL APA YANG<br>ANDA GUNAKAN?</div>
                <form action="pencarian.php" method="GET" class="flex-grow flex flex-col md:flex-row gap-6 w-full z-10 items-end">
                    <div class="flex-1 w-full relative">
                        <label class="text-white/90 text-xs font-bold uppercase mb-2 block tracking-wider">Pabrikan Mobil</label>
                        <select id="select-merk" name="merk" class="w-full bg-transparent text-white font-bold text-lg border-b-2 border-white/40 pb-2 focus:outline-none focus:border-white appearance-none cursor-pointer">
                            <option value="" class="text-gray-800">Pilih Pabrikan...</option>
                            <option value="honda" class="text-gray-800">Honda</option>
                            <option value="toyota" class="text-gray-800">Toyota</option>
                            <option value="mitsubishi" class="text-gray-800">Mitsubishi</option>
                        </select>
                        <div class="absolute right-0 bottom-3 pointer-events-none text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                    </div>
                    <div class="flex-1 w-full relative">
                        <label class="text-white/90 text-xs font-bold uppercase mb-2 block tracking-wider">Model Mobil</label>
                        <select id="select-model" name="model" class="w-full bg-transparent text-white font-bold text-lg border-b-2 border-white/40 pb-2 focus:outline-none focus:border-white appearance-none cursor-pointer">
                            <option value="" class="text-gray-800">Pilih Model...</option>
                        </select>
                        <div class="absolute right-0 bottom-3 pointer-events-none text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                    </div>
                    <div class="w-full lg:w-1/3 relative mt-4 md:mt-0">
                        <input type="text" name="keyword" placeholder="Kata Kunci..." class="w-full py-4 pl-6 pr-14 rounded font-bold text-[#0D0F0D] focus:outline-none focus:ring-2 focus:ring-white shadow-inner bg-white">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-white hover:bg-[#00833F] p-2 bg-[#00A651] rounded transition-colors" aria-label="Cari">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ── TENTANG ── -->
    <section id="tentang" class="relative w-full py-24 md:py-32 bg-[#0D0F0D] overflow-hidden group">
        <img src="https://placehold.co/1920x800/1a1a1a/00A651?text=TEIN+Suspension+Workshop" alt="Workshop TEIN" class="absolute inset-0 w-full h-full object-cover opacity-40 transform group-hover:scale-105 transition-transform duration-1000" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0D0F0D]/90 via-[#0D0F0D]/70 to-transparent pointer-events-none"></div>
        <div class="container mx-auto px-6 relative z-10 flex flex-col lg:flex-row gap-12 items-center">
            <div class="lg:w-2/3 reveal">
                <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur text-[#00A651] text-xs font-black tracking-widest uppercase mb-4 border border-[#00A651]/30 rounded">Dibuat di Jepang</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black italic mb-6 leading-tight text-white uppercase tracking-tight drop-shadow-lg">
                    SUSPENSI TEIN ADALAH SUSPENSI NYAMAN<br>
                    <span class="text-[#00A651]">JIKA ANDA INGIN BERKENDARA NYAMAN, HARUS PASANG TEIN!</span>
                </h2>
                <div class="text-gray-300 text-sm md:text-base leading-relaxed space-y-4 mb-8 max-w-3xl">
                    <p>TEIN adalah merek dari Jepang, terkenal dengan kualitas dan teknologinya yang terbaik di industri otomotif. Sejak awal pendiriannya, TEIN telah mendefinisikan misinya untuk memberikan pengalaman berkendara yang sempurna dengan peredam kejut berkekuatan tinggi.</p>
                </div>
                <a href="tentang.php" class="inline-flex items-center gap-2 bg-[#00A651] text-white font-bold px-6 py-3 rounded hover:bg-[#00833F] transition-all shadow-lg">
                    BACA SELENGKAPNYA
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div id="garansi" class="lg:w-1/3 reveal" style="transition-delay:200ms">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl relative overflow-hidden text-center">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#00A651] rounded-full blur-3xl opacity-30"></div>
                    <div class="font-black text-xs text-white/70 uppercase mb-1">Daftar | Cek</div>
                    <div class="font-black text-2xl text-white uppercase mb-6 tracking-wide">GARANSI TEIN</div>
                    <img src="https://placehold.co/180x180/00833F/FFD700?text=Sertifikat" alt="Badge Garansi" class="w-32 h-32 mx-auto drop-shadow-xl rounded-full border-[3px] border-yellow-400 mb-6" loading="lazy">
                    <a href="#" class="block w-full border-2 border-white text-white font-bold py-2 rounded hover:bg-white hover:text-[#0D0F0D] transition-colors">Cek Garansi Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ── PRODUK ── -->
    <section id="produk" class="py-16 bg-white">
        <div class="container mx-auto px-4 lg:max-w-[1400px]">
            <div class="text-center mb-6 reveal">
                <h2 class="text-3xl font-black text-[#00A651] uppercase tracking-tight">PRODUK UNGGULAN</h2>
            </div>

            <?php if(!empty($products)): ?>
            <!-- Desktop Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 px-4 md:px-8">
                <?php foreach($products as $index => $row): ?>
                <div class="reveal flex flex-col group cursor-pointer" style="transition-delay:<?= $index * 100 ?>ms">
                    <div class="relative w-full aspect-[4/5] bg-gray-50 mb-3 overflow-hidden rounded-lg">
                        <img src="<?= htmlspecialchars($row['main_image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    </div>
                    <div class="font-black italic text-xl leading-none mb-2 text-[#0D0F0D] uppercase">
                        <?php
                            $typeParts = explode(' ', $row['type'], 2);
                            echo htmlspecialchars($typeParts[0]);
                            if(isset($typeParts[1])) {
                                echo '<br><span class="text-sm font-extrabold">' . htmlspecialchars($typeParts[1]) . '</span>';
                            }
                        ?>
                    </div>
                    <p class="text-[10px] text-gray-600 uppercase mb-3 line-clamp-3 leading-relaxed">Peredam kejut untuk <strong><?= htmlspecialchars($row['name']) ?></strong> — <?= htmlspecialchars($row['short_description']) ?></p>
                    <div class="mt-auto">
                        <div class="font-bold text-xs text-[#0D0F0D] mb-3">Rp <?= number_format($row['price'], 0, ',', '.') ?> <span class="text-[9px]">/set</span></div>
                        <div class="flex text-[10px] text-gray-500 pt-3 border-t border-gray-200">
                            <a href="produk-detail.php?slug=<?= htmlspecialchars($row['slug']) ?>" class="w-1/2 pr-2 hover:text-[#00A651] underline underline-offset-2 decoration-gray-300 transition-colors">Deskripsi Produk</a>
                            <div class="w-[1px] bg-gray-200"></div>
                            <a href="https://wa.me/<?= htmlspecialchars($settings['phone']) ?>?text=Halo%20saya%20tertarik%20dengan%20<?= urlencode($row['name']) ?>" target="_blank" class="w-1/2 pl-3 hover:text-[#00A651] underline underline-offset-2 decoration-gray-300 transition-colors">Konsultasi sekarang</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-center text-gray-500 text-sm py-8">Belum ada produk unggulan yang ditampilkan saat ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ── SOROTAN ── -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-black text-[#0D0F0D] uppercase tracking-tight">Sorotan</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Data Sorotan (Statis) -->
                <a href="#garansi" class="reveal block group rounded-2xl overflow-hidden relative shadow-lg h-72">
                    <img src="https://placehold.co/400x600/1a1a1a/00A651?text=Garansi" alt="Pendaftaran Garansi TEIN" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#00A651] via-[#00A651]/50 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-6 z-10 w-full">
                        <h3 class="font-black text-white text-lg uppercase mb-1">Pendaftaran Garansi</h3>
                        <p class="text-white/80 text-xs font-medium leading-relaxed">Halaman pendaftaran garansi resmi produk TEIN Indonesia.</p>
                    </div>
                </a>
                <a href="dealer.php" class="reveal block group rounded-2xl overflow-hidden relative shadow-lg h-72" style="transition-delay:100ms">
                    <img src="https://placehold.co/400x600/1a1a1a/00A651?text=Distribusi" alt="Sistem Distribusi TEIN" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#00A651] via-[#00A651]/50 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-6 z-10 w-full">
                        <h3 class="font-black text-white text-lg uppercase mb-1">Sistem Distribusi</h3>
                        <p class="text-white/80 text-xs font-medium leading-relaxed">Jaringan dealer asli dan titik distribusi TEIN secara nasional.</p>
                    </div>
                </a>
                <a href="teknis.php" class="reveal block group rounded-2xl overflow-hidden relative shadow-lg h-72" style="transition-delay:200ms">
                    <img src="https://placehold.co/400x600/1a1a1a/00A651?text=Teknis" alt="Bantuan Teknis TEIN" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#00A651] via-[#00A651]/50 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-6 z-10 w-full">
                        <h3 class="font-black text-white text-lg uppercase mb-1">Bantuan Teknis</h3>
                        <p class="text-white/80 text-xs font-medium leading-relaxed">Dasar-dasar instruksi teknis peredam kejut dari ahli TEIN.</p>
                    </div>
                </a>
                <a href="dealer.php" class="reveal block group rounded-2xl overflow-hidden relative shadow-lg h-72" style="transition-delay:300ms">
                    <img src="https://placehold.co/400x600/1a1a1a/00A651?text=Instalasi" alt="Lokasi Instalasi TEIN" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#00A651] via-[#00A651]/50 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-6 z-10 w-full">
                        <h3 class="font-black text-white text-lg uppercase mb-1">Lokasi Instalasi</h3>
                        <p class="text-white/80 text-xs font-medium leading-relaxed">Temukan alamat bengkel instalasi dan dukungan terdekat Anda.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

<?php
// 5. Panggil file footer
require_once 'includes/footer.php';
?>

<!-- SCRIPT SPESIFIK HALAMAN BERANDA (TIDAK DITARUH DI FOOTER.PHP KARENA HANYA BERLAKU DI SINI) -->
<script>
    // ── HERO SLIDER ──
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots   = document.querySelectorAll('.dot');
    let slideInterval;

    function updateSlider(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => { d.classList.remove('bg-white'); d.classList.add('bg-white/40'); });
        if (slides[index]) slides[index].classList.add('active');
        if (dots[index]) { dots[index].classList.remove('bg-white/40'); dots[index].classList.add('bg-white'); }
    }
    function changeSlide(step) {
        if (slides.length === 0) return;
        currentSlide = (currentSlide + step + slides.length) % slides.length;
        updateSlider(currentSlide);
        resetInterval();
    }
    function goToSlide(index) {
        currentSlide = index;
        updateSlider(currentSlide);
        resetInterval();
    }
    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(() => changeSlide(1), 6000);
    }
    if (slides.length > 1) resetInterval();

    // ── DROPDOWN MODEL MOBIL DINAMIS ──
    const modelsByBrand = {
        honda:      ['Brio','Jazz','City','Civic','CR-V','HR-V','BR-V'],
        toyota:     ['Avanza','Veloz','Rush','Raize','Innova Zenix','Fortuner','Yaris'],
        mitsubishi: ['Xpander','Pajero Sport','Outlander','Triton']
    };
    const selectMerk  = document.getElementById('select-merk');
    const selectModel = document.getElementById('select-model');
    if (selectMerk && selectModel) {
        selectMerk.addEventListener('change', function() {
            const models = modelsByBrand[this.value] || [];
            selectModel.innerHTML = '<option value="" class="text-gray-800">Pilih Model...</option>';
            models.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.toLowerCase().replace(/\s+/g, '-');
                opt.className = 'text-gray-800';
                opt.textContent = m;
                selectModel.appendChild(opt);
            });
        });
    }
</script>