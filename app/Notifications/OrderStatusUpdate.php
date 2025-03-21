<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Orders;
use Illuminate\Support\Facades\Log;
use App\Services\OrderPdfGenerator;  // Ajoutez cet import

class OrderStatusUpdate extends Notification
{
    use Queueable;

    public $order;
    public $oldStatus;

    /**
     * Crée une nouvelle instance de notification.
     *
     * @param Orders $order
     * @param string $oldStatus
     */
    public function __construct(Orders $order, string $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
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
    public function toMail($notifiable)
    {
        try {
            $statusMessages = [
                'en_attente' => 'en attente de traitement',
                'en_preparation' => 'en cours de préparation',
                'prete' => 'prête à être retirée',
                'payee' => 'payée et complétée'
            ];

            $currentStatus = $statusMessages[$this->order->status] ?? $this->order->status;

            $message = (new MailMessage)
                ->subject('Mise à jour de votre commande #' . $this->order->id)
                ->greeting('Bonjour ' . $notifiable->name . ',');

            // Message spécifique selon le statut
            switch ($this->order->status) {
                case 'en_preparation':
                    $message->line('Bonne nouvelle! Nos cuisiniers ont commencé à préparer votre commande.');
                    break;
                case 'prete':
                    $message->line('Votre commande est maintenant prête à être retirée!')
                        ->line('Vous pouvez venir la récupérer à notre restaurant.');
                    break;
                case 'payee':
                    $message->line('Nous confirmons que votre commande a été payée et complétée.')
                        ->line('Merci pour votre achat et à bientôt chez ISI Burger!');
                    break;
                default:
                    $message->line('Le statut de votre commande a été mis à jour.');
            }

            $message->line('Votre commande est maintenant ' . $currentStatus . '.')
                ->action('Voir ma commande', url('/orders/' . $this->order->id))
                ->line('Montant total : ' . number_format($this->order->total_amount, 0, ',', ' ') . ' F CFA');

            // Joindre le PDF uniquement pour les statuts pertinents
            if (in_array($this->order->status, ['prete', 'payee'])) {
                try {
                    // Générer et joindre le PDF
                    $pdfGenerator = new OrderPdfGenerator();
                    $pdfContent = $pdfGenerator->generatePdfContent($this->order);

                    if ($pdfContent) {
                        $message->attachData(
                            $pdfContent,
                            'commande-' . $this->order->id . '.pdf',
                            ['mime' => 'application/pdf']
                        );

                        $message->line('Vous trouverez en pièce jointe le récapitulatif de votre commande.');
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur lors de la génération du PDF pour l\'email', [
                        'message' => $e->getMessage(),
                        'order_id' => $this->order->id
                    ]);
                    // Continuer sans la pièce jointe en cas d'erreur
                }
            }

            return $message;

        } catch (\Exception $e) {
            Log::error('Erreur dans OrderStatusUpdate::toMail', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $this->order->id
            ]);

            // Email de secours en cas d'erreur
            return (new MailMessage)
                ->subject('Mise à jour de votre commande #' . $this->order->id)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Le statut de votre commande a été mis à jour.')
                ->action('Voir ma commande', url('/orders/' . $this->order->id));
        }
    }
}
