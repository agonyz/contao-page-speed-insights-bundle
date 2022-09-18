// handle the ajax requests from the button elements
function getRequestProgress(){
    fetch(REQUEST_PROGRESS_URL)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            let progressValue = (data['requestCounter'] / data['requestFinalCount'])
            if(typeof progressBar !== 'undefined' && !isNaN(progressValue)) {
                progressBar.animate(progressValue);
            }
            if(data['requestDone'] !== true) {
                setTimeout(getRequestProgress, 5000);
            } else {
                if(pageSpeedInformationContainer !== null) {
                    pageSpeedInformationContainer.style.display = 'block';
                }
                progressBarContainer.style.display = 'none';
            }
        });
}

document.addEventListener('DOMContentLoaded', function () {
    var removeResultsButton = document.getElementById('agonyz-remove-cached-results');
    var makeRequestButton = document.getElementById('agonyz-make-new-request');
    progressBarContainer = document.getElementById('agonyz-progress-container');
    pageSpeedInformationContainer = document.getElementById('agonyz-page-speed-information');
    pageSpeedInformationTimestampContainer = document.getElementById('agonyz-page-speed-timestamp');

    progressBar = new ProgressBar.Circle('#progressBar', {
        color: '#aaa',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 4,
        trailWidth: 1,
        easing: 'easeInOut',
        duration: 1400,
        text: {
            autoStyleContainer: false
        },
        from: { color: '#aaa', width: 1 },
        to: { color: '#90EE90', width: 4 },
        // Set default step function for all animate calls
        step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);

            var value = Math.round(circle.value() * 100);
            if (value === 0) {
                circle.setText('0 %');
            } else {
                circle.setText(value.toString() + ' %');
            }

        }
    });
    progressBar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
    progressBar.text.style.fontSize = '2rem';

    getRequestProgress();

    makeRequestButton.addEventListener('click', function () {
        fetch(MAKE_REQUEST_URL)
            .then((response) => {
                if(pageSpeedInformationContainer !== null) {
                    pageSpeedInformationContainer.style.display = 'none';
                }
                progressBarContainer.style.display = 'block';
            });
        setTimeout(() => getRequestProgress(), 500);
    }, false);
},false);
