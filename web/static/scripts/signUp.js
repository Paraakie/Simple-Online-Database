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
        document.getElementById("emailErrorMessage").style.color = "red";
        document.getElementById("emailErrorMessage").innerText = "Please enter correct email!";
    } else {
        document.getElementById("emailErrorMessage").innerText = "";
    }
}