<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\InvoiceConcept;

class Stall extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const TYPE_RENT = 'rent';
    const TYPE_CONCESSION = 'concession';

    protected $table = 'stalls';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'num',
        'length',
        'rate_id',
        'active',
        'market_id',
        'image',
        'market_group_id',
        'classe_id',
        'auth_prod_id',
        'type',
        'comment',
        'trimestral_1',
        'trimestral_2',
        'trimestral_3',
        'trimestral_4',
        'n_zones',
        'visible'
    ];
    protected $appends = ['titular', 'titular_id', 'titular_info', 'from', 'to', 'num_market', 'id_num'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getIfActive()
    {
        if ($this->hasActiveTitular()) {
            return __('backpack.yes');
        }

        return __('backpack.no');
    }

    public function getTitular()
    {
        return $this->historicals()->pivotActiveTitular()->first();
    }

    public function getFrom()
    {
        $data = null;
        $person = $this->historicals()->pivotActiveTitular()->first();
        if ($person) {
            $data = $person->pivot->start_at;
        }
        return $data;
    }

    public function getTo()
    {
        $data = null;
        $person = $this->historicals()->pivotActiveTitular()->first();
        if ($person) {
            $data = $person->pivot->ends_at;
        }
        return $data;
    }

    public function generateStallConcept($days, $invoice, $stall)
    {
        //Precio tarifa sin despeses
        $price = $this->getCalculatedPrice();

        //Calculamos el subtotal
        $subtotal = 0;

        //Miramos el tipo de tarifa, si es por dia, o fija
        if ($this->rate->rate_type == 'daily') {
            foreach ($days as $day) {
                $subtotal += $price;
            }
        } else {
            //La tarifa es fija, no se tiene en cuenta el numero de dias
            $subtotal = $price;
        }

        InvoiceConcept::create([
            'invoice_id' => $invoice,
            'stall_id' => $stall,
            'concept' => 'stall',
            'concept_type' => $this->rate->price_type,
            'type_rate' => $this->rate->rate_type,
            'length' => $this->length,
            'qty_days' => $days->count(),
            'price' => $this->rate->price,
            'subtotal' => $subtotal
        ]);

        //Devolvemos el subtotal que debe sumarse al total del invoice
        return $subtotal;
    }

    public function generateExpensesConcept($days, $invoice, $stall)
    {
        //Precio despeses
        $price = $this->getCalculatedPriceExpenses();

        //Calculamos el subtotal
        $subtotal = 0;

        //Miramos el tipo de tarifa, si es por dia, o fija
        if ($this->rate->rate_type == 'daily') {
            foreach ($days as $day) {
                $subtotal += $price;
            }
        } else {
            //La tarifa es fija, no se tiene en cuenta el numero de dias
            $subtotal = $price;
        }

        //Creamos solo si hay despeses
        if ($subtotal != 0) {
            InvoiceConcept::create([
                'invoice_id' => $invoice,
                'stall_id' => $stall,
                'concept' => 'expenses',
                'concept_type' => $this->rate->price_expenses_type,
                'type_rate' => $this->rate->rate_type,
                'length' => $this->length,
                'qty_days' => $days->count(),
                'price' => $this->rate->price_expenses,
                'subtotal' => $subtotal
            ]);
        }

        //Devolvemos el subtotal que debe sumarse al total del invoice
        return $subtotal;
    }

    public function generateBonusesConcept($from, $to, $days, $invoice, $stall)
    {

        $price_without_discount = $this->getPriceByDateRangeWithoutDiscount($from, $to, $days);

        $price_with_discount = $this->getPriceByDateRangeWithDiscount($from, $to, $days);

        $subtotal = $price_without_discount - $price_with_discount;

        //Creamos solo si hay bonuses
        if ($subtotal != 0) {
            InvoiceConcept::create([
                'invoice_id' => $invoice,
                'stall_id' => $stall,
                'length' => $this->length,
                'qty_days' => $days->count(),
                'concept' => 'bonuses',
                'concept_type' => 'fixed',
                'type_rate' => $this->rate->rate_type,
                'title' => isset($this->bonuses()->filterByDateRange($from, $to)->first()->title) ? $this->bonuses()->filterByDateRange($from, $to)->first()->title : NULL,
                'price' => $subtotal,
                'subtotal' => $subtotal
            ]);
        }

        //Devolvemos el subtotal que debe restarse al total del invoice
        return $subtotal;
    }

    public function getPriceByDateRangeWithoutDiscount($from, $to, $days)
    {
        //Precio tarifa
        $price_rate = $this->getCalculatedPrice();

        //Precio gastos
        $price_expenses = $this->getCalculatedPriceExpenses();

        //Price day
        $price = $price_rate + $price_expenses;

        $total = 0;

        //Miramos el tipo de tarifa, si es por dia, o fija
        if ($this->rate->rate_type == 'daily') {
            foreach ($days as $day) {
                $total += $price;
            }
        } else {
            //La tarifa es fija, no se tiene en cuenta el numero de dias
            $total = $price;
        }


        return $total;
    }



    public function getPriceByDateRangeWithDiscount($from, $to, $days)
    {
        //Precio tarifa
        $price_rate = $this->getCalculatedPrice();

        //Precio gastos
        $price_expenses = $this->getCalculatedPriceExpenses();

        //Price
        $price = $price_rate + $price_expenses;

        //Obtenemos los bonuses
        $bonuses = $this->bonuses()->filterByDateRange($from, $to)->get();

        $total = 0;

        //Si tiene algun tipo de bonus
        if (count($bonuses) > 0) {
            //Miramos el tipo de tarifa, si es por dia, o fija
            if ($this->rate->rate_type == 'daily') {
                foreach ($days as $day) {
                    $total += $this->getPriceForDate($bonuses, $day, $price, count($days));
                }
            } else {
                //La tarifa es fija, no se tiene en cuenta el numero de dias
                foreach ($bonuses as $bonus) {
                    $price -= $bonus->getPriceChanged($price, count($days));
                }
                $total = $price;
            }
        } else {
            //Miramos el tipo de tarifa, si es por dia, o fija
            if ($this->rate->rate_type == 'daily') {
                foreach ($days as $day) {
                    $total += $price;
                }
            } else {
                //La tarifa es fija, no se tiene en cuenta el numero de dias
                $total = $price;
            }
        }

        return $total;
    }

    public function getPriceForDate($bonuses, $date, $price, $market_days = null)
    {
        $filter_bonuses = $bonuses->filter(function ($bonus) use ($date) {
            return $bonus->start_at <= $date->date && $bonus->ends_at >= $date->date;
        });

        $result = $price;
        if ($filter_bonuses->count()) {
            foreach ($filter_bonuses as $bonus) {
                $descuento = $bonus->getPriceChanged($result, $market_days);
                $result -= $descuento;
                if ($result < 0) {
                    $result = 0;
                }
            }
        }
        return $result;
    }

    public function getCalculatedPrice()
    {
        switch ($this->rate->price_type) {
            case 'fixed':
                return $this->rate->price;
                break;
            case 'meters':
                return $this->length * $this->rate->price;
                break;
            default:
                # code...
                break;
        }
    }

    public function getCalculatedPriceExpenses()
    {
        switch ($this->rate->price_expenses_type) {
            case 'fixed':
                return $this->rate->price_expenses;
                break;
            case 'meters':
                return $this->length * $this->rate->price_expenses;
                break;
            default:
                # code...
                break;
        }
    }

    public function createInvoice($person, $stall, $month, $years, $qty_days, $price, $discount, $from, $to)
    {
        $invoice = Invoice::create([
            'person_id' => $person->id,
            'stall_id' => $stall->id,
            'month' => $month,
            'years' => $years,
            'qty_days' => $qty_days,
            'length' => $this->length,
            'price' => $this->getCalculatedPrice(),
            'expenses' => $this->getCalculatedPriceExpenses(),
            'subtotal' => $price + $discount,
            'discount' => $discount,
            'total' => $price
        ]);
        $bonuses = $this->bonuses()->filterByDateRange($from, $to)->pluck('id');
        $invoice->bonuses()->attach($bonuses);
    }

    /* public function concession()
    {
        if ($this->concessions->count()) {
            return '<a href="/admin/concession/' . $this->concessions->last()->id . '/edit" class="btn btn-sm btn-link" target="_blank"><i class="la la-edit"></i> ' . __('backpack.stalls.concession') . '</a>';
        }
    } */

    public function accreditation()
    {
        $person = $this->getTitular();
        if ($person) {
            return $person->accreditation();
        }
    }

    public function unsubscribe()
    {
        if ($this->hasActiveTitular()) {
            return '<a href="javascript:void(0)" class="btn btn-sm btn-link unsubscribe" attr-id="' . $this->id . '"><i class="la la-close"></i> ' . __('backpack.stalls.unsubscribe') . '</a>';
        }
    }

    public function subscribe()
    {
        if (!$this->hasActiveTitular()) {
            return '<a href="javascript:void(0)" class="btn btn-sm btn-link subscribe" attr-id="' . $this->id . '"><i class="la la-user-check"></i> ' . __('backpack.stalls.subscribe') . '</a>';
        }
    }

    public function certification()
    {
        if ($this->hasAccreditation()) {
            return '<a href="/admin/stall/certification/' . $this->id . '" class="btn btn-sm btn-link" target="_blank"><i class="la la-file"></i> ' . __('backpack.stalls.certification') . '</a>';
        }
    }

    public function createChecklist()
    {
        if ($this->hasActiveTitular()) {
            return '<a href="' . backpack_url("checklist/stall/select?stall={$this->id}") . '" class="btn btn-sm btn-link"><i class="la la-list-alt"></i> ' . __('backpack.stalls.create_checklist') . '</a>';
        }
    }

    public function activeTitularLink()
    {
        $titular = $this->getTitular();
        if (!$titular) {
            return '-';
        }

        // return '<b>'.$titular->name.'</b>';
        return '<a href="' . backpack_url('person/' .  $titular->id . '/show') . '" target="_blank">' . $titular->name . '</a>';
    }

    public function hasActiveTitular()
    {
        return $this->historicals()->pivotActiveTitular()->count();
    }

    public function unsubscribeTitular($request)
    {
        return $this->historicals()->pivotActiveTitular()->update([
            'ends_at' => $request->ends_at ?? Carbon::now()->toDateString(),
            'reason' => $request->reason ?? '',
            'explained_reason' => $request->explained_reason ?? '',
        ]);
    }

    public function hasAccreditation()
    {
        return $this->historicals()->pivotActiveTitular()->count();
    }

    public function authProdsList()
    {
        $auth_prods = $this->auth_prods;

        if ($auth_prods) {
            return  $auth_prods->pluck('name')->implode(', ');
        }

        return '';
    }

    public function sectorsList()
    {
        $auth_prod = $this->auth_prods()->with('sector')->get();

        if ($auth_prod) {
            return  $auth_prod->pluck('sector.name')->implode(', ');
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function marketGroup()
    {
        return $this->belongsTo(MarketGroup::class)->active();
    }

    public function historicals()
    {
        return $this->belongsToMany(Person::class, 'historicals')
            ->withPivot(['start_at', 'ends_at', 'end_vigencia', 'reason', 'family_transfer', 'explained_reason']);
    }

    public function incidences()
    {
        return $this->hasMany(Incidences::class);
    }

    /* public function concessions()
    {
        return $this->hasMany(Concession::class);
    } */

    public function rate()
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function extensions()
    {
        return $this->hasMany(Extension::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function auth_prods()
    {
        return $this->belongsToMany(AuthProd::class);
    }

    public function checklists()
    {
        return $this->morphMany(Checklist::class, 'origin');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilterByMarket($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            return $query->where('market_id', Cache::get('market' . auth()->user()->id));
        } else {
            return $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
        }
    }

    public function scopePresenceByDate($query, $date)
    {
        return $query->whereHas('historicals', function ($query) use ($date) {
            $query->whereDate('start_at', '<=', $date)
                ->whereDate('ends_at', '>=', $date)->orWhere('ends_at', NULL)
                ->whereDoesntHave('absences', function ($query) use ($date) {
                    $query->filterByDate($date);
                });
        })->whereHas('market.calendars', function ($query) use ($date) {
            $query->whereDate('date', $date);
        });
    }

    public function scopeActiveTitular($query)
    {
        return $query->whereHas('historicals', function ($query) {
            $query->whereNull('historicals.ends_at')->where(function ($query) {
                $query->whereDate('historicals.end_vigencia', '>', now())->orWhereNull('historicals.end_vigencia');
            });
        });
    }

    public function scopeNoActiveTitular($query)
    {
        return $query->whereDoesntHave('historicals', function ($query) {
            $query->whereNull('historicals.ends_at')->where(function ($query) {
                $query->whereDate('historicals.end_vigencia', '>', now())->orWhereNull('historicals.end_vigencia');
            });
        });
    }

    /**
     * Used with pivot historicals
     */
    public function scopePivotActiveTitular($query)
    {
        return $query->whereNull('historicals.ends_at')->where(function ($query) {
            $query->whereDate('historicals.end_vigencia', '>', now())->orWhereNull('historicals.end_vigencia');
        });
    }

    public function scopeVisible($query, $visible = true)
    {
        return $query->where('visible', $visible);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getTitularAttribute()
    {
        return $this->getTitular()->name ?? '-';
    }

    public function getTitularIdAttribute()
    {
        return $this->getTitular()->id ?? '';
    }

    public function getTitularInfoAttribute()
    {
        return $this->getTitular();
    }

    public function getFromAttribute()
    {
        return $this->getFrom();
    }

    public function getToAttribute()
    {
        return $this->getTo();
    }

    public function getMarketNameAttribute()
    {
        $market = $this->market->name ?? '';
        return $market;
    }

    public function getNumMarketAttribute()
    {
        return $this->num . ' (' . $this->market_name . ')';
    }

    public function getIdNumAttribute()
    {
       return $this->num . ' (#' . $this->id . ')';
    }

    public function getNumMarketActiveTitularAttribute()
    {
        $result = '[#' . $this->id . '] ' . $this->num_market;

        // Forzar a obtener el valor raw del atributo
        $visibleValue = $this->getAttributes()['visible'] ?? 1;

        if ($visibleValue == 0) {
            $result .= ' [OCULTA]';
        }

        if ($this->historicals->count() > 0) {
            $result .= ' - ' . $this->historicals->last()->name;
        } else {
            $result .= ' - Sense titular';
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        if (is_uploaded_file($value)) {
            $attribute_name = "image";
            $disk = "local";
            $destination_path = app('tenant')->name . "/stall";
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        } else {
            //Si no es uploaded file, viene de migracion
            $this->attributes['image'] = $value;
        }
    }
}
