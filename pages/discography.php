<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta-include.php'; ?>
    <link rel="stylesheet" href="../css/discography.css">
    <title>MKA Discography</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Discography</h1>
    </main-element>

    <main-element class="filters">
        <form method="get">
            <div class="filter-container">
                <div class="filter-group">
                    <h3>Discography Type:</h3>
                    <label><input type="checkbox" name="type[]" value="Album" <?php echo (isset($_GET['type']) && in_array('Album', $_GET['type'])) ? 'checked' : ''; ?>> Albums</label>
                    <label><input type="checkbox" name="type[]" value="EP" <?php echo (isset($_GET['type']) && in_array('EP', $_GET['type'])) ? 'checked' : ''; ?>> EPs</label>
                    <label><input type="checkbox" name="type[]" value="Single" <?php echo (isset($_GET['type']) && in_array('Single', $_GET['type'])) ? 'checked' : ''; ?>> Singles</label>
                </div>
                
                <div class="filter-group">
                    <h3>Content:</h3>
                    <div style="display: flex; gap: 1em;">
                    <label>
                        <div class="custom-select">
                            Contains Explicit Tracks:
                            <select name="has_explicit">
                                <option value="">-- Any --</option>
                                <option value="yes" <?php if (($_GET['has_explicit'] ?? '') === 'yes') echo 'selected'; ?>>Yes</option>
                                <option value="no" <?php if (($_GET['has_explicit'] ?? '') === 'no') echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                    </label>
                    <label>
                        <div class="custom-select">
                            Has Featured Artists:
                            <select name="has_features">
                                <option value="">-- Any --</option>
                                <option value="yes" <?php if (($_GET['has_features'] ?? '') === 'yes') echo 'selected'; ?>>Yes</option>
                                <option value="no" <?php if (($_GET['has_features'] ?? '') === 'no') echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                    </label>
                    <label>
                        <div class="custom-select">
                            Contains Breakcore:
                            <select name="has_breakcore">
                                <option value="">-- Any --</option>
                                <option value="yes" <?php if (($_GET['has_breakcore'] ?? '') === 'yes') echo 'selected'; ?>>Yes</option>
                                <option value="no" <?php if (($_GET['has_breakcore'] ?? '') === 'no') echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                    </label>
                    </div>
                    <label>
                        <!--
                        <div class="custom-select">
                            Hide Non-Main Releases:
                            <select name="hide_non_main">
                                <option value="">-- Any --</option>
                                <option value="yes" <?php if (($_GET['hide_non_main'] ?? '') === 'yes') echo 'selected'; ?>>Yes</option>
                                <option value="no" <?php if (($_GET['hide_non_main'] ?? '') === 'no') echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                        -->
                    </label>
                </div>
            </div>
            <button type="submit">Apply Filters</button>
        </form>
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