
window.addEventListener('scroll', function () {
  const elements = document.querySelectorAll('.fade-in, .slide-in');
  elements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const triggerPoint = window.innerHeight * 0.9; // Titik trigger 90% dari tinggi viewport
    if (elementTop < triggerPoint) {
      element.classList.add('visible');
    }
  });
});

// Jalankan fungsi saat halaman pertama kali dimuat untuk menampilkan elemen yang sudah terlihat
window.dispatchEvent(new Event('scroll'));

/* ===== Ganti foto utama saat thumbnail diklik (Kamar Detail) ===== */
window.changeMainImage = function(el, url) {
    const mainImg = document.getElementById('mainImage');
    if (!mainImg) return;
    
    mainImg.style.opacity = '0';
    setTimeout(() => {
        mainImg.src = url;
        mainImg.style.opacity = '1';
    }, 200);

    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
};

/* ===== Kalkulasi total harga sewa (Kamar Detail) ===== */
document.addEventListener('DOMContentLoaded', function () {
    const selectDurasi = document.getElementById('durasi');
    const totalEl = document.getElementById('totalHarga');

    if (selectDurasi && totalEl) {
        const hargaPerBulan = parseInt(selectDurasi.getAttribute('data-harga'), 10);

        function formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID').replace(/,/g, '.'); // Handle fallback locale
        }

        function updateTotal() {
            const bulan = parseInt(selectDurasi.value, 10);
            totalEl.textContent = formatRupiah(hargaPerBulan * bulan);
        }

        selectDurasi.addEventListener('change', updateTotal);
        updateTotal();
    }
});

/* ===== Filter Ulasan Modal (Kamar Detail) ===== */
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-review');
    const reviewItems = document.querySelectorAll('.detail-review-item');
    const emptyState = document.getElementById('emptyReviewState');

    if(filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Reset semua styling button
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'btn-success');
                    b.classList.add('btn-outline-secondary');
                });
                
                // Aktifkan button yang di-klik
                const target = e.currentTarget;
                target.classList.add('active', 'btn-success');
                target.classList.remove('btn-outline-secondary');

                const starFilter = target.dataset.star;
                let visibleCount = 0;

                reviewItems.forEach(item => {
                    if (starFilter === 'all' || item.dataset.rating === starFilter) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (visibleCount === 0) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                }
            });
        });
    }
});
