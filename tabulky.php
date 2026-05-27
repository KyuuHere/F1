<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 2025</title>
    <link rel="stylesheet" href="./style/tabulky.css">
</head>

<body>
<div class="nadpis">
    <img src="./obrazky/F1-Logo.png" alt="F1 logo" class="logo-left">
    <img src="./obrazky/F1-Logo2.png" alt="F1 logo 2" class="logo-right">
</div>

<header>
    <h2>Formule 1 – Sezóna 2025</h2>
    <p class="subtitle">Průběžné pořadí a statistiky</p>
    <nav class="main-nav">
        <a class="nav-link" href="./index.php">Hlavní</a>
        <a class="nav-link" href="./vysledky.php">Výsledky</a>
    </nav>
</header>

<main class="page-content">
    <section>
        <h3>Průběžné pořadí jezdců</h3>
        <table id="driversT">
            <thead>
                <tr>
                    <th>Pozice</th>
                    <th>Jezdec</th>
                    <th>Tým</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody>
                <!-- snad se vyplni automaticky -->
            </tbody>
        </table>
    </section>

    <section>
        <h3>Průběžné pořadí týmů</h3>
        <table id="teamsT">
            <thead>
                <tr>
                    <th>Pozice</th>
                    <th>Tým</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody>
                <!-- snad se vyplni automaticky -->
            </tbody>
        </table>
    </section>

    <section>
        <h3>Nejrychlejší kola</h3>
        <table id="fastest">
            <thead>
                <tr>
                    <th>Závod</th>
                    <th>Jezdec</th>
                    <th>Čas kola</th>
                </tr>
            </thead>
            <tbody>
                <!-- snad se vyplni automaticky -->
            </tbody>
        </table>
    </section>

    <p id="tableStatus" class="status">Načítám data z databáze...</p>
</main>

<script src="./Java/nadavka.js"></script>
</body>
</html>
