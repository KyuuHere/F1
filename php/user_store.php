<?php
// Vrátí cestu k souboru s uloženými uživateli.
function get_user_file_path(): string
{
    return __DIR__ . '/users.json';
}

// Vrátí seznam výchozích uživatelů, pokud soubor neexistuje.
function get_default_users(): array
{
    return [
        [
            'username' => 'databasemaster',
            'password' => password_hash('databasemaster', PASSWORD_DEFAULT),
            'role' => 'databasemaster',
        ],
        [
            'username' => 'admin',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'role' => 'admin',
        ],
        [
            'username' => 'superadmin',
            'password' => password_hash('superadmin', PASSWORD_DEFAULT),
            'role' => 'superadmin',
        ],
    ];
}

// Načte uživatele ze souboru a vytvoří soubor s výchozími uživateli, když chybí.
function load_users(): array
{
    $file = get_user_file_path();
    if (!file_exists($file)) {
        $defaults = get_default_users();
        file_put_contents($file, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $defaults;
    }
    
    $content = file_get_contents($file);
    $users = json_decode($content, true);
    if (!is_array($users)) {
        $users = [];
    }

    $changed = false;
    foreach (get_default_users() as $defaultUser) {
        if (find_user_index($users, $defaultUser['username']) === null) {
            $users[] = $defaultUser;
            $changed = true;
        }
    }

    if ($changed) {
        save_users($users);
    }

    return $users;
}

// Uloží pole uživatelů zpět do souboru users.json.
function save_users(array $users): bool
{
    return file_put_contents(get_user_file_path(), json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false;
}

// Najde pozici uživatele v poli podle jeho uživatelského jména.
function find_user_index(array $users, string $username): ?int
{
    foreach ($users as $index => $user) {
        if (isset($user['username']) && $user['username'] === $username) {
            return $index;
        }
    }
    return null;
}

// Ověří přihlášení uživatele podle jména a hesla.
function validate_user(string $username, string $password): ?array
{
    $users = load_users();
    foreach ($users as $user) {
        if (!isset($user['username'], $user['password'], $user['role'])) {
            continue;
        }

        if ($user['username'] !== $username) {
            continue;
        }

        if (password_verify($password, $user['password']) || $user['password'] === $password) {
            return [
                'username' => $user['username'],
                'role' => $user['role'],
            ];
        }
    }

    return null;
}

// Vrátí seznam uživatelů bez hesel pro zobrazení v administraci.
function get_public_users(): array
{
    $users = load_users();
    return array_map(function ($user) {
        return [
            'username' => $user['username'] ?? '',
            'role' => $user['role'] ?? 'admin',
        ];
    }, $users);
}

// Přidá nového admin uživatele do souboru.
function add_admin_user(string $username, string $password): array
{
    $username = trim($username);
    $password = trim($password);

    if ($username === '' || $password === '') {
        throw new RuntimeException('Uživatelské jméno a heslo jsou povinné.');
    }

    if (in_array($username, ['databasemaster', 'superadmin'], true)) {
        throw new RuntimeException('Toto uživatelské jméno nelze použít.');
    }

    $users = load_users();
    if (find_user_index($users, $username) !== null) {
        throw new RuntimeException('Uživatel již existuje.');
    }

    $users[] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'admin',
    ];

    if (!save_users($users)) {
        throw new RuntimeException('Nelze uložit nového uživatele.');
    }

    return [
        'username' => $username,
        'role' => 'admin',
    ];
}

// Smaže uživatele podle jména, ale nepovolí smazání hlavních účtů.
function delete_user_by_username(string $username): void
{
    if ($username === 'databasemaster') {
        throw new RuntimeException('Databasemaster nelze smazat.');
    }

    if ($username === 'superadmin') {
        throw new RuntimeException('Superadmin nelze smazat.');
    }

    $users = load_users();
    $index = find_user_index($users, $username);
    if ($index === null) {
        throw new RuntimeException('Uživatel nenalezen.');
    }

    array_splice($users, $index, 1);

    if (!save_users($users)) {
        throw new RuntimeException('Nelze uložit změny uživatelů.');
    }
}
