document.addEventListener('DOMContentLoaded', () => {
    // Render Lucide icons
    lucide.createIcons();

    // Set current copyright year
    document.getElementById('current-year').textContent = new Date().getFullYear();

    /* ==========================================================================
       A. PRELOADER ENGINE
       ========================================================================== */
    const preloader = document.getElementById('preloader');
    const preloaderBar = document.getElementById('preloader-bar');
    const preloaderPercentage = document.getElementById('preloader-percentage');
    let progress = 0;

    const interval = setInterval(() => {
        progress += Math.floor(Math.random() * 15) + 5;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            preloaderBar.style.width = '100%';
            preloaderPercentage.textContent = '100%';
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 500);
        } else {
            preloaderBar.style.width = `${progress}%`;
            preloaderPercentage.textContent = `${progress}%`;
        }
    }, 80);

    /* ==========================================================================
       B. STICKY NAVBAR & PROGRESS BAR
       ========================================================================== */
    const navbar = document.getElementById('navbar');
    const scrollProgress = document.getElementById('scroll-progress');
    const backToTopBtn = document.getElementById('btn-back-to-top');

    window.addEventListener('scroll', () => {
        // Header shrink & glass toggle
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        // Scroll progress percentage calculation
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight > 0) {
            const percentage = (window.scrollY / totalHeight) * 100;
            scrollProgress.style.width = `${percentage}%`;
        }

        // Floating back to top visibility
        if (window.scrollY > 400) {
            if (backToTopBtn) backToTopBtn.classList.add('show');
        } else {
            if (backToTopBtn) backToTopBtn.classList.remove('show');
        }
    });

    // Smooth back to top execution
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ==========================================================================
       C. SEARCH BAR CONTROLLER
       ========================================================================== */
    const searchToggle = document.getElementById('search-toggle');
    const searchBox = document.getElementById('search-box');

    if (searchToggle && searchBox) {
        searchToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            searchBox.classList.toggle('active');
            if (searchBox.classList.contains('active')) {
                searchBox.querySelector('input').focus();
            }
        });

        document.addEventListener('click', (e) => {
            if (!searchBox.contains(e.target) && e.target !== searchToggle) {
                searchBox.classList.remove('active');
            }
        });
    }

    /* ==========================================================================
       D. DARK / LIGHT MODE ENGINE
       ========================================================================== */
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    // Load theme configuration
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeToggleButton(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', targetTheme);
            localStorage.setItem('theme', targetTheme);
            updateThemeToggleButton(targetTheme);
        });
    }

    function updateThemeToggleButton(theme) {
        if (!themeIcon) return;
        if (theme === 'dark') {
            themeIcon.setAttribute('data-lucide', 'sun');
        } else {
            themeIcon.setAttribute('data-lucide', 'moon');
        }
        // Rerender Lucide icon inside switcher button
        lucide.createIcons();
    }

    /* ==========================================================================
       E. RESPONSIVE MOBILE NAVIGATION
       ========================================================================== */
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when items are clicked
        document.querySelectorAll('.nav-item a').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    /* ==========================================================================
       F. PARALLAX CANVAS HERO PARTICLES
       ========================================================================== */
    const canvas = document.getElementById('hero-particles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const particleCount = 45;

        function resizeCanvas() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 3 + 1;
                this.speedX = Math.random() * 0.4 - 0.2;
                this.speedY = Math.random() * 0.4 - 0.2;
            }

            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }

            draw() {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                ctx.fillStyle = isDark ? 'rgba(52, 211, 153, 0.4)' : 'rgba(16, 185, 129, 0.3)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }
        initParticles();

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Draw connecting threads
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dist = Math.hypot(particles[i].x - particles[j].x, particles[i].y - particles[j].y);
                    if (dist < 100) {
                        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                        ctx.strokeStyle = isDark ? `rgba(52, 211, 153, ${0.12 - dist/1000})` : `rgba(16, 185, 129, ${0.08 - dist/1000})`;
                        ctx.lineWidth = 0.8;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
                particles[i].update();
                particles[i].draw();
            }
            requestAnimationFrame(animateParticles);
        }
        animateParticles();
    }

    /* ==========================================================================
       G. SCROLL INTERSECTION REVEALS & NAVBAR ACTIVE SYNC
       ========================================================================== */
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    const sections = document.querySelectorAll('section');
    const navItems = document.querySelectorAll('.nav-item');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('reveal-left')) {
                    entry.target.classList.add('reveal-left-active');
                } else if (entry.target.classList.contains('reveal-right')) {
                    entry.target.classList.add('reveal-right-active');
                } else {
                    entry.target.classList.add('reveal-active');
                }
            }
        });
    }, { threshold: 0.15 });

    revealElements.forEach(el => revealObserver.observe(el));

    // Sync Nav Links Active highlights with section positioning
    window.addEventListener('scroll', () => {
        let currentSection = 'home';
        sections.forEach(sec => {
            const secTop = sec.offsetTop - 150;
            if (window.scrollY >= secTop) {
                currentSection = sec.getAttribute('id');
            }
        });

        navItems.forEach(item => {
            item.classList.remove('active');
            const link = item.querySelector('a');
            if (link && link.getAttribute('href') === `#${currentSection}`) {
                item.classList.add('active');
            }
        });
    });

    /* ==========================================================================
       H. INTERACTIVE MAP & REGIONS CONTROLLER
       ========================================================================== */
    const mapPaths = document.querySelectorAll('.map-province-path');
    const regionCards = document.querySelectorAll('.region-card');
    const regionDetails = document.querySelectorAll('.region-detail-overlay');
    const mapTooltip = document.getElementById('map-tooltip');

    mapPaths.forEach(path => {
        path.addEventListener('mouseenter', (e) => {
            if (!mapTooltip) return;
            const prov = path.getAttribute('data-province');
            mapTooltip.textContent = prov;
            mapTooltip.style.opacity = '1';
        });

        path.addEventListener('mousemove', (e) => {
            if (!mapTooltip) return;
            const containerRect = path.closest('.map-interactive-wrapper').getBoundingClientRect();
            
            const left = e.clientX - containerRect.left + 15;
            const top = e.clientY - containerRect.top - 35;
            
            mapTooltip.style.left = `${left}px`;
            mapTooltip.style.top = `${top}px`;
        });

        path.addEventListener('mouseleave', () => {
            if (mapTooltip) mapTooltip.style.opacity = '0';
        });

        // Path click synchronizes sidebar active card
        path.addEventListener('click', () => {
            const prov = path.getAttribute('data-province');
            let provId = 'sumut';
            if (prov === 'Aceh') provId = 'aceh';
            if (prov === 'West Sumatra') provId = 'sumbar';
            if (prov === 'Riau') provId = 'riau';

            triggerRegionSwitch(provId);
        });
    });

    regionCards.forEach(card => {
        card.addEventListener('click', () => {
            const provId = card.getAttribute('data-prov-id');
            triggerRegionSwitch(provId);
        });
    });

    function triggerRegionSwitch(provId) {
        // Switch sidebar cards
        regionCards.forEach(c => c.classList.remove('active'));
        const targetCard = document.querySelector(`.region-card[data-prov-id="${provId}"]`);
        if (targetCard) targetCard.classList.add('active');

        // Switch details accordion panels
        regionDetails.forEach(d => d.classList.remove('active'));
        const targetDetail = document.getElementById(`details-${provId}`);
        if (targetDetail) targetDetail.classList.add('active');

        // Highlight corresponding map svg path
        mapPaths.forEach(p => p.classList.remove('active'));
        let mapPathId = 'map-prov-sumut';
        if (provId === 'aceh') mapPathId = 'map-prov-aceh';
        if (provId === 'sumbar') mapPathId = 'map-prov-sumbar';
        if (provId === 'riau') mapPathId = 'map-prov-riau';
        
        const targetPath = document.getElementById(mapPathId);
        if (targetPath) targetPath.classList.add('active');
    }

    /* ==========================================================================
       I. CORE ACCORDION CONTROLLER (FAQ & Regulations)
       ========================================================================== */
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const parent = header.parentElement;
            const isActive = parent.classList.contains('active');
            
            // Collapse siblings in the same container if needed
            const container = parent.parentElement;
            container.querySelectorAll('.accordion-item').forEach(item => {
                item.classList.remove('active');
            });

            if (!isActive) {
                parent.classList.add('active');
            }
        });
    });

    /* ==========================================================================
       J. NUMERIC STATISTICS COUNT-UP EFFECT
       ========================================================================== */
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const numEl = entry.target;
                const targetVal = parseInt(numEl.getAttribute('data-target'));
                animateCountUp(numEl, targetVal);
                statsObserver.unobserve(numEl); // run only once
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(n => statsObserver.observe(n));

    function animateCountUp(element, target) {
        let current = 0;
        const duration = 1500; // ms
        const steps = 60;
        const increment = target / steps;
        const stepTime = duration / steps;

        const counter = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('id-ID');
                clearInterval(counter);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, stepTime);
    }

    /* ==========================================================================
       K. CUSTOM LIGHTBOX PHOTO PREVIEWER
       ========================================================================== */
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightbox = document.getElementById('lightbox');
    const lightboxContainer = document.getElementById('lightbox-img-container');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxPrev = document.getElementById('lightbox-prev');
    const lightboxNext = document.getElementById('lightbox-next');
    let currentGalleryIndex = 0;

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            currentGalleryIndex = index;
            openLightbox(item);
        });
    });

    function openLightbox(item) {
        if (!lightboxContainer) return;
        const imgSvgClone = item.querySelector('svg').cloneNode(true);
        const captionText = item.querySelector('.gallery-title').textContent;

        lightboxContainer.innerHTML = '';
        lightboxContainer.appendChild(imgSvgClone);
        if (lightboxCaption) lightboxCaption.textContent = captionText;

        if (lightbox) {
            lightbox.style.display = 'flex';
            setTimeout(() => lightbox.classList.add('active'), 10);
        }
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('active');
        setTimeout(() => { lightbox.style.display = 'none'; }, 400);
    }

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    
    if (lightbox) {
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });
    }

    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', () => {
            currentGalleryIndex = (currentGalleryIndex === 0) ? galleryItems.length - 1 : currentGalleryIndex - 1;
            openLightbox(galleryItems[currentGalleryIndex]);
        });
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', () => {
            currentGalleryIndex = (currentGalleryIndex === galleryItems.length - 1) ? 0 : currentGalleryIndex + 1;
            openLightbox(galleryItems[currentGalleryIndex]);
        });
    }

    /* ==========================================================================
       L. TESTIMONIALS SLIDER ENGINE
       ========================================================================== */
    const testimonialTrack = document.getElementById('testimonial-track');
    const testimonialSlides = document.querySelectorAll('.testimonial-slide');
    const dotsContainer = document.getElementById('testimonial-dots-container');
    let activeSlideIndex = 0;
    let slideInterval;

    if (testimonialTrack && testimonialSlides.length > 0 && dotsContainer) {
        // Generate Slide Dots
        testimonialSlides.forEach((_, idx) => {
            const dot = document.createElement('div');
            dot.classList.add('testimonial-dot');
            if (idx === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                goToSlide(idx);
                resetSlideTimer();
            });
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.testimonial-dot');

        function goToSlide(index) {
            testimonialTrack.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach(d => d.classList.remove('active'));
            dots[index].classList.add('active');
            activeSlideIndex = index;
        }

        function nextSlide() {
            const target = (activeSlideIndex === testimonialSlides.length - 1) ? 0 : activeSlideIndex + 1;
            goToSlide(target);
        }

        function startSlideTimer() {
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetSlideTimer() {
            clearInterval(slideInterval);
            startSlideTimer();
        }

        startSlideTimer();
    }

    /* ==========================================================================
       M. CLIENT FORM VALIDATION & SUCCESS TOAST FEEDBACKS
       ========================================================================== */
    const contactForm = document.getElementById('contact-form');
    const toast = document.getElementById('toast-success');
    const toastTitle = document.getElementById('toast-title');
    const toastMsg = document.getElementById('toast-msg');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Form field selectors
            const nameField = document.getElementById('form-name');
            const emailField = document.getElementById('form-email');
            const subjectField = document.getElementById('form-subject');
            const messageField = document.getElementById('form-message');

            // Super validation check
            let isValid = true;
            
            [nameField, emailField, subjectField, messageField].forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ef4444';
                    isValid = false;
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });

            // Check email format matches standard regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value.trim())) {
                emailField.style.borderColor = '#ef4444';
                isValid = false;
            }

            if (!isValid) {
                showToastAlert('Validation Error', 'Please check your inputs and try again.', '#ef4444');
                return;
            }

            // Simulate database push delay
            const submitBtn = document.getElementById('form-submit-btn');
            const submitOriginalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span style="animation: pulse-glow 1s infinite;">Sending...</span>';

            setTimeout(() => {
                showToastAlert('Message Sent!', `Thank you ${nameField.value.trim()}, our team will respond shortly.`, 'var(--primary)');
                contactForm.reset();
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitOriginalText;
            }, 1200);
        });
    }

    function showToastAlert(title, text, themeColor) {
        if (!toast || !toastTitle || !toastMsg) return;
        toastTitle.textContent = title;
        toastMsg.textContent = text;
        toast.style.borderLeftColor = themeColor;

        toast.classList.add('active');
        setTimeout(() => {
            toast.classList.remove('active');
        }, 4000);
    }

    /* ==========================================================================
       N. VIRTUAL LIVE CHAT ASSISTANT
       ========================================================================== */
    const liveChatTrigger = document.getElementById('btn-live-chat');
    const chatBox = document.getElementById('chat-box');
    const chatClose = document.getElementById('chat-close-btn');
    const chatMessagesArea = document.getElementById('chat-messages-area');
    const chatInputField = document.getElementById('chat-input-field');
    const chatSendBtn = document.getElementById('chat-send-btn');
    const chatSuggestions = document.querySelectorAll('.chat-suggest-btn');
    const chatTypingIndicator = document.getElementById('chat-typing');

    if (liveChatTrigger && chatBox) {
        liveChatTrigger.addEventListener('click', () => {
            chatBox.classList.toggle('active');
        });
    }

    if (chatClose && chatBox) {
        chatClose.addEventListener('click', () => {
            chatBox.classList.remove('active');
        });
    }

    if (chatSendBtn) {
        chatSendBtn.addEventListener('click', executeUserChatSend);
    }
    
    if (chatInputField) {
        chatInputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') executeUserChatSend();
        });
    }

    chatSuggestions.forEach(btn => {
        btn.addEventListener('click', () => {
            const query = btn.getAttribute('data-query');
            appendChatMessage(query, 'sent');
            triggerBotResponse(query);
        });
    });

    function executeUserChatSend() {
        if (!chatInputField) return;
        const text = chatInputField.value.trim();
        if (!text) return;

        appendChatMessage(text, 'sent');
        chatInputField.value = '';
        triggerBotResponse(text);
    }

    function appendChatMessage(text, sender) {
        if (!chatMessagesArea || !chatTypingIndicator) return;
        const msg = document.createElement('div');
        msg.classList.add('chat-msg');
        if (sender === 'sent') {
            msg.classList.add('chat-msg-sent');
        } else {
            msg.classList.add('chat-msg-received');
        }
        msg.textContent = text;

        // Insert before typing indicator
        chatMessagesArea.insertBefore(msg, chatTypingIndicator);
        chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
    }

    function triggerBotResponse(userText) {
        if (!chatTypingIndicator || !chatMessagesArea) return;
        chatTypingIndicator.style.display = 'flex';
        chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;

        let botResponse = "I apologize, I didn't quite catch that. Could you choose from the quick option buttons below?";
        
        const query = userText.toLowerCase();
        if (query.includes('what is') || query.includes('sip-opt')) {
            botResponse = "SIP-OPT is the official Plant Pest and Disease Attack Information System built by BBPPTP Medan to support monitoring, forecasting, and ecological protection across Sumatra.";
        } else if (query.includes('report') || query.includes('pest')) {
            botResponse = "To report a crop infestation, field coordinators must log in to the SIP-OPT Portal and submit coordinate validations. Public inquiries can be directed to contact@bbpptptmedan.go.id.";
        } else if (query.includes('who') || query.includes('operates') || query.includes('medan')) {
            botResponse = "BBPPTP Medan is an official technical unit of the Indonesian Ministry of Agriculture dedicated to plant protection and crop safety.";
        }

        setTimeout(() => {
            chatTypingIndicator.style.display = 'none';
            appendChatMessage(botResponse, 'received');
        }, 1000);
    }

    /* ==========================================================================
       O. MOCK AUTHENTICATION MODAL
       ========================================================================== */
    const loginModal = document.getElementById('login-modal');
    const loginOpenBtn = document.getElementById('login-open-btn');
    const heroLoginBtn = document.getElementById('hero-login-btn');
    const loginClose = document.getElementById('login-modal-close');
    const loginSubmitBtn = document.getElementById('login-submit-btn');

    function openLoginModal() {
        if (!loginModal) return;
        loginModal.style.display = 'flex';
        setTimeout(() => loginModal.classList.add('active'), 10);
    }

    function closeLoginModal() {
        if (!loginModal) return;
        loginModal.classList.remove('active');
        setTimeout(() => { loginModal.style.display = 'none'; }, 400);
    }

    if (loginOpenBtn) loginOpenBtn.addEventListener('click', openLoginModal);
    if (heroLoginBtn) heroLoginBtn.addEventListener('click', openLoginModal);
    
    if (loginClose) loginClose.addEventListener('click', closeLoginModal);
    
    if (loginModal) {
        loginModal.addEventListener('click', (e) => {
            if (e.target === loginModal) closeLoginModal();
        });
    }

    if (loginSubmitBtn) {
        loginSubmitBtn.addEventListener('click', () => {
            const user = document.getElementById('login-user').value.trim();
            const pass = document.getElementById('login-pass').value.trim();
            
            if (user && pass) {
                loginSubmitBtn.textContent = 'Verifying NIP...';
                setTimeout(() => {
                    closeLoginModal();
                    loginSubmitBtn.textContent = 'Authorize Session';
                    showToastAlert('Portal Access', `Welcome observer ${user}. Dynamic dashboard synced!`, 'var(--primary)');
                }, 1200);
            }
        });
    }
});
