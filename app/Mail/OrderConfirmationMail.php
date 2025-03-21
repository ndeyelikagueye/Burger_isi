<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Orders;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    private $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;
        $this->generatePDF();
    }

    /**
     * Générer le PDF de la facture
     */
    private function generatePDF()
    {
        // Charger les relations nécessaires
        $this->order->load(['user', 'items.burger']);

        // Créer le dossier des factures s'il n'existe pas
        $invoiceDirectory = storage_path('app/invoices');
        if (!file_exists($invoiceDirectory)) {
            mkdir($invoiceDirectory, 0755, true);
        }

        // Nom de fichier unique
        $filename = 'facture_commande_' . $this->order->id . '_' . time() . '.pdf';
        $this->pdfPath = $invoiceDirectory . '/' . $filename;

        // Générer le PDF
        $pdf = PDF::loadView('pdf.invoice', [
            'order' => $this->order
        ]);

        // Sauvegarder le PDF
        $pdf->save($this->pdfPath);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre commande - ISI_BURGER',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
            with: [
                'orderDetails' => $this->order
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('facture_commande_' . $this->order->id . '.pdf')
                ->withMime('application/pdf')
        ];
    }

    /**
     * Nettoyer le fichier PDF temporaire
     */
    public function __destruct()
    {
        if (file_exists($this->pdfPath)) {
            unlink($this->pdfPath);
        }
    }
}
