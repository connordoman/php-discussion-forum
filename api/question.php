<?php

/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */

require 'connect_db.php';

function getQuestion($qid) {
    $sql = 'SELECT * FROM all_questions WHERE qid = ? LIMIT 1;';
    $result = query($sql, [$qid], 'i'); 
    if ($result->num_rows == 0) {
        return [];
    }
    return $result->fetch_assoc();
}

function getReplies($qid) {
    $sql = 'SELECT * FROM all_replies WHERE qid = ?;';
    $result = query($sql, [$qid], 'i');
    if ($result->num_rows == 0) {
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function questionExists($qid) {
    // echo "checking if question exists: ". $qid . "<br>";
    if (!isset($qid) || !is_numeric($qid)) {
        return false;
    }
    $sql = 'SELECT COUNT(*) FROM questions GROUP BY qid HAVING qid = ? LIMIT 1;';
    $result = query($sql, [$qid], 'i');
    // echo $result->num_rows;
    return $result->num_rows > 0;
}

function prepareReplies($qid) {
    $codeblock_find_regex = "/.*<code-block lang='(\w+)'>(.+)<\/code-block>.*/m";
    $codeblock_regex = "/<code-block lang='\w+'>.+<\/code-block>/m";
    // echo "preparing replies for question: $qid";
    $replies = getReplies($qid);
    foreach ($replies as $i => $reply) {
        foreach ($reply as $j => $value) {
            if (preg_match($codeblock_find_regex, $value) > 0) {
                $lang = htmlspecialchars(preg_replace($codeblock_find_regex, "$1", $value));
                $code = htmlspecialchars(preg_replace($codeblock_find_regex, "$2", $value));
                $replies[$i][$j] = preg_replace($codeblock_regex, "<code-block data-language=$lang>" . htmlspecialchars($code) . "</code-block>", $value);
            } else {
                $replies[$i][$j] = htmlspecialchars($value);
            }
        }
    }
    unset($reply);
    unset($value);
    return $replies;
}

function findQuestionAtGet() {
    $id = null;
    if ($id = findVar('id')) {
        $id = findVar('id');
    } else if (findVar('qid')) {
        $id = findVar('qid');
    }

    $id = intval($id);

    $exists = questionExists($id);
    // echo 'exists: ' . $exists;
    if ($exists) {
        $question = getQuestion($id);
        foreach ($question as $i => $q_value) {
            if (isset($q_value)) {
                $question[$i] = htmlspecialchars($q_value);
            }
        }
        unset($q_value);

        $replies = prepareReplies($id);

        $data = ["question" => $question, "replies" => $replies];
        // print_r(json_encode($data, JSON_PRETTY_PRINT));
        return $data;
    }
    return null;
}

?>