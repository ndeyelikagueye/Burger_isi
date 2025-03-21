<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Orders;

class OrderStatusUpdated extends Mailable
{
    use Queueable;

    public $order;
    public $orderNumber;
    public $orderStatus;
    public $totalAmount;
    public $orderDate;

    /**
     * Create a new message instance.
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;
        $this->orderNumber = $order->id;
        $this->orderStatus = $this->getStatusLabel($order->status);
        $this->totalAmount = number_format($order->total_amount, 2, ',', ' ') . ' CFA';
        $this->orderDate = $order->created_at->format('d/m/Y H:i');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour du statut de votre commande #' . $this->orderNumber,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
            with: [
                'orderNumber' => $this->orderNumber,
                'orderStatus' => $this->orderStatus,
                'totalAmount' => $this->totalAmount,
                'orderDate' => $this->orderDate,
            ]
        );
    }

    /**
     * Convertir le statut technique en libellé lisible
     */
    private function getStatusLabel($status)
    {
        $statuses = [
            'en_attente' => 'En attente',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'payee' => 'Payée'
        ];

        return $statuses[$status] ?? $status;
    }
}
