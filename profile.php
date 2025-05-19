<?php
session_start();
include 'navbar.php';
include 'functions.php';
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

?>

<!DOCTYPE html>
<html lang="eng">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
            integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv"
            crossorigin="anonymous"
        />
        <link rel="icon" href="img/q!.ico" type="image/x-icon">
        <title>Queasy - Profil</title>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900& display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="index.css" />
        
        <style>
            .navbar {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
        </style>

    </head>
    <body class="bg-body-tertiary">
        <div class="container d-block">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow mt-5">
                        <div class="card-header">
                            <h5 class="mt-2"><i class="fas fa-user opacity-75 "></i> Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" required value="<?php echo $username ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required value="<?php echo $email ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password jika ingin mengganti" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary" name="save" id="save">Simpan</button>
                                <a href="my_quiz.php" class="btn btn-outline-dark">Kuisku</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    $newusername = $_POST["username"];
    $newemail = $_POST["email"];
    $password = $_POST["password"];
    
    $query = "UPDATE user SET username = '$newusername', email = '$newemail' WHERE id = '$id'";  
    $newuser = mysqli_fetch_assoc($user);
    //cek password
    if(isset($_POST["save"])){
        if(password_verify($password, $row["password"])){
            $result = mysqli_query($mysqli,$query);
            if($result){
                $_SESSION["username"] = $newusername;
                echo "<script>
                        alert('Profile updated!');
                        document.location.href = 'index.php';
                    </script>";

            } else {
                echo "<script>
                    alert('Profile update failed!');
                </script>";
            }
        } else {
            echo "<script>
                    alert('Wrong password!');
                </script>";
        }
    }
?>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
></script>
</body>
</html>