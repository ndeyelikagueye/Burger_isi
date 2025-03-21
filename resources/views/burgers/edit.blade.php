<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer un Burger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f9;
            color: #333;
            margin: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 130vh;
            padding-top: 0px;
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
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
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
<div class="title-container">
    <h1>🍔 Burgers</h1>
</div>
<div class="container">
    <h2>Modifie un Burger</h2>
    <form action="{{ route('burgers.update', $burger->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="name">Nom:</label>
        <input type="text" name="name" value="{{ old('name', $burger->name) }}" required>
        @error('name') <p style="color:red;">{{ $message }}</p> @enderror
        <label for="price">Prix:</label>
        <input type="number" name="price" step="0.01" value="{{ old('price', $burger->price) }}" required>
        @error('price') <p style="color:red;">{{ $message }}</p> @enderror
        <label for="image">Image:</label>
        @if($burger->image)
            <div>
                <img src="{{ asset('storage/' . $burger->image) }}" alt="Image du burger" width="150">
            </div>
        @endif
        <input type="file" name="image" accept="image/*">
        @error('image') <p style="color:red;">{{ $message }}</p> @enderror
        <label for="description">Description:</label>
        <textarea name="description">{{ old('description', $burger->description) }}</textarea>
        @error('description') <p style="color:red;">{{ $message }}</p> @enderror
        <label for="stock">Stock:</label>
        <input type="number" name="stock" value="{{ old('stock', $burger->stock) }}" required>
        @error('stock') <p style="color:red;">{{ $message }}</p> @enderror
        <button type="submit">Mettre à jour</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
