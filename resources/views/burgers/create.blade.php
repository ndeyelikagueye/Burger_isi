<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Burger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-4 fw-bold text-warning">🍔 Burgers</h1>
</div>
<div class="container">
    <h1>Créer un Burger</h1>
    <form action="{{ route('burgers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Nom:</label>
        <input type="text" name="name" required>

        <label for="price">Prix:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="image">Image:</label>
        <input type="file" name="image" accept="image/*">

        <label for="description">Description:</label>
        <textarea name="description"></textarea>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" required>

        <button type="submit">Créer</button>
    </form>
</div>
</body>
</html>
