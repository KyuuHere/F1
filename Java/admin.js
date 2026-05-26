document.addEventListener('DOMContentLoaded', async () => {
    // Kontrola autentifikace
    let authData = null;
    try {
        const authResponse = await fetch('./php/check_auth.php');
        if (!authResponse.ok) {
            window.location.href = './login.php';
            return;
        }

        authData = await authResponse.json();
    } catch (error) {
        console.error('Chyba', error);
        window.location.href = './login.php';
        return;
    }

    const currentRole = authData?.role || 'admin';
    const currentUsername = authData?.username || '';
    console.log('admin.js loaded', { currentRole, currentUsername, authData });
    const roleBadge = document.querySelector('.role-badge');
    if (roleBadge) {
        roleBadge.textContent = currentRole === 'superadmin' ? 'Superadmin' : currentRole === 'admin' ? 'Administrator' : currentRole;
    }

    if (currentRole !== 'superadmin') {
        document.getElementById('userManagementSection')?.remove();
    }

    const initButton = document.getElementById('initDatabase');
    const initStatus = document.getElementById('initStatus');
    const driverForm = document.getElementById('driverForm');
    const teamForm = document.getElementById('teamForm');
    const resultForm = document.getElementById('resultForm');
    const logoutButton = document.getElementById('logoutButton');

    logoutButton?.addEventListener('click', async () => {
        try {
            await fetch('./php/logout.php');
            window.location.href = './index.php';
        } catch (error) {
            alert('Chyba při odhlášení: ' + error.message);
        }
    });

    const sendJson = async (url, payload, statusElement) => {
        if (statusElement) {
            statusElement.textContent = 'Odesílání...';
        }
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            let data = null;
            try {
                data = await response.json();
            } catch (jsonError) {
                console.error('Invalid JSON response from', url, jsonError);
                if (statusElement) {
                    statusElement.textContent = 'Server vrátil neplatnou odpověď.';
                }
                return null;
            }

            if (!response.ok || data.error) {
                if (statusElement) {
                    statusElement.textContent = 'Chyba: ' + (data.error || response.statusText);
                }
                return null;
            }

            if (statusElement) {
                statusElement.textContent = data.message || 'Operace proběhla úspěšně.';
            }
            return data;
        } catch (error) {
            if (statusElement) {
                statusElement.textContent = 'Nelze odeslat požadavek: ' + error.message;
            }
            console.error('Fetch POST error:', url, error);
            return null;
        }
    };

    const fetchJson = async (url, statusElement) => {
        if (statusElement) {
            statusElement.textContent = 'Načítám...';
        }
        try {
            const response = await fetch(url);
            let data = null;
            try {
                data = await response.json();
            } catch (jsonError) {
                console.error('Invalid JSON response from', url, jsonError);
                if (statusElement) {
                    statusElement.textContent = 'Server vrátil neplatnou odpověď.';
                }
                return null;
            }

            if (!response.ok || data.error) {
                if (statusElement) {
                    statusElement.textContent = 'Chyba: ' + (data.error || response.statusText);
                }
                return null;
            }
            if (statusElement) {
                statusElement.textContent = '';
            }
            return data;
        } catch (error) {
            if (statusElement) {
                statusElement.textContent = 'Nelze načíst data: ' + error.message;
            }
            console.error('Fetch GET error:', url, error);
            return null;
        }
    };

    const driversTableBody = document.querySelector('#driversTable tbody');
    const teamsTableBody = document.querySelector('#teamsTable tbody');
    const resultsTableBody = document.querySelector('#resultsTable tbody');
    const driversStatus = document.getElementById('driversStatus');
    const teamsStatus = document.getElementById('teamsStatus');
    const resultsStatus = document.getElementById('resultsStatus');

    const renderDrivers = drivers => {
        if (!driversTableBody) return;
        driversTableBody.innerHTML = '';
        drivers.forEach(driver => {
            const row = document.createElement('tr');
            const idCell = document.createElement('td');
            idCell.textContent = driver.id;
            const nameCell = document.createElement('td');
            nameCell.textContent = driver.name;
            const teamCell = document.createElement('td');
            teamCell.textContent = driver.team;
            const pointsCell = document.createElement('td');
            pointsCell.textContent = driver.points;
            const actionCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.textContent = 'Smazat';
            deleteButton.className = 'action-button small-button';
            deleteButton.addEventListener('click', () => deleteDriver(driver.id));
            actionCell.appendChild(deleteButton);
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(teamCell);
            row.appendChild(pointsCell);
            row.appendChild(actionCell);
            driversTableBody.appendChild(row);
        });
    };

    const renderTeams = teams => {
        if (!teamsTableBody) return;
        teamsTableBody.innerHTML = '';
        teams.forEach(team => {
            const row = document.createElement('tr');
            const idCell = document.createElement('td');
            idCell.textContent = team.id;
            const nameCell = document.createElement('td');
            nameCell.textContent = team.name;
            const pointsCell = document.createElement('td');
            pointsCell.textContent = team.points;
            const actionCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.textContent = 'Smazat';
            deleteButton.className = 'action-button small-button';
            deleteButton.addEventListener('click', () => deleteTeam(team.id));
            actionCell.appendChild(deleteButton);
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(pointsCell);
            row.appendChild(actionCell);
            teamsTableBody.appendChild(row);
        });
    };

    const renderResults = results => {
        if (!resultsTableBody) return;
        resultsTableBody.innerHTML = '';
        results.forEach(result => {
            const row = document.createElement('tr');
            const idCell = document.createElement('td');
            idCell.textContent = result.id;
            const raceCell = document.createElement('td');
            raceCell.textContent = result.race;
            const dateCell = document.createElement('td');
            dateCell.textContent = result.date;
            const circuitCell = document.createElement('td');
            circuitCell.textContent = result.circuit;
            const winnerCell = document.createElement('td');
            winnerCell.textContent = result.winner;
            const poleCell = document.createElement('td');
            poleCell.textContent = result.pole;
            const fastestCell = document.createElement('td');
            fastestCell.textContent = result.fastest_lap;
            const actionCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.textContent = 'Smazat';
            deleteButton.className = 'action-button small-button';
            deleteButton.addEventListener('click', () => deleteResult(result.id));
            actionCell.appendChild(deleteButton);
            row.appendChild(idCell);
            row.appendChild(raceCell);
            row.appendChild(dateCell);
            row.appendChild(circuitCell);
            row.appendChild(winnerCell);
            row.appendChild(poleCell);
            row.appendChild(fastestCell);
            row.appendChild(actionCell);
            resultsTableBody.appendChild(row);
        });
    };

    const loadDrivers = async () => {
        const data = await fetchJson('./php/drivers_list.php', driversStatus);
        if (data?.drivers) renderDrivers(data.drivers);
    };

    const loadTeams = async () => {
        const data = await fetchJson('./php/teams_list.php', teamsStatus);
        if (data?.teams) renderTeams(data.teams);
    };

    const loadResults = async () => {
        const data = await fetchJson('./php/results_list.php', resultsStatus);
        if (data?.results) renderResults(data.results);
    };

    const deleteDriver = async id => {
        const data = await sendJson('./php/driver_delete.php', { id }, driversStatus);
        if (data) loadDrivers();
    };

    const deleteTeam = async id => {
        const data = await sendJson('./php/team_delete.php', { id }, teamsStatus);
        if (data) loadTeams();
    };

    const deleteResult = async id => {
        const data = await sendJson('./php/result_delete.php', { id }, resultsStatus);
        if (data) loadResults();
    };

    loadDrivers();
    loadTeams();
    loadResults();

    initButton?.addEventListener('click', async () => {
        initStatus.textContent = 'Inicializuji databázi...';
        try {
            const response = await fetch('./php/init_db.php');
            const data = await response.json();
            initStatus.textContent = data.message || 'Databáze byla inicializována.';
        } catch (error) {
            initStatus.textContent = 'Chyba inicializace: ' + error.message;
        }
    });

    driverForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const payload = {
            name: driverForm.name.value,
            team: driverForm.team.value,
            points: parseInt(driverForm.points.value, 10),
        };
        const response = await sendJson('./php/add_driver.php', payload, document.getElementById('driverStatus'));
        if (response) {
            loadDrivers();
        }
    });

    teamForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const payload = {
            name: teamForm.name.value,
            points: parseInt(teamForm.points.value, 10),
        };
        const response = await sendJson('./php/add_team.php', payload, document.getElementById('teamStatus'));
        if (response) {
            loadTeams();
        }
    });

    resultForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const payload = {
            race: resultForm.race.value,
            date: resultForm.date.value,
            circuit: resultForm.circuit.value,
            winner: resultForm.winner.value,
            pole: resultForm.pole.value,
            fastest_lap: resultForm.fastest_lap.value,
        };
        const response = await sendJson('./php/add_result.php', payload, document.getElementById('resultStatus'));
        if (response) {
            loadResults();
        }
    });

    if (currentRole === 'superadmin') {
        const adminUserForm = document.getElementById('adminUserForm');
        const usersStatus = document.getElementById('usersStatus');
        const usersTableBody = document.querySelector('#usersTable tbody');

        const renderUsers = users => {
            if (!usersTableBody) return;
            usersTableBody.innerHTML = '';

            users.forEach(user => {
                const row = document.createElement('tr');
                const usernameCell = document.createElement('td');
                usernameCell.textContent = user.username;
                const roleCell = document.createElement('td');
                roleCell.textContent = user.role === 'superadmin' ? 'Superadmin' : user.role === 'databasemaster' ? 'Databasemaster' : 'Administrator';
                const actionCell = document.createElement('td');

                if (user.role !== 'databasemaster' && user.username !== currentUsername) {
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.textContent = 'Smazat';
                    deleteButton.className = 'action-button small-button';
                    deleteButton.addEventListener('click', () => deleteUser(user.username));
                    actionCell.appendChild(deleteButton);
                } else {
                    actionCell.textContent = '-';
                }

                row.appendChild(usernameCell);
                row.appendChild(roleCell);
                row.appendChild(actionCell);
                usersTableBody.appendChild(row);
            });
        };

        const refreshUsers = async () => {
            if (!usersStatus) return;
            usersStatus.textContent = 'Načítám uživatele...';
            try {
                const response = await fetch('./php/users_list.php');
                const data = await response.json();
                if (!response.ok || data.error) {
                    usersStatus.textContent = 'Chyba: ' + (data.error || response.statusText);
                    return;
                }
                usersStatus.textContent = '';
                renderUsers(data.users || []);
            } catch (error) {
                usersStatus.textContent = 'Nelze načíst uživatele: ' + error.message;
            }
        };

        const deleteUser = async username => {
            if (!usersStatus) return;
            usersStatus.textContent = 'Mazání uživatele...';
            try {
                const response = await fetch('./php/user_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username }),
                });
                const data = await response.json();
                if (!response.ok || data.error) {
                    usersStatus.textContent = 'Chyba: ' + (data.error || response.statusText);
                    return;
                }
                usersStatus.textContent = data.message || 'Uživatel byl smazán.';
                refreshUsers();
            } catch (error) {
                usersStatus.textContent = 'Nelze smazat uživatele: ' + error.message;
            }
        };

        adminUserForm?.addEventListener('submit', async event => {
            event.preventDefault();
            if (!adminUserForm || !usersStatus) return;

            const usernameInput = adminUserForm.querySelector('#newAdminUsername');
            const passwordInput = adminUserForm.querySelector('#newAdminPassword');
            const username = usernameInput?.value.trim();
            const password = passwordInput?.value.trim();

            if (!username || !password) {
                usersStatus.textContent = 'Uživatelské jméno a heslo jsou povinné.';
                return;
            }

            usersStatus.textContent = 'Přidávám nového admina...';
            try {
                const response = await fetch('./php/user_add.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password }),
                });
                const data = await response.json();
                if (!response.ok || data.error) {
                    usersStatus.textContent = 'Chyba: ' + (data.error || response.statusText);
                    return;
                }
                usersStatus.textContent = data.message || 'Admin úspěšně přidán.';
                usernameInput.value = '';
                passwordInput.value = '';
                refreshUsers();
            } catch (error) {
                usersStatus.textContent = 'Nelze přidat admina: ' + error.message;
            }
        });

        refreshUsers();
    }
});
