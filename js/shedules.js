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