<?php

namespace App\Mail;

use App\Models\Communication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCommunicationEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $communication;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Communication $communication)
    {
        $this->communication = $communication;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->communication->title)
            ->with(['communication' => $this->communication])
            ->markdown('tenant.'.app()->tenant->name.'.email.send_communication');
    }
}
