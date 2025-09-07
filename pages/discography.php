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
        <label>
            <input type="checkbox" id="show-main-releases" checked>
            Show Main Releases Only
        </label>
        <input type="text" id="search-releases" placeholder="Search releases...">
    </main-element>

    <main-element discography>
        <div class="timeline-container">
            <?php
            $db = new SQLite3(__DIR__ . '/../db/kars.db');
            $results = $db->query('SELECT * FROM releases ORDER BY release_date ASC');
            echo '<div class="timeline">';
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $titleEncoded = urlencode($row['release_title']);
                $releaseDate = $row['release_date'];
                if ($releaseDate === "N/A") {
                    $year = "????";
                    $fullDate = "Future";
                } else {
                    $year = date('Y', strtotime($releaseDate));
                    $fullDate = date('F j, Y', strtotime($releaseDate));
                }
                $mainRelease = isset($row['main_release']) ? strtolower(trim($row['main_release'])) : 'true';
                echo '<a href="/pages/songs.php?album=' . $titleEncoded . '" class="timeline-item" 
                    data-album="' . htmlspecialchars($row['release_title']) . '" 
                    data-type="' . htmlspecialchars($row['type']) . '"
                    data-main-release="' . htmlspecialchars($mainRelease) . '">';
                echo '<div class="timeline-content">';
                echo '<div class="timeline-date">' . $year . '</div>';
                echo '<h3>' . htmlspecialchars($row['release_title']) . '</h3>';
                echo '<div class="release-date">' . $fullDate . '</div>';
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
        // Image loading
        document.querySelectorAll('.timeline-item').forEach(item => {
            let album = item.dataset.album
                .replace(/[^a-zA-Z0-9 ]/g, '')
                .replace(/ /g, '_');
            const type = item.dataset.type;
            const imagePath = `../assets/covers/${album}.png`;
            item.style.backgroundImage = `url('${imagePath}')`;
        });

        // Filtering logic
        const mainReleaseCheckbox = document.getElementById('show-main-releases');
        const searchInput = document.getElementById('search-releases');

        function filterReleases() {
            const showMainOnly = mainReleaseCheckbox.checked;
            const searchTerm = searchInput.value.toLowerCase();

            document.querySelectorAll('.timeline-item').forEach(item => {
                const isMain = item.dataset.mainRelease === 'true';
                const title = item.dataset.album.toLowerCase();
                
                let visible = true;
                if (showMainOnly && !isMain) visible = false;
                if (searchTerm && !title.includes(searchTerm)) visible = false;
                
                item.style.display = visible ? '' : 'none';
            });
        }

        mainReleaseCheckbox.addEventListener('change', filterReleases);
        searchInput.addEventListener('input', filterReleases);
    </script>
</body>
</html>