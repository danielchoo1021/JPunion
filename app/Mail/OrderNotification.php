<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $top_message, $t_details, $t_no;
    public function __construct($top_message, $t_details, $t_no)
    {
        $this->top_message = $top_message;
        $this->t_details = $t_details;
        $this->t_no = $t_no;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order_details_notification')->subject("Order Notification");
    }
}
