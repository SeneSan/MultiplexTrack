document.querySelectorAll('[id*="hour"]').forEach(function (element) {
    element.addEventListener('click', function () {
        if (!document.querySelector('[id="' + element.id + '"] .add-movie-grid')) {
            showAddMovieForm(element);
        }
    });

    element.addEventListener('mouseleave', function () {
        element
    });
});

function showAddMovieForm(element) {
    let addMovieForm = element.children[1];

    let addMovieGrid = document.createElement('div');
    addMovieGrid.className = 'add-movie-grid';

    let input = document.createElement('input');
    input.type = 'text';
    input.onkeypress = function () {
        alert('test');
        showDropdown(dropdown);
    };

    let button = document.createElement('button');
    button.innerText = '+';

    let dropdown = document.createElement('div');
    dropdown.className = 'add-movie-dropdown';

    addMovieGrid.appendChild(input);
    addMovieGrid.appendChild(button);

    addMovieForm.appendChild(addMovieGrid);
    addMovieForm.appendChild(dropdown);
}

function showDropdown(dropdown) {
    dropdown.innerText = 'Some test';
    dropdown.style.display = 'block';
}
