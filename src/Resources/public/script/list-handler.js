document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete').forEach(function(button) {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            let deleteMessage = 'Möchten Sie den Request mit der id ' + button.dataset.id + ' löschen?';
            if(document.getElementById('get-locale').dataset.locale !== 'de') {
                deleteMessage = 'Do you want to remove the request with the id ' + button.dataset.id + '?';
            }
            let confirmation = confirm(deleteMessage);

            if(confirmation) {
                fetch(button.href)
                    .then((response) => {
                        location.reload();
                    });
            }
        })
    });
},false);
