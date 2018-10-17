function getErrorsInEmail(email) {
    //using regular expression to check the email format

    const pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if(email === ""){
        return null;
    }
    const result = pattern.test(email);
    if(result === true){
        return null;
    }
    return "Please enter correct email!";
}

function validateEmail()
{
    const email = document.getElementById("emailInput").value;
    let emailErrorMessage = document.getElementById("emailErrorMessage");
    const error = getErrorsInEmail(email);
    if(email === "" || (error === null)) {
        emailErrorMessage.innerText = "";
    } else {
        emailErrorMessage.innerText = error;
    }
}

function getErrorInPassword(password) {
    if(!password.match(/^[a-zA-Z0-9]+$/)) {
        return "The password must contain only letters and digits";
    }
    if(!password.match(/[A-Z]+/)) {
        return "The password must contain at least one uppercase letter";
    }
    if(password.length > 14) {
        return "The password cannot be longer than 14 characters";
    }
    if(password.length < 7) {
        return "The password must be at least 7 characters long";
    }
    return null;
}

function validatePassword(password) {
    let passwordErrorMessage = document.getElementById('passwordErrorMessage');
    const error = getErrorInPassword(password);
    if(password === "" || (error === null)) {
        passwordErrorMessage.innerText = "";
    } else {
        passwordErrorMessage.innerText = error;
    }
}

function validateSubmit(form) {
    let isValid = true;

    let passwordErrorMessage = document.getElementById('passwordErrorMessage');
    passwordErrorMessage.innerText = "";
    const userPassword = form["userPassword"].value;
    const userPassword2 = form["userPassword2"].value;
    if(userPassword !== userPassword2) {
        passwordErrorMessage.innerText = "The two passwords must match";
        isValid =  false;
    }
    let error = getErrorInPassword(userPassword);
    if(error !== null) {
        passwordErrorMessage.innerText = error;
        isValid =  false;
    }

    let emailErrorMessage = document.getElementById("emailErrorMessage");
    emailErrorMessage.innerText = "";
    const email = document.getElementById("emailInput").value;
    error = getErrorsInEmail(email);
    if(error !== null) {
        emailErrorMessage.innerText = error;
        isValid = false;
    }

    if(document.getElementById("userNameErrorMessage").innerText !== "") {
        isValid = false;
    } else {
        let userName = form["userName"].value;
        let xHTTP = new XMLHttpRequest();
        xHTTP.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200){
                document.getElementById("userNameErrorMessage").innerText = this.responseText;
                if(this.responseText !== "") {
                    isValid = false;
                }
            } else if (this.readyState === 4 && this.status === 404){
                alert("not found");
            }
        };
        xHTTP.open("GET", "checkUserName/"+userName, false);
        xHTTP.send();
    }

    return isValid;
}

function checkUserExist(userInput){

    //check user input length
    if(userInput.length === 0) {
        document.getElementById("userNameErrorMessage").innerText = "Please enter a user name!";
    } else {
        let xHTTP = new XMLHttpRequest();
        xHTTP.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200){
                document.getElementById("userNameErrorMessage").innerText = this.responseText;
            } else if (this.readyState === 4 && this.status === 404){
                alert("not found");
            }
        };

        xHTTP.open("GET", "checkUserName/"+userInput);
        xHTTP.send();
    }
}

function centerBasedOnFirstTwoColumns() {
    let inputForm = document.getElementById("inputForm");
    let tableBody = document.getElementById("inputBody");
    const row = tableBody.rows[0];
    const cells = row.cells;
    const rowLength = cells.length;
    let totalWidth = 0;
    for(let i = 0; i < (rowLength - 1); ++i) {
        let positionInfo = cells[i].getBoundingClientRect();
        totalWidth += positionInfo.width;
    }
    let windowWidth = window.innerWidth;
    inputForm.style.position = "absolute";
    inputForm.style.left = ((windowWidth / 2) - (totalWidth / 2))+'px';
}