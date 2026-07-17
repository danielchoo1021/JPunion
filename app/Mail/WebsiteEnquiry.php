<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebsiteEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $top_message, $name, $from_email, $message_content, $country_code, $phone;
    public function __construct($top_message, $name, $from_email, $message_content, $country_code, $phone)
    {
        $this->top_message = $top_message;
        $this->name = $name;
        $this->from_email = $from_email;
        $this->message_content = $message_content;
        $this->country_code = $country_code;
        $this->phone = $phone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.website_enquiry')->subject("Website Enquiry");
    }
}
