<?php include '../config.php';
/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Search | Stack King</title>
        <?php include '../api/load_remote.php' ?>
    </head>
    <body>
        <?php include '../components/header.php' ?>
        <main>
            <div id="content">
                <h1 id="search-title">Search results for: <span id="search-query"></span></h1>
                <div id="search-results"></div>
            </div>
        </main>
        <?php include '../components/footer.php' ?>
        <script type="text/javascript">
            // prepareForm("register-form", "/domanc/api/register.php", undefined, "Thanks for registering!", "Could not register");
        </script>
    </body>
</html>
