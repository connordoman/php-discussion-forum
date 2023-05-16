/*
 * Created on Sun Feb 26 2023
 * Copyright (c) 2023 Connor Doman
 *
 * This file generates the UI element for the website.
 */

/**
 *  Utility Functions
 */

const getYYYYMMDD = (dateString) => {
    if (dateString instanceof string) {
        let date = new Date(date);
    }
    let mm = date.getMonth() + 1; // getMonth() is zero-based
    let dd = date.getDate();

    return [this.getFullYear(), (mm > 9 ? "" : "0") + mm, (dd > 9 ? "" : "0") + dd].join("-");
};

/*
 * Local State
 */

const STATE = {
    error: "",
    success: "",
    clear: () => {
        STATE.error = "";
        STATE.success = "";
    },
};

const getError = () => {
    // let localStorageError = localStorage.getItem("error");
    // return localStorageError ? localStorageError : "";
    return STATE.error;
};

const getSuccess = () => {
    // let localStorageSuccess = localStorage.getItem("success");
    // return localStorageSuccess ? localStorageSuccess : "";
    return STATE.success;
};

const updateError = () => {
    let localError = getError();
    console.log(`Update error: "${localError}"`);

    if (localError) {
        for (let item of document.getElementsByClassName("error")) {
            if (localError == "") {
                item.style.display = "none";
            } else {
                item.style.display = "block";
            }
            item.innerHTML = localError.replace(/\n/g, "<br>");
        }
    }
};

const updateSuccess = () => {
    let localSuccess = getSuccess();
    console.log(`Update success: "${localSuccess}"`);

    if (localSuccess) {
        for (let item of document.getElementsByClassName("success")) {
            if (localSuccess == "") {
                item.style.display = "none";
            } else {
                item.style.display = "block";
            }
            item.innerHTML = localSuccess.replace(/\n/g, "<br>");
        }
    }
};

const setError = (message) => {
    clearSuccess();
    STATE.error = "Error: " + message;
    updateError();
};

const clearError = () => {
    STATE.error = false;
    updateError();
};

const setSuccess = (message) => {
    clearError();
    STATE.success = message;
    updateSuccess();
};

const clearSuccess = () => {
    STATE.success = false;
    updateSuccess();
};

const checkForQuestionsAndAnswers = () => {
    // let question = document.getElementById("question");
    let topAnswer = document.getElementById("top-answer");
    // let answers = document.querySelectorAll("[data-answer='true']::before");

    topAnswer.setAttribute("title", "Top Answer Chosen By The Author");
};

const getFileText = async (filename) => {
    let file = await fetch(filename);
    let text = await file.text();
    return text;
};

const createCodeBlock = (text, language = "java") => {
    if (language !== "java") {
        language = language.toLowerCase();
    }

    let preBlock = document.createElement("pre");
    let codeBlock = document.createElement("code");
    codeBlock.classList.add("language-" + language);
    codeBlock.textContent = text;
    preBlock.appendChild(codeBlock);
    return preBlock;
};

const getCodeFromFile = async (file) => {
    let text = await getFileText(file);
    return text;
};

const populateCodeBlock = async (codeBlock) => {
    let file = codeBlock.getAttribute("data-file");
    let language = codeBlock.getAttribute("data-language");

    let languageField = document.createElement("span");
    languageField.className = "metadata";
    languageField.textContent = language;
    let codeBlockText = codeBlock.textContent;
    codeBlock.textContent = "";
    if (file) {
        codeBlockText = await getCodeFromFile(file);
    }
    let block = createCodeBlock(codeBlockText, language);
    codeBlock.appendChild(block);
    codeBlock.appendChild(languageField);
    return block.getElementsByTagName("code")[0];
};

const addCodeBlocks = async () => {
    // let codeBlocks = document.getElementsByClassName("code-block");
    let codeBlocks = document.getElementsByTagName("code-block");

    let newCodeBlocks = [];
    for (let i = 0; i < codeBlocks.length; i++) {
        let codeSpan = document.createElement("span");
        codeSpan.className = "code-block";
        for (let a of codeBlocks[i].attributes) {
            codeSpan.setAttribute(a.name, a.value);
        }
        codeSpan.innerHTML = codeBlocks[i].innerHTML;
        codeBlocks[i].parentNode.replaceChild(codeSpan, codeBlocks[i]);
        newCodeBlocks.push(await populateCodeBlock(codeSpan));
    }
    return newCodeBlocks;
};

const addCodeBlocksSync = () => {
    addCodeBlocks().then((blocks) => {
        hljs.highlightAll();
        for (let block of blocks) {
            block.classList.add("nohighlight");
        }
    });
};

const getQuestionJSON = async (questionId) => {
    let data = {
        id: questionId,
    };
    let response = await fetch("../api/question.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    });
    console.log(await response.clone().text());
    let json = await response.json();
    return json;
};

/**
 * Finds all span tags with the classname "logout" and adds a logout button to them.
 */
const hydrateLogoutButtons = () => {
    let logout = document.getElementsByClassName("logout");
    let button = document.createElement("a");
    button.className = "linkbutton";
    button.innerHTML = "Logout";

    for (let i = 0; i < logout.length; i++) {
        let b = button.cloneNode(true);
        b.addEventListener("click", () => {
            // logout clears PHP session

            fetch("/domanc/api/clear_session.php", {
                method: "POST",
            }).then((res) => {
                res.text().then((data) => {
                    console.log("Logged out: " + JSON.stringify(data));
                    window.location.replace("https://cosc360.ok.ubc.ca/domanc/");
                });
            });
            // logoutUser();
        });
        logout[i].appendChild(b);
    }
};

const getIncrementorAsString = (number) => {
    return `<span class="incrementor">
    <span class="upvote"><i class="fa-solid fa-arrow-up"></i></span>
    <span class="vote-count">${number}</span>
    <span class="downvote"><i class="fa-solid fa-arrow-down"></i></span>
    </span>`;
};

/**
 * Generate UI Elements
 */

const generateUI = () => {};

const generatePostBody = () => {
    let postBody = document.createElement("p");
    postBody.className = "post-body";
    return postBody;
};

const generateCodeBody = (text, textMillion) => {
    let codeBlock = createCodeBlock();
};

const generateQuestionElement = (question) => {
    let questionDate = new Date(question.questionDatetime);
    let time = questionDate.toLocaleTimeString("en-CA", {
        timeZone: "America/Vancouver",
        timeZoneName: "short",
    });
    let date = getYYYYMMDD(questionDate);

    code += `<div class="post-content flex-col">
        <h2>How do I use getElementById()?</h2>
        <p class="post-body">
            ${question.body}
        </p>
        </div>`;

    code += `<div class="post-data flex-row">
        <span class="post-data-item">
            ${getIncrementorAsString(question.appeal)}
        </span>
        <span class="post-data-item" title="${date + " " + time}"}"
            ><i class="fa-solid fa-clock"></i> ${date}</span
        >
        <span class="post-data-item"><i class="fa-solid fa-user"></i> ${question.questionAuthor}</span>
        <span class="post-data-item"><i class="fa-solid fa-comment"></i> ${question.numComments}</span>
        </div>`;
    return code;
};

const getQuestion = async (questionId) => {
    let question = await getQuestionJSON(questionId);
    return generateQuestionElement(question);
};

const getAllQuestions = async () => {
    let response = await fetch("/domanc/api/questions.php");
    let json = await response.json();
    return json;
};

const addExampleAnswer = () => {
    getQuestion(1).then((questionHTML) => {
        let q = document.createElement("article");
        q.className = "post";
        q.innerHTML = questionHTML;
    });
};

// add event listeners
