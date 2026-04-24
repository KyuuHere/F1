document.addEventListener('DOMContentLoaded', () => {
    const driversBody = document.querySelector('#driversT tbody');
    const teamsBody = document.querySelector('#teamsT tbody');
    const fastestBody = document.querySelector('#fastest tbody');
    const status = document.getElementById('tableStatus');

    if (!driversBody || !teamsBody || !fastestBody) {
        return;
    }

    status.textContent = 'Načítám data z databáze...';

    fetch('/api/standings.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                status.textContent = 'Chyba při načítání dat: ' + data.error;
                return;
            }

            driversBody.innerHTML = '';
            teamsBody.innerHTML = '';
            fastestBody.innerHTML = '';

            data.drivers.forEach((driver, index) => {
                driversBody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${driver.name}</td>
                        <td>${driver.team}</td>
                        <td>${driver.points}</td>
                    </tr>`;
            });

            data.teams.forEach((team, index) => {
                teamsBody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${team.name}</td>
                        <td>${team.points}</td>
                    </tr>`;
            });

            data.fastest.forEach(lap => {
                fastestBody.innerHTML += `
                    <tr>
                        <td>${lap.race}</td>
                        <td>${lap.driver}</td>
                        <td>${lap.time}</td>
                    </tr>`;
            });

            status.textContent = 'Data byla úspěšně načtena.';
        })
        .catch(() => {
            status.textContent = 'Nelze načist data, pravděpodobně vybouchla databaze.';
        });
});