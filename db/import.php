<?php
// Database file path
$dbFile = __DIR__ . '/kars.db';

// Create (or open) the SQLite database
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables with correct columns
$db->exec("
    CREATE TABLE IF NOT EXISTS songs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        song_title TEXT,
        duration TEXT,
        lyrics TEXT,
        spotify TEXT,
        youtube TEXT,
        soundcloud TEXT,
        release_date TEXT,
        main_release TEXT
    );
    CREATE TABLE IF NOT EXISTS releases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        release_title TEXT,
        type TEXT,
        release_date TEXT
    );
    CREATE TABLE IF NOT EXISTS connections (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        song_ID TEXT,
        release_ID TEXT,
        track_number INTEGER,
        main_release TEXT
    );
");

// Helper function to import CSV
function importCsv($db, $csvFile, $table, $columns) {
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
    fgetcsv($handle);
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $stmt = $db->prepare("INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)");
    while (($data = fgetcsv($handle)) !== false) {
        // Only use as many columns as expected
        $data = array_slice($data, 0, count($columns));
        // Pad data if row is short
        $data = array_pad($data, count($columns), null);
        $stmt->execute($data);
    }
    fclose($handle);
    echo "Imported $csvFile into $table\n";
}

// Import each CSV with correct columns
importCsv(
    $db,
    __DIR__ . '/../data/Kars_Ascended Music - songs.csv',
    'songs',
    ['song_title', 'duration', 'lyrics', 'spotify', 'youtube', 'soundcloud', 'release_date', 'main_release']
);
importCsv(
    $db,
    __DIR__ . '/../data/Kars_Ascended Music - releases.csv',
    'releases',
    ['release_title', 'type', 'release_date']
);
importCsv(
    $db,
    __DIR__ . '/../data/Kars_Ascended Music - connections.csv',
    'connections',
    ['song_ID', 'release_ID', 'track_number', 'main_release']
);

echo "Import complete.\n";
?>