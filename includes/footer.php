<!-- ── FOOTER ── -->
    <footer id="footer" class="w-full mt-auto">
        
        <<div class="bg-[#0D0F0D] py-14 border-t-2 border-white/20">
                <div class="container mx-auto px-6 lg:max-w-7xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 text-left">
                        <div class="text-white lg:pr-8 lg:border-r border-white/30">
                        <a href="<?= BASE_URL ?>/index.php" class="block mb-5 hover:opacity-80 transition-opacity duration-300">
                            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo TEIN" class="w-[160px] md:w-[200px] object-contain" loading="lazy">
                        </a>
                        
                        <ul class="space-y-4 text-sm font-medium leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-white shrink-0"></span>
                                <?= htmlspecialchars($settings['address'] ?? 'Jakarta, Indonesia') ?>
                            </li>
                        </ul>
                    </div>
                    <div class="text-white lg:pl-4">
                        <h4 class="font-bold text-[15px] uppercase mb-5 tracking-wide">PRODUK KAMI</h4>
                        <ul class="space-y-3 text-sm font-medium">
                            <li><a href="<?= BASE_URL ?>/produk.php" class="hover:underline">Suspensi TEIN Basic</a></li>
                            <li><a href="<?= BASE_URL ?>/produk.php" class="hover:underline">Suspensi TEIN EnduraPro</a></li>
                            <li><a href="<?= BASE_URL ?>/produk.php" class="hover:underline">Suspensi TEIN EnduraPro Plus</a></li>
                            <li><a href="<?= BASE_URL ?>/produk.php" class="hover:underline">Suspensi TEIN Street Advance Z4</a></li>
                        </ul>
                    </div>
                    <div class="text-white">
                        <h4 class="font-bold text-[15px] uppercase mb-5 tracking-wide">KEBIJAKAN</h4>
                        <ul class="space-y-3 text-sm font-medium">
                            <li><a href="<?= BASE_URL ?>/tentang.php" class="hover:underline">Kebijakan Instalasi &amp; Garansi</a></li>
                            <li><a href="<?= BASE_URL ?>/tentang.php" class="hover:underline">Kebijakan Pengiriman</a></li>
                            <li><a href="<?= BASE_URL ?>/tentang.php" class="hover:underline">Kebijakan Anti Pemalsuan</a></li>
                        </ul>
                    </div>
                    <div class="text-white">
                        <h4 class="font-bold text-[15px] uppercase mb-5 tracking-wide">HUBUNGI KAMI</h4>
                        <ul class="space-y-3 text-sm font-medium">
                            <li>Email: <a href="mailto:<?= htmlspecialchars($settings['email'] ?? 'info@tein.id') ?>" class="hover:underline"><?= htmlspecialchars($settings['email'] ?? 'info@tein.id') ?></a></li>
                            <li>WhatsApp: <a href="https://wa.me/<?= htmlspecialchars($settings['phone'] ?? '6285219463121') ?>" class="hover:underline">+<?= htmlspecialchars($settings['phone'] ?? '6285219463121') ?></a></li>
                            <li>Website: <a href="<?= BASE_URL ?>" class="hover:underline">www.tein.id</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#0D0F0D] py-6 border-t border-white/20">
            <div class="container mx-auto px-6 text-center">
                <div class="text-[11px] md:text-xs text-white font-bold tracking-wide">&copy; <?= date('Y') ?> <?= htmlspecialchars($settings['site_name'] ?? 'TEIN Indonesia') ?>. Seluruh hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>

    <!-- ── FLOATING BUTTONS ── -->
    <a href="<?= BASE_URL ?>/index.php#garansi" class="fixed left-4 bottom-6 z-50 bg-white border border-[#00A651] text-[#00A651] hover:bg-[#00A651] hover:text-white flex items-center gap-2 px-3 py-2 rounded-full shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <svg class="w-4 h-4 md:w-5 md:h-5 text-[#00A651] group-hover:text-white transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        <span class="font-black text-[10px] md:text-xs uppercase tracking-wide pr-1">Daftar / Cek Garansi</span>
    </a>

    <div class="fixed right-4 bottom-[80px] z-50 flex flex-col gap-3 items-center">
        <a href="https://wa.me/<?= htmlspecialchars($settings['phone'] ?? '6285219463121') ?>" target="_blank" rel="noopener" title="WhatsApp" class="w-12 h-12 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="<?= htmlspecialchars($settings['instagram_url'] ?? 'https://instagram.com/tein_inc') ?>" target="_blank" rel="noopener" title="Instagram" class="w-12 h-12 text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform" style="background:radial-gradient(circle at 30% 107%,#fdf497 0%,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285AEB 90%)">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>
    </div>

    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    </button>

    <!-- ── GLOBAL JAVASCRIPT ── -->
    <script>
        // HEADER SCROLL
        // header-wrapper hanya ada jika header.php dimuat di halaman ini;
        // dicek agar tidak melempar error dan menghentikan script di bawahnya.
        const headerWrapper = document.getElementById('header-wrapper');
        if (headerWrapper) {
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        if (window.scrollY <= 80) headerWrapper.classList.remove('scrolled');
                        else headerWrapper.classList.add('scrolled');
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        // MOBILE MENU
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen   = document.getElementById('icon-open');
        const iconClose  = document.getElementById('icon-close');

        if (menuToggle && mobileMenu && iconOpen && iconClose) {
            menuToggle.addEventListener('click', () => {
                // wasOpen = status menu SEBELUM tombol ini diklik
                const wasOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden', wasOpen);
                iconOpen.classList.toggle('hidden', !wasOpen);
                iconClose.classList.toggle('hidden', wasOpen);
                menuToggle.setAttribute('aria-expanded', String(!wasOpen));
            });
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                    menuToggle.setAttribute('aria-expanded', 'false');
                });
            });
        }

        // SCROLL REVEAL
        function reveal() {
            document.querySelectorAll('.reveal').forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight - 80) el.classList.add('active');
            });
        }
        window.addEventListener('scroll', reveal);
        reveal();

        // BACK TO TOP
        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', () => {
                backToTop.classList.toggle('show', window.scrollY > 300);
            });
        }
    </script>
</body>
</html>