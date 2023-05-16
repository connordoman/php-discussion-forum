<?php

/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */

?>
<form id="register-form" method="post" enctype="multipart/form-data">
    <span class="textbox">
        <input type="text" name="firstName" placeholder="First name" />
    </span>
    <span class="textbox">
        <input type="text" name="lastName" placeholder="Last name" />
    </span>
    <span class="textbox">
        <input type="text" name="email" placeholder="Email" />
    </span>
    <span class="textbox">
        <input type="text" name="username" placeholder="Username" />
    </span>
    <span class="textbox">
        <input type="password" name="password" placeholder="Password" />
    </span>
    <span class="textbox">
        <input type="password" name="password_confirm" placeholder="Confirm password" />
    </span>
    <span class="textbox">
        <input type="file" id="profile_pic" name="profile_pic" placeholder="Profile picture" />
    </span>
    <input type="submit" name="register" value="Register" />
</form>