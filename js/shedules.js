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

function hideDropDownOnBlur() {
    let dropdowns = document.querySelectorAll('[id*="add-movie-dropdown-"]');
    dropdowns.forEach(function (element) {
        element.style.display = 'none';
    })
}

function getMovieByTitle(dropdownId) {

    let title = document.querySelector('[id=\"add-movie-schedule-container-' + dropdownId + '\"] input').value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'movie/getMovieByTitle/' + title);
    xhr.send();

    xhr.onload = function () {
        var response = JSON.parse(xhr.response);
        console.log(response);
    }
}