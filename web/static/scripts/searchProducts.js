/**
 * An object used to search for products asynchronously and display them
 */
searcher = {};
searcher.requestIndex = 0;
searcher.lastRequest = null;
searcher.currentRequest = null;
searcher.lastCompletedRequestIndex = 0;

/**
 * Finds products with a similar name asynchronously and displays them.
 * @param input str The name to search for in the products table
 */
searcher.search = function(input) {
    let xmlhttp = new XMLHttpRequest();
    const requestIndex = this.requestIndex + 1;
    this.requestIndex = requestIndex;
    let searcher = this;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if(requestIndex > searcher.lastCompletedRequestIndex) {
                searcher.lastCompletedRequestIndex = requestIndex;
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
            if(xmlhttp === searcher.currentRequest) {
                searcher.currentRequest = null;
            } else if (xmlhttp === searcher.lastRequest) {
                searcher.lastRequest = null;
            }
        }
    };
    xmlhttp.open("GET", "find?search=" + encodeURIComponent(input), true);
    xmlhttp.send();
    if(searcher.lastRequest === null) {
        searcher.lastRequest = searcher.currentRequest;
    }
    if(searcher.currentRequest !== null) {
        searcher.currentRequest.abort();
        searcher.currentRequest = xmlhttp;
    }
};
