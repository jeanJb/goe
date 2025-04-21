const body = document.querySelector('html'),
    sidebar = body.querySelector('nav'),
    toggle = body.querySelector('.toggle'),
    searchBtn = body.querySelector('.search-box'),
    modeSwitch = body.querySelector('.toggle-switch'),
    modeText = body.querySelector('.mode-text');

toggle.addEventListener("click", () => {
    sidebar.classList.toggle('close');
})

/* searchBtn.addEventListener("click", () => {
    sidebar.classList.remove('close');
}) */

// Verifica si el modo oscuro está guardado en localStorage
if (localStorage.getItem('dark-mode') === 'enabled') {
    body.classList.add('dark');
    modeText.innerText = "Dark Mode";
}else {
    modeText.innerText = "Light Mode";
}

modeSwitch.addEventListener("click", () => {
    body.classList.toggle('dark');
    if (body.classList.contains('dark')) {
        localStorage.setItem('dark-mode', 'enabled');
        modeText.innerText = "Dark Mode";
        document.querySelector('.sun').style.display = "none";
        document.querySelector('.moon').style.display = "flex";
    } else {
        localStorage.setItem('dark-mode', 'disabled');
        modeText.innerText = "Light Mode";
        document.querySelector('.sun').style.display = "flex";
        document.querySelector('.moon').style.display = "none";
    }
});

