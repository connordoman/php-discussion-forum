<?php
/*
 * Created on Sat Mar 18 2023
 * Copyright (c) 2023 Connor Doman
 */

?>
<link rel="shortcut icon" href="https://cosc360.ok.ubc.ca/domanc/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="https://cosc360.ok.ubc.ca/domanc/res/css/globals.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" href="https://cosc360.ok.ubc.ca/domanc/res/css/desktop.css?v=<?php echo time(); ?>" />
<!--jquery-->
<script
src="https://code.jquery.com/jquery-3.6.4.min.js"
integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
crossorigin="anonymous"></script>
<!--code highlighting-->
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/stackoverflow-dark.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
<!--load fontawesome styles -->
<script src="https://kit.fontawesome.com/73d23ad498.js" crossorigin="anonymous"></script>
<!--load custom scripts-->
<script src="https://cosc360.ok.ubc.ca/domanc/res/scripts/ui.js"></script>
<script src="https://cosc360.ok.ubc.ca/domanc/res/scripts/forms.js"></script>
<!-- load the user's timezone to PHP -->
<script type="text/javascript">
    // This code was supposed to asynchronously inform php of the user's timezone, but it doesn't work and php might as well be impossible to debug remotely.
    /*
    document.addEventListener('DOMContentLoaded', async () => {
        let tz = Intl.DateTimeFormat().resolvedOptions().timeZone;

        await fetch('https://cosc360.ok.ubc.ca/domanc/api/timezone.php?time=' + encodeURIComponent(encodeURIComponent(tz)), {
            method: 'GET'
        });
    });
    */
</script>
