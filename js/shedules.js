function getEmptySchedule() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'schedule/getView');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert( 'Error: ' + xhr.status);
            return;
        }
        var response = xhr.response;
        document.getElementById('main-content').innerHTML = response;
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
    let form = document.querySelector('[id=\"add-movie-schedule-container-day-' + timeSlot + '\"]');
    let input = document.querySelector('[id=\"add-movie-schedule-container-day-' + timeSlot + '\"] input');

    if (input.getAttribute('movie-duration')) {
        let overlay = document.createElement('div');
        overlay.className = 'overlay-scheduled-movie';
        overlay.style.height = input.getAttribute('movie-duration') * 2 + 'px';
        overlay.style.lineHeight = input.getAttribute('movie-duration') * 2 + 'px';
        overlay.innerText = input.value;
        form.style.display = 'none';

        addToTimeSlot.appendChild(overlay);

        cloneTimeSlotForm(timeSlot, input.getAttribute('movie-duration'));
    }
}

function cloneTimeSlotForm(timeSlot, duration) {
    let nextTimeSlot = getTimeDuration(timeSlot, duration);

    let form = document.querySelector('[id=\"add-movie-schedule-container-day-' + timeSlot + '\"]');
    let newForm = form.cloneNode(true);
    newForm.id = 'add-movie-schedule-container-day-' + nextTimeSlot;
    newForm.children[0].children[0].setAttribute('onkeyup', "showDropdown('day-" + nextTimeSlot + "'); getMovieByTitle('day-"+ nextTimeSlot + "');");
    newForm.children[0].children[0].value = '';
    newForm.children[0].children[0].removeAttribute('movie-id');
    newForm.children[0].children[0].removeAttribute('movie-duration');
    newForm.children[0].children[1].setAttribute('onclick', "scheduleMovie('" + nextTimeSlot + "');");
    newForm.children[1].id = 'add-movie-dropdown-day-' + nextTimeSlot;
    newForm.style.display = 'block';

    let nextTimeSlotParent = document.querySelector('[id=\"day-' + nextTimeSlot + '\"]');
    nextTimeSlotParent.appendChild(newForm);
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