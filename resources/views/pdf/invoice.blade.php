<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture ISI Burger - Commande #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-summary {
            text-align: right;
            margin-top: 20px;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="invoice-header">
        <h1>ISI BURGER</h1>
        <h2>Facture de Commande</h2>
    </div>

    <div class="invoice-details">
        <p><strong>Numéro de commande :</strong> {{ $order->id }}</p>
        <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Client :</strong> {{ $order->user->name }}</p>
        <p><strong>Email :</strong> {{ $order->user->email }}</p>
    </div>

    <table class="invoice-items">
        <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix Unitaire</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->burger->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }} €</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="invoice-summary">
        <p><strong>Total de la commande :</strong> {{ number_format($order->total_amount, 2) }} €</p>
        <p><strong>Méthode de paiement :</strong> {{ $order->payment_method }}</p>
    </div>
</div>
</body>
</html>
