<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/popup.css">
    <title>TEMPLATE</title>
    <?php include '../backend/reuse/nav.php'; ?>
</head>

<body class="main">
    <div class="main-element welcome">
        <h1>Roadmaps</h1>
        <p>Different project roadmaps listed below</p> (<a href="#" class="popup-button">Click me for the key</a>)
        <div class="slide-popup">
            <button class="close-popup">×</button>
            <h2>Key for development:</h2>
            <ul>
                <li>🟥 - Not Started yet</li>
                <li>🟧 - Planning</li>
                <li>🟨 - Developing</li>
                <li>🟩 - Done</li>
                <li>🟦 - Done, but being reworked</li>
            </ul>
        </div>
    </div>

    <div class="main-element">
        <h2>/Kars</h2>
        <p>Soon</p>
    </div>

    <div class="main-element">
        <h1>/Mr-Kitty-Archive</h1>
            <h2>Discography</h2>
            <ul>
                <li>🟩 Main Songs </li>
                <li>🟨 Pre-2010 </li>
                <li>🟧 Singles / Remixes / Demos / Etc...</li>

                <li>🟧 Album Info</li>
                <li>🟨 Lyrics</li>
            </ul>

            <h2>Updates:</h2>
            <ul>
                <li>🟩 Functionality</li>
                <li>🟧 Display</li>
                <li>🟥 Automated pulling of mk posts from insta, twitter, etc...</li>
                
            </ul>
            <p>Currently shows updates for the subreddit, website, and mk posts</p>

            <h2>Gallery:</h2>
            <ul>
                <li>🟩 Display of images / videos</li>
                <li>🟥 Functionality of pulling all from external source</li>
                <li>🟥 Functionality for live shows and other things</li>
            </ul>


            <h2>Other:</h2>
            <ul>
                <li>🟩 Daily Song</li>
                <li>🟧 Site CSS</li>
            </ul>

            Last update:
            20/03/25
    </div>

<script src="../js/slide-popup.js"></script>
</body>
</html>