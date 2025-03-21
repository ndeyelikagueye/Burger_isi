<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Commande #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        h1 {
            color: #d35400;
            margin-bottom: 5px;
        }
        .order-info {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-items th, .order-items td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .order-items th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            background: #d35400;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ asset('images/logo.png') }}" class="logo" alt="ISI Burger Logo">
    <h1>ISI BURGER</h1>
    <p style="font-size: 16px; font-weight: bold;">Reçu de Commande</p>
</div>

<div class="order-info">
    <p><strong>Commande #:</strong> {{ $order->id }}</p>
    <p><strong>Date:</strong> {{ $date }}</p>
    <p><strong>Client:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Statut:</strong>
        @switch($order->status)
            @case('en_attente') En attente @break
            @case('en_preparation') En préparation @break
            @case('prete') Prête @break
            @case('payee') Payée @break
            @default {{ $order->status }}
        @endswitch
    </p>
    <p><strong>Méthode de paiement:</strong>
        @switch($order->payment_method)
            @case('especes') Espèces @break
            @case('carte') Carte bancaire @break
            @case('mobile_money') Mobile Money @break
            @default {{ $order->payment_method }}
        @endswitch
    </p>
</div>

<table class="order-items">
    <thead>
    <tr>
        <th>Produit</th>
        <th>Prix unitaire</th>
        <th>Quantité</th>
        <th>Sous-total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->burger->name ?? 'Produit #'.$item->burger_id }}</td>
            <td>{{ number_format($item->price, 0, ',', ' ') }} F CFA</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="total">
    <p>Total: {{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</p>
</div>

<div class="footer">
    <p>Merci d'avoir choisi <strong>ISI Burger</strong> !</p>
    <p>Pour toute question, contactez-nous à <a href="mailto:support@isiburger.com">support@isiburger.com</a></p>
</div>

</body>
</html>
