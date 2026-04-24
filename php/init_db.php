<?php
$dbFile = __DIR__ . '/../data/f1.sqlite';
$dataDir = dirname($dbFile);
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}
if (file_exists($dbFile)) {
    unlink($dbFile);
}

$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec(
    'CREATE TABLE drivers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        team TEXT NOT NULL,
        points INTEGER NOT NULL
    );'
);

$db->exec(
    'CREATE TABLE teams (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        points INTEGER NOT NULL
    );'
);

$db->exec(
    'CREATE TABLE fastest_laps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        race TEXT NOT NULL,
        driver TEXT NOT NULL,
        time TEXT NOT NULL
    );'
);

$db->exec(
    'CREATE TABLE results (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        race TEXT NOT NULL,
        date TEXT NOT NULL,
        circuit TEXT NOT NULL,
        winner TEXT NOT NULL,
        pole TEXT NOT NULL,
        fastest_lap TEXT NOT NULL
    );'
);

$drivers = [
    ['Max Verstappen', 'Red Bull Racing', 423],
    ['Lando Norris', 'McLaren', 371],
    ['Charles Leclerc', 'Ferrari', 358],
    ['George Russell', 'Mercedes', 312],
    ['Sergio Perez', 'Red Bull Racing', 293],
];

$teams = [
    ['Red Bull Racing', 716],
    ['McLaren', 532],
    ['Ferrari', 501],
    ['Mercedes', 412],
    ['Aston Martin', 284],
];

$fastest = [
    ['Bahrain GP', 'Max Verstappen', '1:32.456'],
    ['Imola GP', 'Charles Leclerc', '1:16.342'],
    ['Monaco GP', 'Lando Norris', '1:13.998'],
];

$results = [
    ['Bahrain GP', '2025-03-16', 'Bahrain International Circuit', 'Max Verstappen', 'Charles Leclerc', 'Max Verstappen'],
    ['Imola GP', '2025-04-06', 'Autodromo Enzo e Dino Ferrari', 'Charles Leclerc', 'Lando Norris', 'Charles Leclerc'],
    ['Monaco GP', '2025-05-25', 'Circuit de Monaco', 'Lando Norris', 'Carlos Sainz', 'Lando Norris'],
];

$insertDriver = $db->prepare('INSERT INTO drivers (name, team, points) VALUES (:name, :team, :points)');
foreach ($drivers as $driver) {
    $insertDriver->execute([':name' => $driver[0], ':team' => $driver[1], ':points' => $driver[2]]);
}

$insertTeam = $db->prepare('INSERT INTO teams (name, points) VALUES (:name, :points)');
foreach ($teams as $team) {
    $insertTeam->execute([':name' => $team[0], ':points' => $team[1]]);
}

$insertFastest = $db->prepare('INSERT INTO fastest_laps (race, driver, time) VALUES (:race, :driver, :time)');
foreach ($fastest as $lap) {
    $insertFastest->execute([':race' => $lap[0], ':driver' => $lap[1], ':time' => $lap[2]]);
}

$insertResult = $db->prepare('INSERT INTO results (race, date, circuit, winner, pole, fastest_lap) VALUES (:race, :date, :circuit, :winner, :pole, :fastest_lap)');
foreach ($results as $result) {
    $insertResult->execute([
        ':race' => $result[0],
        ':date' => $result[1],
        ':circuit' => $result[2],
        ':winner' => $result[3],
        ':pole' => $result[4],
        ':fastest_lap' => $result[5],
    ]);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'status' => 'ok',
    'message' => 'Database initialized successfully',
    'drivers' => count($drivers),
    'teams' => count($teams),
    'fastest' => count($fastest),
    'results' => count($results),
], JSON_PRETTY_PRINT);
