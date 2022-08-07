// handle the ajax requests from the button elements
document.addEventListener('DOMContentLoaded', function () {
    var removeResultsButton = document.getElementById('agonyz-remove-cached-results');
    var makeRequestButton = document.getElementById('agonyz-make-new-request');

    removeResultsButton.addEventListener('click', function() {
        fetch(REMOVE_CACHED_RESULTS_URL)
            .then((response) => response.json())
            .then((data) => location.reload());
    }, false);

    makeRequestButton.addEventListener('click', function () {
        fetch(MAKE_REQUEST_URL)
            .then((response) => response.json())
            .then((data) => location.reload());
    }, false);
},false);
