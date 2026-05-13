const navbarButtons = [
    {
        text: "Kezdőlap",
        file: "home.php",
        selected: true
    },
    {
        text: "Rólunk",
        file: "aboutme.php",
        selected: false
    }
];

function loadPage (pageFile) {
    fetch(`pages/${pageFile}`)
        .then(response => {
            if (!response.ok) throw new Error("Hiba a betöltéskor!");
            return response.text();
        })
        .then(data => {
            document.querySelector("#content").innerHTML = data;
        })
        .catch(err => {
            document.querySelector("#content").innerHTML = "Hiba: Az oldal nem található!";
        });
}

function changeNavBarButtonSelected (index) {
    navbarButtons.forEach(button => {
        button.selected = false;
    });

    navbarButtons[index].selected = true;

    renderNavbarButtons();

    loadPage(navbarButtons[index].file);
}

function renderNavbarButtons () {
    const navbarContainer = document.querySelector(".navbar-buttons-container");
    navbarContainer.innerHTML = "";
    
    navbarButtons.forEach((button, index) => {
        const div = document.createElement("div");
        div.className = `navbar-button ${button.selected ? "navbar-button-selected" : ""}`;

        div.innerHTML = `<a href="#" onclick="event.preventDefault();">${button.text}</a>`;
        div.onclick = () => changeNavBarButtonSelected(index);

        navbarContainer.appendChild(div);
    });
}

renderNavbarButtons();

loadPage("home.php");