<?php

session_start();

require 'connect_db.php';

if (isset($_SESSION['uid'])) {
    $sql = 'SELECT password FROM users WHERE uid = ? LIMIT 1';
    $uid = $_SESSION['uid'];
    $result = query($sql, $uid, 'i');
    $pw = $result->fetch_assoc()['password'];

    $hash_pw = password_hash(postVar('old_password'), PASSWORD_DEFAULT);

    if (password_verify(postVar('old_password'), $pw)) {
        $sql = 'UPDATE users SET password = ? WHERE uid = ?';
        if (postVar('password') != postVar('password_confirm')) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit();
        }
        $hash_pw_new = password_hash(postVar('password'), PASSWORD_DEFAULT);
        $result = query($sql, [$hash_pw_new, $uid], 'si');
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
}


?>