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
        const messageElement = document.getElementById('message');
        console.log(data); // Aggiungi un log per controllare i dati ricevuti

        if (data.message) {
            messageElement.innerText = data.message;
            messageElement.style.color = 'green';

            // Dopo 1 secondo torna alla homepage
            setTimeout(() => {
                console.log("Reindirizzando alla homepage..."); // Log per il reindirizzamento
                window.location.replace('Index.php'); 
            }, 1000); // 1 secondo di attesa prima del reindirizzamento

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
