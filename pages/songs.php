<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Kars' Songs</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Songs</h1>
    </main-element>

    <main-element>
        <?php
        $db = new SQLite3(__DIR__ . '/../db/kars.db');
        
        // Debug: Show table structure
        echo "<pre>";
        $tables = $db->query("SELECT sql FROM sqlite_master WHERE type='table';");
        while ($table = $tables->fetchArray()) {
            echo htmlspecialchars($table['sql']) . "\n\n";
        }
        echo "</pre>";

        $results = $db->query('SELECT songs.*, releases.type 
            FROM songs 
            LEFT JOIN releases ON songs.release_title = releases.release_title 
            ORDER BY songs.release_date ASC');

        echo '<table border="1">';
        echo '<tr><th>Song Title</th><th>Release Title</th><th>Duration</th><th>Player</th></tr>';
        
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            // Debug: Show raw row data
            echo "<!-- Debug: " . print_r($row, true) . " -->";
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['song_title'] ?? 'No Title') . '</td>';
            echo '<td>' . htmlspecialchars($row['release_title'] ?? 'No Release') . '</td>';
            echo '<td>' . htmlspecialchars($row['duration'] ?? '') . '</td>';
            echo '<td>';
            if (!empty($row['song_title']) && !empty($row['release_title'])) {
                echo '<div class="audio-player" 
                    data-song="' . htmlspecialchars($row['song_title']) . '"
                    data-release="' . htmlspecialchars($row['release_title']) . '">
                    <div class="audio-placeholder">Loading...</div>
                    </div>';
            } else {
                echo 'Missing song or release info';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        $db->close();
        ?>
    </main-element>
    <script src="../js/lazy_load.js"></script>
</body>
</html>