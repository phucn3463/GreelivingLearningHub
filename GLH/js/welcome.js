const signUpButton = document.getElementById('vu14');
const signInButton = document.getElementById('vu12');
const main = document.getElementById('vu15');

signUpButton.addEventListener('click', () => {
    main.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    main.classList.remove("right-panel-active");
});