<?php require '../api/profile.php' ?>
<?php
session_start();

// turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$logged_in_on_profile = isset($user) && isset($_SESSION['uid']) && $_SESSION['uid'] == $user['uid'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Profile | Stack King</title>
        <?php include '../api/load_remote.php';?>
    </head>
    <body>
        <?php include '../components/header.php' ?>
        <main>
            <div id="content">
                <div class="profile">
                <?php

                if (isset($user)) {
                ?>
                    <?php if ($logged_in_on_profile) echo "<span class='logout'></span>";?>
                    <div class="userHeader">
                        <?php
                        if (isset($user['profilePic']) && $user['profilePic'] != '') {
                            echo "<span class='profilePic'><img src='/domanc/images/profile_pic/" . $user['profilePic'] . "'/></span>";
                        }
                        ?>
                        <h1><?php echo $user['username']; ?></h1>
                        <?php
                        if(isset($user['isModerator']) && $user['isModerator'] === true) {
                            echo "<span title='User is a moderator'><i class='fas fa-star'></i></span>";
                        } else if (isset($_SESSION['uid']) && isUserModerator($_SESSION['uid']) ) {
                            if (isUserBanned($user['uid']) === false) {
                                echo "<a title='Ban user' href='/domanc/ban/?uid=" . $user['uid'] . "'><i class='fas fa-gavel'></i></a>";
                            } else {
                                echo "<a title='Unban user' href='/domanc/unban/?uid=" . $user['uid'] . "'><i class='fas fa-check'></i></a>";
                            }
                        }
                        ?>
                    </div>
                    <span class="name">
                        <i class="fas fa-user"></i>
                        <?php echo $user['firstName'] . ' ' . $user['lastName']; ?>
                    </span>
                    <table>
                        <tr>
                            <td class="fieldName">Appeal:</td>
                            <td>
                                <?php
                                    $sql_appeal = 'SELECT SUM(appeal) FROM posts WHERE uid = ?';
                                    $appeal = query($sql_appeal, [$user['uid']], 'i')->fetch_row()[0];
                                    echo $appeal ?? 0;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldName">Joined:</td>
                            <td><?php echo $user['joinDate']; ?> UTC</td>
                        </tr>
                        <tr>
                            <td class="fieldName">Last Online:</td>
                            <td><?php echo $user['lastLogin']; ?> UTC</td>
                        </tr>
                    </table>
                    <?php

                    if ($logged_in_on_profile) {
                        echo "<h3>Change password:</h3>";
                        include '../components/change_password.php';
                    }

                    ?>
                    <br/>
                    <h2>Questions:</h2>
                    <?php
                    include '../components/question_preview.php';
                    if (isset($user['questions']) && count($user['questions']) > 0) {
                        foreach ($user['questions'] as $question) {
                            echo createPreview($question['qid'], $question['title'], $question['content']);
                        }
                    } elseif ($logged_in_on_profile) {
                        echo '<p>
                            You have not asked any questions.
                        </p><p><a class="linkbutton" href="/domanc/ask">Post your first question</a></p>';
                    } else {
                        echo '<p>
                                This user has not asked any questions.
                            </p>';
                        }
                    ?>
                    <?php
                } else {
                ?>
                    <h1>Profile not found</h1>
                    <p>
                        The profile you are looking for does not exist.
                    </p>
                <?php
                }
                ?>
                </div>
            </div>
        </main>
        <?php include '../components/footer.php'?>
        <script>
        </script>
    </body>
</html>
