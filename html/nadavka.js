 const drivers2025=[
    {name:"Lando Norris",team:"Mclaren",points:423}
 ];
 const teams2025=[
    {team:"McLaren",points:800}
 ];

 const driversT=document.getElementById("driversT");
 drivers2025.sort((a,b)=>b.points-a.points);
 drivers2025.forEach((driver,index)=>{
    driversT.innerHTML+=`
    <tr>
    <td>$(index+1)</td>
    <td>$(driver.name)</td>
    </tr>
     `;
 });

  const teamsT=document.getElementById("teamsT");
 teams2025.sort((a,b)=>b.points-a.points);
 teams2025.forEach((team,index)=>{
    teamsT.innerHTML+=`
    <tr>
    <td>$(index+1)</td>
    <td>$(team.team)</td>
    </tr>
     `;
 });
 