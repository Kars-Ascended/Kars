<?php
// Database file path
$dbFile = __DIR__ . '/kars.db';

// Create (or open) the SQLite database
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables with correct columns
$db->exec("
    CREATE TABLE IF NOT EXISTS releases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        release_title TEXT UNIQUE,
        type TEXT,
        release_date TEXT,
        main_release TEXT DEFAULT 'TRUE'
    );
    CREATE TABLE IF NOT EXISTS songs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        song_title TEXT,
        release_title TEXT,
        release_type TEXT,
        track_number INTEGER,
        duration TEXT,
        lyrics TEXT,
        spotify TEXT,
        youtube TEXT,
        soundcloud TEXT,
        release_date TEXT,
        main_release TEXT,
        FOREIGN KEY(release_title) REFERENCES releases(release_title)
    );
");

// Helper function to import CSV
function importCsv($db, $csvFile, $table, $columnMap) {
    if (!file_exists($csvFile)) {
        echo "File not found: $csvFile\n";
        return;
    }
    $handle = fopen($csvFile, 'r');
    if (!$handle) {
        echo "Could not open $csvFile\n";
        return;
    }
    
    // Skip header
    $headers = fgetcsv($handle);
    
    // Map CSV columns to database columns
    $columnIndexes = array();
    $dbColumns = array();
    foreach ($columnMap as $csvCol => $dbCol) {
        $index = array_search($csvCol, $headers);
        if ($index !== false) {
            $columnIndexes[] = $index;
            $dbColumns[] = $dbCol;
        }
    }
    
    $placeholders = implode(',', array_fill(0, count($dbColumns), '?'));
    $stmt = $db->prepare("INSERT INTO $table (" . implode(',', $dbColumns) . ") VALUES ($placeholders)");
    
    while (($data = fgetcsv($handle)) !== false) {
        $rowData = array_map(function($index) use ($data) {
            return $index !== false ? $data[$index] : null;
        }, $columnIndexes);
        
        try {
            $stmt->execute($rowData);
        } catch (Exception $e) {
            echo "Error importing row: " . implode(',', $rowData) . "\n";
            echo $e->getMessage() . "\n";
        }
    }
    fclose($handle);
    echo "Imported $csvFile into $table\n";
}

// Clear existing data
$db->exec("DELETE FROM songs; DELETE FROM releases; DELETE FROM sqlite_sequence;");

// Import releases
importCsv(
    $db,
    __DIR__ . '/../data/Kars_Ascended Music - releases.csv',
    'releases',
    [
        'release_title' => 'release_title',
        'type' => 'type',
        'release_date' => 'release_date'
    ]
);

// Import songs
importCsv(
    $db,
    __DIR__ . '/../data/Kars_Ascended Music - songs.csv',
    'songs',
    [
        'song_title' => 'song_title',
        'release_ID' => 'release_title',  // Map release_ID to release_title
        'release_type' => 'release_type',
        'track_number' => 'track_number',
        'duration' => 'duration',
        'lyrics' => 'lyrics',
        'spotify' => 'spotify',
        'youtube' => 'youtube',
        'soundcloud' => 'soundcloud',
        'release_date' => 'release_date',
        'main_release' => 'main_release'
    ]
);

echo "Import complete.\n";
?>