<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $transaction, $transaction_details, $buyer_details, $upline_details;
    public function __construct($transaction, $transaction_details, $buyer_details, $upline_details)
    {
        $this->transaction = $transaction;
        $this->transaction_details = $transaction_details;
        $this->buyer_details = $buyer_details;
        $this->upline_details = $upline_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new_order_notification')->subject("Order Notification");
    }
}
