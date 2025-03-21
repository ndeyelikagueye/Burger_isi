<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',

        'payment_date'
    ];

    // Constantes pour les statuts de commande
    const STATUS_EN_ATTENTE = 'en_attente';
    const STATUS_EN_PREPARATION = 'en_preparation';
    const STATUS_PRETE = 'prete';
    const STATUS_PAYEE = 'payee';

    /**
     * Liste des statuts disponibles avec leurs libellés
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_EN_PREPARATION => 'En préparation',
            self::STATUS_PRETE => 'Prête',
            self::STATUS_PAYEE => 'Payée'
        ];
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = self::getStatusLabels();
        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les éléments de la commande
     */
    public function items()
    {
        return $this->hasMany(Order_Items::class, 'order_id');
    }

    /**
     * Relation avec le paiement
     */
    public function payment()
    {
        return $this->hasOne(Payments::class, 'order_id');
    }
}
