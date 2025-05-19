<?php
session_start();
include "functions.php";
$quiz_id = $_GET["id"];
$sql = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
$result = mysqli_query($mysqli, $sql);

// Get quiz info for header
$quiz_info = mysqli_query($mysqli, "SELECT q.title, c.category_name 
                                   FROM quizzes q 
                                   JOIN category c ON q.category_id = c.id 
                                   WHERE q.id = $quiz_id");
$quiz_data = mysqli_fetch_assoc($quiz_info);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions | Queasy</title>

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
            --primary-color: #6a5acd;
            --secondary-color: #ff8c66;
            --light-bg: #f9f5ff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: #333;
        }
        
        .quiz-header {
            background: linear-gradient(135deg, var(--primary-color), #8a6de9);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }
        
        .quiz-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .quiz-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .action-card {
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .create-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .create-btn:hover {
            background: #5a4cb3;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(106, 90, 205, 0.3);
        }
        
        .back-btn {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #f0edff;
            color: var(--primary-color);
        }
        
        .questions-table {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .questions-table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .questions-table th {
            font-weight: 600;
            padding: 1rem;
        }
        
        .questions-table td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .action-btn {
            padding: 6px 12px;
            font-size: 0.9rem;
            margin: 2px;
            min-width: 80px;
            transition: all 0.2s;
        }
        
        .edit-btn {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .delete-btn {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }
        
        .options-btn {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 0;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--primary-color);
            opacity: 0.3;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .quiz-title {
                font-size: 1.8rem;
            }
            
            .action-btn {
                display: block;
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <div class="quiz-header">
        <div class="container">
            <h1 class="quiz-title">Manage Questions</h1>
            <p class="quiz-subtitle">
                <?php echo htmlspecialchars($quiz_data['title']); ?> • 
                <?php echo htmlspecialchars($quiz_data['category_name']); ?>
            </p>
        </div>
    </div>
    
    <div class="container">
        <div class="action-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Question List</h5>
                    <p class="mb-0 text-muted">Manage all questions for this quiz</p>
                </div>
                <div>
                    <a href="create_questions.php?quiz_id=<?php echo $quiz_id ?>&go=my" class="btn create-btn">
                        <i class="fas fa-plus me-1"></i> Add New Question
                    </a>
                    <a href="my_quiz.php" class="btn back-btn ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to My Quizzes
                    </a>
                </div>
            </div>
        </div>
        
        <div class="questions-table">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Question</th>
                            <th width="30%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        mysqli_data_seek($result, 0); // Reset pointer
                        while($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo htmlspecialchars($row["quest_text"]); ?></td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <a href="edit_questions.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary action-btn edit-btn me-1">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <a href="delete_questions.php?id=<?php echo $row["id"]; ?>&quiz_id=<?php echo $row["quiz_id"]; ?>" class="btn btn-danger action-btn delete-btn me-1">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </a>
                                        <a href="my_options.php?id=<?php echo $row["id"]; ?>" class="btn btn-success action-btn options-btn">
                                            <i class="fas fa-list-ul me-1"></i> Options
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                        $i++;
                        endwhile; 
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state p-4">
                    <div class="empty-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4>No Questions Found</h4>
                    <p class="text-muted">You haven't added any questions to this quiz yet.</p>
                    <a href="create_questions.php?quiz_id=<?php echo $quiz_id ?>&go=my" class="btn create-btn mt-3">
                        <i class="fas fa-plus me-1"></i> Create First Question
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>