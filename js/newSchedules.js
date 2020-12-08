document.querySelectorAll('[id*="hour"]').forEach(function (element) {
    element.addEventListener('click', function () {
        if (!document.querySelector('[id="' + element.id + '"] .add-movie-grid')) {
            showAddMovieForm(element);
        }
    });
});

function showAddMovieForm(element) {
    let addMovieForm = element.children[1];

    let addMovieGrid = document.createElement('div');
    addMovieGrid.className = 'add-movie-grid';

    let input = document.createElement('input');
    input.type = 'text';
    input.onkeypress = function () {
        getMovieByTitle(input, dropdown);
        showDropdown(dropdown);
    };

    let button = document.createElement('button');
    button.innerText = '+';
    button.onclick = function () {
        scheduleMovie(input);
    };

    let dropdown = document.createElement('div');
    dropdown.className = 'add-movie-dropdown';

    addMovieGrid.appendChild(input);
    addMovieGrid.appendChild(button);

    addMovieForm.appendChild(addMovieGrid);
    addMovieForm.appendChild(dropdown);
}

function showDropdown(dropdown) {
    dropdown.innerText = 'Some test';
    dropdown.style.display = 'block';
}

function getMovieByTitle(input, dropdown) {
    let titleValue = input.value;
    let timeSlot = input.parentNode.parentNode.parentNode;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'movie/getMovieByTitle/' + titleValue + '/' + timeSlot.id.replace(':', '-'));
    xhr.send();

    xhr.onload = function () {
        dropdown.innerHTML = '';

        let response = JSON.parse(xhr.response);
        let movies = response['movies'];
        let invalidMovies = response['invalid-movies'];

        movies.forEach(function (movie) {
            let divMovie = document.createElement('div');
            divMovie.className = 'drop-down-item';
            divMovie.onmousedown = function () {
                input.setAttribute('movie-id', movie['id']);
                input.value = movie['title'];
                input.setAttribute('movie-duration', movie['duration'])
            };
            divMovie.onmouseup = function () {
                hideDropDownAfterClick(dropdown);
            };

            let divTitle = document.createElement('div');
            divTitle.innerText = movie['title'];

            let divDuration = document.createElement('div');
            divDuration.innerText = movie['duration'] + ' min';

            divMovie.appendChild(divTitle);
            divMovie.appendChild(divDuration);
            dropdown.appendChild(divMovie);
        });

        invalidMovies.forEach(function (invalidMovie) {
            let divInvalidMovie = document.createElement('div');
            divInvalidMovie.className = 'drop-down-item invalid-movie';

            let divInvalidTitle = document.createElement('div');
            divInvalidTitle.innerText = invalidMovie['title'];

            let divInvalidDuration = document.createElement('div');
            divInvalidDuration.innerText = invalidMovie['duration'] + ' min';

            divInvalidMovie.appendChild(divInvalidTitle);
            divInvalidMovie.appendChild(divInvalidDuration);
            dropdown.appendChild(divInvalidMovie);
        });
    }
}

function hideDropDownAfterClick(dropdown) {
    dropdown.style.display = 'none';
}

function scheduleMovie(input) {
    let movieId = input.getAttribute('movie-id');
    let timeSlot = input.parentNode.parentNode.parentNode;

    if (input.value && movieId) {
        let xhr = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('movie_id', movieId);
        formData.append('time_slot', timeSlot.id);

        xhr.open('POST', 'schedule/publish');
        xhr.send(formData);

        xhr.onload = function () {
            if (xhr.status === 200 && xhr.response.error !== true) {
                let response = JSON.parse(xhr.response);

                createOverlay(timeSlot, response['start_date_time']['start_date_time']);
                timeSlot.children[1].remove();
            }
        }
    }
}

function createOverlay(timeSlot, startDateTime) {
    let input = timeSlot.children[1].children[0].children[0];

    let overlay = document.createElement('div');
    overlay.className = 'overlay-scheduled-movie';
    overlay.setAttribute('start-date-time', startDateTime);

    // Add overlay movie title
    let movieTitle = document.createElement('div');
    movieTitle.className = 'overlay-movie-title';

    // Add overlay remove button
    let removeButton = document.createElement('div');
    removeButton.innerText = 'X';
    removeButton.style.paddingRight = '8.5px';
    removeButton.style.textAlign = 'right';
    removeButton.className = 'remove-overlay-button';
    removeButton.onclick = function () {
        removeTimeSlot(overlay);
    };

    if (checkExceededTimeSlot(timeSlot.id, input.getAttribute('movie-duration'))) {
        let exceeded = checkExceededTimeSlot(timeSlot.id, input.getAttribute('movie-duration'));
        overlay.style.height = exceeded['cutDuration'] * 2 + 'px';
        overlay.className = 'overlay-scheduled-movie overlay-red';
        movieTitle.innerText = input.value + '\nExceeded by ' + exceeded['exceededTime'] + ' min';
    } else {
        overlay.style.height = input.getAttribute('movie-duration') * 2 + 'px';
        movieTitle.innerText = input.value;
    }

    overlay.appendChild(movieTitle);
    overlay.appendChild(removeButton);
    timeSlot.appendChild(overlay);
}

function removeTimeSlot(overlay) {
    let xhr = new XMLHttpRequest();

    let formData = new FormData;
    formData.append('remove_date_time', overlay.getAttribute('start-date-time'));

    xhr.open('POST', 'schedule/removeTimeSlot');
    xhr.send(formData);

    xhr.onload = function () {
        if (xhr.status === 200 && xhr.response.error !== true) {
            overlay.remove();
        }
    }
}

function checkExceededTimeSlot(timeSlot, duration) {

    let start = timeSlot.substr(20);
    start = start.split(':');

    let startDuration = start[0] * 60 + parseInt(start[1]);
    let timeDuration = start[0] * 60 + parseInt(start[1]) + parseInt(duration);
    let maxTime = 18 * 60;

    if (timeDuration < maxTime) {
        return false;
    }
    return {
        'exceededTime': (timeDuration - maxTime),
        'cutDuration': (maxTime - startDuration)
    }
}

