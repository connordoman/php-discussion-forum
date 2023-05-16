<?php
/*
 * Created on Sun Mar 19 2023
 * Copyright (c) 2023 Connor Doman
 */

/**
 * Schema:
 * 
 * title: string,
 * content: string,
 * author: string,
 * postDate: string,
 * appeal: int,
 */
function createPost($post, $id = "", $answer = false, $top_answer = false) {
    if ($top_answer) {
        $id = 'id="top-answer"';
    } else {
        $id = 'id="' . $id . '"';
    }

    $body = explode('\n\n', $post['content']);
    for ($i = 0; $i < count($body); $i++) {
        if (strlen($body[$i]) == 0) {
            unset($body[$i]);
            continue;
        }
        $body[$i] = '<p class="post-body">' . $body[$i] . '</p>';
    }
    
    $title = "";
    if (isset($post['title']) && $post['title'] != "") {
        $title = '<h2>' . $post['title'] . '</h2>';
    }

    $post_date = new DateTime($post['postDate']);
    $pst = new DateTimeZone('America/Los_Angeles');
    $post_date->setTimezone($pst);
    $post_date = $post_date->format('Y-m-d H:i:s') . ' PST';


    return '<article class="post" ' . $id . ' data-answer="' . $answer . '" data-top-answer="'. $top_answer . '">
        <div class="post-content flex-col">
            ' . $title . '
            ' . implode('', $body) . '
            <span></span>
        </div>
        <div class="post-data flex-row">
            <span class="incrementor">
                <span class="upvote"><i class="fa-solid fa-arrow-up"></i></span>
                <span class="vote-count">' . $post['appeal'] . '</span>
                <span class="downvote"><i class="fa-solid fa-arrow-down"></i></span>
            </span>
            <span class="post-data-item" title="'. $post_date . '"
                ><i class="fa-solid fa-clock"></i> ' . substr($post_date, 0, 11) . '</span
            >
            <span class="post-data-item"><i class="fa-solid fa-user"></i> <a href="/domanc/profile/?username='. $post['author'] .'">'. $post['author'] . '</a></span>
        </div>
    </article>';
}

?>
