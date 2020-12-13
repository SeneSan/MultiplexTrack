// most sold tickets chart
function ticketsSoldChart(currentData) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {ticketsSold(currentData)});
}

function ticketsSold(currentData) {

    let data = new google.visualization.DataTable();
    data.addColumn('string', 'Movies');
    data.addColumn('number', 'Nr of tickets');

    let arrayData = [];
    currentData.forEach(function (set) {
        arrayData.push([set[1], set[0]]);
    });

    data.addRows(arrayData);

    let options = {
        title: 'Most tickets sold',
        hAxis: {
            title: 'Movies'
        },
        vAxis: {
            title: 'Nr of tickets'
        },
        height: 400
    };

    let chart = new google.visualization.ColumnChart(
        document.getElementById('most-tickets-sold'));

    chart.draw(data, options);
}


// total amounts chart
function totalAmountChart(currentData) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {totalAmount(currentData)});
}

function totalAmount(currentData) {

    let data = new google.visualization.DataTable();
    data.addColumn('string', 'Movies');
    data.addColumn('number', 'Total amount');

    let arrayData = [];
    currentData.forEach(function (set) {
        arrayData.push([set[1], set[0]]);
    });

    data.addRows(arrayData);

    let options = {
        title: 'Total gross price',
        hAxis: {
            title: 'Movies'
        },
        vAxis: {
            title: 'Total amount'
        },
        height: 400
    };

    let chart = new google.visualization.ColumnChart(
        document.getElementById('total-amount'));

    chart.draw(data, options);
}

// weeks in theater chart
function weekInTheaterChart(currentData) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function () {weekInTheater(currentData)});
}

function weekInTheater(currentData) {

    let data = new google.visualization.DataTable();
    data.addColumn('string', 'Movies');
    data.addColumn('number', 'Weeks in the theater');

    let arrayData = [];
    currentData.forEach(function (set) {
        arrayData.push([set[1], set[0]]);
    });

    data.addRows(arrayData);

    let options = {
        title: 'Most of weeks in the theater',
        hAxis: {
            title: 'Movies'
        },
        vAxis: {
            title: 'Nr of weeks in the theater'
        },
        height: 400
    };

    let chart = new google.visualization.ColumnChart(
        document.getElementById('week-theater'));

    chart.draw(data, options);
}