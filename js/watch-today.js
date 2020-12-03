function showWatchTodayModal(movieId) {
    let xhr = new XMLHttpRequest();

    xhr.open('get', 'watchToday/displayMovieModal/' + movieId);
    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 200) {
            let modalWrapper = document.getElementById('watch-today-modal');
            modalWrapper.innerHTML = xhr.response;

            let modal = modalWrapper.children[0];
            modal.style.display = 'block';
        }
    }
}

function closeWatchTodayModal() {
    document.getElementById('watch-today-modal').innerHTML = '';
}