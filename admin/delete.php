<?php
session_start();
require_once(__DIR__ . "/../layout/functions.php");

if(!isset($_SESSION['username'])) {
    header('location:../login.php');
    exit;
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('location:../index.php');
    exit;
}

if(isset($_GET["table"]) && isset($_GET["id"])) {
    $table = $_GET["table"];
    $id = (int)$_GET["id"];
    
    // Start transaction for atomic operations
    mysqli_begin_transaction($mysqli);
    
    try {
        // Special handling for questions with story segments
        if($table == "questions") {
            // First delete all related story segments
            $delete_stories = mysqli_query($mysqli, "DELETE FROM story_segments WHERE question_id = $id");
            if(!$delete_stories) {
                throw new Exception("Failed to delete story segments: " . mysqli_error($mysqli));
            }
            
            // Then delete all related options
            $delete_options = mysqli_query($mysqli, "DELETE FROM options WHERE question_id = $id");
            if(!$delete_options) {
                throw new Exception("Failed to delete options: " . mysqli_error($mysqli));
            }
        }
        
        // Special handling for quizzes (delete questions and their dependencies)
        if($table == "quizzes") {
            // Get all questions in this quiz
            $questions = mysqli_query($mysqli, "SELECT id FROM questions WHERE quiz_id = $id");
            while($question = mysqli_fetch_assoc($questions)) {
                $question_id = $question['id'];
                
                // Delete story segments for each question
                mysqli_query($mysqli, "DELETE FROM story_segments WHERE question_id = $question_id");
                
                // Delete options for each question
                mysqli_query($mysqli, "DELETE FROM options WHERE question_id = $question_id");
            }
            
            // Then delete all questions in this quiz
            mysqli_query($mysqli, "DELETE FROM questions WHERE quiz_id = $id");
        }
        
        // Special handling for category (delete quizzes and their dependencies)
        if($table == "category") {
            // Get all quizzes in this category
            $quizzes = mysqli_query($mysqli, "SELECT id FROM quizzes WHERE category_id = $id");
            while($quiz = mysqli_fetch_assoc($quizzes)) {
                $quiz_id = $quiz['id'];
                
                // Get all questions in this quiz
                $questions = mysqli_query($mysqli, "SELECT id FROM questions WHERE quiz_id = $quiz_id");
                while($question = mysqli_fetch_assoc($questions)) {
                    $question_id = $question['id'];
                    
                    // Delete story segments for each question
                    mysqli_query($mysqli, "DELETE FROM story_segments WHERE question_id = $question_id");
                    
                    // Delete options for each question
                    mysqli_query($mysqli, "DELETE FROM options WHERE question_id = $question_id");
                }
                
                // Then delete all questions in this quiz
                mysqli_query($mysqli, "DELETE FROM questions WHERE quiz_id = $quiz_id");
            }
            
            // Then delete all quizzes in this category
            mysqli_query($mysqli, "DELETE FROM quizzes WHERE category_id = $id");
        }
        
        // Now delete the main record
        $query = "DELETE FROM $table WHERE id = $id";
        $result = mysqli_query($mysqli, $query);
        
        if(!$result) {
            throw new Exception("Failed to delete record: " . mysqli_error($mysqli));
        }
        
        // Commit transaction if all queries succeeded
        mysqli_commit($mysqli);
        
        // Redirect based on table type - menggunakan index.php dengan parameter content
        $redirect_url = "index.php?content=";
        switch($table) {
            case "user":
                $redirect_url .= "user&success=delete&item=user";
                break;
            case "quizzes":
                $redirect_url .= "quiz&success=delete&item=quiz";
                if(isset($_GET["categ_id"])) {
                    $redirect_url .= "&categ_id=".$_GET["categ_id"];
                }
                if(isset($_GET["categ_name"])) {
                    $redirect_url .= "&name=".$_GET["categ_name"];
                }
                break;
            case "questions":
                $redirect_url .= "questions&success=delete&item=question";
                if(isset($_GET["quiz_id"])) {
                    $redirect_url .= "&quiz_id=".$_GET["quiz_id"];
                }
                if(isset($_GET["quiz_name"])) {
                    $redirect_url .= "&quiz_name=".$_GET["quiz_name"];
                }
                break;
            case "options":
                $redirect_url .= "options&success=delete&item=option";
                if(isset($_GET["question_id"])) {
                    $redirect_url .= "&question_id=".$_GET["question_id"];
                }
                if(isset($_GET["question_text"])) {
                    $redirect_url .= "&question_text=".$_GET["question_text"];
                }
                break;
            case "category":
                $redirect_url .= "category&success=delete&item=category";
                break;
            default:
                $redirect_url .= "dashboard&success=delete&item=record";
        }
        
        header("Location: $redirect_url");
        
    } catch (Exception $e) {
        // Rollback transaction if any query failed
        mysqli_rollback($mysqli);
        
        echo "<script>
                alert('Error: ".addslashes($e->getMessage())."');
                window.history.back();
            </script>";
    }
} else {
    header("Location: index.php");
}
?>