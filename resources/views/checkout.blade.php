<form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="payment_method">Méthode de paiement</label>
        <select name="payment_method" id="payment_method" class="form-control" required>
            <option value="carte">Carte bancaire</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="especes">Espèces à la livraison</option>
        </select>
    </div>

    <!-- Ajoutez d'autres champs ici si nécessaire -->

    <button type="submit" class="btn btn-primary">Passer commande</button>
</form>
