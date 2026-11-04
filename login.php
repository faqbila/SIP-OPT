<?php
require 'koneksi_login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    
    <!-- SEO Meta Tags -->
    <title>Login | SIP-OPT BBPPTP Medan</title>
    <meta name="description" content="Authorized observer secure login portal for the SIP-OPT Plant Pest and Disease Attack Information System of BBPPTP Medan.">
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Modular Login CSS Styling (Immersive 3D Parallax Agriculture Theme) -->
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <!-- Immersive 3D Parallax Scenic Container -->
    <div class="parallax-container" id="parallax-scene">
        
        <!-- LAYER 1: BACKGROUND (Sky, Sunrise Glow, Moving Clouds) -->
        <div class="parallax-layer layer-bg" data-depth="0.10">
            <!-- Morning Sunburst -->
            <div class="sunrise-sun"></div>

            <!-- Moving Clouds -->
            <!-- Cloud 1 -->
            <svg class="scenic-cloud cloud-1" viewBox="0 0 100 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M 20 30 a 10 10 0 0 1 10 -10 a 15 15 0 0 1 22 -6 a 12 12 0 0 1 18 2 a 10 10 0 0 1 10 14 z"/>
            </svg>
            <!-- Cloud 2 -->
            <svg class="scenic-cloud cloud-2" viewBox="0 0 100 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M 20 30 a 10 10 0 0 1 10 -10 a 15 15 0 0 1 22 -6 a 12 12 0 0 1 18 2 a 10 10 0 0 1 10 14 z"/>
            </svg>
        </div>

        <!-- LAYER 2: MIDGROUND (Observation Towers, Hovering Drone, Blinking Network Dots, Butterflies) -->
        <div class="parallax-layer layer-mid" data-depth="0.25">
            <!-- Modern Agricultural Weather / Observation Radar Station (SVG) -->
            <svg viewBox="0 0 100 120" style="position: absolute; bottom: 100px; left: 22%; width: 40px; opacity: 0.6;" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="50" y1="120" x2="50" y2="40" stroke="var(--primary)" stroke-width="3" />
                <path d="M 30 120 L 50 80 L 70 120" stroke="var(--primary)" stroke-width="2" />
                <!-- Rotating Radar dish -->
                <path d="M 35 40 Q 50 20 65 40 Z" fill="var(--bg-secondary)" stroke="var(--secondary)" stroke-width="1.5" />
                <circle cx="50" cy="40" r="4" fill="var(--secondary)" />
            </svg>

            <!-- Agricultural Drone hovering above left -->
            <svg class="scenic-drone" viewBox="0 0 100 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 20 25 L 80 25 M 50 25 L 50 15" stroke="var(--primary-dark)" stroke-width="3" stroke-linecap="round" />
                <circle cx="50" cy="15" r="8" fill="var(--secondary)" />
                <ellipse cx="20" cy="25" rx="10" ry="2" fill="var(--primary)" />
                <ellipse cx="80" cy="25" rx="10" ry="2" fill="var(--primary)" />
            </svg>

            <!-- Blinking data transmission network indicators -->
            <div class="network-dot" style="top: 35%; left: 18%;"></div>
            <div class="network-dot" style="top: 50%; left: 25%;"></div>
            <div class="network-dot" style="top: 45%; right: 28%;"></div>

            <!-- Animated Nature Butterflies fluttering -->
            <!-- Butterfly 1 -->
            <svg class="butterfly" style="bottom: 250px; left: 12%;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C11.5 2.5 11 4.5 11 7C11 9 11.5 9.5 12 10C12.5 9.5 13 9 13 7C13 4.5 12.5 2.5 12 2Z"/>
                <path d="M10 7C7.5 5 4 5 4 8C4 11 7 13 11 10.5C11 9 10.5 8 10 7Z"/>
                <path d="M14 7C16.5 5 20 5 20 8C20 11 17 13 13 10.5C13 9 13.5 8 14 7Z"/>
            </svg>
            <!-- Butterfly 2 -->
            <svg class="butterfly" style="bottom: 180px; right: 18%; animation-delay: 5s;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C11.5 2.5 11 4.5 11 7C11 9 11.5 9.5 12 10C12.5 9.5 13 9 13 7C13 4.5 12.5 2.5 12 2Z"/>
                <path d="M10 7C7.5 5 4 5 4 8C4 11 7 13 11 10.5C11 9 10.5 8 10 7Z"/>
                <path d="M14 7C16.5 5 20 5 20 8C20 11 17 13 13 10.5C13 9 13.5 8 14 7Z"/>
            </svg>
        </div>

        <!-- LAYER 3: FOREGROUND (Overlapping Green hills, nature particles) -->
        <div class="parallax-layer layer-fore" data-depth="0.50">
            <!-- Nature Inspired Floating Foliage Leaf Elements -->
            <div class="nature-leaf" style="left: 8%; animation-delay: 0s; animation-duration: 14s; font-size: 22px;">
                <i data-lucide="leaf"></i>
            </div>
            <div class="nature-leaf" style="left: 22%; animation-delay: 3s; animation-duration: 16s; font-size: 16px;">
                <i data-lucide="leaf"></i>
            </div>
            <div class="nature-leaf" style="left: 72%; animation-delay: 1s; animation-duration: 12s; font-size: 26px;">
                <i data-lucide="sprout"></i>
            </div>
            <div class="nature-leaf" style="left: 88%; animation-delay: 5s; animation-duration: 15s; font-size: 18px;">
                <i data-lucide="leaf"></i>
            </div>

            <!-- Layered Stylized Organic Crop Hills in background footer -->
            <div style="position: absolute; bottom: -10px; left: 0; width: 100%; height: 160px; pointer-events: none; opacity: 0.85;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                    <!-- Hill Layer 1 (Back) -->
                    <path d="M0,80 Q300,30 600,70 T1200,50 L1200,120 L0,120 Z" fill="#c8e6c9" opacity="0.6"></path>
                    <!-- Hill Layer 2 (Front) -->
                    <path d="M0,100 Q400,60 800,90 T1200,80 L1200,120 L0,120 Z" fill="#a5d6a7" opacity="0.8"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Main Glassmorphic Login Wrapper -->
    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Brand Logo and Header Block -->
            <div class="brand-header">
                <div class="brand-icon-wrapper">
                    <!-- High-fidelity custom SVG leaf / protection badge -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px;">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 1 9.8a7 7 0 0 1-9 8.2Z"/>
                        <path d="M9 10a5 5 0 0 0-5 5h5v-5Z"/>
                    </svg>
                </div>
                <div class="brand-subtitle">SIP-OPT BBPPTP MEDAN</div>
                <h1 class="brand-title">Observer Login</h1>
            </div>

            <!-- Form Login  -->
            <form method="post" autocomplete="on">
                
                <!-- Username Input Group -->
                <div class="form-group">
                    <label class="form-label" for="username-input">
                        <i data-lucide="user" style="width: 14px; height: 14px; color: var(--primary);"></i>
                        <span>Username / NIP</span>
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="username-input"
                            name="username" 
                            class="form-control-custom"
                            placeholder="Enter username"
                            required
                            autocomplete="username">
                        <i data-lucide="user" class="input-icon-left" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>

                <!-- Password Input Group -->
                <div class="form-group">
                    <label class="form-label" for="password-input">
                        <i data-lucide="key-round" style="width: 14px; height: 14px; color: var(--primary);"></i>
                        <span>Security Password</span>
                    </label>
                    <div class="input-container">
                        <input 
                            type="password" 
                            id="password-input"
                            name="password" 
                            class="form-control-custom"
                            placeholder="Enter password"
                            required
                            autocomplete="current-password">
                        <i data-lucide="lock" class="input-icon-left" style="width: 18px; height: 18px;"></i>
                        
                        <!-- Dynamic Password Visibility Toggle Button -->
                        <button type="button" class="password-toggle-btn" id="password-toggle" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="toggle-icon" style="width: 18px; height: 18px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Login Submit -->
                <button 
                    type="submit" 
                    name="login" 
                    class="btn-submit-glow">
                    <span>Authenticate Session</span>
                    <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                </button>

            </form>

            <!-- Back to Homepage Navigation Link -->
            <a href="index.php" class="back-home-link">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                <span>Return to Landing Page</span>
            </a>

        </div>
    </div>

    <!-- Client-side Interactive Behaviors -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Render Lucide Icons
            lucide.createIcons();

            // Password Visibility Toggle Logic
            const passwordInput = document.getElementById('password-input');
            const passwordToggle = document.getElementById('password-toggle');
            const toggleIcon = document.getElementById('toggle-icon');

            if (passwordToggle && passwordInput && toggleIcon) {
                passwordToggle.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if (type === 'text') {
                        toggleIcon.setAttribute('data-lucide', 'eye-off');
                    } else {
                        toggleIcon.setAttribute('data-lucide', 'eye');
                    }
                    
                    // Rerender specific icon
                    lucide.createIcons();
                });
            }

            /* ==========================================================================
               3D MOUSE-TRACKING PARALLAX MATRIX ENGINE
               ========================================================================== */
            const scene = document.getElementById('parallax-scene');
            const layers = document.querySelectorAll('.parallax-layer');

            if (scene && layers.length > 0) {
                document.addEventListener('mousemove', (e) => {
                    const width = window.innerWidth;
                    const height = window.innerHeight;
                    
                    // Calculate normalized coordinates (-0.5 to 0.5)
                    const mouseX = (e.clientX / width) - 0.5;
                    const mouseY = (e.clientY / height) - 0.5;
                    
                    // Apply translation factor based on layer depth weight
                    layers.forEach(layer => {
                        const depth = parseFloat(layer.getAttribute('data-depth'));
                        
                        // Translation pixels factors
                        const transX = mouseX * (depth * 50); // max 25px offset
                        const transY = mouseY * (depth * 30); // max 15px offset
                        
                        layer.style.transform = `translateX(${transX}px) translateY(${transY}px)`;
                    });
                });
            }
        });
    </script>
</body>
</html>
