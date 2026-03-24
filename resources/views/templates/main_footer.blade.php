<footer class="dk-footer py-5 slide-in">
    <div class="container">
        <div class="row gy-4">
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Tentang kami</h5>
                <p class="footer-text">D'Kost merupakan platform pemesanan kos online terpercaya yang terletak di Jember.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-circle"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-circle"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Berita terakhir</h5>
                <div class="news-item mb-3">
                    <a href="#" class="footer-link">Kamar baru dekat kampus dengan fasilitas AC + Wi-Fi</a>
                    <div class="text-muted small">Mar 18, 2026 &nbsp; Admin</div>
                </div>
                <div class="news-item mb-2">
                    <a href="#" class="footer-link">Promo early bird: potongan 20% booking bulan pertama</a>
                    <div class="text-muted small">Mar 9, 2026 &nbsp; Admin</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Link cepat</h5>
                <ul class="list-unstyled footer-nav">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('product') }}">Product</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Ada pertanyaan?</h5>
                <ul class="list-unstyled footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i>  Jl. Mastrip, Krajan Timur, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121.</li>
                    <li><i class="bi bi-telephone-fill"></i> +6277 05611 2113</li>
                    <li><i class="bi bi-envelope-fill"></i> dkost@gmail.com</li>
                </ul>
            </div>
        </div>
        <div class="row mt-4 pt-3 border-top footer-bottom">
            <div class="col text-center">
                <small>© {{ date('Y') }} D'Kost. Semua hak cipta dilindungi.</small>
            </div>
        </div>
    </div>
</footer>

