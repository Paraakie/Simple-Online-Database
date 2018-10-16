function checkEmailFormat()
{
    //using regular expression to check the email format
    var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    var value = document.getElementById("emailInput").value;
    if(value === ""){
        return;
    }
    var result = pattern.test(value);
    if(result != true){
        document.getElementById("emailErrorMessage").innerText = "Please enter correct email !";
    } else {
        document.getElementById("emailErrorMessage").innerText = "";
    }
}

function checkUserExist(userInput){

    //check user input length
    if(userInput.length == 0) {
        document.getElementById("userNameErrorMessage").innerText = "Please enter a user name !";
        return;
    }
    else {

        var xHTTP = new XMLHttpRequest();
        xHTTP.onreadystatechange = function () {
            if(this.readyState == 4 && this.status == 200){
                document.getElementById("userNameErrorMessage").innerText = this.responseText;
            } else if (this.readyState == 4 && this.status == 404){
                alert("not found");
            }
        };

        xHTTP.open("GET", "checkUserName/"+userInput);
        xHTTP.send();
    }
}