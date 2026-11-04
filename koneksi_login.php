<?php
session_start();

$koneksi = mysqli_connect("localhost", "root", "", "opt_bbpptp");

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi,
        "SELECT * FROM users 
        WHERE username='$username' 
        AND password='$password'"
    );

    $cek = mysqli_num_rows($query);

    if($cek > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        // CEK ROLE
     if($data['role'] == "admin"){

    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function(){

        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            html: `
                <div style='text-align:center'>
                    <h3>Selamat Datang</h3>
                    <p>{$data['nama']}</p>
                </div>
            `,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href='home_admin.php';
        });

    });
    </script>
    ";

} elseif($data['role'] == "user"){

    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function(){

        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            html: `
                <div style='text-align:center'>
                    <h3>Selamat Datang</h3>
                    <p>{$data['nama']}</p>
                </div>
            `,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href='home_user.php';
        });

    });
    </script>
    ";

}

    } else {

echo "
<script>
document.addEventListener('DOMContentLoaded', function() {

    Swal.fire({
        icon: 'error',
        html: `
            <div style='text-align:center'>          
                <h3>SIP-OPT Security Check</h3>
                <p>Username atau Password tidak valid.</p>
            </div>
        `,
        confirmButtonText: 'Login Ulang',
        confirmButtonColor: '#2E7D32',
        timer: 5000,
        timerProgressBar: true
    });

});

</script>
";

    }
}
?>