<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script src="app.js" defer></script>
  <meta charset="UTF-8">
  <title>Logout Berhasil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
     <style>
  </style>
</head>
<body>
   <div id="preloader">
        <div class="preloader-logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 1 9.8a7 7 0 0 1-9 8.2Z"/>
                <path d="M9 10a5 5 0 0 0-5 5h5v-5Z"/>
            </svg>
            <div class="preloader-title">SIP-OPT</div>
        </div>
        <div class="preloader-bar-bg">
            <div class="preloader-bar-fill" id="preloader-bar"></div>
        </div>
        <div class="preloader-count" id="preloader-percentage">0%</div>
    </div>
 <script>
Swal.fire({
    title: 'Logout Berhasil!',
    text: 'Anda akan diarahkan ke halaman login...',
    icon: 'success',
    showConfirmButton: false,
    timer: 1000,

    didClose: () => {

        // Tampilkan preloader
        const preloader = document.getElementById('preloader');

        preloader.style.visibility = 'visible';
        preloader.style.opacity = '1';

        const preloaderBar = document.getElementById('preloader-bar');
        const preloaderPercentage = document.getElementById('preloader-percentage');

       let progress = 0;

const interval = setInterval(() => {

    progress += 2;

    preloaderBar.style.width = progress + "%";
    preloaderPercentage.textContent = progress + "%";

    if(progress >= 100){

        clearInterval(interval);

        setTimeout(() => {
            window.location.href = "login.php";
        },300);

    }

},30);
    }
});
</script>
</html>