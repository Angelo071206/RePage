document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = {
        nome: document.getElementById('nome').value,
        cognome: document.getElementById('cognome').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };

    // Esegui la richiesta fetch per inviare i dati al server
    fetch('api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        // Verifica se la risposta è OK (200-299)
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
        } else if (data.error) {
            messageElement.innerText = data.error;
            messageElement.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        const messageElement = document.getElementById('message');
        messageElement.innerText = 'Si è verificato un errore durante la registrazione.';
        messageElement.style.color = 'red';
    });
});
