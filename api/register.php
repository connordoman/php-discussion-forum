<?php require "connect_db.php";

/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */

$image_dir = SITE_ROOT . "/images/profile_pic/";

function insertUser($firstName, $lastName, $email, $username, $password, $profilePicUrl) {
    $sql = "INSERT INTO users (firstName, lastName, email, username, password, joinDate, lastLogin, appeal, profilePic) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0, ?)";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $result = query($sql, [$firstName, $lastName, $email, $username, $hashed_password, $profilePicUrl], 'ssssss');
    return $result;
}

function checkIfProfilePicValid() {
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        return true;
    } else {
        return false;
    }
}

function determineProfilePicName() {
    global $image_dir;
    $image_name = basename($_FILES["profile_pic"]["name"]);
    $image_name = str_replace(" ", "_", $image_name);
    $image_name = str_replace("'", "", $image_name);
    $image_name = str_replace('"', "", $image_name);
    $image_name = str_replace("?", "", $image_name);
    $image_name = str_replace("!", "", $image_name);
    $image_name = str_replace(":", "", $image_name);
    $image_name = str_replace(";", "", $image_name);
    $image_name = str_replace(",", "", $image_name);
    // $image_name = str_replace(".", "", $image_name);
    $image_name = str_replace("(", "", $image_name);
    $image_name = str_replace(")", "", $image_name);
    $image_name = str_replace("[", "", $image_name);
    $image_name = str_replace("]", "", $image_name);
    $image_name = str_replace("{", "", $image_name);
    $image_name = str_replace("}", "", $image_name);
    $image_name = str_replace("<", "", $image_name);
    $image_name = str_replace(">", "", $image_name);
    $image_name = str_replace("/", "", $image_name);
    $image_name = str_replace("\\", "", $image_name);
    $image_name = str_replace("|", "", $image_name);
    $image_name = str_replace("=", "", $image_name);
    $image_name = str_replace("+", "", $image_name);
    $image_name = str_replace("-", "", $image_name);
    $image_name = str_replace("*", "", $image_name);
    $image_name = str_replace("&", "", $image_name);
    $image_name = str_replace("^", "", $image_name);
    $image_name = str_replace("%", "", $image_name);
    $image_name = str_replace("$", "", $image_name);
    $image_name = str_replace("#", "", $image_name);
    $image_name = str_replace("@", "", $image_name);
    $image_name = str_replace("`", "", $image_name);
    $image_name = str_replace("~", "", $image_name);
    return strval(time()) . $image_name;
}

function checkIfProfilePicExists($image_name) {
    return file_exists($image_name);
}

function checkProfilePicSize() {
    return $_FILES["profile_pic"]["size"] < 1000000; // 1 MB
}

function checkProfilePicType($image_name) {
    $image_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    return $image_type == "jpg" || $image_type == "jpeg" || $image_type == "png" || $image_type == "gif";
}

function checkIfUsernameOrEmailExists($username, $email) {
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $result = query($sql, [$username, $email], 'ss');
    return $result->num_rows > 0;
}

function placeProfilePicOnSystem() {
    global $image_dir;
    $image_name = determineProfilePicName();
    if (!checkIfProfilePicValid()) {
        // sendJSON(["success" => false, "message" => "Profile pic is not valid."]);
        return false;
    }

    if (checkIfProfilePicExists($image_name)) {
        // sendJSON(["success" => false, "message" => "Profile pic already exists."]);
        return false;
    }

    if (!checkProfilePicSize()) {
        // sendJSON(["success" => false, "message" => "Profile pic is too large."]);
        return false;
    }
    if (!checkProfilePicType($image_name)) {
        // sendJSON(["success" => false, "message" => "Profile pic is not a valid type.", "type" => strtolower(pathinfo($image_name, PATHINFO_EXTENSION)), "file_name" => $image_name]);
        return false;
    }
    if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $image_dir . $image_name)) {
        // sendJSON(["success" => false, "message" => "Profile pic could not be uploaded."]);
        return false;
    }
    return $image_name;
}

function ableToRegister() {
    return postVar("firstName") && postVar("lastName") && postVar("email") && postVar("username") && postVar("password") && postVar("password_confirm");
}

function sendJSON($array) {
    header('Content-Type: application/json');
    echo json_encode($array);
    exit();
}

function processRegistration() {
    $can = ableToRegister();
    if ($can) {
        if (checkIfUsernameOrEmailExists(postVar("username"), postVar("email"))) {
            sendJSON(["success" => false, "message" => "Username or email already exists."]);
        } else {
            if (postVar("password") == postVar("password_confirm")) {
                $profile_pic_name = placeProfilePicOnSystem();

                if ($profile_pic_name) {
                    insertUser(postVar("firstName"), postVar("lastName"), postVar("email"), postVar("username"), postVar("password"), $profile_pic_name);
                } else {
                    insertUser(postVar("firstName"), postVar("lastName"), postVar("email"), postVar("username"), postVar("password"), null);
                }
                sendJSON(["success" => true, "message" => "User registered."]);
            } else {
                sendJSON(["success" => false, "message" => "Passwords do not match."]);
            }
        }
    } else {
        sendJSON(["success" => false, "message" => "Unable to register."]);
    }
}

processRegistration();

// if (fileVar("profile_pic")) {
//     if (placeProfilePicOnSystem()) {
//         sendJSON(["success" => true, "message" => "Profile pic uploaded."]);
//     } else {
//         sendJSON(["success" => false, "message" => "Profile pic could not be uploaded."]);
//     }
// } else {
//     // echo "No profile pic.";
//     sendJSON(["success" => false, "message" => "No profile pic.", "file" => $_FILES["profile_pic"]]);
// }
?>