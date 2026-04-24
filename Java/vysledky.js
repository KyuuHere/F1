document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('#raceResults tbody');
    const status = document.getElementById('resultsStatus');

    if (!tableBody) return;

    fetch('/php/results.php')
        .then(response => response.json())
        .then(data => {
            if (!data.results) {
                status.textContent = 'Nebyly nalezeny žádné výsledky.';
                return;
            }

            data.results.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row.race}</td>
                    <td>${row.date}</td>
                    <td>${row.circuit}</td>
                    <td>${row.winner}</td>
                    <td>${row.pole}</td>
                    <td>${row.fastest_lap}</td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(() => {
            status.textContent = 'Nelze načist data, pravděpodobně vybouchla databaze.';
        });
});
