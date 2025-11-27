<?php

namespace App\Mail;

use App\Models\Incidences;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendIncidenceEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $incidence;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Incidences $incidence)
    {
        $this->incidence = $incidence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = $this->incidence->generatePdf();

        $message = $this->subject($this->incidence->title . ' - ' . __('backpack.incidences.new'))
            ->with(['incidence' => $this->incidence])
            ->markdown('tenant.' . app()->tenant->name . '.email.send_incidence')
            ->attachData($pdf->output(), 'Incidencia.pdf');

        return $message;
    }
}
