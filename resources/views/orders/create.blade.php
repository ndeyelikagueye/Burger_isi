<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .confirmation-message {
            display: none;
            text-align: center;
        }
        .confirmation-message i {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Confirmation de Commande</h1>

    <!-- Formulaire de validation de commande -->
    <form id="orderForm">
        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="total_amount">Montant Total</label>
        <input type="text" name="total_amount" value="6000 CFA" readonly>

        <button type="submit">Valider la Commande</button>
    </form>

    <!-- Formulaire de paiement en espèces -->
    <form id="cashPaymentForm" style="display: none;">
        <label for="amount">Montant à Payer</label>
        <input type="number" name="amount" value="6000" required>

        <button type="button" id="payCashBtn">Payer en Espèces</button>
    </form>

    <!-- Message de confirmation -->
    <div class="confirmation-message">
        <i class="fa fa-check-circle"></i>
        <h2>Commande confirmée !</h2>
        <p>Merci pour votre commande. Un récapitulatif a été envoyé à votre adresse email.</p>
        <a href="/burgers"><button >Retour à l'accueil</button></a>
    </div>
</div>

<script>
    // Soumettre le formulaire de commande
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher le rechargement de la page

        // Afficher le message de confirmation et cacher le formulaire
        document.querySelector('.confirmation-message').style.display = 'block';
        document.getElementById('orderForm').style.display = 'none';

        // Afficher le formulaire de paiement en espèces
        document.getElementById('cashPaymentForm').style.display = 'block';
    });

    // Soumettre le paiement en espèces
    document.getElementById('payCashBtn').addEventListener('click', function() {
        var formData = new FormData();
        formData.append('amount', document.querySelector('input[name="amount"]').value);

        // Simuler l'envoi de données de paiement via AJAX (en utilisant fetch)
        fetch('/pay/cash', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ajouter le token CSRF
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.message === "Payment processed successfully") {
                    alert('Paiement effectué avec succès !');
                    document.querySelector('.confirmation-message').style.display = 'block';
                    document.getElementById('cashPaymentForm').style.display = 'none';
                } else {
                    alert('Une erreur est survenue lors du paiement.');
                }
            })
            .catch(error => {
                alert('Erreur de communication avec le serveur.');
            });
    });

    // Retourner à l'accueil
    document.getElementById('backToHome').addEventListener('click', function() {
        window.location.href = '/'; // Redirige vers la page d'accueil
    });
</script>
</body>
</html>
