<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommunicationRequest;
use App\Mail\SendCommunicationEmail;
use App\Models\Market;
use App\Models\Person;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Setting;

/**
 * Class CommunicationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommunicationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Communication::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/communication');
        $this->crud->setEntityNameStrings(__('backpack.communications.single'), __('backpack.communications.plural'));
        $this->crud->setCreateView('admin.communication.crud');
        $this->crud->setEditView('admin.communication.crud');
        $this->setPermissions('communications');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('ByMarketSelected');

        $this->crud->addColumn([
            'name' => 'type',
            'label' => __('backpack.communications.type'),
            'type' => 'select_from_array',
            'options' => __('backpack.communications.types'),
        ]);
        $this->crud->addColumn(['name' => 'title', 'label' => __('backpack.communications.title')]);
        $this->crud->addColumn(['name' => 'user_id', 'label' => __('backpack.communications.user_id')]);
        $this->crud->addColumn(['name' => 'created_at', 'label' => __('backpack.communications.created_at')]);
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn([
            'name' => 'type',
            'label' => __('backpack.communications.type'),
            'type' => 'select_from_array',
            'options' => __('backpack.communications.types'),
        ]);
        $this->crud->addColumn(['name' => 'title', 'label' => __('backpack.communications.title')]);
        $this->crud->addColumn(['name' => 'user_id', 'label' => __('backpack.communications.user_id')]);
        $this->crud->addColumn(['name' => 'created_at', 'label' => __('backpack.communications.created_at')]);
        $this->crud->addColumn(['name' => 'message', 'label' => __('backpack.communications.message'), 'type' => 'textarea']);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CommunicationRequest::class);

        $this->crud->addFields([
            [
                'name' => 'type',
                'label' => __('backpack.communications.type'),
                'type' => 'select_from_array',
                'options' => __('backpack.communications.types'),
                'allows_null' => false,
                'default' => 'email'
            ],
            [
                'name' => 'market_id',
                'label' => __('backpack.communications.market_id'),
                'type' => 'select2',
                'model' => 'App\Models\Market',
                'attribute' => 'name',
                'entity' => 'market',
                'placeholder' => __('backpack.communications.marcket_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->whereIn('id', backpack_user()->my_markets->pluck('id')->toArray())->orderBy('name')->get();
                }),
            ],
            [
                'name' => 'sector_id',
                'label' => __('backpack.communications.sector_id'),
                'type' => 'select2',
                'model' => 'App\Models\Sector',
                'attribute' => 'name',
                'entity' => 'sector',
                'placeholder' => __('backpack.auth_prods.sector_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name')->get();
                }),
            ],
            [
                'name' => 'auth_prod_id',
                'label' => __('backpack.communications.auth_prod_id'),
                'type' => 'select2',
                'model' => 'App\Models\AuthProd',
                'attribute' => 'name',
                'entity' => 'auth_prod',
                'placeholder' => __('backpack.stalls.auth_prod_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name')->get();
                }),
            ],
            [
                'name' => 'stalls',
                'label' => __('backpack.communications.stalls'),
                'type' => 'select2_multiple',
                'entity' => 'stalls',
                'model' => "App\Models\Stall",
                'attribute' => 'num_market',
                'placeholder' => __('backpack.communications.stall_default'),
                'pivot' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('market_id')->orderBy('num')->get();
                }),
            ],
            [
                'name' => 'persons',
                'label' => __('backpack.communications.persons'),
                'type' => 'select2_multiple',
                'entity' => 'persons',
                'model' => "App\Models\Person",
                'placeholder' => __('backpack.communications.person_default'),
                'attribute' => 'name',
                'pivot' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name')->get();
                }),
            ],
            [
                'name' => 'title',
                'label' => __('backpack.communications.title'),
            ],
            [
                'name' => 'message',
                'label' => __('backpack.communications.message'),
                'type' => 'textarea'
            ]
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        // check if user is allowed to edit this record
        if ($this->crud->getCurrentEntry()->market_id) {
            $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->market_id);
        }

        $this->setupCreateOperation();
    }

    protected function setupDeleteOperation()
    {
        // check if user is allowed to edit this record
        if ($this->crud->getCurrentEntry()->market_id) {
            $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->market_id);
        }
    }

    public function check(Request $request)
    {
        if (
            isset($request->stalls) || isset($request->persons) || isset($request->market_id) || is_null($request->market_id) ||
            isset($request->sector_id) || isset($request->auth_prod_id)
        ) {
            $senders = $this->getSenders($request->type);

            if ($senders->count() == 0) {
                return [
                    'status' => 'Error',
                    'message' => __('backpack.communications.errors.no_results')
                ];
            }

            return [
                'group' => $senders,
                'status' => 'Success'
            ];
        }
        return [
            'status' => 'Error',
            'message' => __('backpack.communications.errors.no_filter')
        ];
    }

    public function store()
    {
        $response = $this->traitStore();
        $communication = $this->crud->entry;
        $this->sendMessage($communication);
        return $response;
    }

    public function sendMessage($communication)
    {
        $array = [
            'email' => 'sendEmail',
            'sms' => 'sendSms'
        ];

        if (isset($array[$communication->type])) {
            $function = $array[$communication->type];
            return $this->$function($communication);
        }
    }

    public function sendEmail($communication)
    {
        $emails = $this->getSenders('email');
        // load email configuration
        $this->loadEmailConfig();
        foreach ($emails as $email) {
            Mail::to($email)->queue(new SendCommunicationEmail($communication));
        }
    }

    public function sendSms($communication)
    {
        if (!config('ettings.sms_id_associacio')) {
            abort(500, 'Falten dades de configuració');
        }

        $senders = $this->getSenders('phone');
        foreach ($senders as $sender) {
            $ch = curl_init('https://api.moneder.cat/index.php');
            # Setup request to send json via POST.
            $payload = [
                'fn' => 'sendSMS',
                'idAssociacio' => config("settings.sms_id_associacio"),
                'apiKey' => config("settings.sms_api_key"),
                'idUsuari' => config("settings.sms_id_usuari"),
                'idUser' => config("settings.sms_id_user"),
                'destinatari' => $sender,
                'missatge' => $communication->message,
            ];

            $POSTFIELDS = http_build_query($payload);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
            # Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    private function getSenders($field)
    {
        $array = collect();
        $stalls = collect();

        $request = request();
        // if has some filter
        if (
            is_null($request->persons) && (isset($request->stalls) || is_null($request->market_id) || isset($request->market_id) ||
                isset($request->sector_id) || isset($request->auth_prod_id))
        ) {
            $query = Stall::activeTitular()->when($request->market_id, function ($query, $market_id) {
                $query->where('market_id', $market_id);
            })->when($request->sector_id, function ($query, $sector_id) {
                $query->where('sector_id', $sector_id);
            })->when($request->auth_prod_id, function ($query, $auth_prod_id) {
                $query->whereHas('auth_prods', function ($query) use ($auth_prod_id) {
                    $query->where('auth_prods.id', $auth_prod_id);
                });
            })->when($request->stalls, function ($query, $stalls) {
                $query->whereIn('id', (array)$stalls);
            })->with('historicals');

            // all markets
            if (is_null($request->market_id)) {
                $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
            }

            $stalls = $query->get();
        }

        foreach ($stalls as $stall) {
            $person = $stall->historicals->last();
            if (!$array->contains($person->{$field})) {
                $array->push($person->{$field});
            }
        }

        // get direct persons
        if ($request->persons) {

            $persons = Person::whereIn('id', (array)$request->persons)->get();

            foreach ($persons as $person) {
                if (!$array->contains($person->{$field})) {
                    $array->push($person->{$field});
                }
            }
        }

        return $array;
    }

    private function loadEmailConfig()
    {
        if (!Setting::get('mail_username') || !Setting::get('mail_password')) {
            return false;
        }

        config(['mailers.smtp.host' => Setting::get('mail_host')]);
        config(['mailers.smtp.port' => Setting::get('mail_port')]);
        config(['mailers.smtp.username' => Setting::get('mail_username')]);
        config(['mailers.smtp.password' => Setting::get('mail_password')]);
        config(['mail.from.address' => Setting::get('mail_from_address')]);
        config(['mail.from.name' => Setting::get('mail_from_name')]);
        config(['mailers.smtp.encryption' => Setting::get('mail_encryption')]);
    }
}
