<?php
/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */

// require '../api/connect_db.php';
require '../api/profile.php';

session_start();

$uid_ban = getVar('uid');
$qid_ban = getVar('qid');

if (!isset($_SESSION['uid']) || !isUserModerator($_SESSION['uid']) || (!$uid_ban && !$qid_ban)) {
    header('Location: ../login');
    exit();
};
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Unban | Stack King</title>
        <?php include "../api/load_remote.php" ?>
        <script src='../res/scripts/ask.js' type='text/javascript'></script>
    </head>
    <body>
        <?php include "../components/header.php"; ?>
        <main>
            <div id="content">
                <h2>Unban</h2>

                <?php
                
                if ($uid_ban) {
                    $sql_ban = "DELETE FROM banned_users WHERE uid = ?;";
                    $result = query($sql_ban, [$uid_ban], 'i');

                    $sql_usr = "SELECT username FROM users WHERE uid = ? LIMIT 1;";
                    $username = query($sql_usr, [$uid_ban], 'i')->fetch_row()[0];

                    echo "<p>User <strong>$username</strong> has been unbanned.</p>";
                }

                if ($qid_ban) {
                    $sql_ban = "DELETE FROM banned_posts WHERE pid = ?;";
                    $result = query($sql_ban, [$qid_ban], 'i');

                    $sql_qst = "SELECT title FROM questions WHERE qid = ? LIMIT 1;";
                    $title = query($sql_qst, [$qid_ban], 'i')->fetch_row()[0];

                    echo "<p>Question <strong>$title</strong> has been unbanned.</p>";
                }
                 ?>
                <p class="error"></p>
                <p class="success"></p>
            </div>
        </main>
        <?php include "../components/footer.php"; ?>
    </body>
</html>
