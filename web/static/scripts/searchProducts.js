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
 * @param requestString str The server request.
 * @param callback A function that will be called when the data is changed
 */
searcher.search = function(requestString, callback) {
    let xmlhttp = new XMLHttpRequest();
    const requestIndex = this.requestIndex + 1;
    this.requestIndex = requestIndex;
    let searcher = this;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if(requestIndex > searcher.lastCompletedRequestIndex) {
                searcher.lastCompletedRequestIndex = requestIndex;
                document.getElementsByClassName("productsTableBody")[0].innerHTML = this.responseText;
                callback();
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
 * @param callback Function to be call when data is changed
 */
searcher.searchByName = function(input, callback) {
    this.search("find?search=" + encodeURIComponent(input), callback);
};

searcher.searchByFormData = function (form, callback) {
    const queryString = [...new FormData(form).entries()]
        .map(e => encodeURIComponent(e[0]) + "=" + encodeURIComponent(e[1]))
        .join('&');
    this.search("filter?" + queryString, callback);
};