/*
 * Created on Sun Mar 26 2023
 * Copyright (c) 2023 Connor Doman
 */

const validateTitle = (title) => {
    if (title.length < 3) {
        return false;
    }
    if (title.length > 100) {
        return false;
    }
    return true;
};

const validatePostBody = (body) => {
    if (body.length < 3) {
        return false;
    }
    if (body.length > 50000) {
        return false;
    }
    return true;
};

const processTagString = (tagString) => {
    let tags = tagString.split(",");
    let processedTags = [];
    for (let i = 0; i < tags.length; i++) {
        let tag = tags[i].trim();
        if (tag.length > 0) {
            processedTags.push(tag);
        }
    }
    return processedTags;
};

const processQuestion = async () => {
    let form = document.getElementById("question_form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        let formData = new FormData(form);
        let title = formData.get("question_title");
        let body = formData.get("question_body");
        let tags = formData.get("question_tags");

        if (!validateTitle(title)) {
            setError("Title must be between 3 and 100 characters");
            return;
        }
        if (!validatePostBody(body)) {
            setError("Body must be between 3 and 50000 characters");
            return;
        }

        let tagsArray = processTagString(tags);

        if (tagsArray.length < 1) {
            setError("Must have at least one tag");
            return;
        }

        let formDataBody = new FormData();
        formDataBody.append("title", title);
        formDataBody.append("body", body);
        formDataBody.append("tags", tagsArray);

        // console.log(...formDataBody);

        let response = await fetch("/domanc/api/ask.php", {
            method: "POST",
            body: formData,
        });

        let resultCopy = response.clone();
        try {
            clearError();
            clearSuccess();
            let result = await response.json();
            // console.log("Result:", result);
            if (result.success) {
                setSuccess("Posted! Redirecting...");
                form.style.display = "none";
                console.log("Response:", result);

                setTimeout(() => {
                    window.location.replace(`https://cosc360.ok.ubc.ca/domanc/question/?id=${result.qid}`);
                }, 500);
            }
        } catch (e) {
            console.log("Error:", e);
            console.log("Response:", await resultCopy.text());
            setError("An error occurred");
        }
    });
};
