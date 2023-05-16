<?php require 'connect_db.php'; include '../components/question_preview.php'; ?>
<?php

// turn on error reporting in the browser
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function findPosts($search) {
    $sql = "SELECT * FROM all_questions WHERE title LIKE ? OR author LIKE ? OR LOCATE(?, content) > 0 ORDER BY postDate DESC;";
    $wildcard_search = '%' . $search . '%';
    $result = query($sql, [$wildcard_search, $wildcard_search, $search], 'sss');
    return $result;
}

function createPreviewsFromResults($search_results) {
    $previews = [];
    for ($i = 0; $i < count($search_results); $i++) {
        $row = $search_results[$i];
        $prev = createPreview($row['qid'], $row['title'], $row['content'], $row['author']);
        array_push($previews, $prev);
    }
    return $previews;
}

// echo "Result: " . print_r(findPosts(findVar('q'))->fetch_all(), true);
echo json_encode(createPreviewsFromResults(findPosts(findVar('q'))->fetch_all(MYSQLI_ASSOC)));

?>