<?php
session_start();
?>
<form id="reply_form">
    <span class="textarea">
        <textarea name="reply_body" placeholder="Your response"></textarea>
    </span>
    <input type="hidden" name="question_id" value="<?php echo getVar("id") ?>" />
    <input type="submit" value="Reply as <?php echo $_SESSION['username'] ?>" />
</form>
<p class="error"></p>
<p class="success"></p>