<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser votre commande</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: #FFA500;
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
                    <h3 class="text-center mb-0">Finaliser votre commande</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Récapitulatif du panier -->
                    @php
                        $cart = session()->get('cart', []);
                        $total = 0;
                    @endphp

                    @if(count($cart) > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Récapitulatif de votre panier</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Prix</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cart as $id => $details)
                                            @php
                                                $itemTotal = $details['price'] * $details['quantity'];
                                                $total += $itemTotal;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                        <span>{{ $details['name'] }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($details['price'], 0, ',', ' ') }} CFA</td>
                                                <td>{{ $details['quantity'] }}</td>
                                                <td>{{ number_format($itemTotal, 0, ',', ' ') }} CFA</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total :</td>
                                            <td class="fw-bold fs-5">{{ number_format($total, 0, ',', ' ') }} CFA</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de commande -->
                        <form action="{{ route('orders.processCart') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Méthode de paiement</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="especes" checked>
                                    <label class="form-check-label" for="payment_cash">
                                        Paiement en espèces
                                    </label>
                                </div>
                                <!-- Vous pouvez ajouter d'autres méthodes de paiement ici si nécessaire -->
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary btn-custom">
                                    <i class="fas fa-arrow-left"></i> Retour au panier
                                </a>

                                <button type="submit" class="btn btn-success btn-custom">
                                    <i class="fas fa-check-circle"></i> Confirmer la commande
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info text-center">
                            <h5>Votre panier est vide !</h5>
                            <a href="{{ route('burgers.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-burger"></i> Découvrir nos burgers
                            </a>
                        </div>
                    @endif
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
