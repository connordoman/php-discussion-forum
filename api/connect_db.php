<?php
require __DIR__ . "/../config.php";
/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */

$server_hostname = "MYSQL SERVER IP";
$server_port = 3306;
$server_username = "MYSQL USERNAME";
$server_password = "MYSQL PASSWORD";
$server_database = "MYSQL DATABASE";

// Create connection
$conn = new mysqli($server_hostname, $server_username, $server_password, $server_database);
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function query($sql, $params = [], $typestring = "") {
    global $conn;
    if (!is_array($params)) {
        $params = [$params];
    }
    $stmt = $conn->prepare($sql);
    if (count($params) == 0) {
        $result = $stmt->execute();
    } else {
        $stmt->bind_param($typestring, ...$params);
        $result = $stmt->execute();
    }
    if ($result) {
        if (isset($stmt->insert_id) && $stmt->insert_id > 0) {
            return $stmt->insert_id;
        } else {
            return $stmt->get_result();
        }
    }
    return false;
}

function getVar($name, $default = false) {
    if (isset($_GET[$name])) {
        return $_GET[$name];
    } else {
        return $default;
    }
}

function postVar($name, $default = false) {
    if (isset($_POST[$name])) {
        return $_POST[$name];
    } else {
        return $default;
    }
}

function fileVar($name, $default = false) {
    if (isset($_FILES[$name])) {
        return $_FILES[$name];
    } else {
        return $default;
    }
}

function findVar($name, $default = false) {
    $type = $_SERVER['REQUEST_METHOD'];
    if ($type == "GET") {
        return getVar($name, $default);
    } else if ($type == "POST") {
        return postVar($name, $default);
    } else {
        return $default;
    }
}


return $conn;
?>