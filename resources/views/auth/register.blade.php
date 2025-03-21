<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
        <h2 class="text-center text-warning"><i class="fas fa-user-plus"></i> Inscription</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nom -->
            <div class="mb-3">
                <label for="name" class="form-label text-warning">Nom</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                <x-input-error :messages="$errors->get('name')" class="text-danger mt-1" />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label text-warning">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="text-danger mt-1" />
            </div>

            <!-- Mot de passe -->
            <div class="mb-3 position-relative">
                <label for="password" class="form-label text-warning">Mot de passe</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <button class="btn btn-outline-warning" type="button" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" />
            </div>

            <!-- Confirmation du mot de passe -->
            <div class="mb-3 position-relative">
                <label for="password_confirmation" class="form-label text-warning">Confirmer le mot de passe</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    <button class="btn btn-outline-warning" type="button" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1" />
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('login') }}" class="text-warning text-decoration-none">Déjà inscrit ?</a>
                <button type="submit" class="btn btn-warning">S'inscrire</button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(id) {
        let input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
