<?php
session_start();

// Kontrola autentizace
if (!isset($_SESSION['databasemaster_logged_in']) || $_SESSION['databasemaster_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F1 Admin - Správa databáze</title>
  <link rel="stylesheet" href="./style/admin.css">
  <script src="./Java/admin.js" defer></script>
</head>
<body>
  <div class="nadpis">
    <img src="./obrazky/F1-Logo.png" alt="F1 logo" class="logo-left">
    <img src="./obrazky/F1-Logo2.png" alt="F1 logo 2" class="logo-right">
    <button id="logoutButton" class="logout-button">Odhlásit se</button>
  </div>

  <header>
    <h2>Správa F1 databáze</h2>
    <h4>Database Master</h4>
    <p class="subtitle"></p>
    <nav class="main-nav">
      <a class="nav-link" href="./index.php">Hlavní</a>
      <a class="nav-link" href="./vysledky.php">Výsledky</a>
      <a class="nav-link" href="./tabulky.php">Tabulky</a>
    </nav>
  </header>

  <main class="page-content">
    <section class="card">
      <h3>Inicializace databáze</h3>
      <button id="initDatabase" class="action-button">Spustit /php/init_db.php</button>
      <p id="initStatus" class="status">Zdá se že asi databaze vybouchla, jinak bys zde nebyl, zkus ji opravit timhle magickym tlačitkem..</p>
    </section>

  </main>
</body>
</html>
