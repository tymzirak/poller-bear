const userEditForm = document.querySelector("#user_edit_form");
const userEditError = document.querySelector("#user_edit_error");
if (userEditForm && userEditError)
    userEditForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userEditForm, {"method": "PATCH"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userEditError.innerHTML = "<div class='main-error settings-error'> " + error + " </div>";
            });
    });

const userDeleteForm = document.querySelector("#user_delete_form");
const userDeleteError = document.querySelector("#user_delete_error");
if (userDeleteForm && userDeleteError)
    userDeleteForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userDeleteForm, {"method": "DELETE"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userDeleteError.innerHTML = "<div class='main-error settings-error'> " + error + " </div>";
            });
    });

const userSignupForm = document.querySelector("#user_signup_form");
const userSignupError = document.querySelector("#user_signup_error");
if (userSignupForm && userSignupError)
    userSignupForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userSignupForm, {"method": "POST"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userSignupError.innerHTML = "<div class='main-error card-error'> " + error + " </div>";
            });
    });

const medias = document.querySelectorAll(".media");
if (medias)
    for (let media of medias) {
        let likeItem = media.querySelector(".media-bar-like");
        let likeIcon = media.querySelector(".media-bar-icon");
        let checkPair = getPair(likeIcon.className, "check");

        useCheck(
            checkPair,
            () => likeIcon["src"] = LIKE_IMG["checked"],
            () => likeIcon["src"] = LIKE_IMG["unchecked"]
        );

        likeItem.addEventListener("click", () => {
            likeIcon.className = switchCheck(
                likeIcon.className,
                getPair(likeIcon.className, "check"),
                () => likeIcon["src"] = LIKE_IMG["checked"],
                () => likeIcon["src"] = LIKE_IMG["unchecked"]
            );
        });
    }

const comments = document.querySelectorAll(".comment");
if (comments)
    for (let comment of comments) {
        let replyCont = comment.querySelector(".reply-cont");
        let commentBarReply = comment.querySelector(".comment-bar-reply");

        commentBarReply.addEventListener("mousedown", () => {
            displaySwitch(replyCont, "block", "none");
        });
    }

const pollConts = document.querySelectorAll(".poll-cont");
if (pollConts)
    for (pollCont of pollConts) {
        let pollBars = pollCont.querySelectorAll(".poll-bar");

        for (let pollBar of pollBars) {
            let checkPair = getPair(pollBar.className, "check");
            let pollVoted = pollBar.querySelector(".poll-voted");

            useCheck(
                checkPair,
                () => pollVoted.style.backgroundColor = "var(--theme-color-d)",
                () => pollVoted.style.backgroundColor = "var(--theme-color)"
            );
        }

        pollCont.addEventListener("click", (event) => {
            if (event.target.className.indexOf("poll-bar") == -1) return;

            let checkPair = getPair(event.target.className, "check");
            let pollVoted = event.target.querySelector(".poll-voted");

            for (let pollBar of pollBars) {
                pollBar.querySelector(".poll-voted").style.backgroundColor = "var(--theme-color)";
                pollBar.className = setTextPairValue(pollBar.className, getPair(pollBar.className, "check"), 0);
            }

            event.target.className = switchCheck(
                event.target.className,
                checkPair,
                () => pollVoted.style.backgroundColor = "var(--theme-color-d)",
                () => pollVoted.style.backgroundColor = "var(--theme-color)"
            );
        });
    }

const selects = document.querySelectorAll(".select");
if (selects)
    for (let select of selects) {
        let selectChecked = select.querySelector(".select-checked");
        let selectItems = select.querySelectorAll(".select-item");
        let storagePair = getPair(select.className, "storage");

        if (storagePair) {
            let storagePairValue = usePair(STORAGE_LIB, storagePair);
            if (storagePairValue)
                for (let selectItem of selectItems) {
                    let checkPair = getPair(selectItem.className, "check");

                    if (parsePair(getPair(selectItem.className, "select"))[1] == storagePairValue)
                        selectItem.className = setTextPairValue(selectItem.className, checkPair, 1);
                    else selectItem.className = setTextPairValue(selectItem.className, checkPair, 0);
                }
        }

        for (let selectItem of selectItems)
            if (isChecked(getPair(selectItem.className, "check"))) {
                selectChecked.innerText = selectItem.innerText;
                break;
            }

        select.addEventListener("click", (event) => {
            if (event.target.className.indexOf("select-item") == -1) return;

            for (let selectItem of selectItems)
                selectItem.className = setTextPairValue(selectItem.className, getPair(selectItem.className, "check"), 0);

            usePair(SELECT_LIB, getPair(event.target.className, "select"));

            event.target.className = switchCheck(
                event.target.className,
                getPair(event.target.className, "check"),
                () => selectChecked.innerText = event.target.innerText,
                () => selectChecked.innerText = event.target.innerText
            );
        });
    }

const profileMenuNavItems = document.querySelectorAll(".profile-menu-nav-item");
if (profileMenuNavItems)
    for (let profileMenuNavItem of profileMenuNavItems)
        execUrlPathMatch(
            profileMenuNavItem.href,
            () => profileMenuNavItem.style.borderBottom = "2px var(--theme-color) solid"
        );

const dropdownUriParams = document.querySelectorAll(".dropdown-uriparam");
if (dropdownUriParams)
    for (let dropdownUriParam of dropdownUriParams)
        dropdownUriParam.addEventListener("click", (event) => {
            let pair = getPair(event.target.className, "uriparam");
            if (!pair) return;

            let uriParamPair = parsePair(pair);

            setUriParam(uriParamPair[0], uriParamPair[1]);
        });
