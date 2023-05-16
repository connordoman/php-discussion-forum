<?php

/*
 * Created on Wed Mar 15 2023
 * Copyright (c) 2023 Connor Doman
 */

?>
<footer>
    <script>
        window.addEventListener("load", async () => {
            // loadQuestions();
            addCodeBlocksSync();
            hydrateLogoutButtons();
            await prepareSearchBox();

            let ham = $("#hamburger");
            let hiddenMenu = $("#hidden-menu");
            window.addEventListener("resize", () => {
                if (hiddenMenu.attr("data-visible") == "true") {
                    hiddenMenu.css({
                        left: "calc(100% - 10em))",
                    });
                } else if (hiddenMenu.attr("data-visible") == "false") {
                    hiddenMenu.css({
                        left: "100%",
                    });
                }
                console.log("resized");
            });

            const handleMoveMenu = () => {
                let vis = hiddenMenu.attr("data-visible");
                if (vis == "true") {
                    hiddenMenu.attr("data-visible", "false");
                    hiddenMenu.animate(
                        {
                            left: `+=10em`,
                            right: 0,
                        },
                        500
                    );
                } else {
                    hiddenMenu.attr("data-visible", "true");
                    hiddenMenu.animate(
                        {
                            left: `-=10em`,
                            right: 0,
                        },
                        500
                    );
                }
            }
            ham.click(handleMoveMenu);
            ham.on("touchstart", handleMoveMenu);
        });
    </script>
    <div class="flex-row">
        <div class="flex-col">
            <a href="https://github.com/COSC360/project-connordoman"
                ><i class="fa-brands fa-github"></i> COSC 360 Project</a
            >
            <a href="https://connordoman.dev"><i class="fa-solid fa-computer"></i> connordoman.dev</a>
            <a href="https://twitter.com/connordoman"><i class="fa-brands fa-twitter"></i> Twitter</a>
        </div>
        <div class="flex-col">
            <span>&copy;2023 Connor Doman</span
            ><span>Send feedback on this website to domanc@student.ubc.ca</span>
        </div>
    </div>
</footer>
