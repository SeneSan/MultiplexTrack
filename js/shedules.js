function getEmptySchedule() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'schedule/getView');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert( 'Error: ' + xhr.status);
            return;
        }
        document.getElementById('main-content').innerHTML = xhr.response;
    }
}

// add movie drop-down for scheduler
function showDropdown(id) {
    let dropdown = document.getElementById('add-movie-dropdown-' + id);
    dropdown.style.display = 'block';
}

function hideDropDownAfterClick() {
    let dropdowns = document.querySelectorAll('[id*="add-movie-dropdown-"]');
    dropdowns.forEach(function (element) {
        element.style.display = 'none';
    })
}

function getMovieByTitle(dropdownId) {

    let input = document.querySelector('[id=\"add-movie-schedule-container-' + dropdownId + '\"] input');

    let titleValue = input.value;
    let dropdown = document.getElementById('add-movie-dropdown-' + dropdownId);

    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'movie/getMovieByTitle/' + titleValue);
    xhr.send();

    xhr.onload = function () {
        dropdown.innerHTML = '';

        let response = JSON.parse(xhr.response);
        let movies = response['movies'];

        movies.forEach(function (movie) {
            let div = document.createElement('div');
            div.className = 'drop-down-item';
            div.onmousedown = function () {
                input.setAttribute('movie-id', movie['id']);
                input.value = movie['title'];
                input.setAttribute('movie-duration', movie['duration'])
            };
            div.onmouseup = function () {
                hideDropDownAfterClick();
            };

            let divTitle = document.createElement('div');
            divTitle.innerText = movie['title'];

            let divDuration = document.createElement('div');
            divDuration.innerText = movie['duration'] + ' min';

            dropdown.appendChild(div);
            div.appendChild(divTitle);
            div.appendChild(divDuration);
        })
    }
}

function scheduleMovie(timeSlot) {
    let addToTimeSlot = document.querySelector('[id=\"day-' + timeSlot + '\"]');
    let input = document.querySelector('[id=\"add-movie-schedule-container-day-' + timeSlot + '\"] input');
    let form = document.querySelector('[id=\"add-movie-schedule-container-day-' + timeSlot + '\"]');

    let nextTimeSlot = getTimeDuration(timeSlot, input.getAttribute('movie-duration'));

    if (input.getAttribute('movie-duration')) {
        let overlay = document.createElement('div');
        overlay.className = 'overlay-scheduled-movie';
        overlay.style.height = input.getAttribute('movie-duration') * 2 + 'px';

        // Add overlay movie title
        let movieTitle = document.createElement('div');
        movieTitle.innerText = input.value;
        movieTitle.className = 'overlay-movie-title';

        // Add overlay remove button
        let removeButton = document.createElement('div');
        removeButton.innerText = 'X';
        removeButton.style.paddingRight = '8.5px';
        removeButton.style.textAlign = 'right';
        removeButton.className = 'remove-overlay-button';
        removeButton.onclick = function () {
            overlay.remove();
            if (checkNextTimeSlot(nextTimeSlot)) {
                cloneTimeSlotForm(nextTimeSlot, timeSlot);
            } else {
                input.value = '';
                form.style.display = 'block';
            }
            displayPreviousRemoveButton(timeSlot.substr(0, 1));
        };

        overlay.appendChild(movieTitle);
        overlay.appendChild(removeButton);

        addToTimeSlot.appendChild(overlay);

        cloneTimeSlotForm(timeSlot, nextTimeSlot);

        onlyShowLastRemoveButton(timeSlot.substr(0, 1));
    }
}

function cloneTimeSlotForm(currentTimeSlot, nextTimeSlot) {

    let form = document.querySelector('[id=\"add-movie-schedule-container-day-' + currentTimeSlot + '\"]');

    if (document.querySelector('[id=\"day-' + nextTimeSlot + '\"]')) {

        let newForm = createTimeSlotForm(nextTimeSlot, form);

        form.remove();

        let nextTimeSlotParent = document.querySelector('[id=\"day-' + nextTimeSlot + '\"]');
        nextTimeSlotParent.appendChild(newForm);
    } else {
        form.style.display = 'none';
    }
}

function createTimeSlotForm(timeSlot, form) {
    let newForm = form.cloneNode(true);

    newForm.id = 'add-movie-schedule-container-day-' + timeSlot;
    newForm.children[0].children[0].setAttribute('onkeyup', "showDropdown('day-" + timeSlot + "'); getMovieByTitle('day-"+ timeSlot + "');");
    newForm.children[0].children[0].value = '';
    newForm.children[0].children[0].removeAttribute('movie-id');
    newForm.children[0].children[0].removeAttribute('movie-duration');
    newForm.children[0].children[1].setAttribute('onclick', "scheduleMovie('" + timeSlot + "');");
    newForm.children[1].id = 'add-movie-dropdown-day-' + timeSlot;

    return newForm;
}

function getTimeDuration(timeSlot, duration) {
    let day = timeSlot.substr(0, 1);
    let start = timeSlot.substr(7);
    start = start.split(':');

    let timeDuration = start[0] * 60 + parseInt(start[1]) + parseInt(duration);

    if (timeDuration % 60 <= 30 && timeDuration % 60 > 0) {
        return day + '-hour-' + Math.floor(timeDuration / 60) + ':30';
    } else if (timeDuration % 60 === 0) {
        return day + '-hour-' + Math.floor(timeDuration / 60) + ':00';
    } else if (timeDuration % 60 > 30) {
        return day + '-hour-' + Math.floor(timeDuration / 60 + 1) + ':00';
    }
}

function onlyShowLastRemoveButton(day) {
    let buttons = document.querySelectorAll('[id="day-' + day + '"] .overlay-scheduled-movie');
    for (let i = 0; i < buttons.length - 1; i++) {
        buttons[i].children[1].style.display = 'none';
    }
}

function displayPreviousRemoveButton(day) {
    if (document.querySelectorAll('[id="day-' + day + '"] .overlay-scheduled-movie')) {
        let buttons = document.querySelectorAll('[id="day-' + day + '"] .overlay-scheduled-movie');
        buttons[buttons.length - 1].children[1].style.display = 'block';
    }
}

function checkNextTimeSlot(nextTimeSlot) {
    if (document.querySelector('[id=\"day-' + nextTimeSlot + '\"]')) {
        return true;
    }
    return false;
}