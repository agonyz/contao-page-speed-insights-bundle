document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete').forEach(function(button) {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            let confirmation = confirm("Do you want to remove the request with the id " + button.dataset.id);

            if(confirmation) {
                fetch(button.href)
                    .then((response) => {
                        location.reload();
                    });
            }
        })
    });
},false);
