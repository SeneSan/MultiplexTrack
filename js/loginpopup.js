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