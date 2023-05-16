<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>404 Not Found | Stack King</title>
        <?php include 'api/load_remote.php';?>
    </head>
    <body>
        <?php
        include 'components/header.php';
        ?>
        <main>
            <div id="content">
                <h2>404 - Page Not Found</h2>
                <p>The page you are looking for could not be found.</p>
            </div>
        </main>
        <?php include 'components/footer.php' ?>
    </body>
</html>
