<?php $root = $_SERVER['DOCUMENT_ROOT']; ?>

<!-- Site-wide html tags -->
<meta charset="UTF-8">  <!-- Ensures correct text encoding -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Makes it mobile-friendly -->

<!-- SEO -->
<meta name="description" content="Kars' Site --">
<meta property="og:description" content="Kars' Site --"> 
<meta property="og:title" content="Kars' Site">
<!-- <meta property="og:image" content="https://mr-kitty-archive.xyz/assets/icons/mk-logo.png"> -->

<!-- Page metadata -->
<link rel="icon" href="/assets/pfp.png" type="image/x-icon">

<!-- CSS Stylesheets -->
<link rel="stylesheet" href="/css/base.css?v=<?= filemtime($root . '/css/base.css') ?>">
<link rel="stylesheet" href="/css/meta-include.css?v=<?= filemtime($root . '/css/meta-include.css') ?>">

<!-- JS Scripts -->

<!-- NAV -->
<div class="nav">
    <a button href="/home">
        <span class="icon">🏠</span>
        <span class="label">Home</span>
    </a>
    <a button href="/songs">
        <span class="icon">🎵</span>
        <span class="label">Songs Database</span>
    </a>
    <a button href="/discography">
        <span class="icon">💿</span>
        <span class="label">Discography</span>
    </a>
    <a button href="/extras.php">
        <span class="icon">✨</span>
        <span class="label">Extras</span>
    </a>
</div> <!-- End of .nav -->

<!-- Mobile Top Navbar Button -->
<div class="mobile-navbar-top">
    <button id="open-mobile-navbar">☰ Navbar</button>
</div>
<!-- Mobile Fullscreen Navbar Overlay -->
<div class="mobile-navbar-overlay" id="mobile-navbar-overlay">
    <div class="mobile-navbar-content">
        <button id="close-mobile-navbar" class="close-btn">✕</button>
        <nav>
            <a href="/home" class="mobile-nav-btn"><span class="icon">🏠</span> Home</a>
            <a href="/songs" class="mobile-nav-btn"><span class="icon">🎵</span> Songs Database</a>
            <a href="/discography" class="mobile-nav-btn"><span class="icon">💿</span> Discography</a>
            <a href="/extras.php" class="mobile-nav-btn"><span class="icon">✨</span> Extras</a>
        </nav>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownParents = document.querySelectorAll('.dropdown-parent');
    dropdownParents.forEach(parent => {
        const toggle = parent.querySelector('.dropdown-toggle');
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                parent.classList.toggle('open');
            });
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        dropdownParents.forEach(parent => {
            if (!parent.contains(e.target)) {
                parent.classList.remove('open');
            }
        });
    });
});

    // Mobile settings button toggles settings panel
    document.addEventListener('DOMContentLoaded', function() {
        var mobileSettings = document.getElementById('mobile-settings');
        var settingsPanel = document.querySelector('.settings');
        if (mobileSettings && settingsPanel) {
            mobileSettings.addEventListener('click', function(e) {
                e.preventDefault();
                settingsPanel.classList.add('active');
                settingsPanel.classList.remove('inactive');
            });
        }
    });

    // Mobile navbar overlay open/close
    document.addEventListener('DOMContentLoaded', function() {
        var openBtn = document.getElementById('open-mobile-navbar');
        var closeBtn = document.getElementById('close-mobile-navbar');
        var overlay = document.getElementById('mobile-navbar-overlay');
        if (openBtn && overlay) {
            openBtn.addEventListener('click', function() {
                overlay.classList.add('active');
            });
        }
        if (closeBtn && overlay) {
            closeBtn.addEventListener('click', function() {
                overlay.classList.remove('active');
            });
        }
        // Optional: close overlay when clicking outside nav
        overlay && overlay.addEventListener('click', function(e) {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });
</script>

