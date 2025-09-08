<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <title>Kars' Songs</title>
    <style>
        .search-form {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-form input[type="text"] {
            padding: 5px;
            min-width: 200px;
        }
        .link-cell {
            display: flex;
            gap: 10px;
        }
        .link-cell a {
            padding: 5px 10px;
            text-decoration: none;
            color: inherit;
        }
        .link-cell a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Songs</h1>
    </main-element>

    <main-element>
        <form class="search-form" method="get">
            <div>
                <input type="text" name="search" placeholder="Search songs or releases..." 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php
        $db = new SQLite3(__DIR__ . '/../db/kars.db');
        
        // Build query based on search
        $where = '';
        $params = [];
        if (!empty($_GET['search'])) {
            $searchTerm = '%' . $_GET['search'] . '%';
            $where = "WHERE songs.song_title LIKE :search 
                     OR songs.release_title LIKE :search 
                     OR songs.lyrics LIKE :search";
            $params[':search'] = $searchTerm;
        }

        $query = "SELECT songs.*, releases.type 
            FROM songs 
            LEFT JOIN releases ON songs.release_title = releases.release_title 
            $where 
            ORDER BY songs.release_date ASC, songs.track_number ASC";

        $stmt = $db->prepare($query);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, SQLITE3_TEXT);
        }
        $results = $stmt->execute();

        echo '<table>';
        echo '<tr>';
        echo '<th>Track #</th>';
        echo '<th>Title</th>';
        echo '<th>Release</th>';
        echo '<th>Duration</th>';
        echo '<th>Release Date</th>';
        echo '<th>Links</th>';
        echo '<th>Player</th>';
        echo '</tr>';
        
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $releaseDate = $row['release_date'] === 'N/A' ? 'TBA' : date('Y-m-d', strtotime($row['release_date']));
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['track_number'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($row['song_title'] ?? 'No Title') . '</td>';
            echo '<td>' . htmlspecialchars($row['release_title'] ?? 'No Release') . 
                 ' (' . htmlspecialchars($row['type'] ?? 'Unknown Type') . ')</td>';
            echo '<td>' . htmlspecialchars($row['duration'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($releaseDate) . '</td>';
            echo '<td class="link-cell">';
            if (!empty($row['spotify'])) {
                echo '<a href="' . htmlspecialchars($row['spotify']) . '" target="_blank">Spotify</a>';
            }
            if (!empty($row['youtube'])) {
                echo '<a href="' . htmlspecialchars($row['youtube']) . '" target="_blank">YouTube</a>';
            }
            if (!empty($row['soundcloud'])) {
                echo '<a href="' . htmlspecialchars($row['soundcloud']) . '" target="_blank">SoundCloud</a>';
            }
            echo '</td>';
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