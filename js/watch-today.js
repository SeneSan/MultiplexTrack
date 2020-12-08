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

function selectHour(element) {
    let hours = document.querySelectorAll('.watch-today-hours');
    hours.forEach(function (element) {
       element.removeAttribute('selected');
    });

    element.setAttribute('selected', '');
}

function changePrice(input) {
    let totalPrice = document.getElementById('total-price');
    let initialPrice = totalPrice.getAttribute('initial-price');
    totalPrice.innerText = (input.value * initialPrice).toFixed(2);
}

function confirmPurchase() {
    let selectedHour = document.querySelectorAll('[selected]');

    if (selectedHour.length == 0) {
        alert('Please select an hour for the movie!');
    }

    let date = document.getElementById('movie-title').getAttribute('date');
    let hour = document.querySelector('[selected].watch-today-hours').getAttribute('hour');
    let nrSeats = document.getElementById('movie-seats').value;
    let totalPrice = document.getElementById('total-price').innerText;

    let xhr = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('start_date_time', date + ' ' + hour + ':00');
    formData.append('nr_seats', nrSeats);
    formData.append('total_price', totalPrice);

    xhr.open('POST', '/watchToday/sellTickets');
    xhr.send(formData);

    xhr.onload = function () {
        if (xhr.status === 200) {
            let parent = document.getElementsByClassName('watch-today-modal-content')[0];
            let pdf = document.createElement('div');
            pdf.innerHTML = xhr.response;
            parent.appendChild(pdf);
        }
    }
}