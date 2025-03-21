

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Burgers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        h1 {
            color: #ff9800;
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .button {
            display: block;
            padding: 10px 15px;
            margin: 20px auto;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            width: fit-content;
        }
        .alert {
            margin: 20px auto;
            width: 80%;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }
        .actions a, .actions button {
            margin: 5px;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
        .view {
            background-color: #17a2b8;
            color: white;
        }
        .edit {
            background-color: #ffc107;
            color: black;
        }
        .delete {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        .card {
            margin: 20px auto;
            width: 100%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px 5px 0 0;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            color: #666;
        }
        .btn-details {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-commande {
            background-color: #ffc107;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-details:hover {
            background-color: #138496;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-container form {
            gap: 5px;
            display: block;
            margin: 20px auto;
        }
        .form-container input, .form-container button {
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 fw-bold">🍔 Burgers</h1>

        <div>
            <!-- Boutons spécifiques aux gestionnaires -->
            @if(Auth::check() && Auth::user()->role === 'gestionnaire')
                <a href="{{ route('burgers.create') }}" class="btn btn-success btn-lg shadow-sm me-2">
                    <i class="fas fa-plus-circle"></i> Ajouter un Burger
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-warning btn-lg shadow-sm">
                    <i class="fas fa-list"></i> Gérer les Commandes
                </a>

            @endif

            <!-- Boutons spécifiques aux clients -->
            @if(Auth::check() && Auth::user()->role === 'client')
                <a href="{{ route('orders.my_orders') }}" class="btn btn-warning btn-lg shadow-sm me-2">
                    <i class="fas fa-shopping-bag"></i> Mes Commandes
                </a>


                <a href="{{ route('cart.view') }}" class="btn btn-info btn-lg shadow-sm">
                    <i class="fas fa-shopping-cart"></i> Mon Panier
                    @if(session()->has('cart') && count(session()->get('cart')) > 0)
                        <span class="badge bg-danger">{{ count(session()->get('cart')) }}</span>
                    @endif
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-lg shadow-sm me-2">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
            <!-- Boutons pour les utilisateurs non connectés -->
            @if(!Auth::check())
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg shadow-sm me-2">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
                <a href="{{ route('register') }}" class="btn btn-secondary btn-lg shadow-sm">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            @endif
        </div>
    </div>

    <!-- Gestion des Messages d'Erreur et de Succès -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulaire de filtre  -->
    <div class="container my-4">
        <form method="GET" action="{{ route('burgers.index') }}" class="d-flex align-items-center bg-light p-3 rounded shadow-sm">
            <input type="text" name="search" id="search" class="form-control me-2 border-0 shadow-sm"
                   placeholder="🔍 Rechercher un burger..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-warning text-white fw-bold px-4 shadow-sm">
                <i class="fas fa-search"></i> Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau des burgers - visible uniquement pour les gestionnaires -->
    @if(Auth::check() && Auth::user()->role === 'gestionnaire')
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($burgers as $burger)
                <tr>
                    <td>{{ $burger->name }}</td>
                    <td>{{ $burger->price }} CFA</td>
                    <td>{{ $burger->stock }}</td>
                    <td>{{ $burger->description }}</td>
                    <td><img src="{{ asset('storage/' . $burger->image) }}" alt="{{ $burger->name }}" style="max-width: 200px;"></td>
                    <td class="actions">
                        <a href="{{ route('burgers.show', $burger->id) }}" class="view">Voir</a>
                        <a href="{{ route('burgers.edit', $burger->id) }}" class="edit">Éditer</a>
                        <form action="{{ route('burgers.destroy', $burger->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce burger?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <!-- Liste des burgers en cartes (visible par tous) -->
    @if(Auth::check() && Auth::user()->role === 'client')
        <div class="row">
            @foreach ($burgers as $burger)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $burger->image) }}" alt="{{ $burger->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $burger->name }}</h5>
                            <p class="card-text">{{ $burger->price }} CFA</p>
                            <p class="card-text {{ $burger->stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $burger->stock > 0 ? 'En stock (' . $burger->stock . ')' : 'Rupture de stock' }}
                            </p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('burgers.show', $burger->id) }}">
                                    <button class="btn-details">Détails</button>
                                </a>

                                <!-- Bouton Commander (visible uniquement pour les clients) -->
                                @if(Auth::check() && Auth::user()->role === 'client' && $burger->stock > 0)
                                    <a href="{{ route('cart.add', $burger->id) }}">
                                        <button class="btn-commande"><i class="fas fa-shopping-cart"></i> Commander</button>
                                    </a>
                                @endif

                                <!-- Bouton Éditer (visible uniquement pour les gestionnaires) -->
                                @if(Auth::check() && Auth::user()->role === 'gestionnaire')
                                    <a href="{{ route('burgers.edit', $burger->id) }}">
                                        <button class="edit">Éditer</button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <!-- Message pour les visiteurs non connectés -->
    @if(!Auth::check())
        <div class="row">
            @foreach ($burgers as $burger)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $burger->image) }}" alt="{{ $burger->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $burger->name }}</h5>
                            <p class="card-text">{{ $burger->price }} CFA</p>
                            <p class="card-text {{ $burger->stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $burger->stock > 0 ? 'En stock (' . $burger->stock . ')' : 'Rupture de stock' }}
                            </p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('burgers.show', $burger->id) }}">
                                    <button class="btn-details">Détails</button>
                                </a>

                                <!-- Bouton Commander (visible uniquement pour les clients) -->
                                @if(Auth::check() && Auth::user()->role === 'client' && $burger->stock > 0)
                                    <a href="{{ route('cart.add', $burger->id) }}">
                                        <button class="btn-commande"><i class="fas fa-shopping-cart"></i> Commander</button>
                                    </a>
                                @endif

                                <!-- Bouton Éditer (visible uniquement pour les gestionnaires) -->
                                @if(Auth::check() && Auth::user()->role === 'gestionnaire')
                                    <a href="{{ route('burgers.edit', $burger->id) }}">
                                        <button class="edit">Éditer</button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="alert alert-info text-center mt-4">
            <h4>Vous souhaitez commander nos délicieux burgers?</h4>
            <p>Connectez-vous ou créez un compte pour pouvoir commander.</p>
            <div class="mt-3">
                <a href="{{ route('login') }}" class="btn btn-primary me-2">Se connecter</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Créer un compte</a>
            </div>
        </div>
    @endif
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBdR0P9S4B1T4z4klp/8d3Pj0z6M/kPms8FfvVYKfmz3hbaE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0p5MSaBgwQ/YYbhzYtoTyXr2BLiN6YlX/Xg4pPLPi5tV0P5B" crossorigin="anonymous"></script>

</body>
</html>

