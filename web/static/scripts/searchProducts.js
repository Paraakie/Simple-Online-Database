/**
 * sample code from w3school for testing
 * @param input str
 */
function showHint(input) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "find?search=" + encodeURIComponent(input), true);
    xmlhttp.send();
}
