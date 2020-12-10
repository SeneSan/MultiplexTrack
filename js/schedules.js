const OOPS_MESSAGE = 'Oops! Something went wrong.';

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
        getTimeSlots();
    };
}

function getTimeSlots() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'schedule/getCurrentTimeSlots');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 200 && xhr.response.error !== true) {

            if (xhr.response === OOPS_MESSAGE) {
                alert(xhr.response);
            } else {
                let parsed = JSON.parse(xhr.response);
                let timeSlots = parsed.timeSlots;

                timeSlots.forEach((timeSlot) => {
                    setOverlay(timeSlot);
                });
            }
        } else {
            alert( 'Error: ' + xhr.status);
        }
    }
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


function setOverlay(timeSlot) {
    let pageTimeSlot = document.getElementById(timeSlot['time_slot']);

    let overlay = document.createElement('div');
    overlay.className = 'overlay-scheduled-movie';
    overlay.setAttribute('start-date-time', timeSlot['start_date_time']);

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
        removeTimeSlot(overlay, timeSlot);
    };

    if (checkExceededTimeSlot(timeSlot['time_slot'], timeSlot['movie_duration'])) {
        let exceeded = checkExceededTimeSlot(timeSlot['time_slot'], timeSlot['movie_duration']);
        overlay.style.height = exceeded['cutDuration'] * 2 + 'px';
        overlay.className = 'overlay-scheduled-movie overlay-red';
        movieTitle.innerText = timeSlot['movie_title'] + '\nExceeded by ' + exceeded['exceededTime'] + ' min';
    } else {
        overlay.style.height = timeSlot['movie_duration'] * 2 + 'px';
        movieTitle.innerText = timeSlot['movie_title'];
    }

    overlay.appendChild(movieTitle);
    overlay.appendChild(removeButton);
    pageTimeSlot.appendChild(overlay);
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