<?php

namespace App\Mail;

use App\Models\Checklist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendChecklistEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $checklist;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Checklist $checklist)
    {
        $this->checklist = $checklist;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = $this->checklist->generatePdf();

        return $this->subject($this->checklist->checklist_type->name . ' - ' . __('backpack.checklists.new'))
            ->with(['checklist' => $this->checklist])
            ->markdown('tenant.'.app()->tenant->name.'.email.send_checklist')
            ->attachData($pdf->output(), "checklist.pdf");
    }
}
