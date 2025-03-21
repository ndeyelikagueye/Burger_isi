<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container py-5">
    <h1 class="display-5 fw-bold text-center mb-5">🍔 Détails de la Commande #{{ $order->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Produits commandés</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach($order->items as $item)
                            @php $itemTotal = $item->price * $item->quantity; $subtotal += $itemTotal; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->burger && $item->burger->image)
                                            <img src="{{ asset('storage/' . $item->burger->image) }}" alt="{{ $item->burger->name }}" class="me-3 rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <span>{{ $item->burger ? $item->burger->name : 'Produit indisponible' }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 0, ',', ' ') }} CFA</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-bold">{{ number_format($itemTotal, 0, ',', ' ') }} CFA</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total :</td>
                            <td class="fw-bold fs-5">{{ number_format($order->total_amount, 0, ',', ' ') }} CFA</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations de commande</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Date :</span>
                            <span class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Statut :</span>
                            <span>
                                    @if($order->status === 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($order->status === 'en_preparation')
                                    <span class="badge bg-info">En préparation</span>
                                @elseif($order->status === 'prete')
                                    <span class="badge bg-success">Prête</span>
                                @elseif($order->status === 'payee')
                                    <span class="badge bg-primary">Payée</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                                </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Client :</span>
                            <span class="fw-bold">{{ $order->user->name ?? 'Non spécifié' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Email :</span>
                            <span class="fw-bold">{{ $order->user->email ?? 'Non spécifié' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Méthode de paiement :</span>
                            <span class="fw-bold">{{ $order->payment_method ?? 'Non spécifiée' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($order->payment)
                <div class="card shadow-lg mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Détails du paiement</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Date :</span>
                                <span class="fw-bold">{{ $order->payment->payment_date ?? 'Non spécifié' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Méthode :</span>
                                <span class="fw-bold">{{ $order->payment->payment_method ?? 'Non spécifié' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Montant :</span>
                                <span class="fw-bold">{{ number_format($order->payment->amount, 0, ',', ' ') ?? '0' }} CFA</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('orders.my_orders') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à mes commandes
        </a>

        @if($order->status === 'en_attente')
            <form action="{{ route('orders.validate', $order->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Payer maintenant
                </button>
            </form>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
