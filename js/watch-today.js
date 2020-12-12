function showWatchTodayModal(movieId) {
    let xhr = new XMLHttpRequest();

    xhr.open('get', 'watchToday/displayMovieModal/' + movieId);
    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.response === OOPS_MESSAGE){
                alert(xhr.response);
            } else {
                let modalWrapper = document.getElementById('watch-today-modal');
                modalWrapper.innerHTML = xhr.response;

                let modal = modalWrapper.children[0];
                modal.style.display = 'block';
            }
        } else {
            alert('Error: ' + xhr.status);
        }
    }
}

function closeWatchTodayModal() {
    document.getElementById('watch-today-modal').innerHTML = '';
}

function selectHour(element) {

    let hour = element.getAttribute('hour');
    hour = hour.replace(':', '-');
    let screenId = element.parentElement.children[0].getAttribute('screen');


    let xhr = new XMLHttpRequest();

    let path = 'watchToday/getSeats/' + hour + '/' + screenId;
    xhr.open('GET', path);
    xhr.send();

    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.response !== OOPS_MESSAGE) {

                let response = JSON.parse(xhr.response);

                let section = document.getElementById('seats-selection-section');
                section.innerHTML = response['layout'];

                if (typeof response['existing_seats'] !== 'string') {
                    response['existing_seats'].forEach(function (value, key) {
                        document.getElementById(value['seat_nr']).className = 'seat sold';
                    });
                }

                document.querySelectorAll('.seat:not(.sold)').forEach(function (element) {
                    element.addEventListener('click', function () {
                        if (element.className === 'seat free') {
                            element.className = 'seat selected';
                        } else if (element.className === 'seat selected') {
                            element.className = 'seat free';
                        }

                        let totalPrice = document.getElementById('total-price');
                        let singlePrice = totalPrice.getAttribute('initial-price');
                        let nrSelectedSeats = document.querySelectorAll('.seat.selected').length;

                        totalPrice.innerText = (singlePrice * nrSelectedSeats).toFixed(2);
                    });
                });

                let hours = document.querySelectorAll('.watch-today-hours');
                hours.forEach(function (element) {
                    element.removeAttribute('selected');
                });

                element.setAttribute('selected', '');

            } else {
                alert(xhr.response);
            }

        } else {
            alert('Error: ' + xhr.status);
        }
    }
}

function confirmPurchase() {
    let selectedHour = document.querySelectorAll('[selected]');

    if (selectedHour.length === 0) {
        alert('Please select an hour for the movie!');
    } else if (document.querySelectorAll('.seat.selected ').length === 0) {
        alert('Please select at least one seat!');
    } else {

        let date = document.getElementById('movie-title').getAttribute('date');
        let hour = document.querySelector('[selected].watch-today-hours').getAttribute('hour');

        let seats = '';
        document.querySelectorAll('.seat.selected').forEach(function (seat) {
            seats = seats + seat.id + ', ';
        });

        let singlePrice = document.getElementById('total-price').getAttribute('initial-price');
        let totalPrice = document.getElementById('total-price').innerText;

        let xhr = new XMLHttpRequest();

        let formData = new FormData();
        formData.append('start_date_time', date + ' ' + hour + ':00');
        formData.append('seats', seats);
        formData.append('single_price', singlePrice);
        formData.append('total_price', totalPrice);

        xhr.open('POST', '/watchToday/sellTickets');
        xhr.send(formData);

        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.response === OOPS_MESSAGE) {
                    alert(xhr.response);
                } else {
                    let parent = document.getElementById('watch-today-info');
                    parent.innerHTML = '';

                    let confirmed = document.createElement('div');
                    confirmed.className = 'confirmed-message';
                    confirmed.innerText = 'Purchase successfully processed!';

                    let pdf = document.createElement('div');
                    pdf.innerHTML = xhr.response;

                    parent.appendChild(confirmed);
                    parent.appendChild(document.createElement('br'));
                    parent.appendChild(pdf);
                }
            } else {
                alert('Error: ' + xhr.status)
            }
        }
    }
}