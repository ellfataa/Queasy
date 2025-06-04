<?php
    require_once '../../layout/config.php';
    session_start();
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
        exit;
    }
    // Only select non-admin users and order by score descending
    $user = mysqli_query($mysqli,"SELECT * FROM user WHERE role = 'user' ORDER BY score DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringkat</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="icon" href="../../img/q!.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../index.css" />
    <style>
        :root {
            --primary-color: #fcc822;
            --secondary-color: #ff8c66;
            --gold: #ffd700;
            --silver: #c0c0c0;
            --bronze: #cd7f32;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .leaderboard-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .leaderboard-title {
            font-family: "Fredoka One", cursive;
            font-size: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 0.5rem;
        }

        .leaderboard-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .leaderboard-container {
            padding: 0 10px;
            max-width: 800px;
            margin: 0 auto;
        }

        .leaderboard-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-leaderboard {
            width: 100%;
            margin-bottom: 0;
            min-width: 300px;
        }

        .table-leaderboard thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table-leaderboard thead th {
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-leaderboard tbody tr {
            transition: all 0.2s ease;
        }

        .table-leaderboard tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-leaderboard tbody td,
        .table-leaderboard tbody th {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }

        .rank-cell {
            font-weight: 700;
            text-align: center;
            width: 50px;
        }

        .top-1 {
            background-color: rgba(255, 215, 0, 0.08);
        }

        .top-1 .rank-cell {
            color: var(--gold);
        }

        .top-2 {
            background-color: rgba(192, 192, 192, 0.08);
        }

        .top-2 .rank-cell {
            color: var(--silver);
        }

        .top-3 {
            background-color: rgba(205, 127, 50, 0.08);
        }

        .top-3 .rank-cell {
            color: var(--bronze);
        }

        .user-cell {
            display: flex;
            align-items: center;
            min-width: 150px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .username-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .score-cell {
            font-weight: 600;
            color: var(--primary-color);
            text-align: right;
            white-space: nowrap;
        }

        .badge-top {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            color: white;
            text-align: center;
            line-height: 24px;
            font-size: 0.8rem;
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
            background-color: rgba(106, 90, 205, 0.1);
            position: relative;
        }

        .current-user::after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: var(--primary-color);
        }

        /* Mobile-specific styles */
        @media (max-width: 576px) {
            .leaderboard-header {
                padding: 1rem 0;
                border-radius: 0;
            }

            .leaderboard-title {
                font-size: 1.5rem;
            }

            .leaderboard-subtitle {
                font-size: 0.9rem;
            }

            .table-leaderboard thead th {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .table-leaderboard tbody td,
            .table-leaderboard tbody th {
                padding: 0.6rem;
                font-size: 0.8rem;
            }

            .user-avatar {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
                margin-right: 8px;
            }

            .badge-top {
                width: 20px;
                height: 20px;
                line-height: 20px;
                font-size: 0.7rem;
            }

            .rank-cell {
                width: 40px;
            }
        }

        @media (max-width: 400px) {
            .user-cell {
                min-width: 120px;
            }
            
            .username-text {
                max-width: 80px;
            }
        }
    </style>
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
            <div class="table-responsive">
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
                                <span class="username-text"><?= $row["username"] ?></span>
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>