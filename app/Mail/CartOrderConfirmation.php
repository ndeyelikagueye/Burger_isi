<?php

namespace App\Mail;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cartItems;
    public $totalAmount;

    /**
     * Create a new message instance.
     *
     * @param Orders $order
     * @param array $cartItems
     * @param float $totalAmount
     * @return void
     */
    public function __construct(Orders $order, array $cartItems, $totalAmount)
    {
        $this->order = $order;
        $this->cartItems = $cartItems;
        $this->totalAmount = $totalAmount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmation de votre commande #' . $this->order->id)
            ->view('emails.order-confirmation');
    }
}
