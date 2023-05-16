<?php require 'connect_db.php';?>
<?php 

error_reporting(E_ALL);
ini_set('display_errors', 'On');

/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */

session_start();

function updateLoginTime($uid) {
    $sql = 'UPDATE users SET lastLogin = CURRENT_TIMESTAMP WHERE uid = ?';
    $result = query($sql, $uid, 'i');
}

function send_json($array) {
    header('Content-Type: application/json');
    echo json_encode($array);
    exit();
}

function askToLogin($username, $password) {
    $sql = 'SELECT username, password FROM users WHERE username = ? LIMIT 1';
    $result = query($sql, $username, 's');
    $user = $result->fetch_assoc();

    foreach ($user as $i => $value) {
        $user[$i] = htmlspecialchars($value);
    }
    unset($value);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $sql = 'SELECT * FROM users WHERE username = ? LIMIT 1';
            $result = query($sql, $username, 's');
            $user_full = $result->fetch_assoc();
            foreach ($user_full as $i => $value) {
                $user_full[$i] = htmlspecialchars($value);
            }
            unset($value);

            $_SESSION['uid'] = $user_full['uid'];
            $_SESSION['username'] = $user_full['username'];
            $_SESSION['email'] = $user_full['email'];
            $_SESSION['firstName'] = $user_full['firstName'];
            $_SESSION['lastName'] = $user_full['lastName'];
            $_SESSION['joinDate'] = $user_full['joinDate'];
            $_SESSION['lastLogin'] = $user_full['lastLogin'];
            $_SESSION['appeal'] = $user_full['appeal'];
            $_SESSION['profilePic'] = $user_full['profilePic'];
            $_SESSION['user'] = $user_full;

            return true;
        } else {
            return false;
        }
    }
}

try {

    
    if (isset($_SESSION['uid'])) {
        $user = $_SESSION['user'];
        send_json(["success" => true, ...$user]);
    }
    
    if (postVar("username") && postVar("password")) {
        if (askToLogin(postVar("username"), postVar("password"))) {
            updateLoginTime($_SESSION['uid']);
            $user = $_SESSION['user'];
            send_json(["success" => true, "message" => "Welcome back."]);
        } else {
            send_json(["success" =>  false, "message" => "Invalid username or password."]);
        }
    }
} catch (Exception $e) {
    send_json(["success" => false, "message" => $e->getMessage()]);
}

?>