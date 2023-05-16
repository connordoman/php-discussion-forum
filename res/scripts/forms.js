/*
 * Created on Wed Feb 22 2023
 * Copyright (c) 2023 Connor Doman
 */

// const USERNAME_REGEX = /^[a-zA-Z0-9_]+$/g;
// const EMAIL_REGEX = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/g;
// const PASSWORD_REGEX = /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+$/g;

// regex to keep alphanumeric characters and underscore
const NAME_REGEX = /([\w -]+)/g;
const USERNAME_REGEX = /([\w]+)/g;
const USERNAME_REGEX_REPLACE = /([^\w]+)/g;
const EMAIL_REGEX = /([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/gi;
const EMAIL_REGEX_REPLACE = /([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)(?!.+)/gi;
const PASSWORD_REGEX = /([\w!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+)/gi;
const PASSWORD_REGEX_REPLACE = /[^\w!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/gi;

const numberCheck = (value) => {
    return !isNaN(value);
};

const stringCheck = (value, regex) => {
    if (typeof value !== "string") {
        return false;
    }
    return regex.test(value);
};

const replaceBadCharacters = (value, regex) => {
    if (typeof value !== "string") {
        return "";
    }
    return value.replace(regex, "$1");
};

const truncateString = (str, len) => {
    if (str.length > len) {
        return str.slice(0, len);
    }
    return str;
};

const validateForm = (formId) => {
    clearError();
    clearSuccess();
    let form = document.getElementById(formId);
    let inputElements = form.getElementsByTagName("input");

    let password = "";
    let passwordConfirm = "-1"; // in case a form doesn't have a password confirm field
    let elem;
    for (let i = 0; i < inputElements.length; i++) {
        elem = inputElements[i];

        // are all fields filled out?
        if (elem.value == "") {
            setError("Please fill out all fields");
            return false;
        }

        // Store passwords for comparison
        if (elem.name == "password") {
            password = elem.value;
        }
        if (elem.name == "password_confirm") {
            passwordConfirm = elem.value;
        }

        // Check if email is an email
        if (elem.name == "email") {
            let emailComponents = elem.value.split("@");

            // check if email has an @ symbol
            if (emailComponents.length < 2) {
                setError("Please enter a valid email address");
                return false;
            }
            // check if email has a domain
            if (emailComponents[1].split(".").length < 2) {
                setError("Please enter a valid email address");
                return false;
            }
        }

        // Check if username is valid
        if (elem.name == "username") {
            if (elem.value.length < 3) {
                setError("Username must be at least 3 characters long");
                return false;
            } else if (elem.value.length > 32) {
                setError("Username must be less than 32 characters long");
                return false;
            }
        }

        // truncate long fields
        if (elem.value.length > 255) {
            elem.value = elem.value.slice(0, 256);
        }
    }

    // Check if passwords match
    if (passwordConfirm != -1 && password !== passwordConfirm) {
        setError("Passwords do not match");
        return false;
    }

    return true;
};

const addListenersToFormInputs = (form) => {
    // prepare input elements
    let inputs = form.querySelectorAll("input[type=text], input[type=password]");
    for (let i = 0; i < inputs.length; i++) {
        // determine which type of input element this is
        let localRegex = USERNAME_REGEX;
        let localReplaceRegex = USERNAME_REGEX_REPLACE;
        if (inputs[i].name == "email") {
            localRegex = EMAIL_REGEX;
            localReplaceRegex = EMAIL_REGEX_REPLACE;
        } else if (inputs[i].name == "password") {
            localRegex = PASSWORD_REGEX;
            localReplaceRegex = PASSWORD_REGEX_REPLACE;
        }

        // add key up and keydown listeners
        inputs[i].addEventListener("keydown", (e) => {
            if (stringCheck(e.key, localReplaceRegex)) {
                e.preventDefault();
            }
            // return;
        });
        inputs[i].addEventListener("keyup", (e) => {
            inputs[i].value = truncateString(replaceBadCharacters(e.target.value, localRegex), 256);
            // allow passwords to contain arbitrary spaces
            if (inputs[i].name !== "password") {
                inputs[i].value = inputs[i].value.trim();
            }
        });
    }
};



/**
 * @returns {Promise<void>}
 * @description prepares the search box and adds event listeners. Fired after the page loads.
 */
const prepareSearchBox = async () => {
    const searchForm = document.getElementById("search-form");
    const searchBox = document.getElementById("search-box");
    const searchQuery = document.getElementById("search-query");
    const searchResults = document.getElementById("search-results");

    const createPreviewElements = (results) => {
        searchResults.innerHTML = "";
        for (let r of results) {
            searchResults.innerHTML += r;
        }
    };
    
    const getSearchResults = async (query) => {
        let response = await fetch("/domanc/api/search.php?q=" + encodeURIComponent(encodeURIComponent(query)));
        let data = await response.json();
        return data;
    };

    const processResults = async (query) => {
        searchResults.innerHTML = "";
        
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        if (!query && params.q) {
            query = params.q;
        }

        if (query == "" || !query) {
            return;
        }

        if (searchBox.value !== query) {
            searchBox.value = query;
        }

        if (searchQuery) {
            searchQuery.textContent = query;
        }

        let results = await getSearchResults(query);
        if (results) {
            createPreviewElements(results);
        }
    }

    // if we're on the search page, process the results
    if (window.location.href.indexOf("/domanc/search") > -1) {
        await processResults();
    }

    // add event listeners
    searchForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const query = searchBox.value.toLowerCase();

        // redirect to search page if not on it
        if (window.location.href.indexOf("/domanc/search") < 0) {
            window.location.href = encodeURI(`/domanc/search?q=${query}`);
            return;
        }

        // alert(query);
        await processResults(query);
    });
}


const prepareForm = (formId, apiRoute, redirectTo = "", onSuccess, onError) => {
    // find form DOM element
    let form = document.getElementById(formId);

    // add listeners to form inputs
    // addListenersToFormInputs(form);

    // add form submit listener
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // validate form input on submit
        if (!validateForm(formId)) {
            return;
        }

        // get form data
        let formData = new FormData(form);
        // for (const pair of formData) {
        //     data.append(pair[0], pair[1]);
        // }

        if (formData["profile_pic"]) {
            let fileInput = form.querySelector("input[type=file]");
            formData["profile_pic"] = fileInput.files[0];
        }
        console.log(formData);

        clearError();
        clearSuccess();

        fetch(apiRoute, {
            method: "POST",
            body: formData,
        }).then((res) => {
            let resultCopy = res.clone();
            res.json()
                .then((json) => {
                    console.log(JSON.stringify(json, null, 4));
                    if (json.error || !json.success) {
                        setError(json.message);
                        return;
                    }
                    setSuccess(`${json.message ? json.message : ""} ${onSuccess}`);
                    console.log(json);
                    form.style.display = "none";

                    if (redirectTo && redirectTo.length > 0) {
                        setTimeout(() => {
                            window.location.replace("https://cosc360.ok.ubc.ca/domanc" + redirectTo);
                        }, 250);
                    }
                })
                .catch((err) => {
                    clearSuccess();
                    console.log(err);
                    resultCopy.text().then((text) => {
                        console.log(text);
                    });
                    setError(onError);
                });
        });
    });
};
