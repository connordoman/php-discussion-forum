<?php
require '../api/connect_db.php';
session_start();

// turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// data flow:
// need to add reply to posts
// need to add reply to replies

function addReplyToPosts() {
    // need uid, content
    $sql = "INSERT INTO posts (uid, content, postDate, appeal) VALUES (?, ?, CURRENT_TIMESTAMP, 1)";
    // sanitize post body
    $content =  htmlspecialchars($_POST['reply_body']);
    $result = query($sql, [$_SESSION['uid'], $_POST['reply_body']], "is");
    if ($result === false) {
        throw new Exception("Failed to add reply to posts!");
    }
    return $result;
}

function addReplyToReplies($qid, $pid) {
    // $sql_get_pid = "SELECT MAX(pid) FROM posts;";
    // $pid = query($sql_get_pid)->fetch_row()[0];
    // need qid, pid
    $sql = "INSERT INTO replies (qid, pid) VALUES (?, ?)";
    $result = query($sql, [$qid, $pid], "ii");
    if ($result === false) {
        throw new Exception("Failed to add reply to replies!");
    }
}

function addReplyToDatabase() {
    global $conn;
    $conn->begin_transaction();
    try {
        $qid = postVar('question_id');
        if (!$qid) {
            throw new Exception("Question ID not set!");
        }

        $pid = addReplyToPosts();
        addReplyToReplies($qid, $pid);
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Successful reply']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to post reply!']);
        exit();
    } finally {
        $conn->close();
    }
}

addReplyToDatabase();

// echo json_encode(['success' => true, 'message' => 'Successfully posted reply!']);

?>