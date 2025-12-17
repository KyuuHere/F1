function minecraft(){
    const details = document.getElementById('roblox');
    const button = document.getElementById('Fortnite');

    if(details.classList.contains('hidden')){
        details.classList.remove('hidden');
        button.textContent="Skrýt";
    }
    else{
        details.classList.add('hidden');
        button.textContent="zobrazit";
    }
}