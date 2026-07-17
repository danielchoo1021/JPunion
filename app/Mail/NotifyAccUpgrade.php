<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAccUpgrade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $top_message, $f_name, $email, $lvlEN, $lvlCN, $upgradeDate;
    public function __construct($top_message, $f_name, $email, $lvlEN, $lvlCN, $upgradeDate)
    {
        $this->top_message = $top_message;
        $this->f_name = $f_name;
        $this->email = $email;
        $this->lvlEN = $lvlEN;
        $this->lvlCN = $lvlCN;
        $this->upgradeDate = $upgradeDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.account_upgraded')->subject("Account Notification");
    }
}
