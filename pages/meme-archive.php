<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/index-img.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/meme-archive.css">
    <link rel="stylesheet" href="../css/nav.css">
    <title>TEMPLATE</title>
    <?php include '../backend/reuse/nav.php'; ?>
</head>

<body class="main">
    <div class="main-element">
        <h1>Welcome to my meme archive</h1>
        <p>Very new right now, more to come</p>
    </div>

    <div id="gallery">
        <?php
            $folder = "../assets/archive/";
            $files = scandir($folder);

            foreach ($files as $file) {
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    echo "<img src='$folder/$file' style='width:200px; margin:5px;'>";
                } elseif (preg_match('/\.(mp4|webm)$/i', $file)) {
                    echo "<video controls width='300' style='margin:5px;'><source src='$folder/$file' type='video/mp4'>Your browser does not support the video tag.</video>";
                }
            }
        ?>
    </div>

</body>
</html>