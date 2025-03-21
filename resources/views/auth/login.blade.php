<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-warning shadow-lg">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="fas fa-sign-in-alt"></i> Connexion</h4>
                </div>
                <div class="card-body">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-warning">Email</label>
                            <input id="email" type="email" name="email" class="form-control border-warning" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-warning" />
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-warning">Mot de passe</label>
                            <input id="password" type="password" name="password" class="form-control border-warning" required>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-warning" />
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input border-warning" name="remember">
                            <label for="remember_me" class="form-check-label text-warning">Se souvenir de moi</label>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between align-items-center">
                            @if (Route::has('password.request'))
                                <a class="text-warning text-decoration-none" href="{{ route('password.request') }}">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
