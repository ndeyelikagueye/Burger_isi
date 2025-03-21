<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue à Isi Burger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-warning-animation {
            animation: colorChange 5s infinite;
        }

        @keyframes colorChange {
            0% { background-color: #f0ad4e; }
            50% { background-color: #d9534f; }
            100% { background-color: #f0ad4e; }
        }

        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .btn-custom {
            font-size: 1.5rem;
            padding: 15px 30px;
            background-color: #f0ad4e;
            color: white;
            border-radius: 25px;
        }

        .btn-custom:hover {
            background-color: #d9534f;
        }
    </style>
</head>
<body class="bg-warning-animation">
<div class="welcome-container">
    <div>
        <h1 class="display-3 text-dark">Bienvenue à Isi Burger !</h1>
        <img src="https://example.com/burger-image.jpg" alt="Burger" class="img-fluid" style="width: 250px;">
        <br><br>
        <a href="{{ route('home') }}" class="btn btn-custom">Découvrir</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
