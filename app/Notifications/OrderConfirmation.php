<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Orders;
use Illuminate\Support\Facades\Log;

class OrderConfirmation extends Notification
{
    use Queueable;

    public $order;

    /**
     * Crée une nouvelle instance de notification.
     *
     * @param Orders $order
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    /**
     * Détermine les canaux de diffusion de la notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construit le message de l'e-mail.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /**
     * @override
     */
    public function toMail($notifiable)
    {
        try {
            // Vérification de l'existence des items
            if (!$this->order->items || count($this->order->items) === 0) {
                Log::warning('Commande sans items détectée', [
                    'order_id' => $this->order->id,
                    'user_id' => $this->order->user_id
                ]);

                // Utiliser le montant total stocké dans la commande
                return (new MailMessage)
                    ->subject('Confirmation de votre commande #' . $this->order->id)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Merci d\'avoir passé commande sur ISI Burger.')
                    ->line('Votre commande #' . $this->order->id . ' a été reçue et est en cours de traitement.')
                    ->line('Montant total : ' . number_format($this->order->total_amount, 0, ',', ' ') . ' F CFA')
                    ->action('Voir ma commande', url('/orders/' . $this->order->id))
                    ->line('Nous vous informerons lorsque votre commande sera prête.');
            }

            // Calcul du montant total à partir des items
            $total = 0;
            foreach ($this->order->items as $item) {
                $total += $item->price * $item->quantity;
            }

            return (new MailMessage)
                ->subject('Confirmation de votre commande #' . $this->order->id)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Merci d\'avoir passé commande sur ISI Burger.')
                ->line('Votre commande #' . $this->order->id . ' a été reçue et est en cours de traitement.')
                ->line('Montant total : ' . number_format($total, 0, ',', ' ') . ' F CFA')
                ->action('Voir ma commande', url('/orders/' . $this->order->id))
                ->line('Nous vous informerons lorsque votre commande sera prête.');
        } catch (\Exception $e) {
            Log::error('Erreur dans OrderConfirmation::toMail', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $this->order->id
            ]);

            // Email de secours en cas d'erreur
            return (new MailMessage)
                ->subject('Confirmation de votre commande #' . $this->order->id)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Votre commande a bien été reçue et est en cours de traitement.')
                ->line('Merci d\'avoir choisi ISI Burger!');
        }
    }
}
