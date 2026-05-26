<?php
session_start();

$role = $_SESSION['user_role'] ?? null;
if ($role === null) {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        $role = 'admin';
    }
}

if ($role !== 'admin' && $role !== 'superadmin') {
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
    <p class="role-badge"><?= htmlspecialchars($role === 'superadmin' ? 'Superadmin' : 'Administrator', ENT_QUOTES, 'UTF-8') ?></p>
    <p class="subtitle"></p>
    <nav class="main-nav">
      <a class="nav-link" href="./index.php">Hlavní</a>
      <a class="nav-link" href="./vysledky.php">Výsledky</a>
      <a class="nav-link" href="./tabulky.php">Tabulky</a>
    </nav>
  </header>

  <main class="page-content">

  <section class="card superadmin-section" id="userManagementSection" style="<?= $role === 'superadmin' ? '' : 'display:none;' ?>">
    <h3>Správa uživatelů</h3>
    <form id="adminUserForm">
      <div class="field-group">
        <label for="newAdminUsername">Nové uživatelské jméno</label>
        <input id="newAdminUsername" name="username" type="text" required>
      </div>
      <div class="field-group">
        <label for="newAdminPassword">Heslo</label>
        <input id="newAdminPassword" name="password" type="password" required>
      </div>
      <button type="submit" class="action-button">Přidat nového admina</button>
      <p id="adminUserStatus" class="status"></p>
    </form>

    <h3>Tabulka uživatelů</h3>
    <div class="table-container">
      <table id="usersTable">
        <thead>
          <tr>
            <th>Uživatel</th>
            <th>Role</th>
            <th>Akce</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <p id="usersStatus" class="status"></p>
  </section>

    <section class="card">
      <h3>Přidat jezdce</h3>
      <form id="driverForm">
        <div class="field-group">
          <label for="driverName">Jméno jezdce</label>
          <input id="driverName" name="name" type="text" required>
        </div>
        <div class="field-group">
          <label for="driverTeam">Tým</label>
          <input id="driverTeam" name="team" type="text" required>
        </div>
        <div class="field-group">
          <label for="driverPoints">Body</label>
          <input id="driverPoints" name="points" type="number" min="0" required>
        </div>
        <button type="submit" class="action-button">Přidat jezdce</button>
        <p id="driverStatus" class="status"></p>
      </form>
    </section>

    <section class="card">
      <h3>Přidat tým</h3>
      <form id="teamForm">
        <div class="field-group">
          <label for="teamName">Jméno týmu</label>
          <input id="teamName" name="name" type="text" required>
        </div>
        <div class="field-group">
          <label for="teamPoints">Body</label>
          <input id="teamPoints" name="points" type="number" min="0" required>
        </div>
        <button type="submit" class="action-button">Přidat tým</button>
        <p id="teamStatus" class="status"></p>
      </form>
    </section>

    <section class="card">
      <h3>Správa jezdců</h3>
      <div class="table-container">
        <table id="driversTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Jméno</th>
              <th>Tým</th>
              <th>Body</th>
              <th>Akce</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <p id="driversStatus" class="status"></p>
    </section>

    <section class="card">
      <h3>Správa týmů</h3>
      <div class="table-container">
        <table id="teamsTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Jméno</th>
              <th>Body</th>
              <th>Akce</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <p id="teamsStatus" class="status"></p>
    </section>

    <section class="card">
      <h3>Přidat výsledek závodu</h3>
      <form id="resultForm">
        <div class="field-group">
          <label for="resultRace">Závod</label>
          <input id="resultRace" name="race" type="text" required>
        </div>
        <div class="field-group">
          <label for="resultDate">Datum</label>
          <input id="resultDate" name="date" type="date" required>
        </div>
        <div class="field-group">
          <label for="resultCircuit">Okruh</label>
          <input id="resultCircuit" name="circuit" type="text" required>
        </div>
        <div class="field-group">
          <label for="resultWinner">Vítěz</label>
          <input id="resultWinner" name="winner" type="text" required>
        </div>
        <div class="field-group">
          <label for="resultPole">Pole position</label>
          <input id="resultPole" name="pole" type="text" required>
        </div>
        <div class="field-group">
          <label for="resultFastest">Nejrychlejší kolo</label>
          <input id="resultFastest" name="fastest_lap" type="text" required>
        </div>
        <button type="submit" class="action-button">Přidat výsledek</button>
        <p id="resultStatus" class="status"></p>
      </form>
    </section>

    <section class="card">
      <h3>Správa výsledků</h3>
      <div class="table-container">
        <table id="resultsTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Závod</th>
              <th>Datum</th>
              <th>Okruh</th>
              <th>Vítěz</th>
              <th>Pole</th>
              <th>Nejrychlejší</th>
              <th>Akce</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <p id="resultsStatus" class="status"></p>
    </section>
  </main>
</body>
</html>
