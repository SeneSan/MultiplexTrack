function getMovies() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'movie/getMovies');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert( 'Error: ' + xhr.status);
            return;
        }
        document.getElementById('main-content').innerHTML = xhr.response;
    }
}

function displayModal() {
    document.getElementById('add-movie-modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('add-movie-modal').style.display = 'none';

}