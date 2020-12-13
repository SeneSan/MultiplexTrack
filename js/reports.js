function getReportsPage() {
    let ticketsSold = document.createElement('div');
    ticketsSold.id = 'most-tickets-sold';

    let totalAmount = document.createElement('div');
    totalAmount.id = 'total-amount';

    let weekTheater = document.createElement('div');
    weekTheater.id = 'week-theater';

    let script = document.createElement('script');
    script.src ='js/charts.js';

    let mainContent = document.getElementById('main-content');
    mainContent.innerHTML = '';
    mainContent.appendChild(script);
    mainContent.appendChild(ticketsSold);
    mainContent.appendChild(totalAmount);
    mainContent.appendChild(weekTheater);

    getTicketsSold();
    getTotalAmounts();
    getWeekInTheater();
}

function getTicketsSold() {
    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'reports/getTicketsSold');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert('Error: ' + xhr.status);
        } else {
            let response = JSON.parse(xhr.response);
            ticketsSoldChart(response);
        }
    }
}

function getTotalAmounts() {
    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'reports/getTotalAmounts');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert('Error: ' + xhr.status);
        } else {
            let response = JSON.parse(xhr.response);
            totalAmountChart(response);
        }
    }
}

function getWeekInTheater() {
    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'reports/getWeeksInTheater');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert('Error: ' + xhr.status);
        } else {
            let response = JSON.parse(xhr.response);
            weekInTheaterChart(response);
        }
    }
}
