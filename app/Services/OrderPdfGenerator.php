<?php

namespace App\Services;

use App\Models\Orders;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderPdfGenerator
{
    /**
     * Génère un PDF pour la commande spécifiée
     *
     * @param Orders $order
     * @return \Illuminate\Http\Response|string
     */
    public function generatePdf(Orders $order)
    {
        try {
            // Charger les relations nécessaires s'ils ne sont pas déjà chargés
            if (!$order->relationLoaded('items')) {
                $order->load('items.burger', 'user');
            }

            // Créer le PDF
            $pdf = PDF::loadView('pdf.order', [
                'order' => $order,
                'user' => $order->user,
                'items' => $order->items,
                'date' => now()->format('d/m/Y H:i')
            ]);

            // Définir les options du PDF (facultatif)
            $pdf->setPaper('a4');

            // Retourner le PDF pour téléchargement
            return $pdf->download('commande-' . $order->id . '.pdf');

            // Alternativement, pour le visualiser dans le navigateur :
            // return $pdf->stream('commande-' . $order->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF', [
                'message' => $e->getMessage(),
                'order_id' => $order->id
            ]);

            throw $e;
        }
    }
    /**
     * Génère le contenu brut du PDF pour la commande spécifiée
     *
     * @param Orders $order
     * @return string|null
     */
    public function generatePdfContent(Orders $order)
    {
        try {
            // Charger les relations nécessaires
            if (!$order->relationLoaded('items')) {
                $order->load('items.burger', 'user');
            }

            // Créer le PDF
            $pdf = PDF::loadView('pdf.order', [
                'order' => $order,
                'user' => $order->user,
                'items' => $order->items,
                'date' => now()->format('d/m/Y H:i')
            ]);

            return $pdf->output();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du contenu PDF', [
                'message' => $e->getMessage(),
                'order_id' => $order->id
            ]);

            return null;
        }
    }
}
