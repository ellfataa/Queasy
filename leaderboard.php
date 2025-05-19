<?php
    require_once("config.php");
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
    <link rel="icon" href="img/q!.ico" type="image/x-icon">
    
    <style>
        /* navbar */
        .logo {
            font-size: 32px;
            font-style: normal;
            font-weight: 800;
            margin-bottom: 0px;
            color: #000000;
        }
        .logo span {
            color: #fcc822;
        }
        .nav-item {
            font-size: 14px;
        }
        .nav-link {
            color: #000000;
            text-decoration: none;
        }
        .nav-item button {
            font-size: 14px;
        }
        .nav-item a:hover {
            color: #fcc822;
            text-decoration: underline;
            transition: 0.3s;
        }
        .logo:hover {
            color: white;
        }
        .navbar{
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            background-color: white;
        }
        /* navbar end */

        :root {
            --primary-color: #fcc822;
            --secondary-color: #ff8c66;
            --gold: #ffd700;
            --silver: #c0c0c0;
            --bronze: #cd7f32;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .leaderboard-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(106, 90, 205, 0.3);
        }

        .leaderboard-header::before {
            content: "";
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .leaderboard-header::after {
            content: "";
            position: absolute;
            bottom: 300px;
            right: -30px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .leaderboard-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .leaderboard-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            color: white;
        }
        
        .leaderboard-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .leaderboard-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table-leaderboard {
            margin-bottom: 0;
        }
        
        .table-leaderboard thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .table-leaderboard thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table-leaderboard tbody tr {
            transition: all 0.3s ease;
        }
        
        .table-leaderboard tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .table-leaderboard tbody td, 
        .table-leaderboard tbody th {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .rank-cell {
            font-weight: 700;
            text-align: center;
            width: 80px;
        }
        
        .top-1 {
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .top-1 .rank-cell {
            color: var(--gold);
            font-size: 1.2rem;
        }
        
        .top-2 {
            background-color: rgba(192, 192, 192, 0.1);
        }
        
        .top-2 .rank-cell {
            color: var(--silver);
            font-size: 1.1rem;
        }
        
        .top-3 {
            background-color: rgba(205, 127, 50, 0.1);
        }
        
        .top-3 .rank-cell {
            color: var(--bronze);
        }
        
        .user-cell {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
        }
        
        .score-cell {
            font-weight: 600;
            color: var(--primary-color);
            text-align: right;
        }
        
        .badge-top {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            color: white;
            text-align: center;
            line-height: 25px;
            font-size: 0.8rem;
            margin-right: 8px;
        }
        
        .badge-1 {
            background-color: var(--gold);
        }
        
        .badge-2 {
            background-color: var(--silver);
        }
        
        .badge-3 {
            background-color: var(--bronze);
        }
        
        .current-user {
            background-color: rgba(106, 90, 205, 0.15);
            border-left: 4px solid var(--primary-color);
        }
        
        @media (max-width: 576px) {
            .leaderboard-title {
                font-size: 2rem;
            }
            
            .table-leaderboard thead th,
            .table-leaderboard tbody td,
            .table-leaderboard tbody th {
                padding: 0.75rem;
            }
            
            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="leaderboard-header text-center">
        <div class="container">
            <h1 class="leaderboard-title">🏆 Daftar Peringkat</h1>
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
                                <span class="badge bg-primary ms-2">Kamu</span>
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