<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function deleting(Invoice $invoice)
    {
        foreach ($invoice->concepts as $concept) {
            $concept->delete();
        }
    }

}
