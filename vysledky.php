<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F1 2025</title>
  <link rel="stylesheet" href="./style/vysledky.css">
  <script src="./Java/vysledky.js" defer></script>
</head>
<body>
  <div class="nadpis">
    <img src="./obrazky/F1-Logo.png" alt="F1logo" class="logo-left">
    <img src="./obrazky/F1-Logo2.png" alt="F1Logo2" class="logo-right">
  </div>
  <header>
      <h2>Formule 1 – Sezóna 2025</h2>
      <p class="subtitle">Průběžné pořadí a statistiky</p>
      <nav class="main-nav">
          <a class="nav-link" href="./index.php">Hlavní</a>
          <a class="nav-link" href="./tabulky.php">Tabulky</a>
      </nav>
  </header>
  </header>

  <main class="page-content">
    <section class="results-table">
      <h3>Sezónní výsledky závodů</h3>
      <table id="raceResults">
        <thead>
          <tr>
            <th>#</th>
            <th>Závod</th>
            <th>Datum</th>
            <th>Okruh</th>
            <th>Vítěz</th>
            <th>Pole position</th>
            <th>Nejrychlejší kolo</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data načteno z databáze -->
        </tbody>
      </table>
      <p id="resultsStatus" class="status">Načítám výsledky z databáze...</p>

    </section>

    <section class="widget-section">
      <h3>Oficiální F1 widget</h3>
      <iframe src="https://1racing.co/widget/f1" title="F1 widget" class="widget-frame"></iframe>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 F1 - Výsledky a statistiky</p>
  </footer>
</body>
</html>
