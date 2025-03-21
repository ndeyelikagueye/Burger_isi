
    <div class="container">
        <div class="text-center my-5">
            <i class="fa fa-check-circle text-success" style="font-size: 5rem;"></i>
            <h2 class="my-3">Commande confirmée !</h2>
            <p class="lead">Merci pour votre commande. Un récapitulatif a été envoyé à votre adresse email.</p>
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Voir mes commandes</a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Retour à l'accueil</a>
            </div>
        </div>
    </div>
