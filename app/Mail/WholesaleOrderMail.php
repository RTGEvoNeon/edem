<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\WholesaleProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WholesaleOrderMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param  array{name: string, phone: string, notes: string}  $customer
     */
    public function __construct(
        public readonly WholesaleProduct $product,
        public readonly int $quantity,
        public readonly float $pricePerUnit,
        public readonly float $total,
        public readonly array $customer,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новая оптовая заявка: '.$this->product->name.', '.number_format($this->quantity, 0, '', ' ').' шт',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.wholesale_order');
    }
}
