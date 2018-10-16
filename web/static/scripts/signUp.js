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
    return isValid;
}