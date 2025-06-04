<?php
session_start();

require_once(__DIR__ . "../../layout/functions.php");
if(!isset($_SESSION["login"])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];
$user = mysqli_query($mysqli, "SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_assoc($user);
$id = $row["id"];
$useruid = $row["username"]; 
$email = $row["email"];

// Proses update profil
if(isset($_POST["save"])){
    $newusername = trim($_POST["username"]);
    $newemail = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Validasi input
    if(empty($newusername) || empty($newemail) || empty($password)){
        echo "<script>alert('Semua field harus diisi!');</script>";
    } else {
        // Cek password lama
        if(password_verify($password, $row["password"])){
            $check_username = mysqli_query($mysqli, "SELECT id FROM user WHERE username = '$newusername' AND id != '$id'");
            
            if(mysqli_num_rows($check_username) > 0){
                echo "<script>alert('Username sudah digunakan!');</script>";
            } else {
                // Update data
                $query = "UPDATE user SET username = '$newusername', email = '$newemail' WHERE id = '$id'";  
                $result = mysqli_query($mysqli, $query);
                
                if($result){
                    $_SESSION["username"] = $newusername;
                    echo "<script>
                            alert('Profile berhasil diupdate!');
                            document.location.href = '/Queasy/index.php';
                        </script>";
                } else {
                    echo "<script>alert('Gagal mengupdate profile!');</script>";
                }
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="eng">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="../img/Q!.ico" type="image/x-icon">
        <title>Queasy - Profil</title>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../index.css" />
        <style>
            .navbar {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <?php
    include '../layout/navbar.php';
    ?>
    <body class="bg-body-tertiary">
        <div class="container d-block">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow mt-5">
                        <div class="card-header">
                            <h5 class="mt-2"><i class="fas fa-user opacity-75"></i> Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" required value="<?php echo htmlspecialchars($username); ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="password">Password Saat Ini</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password saat ini untuk konfirmasi" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary" name="save" id="save">Simpan</button>
                                <a href="/Queasy/index.php" class="btn bg-danger text-white">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"
        ></script>
    </body>
</html>