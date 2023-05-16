<?php
/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */

require '../api/profile.php';

session_start();

if (!isset($_SESSION['uid'])) {
    header('Location: ../login');
    exit();
};
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New Post | Stack King</title>
        <?php include "../api/load_remote.php" ?>
        <script src='../res/scripts/ask.js' type='text/javascript'></script>
    </head>
    <body>
        <?php include "../components/header.php"; ?>
        <main>
            <div id="content">
                <h2>Post</h2>
                <?php
                
                if (isUserBanned($_SESSION['uid'])) {
                    echo "<p>You have been banned from posting.</p>";
                } else {
                    echo "<p>
                    Ask your programming or computer science related question here. Try not to ask a question
                    that&apos;s been asked before. Make sure you explain your question thoroughly and include any code
                    or other information that is relevant to what is being asked.
                    </p>";
                    include "../components/ask_form.php";
                    echo "<script type='text/javascript'>processQuestion();</script>";
                }
                
                 ?>
                <p class="error"></p>
                <p class="success"></p>
            </div>
        </main>
        <?php include "../components/footer.php"; ?>
    </body>
</html>
