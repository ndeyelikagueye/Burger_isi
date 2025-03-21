<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Panier</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .cart-container {
            max-width: 900px;
            margin: 50px auto;
        }
    </style>
</head>
<body>

<div class="container cart-container py-5">
    <h1 class="mb-4 text-center text-warning fw-bold">🛒 Mon Panier</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $cart = session()->get('cart', []);
        $total = 0;
    @endphp

    @if(count($cart) > 0)
        <div class="table-responsive shadow-lg p-4 bg-white rounded">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" width="80" class="rounded">
                        </td>
                        <td class="fw-bold">{{ $details['name'] }}</td>
                        <td class="text-success fw-bold">{{ $details['price'] }} CFA</td>
                        <td>
                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="form-control form-control-sm text-center me-2" style="width: 60px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-sync-alt"></i></button>
                            </form>
                        </td>
                        <td class="fw-bold">{{ $details['price'] * $details['quantity'] }} CFA</td>
                        <td>
                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="table-warning">
                    <td colspan="4" class="text-end fw-bold fs-5">Total :</td>
                    <td colspan="2" class="fw-bold fs-5">{{ $total }} CFA</td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('cart.clear') }}" class="btn btn-outline-secondary">
                <i class="fas fa-trash"></i> Vider le panier
            </a>


            <!-- Ajouter des champs cachés si nécessaire -->
            <input type="hidden" name="burger_id" value="{{ $id }}">
            <input type="hidden" name="quantity" value="{{ $details['quantity'] }}">
            <input type="hidden" name="payment_method" value="cash"> <!-- Exemple de méthode de paiement -->
                <a href="cart/checkout" class="btn btn-outline-secondary">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Commander
            </button>
                </a>

        </div>
    @else
        <div class="alert alert-info text-center">
            <h5>Votre panier est vide !</h5>
            <a href="{{ route('burgers.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-cart"></i> Continuer vos achats
            </a>
        </div>
    @endif
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
