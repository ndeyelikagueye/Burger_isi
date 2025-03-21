<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Burger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
            color: #333;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150vh;
            padding-top: 30px;
        }
        .title-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-container h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #ff9800;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .details p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .details strong {
            color: #555;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        a.btn {
            display: block;
            margin-top: 20px;
            background-color: #17a2b8;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: 0.3s;
        }
        a.btn:hover {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
<div class="title-container">
    <h1>🍔 Burgers</h1>
</div>
<div class="container">
    <h2>Détails du Burger</h2>
    <div class="details">
        <p><strong>Nom:</strong> {{ $burger->name }}</p>
        <p><strong>Prix:</strong> {{ $burger->price }} CFA</p>
        <p><strong>Description:</strong> {{ $burger->description }}</p>
        <p><strong>Stock:</strong> {{ $burger->stock }}</p>
        @if($burger->image)
            <img src="{{ asset('storage/' . $burger->image) }}" alt="{{ $burger->name }}">
        @endif
    </div>
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('burgers.index') }}" class="btn">Retour à la liste</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
