/**
 * An object used to search for products asynchronously and display them
 */
searcher = {};
searcher.requestIndex = 0;
searcher.lastRequest = null;
searcher.currentRequest = null;
searcher.lastCompletedRequestIndex = 0;

/**
 * Finds products using the given server request asynchronously and displays them.
 * @param input str The server request.
 */
searcher.search = function(requestString) {
    let xmlhttp = new XMLHttpRequest();
    const requestIndex = this.requestIndex + 1;
    this.requestIndex = requestIndex;
    let searcher = this;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if(requestIndex > searcher.lastCompletedRequestIndex) {
                searcher.lastCompletedRequestIndex = requestIndex;
                document.getElementsByClassName("productsTableBody")[0].innerHTML = this.responseText;
            }
            if(xmlhttp === searcher.currentRequest) {
                searcher.currentRequest = null;
            } else if (xmlhttp === searcher.lastRequest) {
                searcher.lastRequest = null;
            }
        }
    };
    xmlhttp.open("GET", requestString, true);
    xmlhttp.send();
    if(searcher.lastRequest === null) {
        searcher.lastRequest = searcher.currentRequest;
    }
    if(searcher.currentRequest !== null) {
        searcher.currentRequest.abort();
        searcher.currentRequest = xmlhttp;
    }
};

/**
 * Finds products with a similar name asynchronously and displays them.
 * @param input str The name to search for in the products table
 */
searcher.searchByName = function(input) {
    this.search("find?search=" + encodeURIComponent(input));
};