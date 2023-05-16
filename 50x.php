<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Server Error | Stack King</title>
        <?php include 'api/load_remote.php';?>
    </head>
    <body>
        <?php
        include 'components/header.php';
        ?>
        <main>
            <div id="content">
                <h2>Server Error</h2>
                <p>The server encountered an error and could not process your request.</p>
            </div>
        </main>
        <?php include 'components/footer.php' ?>
    </body>
</html>
