<?php
require '../api/connect_db.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>COSC 360 Project | Stack King</title>
        <?php include '../api/load_remote.php';?>
    </head>
    <body>
        <?php
        include '../components/header.php';
        ?>
        <main>
            <div id="content">
                <h2>Recent Questions</h2>
                <!-- Article preview text will be injected by JavaScript (or php LOL) and will be limited in character number-->
                <?php
                include '../components/question_preview.php';

                $count = 10;
                if (getVar('count')) {
                    $count = intval(getVar('count'));
                }
                $sql_all_questions = "SELECT qid, title, content, author FROM all_questions ORDER BY postDate DESC LIMIT ?;";
                $questions = query($sql_all_questions, $count, 'i')->fetch_all(MYSQLI_ASSOC);

                $tag = '';
                if (getVar('tag')) {
                    $tag = getVar('tag');
                    echo "<h3>Posts tagged '$tag'</h3>";
                    $sql_tag_questions = "SELECT qid, title, content, author FROM all_questions WHERE qid = (SELECT qid FROM tagged_questions WHERE name = ?) ORDER BY postDate DESC LIMIT ?;";
                    $questions = query($sql_tag_questions, [$tag, $count], 'si')->fetch_all(MYSQLI_ASSOC);
                }

                for ($i = 0; $i < count($questions); $i++) {
                    echo createPreview($questions[$i]['qid'], $questions[$i]['title'], $questions[$i]['content'], $questions[$i]['author']);
                }
                ?>
                <p>
                    <?php 
                    echo '<a href="https://cosc360.ok.ubc.ca/domanc/browse/?count=' . ($count + 10) . (strlen($tag) > 0 ? '&tag=' . $tag : '') .'" class="linkbutton">Load 10 more</a></p>';
                    ?>
                </p>
            </div>
        </main>
        <?php include '../components/footer.php' ?>
    </body>
</html>
