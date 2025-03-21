<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #fff3cd;
        }
        .card {
            border: 2px solid #ffc107;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-warning:hover {
            background-color: #e68900;
            border-color: #e68900;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <h1 class="mb-4 text-warning">Mes Commandes</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('burgers.index') }}" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Retour aux Burgers
            </a>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('cart.view') }}" class="btn btn-warning">
                <i class="fas fa-shopping-cart"></i> Voir mon Panier
                @if(session()->has('cart') && count(session()->get('cart')) > 0)
                    <span class="badge bg-danger">{{ count(session()->get('cart')) }}</span>
                @endif
            </a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center py-5">
            <h4>Vous n'avez pas encore de commandes</h4>
            <p>Découvrez nos délicieux burgers et passez votre première commande!</p>
            <a href="{{ route('burgers.index') }}" class="btn btn-warning mt-3">Voir les Burgers</a>
        </div>
    @else
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Commande #{{ $order->id }}</h5>
                            <span class="badge
                                @if($order->status == 'en_attente') bg-dark
                                @elseif($order->status == 'en_preparation') bg-primary
                                @elseif($order->status == 'prete') bg-success
                                @elseif($order->status == 'payee') bg-secondary
                                @else bg-danger
                                @endif">
                                @if($order->status == 'en_attente') En attente
                                @elseif($order->status == 'en_preparation') En préparation
                                @elseif($order->status == 'prete') Prête
                                @elseif($order->status == 'payee') Payée
                                @else Annulée
                                @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                <span><strong>Total:</strong> {{ $order->total_amount }} CFA</span>
                            </div>

                            <h6>Articles commandés</h6>
                            <ul class="list-group mb-3">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">{{ $item->burger->name }}</span>
                                            <small class="d-block text-muted">{{ $item->price }} CFA × {{ $item->quantity }}</small>
                                        </div>
                                        <span>{{ $item->price * $item->quantity }} CFA</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if(Auth::check() && Auth::user()->role === 'gestionnaire')
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-warning btn-sm">
                                    Voir les détails
                                </a>

                                @if($order->status == 'prete' && !$order->payment_date)
                                    <a href="{{ route('orders.confirm', $order->id) }}" class="btn btn-success btn-sm">
                                        Payer maintenant
                                    </a>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="card-footer text-muted">
                            @if($order->payment_date)
                                <small>Payée le: {{ \Carbon\Carbon::parse($order->payment_date)->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
