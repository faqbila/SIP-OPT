<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-OPT</title>
    
    <!-- SEO Meta Tags -->
    <title>SIP-OPT | Sistem Informasi Peramalan Serangan Organisme Pengganggu Tanaman - BBPPTP Medan</title>
    <meta name="description" content="SIP-OPT is a modern, integrated digital platform by BBPPTP Medan for monitoring, forecasting, and managing plant pest and disease outbreaks in agricultural sectors.">
    <meta name="keywords" content="SIP-OPT, BBPPTP Medan, Plant Protection, Agricultural Pest Forecasting, Crop Diseases, Agricultural Technology, Plant Pest Monitoring">
    <meta name="author" content="BBPPTP Medan">
    <meta name="robots" content="index, follow">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Modularized Design Stylesheet -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Modularized Interactive Script -->
    <script src="app.js" defer></script>
</head>
<body>

    <!-- Scroll Progress Bar -->
    <div id="scroll-progress"></div>

    <!-- ==========================================================================
       1. PRELOADER & INTERACTIVE LOADING SCREEN
       ========================================================================== -->
    <div id="preloader">
    <div class="preloader-logo">
        <img src="logo2.png" alt="Logo SIP-OPT"
             style="width:120px; height:auto; object-fit:contain;">
        <div class="preloader-title">SIP-OPT</div>
    </div>

        <div class="preloader-bar-bg">
            <div class="preloader-bar-fill" id="preloader-bar"></div>
        </div>
        <div class="preloader-count" id="preloader-percentage">0%</div>
    </div>

    <!-- ==========================================================================
       2. FIXED STICKY NAVIGATION BAR
       ========================================================================== -->
    <header id="navbar">
        <div class="container nav-wrapper">
            <!-- Brand Logo -->
            <a href="#home" class="logo-block">
                <img src="logo2.png" alt="Logo" style="width: 50px; margin-bottom: 0px; margin-left: 50px;">
                <div class="logo-text">
                    <h1>SIP-OPT</h1>
                    <span>BBPPTP MEDAN</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav id="nav-menu">
                <ul class="nav-list">
                    <li class="nav-item active"><a href="#home">Home</a></li>
                    <li class="nav-item"><a href="#about">Tentang</a></li>
                    <li class="nav-item"><a href="#profile">Profil</a></li>
                    <li class="nav-item"><a href="#working-areas">Wilayah Kerja</a></li>
                    <li class="nav-item"><a href="#duties">Tugas</a></li>
                    <li class="nav-item"><a href="#regulations">Regulasi</a></li>
                    <li class="nav-item"><a href="#contact">Kontak</a></li>
                </ul>

                <!-- Theme / Search / Login action group -->
                <div class="nav-actions">
                    <!-- Search Bar Toggle -->
                    <div class="search-container" id="search-box">
                        <i data-lucide="search" class="search-icon-inside"></i>
                        <input type="text" class="search-input" placeholder="Search SIP-OPT...">
                    </div>
                    <button class="icon-btn" id="search-toggle" aria-label="Toggle Search bar">
                        <i data-lucide="search"></i>
                    </button>

                    <!-- Light / Dark Mode Toggle Button -->
                    <button class="icon-btn" id="theme-toggle" aria-label="Toggle light/dark theme">
                        <i data-lucide="moon" id="theme-icon"></i>
                    </button>

                    <!-- Login Button -->
                    <a href="login.php">
                    <button class="btn-login" id="login-open-btn">
                        <i data-lucide="log-in"></i>
                        <span>Login</span>
                    </button></a>
                </div>
            </nav>

            <!-- Responsive Toggle for Mobile -->
            <button class="mobile-toggle" id="menu-toggle" aria-label="Toggle mobile menu navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- ==========================================================================
       3. HERO SECTION (Parallax + Floating elements + Particles)
       ========================================================================== -->
    <section class="hero" id="home">
        <!-- Interactive Node Canvas Background -->
        <canvas id="hero-particles"></canvas>
        
        <!-- Floating decorative shapes -->
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>

        <div class="container hero-container">
            <div class="hero-content reveal-left reveal-left-active">
                <div class="hero-badge">
                    <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                    <span>Digitalization of Food Crop Security</span>
                </div>
                <h2 class="hero-title">SIP-OPT</h2>
                <div class="hero-subtitle">Sistem Informasi Peramalan Serangan Organisme Pengganggu Tanaman</div>
                <p class="hero-desc">
                    Sebuah platform digital yang dirancang untuk mendukung pemantauan, pengelolaan, peramalan, analisis, dan penyebaran informasi serangan hama tanaman secara efisien, akurat, dan terintegrasi.
                </p>
                <div class="hero-buttons">
                    <a href="#about" class="btn btn-primary">
                        <span>Pelajari selanjutnya</span>
                        <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                    </a>
                    <a href="login.php" class="btn btn-secondary">
                        <i data-lucide="shield-check" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        <span>Login System</span>
                    </a>
                </div>
            </div>

            <!-- Beautiful isometric farming illustration in SVG -->
            <div class="hero-graphic-container reveal-right reveal-right-active">
                <svg viewBox="0 0 500 500" class="hero-illustration" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#34d399" />
                            <stop offset="100%" stop-color="#0ea5e9" />
                        </linearGradient>
                        <filter id="glow-effect" x="-20%" y="-20%" width="140%" height="140%">
                            <drop-shadow dx="0" dy="8" stdDeviation="15" flood-color="#10b981" flood-opacity="0.15" />
                        </filter>
                    </defs>
                    <!-- Background radial waves representing monitoring -->
                    <circle cx="250" cy="250" r="180" stroke="var(--primary)" stroke-width="1.5" stroke-dasharray="4 8" opacity="0.2" />
                    <circle cx="250" cy="250" r="220" stroke="var(--secondary)" stroke-width="1.5" stroke-dasharray="8 8" opacity="0.1" />
                    <circle cx="250" cy="250" r="140" stroke="var(--primary)" stroke-width="1.5" opacity="0.3" />

                    <!-- Isometric Grid Ground (Farm Plots) -->
                    <path d="M250 80 L420 180 L250 280 L80 180 Z" fill="var(--bg-tertiary)" opacity="0.8" stroke="var(--border-color)" stroke-width="2" />
                    <path d="M250 120 L370 190 L250 260 L130 190 Z" fill="var(--primary-light)" stroke="rgba(var(--primary-rgb), 0.15)" stroke-width="1.5" />
                    
                    <!-- Crop Rows Representation -->
                    <path d="M170 170 L210 195" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    <path d="M190 160 L230 185" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    <path d="M210 150 L250 175" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    
                    <path d="M250 205 L290 230" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    <path d="M270 195 L310 220" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    <path d="M290 185 L330 210" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />

                    <!-- Floating Technology holographic elements -->
                    <!-- Central Sensor/Shield Shield representing crop defense -->
                    <g transform="translate(0, -20)">
                        <path d="M250 140 L310 175 L310 240 L250 275 L190 240 L190 175 Z" fill="url(#shieldGrad)" filter="url(#glow-effect)" opacity="0.85" />
                        <path d="M250 155 L295 180 L295 230 L250 255 L205 230 L205 180 Z" fill="var(--bg-secondary)" opacity="0.9" />
                        <!-- Inside details -->
                        <path d="M250 170 A25 25 0 0 1 250 240" stroke="var(--primary)" stroke-width="3" stroke-linecap="round" />
                        <path d="M250 170 A25 25 0 0 0 250 240" stroke="var(--secondary)" stroke-width="3" stroke-linecap="round" />
                        <circle cx="250" cy="205" r="10" fill="var(--primary)" />
                        <!-- Hologram Rays -->
                        <line x1="250" y1="205" x2="250" y2="80" stroke="var(--primary)" stroke-width="2" stroke-dasharray="3 3" />
                        <circle cx="250" cy="80" r="5" fill="var(--primary)" />
                    </g>

                    <!-- Smart Drone floating on the left -->
                    <g transform="translate(100, 100)">
                        <animateTransform attributeName="transform" type="translate" values="100, 90; 100, 110; 100, 90" dur="5s" repeatCount="indefinite" />
                        <path d="M0 0 L50 20 L25 35 L-25 15 Z" fill="var(--bg-secondary)" stroke="var(--border-color)" stroke-width="1" />
                        <circle cx="0" cy="0" r="8" fill="var(--secondary)" />
                        <line x1="-25" y1="15" x2="-25" y2="25" stroke="var(--text-muted)" stroke-width="2" />
                        <line x1="50" y1="20" x2="50" y2="30" stroke="var(--text-muted)" stroke-width="2" />
                        <!-- Rotor propellers -->
                        <ellipse cx="-25" cy="25" rx="10" ry="2" fill="var(--text-muted)" />
                        <ellipse cx="50" cy="30" rx="10" ry="2" fill="var(--text-muted)" />
                    </g>

                    <!-- Data floating charts on the right -->
                    <g transform="translate(320, 260)">
                        <animateTransform attributeName="transform" type="translate" values="320, 260; 320, 245; 320, 260" dur="4s" repeatCount="indefinite" />
                        <!-- Glassmorphic panel -->
                        <rect x="0" y="0" width="100" height="70" rx="8" fill="rgba(255,255,255,0.7)" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" />
                        <!-- Mock chart lines -->
                        <path d="M15 50 L35 30 L55 40 L85 15" stroke="var(--secondary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="85" cy="15" r="4" fill="var(--secondary)" />
                    </g>
                </svg>
            </div>
        </div>
        
        <!-- Animated wave divider between sections -->
        <div class="wave-divider wave-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="wave-fill"></path>
            </svg>
        </div>
    </section>

    <!-- ==========================================================================
       4. ABOUT SIP-OPT
       ========================================================================== -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Tentang Sistem Informasi</div>
                <h3 class="section-title">Apa itu SIP-OPT?</h3>
                <p class="section-desc">Solusi cerdas yang mendorong transformasi digital pertanian. Kami mengintegrasikan alur kerja pengamatan tanaman dengan model peramalan analitik big data untuk melindungi keberlanjutan pangan.</p>
            </div>

            <!-- Bento-style grid columns -->
            <div class="about-grid">
                <!-- Card 1: Monitoring -->
                <div class="about-card reveal delay-100">
                    <div class="card-icon">
                        <i data-lucide="eye"></i>
                    </div>
                    <h4 class="card-title">Real-Time Monitoring</h4>
                    <p class="card-desc">Memberikan kemampuan instan kepada petugas penyuluh pertanian dan pengamat untuk mencatat dan memetakan persebaran hama langsung dari lahan pertanian.</p>
                </div>

                <!-- Card 2: Prediction -->
                <div class="about-card reveal delay-200">
                    <div class="card-icon">
                        <i data-lucide="trending-up"></i>
                    </div>
                    <h4 class="card-title">Early Outbreak Forecasts</h4>
                    <p class="card-desc">Memanfaatkan kumpulan data historis, komoditas, dan variabel Organsime untuk memprediksi potensi ancaman hama </p>
                </div>

                <!-- Card 3: Analytics -->
                <div class="about-card reveal delay-300">
                    <div class="card-icon">
                        <i data-lucide="bar-chart-2"></i>
                    </div>
                    <h4 class="card-title">Data Validation & Analysis</h4>
                    <p class="card-desc">Mengintegrasikan validasi bawaan yang memastikan semua input lapangan dilaksanakan unit perlindungan tanaman BBPPTP untuk menghasilkan laporan visual.</p>
                </div>

                <!-- Card 4: Stakeholders -->
                <div class="about-card reveal delay-400">
                    <div class="card-icon">
                        <i data-lucide="users"></i>
                    </div>
                    <h4 class="card-title">Actionable Dissemination</h4>
                    <p class="card-desc">Mendistribusikan rekomendasi digital terperinci dan panduan intervensi secara instan kepada petugas pertanian, peneliti, dan petani masyarakat dalam penanganan OPT.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       5. BBPPTP MEDAN PROFILE
       ========================================================================== -->
    <section class="profile" id="profile">
        <!-- SVG Wave top -->
        <div class="wave-divider wave-divider-top">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="wave-fill"></path>
            </svg>
        </div>

        <div class="container profile-container">
            <!-- Left Pane: Lab Facility isometric graphic -->
            <div class="profile-illustration reveal-left">
                <svg viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Ground base -->
                    <path d="M100 280 L250 350 L400 280 L250 210 Z" fill="var(--bg-secondary)" stroke="var(--border-color)" stroke-width="2" />
                    <!-- Subfloor thickness -->
                    <path d="M100 280 L100 295 L250 365 L250 350 Z" fill="var(--bg-tertiary)" />
                    <path d="M250 350 L250 365 L400 295 L400 280 Z" fill="var(--border-color)" />

                    <!-- High Tech Forecasting Dome/Laboratory Building -->
                    <!-- Main blocks -->
                    <g transform="translate(180, 150)">
                        <path d="M0 60 L70 90 L140 60 L70 30 Z" fill="var(--primary-light)" stroke="var(--primary)" stroke-width="2" />
                        <path d="M0 60 L0 110 L70 140 L70 90 Z" fill="var(--bg-tertiary)" stroke="var(--primary)" stroke-width="1.5" />
                        <path d="M70 90 L70 140 L140 110 L140 60 Z" fill="var(--border-color)" stroke="var(--primary)" stroke-width="1.5" />

                        <!-- Window grids representing futuristic lab glass -->
                        <path d="M15 75 L15 95 L55 112 L55 92 Z" fill="rgba(14,165,233,0.3)" stroke="var(--secondary)" stroke-width="1" />
                        <path d="M85 112 L85 92 L125 75 L125 95 Z" fill="rgba(14,165,233,0.3)" stroke="var(--secondary)" stroke-width="1" />

                        <!-- Rooftop Radar / Weather Forecasting Dish -->
                        <g transform="translate(70, 30)">
                            <line x1="0" y1="0" x2="0" y2="-30" stroke="var(--text-primary)" stroke-width="3" />
                            <!-- Rotating dish -->
                            <path d="M-20 -30 C-20 -50 20 -50 20 -30 Z" fill="var(--bg-secondary)" stroke="var(--primary)" stroke-width="2" />
                            <!-- Wave signals radiating -->
                            <path d="M-30 -60 C-10 -75 10 -75 30 -60" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 4" opacity="0.7">
                                <animate attributeName="stroke-dashoffset" values="0; 10" dur="2s" repeatCount="indefinite" />
                            </path>
                        </g>
                    </g>
                    
                    <!-- Bio plants representation inside greenhouse area -->
                    <g transform="translate(140, 240)">
                        <!-- Small test tube/pot plants -->
                        <circle cx="0" cy="0" r="10" fill="var(--bg-tertiary)" />
                        <path d="M0 0 C-5 -10 -5 -20 0 -25 C5 -20 5 -10 0 0" fill="var(--primary)" />
                    </g>
                    <g transform="translate(120, 250)">
                        <circle cx="0" cy="0" r="10" fill="var(--bg-tertiary)" />
                        <path d="M0 0 C-5 -10 -5 -20 0 -25 C5 -20 5 -10 0 0" fill="var(--primary)" />
                    </g>
                </svg>
            </div>

            <!-- Right Pane: History, Vision, and Mission details -->
            <div class="profile-details reveal-right">
                <div class="section-badge">Institution Profile</div>
                <h3 class="section-title">BBPPTP Medan</h3>
                <p class="profile-text">
                BBPPTP Medan mempunyai peranan strategis dalam memberikan dukungan atas produksi, produktivitas dan mutu tanaman perkebunan berkelanjutan melalui kegiatan dukungan pengujian dan pengawasan mutu benih serta penerapan teknologi proteksi tanaman perkebunan. 
                </p>

                <div class="vission-mission-block">
                    <!-- Vision Card -->
                    <div class="vm-card">
                        <div class="vm-badge">Vision</div>
                        <div class="vm-content">
                            <h4>Visi BBPPTP</h4>
                            <p>“Menjadi Balai Besar terbaik, handal dan profesional dalam pelayanan prima kepada masyarakat perkebunan”</p>
                        </div>
                    </div>

                    <!-- Mission Card -->
                    <div class="vm-card">
                        <div class="vm-badge" style="background-color: var(--primary-light); color: var(--primary);">Mission</div>
                        <div class="vm-content">
                            <h4>Misi BBPPTP</h4>
                            <p>"Meningkatkan penggunaan benih unggul bermutu dan bersertifikat dalam pembangunan perkebunan di setiap wilayah pengembangan."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SVG Wave bottom -->
        <div class="wave-divider wave-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="wave-fill"></path>
            </svg>
        </div>
    </section>

    <!-- ==========================================================================
       6. WORKING AREAS (Provinces & Interactive map)
       ========================================================================== -->
    <section class="working-areas" id="working-areas">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Wilayah Cakupan</div>
                <h3 class="section-title">Wilayah Kerja</h3>
                <p class="section-desc">BBPPTP Medan berlokasi di Kecamatan Medan Helvetia, Kota Medan, Sumatera Utara.</p>
                <p class="section-desc">Wilayah kerja BBPPTP Medan bidang proteksi meliputi Provinsi Aceh, Provinsi Sumatera Utara, Provinsi Sumatera Barat, Provinsi Riau, Provinsi Kepulauan Riau, Provinsi Jambi, Provinsi Bengkulu, Provinsi Sumatera Selatan, Provinsi Bangka Belitung dan Provinsi Lampung.</p>
            </div>

            <div class="working-container">
                <!-- Interactive Sumatran Monitored Provinces SVG Map -->
                <div class="map-interactive-wrapper reveal-left">
                    <div class="map-tooltip" id="map-tooltip">North Sumatra</div>
                    
                    <svg viewBox="0 0 600 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Stylized Sumatra Islands Outline -->
                        <!-- Aceh Path -->
                        <path class="map-province-path" id="map-prov-aceh" data-province="Aceh" d="M120 40 L180 80 L160 110 L130 110 L100 80 Z" />
                        <!-- North Sumatra Path -->
                        <path class="map-province-path active" id="map-prov-sumut" data-province="North Sumatra" d="M180 80 L230 140 L210 180 L140 130 L160 110 Z" />
                        <!-- West Sumatra Path -->
                        <path class="map-province-path" id="map-prov-sumbar" data-province="West Sumatra" d="M210 180 L260 210 L240 250 L190 220 L200 190 Z" />
                        <!-- Riau Path -->
                        <path class="map-province-path" id="map-prov-riau" data-province="Riau" d="M230 140 L310 160 L290 210 L260 210 L210 180 Z" />
                        <!-- Decorative ocean accents -->
                        <path d="M80 200 Q100 205 120 200" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" opacity="0.3" />
                        <path d="M400 80 Q420 85 440 80" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" opacity="0.3" />
                    </svg>
                </div>

                <!-- Regions List sidebar toggling -->
                <div class="region-sidebar reveal-right">
                    <!-- Province Card: North Sumatra -->
                    <div class="region-card active" data-prov-id="sumut">
                        <div class="region-info">
                            <h4>North Sumatra (Sumatera Utara)</h4>
                            <p>Headquarters & Main Testing Laboratory</p>
                        </div>
                        <i data-lucide="chevron-right" class="region-arrow"></i>
                    </div>
                    <div class="region-detail-overlay active" id="details-sumut">
                        <div class="detail-stat-row">
                            <span>Main Commodities</span>
                            <span>Paddy (Rice), Corn</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Active Field Observers</span>
                            <span>84 Observers</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Observation Districts</span>
                            <span>25 Districts Monitored</span>
                        </div>
                    </div>

                    <!-- Province Card: Aceh -->
                    <div class="region-card" data-prov-id="aceh">
                        <div class="region-info">
                            <h4>Aceh</h4>
                            <p>North-Western Crop Forecasting Outpost</p>
                        </div>
                        <i data-lucide="chevron-right" class="region-arrow"></i>
                    </div>
                    <div class="region-detail-overlay" id="details-aceh">
                        <div class="detail-stat-row">
                            <span>Main Commodities</span>
                            <span>Paddy, Soybeans</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Active Field Observers</span>
                            <span>52 Observers</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Observation Districts</span>
                            <span>18 Districts Monitored</span>
                        </div>
                    </div>

                    <!-- Province Card: West Sumatra -->
                    <div class="region-card" data-prov-id="sumbar">
                        <div class="region-info">
                            <h4>West Sumatra (Sumatera Barat)</h4>
                            <p>Highland Crop Disease Monitoring</p>
                        </div>
                        <i data-lucide="chevron-right" class="region-arrow"></i>
                    </div>
                    <div class="region-detail-overlay" id="details-sumbar">
                        <div class="detail-stat-row">
                            <span>Main Commodities</span>
                            <span>Rice, Horticultural Crops</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Active Field Observers</span>
                            <span>46 Observers</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Observation Districts</span>
                            <span>14 Districts Monitored</span>
                        </div>
                    </div>

                    <!-- Province Card: Riau -->
                    <div class="region-card" data-prov-id="riau">
                        <div class="region-info">
                            <h4>Riau</h4>
                            <p>Peatland & Specific Food Crop Protections</p>
                        </div>
                        <i data-lucide="chevron-right" class="region-arrow"></i>
                    </div>
                    <div class="region-detail-overlay" id="details-riau">
                        <div class="detail-stat-row">
                            <span>Main Commodities</span>
                            <span>Paddy, Palawija</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Active Field Observers</span>
                            <span>30 Observers</span>
                        </div>
                        <div class="detail-stat-row">
                            <span>Observation Districts</span>
                            <span>10 Districts Monitored</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       7. DUTIES AND FUNCTIONS TIMELINE
       ========================================================================== -->
    <section class="duties" id="duties">
        <!-- SVG Wave top -->
        <div class="wave-divider wave-divider-top">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="wave-fill"></path>
            </svg>
        </div>

        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Tugas dan Fungsi</div>
                <h3 class="section-title">Fungsional Perlidnungan</h3>
                <p class="section-desc">Balai Besar Perbenihan dan Pelindungan Tanaman Perkebunan (BBPPTP) Medan mempunyai tugas melaksanakan pengelolaan perbenihan dan pelindungan tanaman perkebunan.</p>
            </div>

            <!-- Interleaved Timeline layout -->
            <div class="timeline">
                <!-- Timeline step 1 -->
                <div class="timeline-item left reveal">
                    <div class="timeline-content">
                        <span class="timeline-step">Function 01</span>
                        <h4 class="timeline-title">Perencanaan Monitoring</h4>
                        <p class="timeline-desc">pelaksanaan penyusunan dan penguatan metode pengamatan, peramalan dan pengendalian organisme pengganggu tumbuhan.</p>
                    </div>
                </div>

                <!-- Timeline step 2 -->
                <div class="timeline-item right reveal">
                    <div class="timeline-content">
                        <span class="timeline-step" style="color: var(--secondary);">Function 02</span>
                        <h4 class="timeline-title">Rekomendasi pengendalian organisme pengganggu tumbuhan</h4>
                        <p class="timeline-desc">Melakukan Proses peramalan serangan OPT menggunakan perhitungan matematis berbasis model deret waktu dengan metode ARIMA.</p>
                    </div>
                </div>

                <!-- Timeline step 3 -->
                <div class="timeline-item left reveal">
                    <div class="timeline-content">
                        <span class="timeline-step">Function 03</span>
                        <h4 class="timeline-title">Analis Data</h4>
                        <p class="timeline-desc">pelaksanaan identifikasi, analisis data serangan dan faktor yang mempengaruhi organisme pengganggu tumbuhan perkebunan.</p>
                    </div>
                </div>

                <!-- Timeline step 4 -->
                <div class="timeline-item right reveal">
                    <div class="timeline-content">
                        <span class="timeline-step" style="color: var(--secondary);">Function 04</span>
                        <h4 class="timeline-title">pelaksanaan pengelolaan hama terpadu</h4>
                        <p class="timeline-desc">pelaksanaan eksplorasi, perbanyakan, pengujian kualitas, dan penyebaran agens pengendali hayati.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SVG Wave bottom -->
        <div class="wave-divider wave-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="wave-fill"></path>
            </svg>
        </div>
    </section>

    <!-- ==========================================================================
       8. REGULATIONS AND LEGAL BASIS
       ========================================================================== -->
    <section class="regulations" id="regulations">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Landasan hukum</div>
                <h3 class="section-title">Peraturan & Kebijakan</h3>
                <p class="section-desc">BBPPTP Medan mempunyai peranan strategis dalam memberikan dukungan atas produksi, produktivitas dan mutu tanaman perkebunan berkelanjutan melalui kegiatan dukungan pengujian dan pengawasan mutu benih serta penerapan teknologi proteksi tanaman perkebunan.</p>
            </div>

            <!-- Accordion layout using smart height transitions -->
            <div class="accordion-wrapper">
                <!-- Law 1 -->
                <div class="accordion-item reveal">
                    <div class="accordion-header">
                        <div class="accordion-header-left">
                            <span class="accordion-num">01</span>
                            <span class="accordion-title">Undang-Undang No. 12 Tahun 1992</span>
                        </div>
                        <i data-lucide="chevron-down" class="accordion-icon"></i>
                    </div>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <div class="accordion-inner">
                               Direktorat Jenderal Perkebunan melalui Menteri Pertanian telah membentuk beberapa Unit Pelaksana Teknis (UPT) Pusat di daerah, salah satunya berada di Provinsi Sumatera Utara.
                                <br>
                                <span class="regulation-meta">
                                    <i data-lucide="file-text" style="width: 12px; height: 12px;"></i>
                                    <span>Undang-Undang</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Law 2 -->
                <div class="accordion-item reveal">
                    <div class="accordion-header">
                        <div class="accordion-header-left">
                            <span class="accordion-num">02</span>
                            <span class="accordion-title">Peraturan Menteri Pertanian Nomor 09/Permentan/OT.140/2/2008</span>
                        </div>
                        <i data-lucide="chevron-down" class="accordion-icon"></i>
                    </div>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <div class="accordion-inner">
                                tentang Organisasi dan Tata Kerja Balai Besar Perbenihan dan Proteksi Tanaman Perkebunan (BBP2TP) Medan. Secara struktur BBPPTP Medan yang dipimpin oleh seorang Kepala berada di bawah Direktorat Jenderal Perkebunan dan bertanggung jawab kepada Direktur Jenderal Perkebunan.
                                <br>
                                <span class="regulation-meta">
                                    <i data-lucide="file-text" style="width: 12px; height: 12px;"></i>
                                    <span>Peraturan Mentri Pertanian</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Law 3 -->
                <div class="accordion-item reveal">
                    <div class="accordion-header">
                        <div class="accordion-header-left">
                            <span class="accordion-num">03</span>
                            <span class="accordion-title">Peraturan Menteri Pertanian Republik Indonesi Nomor 11 Tahun 2023</span>
                        </div>
                        <i data-lucide="chevron-down" class="accordion-icon"></i>
                    </div>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <div class="accordion-inner">
                                BBPPTP Medan memerlukan pembenahan sistem koordinasi dan sinkronisasi yang baik dengan didukung oleh aparatur yang profesional. Sejalan dengan itu maka pembangunan aparatur negara dilakukan melalui reformasi birokrasi untuk meningkatkan profesionalisme aparatur negara dan untuk mewujudkan tata kelola pemerintahan yang baik (good governance).
                                <br>
                                <span class="regulation-meta">
                                    <i data-lucide="file-text" style="width: 12px; height: 12px;"></i>
                                    <span>Peraturan Mentri Pertanian</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       9. STATISTICS DASHBOARD (Counter-up)
       ========================================================================== -->
    <section class="stats" id="statistics">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Live System Metrics</div>
                <h3 class="section-title">Statistik Data Pengendalian</h3>
                <p class="section-desc">Statistik secara real time yang tercatat di SIP-OPT dalam efisiensi pemantauan Perkebunan.</p>
            </div>

            <!-- Stats grid layout with counters -->
            <div class="stats-grid">
                <!-- Stat 1 -->
                <div class="stat-card reveal delay-100">
                    <div class="stat-icon">
                        <i data-lucide="sprout"></i>
                    </div>
                    <div class="stat-number" data-target="5">0</div>
                    <div class="stat-label">Komoditas Perkebunan</div>
                </div>

                <!-- Stat 2 -->
                <div class="stat-card reveal delay-200">
                    <div class="stat-icon">
                        <i data-lucide="bug"></i>
                    </div>
                    <div class="stat-number" data-target="82">0</div>
                    <div class="stat-label">Jenis Organisme Pengganggu Tanaman</div>
                </div>

                <!-- Stat 3 -->
                <div class="stat-card reveal delay-300">
                    <div class="stat-icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div class="stat-number" data-target="130">0</div>
                    <div class="stat-label">Wilayah Kabupaten</div>
                </div>

                <!-- Stat 4 -->
                <div class="stat-card reveal delay-400">
                    <div class="stat-icon">
                        <i data-lucide="file-check-2"></i>
                    </div>
                    <div class="stat-number" data-target="31150">0</div>
                    <div class="stat-label">Total Catatan Serangan </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       10. SYSTEM WORKFLOW (Step cards with arrows)
       ========================================================================== -->
    <section class="workflow" id="workflow">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Saluran Informasi</div>
                <h3 class="section-title">How the System Works</h3>
                <p class="section-desc">Dari survei lapangan  hingga analitik berbasis cloud dan peramalan model ARIMA SIP-OPT.</p>
            </div>

            <!-- Workflow 6-step layout -->
            <div class="workflow-grid">
                <!-- Step 1 -->
                <div class="workflow-step reveal delay-100">
                    <div class="workflow-card">
                        <div class="workflow-num">01</div>
                        <i data-lucide="pencil-line" class="workflow-icon"></i>
                        <h4 class="workflow-title">Input Data</h4>
                        <p class="workflow-desc">Para pengamat mencatat Kabupaten, jenis hama, Jenis Komoditas, dan Total luas serangan dari lahan perkebunan.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="workflow-step reveal delay-200">
                    <div class="workflow-card">
                        <div class="workflow-num">02</div>
                        <i data-lucide="check-square" class="workflow-icon"></i>
                        <h4 class="workflow-title">Filtering</h4>
                        <p class="workflow-desc">Melakukan proses filter data untuk memastikan data terinput dengan variabel yang sama dnan menghilangkan anomali data.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="workflow-step reveal delay-300">
                    <div class="workflow-card">
                        <div class="workflow-num">03</div>
                        <i data-lucide="database" class="workflow-icon"></i>
                        <h4 class="workflow-title">Processing</h4>
                        <p class="workflow-desc">Data yang valid disimpan dalam database berbasis Cloud yang dapat diakses secara daring.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="workflow-step reveal delay-400">
                    <div class="workflow-card">
                        <div class="workflow-num">04</div>
                        <i data-lucide="line-chart" class="workflow-icon"></i>
                        <h4 class="workflow-title">Analysis</h4>
                        <p class="workflow-desc">Data deret waktu digunakan untuk menganalisa pemantauan serangan yang ditampilkan dengan model grafik.</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="workflow-step reveal delay-500">
                    <div class="workflow-card">
                        <div class="workflow-num">05</div>
                        <i data-lucide="alert-triangle" class="workflow-icon"></i>
                        <h4 class="workflow-title">Peramalan</h4>
                        <p class="workflow-desc">Prediksi serangan OPT menggunakan Metode ARIMA untuk membantu pengambilan keputusan dalam pengendalian hayati.</p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="workflow-step reveal">
                    <div class="workflow-card">
                        <div class="workflow-num">06</div>
                        <i data-lucide="send" class="workflow-icon"></i>
                        <h4 class="workflow-title">Laporan</h4>
                        <p class="workflow-desc">Hasil prediksi dapat dicetak untuk penyuluhan pada masyarakat luas serta petani-petani lokal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       11. LATEST NEWS & ANNOUNCEMENTS
       ========================================================================== -->
    <section class="news" id="news">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Newsroom updates</div>
                <h3 class="section-title">Latest Announcements & Feeds</h3>
                <p class="section-desc">Stay informed about crop safety activities, pest outbreaks, and government interventions.</p>
            </div>

            <!-- News grid -->
            <div class="news-grid">
                <!-- News 1 -->
                <div class="news-card reveal delay-300">
                <div class="news-image">
                <span class="news-category" style="color: var(--accent);">Eco-Focus</span>

                <svg viewBox="0 0 400 200">
                <!-- Gambar sebagai background -->
                <image
                href="artikel3.jpg"
                x="0"
                y="0"
                width="400"
                height="200"
                preserveAspectRatio="xMidYMid slice"
                />

            <!-- Efek lingkaran -->
            <circle cx="200" cy="100" r="30" fill="white" opacity="0.2" />
        </svg>
    </div>
                    <div class="news-body">
                        <div class="news-date">
                            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                            <span>April 28, 2026</span>
                        </div>
                        <h4 class="news-card-title">UJI BANDING ANTARLABORATORIUM PENGUJI MUTU APH BBPPTP SURABAYA DI LABORATORIUM BBPPTP MEDAN</h4>
                        <p class="news-summary">Laboratorium Integrasi BBPPTP Medan terus berkomitmen dalam menjaga dan meningkatkan mutu layanan pengujian melalui partisipasi aktif dalam Uji Banding Antarlaboratorium Penguji Mutu Agens Pengendali Hayati (APH) sesuai SNI ISO/IEC 17025:2017. Kegiatan ini merupakan salah satu bentuk implementasi persyaratan laboratorium terakreditasi dalam menjamin keabsahan hasil pengujian melalui pengendalian mutu eksternal.</p>
                        <a href="#news" class="btn-read-more">
                            <span>Read Full Article</span>
                            <i data-lucide="chevron-right"></i>
                        </a>
                    </div>
                </div>

                <!-- News 2 -->
                 <div class="news-card reveal delay-300">
                <div class="news-image">
                <span class="news-category" style="color: var(--accent);">Eco-Focus</span>

                <svg viewBox="0 0 400 200">
                <!-- Gambar sebagai background -->
                <image
                href="artikel2.jpg"
                x="0"
                y="0"
                width="400"
                height="200"
                preserveAspectRatio="xMidYMid slice"
                />

            <!-- Efek lingkaran -->
            <circle cx="200" cy="100" r="30" fill="white" opacity="0.2" />
        </svg>
    </div>
                    <div class="news-body">
                        <div class="news-date">
                            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                            <span>Desember 18, 2026</span>
                        </div>
                        <h4 class="news-card-title">Pelatihan Perbanyakan dan Penyebaran APH bagi Petani Kelapa Sawit di Kabupaten Langkat Provinsi Sumatera Utara</h4>
                        <p class="news-summary">Salah satu penyebab utama yang dapat menurunkan produksi dan produktivitas kelapa sawit adalah adanya serangan OPT (Organisme Pengganggu Tanaman). Diketahui bahwa OPT utama pada kelapa sawit di Kabupaten Langkat adalah penyakit busuk pangkal batang. Serangan penyakit ini pada Triwulan I tahun 2025 mencapai luas 7.797,10 hektare dengan serangan berat seluas 7.147,34 hektare (Sipereda, 2025). Selama ini, petani banyak mengandalkan pestisida kimia dalam mengendalikan penyakit busuk pangkal batang di kebunnya. Bila ditinjau dari sisi ekonomi, pestisida kimia  relatif mahal dan berpotensi menimbulkan dampak negatif terhadap makhluk hidup serta ekosistem. Oleh karena itu diperlukan alternatif pengendalian terhadap penyakit busuk pangkal batang yang lebih aman, murah dan mendukung keberlanjutan perkebunan kelapa sawit rakyat.</p>
                        <a href="#news" class="btn-read-more">
                            <span>Read Full Article</span>
                            <i data-lucide="chevron-right"></i>
                        </a>
                    </div>
                </div>

                <!-- News 3 -->
                <div class="news-card reveal delay-300">
                <div class="news-image">
                <span class="news-category" style="color: var(--accent);">Eco-Focus</span>

                <svg viewBox="0 0 400 200">
                <!-- Gambar sebagai background -->
                <image
                href="artikel.jpg"
                x="0"
                y="0"
                width="400"
                height="200"
                preserveAspectRatio="xMidYMid slice"
                />

            <!-- Efek lingkaran -->
            <circle cx="200" cy="100" r="30" fill="white" opacity="0.2" />
        </svg>
    </div>  
                    <div class="news-body">
                        <div class="news-date">
                            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                            <span>Maret 03</span>
                        </div>
                        <h4 class="news-card-title">UJI KOMPETENSI ANTARANALIS APH BBPPTP MEDAN TAHUN 2026</h4>
                        <p class="news-summary">Pengujian mutu Agens Pengendali Hayati (APH) termasuk dalam ruang lingkup layanan Laboratorium Pelindungan BBPPTP Medan yang telah terakreditasi berdasarkan SNI ISO/IEC 17025:2017 oleh Komite Akreditasi Nasional (KAN) sejak Desember 2022. Status akreditasi tersebut menunjukkan bahwa laboratorium tidak hanya memenuhi aspek sistem manajemen mutu, tetapi juga mampu membuktikan pemenuhan persyaratan teknis sebagaimana diatur dalam standar SNI ISO/IEC 17025:2017, khususnya terkait kompetensi teknis, validitas metode, serta jaminan mutu hasil pengujian laboratorium.</p>
                        <a href="#news" class="btn-read-more">
                            <span>Read Full Article</span>
                            <i data-lucide="chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       12. ACTIVITY GALLERY & LIGHTBOX
       ========================================================================== -->
    <section class="gallery" id="gallery">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Visual Showcase</div>
                <h3 class="section-title">Kegiatan & Kerja Lapangan</h3>
                <p class="section-desc">Gambaran visual dari laboratorium dan aktivitas lapangan kami, memantau perlindungan dan keamanan tanaman di berbagai provinsi.</p>
            </div>

            <!-- Responsive Grid Gallery -->
            <div class="gallery-grid">
                <!-- Item 1 -->
                <div class="gallery-item reveal delay-100" data-img-index="0">
                    <div class="gallery-item reveal delay-200" data-img-index="1">
    <svg viewBox="0 0 300 225">
        <image
            href="klinik.jpg"
            x="0"
            y="0"
            width="300"
            height="225"
            preserveAspectRatio="xMidYMid slice"
        />
    </svg>

    <div class="gallery-overlay">
        <span class="gallery-cat">KITA SERBU</span>
        <h4 class="gallery-title">Klinik Petani</h4>
    </div>
</div>
                    <div class="gallery-overlay">
                        <span class="gallery-cat">Survey Pekan Baru</span>
                        <h4 class="gallery-title">PENETAPAN KEBUN INDUK DAN POHON INDUK KELAPA SAWIT</h4>
                    </div>
                </div>

                <!-- Item 2 -->
             <div class="gallery-item reveal delay-200" data-img-index="1">
    <svg viewBox="0 0 300 225">
        <image
            href="labo.jpg"
            x="0"
            y="0"
            width="300"
            height="225"
            preserveAspectRatio="xMidYMid slice"
        />
    </svg>

    <div class="gallery-overlay">
        <span class="gallery-cat">Lab Research</span>
        <h4 class="gallery-title">Laboratium Integrasi Uji DNA</h4>
    </div>
</div>

                <!-- Item 3 -->
                <div class="gallery-item reveal delay-300" data-img-index="2">
                   <svg viewBox="0 0 300 225">
        <image
            href="sawit.jpg"
            x="0"
            y="0"
            width="300"
            height="225"
            preserveAspectRatio="xMidYMid slice"
        />
    </svg>
                    <div class="gallery-overlay">
                        <span class="gallery-cat">Eco Systems</span>
                        <h4 class="gallery-title">Beneficial Insect Breeding</h4>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="gallery-item reveal delay-400" data-img-index="3">
                    <svg viewBox="0 0 300 225">
        <image
            href="gg.jpg"
            x="0"
            y="0"
            width="300"
            height="225"
            preserveAspectRatio="xMidYMid slice"
        />
    </svg>
                    <div class="gallery-overlay">
                        <span class="gallery-cat">Survey Kisaran</span>
                        <h4 class="gallery-title">PENETAPAN KEBUN INDUK DAN POHON INDUK KELAPA SAWIT</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUSTOM POPUP JS LIGHTBOX -->
        <div id="lightbox">
            <span class="lightbox-close" id="lightbox-close">&times;</span>
            <span class="lightbox-nav lightbox-prev" id="lightbox-prev">&#10094;</span>
            <span class="lightbox-nav lightbox-next" id="lightbox-next">&#10095;</span>
            <div class="lightbox-content-wrapper">
                <div id="lightbox-img-container"></div>
                <div class="lightbox-caption" id="lightbox-caption">Field Survey</div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       13. FAQ SECTION (Accordion)
       ========================================================================== -->
    <section class="faq" id="faq">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Frequently Asked</div>
                <h3 class="section-title">Common Inquiries</h3>
                <p class="section-desc">Got questions about reporting, account access, or forecasting metrics? Find quick guides below.</p>
            </div>

            <div class="accordion-wrapper" style="max-width: 800px;">
                <!-- FAQ 1 -->
                <div class="accordion-item reveal">
                    <div class="accordion-header">
                        <div class="accordion-header-left">
                            <i data-lucide="help-circle" style="color: var(--primary);"></i>
                            <span class="accordion-title">How do I register an observer account in SIP-OPT?</span>
                        </div>
                        <i data-lucide="chevron-down" class="accordion-icon"></i>
                    </div>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <div class="accordion-inner">
                               Registrasi akun dapat dilakukan dengan menghubungi pihak administrasi fungsional perlindungan BBPPTP Medan sehingga akun akan dibuat dan diberikan sesuai permintaan.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="accordion-item reveal">
                    <div class="accordion-header">
                        <div class="accordion-header-left">
                            <i data-lucide="help-circle" style="color: var(--primary);"></i>
                            <span class="accordion-title">How accurate are the early forecasting reports?</span>
                        </div>
                        <i data-lucide="chevron-down" class="accordion-icon"></i>
                    </div>
                    <div class="accordion-collapse">
                        <div class="accordion-body">
                            <div class="accordion-inner">
                                Metode Auto ARIMA digunakan karena dapat secara otomatis mencari model peramalan yang paling sesuai berdasarkan data historis yang tersedia. Dengan metode ini, proses pemilihan model menjadi lebih cepat dan tidak memerlukan penentuan parameter secara manual. Tingkat ketepatan hasil peramalan bergantung pada kualitas dan pola data yang digunaka,,,n
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       14. TESTIMONIALS (Auto slider / Carousel)
       ========================================================================== -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Observer Stories</div>
                <h3 class="section-title">Feedback from the Fields</h3>
                <p class="section-desc">Listen to voices from agricultural officers and stakeholders leveraging SIP-OPT daily.</p>
            </div>

            <!-- Custom auto-carousel container -->
            <div class="testimonials-wrapper reveal">
                <div class="testimonials-container" id="testimonial-track">
                    <!-- Slide 1 -->
                    <div class="testimonial-slide">
                        <div class="testimonial-card">
                            <span class="quote-icon">“</span>
                            <div class="testimonial-stars">
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                            </div>
                            <p class="testimonial-text">
                                "SIP-OPT has completely eliminated standard paperwork latency. We no longer wait weeks for laboratory validation—outbreak warnings are dispatched in minutes!"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="var(--primary-light)"/><circle cx="50" cy="40" r="18" fill="var(--primary)"/><path d="M20 80 Q50 60 80 80 Z" fill="var(--primary)"/></svg>
                                </div>
                                <span class="author-name">Budi Santoso</span>
                                <span class="author-role">Field Officer, Deli Serdang Regency</span>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="testimonial-slide">
                        <div class="testimonial-card">
                            <span class="quote-icon">“</span>
                            <div class="testimonial-stars">
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                                <i data-lucide="star" fill="currentColor"></i>
                            </div>
                            <p class="testimonial-text">
                                "As a researcher, access to structured, geo-tagged pest historical variables has drastically accelerated our ecological modeling progress."
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="var(--secondary-light)"/><circle cx="50" cy="40" r="18" fill="var(--secondary)"/><path d="M20 80 Q50 60 80 80 Z" fill="var(--secondary)"/></svg>
                                </div>
                                <span class="author-name">Dr. Rina Astuti</span>
                                <span class="author-role">Agricultural Pathologist, Universitas Sumatera Utara</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Indicator dots -->
                <div class="testimonial-dots" id="testimonial-dots-container"></div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       15. CONTACT US SECTION (Address & Custom JS Form validation)
       ========================================================================== -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-badge">Connect with us</div>
                <h3 class="section-title">Get in Touch</h3>
                <p class="section-desc">Have a concern or need administrative support? Send us a message or visit our center.</p>
            </div>

            <div class="contact-container">
                <!-- Sidebar info panel -->
                <div class="contact-sidebar reveal-left">
                    <div class="contact-card-info">
                        <div class="contact-info-list">
                            <!-- Address -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i data-lucide="map-pin"></i>
                                </div>
                                <div class="contact-info-details">
                                    <h4>Office Address</h4>
                                    <p>Jl. Karya Jasa No. 20, Gedung Johor, Kec. Medan Johor, Kota Medan, Sumatera Utara 20144</p>
                                </div>
                            </div>

                            <!-- Contact info -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i data-lucide="phone"></i>
                                </div>
                                <div class="contact-info-details">
                                    <h4>Hotline & Email</h4>
                                    <p>+62 (061) 786-2144<br>contact@bbpptptmedan.go.id</p>
                                </div>
                            </div>

                            <!-- Hours -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i data-lucide="clock"></i>
                                </div>
                                <div class="contact-info-details">
                                    <h4>Operating Hours</h4>
                                    <p>Monday - Friday: 08.00 AM - 04.30 PM<br>Saturday - Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MOCK GOOGLE MAP GRAPHIC -->
                    <div class="map-placeholder">
                        <svg viewBox="0 0 400 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background Map grid grid -->
                            <rect width="100%" height="100%" fill="var(--bg-tertiary)" />
                            <!-- Abstract roads -->
                            <line x1="0" y1="50" x2="400" y2="50" stroke="var(--border-color)" stroke-width="8" />
                            <line x1="120" y1="0" x2="120" y2="250" stroke="var(--border-color)" stroke-width="12" />
                            <line x1="0" y1="180" x2="400" y2="180" stroke="var(--border-color)" stroke-width="6" />
                            <!-- River representation -->
                            <path d="M 0 250 Q 80 180 200 240 T 400 210" stroke="rgba(14,165,233,0.15)" stroke-width="16" fill="none" />
                            
                            <!-- Main building marker pin pin -->
                            <g transform="translate(120, 110)">
                                <circle cx="0" cy="-10" r="10" fill="var(--primary)" />
                                <path d="M 0 0 L -5 -8 L 5 -8 Z" fill="var(--primary)" />
                                <circle cx="0" cy="-10" r="4" fill="white" />
                                <animateTransform attributeName="transform" type="translate" values="120, 110; 120, 105; 120, 110" dur="2s" repeatCount="indefinite" />
                            </g>
                            
                            <!-- Label -->
                            <rect x="140" y="80" width="130" height="30" rx="4" fill="var(--bg-secondary)" stroke="var(--primary)" stroke-width="1" />
                            <text x="205" y="99" fill="var(--text-primary)" font-size="10" font-weight="bold" text-anchor="middle">BBPPTP MEDAN HQ</text>
                        </svg>
                    </div>
                </div>

                <!-- Action Contact form -->
                <div class="contact-form-wrapper reveal-right">
                    <form class="contact-form" id="contact-form" novalidate>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="form-name">Full Name</label>
                                <input type="text" class="form-control" id="form-name" placeholder="John Doe" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="form-email">Email Address</label>
                                <input type="email" class="form-control" id="form-email" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label class="form-label" for="form-subject">Subject</label>
                            <input type="text" class="form-control" id="form-subject" placeholder="Inquiry about SIP-OPT" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 24px;">
                            <label class="form-label" for="form-message">Your Message</label>
                            <textarea class="form-control" id="form-message" placeholder="Type your message here..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" id="form-submit-btn">
                            <i data-lucide="send" style="width: 18px; height: 18px;"></i>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Glassmorphic Success Form Feedback Toast -->
    <div class="toast-notification" id="toast-success">
        <div class="toast-icon">
            <i data-lucide="check-circle-2"></i>
        </div>
        <div class="toast-details">
            <h4 id="toast-title">Message Sent!</h4>
            <p id="toast-msg">Thank you, we will contact you shortly.</p>
        </div>
    </div>

    <!-- ==========================================================================
       16. FOOTER
       ========================================================================== -->
    <footer>
        <div class="container footer-grid">
            <!-- Col 1: About -->
            <div>
                <a href="#home" class="logo-block" style="display: inline-flex;">
                    <div class="logo-icon">
                        <img src="logo2.png" alt="">
                    </div>
                    <div class="logo-text">
                        <h1 style="background: white; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SIP-OPT</h1>
                        <span style="color: rgba(255,255,255,0.5);">BBPPTP MEDAN</span>
                    </div>
                </a>
                <p class="footer-desc">
                    Meningkatkan ketahanan pangan dan keberlanjutan tanaman di Sumatera melalui indeks pengamatan penyakit secara real-time dan peringatan analitis tingkat lanjut.
                </p>
                <div class="social-links">
                    <a href="#footer" class="social-icon" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                    <a href="#footer" class="social-icon" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                    <a href="#footer" class="social-icon" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                    <a href="#footer" class="social-icon" aria-label="Youtube"><i data-lucide="youtube"></i></a>
                </div>
            </div>

            <!-- Col 2: Navigation Links -->
            <div>
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Apa itu SIP-OPT</a></li>
                    <li><a href="#profile">BBPPTP Medan</a></li>
                    <li><a href="#working-areas">Wilayah Kerja</a></li>
                </ul>
            </div>

            <!-- Col 3: Institutional Links -->
            <div>
                <h4 class="footer-title">Governance</h4>
                <ul class="footer-links">
                    <li><a href="#duties">Tugas dan Fungsi</a></li>
                    <li><a href="#regulations">Regulasi dasar</a></li>
                    <li><a href="#faq">Faq Panel</a></li>
                    <li><a href="#contact">Emergency Contact</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact info details -->
            <div>
                <h4 class="footer-title">Official Center</h4>
                <div class="footer-contact-item">
                    <i data-lucide="map-pin"></i>
                    <span> Jl.Asrama No. 124 Medan, Kel. Cinta Damai, Kec. Medan Helvetia, Kota Medan, Provinsi Sumatera Utara  20126 - Indonesia</span>
                </div>
                <div class="footer-contact-item">
                    <i data-lucide="phone"></i>
                    <span>(061) 8470504, 8458008, 8466771</span>
                </div>
                <div class="footer-contact-item">
                    <i data-lucide="mail"></i>
                    <span>bbpptp.medan@pertanian.go.id</span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="container footer-bottom">
            <div>&copy; <span id="current-year">2026</span> SIP-OPT. All Rights Reserved. @faqbila.</div>
            <div class="footer-bottom-links">
                <a href="#footer">Privacy Policy</a>
                <a href="#footer">Terms of Service</a>
            </div>
        </div>
    </footer>

    <!-- ==========================================================================
       17. FLOATING LIVE CHAT BOT WIDGET
       ========================================================================== -->
    <div class="btn-float" id="btn-live-chat" aria-label="Open live chat virtual assistant">
        <i data-lucide="message-square" style="width: 22px; height: 22px;"></i>
        <div class="chat-pulse"></div>
    </div>

    <!-- Live chat panel UI -->
    <div class="chat-box-container" id="chat-box">
        <div class="chat-box-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <i data-lucide="bot" style="width: 18px; height: 18px; color: white;"></i>
                </div>
                <div class="chat-header-title">
                    <h4>SIP-OPT Assistant</h4>
                    <span>Online | Virtual Agent</span>
                </div>
            </div>
            <div class="chat-close-btn" id="chat-close-btn">&times;</div>
        </div>

        <div class="chat-messages-area" id="chat-messages-area">
            <!-- Intro greeting -->
            <div class="chat-msg chat-msg-received">
                Welcome to SIP-OPT Assistant! How can I help you today?
            </div>
            <!-- Dynamic Typing indicator -->
            <div class="chat-typing" id="chat-typing">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Predefined questions suggestions -->
        <div class="chat-suggestions" id="chat-suggestions">
            <button class="chat-suggest-btn" data-query="What is SIP-OPT?">What is SIP-OPT?</button>
            <button class="chat-suggest-btn" data-query="How do I report a pest?">How do I report a pest?</button>
            <button class="chat-suggest-btn" data-query="Who operates this site?">Who operates this site?</button>
        </div>

        <!-- Interactive Send Input area -->
        <div class="chat-input-area">
            <input type="text" id="chat-input-field" placeholder="Type a message...">
            <button class="chat-send-btn" id="chat-send-btn">
                <i data-lucide="send" style="width: 16px; height: 16px;"></i>
            </button>
        </div>
    </div>

    <!-- Back to top floating button -->
    <div class="btn-float" id="btn-back-to-top" aria-label="Scroll back to top of page">
        <i data-lucide="chevron-up" style="width: 20px; height: 20px;"></i>
    </div>

    <!-- ==========================================================================
       18. MOCK PORTAL LOGIN SYSTEM MODAL OVERLAY
       ========================================================================== -->

</body>
</html>
