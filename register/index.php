<?php include '../config.php';
/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */

session_start();

 if (isset($_SESSION['uid'])) {
    header('Location: ../');
    exit();
 };
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register | Stack King</title>
        <?php include '../api/load_remote.php' ?>
    </head>
    <body>
        <?php include '../components/header.php' ?>
        <main>
            <div id="content">
                <h2>Register for a Stack King Account</h2>
                <p>Fill out the fields below to register for a Stack King account.</p>
                <?php include '../components/register_form.php' ?>
                <p class="error"></p>
                <p class="success"></p>
            </div>
        </main>
        <?php include '../components/footer.php' ?>
        <script type="text/javascript">
            prepareForm("register-form", "/domanc/api/register.php", undefined, "Thanks for registering!", "Could not register");
        </script>
    </body>
</html>
