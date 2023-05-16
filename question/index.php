<?php
/*
 * Created on Sun Mar 19 2023
 * Copyright (c) 2023 Connor Doman
 */
include '../components/post.php';
include '../api/question.php';
session_start();

// had to redefine these functions because PHP's imports are ridiculous and bad.
function isUserModerator($uid) {
    $sql = 'SELECT * FROM moderators WHERE uid = ? LIMIT 1';
    $result = query($sql, $uid, 'i');
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function isUserBanned($uid) {
    $sql = 'SELECT COUNT(*) FROM banned_users WHERE uid = ? LIMIT 1';
    $result = query($sql, $uid, 'i')->fetch_row()[0];
    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>How do I use getElementById()? | Stack King</title>
        <?php include '../api/load_remote.php' ?>
    </head>
    <body>
        <?php include '../components/header.php' ?>
        <main>
            <div id="content">
                <?php 
                    // from api/question.php
                    $q = findQuestionAtGet();
                    $top_answer = -1;

                    // print_r($q);
                    if (isset($q)) {
                        $question = $q['question'];
                        $replies = $q['replies'];

                        if (isset($question['top'])) {
                            $top_answer = $question['top'];
                        }
                        
                        if (isUserModerator($_SESSION['uid'] ?? -1)) {
                            echo "<a title='Delete post' href='/domanc/ban/?qid=" . $question['pid'] . "' style='color: red;'><i class='fas fa-x'></i></a>";
                        }

                        echo createPost($question, "question", false, false);

                        $tags = [];
                        $sql_tags = "SELECT name FROM tagged_questions WHERE qid = ?;";
                        $result_tags = query($sql_tags, [$question['qid']], 'i')->fetch_all();
                        foreach ($result_tags as $tag) {
                            array_push($tags, "<a href='/domanc/browse/?tag=". urlencode($tag[0]) . "'>" . $tag[0] . "</a>");
                        }
                        unset($tag);
                        echo "<p>Tags: " . implode(", ", $tags) . "</p>";

                        $answers_array = [];
                        $top_answer_body = "";
                        for ($i = 0; $i < count($replies); $i+=1) {
                            $a = $replies[$i];
                            
                            // put the top answer at the beginning of the array
                            if ($a['pid'] == $top_answer) {
                                $top_answer_body = createPost($a, "answer-" . $a['author'] . "-" . $a['pid'], true, true);
                                // $answers_array = array_merge([createPost($a, "answer-" . $a['author'] . "-" . $a['pid'], true, true)], $answers_array);
                            } else {
                                array_push($answers_array, createPost($a, "answer-" . $a['author'] . "-" . $a['pid'], true, false));
                            }
                        }

                        if (strlen($top_answer_body) > 0) {
                            // echo "<hr />";
                            echo "<h2>Top Answer:</h2>";
                            echo $top_answer_body;
                        }

                        // echo reply form here
                        if (isset($_SESSION['user'])) {
                            // echo "<hr/>";
                            echo "<h2>Reply to Question:</h2>";
                            if (isUserBanned($_SESSION['uid'] ?? -1)) {
                                echo "<h3>You are banned from posting replies</h2>";
                            } else {
                                include "../components/reply_form.php";
                                echo '<script type="text/javascript">prepareForm("reply_form", "../api/reply.php", null, "", "Failed to post reply");</script>';
                            }
                        }
                        
                    } else {
                        echo "<h2>Question not found</h2>";
                    }
                    unset($q);
                ?>
                <div id="answer-container">
                    <?php 
                    echo "<h2>" . (strlen($top_answer_body) > 0 ? "Other " : "") . "Answers:</h2>";
                    for ($i = 0; $i < count($answers_array); $i+=1) {
                        echo $answers_array[$i];
                    }
                    ?>
                </div>
                <script type="text/javascript">
                    const answerContainer = document.getElementById("answer-container");

                    const reloadAnswers = async () => {
                        const res = await fetch("/domanc/api/load_replies.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                qid: "<?php echo $question['qid'] ?>",
                                top: "<?php echo $top_answer ?>"
                            })
                        });

                        const data = await res.json();
                        if (data) {
                            // console.log(data);
                            let newAnswerContainerHTML = "";
                            for (let p of data.replies) {
                                newAnswerContainerHTML += p;
                            }

                            if (newAnswerContainerHTML.length !== answerContainer.innerHTML.length) {
                                answerContainer.innerHTML = "<?php echo "<h2>" . (strlen($top_answer_body) > 0 ? "Other " : "") . "Answers:</h2>"; ?>";
                                answerContainer.innerHTML += newAnswerContainerHTML;
                                addCodeBlocksSync();
                            }
                        }
                    }
                    reloadAnswers();
                    // try and load more questions every second
                    setInterval(reloadAnswers, 5000);
                </script>
            </div>
        </main>
        <?php include '../components/footer.php' ?>
    </body>
</html>
