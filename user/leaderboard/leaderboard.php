<?php
    require_once '../../layout/config.php';
;
    session_start();
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }
    $user = mysqli_query($mysqli,"SELECT * FROM user ORDER BY score DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringkat</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="leaderboard.css">
    <link rel="stylesheet" href="../../index.css" />
</head>
<body>
    <?php include('../../layout/navbar.php'); ?>
    
    <div class="leaderboard-header text-center">
        <div class="container">
            <h1 class="leaderboard-title">Daftar Peringkat</h1>
            <p class="leaderboard-subtitle">Lihat posisi Kamu di antara pemain terbaik</p>
        </div>
    </div>
    
    <div class="container leaderboard-container">
        <div class="leaderboard-card">
            <table class="table table-leaderboard">
                <thead>
                    <tr>
                        <th scope="col" class="rank-cell">Rank</th>
                        <th scope="col">Pemain</th>
                        <th scope="col" class="text-end">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php while($row = mysqli_fetch_assoc($user)): ?>
                    <tr class="<?php 
                        echo 'top-' . $i . ' ';
                        echo ($row['id'] == $_SESSION['id']) ? 'current-user' : '';
                    ?>">
                        <th scope="row" class="rank-cell">
                            <?php if($i <= 3): ?>
                                <span class="badge-top badge-<?= $i ?>"><?= $i ?></span>
                            <?php else: ?>
                                <?= $i ?>
                            <?php endif; ?>
                        </th>
                        <td class="user-cell">
                            <div class="user-avatar">
                                <?= strtoupper(substr($row["username"], 0, 1)) ?>
                            </div>
                            <?= $row["username"] ?>
                            <?php if($row['id'] == $_SESSION['id']): ?>
                                <span class="ms-2"></span><span class="badge bg-primary ms-2">Kamu</span>
                            <?php endif; ?>
                        </td>
                        <td class="score-cell">
                            <?= number_format($row["score"]) ?> pts
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>