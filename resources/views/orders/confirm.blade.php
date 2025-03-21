<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }
        .btn-custom {
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
        .card-header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            line-height: 1.6;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="text-center mb-0">Confirmation de Commande</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($order->email_sent)
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-envelope me-2"></i> Un e-mail de confirmation a été envoyé à {{ Auth::user()->email }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Votre commande #{{ $order->id }} a été enregistrée</h4>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Récapitulatif de la commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->burger->name }}</td>
                                            <td>{{ number_format($item->price, 0, ',', ' ') }} CFA</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} CFA</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total :</td>
                                        <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', ' ') }} CFA</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Email :</strong> {{ Auth::user()->email }}</p>
                                    <p class="mb-2"><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Statut :</strong>
                                        <span class="badge bg-warning">En attente de paiement</span>
                                    </p>
                                    <p class="mb-2"><strong>Méthode de paiement :</strong> {{ $order->payment_method ?? 'Espèces' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('burgers.index') }}" class="btn btn-outline-secondary btn-custom">
                            <i class="fas fa-arrow-left"></i> Continuer les achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
