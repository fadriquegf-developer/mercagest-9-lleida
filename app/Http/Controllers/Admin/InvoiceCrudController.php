<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoicesExport;
use App\Models\BicConversion;
use App\Models\Calendar;
use App\Models\Invoice;
use App\Models\InvoiceConcept;
use App\Models\Market;
use App\Models\MarketGroup;
use App\Models\Person;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;


/**
 * Class InvoiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }
    use \App\Traits\AdminPermissions;

    /**
     * Cache de dies de calendari per market
     * Format: [market_id => Collection of Calendar days]
     */
    private $calendarCache = [];

    /**
     * Cache de rates per stall
     */
    private $rateCache = [];

    /**
     * Concepts pendents d'inserir (batch insert)
     */
    private $conceptsToInsert = [];

    /**
     * IDs d'invoices a eliminar (batch delete)
     */
    private $invoicesToDelete = [];

    /**
     * Mida del batch per inserts
     */
    private const BATCH_SIZE = 100;

    /**
     * Mida del chunk per processar persones
     */
    private const CHUNK_SIZE = 50;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Invoice::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/invoice');
        $this->crud->setEntityNameStrings(__('backpack.invoices.single'), __('backpack.invoices.plural'));
        $this->crud->enableExportButtons();
        $this->setPermissions('invoices');
    }

    protected function getView()
    {
        $this->data['crud'] = $this->crud;
        $this->data['invoices'] = Invoice::groupBy(DB::raw("YEAR(month)"))->get();
        return view('admin.invoice.index', $this->data);
    }

    protected function getInvoiceFilterByDates()
    {
        $request = request()->except('_token');
        return redirect('/admin/invoice?' . http_build_query($request));
    }

    protected function setupListOperation()
    {
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');

        $this->setFilterToListByRequest();
        $this->crud->addClause('published');

        $this->crud->addFilter([
            'name'  => 'type',
            'type'  => 'dropdown',
            'label' => 'Tipus'
        ], [
            'mensual' => 'Mensual',
            'trimestral' => 'Trimestral',
        ], function ($value) {
            $this->crud->addClause('where', 'type', $value);
        });

        $this->crud->addFilter([
            'name'  => 'month',
            'type'  => 'dropdown',
            'label' => 'Mes'
        ], [
            1 => 'Gener',
            2 => 'Febrer',
            3 => 'Març',
            4 => 'Abril',
            5 => 'Maig',
            6 => 'Juny',
            7 => 'Juliol',
            8 => 'Agost',
            9 => 'Setembre',
            10 => 'Octubre',
            11 => 'Novembre',
            12 => 'Decembre',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'month', $value);
        });

        $this->crud->addFilter([
            'name'  => 'trimestral',
            'type'  => 'dropdown',
            'label' => 'Trimestre'
        ], [
            1 => 'Trimestre 1',
            2 => 'Trimestre 2',
            3 => 'Trimestre 3',
            4 => 'Trimestre 4',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'trimestral', $value);
        });

        $this->crud->addFilter([
            'name'  => 'year',
            'type'  => 'dropdown',
            'label' => 'Any'
        ], [
            '2022' => '2022',
            '2023' => '2023',
            '2024' => '2024',
            '2025' => '2025',
        ], function ($value) { // if the filter is active
            $this->crud->addClause('where', 'years', 'LIKE', "%$value%");
        });

        $this->crud->addFilter([
            'name'  => 'person_id',
            'type'  => 'select2_multiple',
            'label' => 'Paradista'
        ], function () {
            return Person::get()->pluck('name', 'id')->toArray();
        }, function ($values) { // if the filter is active
            $this->crud->addClause('whereIn', 'person_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name'  => 'market_group_id',
            'type'  => 'select2_multiple',
            'label' => 'Tipus de Mercat'
        ], function () {
            return MarketGroup::get()->pluck('title', 'id')->toArray();
        }, function ($values) { // if the filter is active
            $this->crud->addClause('whereIn', 'market_group_id', json_decode($values));
        });

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => __('backpack.invoices.num')
            ],
            [
                'name' => 'type',
                'label' => __('backpack.invoices.type'),
                'value' => function ($entry) {
                    return ucfirst($entry->type);
                }
            ],
            [
                'name' => 'month',
                'label' => __('backpack.invoices.month') . '/' . __('backpack.invoices.trimestral'),
                'value' => function ($entry) {
                    if ($entry->month) {
                        $meses = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
                        return $meses[$entry->month - 1];
                    }
                    return 'Trimestre ' . $entry->trimestral;
                }
            ],
            [
                'name' => 'years',
                'label' => __('backpack.invoices.years')
            ],
            [
                'name' => 'person_id',
                'label' => __('backpack.invoices.person_id'),
                'wrapper' => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('person/' . $related_key . '/show');
                    },
                ],
            ],
            [
                'name' => 'dni',
                'label' => 'DNI/NIF',
                'value' => function ($entry) {
                    return $entry->person->dni;
                }
            ],
            [
                'name' => 'iban',
                'label' => 'Nom Titular',
                'value' => function ($entry) {
                    return $entry->person->name_titular;
                }
            ],
            [
                'name' => 'iban',
                'label' => 'NIF Titular',
                'value' => function ($entry) {
                    return $entry->person->nif_titular;
                }
            ],
            [
                'name' => 'iban',
                'label' => 'IBAN',
                'value' => function ($entry) {
                    return $entry->person->iban;
                }
            ],
            [
                'name' => 'market_group',
                'label' => __('backpack.stalls.market_group_id'),
                'type' => 'relationship',
                'attribute' => 'title'
            ],
            [
                'name' => 'total',
                'label' => __('backpack.invoices.total'),
                'type' => 'numeric',
                'wrapper' => [
                    'element' => 'span',
                    'class' => 'text-success'
                ]
            ]
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => __('backpack.invoices.num')
            ],
            [
                'name' => 'type',
                'label' => __('backpack.invoices.type'),
                'value' => function ($entry) {
                    return ucfirst($entry->type);
                }
            ],
            [
                'name' => 'month',
                'label' => __('backpack.invoices.month') . '/' . __('backpack.invoices.trimestral'),
                'value' => function ($entry) {
                    if ($entry->month) {
                        $meses = array("Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
                        return $meses[$entry->month - 1];
                    }
                    return 'Trimestre ' . $entry->trimestral;
                }
            ],
            [
                'name' => 'years',
                'label' => __('backpack.invoices.years')
            ],
            [
                'name' => 'person_id',
                'label' => __('backpack.invoices.person_id'),
                'wrapper' => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('person/' . $related_key . '/show');
                    },
                ],
            ],
            [
                'name' => 'dni',
                'label' => 'DNI/NIF',
                'value' => function ($entry) {
                    return $entry->person->dni;
                }
            ],
            [
                'name' => 'iban',
                'label' => 'IBAN',
                'value' => function ($entry) {
                    return $entry->person->iban;
                }
            ],
            [
                'name' => 'market_group',
                'label' => __('backpack.stalls.market_group_id'),
                'type' => 'relationship',
                'attribute' => 'title'
            ],
            [
                'name'  => 'concept_details',
                'label' => 'Conceptes',
                'type'  => 'table',
                'columns' => [
                    'concept' => 'Concepte',
                    'meters' => 'Metres / Mòduls',
                    'days' => 'Dies',
                    'price' => 'Preu',
                    'subtotal' => 'Subtotal'
                ]
            ],
            [
                'name' => 'total',
                'label' => __('backpack.invoices.total'),
                'value' => function ($entry) {
                    return $entry->total . ' €';
                },
                'wrapper' => [
                    'element' => 'span',
                    'class' => 'text-success'
                ]
            ]
        ]);
    }

    public function getStallsWithPersonToInvoice($data, $market)
    {
        $stallCondition = function ($query) use ($market, $data) {
            $query->where('market_id', $market->id)
                ->where('active', 1)
                ->where('historicals.start_at', '<=', $data['to'])
                ->where(function ($query) use ($data) {
                    $query->whereNull('historicals.ends_at')
                        ->orWhere('historicals.ends_at', '>=', $data['from']);
                });
        };

        $persons = Person::with(['historicals' => $stallCondition, 'historicals.rate'])
            ->select('persons.*')
            ->whereHas('historicals.market.rates')
            ->whereHas('historicals', $stallCondition);

        $invoices = Invoice::published()->whereIn('person_id', $persons->pluck('id')->toArray())
            ->where('month', 'like', $data['from'])
            ->where('type', $data['type'])
            ->get();

        return $persons->whereNotIn('id', $invoices->pluck('person_id')->toArray())->get();
    }


    protected function setFilterToListByRequest()
    {
        if (isset(request()->start_date) || isset(request()->end_date) || isset(request()->years)) {
            $this->crud->addClause('filterByDateRange', request()->all());
        }

        if (isset(request()->not_paid)) {
            $this->crud->addClause('filterByNotPaid');
        }

        if (isset(request()->last_not_paid)) {
            $this->crud->addClause('filterByLastNotPaid');
        }
    }

    public function generateInvoices()
    {

        $this->crud->addFields([
            [
                'name'        => 'years',
                'label'       => __('backpack.stalls.years'),
                'type'        => 'select_from_array',
                'options'     => [
                    date('Y') - 1 => date('Y') - 1,
                    date('Y')     => date('Y'),
                    date('Y') + 1 => date('Y') + 1,
                ],
                'allows_null' => false,
                'require'     => 'required'
            ],
            [
                'name'        => 'marketgroup_id',
                'label'       => __('backpack.stalls.market_group'),
                'type'        => 'select_from_array',
                'options'     => \App\Models\MarketGroup::active()->get()->pluck('titleGenerateInvoice', 'id'),
                'allows_null' => false,
            ],
            [
                'name'        => 'month',
                'label'       => __('backpack.stalls.month'),
                'type'        => 'select_from_array',
                'options'     => ['1' => 'Gener', '2' => 'Febrer', '3' => 'Març', '4' => 'Abril', '5' => 'Maig', '6' => 'Juny', '7' => 'Juliol', '8' => 'Agost', '9' => 'Setembre', '10' => 'Octubre', '11' => 'Novembre', '12' => 'Desembre'],
                'allows_null' => false,
                'require' => 'required'
            ],
            [
                'name'        => 'trimestral',
                'label'       => __('backpack.stalls.trimestral'),
                'type'        => 'select_from_array',
                'options'     => ['1' => '1r trimestre', '2' => '2n trimestre', '3' => '3r trimestre', '4' => '4t trimestre'],
                'allows_null' => false,
                'require' => 'required'
            ],
            [
                'name' => 'especial_edition',
                'label' => 'Edició especial',
                'type' => 'checkbox'
            ],
            [
                'name' => 'liquidation_days',
                'label' => 'Quants dies vols liquidar?',
                'type' => 'number',
                'attributes' => ["step" => "1", "min" => "1"],
            ]
        ]);

        $this->data['crud'] = $this->crud;
        return view('admin.invoice.generate_invoices', $this->data);
    }

    /**
     * Genera els invoices - VERSIÓ OPTIMITZADA
     */
    public function generate_index_invoice(Request $request)
    {
        // Validació d'edició especial
        if ($request->especial_edition) {
            $validator = Validator::make($request->all(), [
                'liquidation_days' => 'required|integer|min:1',
            ], [
                'liquidation_days.required' => 'Has d\'indicar un nombre de dies per a l\'edició especial.',
                'liquidation_days.integer' => 'El nombre de dies ha de ser un valor enter.',
                'liquidation_days.min' => 'El nombre de dies ha de ser superior a 0.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $years = $request->years;
        $marketGroupId = $request->marketgroup_id;
        $especialEdition = $request->especial_edition ?? false;
        $liquidationDays = $especialEdition ? $request->liquidation_days : null;

        // Calcular rang de dates
        $month = null;
        $trimestral = null;

        if ($request->trimestral) {
            $trimestral = $request->trimestral;
            $ranges = [
                '1' => [1, 3],
                '2' => [4, 6],
                '3' => [7, 9],
                '4' => [10, 12]
            ];
            $range = $ranges[$trimestral];
            $from = \Carbon\Carbon::create($years, $range[0])->firstOfMonth()->toDateString();
            $to = \Carbon\Carbon::create($years, $range[1])->lastOfMonth()->toDateString();
        } else {
            $month = $request->month;
            $from = \Carbon\Carbon::create($years, $month)->firstOfMonth()->toDateString();
            $to = \Carbon\Carbon::create($years, $month)->lastOfMonth()->toDateString();
        }

        // Inicialitzar arrays
        $invoices = [];
        $this->invoicesToDelete = [];
        $this->conceptsToInsert = [];
        $processedCount = 0;

        // OPTIMITZACIÓ 1: Eliminar invoices provisionals (status 0) d'una vegada
        // IMPORTANT: Això s'executa FORA de la transacció
        $this->deleteExpiredOptimized();

        // Utilitzar transacció per a les operacions de INSERT/UPDATE
        // NOTA: NO incloure refreshDB (ALTER TABLE) dins - fa commit implícit
        DB::transaction(function () use (
            $from,
            $to,
            $marketGroupId,
            $especialEdition,
            $liquidationDays,
            $years,
            $month,
            $trimestral,
            &$invoices,
            &$processedCount
        ) {
            // OPTIMITZACIÓ 2: Pre-carregar calendari UNA vegada
            $this->preloadCalendar($marketGroupId, $from, $to);

            // Query de persones (LÒGICA ORIGINAL simplificada)
            // L'eager loading s'ha eliminat perquè causava problemes
            // Els stalls es carreguen dins de processPersonInvoice
            $personsQuery = Person::whereHas('historicals', function ($query) use ($marketGroupId, $from, $to) {
                $query->activeTitular()
                    ->orWhere(function ($query) use ($from, $to) {
                        $query->whereDate('ends_at', '>=', $from)
                            ->whereDate('ends_at', '<=', $to);
                    })
                    ->where('market_group_id', $marketGroupId)
                    ->whereHas('market', function ($market) {
                        $market->where('status', 1);
                    });
            })->orderBy('name', 'ASC');

            // OPTIMITZACIÓ 4: Processar en chunks
            $personsQuery->chunk(self::CHUNK_SIZE, function ($persons) use (
                $from,
                $to,
                $marketGroupId,
                $especialEdition,
                $liquidationDays,
                $years,
                $month,
                $trimestral,
                &$invoices,
                &$processedCount
            ) {
                foreach ($persons as $person) {
                    $invoice = $this->processPersonInvoice(
                        $person,
                        $from,
                        $to,
                        $marketGroupId,
                        $especialEdition,
                        $liquidationDays,
                        $years,
                        $month,
                        $trimestral
                    );

                    if ($invoice) {
                        $invoices[] = $invoice;
                    }

                    $processedCount++;
                }

                // OPTIMITZACIÓ 6: Garbage collection
                gc_collect_cycles();
            });

            // OPTIMITZACIÓ 8: Eliminar invoices buits en batch
            if (!empty($this->invoicesToDelete)) {
                // Eliminar concepts associats
                InvoiceConcept::whereIn('invoice_id', $this->invoicesToDelete)->delete();
                // Eliminar invoices
                Invoice::whereIn('id', $this->invoicesToDelete)->delete();
            }
        }); // Fi de la transacció

        // OPTIMITZACIÓ 9: UN ÚNIC refreshDB al final
        // IMPORTANT: Això s'executa FORA de la transacció perquè ALTER TABLE fa commit implícit
        $this->refreshDB();

        // Recarregar invoices amb les seves relacions per a la vista
        $invoiceIds = collect($invoices)->pluck('id')->toArray();
        $invoices = Invoice::with(['person', 'concepts.stall.market', 'concepts.stall.classe', 'market_group'])
            ->whereIn('id', $invoiceIds)
            ->get();

        $crud = $this->crud;
        return view('admin.invoice.index_invoice_gtt', compact('invoices', 'crud'));
    }

    /**
     * Afegeix un concept al batch per inserir després
     */
    private function addConceptToBatch(array $conceptData): void
    {
        $conceptData['created_at'] = now();
        $conceptData['updated_at'] = now();
        $this->conceptsToInsert[] = $conceptData;

        // Inserir quan arribem al límit del batch
        if (count($this->conceptsToInsert) >= self::BATCH_SIZE) {
            $this->flushConceptsBatch();
        }
    }

    /**
     * Insereix tots els concepts pendents
     */
    private function flushConceptsBatch(): void
    {
        if (!empty($this->conceptsToInsert)) {
            InvoiceConcept::insert($this->conceptsToInsert);
            $this->conceptsToInsert = [];
        }
    }

    /**
     * Genera el concepte de stall i retorna el subtotal
     * OPTIMITZACIÓ: Utilitza addConceptToBatch en lloc de create()
     */
    private function generateStallConceptOptimized($stall, $days, $invoiceId, $stallId): float
    {
        $price = $stall->getCalculatedPrice();
        $subtotal = 0;

        if ($stall->rate->rate_type == 'daily') {
            $subtotal = $price * $days->count();
        } else {
            $subtotal = $price;
        }

        $this->addConceptToBatch([
            'invoice_id' => $invoiceId,
            'stall_id' => $stallId,
            'concept' => 'stall',
            'concept_type' => $stall->rate->price_type,
            'type_rate' => $stall->rate->rate_type,
            'length' => $stall->length,
            'qty_days' => $days->count(),
            'price' => $stall->rate->price,
            'subtotal' => $subtotal
        ]);

        return $subtotal;
    }

    /**
     * Genera el concepte de despeses i retorna el subtotal
     */
    private function generateExpensesConceptOptimized($stall, $days, $invoiceId, $stallId): float
    {
        $price = $stall->getCalculatedPriceExpenses();

        if ($price <= 0) {
            return 0;
        }

        $subtotal = 0;

        if ($stall->rate->rate_type == 'daily') {
            $subtotal = $price * $days->count();
        } else {
            $subtotal = $price;
        }

        $this->addConceptToBatch([
            'invoice_id' => $invoiceId,
            'stall_id' => $stallId,
            'concept' => 'expenses',
            'concept_type' => $stall->rate->price_type,
            'type_rate' => $stall->rate->rate_type,
            'length' => $stall->length,
            'qty_days' => $days->count(),
            'price' => $stall->rate->price_expenses,
            'subtotal' => $subtotal
        ]);

        return $subtotal;
    }

    /**
     * Genera el concepte de bonificacions i retorna el subtotal
     */
    private function generateBonusesConceptOptimized($stall, $from, $to, $days, $invoiceId, $stallId): float
    {
        // Utilitzem el mètode existent del model per calcular
        $bonusesPrice = $stall->generateBonusesConcept($from, $to, $days, $invoiceId, $stallId);
        return $bonusesPrice;
    }

    /**
     * Processa una persona i genera el seu invoice
     * Retorna l'invoice creat o null si no s'ha creat/és buit
     * NOTA: Utilitza la lògica ORIGINAL per obtenir stalls (més fiable)
     */
    private function processPersonInvoice(
        Person $person,
        string $from,
        string $to,
        int $marketGroupId,
        bool $especialEdition,
        ?int $liquidationDays,
        string $years,
        ?int $month,
        ?int $trimestral
    ): ?Invoice {
        // Comprovar si ja existeix un invoice (LÒGICA ORIGINAL)
        if (!$especialEdition) {
            $invoiceQuery = Invoice::published()
                ->where('person_id', $person->id)
                ->where('market_group_id', $marketGroupId)
                ->where('years', $years);

            if (isset($trimestral)) {
                $invoiceQuery->where('type', 'trimestral')->where('trimestral', $trimestral);
            } else {
                $invoiceQuery->where('type', 'mensual')->where('month', $month);
            }

            $existingInvoice = $invoiceQuery->first();
            if ($existingInvoice) {
                return null;
            }
        }

        // Crear invoice
        $invoice = Invoice::create([
            'status' => 0,
            'person_id' => $person->id,
            'market_group_id' => $marketGroupId,
            'type' => isset($trimestral) ? 'trimestral' : 'mensual',
            'month' => $month ?? null,
            'trimestral' => $trimestral ?? null,
            'years' => $years,
            'total' => 0,
            'special_edition' => $especialEdition ? 1 : 0
        ]);

        $totalInvoice = 0;
        $hasValidConcepts = false;

        // LÒGICA ORIGINAL: Obtenir stalls amb query (NO eager loading)
        $stalls = $person->historicals()->where(function ($query) use ($from, $to) {
            $query->where('end_vigencia', null)
                ->orWhere('end_vigencia', '>=', $from)
                ->where('ends_at', null)
                ->orWhere('ends_at', '>=', $from);
        })->where('market_group_id', $marketGroupId)->get();

        foreach ($stalls as $stall) {
            // Validacions habituals (LÒGICA ORIGINAL)
            if ($stall->pivot->ends_at != null && $stall->pivot->ends_at <= $to) {
                if ($stall->pivot->ends_at < $from || !($stall->pivot->ends_at >= $from && $stall->pivot->ends_at <= $to)) {
                    continue;
                }
            }
            if ($stall->market->status == 0) {
                continue;
            }
            if ($stall->rate_id == null) {
                continue;
            }
            if (isset($trimestral) && !$stall->{'trimestral_' . $trimestral}) {
                continue;
            }

            // Obtenir dies del calendari (OPTIMITZAT: des del cache)
            $days = $this->getCalendarDays($stall->market->id);

            // Si no hi ha dies al cache, fer query (fallback)
            if ($days->isEmpty()) {
                $days = Calendar::filterByMarket($stall->market->id)
                    ->filterByDateRange($from, $to)
                    ->get();
            }

            // Ajustar dies segons dates del stall (LÒGICA ORIGINAL)
            if ($stall->pivot->ends_at != null && $stall->pivot->ends_at >= $from && $stall->pivot->ends_at <= $to) {
                $days = $days->filter(function ($day) use ($stall) {
                    return $day->date < $stall->pivot->ends_at;
                });
            }
            if ($stall->pivot->start_at != null && $stall->pivot->start_at >= $from && $stall->pivot->start_at <= $to) {
                $days = $days->filter(function ($day) use ($stall) {
                    return $day->date > $stall->pivot->start_at;
                });
            }

            if ($days->isEmpty()) {
                continue;
            }

            // Generar conceptes (LÒGICA ORIGINAL: utilitzant mètodes del model)
            $stall_price = $stall->generateStallConcept($days, $invoice->id, $stall->id);
            $expenses_price = $stall->generateExpensesConcept($days, $invoice->id, $stall->id);
            $bonuses_price = $stall->generateBonusesConcept($from, $to, $days, $invoice->id, $stall->id);

            $totalInvoice += ($stall_price + $expenses_price - $bonuses_price);
            $hasValidConcepts = true;
        }

        // Guardar total
        $invoice->total = $totalInvoice;
        $invoice->save();

        // Si no s'han generat conceptes o el total és 0, marcar per eliminar
        if ($invoice->concepts->count() == 0 || $invoice->total == 0) {
            $this->invoicesToDelete[] = $invoice->id;
            return null;
        }

        return $invoice;
    }

    /**
     * Filtra els dies del calendari segons les dates del stall
     */
    private function filterCalendarDays(
        \Illuminate\Support\Collection $days,
        ?string $stallStartAt,
        ?string $stallEndsAt,
        string $from,
        string $to
    ): \Illuminate\Support\Collection {
        return $days->filter(function ($day) use ($stallStartAt, $stallEndsAt, $from, $to) {
            $date = $day->date;

            // Filtrar per data d'inici del stall
            if ($stallStartAt && $stallStartAt >= $from && $stallStartAt <= $to) {
                if ($date <= $stallStartAt) {
                    return false;
                }
            }

            // Filtrar per data de fi del stall
            if ($stallEndsAt && $stallEndsAt >= $from && $stallEndsAt <= $to) {
                if ($date >= $stallEndsAt) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    /**
     * Pre-carrega tots els dies del calendari per als mercats del grup
     * OPTIMITZACIÓ: Una única query en lloc de N queries (una per stall)
     */
    private function preloadCalendar(int $marketGroupId, string $from, string $to): void
    {
        // Obtenir tots els market IDs actius del grup
        $marketIds = Market::where('market_group_id', $marketGroupId)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();

        if (empty($marketIds)) {
            return;
        }

        // Carregar TOTS els dies del calendari d'UNA vegada
        $allDays = Calendar::whereIn('market_id', $marketIds)
            ->whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->get();

        // Agrupar per market_id per accés ràpid
        $this->calendarCache = $allDays->groupBy('market_id');
    }

    /**
     * Obté els dies del calendari per un mercat (des del cache)
     */
    private function getCalendarDays(int $marketId): \Illuminate\Support\Collection
    {
        return $this->calendarCache[$marketId] ?? collect();
    }

    public function generate_gtt(Request $request)
    {
        //Comprovamos si hay que generar el excel provisional, o el gtt final
        if ($request->action == 'excel') {
            return $this->generate_excel($request);
        }

        setlocale(LC_ALL, "es_ES");

        $code = Str::random(6);

        //Convertimos los invoices de las ids a status 0 como validadas (una vez se decide descargar el GTT, estas pasan a ser aprovadas 'status' = 1)
        Invoice::whereIn('id', $request->invoices)->update(['status' => 1]);

        //Obtenemos los invoices de las ids, i que no tengan valor 0, no quieren invoices 0
        $invoices = Invoice::whereIn('id', $request->invoices)->where('total', '!=', 0)->orderBy('id', 'ASC')->get();

        $file =  storage_path() . '/app/gtt/' . $code . '-informe_gtt.txt';

        foreach ($invoices as $key => $invoice) {
            $linea_formato_dv = $this->generate_format_dv($key + 1, $invoice);
            $linea_formato_li = $this->generate_format_li($key + 1, $invoice);
            //file_put_contents($file, $linea_formato_dv. PHP_EOL . $linea_formato_li. PHP_EOL, FILE_APPEND);
            if ($key == 0) {
                file_put_contents($file, $linea_formato_dv, FILE_APPEND);
            } else {
                file_put_contents($file, "\r\n" . $linea_formato_dv, FILE_APPEND);
            }
            file_put_contents($file, "\r\n" . $linea_formato_li, FILE_APPEND);
        }
        //Marcar el final de la ultima linea
        file_put_contents($file, "\r\n", FILE_APPEND);

        $gtt_url = storage_path() . '/app/gtt/' . $code . '-informe_gtt.txt';

        //Generam decret d'aprovacio
        $url_pdf_decret = storage_path() . '/app/gtt/' . $code . '-pdf_decret_sm.pdf';
        $pdf = PDF::loadView('admin.invoice.pdf_decret_sm', compact('invoices'))->save($url_pdf_decret);

        //Generam informe MM
        $url_pdf_informe = storage_path() . '/app/gtt/' . $code . '-pdf_informe_mm.pdf';
        $pdf = PDF::loadView('admin.invoice.pdf_informe_mm', compact('invoices'))->save($url_pdf_informe);

        //Comprimim archius en zip
        $zipFileName = $code . '-gtt_decret.zip';
        $zipPath = storage_path() . '/app/gtt/' . $zipFileName;
        $zip = new ZipArchive;
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile($gtt_url, $code . '-informe_gtt.txt');
        $zip->addFile($url_pdf_decret, $code . '-pdf_decret_sm.pdf');
        $zip->addFile($url_pdf_informe, $code . '-pdf_informe_mm.pdf');
        $zip->close();


        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );

        $filetopath = storage_path() . '/app/gtt/' . $zipFileName;

        return response()->download($filetopath, $zipFileName, $headers);
    }

    public function clean_string($string)
    {

        //Reemplazamos la A y a
        $string = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $string
        );

        //Reemplazamos la E y e
        $string = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $string
        );

        //Reemplazamos la I y i
        $string = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $string
        );

        //Reemplazamos la O y o
        $string = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $string
        );

        //Reemplazamos la U y u
        $string = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $string
        );

        //Reemplazamos la N, n, C y c y €
        $string = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $string
        );

        return $string;
    }



    private function generate_format_dv($key, Invoice $invoice)
    {
        $meses = ['Gener', 'Febrer', 'Marc', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];
        $formato_dv = [];
        //0 Valor vacio
        $formato_dv[0] = '';
        //Datos de identificación
        //1 1 Año cargo valor N(4)
        $formato_dv[1] = str_pad(date("Y"), 4);
        //2 5 SubOrganismo N(5)
        $formato_dv[2] = str_pad('25120', 5);
        //3 10 Concepto A(2)
        $formato_dv[3] = str_pad($invoice->market_group->gtt_type, 2);
        //4 12 N º de Emisión N(2)
        $formato_dv[4] = str_pad('01', 2);
        //5 14 Tipo de Valor A(1)
        $formato_dv[5] = str_pad('L', 1);
        //6 15 Número de ref. lista del valor N(6)
        $formato_dv[6] = str_pad($key, 6, "0", STR_PAD_LEFT);
        //7 21 Formato de línea del soporte A(2)
        $formato_dv[7] = str_pad('DV', 2);
        //8 23 Formato del cuerpo del valor A(2)
        $formato_dv[8] = str_pad('LI', 2);
        //Periodo del valor
        //9 25 Voluntaria/Ejecutiva A(1) 
        $formato_dv[9] = str_pad('V', 1);
        //10 26 Ejercicio N(4)
        $formato_dv[10] = str_pad($invoice->years, 4);
        //11 30 Periodo A(30)
        $formato_dv[11] = str_pad((isset($invoice->month) ? $meses[$invoice->month - 1] : 'TRIMESTRE ' . $invoice->trimestral) . ' ' . $invoice->years, 30);
        //12 60 Fecha devengo A(8)
        $formato_dv[12] = str_pad(date('Ymd'), 8);
        //Datos del contribuyente y dirección fiscal
        //13 68 Nif A(10)
        $formato_dv[13] = str_pad($invoice->person->dni, 10);
        //14 78 Nombre A(60)
        $formato_dv[14] = str_pad($this->clean_string($invoice->person->name), 60);
        //Dirección
        //15 138 Siglas A(2)
        $formato_dv[15] = str_pad($this->clean_string($invoice->person->type_address), 2);
        //16 140 Calle A(40)
        $formato_dv[16] = str_pad($this->clean_string($invoice->person->address), 40);
        //17 180 Ampliación Vía A(40)
        $formato_dv[17] = str_pad($this->clean_string($invoice->person->extra_address), 40);
        //18 220 Número N(5)
        $formato_dv[18] = str_pad($invoice->person->number_address, 5, "0", STR_PAD_LEFT);
        //19 225 Duplicado A(5)
        $formato_dv[19] = str_pad('', 5);
        //20 230 Número2 N(5)
        $formato_dv[20] = str_pad('', 5, "0", STR_PAD_LEFT);
        //21 235 Duplicado2 A(5)
        $formato_dv[21] = str_pad('', 5);
        //22 240 Escalera A(2)
        $formato_dv[22] = str_pad('', 2);
        //23 242 Planta A(3)
        $formato_dv[23] = str_pad('', 3);
        //24 245 Puerta A(5)
        $formato_dv[24] = str_pad('', 5);
        //25 250 Portal A(2)
        $formato_dv[25] = str_pad('', 2);
        //26 252 Bloque A(4)
        $formato_dv[26] = str_pad('', 4);
        //27 256 Km N(5)
        $formato_dv[27] = str_pad('', 5, "0", STR_PAD_LEFT);
        //28 261 Mano A(2)
        $formato_dv[28] = str_pad('', 2);
        //29 263 Población A(30)
        $formato_dv[29] = str_pad($this->clean_string($invoice->person->city), 30);
        //30 293 Entidad Local/Pedanía A(40)
        $formato_dv[30] = str_pad($this->clean_string($invoice->person->region), 40);
        //31 333 Provincia A(30)
        $formato_dv[31] = str_pad($this->clean_string($invoice->person->province), 30);
        //32 363 Código Postal N(5)
        $formato_dv[32] = str_pad($invoice->person->zip, 5, "0", STR_PAD_LEFT);
        //Datos identificativos de la deuda
        //33 368 Municipio N(5)
        $formato_dv[33] = str_pad('', 5, "0", STR_PAD_LEFT);
        //34 373 Objeto Tributario A(80)
        $formato_dv[34] = str_pad('', 80);
        //35 453 Número Fijo A(14)
        $formato_dv[35] = str_pad('', 14);
        //36 467 Importe Principal N(10)
        $formato_dv[36] = str_pad(number_format($invoice->total, 2, '', ''), 10, "0", STR_PAD_LEFT);
        //37 477 IVA N(10)
        $formato_dv[37] = str_pad('', 10, "0", STR_PAD_LEFT);
        //38 487 Importe Recargo Provincial N(10)
        $formato_dv[38] = str_pad('', 10, "0", STR_PAD_LEFT);
        //39 497 Cuota Municipal N(10)
        $formato_dv[39] = str_pad(number_format($invoice->total, 2, '', ''), 10, "0", STR_PAD_LEFT);
        //40 507 Sección IAE N(1)
        $formato_dv[40] = str_pad('', 1, "0", STR_PAD_LEFT);
        //41 508 Importe Canon N(10)
        $formato_dv[41] = str_pad('', 10, "0", STR_PAD_LEFT);
        //42 518 Bonificación Plusvalías N(7,4)
        $formato_dv[42] = str_pad('', 7, "0", STR_PAD_LEFT);
        //43 525 Recargo extemporaneidad N(10)
        $formato_dv[43] = str_pad('', 10, "0", STR_PAD_LEFT);
        //44 535 Intereses extemporaneidad N(10)
        $formato_dv[44] = str_pad('', 10, "0", STR_PAD_LEFT);
        //45 545 Referencia Externa A(29)
        $formato_dv[45] = str_pad('JAV' . str_pad($invoice->id, 6, "0", STR_PAD_LEFT), 29);
        //46 574 Referencia Contable A(20)
        $formato_dv[46] = str_pad('', 20);
        //47 594 Referencia Catastral A(20)
        $formato_dv[47] = str_pad('', 20);
        //48 614 Año Contable N(4)
        $formato_dv[48] = str_pad('', 4, "0", STR_PAD_LEFT);
        //49 618 Fecha decreto aprobación A(8)
        $formato_dv[49] = str_pad('', 8, "0", STR_PAD_LEFT);
        //50 626 Número decreto aprobación A(30)
        $formato_dv[50] = str_pad('', 30);
        //51 656 Fecha Inicio Voluntaria A(8)
        $formato_dv[51] = str_pad('', 8, "0", STR_PAD_LEFT);
        //52 664 Fecha Fin Voluntaria A(8)
        $formato_dv[52] = str_pad('', 8, "0", STR_PAD_LEFT);
        //53 672 LOPD N(8)
        $formato_dv[53] = str_pad('1', 8, "0", STR_PAD_LEFT);
        //54 680 Fecha Notificación Prov. Apremio A(8)
        $formato_dv[54] = str_pad('', 8, "0", STR_PAD_LEFT);
        //55 688 Resultado Notificación A(2)
        $formato_dv[55] = str_pad('', 2);
        //56 690 Número providencia de apremio A(30)
        $formato_dv[56] = str_pad('', 30);
        //57 720 Fecha firma providencia de apremio A(8)
        $formato_dv[57] = str_pad('', 8, "0", STR_PAD_LEFT);
        //58 728 Órgano que decreta la providencia de apremio A(30)
        $formato_dv[58] = str_pad('', 30);
        //59 758 Fecha Notificación Voluntaria A(8)
        $formato_dv[59] = str_pad('', 8, "0", STR_PAD_LEFT);
        //60 766 Resultado Notificación Voluntaria A(2)
        $formato_dv[60] = str_pad('', 2);
        //61 768 Fecha de Inicio del Periodo A(8)
        $formato_dv[61] = str_pad('', 8, "0", STR_PAD_LEFT);
        //62 776 Fecha de Fin del Periodo A(8)
        $formato_dv[62] = str_pad('', 8, "0", STR_PAD_LEFT);
        //63 784 Fecha1 interrupción de la prescripción A(8)
        $formato_dv[63] = str_pad('', 8, "0", STR_PAD_LEFT);
        //64 792 Descripción1 interrupción de la prescripción A(60)
        $formato_dv[64] = str_pad('', 60);
        //65 852 Fecha2 interrupción de la prescripción A(8)
        $formato_dv[65] = str_pad('', 8, "0", STR_PAD_LEFT);
        //66 860 Descripción2 interrupción de la prescripción A(60)
        $formato_dv[66] = str_pad('', 60);
        //67 920 Fecha3 interrupción de la prescripción A(8)
        $formato_dv[67] = str_pad('', 8, "0", STR_PAD_LEFT);
        //68 928 Descripción3 interrupción de la prescripción A(60)
        $formato_dv[68] = str_pad('', 60);
        //69 988 Fecha4 interrupción de la prescripción A(8)
        $formato_dv[69] = str_pad('', 8, "0", STR_PAD_LEFT);
        //70 996 Descripción4 interrupción de la prescripción A(60)
        $formato_dv[70] = str_pad('', 60);
        //71 1056 Fecha5 interrupción de la prescripción A(8)
        $formato_dv[71] = str_pad('', 8, "0", STR_PAD_LEFT);
        //72 1064 Descripción5 interrupción de la prescripción A(60)
        $formato_dv[72] = str_pad('', 60);
        //Domiciliacion
        //73 1124 Titular A(60)
        $formato_dv[73] = str_pad($this->clean_string($invoice->person->name_titular), 60);
        //74 1184 NIF Titular A(10)
        $formato_dv[74] = str_pad($this->clean_string($invoice->person->nif_titular), 10);
        //75 1194 Fecha domiciliación A(8)
        if ($invoice->person->date_domiciliacion != '0000-00-00' && $invoice->person->date_domiciliacion != NULL) {
            $formato_dv[75] = str_pad(date('Ymd', strtotime($invoice->person->date_domiciliacion)), 8, "0", STR_PAD_LEFT);
        } else {
            $formato_dv[75] = str_pad("00000000", 8, "0", STR_PAD_LEFT);
        }
        //76 1202 IBAN A(34)
        $formato_dv[76] = str_pad($this->clean_string($invoice->person->iban), 34);
        //77 1236 BIC A(13)
        if ($invoice->person->iban) {
            $formato_dv[77] = str_pad($this->getBicCode($invoice->person->iban), 13);
        } else {
            $formato_dv[77] = str_pad('', 13);
        }
        //78 1249 Fecha mandato A(8)
        if ($invoice->person->date_domiciliacion != '0000-00-00' && $invoice->person->date_domiciliacion != NULL) {
            $formato_dv[78] = str_pad(date('Ymd', strtotime($invoice->person->date_domiciliacion)), 8, "0", STR_PAD_LEFT);
        } else {
            $formato_dv[78] = str_pad("00000000", 8, "0", STR_PAD_LEFT);
        }
        //79 1257 Cuerpo c19 A(140)
        $string_c19 = strtoupper($invoice->market_group->title) . ' - PERIODE:' . (isset($invoice->month) ? $meses[$invoice->month - 1] : 'TRIMESTRE ' . $invoice->trimestral) . ' ' . $invoice->years . ' - TOTAL REBUT: ' . number_format($invoice->total, 2, ',', '.') . ' e';
        $formato_dv[79] = str_pad($this->clean_string($string_c19), 140);
        //80 1397 Cuerpo c19 ampliado A(640)
        $string_c19_ampliado = 'ORDENANCA FISCAL 2.9 - LIQUIDACIO REBUTS - REBUT N.' . $invoice->id . ' - ' . strtoupper($invoice->market_group->title) . ' - PERIODE:' . (isset($invoice->month) ? $meses[$invoice->month - 1] : 'TRIMESTRE ' . $invoice->trimestral) . ' ' . $invoice->years;
        foreach ($invoice->concepts as $concept) {
            switch ($concept->concept) {
                case 'stall':
                    $string_c19_ampliado .= $concept->stall->market->name . ' - ' . $concept->stall->classe->name . ' ' . number_format($concept->subtotal, 2, ',', '.') . ' e';
                    break;
                case 'expenses':
                    $string_c19_ampliado .= 'Despeses Mant. ' . $concept->stall->market->name . ' - ' . $concept->stall->classe->name . ' ' . number_format($concept->subtotal, 2, ',', '.') . ' e';
                    break;
                case 'bonuses':
                    $string_c19_ampliado .= 'Bonificacions ' . number_format($concept->subtotal, 2, ',', '.') . ' e';
                    break;
            }
        }
        $string_c19_ampliado .= '   IMPORT TOTAL  ' . number_format($invoice->total, 2, ',', '.') . ' e';

        //80 1397 Cuerpo c19 ampliado A(640)
        $formato_dv[80] = str_pad($this->clean_string($string_c19_ampliado), 640);
        //81 2037 Blancos hasta fin de fichero A(4)
        $formato_dv[81] = str_pad('', 4);
        //82 2040 Fin de fichero

        $linea_dv = '';
        foreach ($formato_dv as $key => $row) {
            $linea_dv .= $row;
        }

        return $linea_dv;
    }

    private function generate_format_li($key, Invoice $invoice)
    {
        $meses = ['Gener', 'Febrer', 'Marc', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];

        $formato_li = [];
        //0 Valor vacio
        $formato_li[0] = '';
        //1 1 Año cargo valor N(4)
        $formato_li[1] = str_pad(date("Y"), 4, "0", STR_PAD_LEFT);
        //2 5 SubOrganismo N(5)
        $formato_li[2] = str_pad('25120', 5, "0", STR_PAD_LEFT);
        //3 10 Concepto A(2)
        $formato_li[3] = str_pad($invoice->market_group->gtt_type, 2);
        //4 12 N º de Emisión N(2)
        $formato_li[4] = str_pad('01', 2, "0", STR_PAD_LEFT);
        //5 14 Tipo de Valor A(1)
        $formato_li[5] = str_pad('L', 1);
        //6 15 Número de ref. lista del valor N(6)
        $formato_li[6] = str_pad($key, 6, "0", STR_PAD_LEFT);
        //7 21 Formato de línea del soporte A(2)
        $formato_li[7] = str_pad('LI', 2);
        //8 23 Blancos A(2)
        $formato_li[8] = str_pad('', 2);
        //9 25 15 líneas de texto A(80)
        //$string_texto = $invoice->person->name . ' - ORDENANCA FISCAL 2.9 - LIQUIDACIO REBUTS - REBUT N.' . $invoice->id . ' - ' . $invoice->market_group->title . ' - PERIODE:' . (isset($invoice->month) ? $meses[$invoice->month - 1] : 'TRIMESTRE ' . $invoice->trimestral) . ' ' . $invoice->years;
        //Cada linea son 80
        //Primera linea
        $string_texto = str_pad('ORDENANCA FISCAL 2.9', 40);
        $string_texto .= str_pad('LIQUIDACIO REBUTS - REBUT N.' . $invoice->id, 40);
        //Segunda linea
        $string_texto .= str_pad($invoice->market_group->title, 40);
        $string_texto .= str_pad('PERIODE:' . (isset($invoice->month) ? $meses[$invoice->month - 1] : 'TRIMESTRE ' . $invoice->trimestral) . ' ' . $invoice->years, 40);
        //Resto de linea
        foreach ($invoice->concepts as $concept) {
            switch ($concept->concept) {
                case 'stall':
                    $string_texto .= str_pad($concept->stall->market->name . ' - ' . $concept->stall->classe->name . ' ' . number_format($concept->subtotal, 2, ',', '.') . ' e', 80);
                    break;
                case 'expenses':
                    $string_texto .= str_pad('Despeses Mant. ' . $concept->stall->market->name . ' - ' . $concept->stall->classe->name . ' ' . number_format($concept->subtotal, 2, ',', '.') . ' e', 80);
                    break;
                case 'bonuses':
                    $string_texto .= str_pad('Bonificacions ' . number_format($concept->subtotal, 2, ',', '.') . ' e', 80);
                    break;
            }
        }
        $string_texto .= str_pad('IMPORT TOTAL ' . number_format($invoice->total, 2, ',', '.') . ' e', 80);
        $formato_li[9] = str_pad($this->clean_string($string_texto), 2016);
        //10 1225 Blancos hasta fin de fichero A(816)
        //$formato_li[10] = str_pad('0', 816);
        //11 2040 Fin fichero
        //$formato_li[11] = str_pad('0', 1078);


        $linea_li = '';
        foreach ($formato_li as $key => $row) {
            $linea_li .= $row;
        }

        return $linea_li;
    }

    public function deleteInvoice(Request $request)
    {
        $invoice_id = $request->id;
        if ($invoice_id) {
            $invoice = Invoice::find($request->id);
            $price = $invoice->total;
            $invoice->delete();
            return ['status' => 'ok', 'price' => $price];
        }
        return ['status' => 'fail'];
    }

    public function getBicCode($iban)
    {
        //Quitamos los espacios en blanco
        $iban = str_replace(' ', '', $iban);

        //Si el iban empieza por ES, los 4 siguientes numeros con el codigo de banco
        if (str_starts_with($iban, 'ES')) {
            $bank_code = substr($iban, 4, 4);
        } else {
            //Sino, son los 4 primeros
            $bank_code = substr($iban, 0, 4);
        }

        //Obtenemos la conversion del codigo del banco
        $bic_code = BicConversion::where('bank_code', $bank_code)->first();

        //Si existe, devolvemos el codigo BIC
        if ($bic_code) {
            return $bic_code->bank_bic;
        }

        //Sino, devolvemos string vacio
        return '';
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];

        foreach ($entries as $id) {
            if ($entry = $this->crud->model->find($id)) {
                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }


    public function generate_excel($request)
    {
        //Obtenemos todas las invoices
        $invoices = Invoice::whereIn('invoices.id', $request->invoices)
            ->orderBy(
                Person::select('name')
                    ->whereColumn('persons.id', 'invoices.person_id')
            )->get();

        $code = Str::random(6);

        return Excel::download(new InvoicesExport($invoices), 'provisional-' . $code . '.xlsx');
    }

    /**
     * Elimina invoices provisionals de forma optimitzada
     */
    public function deleteExpiredOptimized(): void
    {
        // Obtenir IDs d'invoices amb status 0
        $invoiceIds = Invoice::where('status', 0)->pluck('id')->toArray();

        if (!empty($invoiceIds)) {
            // Eliminar concepts primer (FK constraint)
            InvoiceConcept::whereIn('invoice_id', $invoiceIds)->delete();
            // Eliminar invoices
            Invoice::whereIn('id', $invoiceIds)->delete();
        }
    }

    public function deleteExpired()
    {
        $this->deleteExpiredOptimized();
        $this->refreshDB();
    }

    public function refreshDB()
    {
        $max = DB::table('invoices')->max('id') ?? 0;
        $max++;
        DB::statement("ALTER TABLE invoices AUTO_INCREMENT = $max");
    }
}
