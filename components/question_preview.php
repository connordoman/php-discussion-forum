<?php
// Compare this snippet from components/question_preview.php:
function createPreview($qid, $title, $content, $username = "") {
    if (strlen($content) > 200) {
        $content = substr($content, 0, 200) . '...';
    }
    $preview = "";
    $preview = $preview . '<article class="question">';
    $preview = $preview . '<h3>' . $title . '</h3>';
    $preview = $preview . '<hr />';
    $preview = $preview . '<p>' . $content . '</p>';
    $preview = $preview . '<p>';
    if ($username != "")
        $preview = $preview . '<span>Asked by: <a href="/domanc/profile?username=' . urlencode($username) . '">' . $username . '</a></span><br/><br/>';
    $preview = $preview . '<a href="/domanc/question?id=' . $qid . '" class="linkbutton highlight">View Question</a>';
    $preview = $preview . '</p>';
    $preview = $preview . '</article>';
    return $preview;
}
?>