
window.addEventListener('scroll', function() {
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
  