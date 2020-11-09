function addMovie() {
    // define URL and for element
    const url = "/movie/addMovie";

    const title = document.getElementById('title').value;
    const year = document.getElementById('year').value ;
    const type = document.getElementById('movie-type').value;
    const duration = document.getElementById('duration').value;
    const categories = document.getElementById('categories').value;
    const poster = document.getElementById('poster').files;
    const description = document.getElementById('description').value;

    var formData = new FormData();
    formData.append('title', title);
    formData.append('year', year);
    formData.append('type', type);
    formData.append('duration', duration);
    formData.append('categories', categories);
    formData.append('poster', poster[0]);
    formData.append('description', description);

    const form = document.querySelector('form');

    // add event listener
    form.addEventListener('submit', e => {

        // disable default action
        e.preventDefault();
    });

    const xhr = new XMLHttpRequest();
    // xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // create and send the request
    xhr.open('POST', url);
    xhr.send(formData);

    xhr.onload = function () {
        var response = JSON.parse(xhr.response);
        var div = document.getElementById('add-movie-modal-message');
        div.innerText = response['message'];
        div.style.display = 'block';

        if (response['error'] == 'true') {
            div.style.backgroundColor = 'red';
        } else {
            div.style.backgroundColor = 'lightgreen';
        }
    }
}
