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
        <form method="get" style="margin-bottom:1em;">
            <input type="text" name="q" placeholder="Search title, album, etc..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit">Search</button>
            <div style="margin-top:0.5em;">
                <?php
                $allColumns = [
                    'id' => 'ID',
                    'song_title' => 'Title',
                    'duration' => 'Duration',
                    'lyrics' => 'Lyrics',
                    'spotify' => 'Spotify',
                    'youtube' => 'YouTube',
                    'soundcloud' => 'SoundCloud',
                    'release_date' => 'Release Date',
                    'main_release' => 'Main Release',
                    'audio_player' => 'Audio Player' // Add this line
                ];
                $visible = isset($_GET['columns']) ? $_GET['columns'] : array_keys($allColumns);
                foreach ($allColumns as $col => $label) {
                    echo '<label style="margin-right:1em;"><input type="checkbox" name="columns[]" value="' . $col . '" ' . (in_array($col, $visible) ? 'checked' : '') . '> ' . $label . '</label>';
                }
                ?>
                <button type="submit" style="margin-left:1em;">Apply Columns</button>
            </div>
        </form>
        <?php
        $db = new SQLite3(__DIR__ . '/../db/kars.db');
        $where = '';
        $params = [];
        if (!empty($_GET['q'])) {
            $q = '%' . $_GET['q'] . '%';
            $where = "WHERE song_title LIKE :q OR main_release LIKE :q OR lyrics LIKE :q";
            $stmt = $db->prepare("SELECT * FROM songs $where");
            $stmt->bindValue(':q', $q, SQLITE3_TEXT);
            $results = $stmt->execute();
        } else {
            $results = $db->query('SELECT * FROM songs');
        }
        $columns = $visible;
        echo '<table border="1" cellpadding="5" cellspacing="0"><tr>';
        foreach ($columns as $col) {
            echo '<th>' . htmlspecialchars($allColumns[$col]) . '</th>';
        }
        echo '</tr>';
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $col) {
                if ($col === 'audio_player') {
                    $song = isset($row['song_title']) ? $row['song_title'] : '';
                    $is_demo = (strtoupper($row['main_release']) === 'FALSE');

                    // Lookup release_ID from connections table if not a demo
                    $release_id = '';
                    if (!$is_demo) {
                        $song_id = $row['song_title'];
                        $conn_db = new SQLite3(__DIR__ . '/../db/kars.db');
                        $escaped_song_id = $conn_db->escapeString($song_id);
                        $conn_result = $conn_db->querySingle("SELECT release_ID FROM connections WHERE song_ID = '" . $escaped_song_id . "'");
                        $release_id = $conn_result ? $conn_result : '';
                        $conn_db->close();
                    }

                    echo '<td><div class="audio-player" data-song="' . htmlspecialchars($song) . '" data-release="' . htmlspecialchars($release_id) . '" data-demo="' . ($is_demo ? '1' : '0') . '"><div class="audio-placeholder">Loading...</div></div></td>';
                } else {
                    echo '<td>' . htmlspecialchars($row[$col]) . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        $db->close();
        ?>
    </main-element>
    <!-- Add this before </body> -->
    <script src="../js/lazy_load.js?v=<?= filemtime($root . 'js/lazy_load.js') ?>"></script>
</body>
</html>