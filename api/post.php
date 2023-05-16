
<?php require "connect_db.php" ?>
<?php 
    
/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */


/**
* Get a POST variable
*/
function post($key) {
    return htmlspecialchars($_POST[$key]);
}


function insertQuestion($conn, $title, $content, $author, $category, $tags) {
    $sql = "INSERT INTO posts (uid, title, content, postDate, appeal) VALUES (?, ?, ?, CURRENT_TIMESTAMP, 1)";
    $sql = "INSERT INTO questions (title, content, author, postDate, category, tags, appeal) VALUES (?, ?, ?, CURRENT_TIMESTAMP, 1)";
    $result = query($sql, [$title, $content, $author], 'sss');
    if ($result === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Return the example POST data
echo "POST data from post:\n" . json_encode($_POST, JSON_PRETTY_PRINT);
?>