<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Blog</title>
</head>
<body>
    <div class="navbar">
        <div class="navbar-logo">
            <img src="resources/logo.svg" alt="logo" width="55" height="55">
            <span>Blog</span>
        </div>
        <div class="navbar-buttons-container">
        </div>
        <div class="navbar-logo navbar-user-logo">
            <img src="resources/user.svg" alt="user" onclick="navigate('login')" width="45" height="45">
        </div>
    </div>
    <div id="content"></div>
    <script src="scripts/script.js"></script>
</body>
</html>