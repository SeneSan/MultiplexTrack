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
        let js = document.createElement('script');
        js.src = 'js/newSchedules.js';
        document.body.appendChild(js);
    };
    // window.sessionStorage.clear();
}

// add movie drop-down for scheduler
function showDropdown(id) {
    let dropdown = document.getElementById(id);
    dropdown.style.display = 'block';
}

function hideDropDownAfterClick() {
    let dropdowns = document.querySelectorAll('[id*="add-movie-dropdown-"]');
    dropdowns.forEach(function (element) {
        element.style.display = 'none';
    })
}

function getMovieByTitle(dropdownId, screen) {

    let input = document.querySelector('[id=\"screen-' + screen + '-add-movie-schedule-container-' + dropdownId + '\"] input');

    let titleValue = input.value;
    let dropdown = document.querySelector('[id=\"screen-' + screen + '-add-movie-dropdown-' + dropdownId + '\"]');

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

function scheduleMovie(timeSlot, screen) {
    let addToTimeSlot = document.querySelector('[id=\"screen-' + screen + '-day-' + timeSlot + '\"]');
    let input = document.querySelector('[id=\"screen-' + screen + '-add-movie-schedule-container-day-' + timeSlot + '\"] input');
    let form = document.querySelector('[id=\"screen-' + screen + '-add-movie-schedule-container-day-' + timeSlot + '\"]');

    let nextTimeSlot = getTimeDuration(timeSlot, input.getAttribute('movie-duration'));

    if (input.getAttribute('movie-duration')) {
        if (typeof nextTimeSlot == 'string' && nextTimeSlot.substr(2, 4) === 'hour') {
            createOverlay(input, nextTimeSlot, timeSlot, addToTimeSlot, form, screen);
        } else {
            let message = 'The movie ' + input.value + ' will exceed the closing times by ' + nextTimeSlot.exceededTime + ' minutes. Confirm you still schedule the movie?';
            if (confirm(message)) {
                createOverlay(input, nextTimeSlot, timeSlot, addToTimeSlot, form, screen, true);
            }
        }
    }
}

function createOverlay(input, nextTimeSlot, timeSlot, addToTimeSlot, form, screen, exceeded = false ) {
    let overlay = document.createElement('div');
    overlay.className = 'overlay-scheduled-movie';

    // Add overlay movie title
    let movieTitle = document.createElement('div');
    movieTitle.className = 'overlay-movie-title';

    // Add overlay remove button
    let removeButton = document.createElement('div');
    removeButton.innerText = 'X';
    removeButton.style.paddingRight = '8.5px';
    removeButton.style.textAlign = 'right';
    removeButton.className = 'remove-overlay-button';
    removeButton.onmousedown = function () {
        overlay.remove();
        if (checkNextTimeSlot(nextTimeSlot, screen)) {
            cloneTimeSlotForm(nextTimeSlot, timeSlot, screen, true);
        } else {
            input.value = '';
            form.style.display = 'block';
            deleteLastTimeSlot(form);
        }
        displayPreviousRemoveButton(timeSlot.substr(0, 1), screen);
    };

    if (exceeded) {
        overlay.style.backgroundColor = 'red';
        overlay.style.height = nextTimeSlot.cutDuration * 2 + 'px';
        movieTitle.innerText = input.value + '\n Exceeded by ' + nextTimeSlot.exceededTime + ' min';
    } else {
        overlay.style.height = input.getAttribute('movie-duration') * 2 + 'px';
        movieTitle.innerText = input.value;
    }

    overlay.appendChild(movieTitle);
    overlay.appendChild(removeButton);

    addToTimeSlot.appendChild(overlay);

    setTimeSlot(input);

    cloneTimeSlotForm(timeSlot, nextTimeSlot, screen);
    onlyShowLastRemoveButton(timeSlot.substr(0, 1), screen);
}

function cloneTimeSlotForm(currentTimeSlot, nextTimeSlot, screen, remove = false) {

    let form = document.querySelector('[id=\"screen-' + screen + '-add-movie-schedule-container-day-' + currentTimeSlot + '\"]');

    if (document.querySelector('[id=\"screen-' + screen + '-day-' + nextTimeSlot + '\"]')) {

        let newForm = createTimeSlotForm(nextTimeSlot, form, screen);

        form.remove();

        let nextTimeSlotParent = document.querySelector('[id=\"screen-' + screen + '-day-' + nextTimeSlot + '\"]');
        nextTimeSlotParent.appendChild(newForm);
    } else {
        form.style.display = 'none';
    }

    if (remove) {
        deleteTimeSlot('screen-' + screen + '-day-' + nextTimeSlot);
    }
}

function createTimeSlotForm(timeSlot, form, screen) {
    let newForm = form.cloneNode(true);

    newForm.id = 'screen-' + screen + '-add-movie-schedule-container-day-' + timeSlot;
    newForm.children[0].children[0].setAttribute('onkeyup', "showDropdown('screen-" + screen + "-add-movie-dropdown-day-" + timeSlot + "'); getMovieByTitle('day-"+ timeSlot + "', '" + screen + "');");
    newForm.children[0].children[0].value = '';
    newForm.children[0].children[0].removeAttribute('movie-id');
    newForm.children[0].children[0].removeAttribute('movie-duration');
    newForm.children[0].children[1].setAttribute('onclick', "scheduleMovie('" + timeSlot + "', '" + screen + "');");
    newForm.children[1].id = 'screen-' + screen + '-add-movie-dropdown-day-' + timeSlot;

    return newForm;
}

function getTimeDuration(timeSlot, duration) {
    let day = timeSlot.substr(0, 1);
    let start = timeSlot.substr(7);
    start = start.split(':');

    let startDuration = start[0] * 60 + parseInt(start[1]);
    let timeDuration = start[0] * 60 + parseInt(start[1]) + parseInt(duration);
    let maxTime = 18 * 60;

    if (timeDuration < maxTime) {

        if (timeDuration % 60 <= 30 && timeDuration % 60 > 0) {
            return day + '-hour-' + Math.floor(timeDuration / 60) + ':30';
        } else if (timeDuration % 60 === 0) {
            return day + '-hour-' + Math.floor(timeDuration / 60) + ':00';
        } else if (timeDuration % 60 > 30) {
            return day + '-hour-' + Math.floor(timeDuration / 60 + 1) + ':00';
        }
    }
    return {
        'exceededTime': (timeDuration - maxTime),
        'cutDuration': (maxTime - startDuration)
    }
}

function onlyShowLastRemoveButton(day, screen) {
    let buttons = document.querySelectorAll('[id="screen-' + screen + '-day-' + day + '"] .overlay-scheduled-movie');
    for (let i = 0; i < buttons.length - 1; i++) {
        buttons[i].children[1].style.display = 'none';
    }
}

function displayPreviousRemoveButton(day, screen) {
    if (document.querySelectorAll('[id="screen-' + screen + '-day-' + day + '"] .overlay-scheduled-movie')) {
        let buttons = document.querySelectorAll('[id="screen-' + screen + '-day-' + day + '"] .overlay-scheduled-movie');
        if (buttons[buttons.length - 1]) {
            buttons[buttons.length - 1].children[1].style.display = 'block';
        }
    }
}

function checkNextTimeSlot(nextTimeSlot, screen) {
    if (document.querySelector('[id=\"screen-' + screen + '-day-' + nextTimeSlot + '\"]')) {
        return true;
    }
    return false;
}

function showSchedule(screenID) {
    document.querySelectorAll('[id*="schedule-screen"]').forEach((element) => {
        element.style.display = 'none';
    });

    document.querySelectorAll('[id*="screen_"]').forEach((element) => {
        element.style.backgroundColor = '#555';
    });
    document.querySelector('#schedule-screen-'+ screenID).style.display = 'block';
    document.querySelector('#screen_'+ screenID).style.backgroundColor = 'cornflowerblue';
}

function setTimeSlot(input) {
    let schedules = window.sessionStorage.getItem('schedules') ? window.sessionStorage.getItem('schedules') : {};
    if (window.sessionStorage.getItem('schedules')) {
        window.sessionStorage.clear();
    }

    let parsed = typeof schedules === 'string' ? JSON.parse(schedules) : {};

    let details = input.parentNode.parentNode.parentNode.id;
    let screen = details.substr(7, 1);
    let day = details.substr(13, 1);
    let hour = details.substr(20);

    let timeSlot = {};
    timeSlot['screen'] = screen;
    timeSlot['day'] = day;
    timeSlot['hour'] = hour;
    timeSlot['movie_id'] = input.getAttribute('movie-id');

    parsed[details] = timeSlot;
    let json = JSON.stringify(parsed);

    window.sessionStorage.setItem('schedules', json);
}

function deleteTimeSlot(details) {
    let schedules = window.sessionStorage.getItem('schedules');
    if (window.sessionStorage.getItem('schedules')) {
        window.sessionStorage.clear();
    }

    let parsed = JSON.parse(schedules);

    delete parsed[details];

    let json = JSON.stringify(parsed);

    window.sessionStorage.setItem('schedules', json);
}

function deleteLastTimeSlot(form) {
    let schedules = window.sessionStorage.getItem('schedules');
    if (window.sessionStorage.getItem('schedules')) {
        window.sessionStorage.clear();
    }

    let parsed = JSON.parse(schedules);

    let details = form.parentNode.id;
    delete parsed[details];

    let json = JSON.stringify(parsed);

    window.sessionStorage.setItem('schedules', json);
}

function publishSchedules() {
    if (confirm('Are you sure you want to publish this schedules?')) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/schedule/publish');

        let form = new FormData();
        form.append('schedules', window.sessionStorage.getItem('schedules'));

        xhr.send(form);

        xhr.onload = function () {
            if (xhr.status !== 200) {
                alert( 'Error: ' + xhr.status);
                return;
            }
            alert(xhr.response);
            window.location = '/';
        };

    }
}