<?php
/*
 * Created on Sun Mar 19 2023
 * Copyright (c) 2023 Connor Doman
 */


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
?>