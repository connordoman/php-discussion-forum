<?php
/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */
 ?>
<header>
 <nav>
     <div id="logo">
         <a href="/domanc/" title="Home">
             <h1>
             <span class="icon">&nbsp;&lt;&nbsp;<i class="fas fa-crown"></i>&nbsp;&gt;&nbsp; </span>
             <!-- <br class="mobile" /> -->
                 Stack King
             </h1>
         </a>
     </div>
     <div id="search">
        <form id="search-form">
            <span class="textbox">
                <input type="text" id="search-box" placeholder="Search..." />
            </span>
        </form>
         <span id="hamburger" class="hamburger">
             <i class="fa-solid fa-bars"></i>
         </span>
     </div>
 </nav>
</header>
 <div id="hidden-menu" data-visible="false">
        <ul>
            <li><a href="/domanc/" title="Home">Home</a></li>
            <li><a href="/domanc/browse/" title="Browse Questions">Browse Questions</a></li>
            <li><a href="/domanc/profile" title="Profile">Profile</a></li>
            <?php if (isset($_SESSION['user'])) {
                echo '<li><a href="/domanc/ask" title="Ask a Question">Ask a Question</a></li>';
                echo '<li>Logged in as <strong>' . $_SESSION["username"] . '</strong></li>';
                echo '<li class="logout"></li>';
              }  else {
                echo '<li><a href="/domanc/login" title="Login">Login</a></li>';
             } ?>
        </ul>
 </div>
