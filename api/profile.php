<?php require 'connect_db.php';

/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */


session_start();

 /**
   * Schema:
    * uid: int
    * username: varchar(255)
    * password: varchar(255)
    * email: varchar(255)
    * firstName: varchar(255)
    * lastName: varchar(255)
    * joinDate: datetime
    * lastLogin: datetime
    * appeal: int
    */

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

$user = [ 'uid' => 0, 'username' => 'Guest', 'email' => '', 'firstName' => '', 'lastName' => '', 'joinDate' => '', 'lastLogin' => '', 'appeal' => 0, 'profilePic' => '', 'isModerator' => false, 'questions' => array()];

// get user from id
if (isset($_GET['id'])) {
    $sql = 'SELECT * FROM users WHERE uid = ? LIMIT 1';
    $result = query($sql, $_GET['id'], 'i');
    $user = $result->fetch_assoc();
} else if (isset($_GET['username'])) {
    $sql = 'SELECT * FROM users WHERE username = ? LIMIT 1';
    $result = query($sql, $_GET['username'], 's');
    $user = $result->fetch_assoc();
} else if (isset($_SESSION['uid'])) {
    $sql = 'SELECT * FROM users WHERE uid = ? LIMIT 1';
    $uid = $_SESSION['uid'];
    $result = query($sql, $uid, 'i');
    $user = $result->fetch_assoc();
}

// get user's questions
if (isset($user)) {
    if (is_array($user)) {
        foreach ($user as $i => $value) {
            if (isset($value)) {
                $user[$i] = htmlspecialchars($value);
            } else {
                $user[$i] = "";
            }
        }
    } else {
        $user = htmlspecialchars($user);
    }
    $sql = 'SELECT * FROM all_questions WHERE uid =  ?';
    $result = query($sql, [$user['uid']], 'i');
    if (isUserModerator($user['uid'])) {
        $user['isModerator'] = true;
    } else {
        $user['isModerator'] = false;
    }
    $user['questions'] = $result->fetch_all(MYSQLI_ASSOC);
}

?>