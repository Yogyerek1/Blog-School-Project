const navbarButtons = [
    {
        text: "Kezdőlap",
        path: "/Blog-School-Project/index.php",
        selected: true
    },
    {
        text: "Rólunk",
        path: "/Blog-School-Project/index.php/aboutme",
        selected: false
    }
];

function changeNavBarButtonSelected (index) {
    navbarButtons.forEach(button => {
        button.selected = false;
    });

    navbarButtons[index].selected = true;

    renderNavbarButtons();
}

function renderNavbarButtons () {
    const navbarContainer = document.querySelector(".navbar-buttons-container");
    navbarContainer.innerHTML = "";
    
    navbarButtons.forEach((button, index) => {
        navbarContainer.innerHTML += `
            <div
                class="navbar-button ${button.selected ? "navbar-button-selected" : ""}"
                onclick="changeNavBarButtonSelected(${index})"
            >
            <a href="${button.path}">${button.text}</a></div>
        `;
    });
}

renderNavbarButtons();