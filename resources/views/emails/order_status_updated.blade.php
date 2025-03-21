<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour de votre commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            margin-top: 20px;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Mise à jour de votre commande</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $order->user->name }},</p>

        <p>Nous vous informons que le statut de votre commande a été mis à jour.</p>

        <div class="order-details">
            <h2>Détails de la commande</h2>
            <p><strong>Numéro de commande :</strong> {{ $orderNumber }}</p>
            <p><strong>Date de commande :</strong> {{ $orderDate }}</p>
            <p><strong>Montant total :</strong> {{ $totalAmount }}</p>
            <p><strong>Statut actuel :</strong> {{ $orderStatus }}</p>
        </div>

        <p>Merci pour votre confiance.</p>

        <p>Cordialement,<br>L'équipe ISI Burger</p>
    </div>

    <div class="footer">
        <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
    </div>
</div>
</body>
</html>
