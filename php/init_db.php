<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// Vytvoří databázi roblox, pokud ještě neexistuje.
function createDatabaseIfMissing(): void
{
    global $dbHost, $dbPort, $dbName, $dbUser, $dbPass;

    $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $dbHost, $dbPort);
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $dbName));
}

// Vytvoří tabulky v databázi, pokud ještě neexistují.
function ensureTables(PDO $db): void
{
    $db->exec(
        'CREATE TABLE IF NOT EXISTS drivers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            team VARCHAR(255) NOT NULL,
            points INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
    );

    $db->exec(
        'CREATE TABLE IF NOT EXISTS teams (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            points INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
    );

    $db->exec(
        'CREATE TABLE IF NOT EXISTS fastest_laps (
            id INT AUTO_INCREMENT PRIMARY KEY,
            race VARCHAR(255) NOT NULL,
            driver VARCHAR(255) NOT NULL,
            time VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
    );

    $db->exec(
        'CREATE TABLE IF NOT EXISTS results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            race VARCHAR(255) NOT NULL,
            date DATE NOT NULL,
            circuit VARCHAR(255) NOT NULL,
            winner VARCHAR(255) NOT NULL,
            pole VARCHAR(255) NOT NULL,
            fastest_lap VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
    );
}

// Naplní tabulky ukázkovými daty jen když je tabulka prázdná.
function seedSampleData(PDO $db): void
{
    $count = $db->query('SELECT COUNT(*) FROM drivers')->fetchColumn();
    if ($count > 0) {
        return;
    }

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
}

try {
    createDatabaseIfMissing();
    $db = getDatabase();
    ensureTables($db);
    seedSampleData($db);

    echo json_encode([
        'status' => 'ok',
        'message' => 'Database initialized successfully',
    ], JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
