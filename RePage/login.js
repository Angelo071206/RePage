document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    // Eseguo la richiesta fetch per inviare i dati al server
    fetch('api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        // Verifico la risposta
        if (!response.ok) {
            throw new Error('Errore nella risposta del server');
        }
        return response.json(); // Converte la risposta in JSON
    })
    .then(data => {
        // Mostra il messaggio in base alla risposta
        const messageElement = document.getElementById('message');
        if (data.message) {
            messageElement.innerText = data.message;
            messageElement.style.color = 'green';
            // Eventualmente salva i dati dell'utente nel localStorage o sessionStorage
            sessionStorage.setItem('user', JSON.stringify(data.user));
        } else if (data.error) {
            messageElement.innerText = data.error;
            messageElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        const messageElement = document.getElementById('message');
        messageElement.innerText = 'Si è verificato un errore durante il login.';
        messageElement.style.color = 'red';
    });
});
