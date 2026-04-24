document.addEventListener('DOMContentLoaded', async () => {
    // Kontrola autentifikace
    try {
        const authResponse = await fetch('/php/check_auth.php');
        if (!authResponse.ok) {
            // Není přihlášen, přesměruj na login
            window.location.href = '/html/login.html';
            return;
        }
    } catch (error) {
        console.error('Chyba', error);
        window.location.href = '/html/login.html';
        return;
    }

    const initButton = document.getElementById('initDatabase');
    const initStatus = document.getElementById('initStatus');
    const driverForm = document.getElementById('driverForm');
    const teamForm = document.getElementById('teamForm');
    const resultForm = document.getElementById('resultForm');
    const logoutButton = document.getElementById('logoutButton');

    logoutButton?.addEventListener('click', async () => {
        try {
            await fetch('/php/logout.php');
            window.location.href = '/html/index.html';
        } catch (error) {
            alert('Chyba při odhlášení: ' + error.message);
        }
    });

    const sendJson = async (url, payload, statusElement) => {
        statusElement.textContent = 'Odesílání...';
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const data = await response.json();
            if (!response.ok || data.error) {
                statusElement.textContent = 'Chyba: ' + (data.error || response.statusText);
                return;
            }

            statusElement.textContent = data.message || 'Operace proběhla úspěšně.';
        } catch (error) {
            statusElement.textContent = 'Nelze odeslat požadavek: ' + error.message;
        }
    };

    initButton?.addEventListener('click', async () => {
        initStatus.textContent = 'Inicializuji databázi...';
        try {
            const response = await fetch('/php/init_db.php');
            const data = await response.json();
            initStatus.textContent = data.message || 'Databáze byla inicializována.';
        } catch (error) {
            initStatus.textContent = 'Chyba inicializace: ' + error.message;
        }
    });

    driverForm?.addEventListener('submit', event => {
        event.preventDefault();
        const payload = {
            name: driverForm.name.value,
            team: driverForm.team.value,
            points: parseInt(driverForm.points.value, 10),
        };
        sendJson('/php/add_driver.php', payload, document.getElementById('driverStatus'));
    });

    teamForm?.addEventListener('submit', event => {
        event.preventDefault();
        const payload = {
            name: teamForm.name.value,
            points: parseInt(teamForm.points.value, 10),
        };
        sendJson('/php/add_team.php', payload, document.getElementById('teamStatus'));
    });

    resultForm?.addEventListener('submit', event => {
        event.preventDefault();
        const payload = {
            race: resultForm.race.value,
            date: resultForm.date.value,
            circuit: resultForm.circuit.value,
            winner: resultForm.winner.value,
            pole: resultForm.pole.value,
            fastest_lap: resultForm.fastest_lap.value,
        };
        sendJson('/php/add_result.php', payload, document.getElementById('resultStatus'));
    });
});
