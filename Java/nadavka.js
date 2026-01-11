const drivers2025 = [
    { name: " Lando Norris ", team: " McLaren", points: 423 }
];

const teams2025 = [
    { team: " McLaren", points: 800 }
];

// mělo by to doplnit tabulku drivers(snad)
const driversT = document.getElementById("driversT");
drivers2025.sort((a, b) => b.points - a.points);

drivers2025.forEach((driver, index) => {
    driversT.innerHTML += `
        <tr>
            <td>${index + 1}</td>
            <td>${driver.name }</td>
            <td>${driver.team }</td>
            <td>${driver.points }</td>
        </tr>
    `;
});

// mělo by to doplnit tabulku teams(snad)
const teamsT = document.getElementById("teamsT");
teams2025.sort((a, b) => b.points - a.points);

teams2025.forEach((team, index) => {
    teamsT.innerHTML += `
        <tr>
            <td>${index + 1 }</td>
            <td>${team.team }</td>
            <td>${team.points }</td>
        </tr>
    `;
});

const fastest2025=[
       { race: "Bahrain", driver: "Max Verstappen", time:" 1:32.456" }
];

// mělo by to doplnit tabulku kol (snad)
const fastest=document.getElementById("fastest");
fastest2025.forEach(lap=>{
   fastest.innerHTML +=`
        <tr>
            <td>${lap.race }</td>
            <td>${lap.driver }</td>
            <td>${lap.time }</td>
        </tr>
    `;
})