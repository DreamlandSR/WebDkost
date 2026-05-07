        document.addEventListener('DOMContentLoaded', function() {
            // Cek semua elemen yang dibutuhkan
            const scrollContainer = document.getElementById('scrollContainer');
            const scrollProgress = document.getElementById('scrollProgress');
            const navDots = document.querySelectorAll('.nav-dot');
            const sections = document.querySelectorAll('.full-page-section');
            
            // Debug: cek apakah elemen ditemukan
            console.log('scrollContainer:', scrollContainer);
            console.log('scrollProgress:', scrollProgress);
            console.log('navDots count:', navDots.length);
            console.log('sections count:', sections.length);
            
            // VALIDASI - Jika scrollContainer tidak ada, STOP
            if (!scrollContainer) {
                console.error('ERROR: #scrollContainer tidak ditemukan!');
                return;
            }
            
            if (!scrollProgress) {
                console.error('ERROR: #scrollProgress tidak ditemukan!');
            }
            
            // Update scroll progress
            function updateScrollProgress() {
                if (!scrollProgress) return;
                const scrollTop = scrollContainer.scrollTop;
                const scrollHeight = scrollContainer.scrollHeight - scrollContainer.clientHeight;
                const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
                scrollProgress.style.width = progress + '%';
            }

            // Update active nav dot
            function updateActiveDot() {
                if (navDots.length === 0) return;
                
                const scrollTop = scrollContainer.scrollTop;
                const windowHeight = scrollContainer.clientHeight;
                
                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionBottom = sectionTop + section.offsetHeight;
                    
                    if (scrollTop + windowHeight / 2 >= sectionTop && scrollTop + windowHeight / 2 < sectionBottom) {
                        navDots.forEach(dot => dot.classList.remove('active'));
                        if (navDots[index]) navDots[index].classList.add('active');
                    }
                });
            }

            // Scroll to section on dot click
            if (navDots.length > 0) {
                navDots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        if (sections[index]) {
                            sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            }

            // Add scroll event listeners
            scrollContainer.addEventListener('scroll', () => {
                updateScrollProgress();
                updateActiveDot();
            });

            // Initial calls
            updateScrollProgress();
            updateActiveDot();

            // Carousel text synchronization
            const carousel = document.querySelector('#productCarousel');
            if (carousel) {
                carousel.addEventListener('slide.bs.carousel', function(e) {
                    const nextSlide = carousel.querySelectorAll('.carousel-item')[e.to];
                    if (nextSlide) {
                        const productName = document.getElementById('productName');
                        const productDesc = document.getElementById('productDesc');
                        const productTipe = document.getElementById('productTipe');
                        const productHarga = document.getElementById('productHarga');
                        
                        if (productName) productName.textContent = nextSlide.dataset.kamarNama || '';
                        if (productDesc) productDesc.textContent = nextSlide.dataset.kamarDesc || '';
                        if (productTipe) productTipe.textContent = nextSlide.dataset.kamarTipe || '';
                        if (productHarga) productHarga.textContent = nextSlide.dataset.kamarHarga || '';
                    }
                });
            }

            // Intersection Observer for fade animations
            const fadeElements = document.querySelectorAll('.animate-fade-up, .animate-left, .animate-right, .animate-scale');
            if (fadeElements.length > 0) {
                const fadeObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translate(0) scale(1)';
                            fadeObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });
                
                fadeElements.forEach(element => {
                    element.style.opacity = '0';
                    if (element.classList.contains('animate-fade-up')) {
                        element.style.transform = 'translateY(30px)';
                    } else if (element.classList.contains('animate-left')) {
                        element.style.transform = 'translateX(-50px)';
                    } else if (element.classList.contains('animate-right')) {
                        element.style.transform = 'translateX(50px)';
                    } else if (element.classList.contains('animate-scale')) {
                        element.style.transform = 'scale(0.9)';
                    }
                    fadeObserver.observe(element);
                });
            }
            
            console.log('✅ JavaScript initialized successfully');
        });
    