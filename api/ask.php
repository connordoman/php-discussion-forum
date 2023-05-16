<?php require 'profile.php';

session_start();

$title = postVar("question_title");
$body = postVar("question_body");
$tags = postVar("question_tags");

function truncateTitle($t) {
    return substr($t, 0, 100);
}

function truncateBody($b) {
    return substr($b, 0, 50000);
}

function processTags($t) {
    $tags = explode(",", $t);
    $tags = array_map("trim", $tags);
    $tags = array_map("strtolower", $tags);
    $tags = array_unique($tags);
    $tags = array_filter($tags);
    return $tags;
}

function insertTags($tags) {
    $num_tags = 0;
    for ($i = 0; $i < count($tags); $i++) {
        $tag = $tags[$i];
        $sql_select = "SELECT * FROM tags WHERE name = ?";
        $result = query($sql_select, $tag, "s")->fetch_all();
        if (count($result) < 1) {
            $sql_insert = "INSERT INTO tags (name) VALUES (?)";
            $insert_result = query($sql_insert, $tag, "s");
            if ($insert_result) {
                $num_tags++;
            }
        }
    }
    return $num_tags;
}

function insertQuestion($title, $body) {
    $sql_post = "INSERT INTO posts (uid, content, postDate, appeal) VALUES (?, ?, CURRENT_TIMESTAMP, 1);";
    $result_post = query($sql_post, [$_SESSION["uid"], $body], "is");

    $sql_question = "INSERT INTO questions (title, pid) VALUES (?, (SELECT MAX(pid) FROM posts));";
    $result = query($sql_question, [$title], "s");

    $sql_get_q = "SELECT MAX(qid) FROM questions;";
    $result = query($sql_get_q)->fetch_all();
    return $result[0][0];
}

function askQuestion() {
    global $title, $body, $tags, $conn;
    $conn->begin_transaction();
    try {

        if (!isset($_SESSION["uid"])) {
            throw new Exception("User not logged in.");
        } else if (isUserBanned($_SESSION["uid"])) {
            throw new Exception("User is banned.");
        }

        $title = truncateTitle($title);
        $body = truncateBody($body);
        $tags_array = processTags($tags);
        $number_of_tags = insertTags($tags_array);
        $qid = insertQuestion($title, $body);
        
        if (!$qid) {
            throw new Exception("Error inserting question and post");
        }

        foreach($tags_array as $i => $tag) {
            $sql_select = "SELECT tid FROM tags WHERE name = ?";
            $result = query($sql_select, $tag, "s")->fetch_all();
            if (count($result) > 0) {
                $sql_insert = "INSERT INTO question_tags (qid, tid) VALUES (?, ?)";
                $insert_result = query($sql_insert, [$qid, $result[0][0]], "is");
            }
        }
        unset($tag);

        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollback();
        throw $e;
    }
    return $qid;
}

// echo "Result: " . askQuestion();

// echo "Result: " . $number_of_tags;

header('Content-Type: application/json');
echo json_encode(["success" => true, "qid" => askQuestion()]);
exit();

?>