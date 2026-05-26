document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const loginStatus = document.getElementById('loginStatus');

    loginForm?.addEventListener('submit', async event => {
        event.preventDefault();

        const username = loginForm.querySelector('#username').value.trim();
        const password = loginForm.querySelector('#password').value.trim();

        if (!username || !password) {
            loginStatus.textContent = 'Prosím vyplň všechna pole.';
            loginStatus.style.color = '#ff6b6b';
            return;
        }

        loginStatus.textContent = 'Přihlašuji...';
        loginStatus.style.color = '#ccc';

        try {
            const response = await fetch('./php/login-api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password }),
            });

            const data = await response.json();

            if (!response.ok || data.error) {
                loginStatus.textContent = data.error || 'ERROR.';
                loginStatus.style.color = '#ff6b6b';
                return;
            }

            loginStatus.textContent = 'Úspěšně přihlášen! Přesměrovávám...';
            loginStatus.style.color = '#4ade80';

            setTimeout(() => {
                const role = data.role || (username === 'databasemaster' ? 'databasemaster' : 'admin');
                if (role === 'databasemaster') {
                    window.location.href = './database.php';
                } else {
                    window.location.href = './admin.php';
                }
            }, 800);
        } catch (error) {
            loginStatus.textContent = 'Chyba při přihlášení: ' + error.message;
            loginStatus.style.color = '#ff6b6b';
        }
    });
});
