function openForm() {

    if (document.getElementById("myLoginForm").style.display === 'block' ||
        document.getElementById("myRegistrationForm").style.display === 'block') {

        document.getElementById("myLoginForm").style.display = "none";
        document.getElementById("myRegistrationForm").style.display = "none";
    } else {
        document.getElementById("myLoginForm").style.display = "block";
    }
}

function openRegistrationForm() {
    document.getElementById("myRegistrationForm").style.display = "block";
    document.getElementById("myLoginForm").style.display = "none";
}

function openLoginForm() {
    document.getElementById("myRegistrationForm").style.display = "none";
    document.getElementById("myLoginForm").style.display = "block";
}

function login() {
    let xhr = new XMLHttpRequest();

    xhr.open('POST', 'user/login');

    let formData = new FormData();
    formData.append('username', document.querySelector('[name="username"]').value);
    formData.append('psw', document.querySelector('[name="psw"]').value);

    xhr.send(formData);

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert( 'Error: ' + xhr.status);
            return;
        }

        if (typeof xhr.response === "string" && xhr.response !== '') {
            alert(xhr.response);
        } else {
            window.location = '/';
        }
    }
}

function registerUser() {
    let xhr = new XMLHttpRequest();

    xhr.open('POST', 'user/register');

    let formData = new FormData();
    formData.append('reg_username', document.querySelector('[name="reg_username"]').value);
    formData.append('reg_psw', document.querySelector('[name="reg_psw"]').value);
    formData.append('reg_email', document.querySelector('[name="reg_email"]').value);
    formData.append('reg_phone', document.querySelector('[name="reg_phone"]').value);

    xhr.send(formData);

    xhr.onload = function () {
        if (xhr.status !== 200) {
            alert( 'Error: ' + xhr.status);
            return;
        }

        if (xhr.response === OOPS_MESSAGE) {
            alert(xhr.response);
        } else {
            alert(xhr.response);
            window.location = '/';
        }
    }
}