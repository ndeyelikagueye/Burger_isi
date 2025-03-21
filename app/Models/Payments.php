<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    // Constantes pour les méthodes de paiement
    const PAYMENT_METHOD_CASH = 'especes';
    const PAYMENT_METHOD_CARD = 'carte';

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_date'
    ];

    /**
     * Liste des méthodes de paiement disponibles
     */
    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_METHOD_CASH => 'Espèces',
            self::PAYMENT_METHOD_CARD => 'Carte bancaire'
        ];
    }

    /**
     * Crée un paiement en espèces
     */
    public static function createCashPayment($orderId, $amount)
    {
        return self::create([
            'order_id' => $orderId,
            'amount' => $amount,
            'payment_method' => self::PAYMENT_METHOD_CASH,
            'payment_date' => now(),
        ]);
    }

    /**
     * Relation avec la commande
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
