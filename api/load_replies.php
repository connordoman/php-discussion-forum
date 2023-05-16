<?php
require './question.php';
require '../components/post.php';
session_start();

// this is the dumbest thing in the world PHP sucks.
if( count($_POST) === 0 ){
    $_POST = json_decode(file_get_contents('php://input'), true);
}

// turn on error reporting

function loadReplies() {
    $qid = postVar('qid');
    $top_pid = postVar('top');
    $replies = prepareReplies($qid);
    $reply_posts = [];
    for ($i = 0; $i < count($replies); $i+=1) {
        if ($replies[$i]["pid"] === $top_pid) {
            continue;
        }
        array_push($reply_posts, createPost($replies[$i], "answer-" . $replies[$i]['author'] . "-" . $replies[$i]['pid'], true, false));
    }
    unset($replies);
    return $reply_posts;
}

header('Content-Type: application/json');
// print_r($_POST);
echo json_encode([ "replies" => loadReplies()]);
exit();

?>