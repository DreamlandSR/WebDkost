/**
 * pengeluaran.js
 * Semua logika interaktif halaman Laporan Pengeluaran
 */

/* ============================================================
   1. Kategori Pill — Modal Tambah
   ============================================================ */

/**
 * Dipanggil via onchange di setiap radio kategori (modal tambah).
 * @param {HTMLInputElement} radio
 */
function selectKategori(radio) {
    // Reset semua pill
    document.querySelectorAll('.pill-label').forEach(function (el) {
        el.style.background   = '#f9fafb';
        el.style.color        = '#6b7280';
        el.style.borderColor  = '#e5e7eb';
        el.style.fontWeight   = '500';
    });

    // Aktifkan pill yang dipilih
    var lbl = radio.nextElementSibling;
    lbl.style.background  = '#ecfdf5';
    lbl.style.color       = '#00a669';
    lbl.style.borderColor = '#00a669';
    lbl.style.fontWeight  = '600';
}

/* ============================================================
   2. Kategori Pill — Modal Edit
   ============================================================ */

/**
 * Dipanggil via onchange di setiap radio kategori (modal edit).
 * @param {HTMLInputElement} radio
 * @param {number|string}    id    — id_pengeluaran baris yang sedang diedit
 */
function updateEditPill(radio, id) {
    document.querySelectorAll('.pill-label-edit-' + id).forEach(function (el) {
        el.classList.remove('pill-active-edit');
    });
    radio.nextElementSibling.classList.add('pill-active-edit');
}

/* ============================================================
   3. Reset Modal Tambah saat ditutup
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    var tambahModal = document.getElementById('tambahDataModal');
    if (tambahModal) {
        tambahModal.addEventListener('hidden.bs.modal', function () {
            // Reset semua pill ke state default
            tambahModal.querySelectorAll('.pill-label').forEach(function (el) {
                el.style.background  = '#f9fafb';
                el.style.color       = '#6b7280';
                el.style.borderColor = '#e5e7eb';
                el.style.fontWeight  = '500';
            });

            // Uncheck semua radio kategori
            tambahModal.querySelectorAll('input[type="radio"]').forEach(function (r) {
                r.checked = false;
            });
        });
    }

    /* ----------------------------------------------------------
       4. Auto-dismiss success alert setelah 4 detik
       ---------------------------------------------------------- */
    var successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function () {
            successAlert.style.display = 'none';
        }, 4000);
    }
});
