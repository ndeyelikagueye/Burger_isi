<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #FFA500;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-summary {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 15px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Confirmation de Commande</h1>
</div>
<div class="content">
    <p>Bonjour {{ $order->user->name }},</p>

    <p>Nous vous remercions pour votre commande. Voici un récapitulatif des détails :</p>

    <div class="order-details">
        <p><strong>Numéro de commande :</strong> #{{ $order->id }}</p>
        <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method ?? 'Espèces') }}</p>
    </div>

    <div class="order-summary">
        <h3>Récapitulatif de commande</h3>
        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['price'], 0, ',', ' ') }} CFA</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} CFA</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="total">
            Total : {{ number_format($totalAmount, 0, ',', ' ') }} CFA
        </div>
    </div>

    <p>Nous préparons votre commande avec soin et nous vous informerons dès qu'elle sera prête.</p>

    <p>Merci pour votre confiance !</p>

    <p>Cordialement,<br>
        L'équipe ISI BURGER</p>
</div>

<div class="footer">
    <p>© {{ date('Y') }} ISI BURGER - Tous droits réservés</p>
</div>
</body>
</html>
