<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyWithdrawalEN extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $top_message, $code, $f_name, $email, $balance;
    public function __construct($top_message, $code, $f_name, $email, $balance)
    {
        $this->top_message = $top_message;
        $this->code = $code;
        $this->f_name = $f_name;
        $this->email = $email;
        $this->balance = $balance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.balance_withdrew_en')->subject("Withdrawal Notification");
    }
}
