<?php

namespace App\Services;

use App\Models\Person;
use App\Models\MarketGroup;
use App\Models\Sector;
use PDF;

class PersonService
{
    public static function getAccreditation(Person $person)
    {
        $stallsByMarket = $person->historicals()->pivotActiveTitular()->with('auth_prods')->get()
            ->groupBy('market_id');

        $pdf = PDF::loadView('tenant.' . app()->tenant->name . '.person.pdf', compact('person', 'stallsByMarket'))
            ->setPaper('a5', 'landscape');

        return $pdf;
    }
}
