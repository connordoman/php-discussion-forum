<?php
require './api/connect_db.php';
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>COSC 360 Project | Stack King</title>
        <?php include 'api/load_remote.php';?>
    </head>
    <body>
        <?php
        include 'components/header.php';
        ?>
        <main>
            <div id="content">
                <h2>Welcome to Stack King</h2>
                <?php
                if (!isset($_SESSION['uid'])) {
                    echo '<p>
                        This is a website where you can ask questions about software, hardware, programming, and computer
                        science. Users can answer questions and vote on the best answers.
                    </p>
                    <p>
                        <a href="https://cosc360.ok.ubc.ca/domanc/login" class="linkbutton">Login</a> or
                        <a href="https://cosc360.ok.ubc.ca/domanc/register" class="linkbutton">Register</a>
                        to get started.
                    </p>';
                } else {
                    echo '<p>Welcome back, <strong>' . $_SESSION['username'] . '</strong>!</p><p>
                    <a href="https://cosc360.ok.ubc.ca/domanc/profile" class="linkbutton">View your profile</a></p>';
                }
                ?>
                <p><a href="https://cosc360.ok.ubc.ca/domanc/ask" class="linkbutton">Post a question</a></p>
                <!-- <p></p> -->
                <span style="display: flex; flex-direction: row; align-items: center; justify-content: right;"><h2>Recent Questions</h2>&nbsp;&nbsp;&nbsp;<a href="https://cosc360.ok.ubc.ca/domanc/browse" class="linkbutton">View all questions</a></span>
                <!-- Article preview text will be injected by JavaScript (or php LOL) and will be limited in character number-->
                <?php
                include 'components/question_preview.php';

                $sql_all_questions = "SELECT qid, title, content, author FROM all_questions ORDER BY postDate DESC LIMIT 10;";

                $questions = query($sql_all_questions)->fetch_all(MYSQLI_ASSOC);

                // print_r($questions);

                // $questions = [0 => ["title" => "What is the best programming language?", "content" => "I'm new to programming and I'm wondering what the best programming language is. I've heard that C++ is the best language, but I've also heard that Python is the best language. Which one is it?", "author" => "user1"],
                //               1 => ["title" => "Issue with event listeners in es6", "content" => "I'm trying to add an event listener to a button in my React app, but I'm getting an error. I've tried using the addEventListener method and the onClick method, but neither of them work. Here's my code:...", "author" => "user2"],
                //               2 => ["title" => "Help I'm stuck", "content" => "<code>[java code snippet]</code>", "author" => "user3"]];

                for ($i = 0; $i < count($questions); $i++) {
                    echo createPreview($questions[$i]['qid'], $questions[$i]['title'], $questions[$i]['content'], $questions[$i]['author']);
                }
                ?>
            </div>
        </main>
        <?php include 'components/footer.php' ?>
    </body>
</html>
