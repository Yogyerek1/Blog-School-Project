const routes = {
    "home": { text: "Kezdőlap", file: "home.php", showInNavbar: true },
    "aboutme": { text: "Rólunk", file: "aboutme.php", showInNavbar: true },
    "login": { text: "Belépés", file: "login.php", showInNavbar: false },
    "register": { text: "Regisztráció", file: "register.php", showInNavbar: false },
    "user": { text: "Fiók", file: "user.php", showInNavbar: false },
    "dashboard": { text: "Dashboard", file: "dashboard.php", showInNavbar: false },
};

function loadContent(path) {
    const route = routes[path] || routes["home"];

    fetch(`pages/${route.file}`)
        .then(response => {
            if (!response.ok) throw new Error("A fájl nem található");
            return response.text();
        })
        .then(html => {
            const contentDiv = document.querySelector("#content");
            contentDiv.innerHTML = html;

            contentDiv.querySelectorAll("script").forEach(oldScript => {
                const newScript = document.createElement("script");
                newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript);
                oldScript.remove();
            });

            updateNavbarUI(path);
        })
        .catch(err => {
            document.querySelector("#content").innerHTML = "<h2>404 - Hiba történt</h2>";
        });
}

function navigate(path) {
    window.location.hash = path;
    loadContent(path);
}

function renderNavbar() {
    const container = document.querySelector(".navbar-buttons-container");
    container.innerHTML = "";

    for (const path in routes) {
        const route = routes[path];

        if (route.showInNavbar) {
            const div = document.createElement("div");
            div.className = "navbar-button";
            div.innerHTML = `<a href="${path}" data-path="${path}" onclick="event.preventDefault(); navigate('${path}')">${route.text}</a>`;
            container.appendChild(div);
        }
    }
}

function updateNavbarUI(activePath) {
    document.querySelectorAll(".navbar-button").forEach(btn => {
        const linkPath = btn.querySelector("a").getAttribute("data-path");
        btn.classList.toggle("navbar-button-selected", linkPath === activePath);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    renderNavbar();

    window.onhashchange = () => {
        const path = window.location.hash.replace('#', '') || 'home';
        loadContent(path);
    };

    const path = window.location.hash.replace('#', '') || 'home';
    loadContent(path);
});