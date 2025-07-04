<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <link rel="stylesheet" href="../css/discography.css">
    <title>Kars' Discography</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Discography</h1>
    </main-element>

    <main-element class="filters">
    </main-element>

    <main-element discography>
        <div class="timeline-container">
            <?php
            $db = new SQLite3(__DIR__ . '/../db/kars.db');
            $results = $db->query('SELECT * FROM releases ORDER BY release_date ASC');
            echo '<div class="timeline">';
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $titleEncoded = urlencode($row['release_title']);
                echo '<a href="/pages/songs.php?album=' . $titleEncoded . '" class="timeline-item" data-album="' . htmlspecialchars($row['release_title']) . '" data-type="' . htmlspecialchars($row['type']) . '">';
                echo '<div class="timeline-content">';
                echo '<div class="timeline-date">' . date('Y', strtotime($row['release_date'])) . '</div>';
                echo '<h3>' . htmlspecialchars($row['release_title']) . '</h3>';
                echo '<div class="release-date">' . date('F j, Y', strtotime($row['release_date'])) . '</div>';
                echo '<div class="release-type">' . htmlspecialchars($row['type']) . '</div>';
                echo '</div>';
                echo '</a>';
            }
            echo '</div>';
            $db->close();
            ?>
        </div>
    </main-element>
    <script>
        document.querySelectorAll('.timeline-item').forEach(item => {
            const album = item.dataset.album;
            const type = item.dataset.type;
            const imagePath = `../assets/covers/${type}/${album}.png`;
            item.style.backgroundImage = `url('${imagePath}')`;
        });
    </script>
</body>
</html>