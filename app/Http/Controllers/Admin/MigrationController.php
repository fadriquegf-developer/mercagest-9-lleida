<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Town;
use App\Models\Sector;
use App\Models\AuthProd;
use App\Models\Market;
use App\Models\Rate;
use App\Models\Calendar;
use App\Models\Stall;
use App\Models\Person;
use App\Models\Historical;
use App\Models\Concession;
use App\Models\Absence;
use App\Models\Communication;
use App\Models\ContactEmail;
use App\Models\Invoice;
use App\Models\Incidences;
use App\Models\Classe;
use App\Models\MarketGroup;

class MigrationController extends Controller
{
    /**
     * Funcion para migrar una BD antigua de mercagest, a la nueva version
     * Dentro de config/database, encontraremos la connexion 'migration' donde deberemos especificar la bd que queremos migrar
     */
    public function migration()
    {
        
        $this->migrateUsers();
        $this->migrateTowns();
        $this->migrateSectors();
        $this->migrateProdAuth();
        $this->migrateMarkets();
        $this->migrateRates();
        $this->migrateMarketUser();
        $this->migrateCalendar();
        $this->createBasicClasses();
        $this->migrateStalls();
        $this->migrateOwner();
        $this->migrateOwnerstall();
        $this->migrateAbsences();
        $this->migrateCommunications();
        $this->migrateContactEmails();
        $this->migrateInvoices();
        $this->migrateIncidences();

        return 'Migracion finalizada';
       
    }

    
    public function migrateUsers()
    {
        //Eliminamos los usuarios actuales de la bd menos el admin
        User::where('id', '!=', 1)->delete();

        //Obtenemos los usuarios a migrar
        $users = DB::connection('migration')->table('users')->where('id', '!=', 1)->get();
        foreach($users as $user){
            //Como los usuarios tienen el rol en esta tabla, comprovamos si el rol que tienen existe, si no existe creamos el rol, sino obtenemos la id del rol, para asignarselo al usuario
            $role = Role::where('name', $user->role)->first();

            //Si el rol no existe, se crea i se le dan todos los permisos
            if(!$role){
                $role =  Role::create(['name' => $user->role, 'guard_name' => backpack_guard_name()]);
                $permissions = Permission::where('guard_name', backpack_guard_name())->get();
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            }

            //Creamos el usuario
            $user_created = User::create([
                'id' => $user->id,
                'name' => $user->name.' '.$user->surname,
                'email' => $user->email,
                'password' => $user->password,
                'language' => 'ca',
                'remember_token' => $user->remember_token
            ]);

            //asignamos el rol al usuario
            $user_created->assignRole($role->name);
        }

        print('Usuarios migrados correctamente<br>');
    }

    public function migrateTowns()
    {
        //Limpiamos la tabla
        Town::truncate();

        $towns = DB::connection('migration')->table('town')->get();

        foreach($towns as $town){
            Town::create([
                'id' => $town->id,
                'name' => $town->name
            ]);
        }

        print('Pueblos migrados correctamente<br>');
    }

    public function migrateSectors()
    {
        Sector::truncate();

        $sectors = DB::connection('migration')->table('sector')->get();

        foreach($sectors as $sector){
            Sector::create([
                "id" => $sector->id,
                "name" => $sector->name,
                "slug" => Str::slug($sector->name, '-')
            ]);
        }

        print('Sectores migrados correctamente<br>');
    }

    public function migrateProdAuth()
    {
        AuthProd::truncate();

        $authprods = DB::connection('migration')->table('auth_prod')->get();

        foreach($authprods as $authprod){
            AuthProd::create([
                "id" => $authprod->id,
                "name" => $authprod->name,
                "slug" => Str::slug($authprod->name, '-'),
                "sector_id" => $authprod->sector_id,
            ]);
        }

        print('Productos Autorizados migrados correctamente<br>');
    }

    public function migrateMarkets()
    {
        Market::truncate();

        $markets = DB::connection('migration')->table('market')->get();

        foreach($markets as $market){
            Market::create([
                'id' => $market->id,
                'name' => $market->name,
                'slug' => Str::slug($market->name, '-'),
                'town_id' => $market->town_id
            ]);

            //Creamos un grupo de mercado por cada mercado, ya que esta funcionalidad es necesaria para generar recibos i antes no existia
            MarketGroup::create([
                'id' => $market->id,
                'type' => strtoupper(substr($market->name, 0, 1)),
                'gtt_type' => strtoupper(substr($market->name, 0, 2)),
                'title' => $market->name,
            ]);

        }

        print('Markets migrados correctamente<br>');
    }

    public function migrateRates()
    {
        Rate::truncate();

        $rates = DB::connection('migration')->table('tariff')->get();

        foreach($rates as $rate){
            Rate::create([
                'id' => $rate->id,
                'name' => $rate->name,
                'rate_type' => 'daily',
                'price' => $rate->price,
                'price_type' => 'fixed',
                'price_expenses' => 0,
                'price_expenses_type' => 'fixed'
                
            ]);

            //Como tienen la id del mercado aqui, la tenemos que asignar en mercado
            $market = Market::find($rate->market_id);
            $market->rate_id = $rate->id;
            $market->save();
        }

        print('Rates migrados correctamente<br>');
    }

    public function migrateMarketUser()
    {
        DB::table('market_user')->truncate();

        $market_users = DB::connection('migration')->table('user_market')->get();

        foreach($market_users as $market_user){
            DB::table('market_user')->insert([
                'user_id' => $market_user->user_id,
                'market_id' => $market_user->market_id
            ]);
        }

        print('Market_user migrados correctamente<br>');
    }

    public function migrateCalendar()
    {
        DB::table('calendar')->truncate();

        $calendars = DB::connection('migration')->table('calendar')->get();

        foreach($calendars as $calendar){
            Calendar::create([
                'id' => $calendar->id,
                'market_id' => $calendar->market_id,
                'date' => $calendar->date
            ]);
        }

        print('Dias del calendario migrados correctamente<br>');
    }

    public function createBasicClasses()
    {
        DB::table('classes')->truncate();

        Classe::create([
            'id' => 1,
            'name' => 'Parada'
        ]);

        print('Creado clase básica para Paradas<br>');
    }

    public function migrateStalls()
    {
        DB::table('stalls')->truncate();
        DB::table('auth_prod_stall')->truncate();

        $stalls = DB::connection('migration')->table('stall')->get();

        foreach($stalls as $stall){
            $created_stall = Stall::create([
                'id' => $stall->id,
                'market_id' => $stall->market_id,
                'rate_id' => Market::find($stall->market_id)->rate_id,
                'active' => $stall->active,
                'num' => $stall->num,
                'length' => $stall->length,
                'market_group_id' => $stall->market_id, //El martket group sera el id del mercado, ya que al crear mercados, creamos un grupo de mercado con la id i el nombre del mercado
                'classe_id' => 1, //clase 1 son paradas, en migracio tot son parades, ya que aixo no existia abans
                'type' => 'concession' //este campo no existia antes, lo dejamos como concession que es lo que se llevava antes
            ]);

            $created_stall->auth_prods()->attach($stall->auth_prod_id);
        }

        print('Stalls migrados correctamente<br>');
    }

    public function migrateOwner()
    {
        DB::table('persons')->truncate();
        DB::table('substitute_person')->truncate();

        $owners = DB::connection('migration')->table('owner')->get();

        foreach($owners as $owner){
            $person = Person::create([
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'dni' => $owner->dni,
                'type_address' => 'CA', //Este campo no existia, lo marcamos como Ca
                'address' => $owner->address,
                //'number_address' => $owner,
                'phone' => $owner->phone,
                'iban' => $owner->iban,
                //'ref_domiciliacion' => $owner,
                //'date_domicilacion' => $owner,
                'image' => $owner->image != '' ? app('tenant')->name."/person/".$owner->image : NULL,
                'pdf1' => $owner->pdf1 != '' ? app('tenant')->name."/person/".$owner->pdf1 : NULL,
                'pdf2' => $owner->pdf2 != '' ? app('tenant')->name."/person/".$owner->pdf2 : NULL,
                'city' => $owner->city,
                'zip' => $owner->zip,
                'region' => $owner->region,
                'province' => $owner->province,
            ]);
        }

        //Buscamos los owners que tienen substitutos, para crear el substituto, o mirar si ya existe
        $owners_substitutes = DB::connection('migration')->table('owner')->where('sdni', '!=', NULL)->get();
        foreach($owners_substitutes as $owner){
            //Si en owner, tiene substituto, se crea como persona, i se le hace attach a la persona principal
            if($owner->sdni){
                //Primero buscamos si la persona ya existe
                $substituto = Person::where('dni', $owner->sdni)->first();
                if(!$substituto){
                    $substituto = Person::create([
                        'dni' => $owner->sdni,
                        'name' => $owner->sname,
                        'type_address' => 'CA', //Este campo no existia, lo marcamos como Ca
                        'address' => $owner->saddress,
                        'city' => $owner->scity,
                        'zip' => $owner->szip,
                        'region' => $owner->sregion,
                        'province' => $owner->sprovince,
                        'phone' => $owner->sphone,
                        'email' => $owner->semail,
                        'pdf1' => $owner->pdf3 != '' ? app('tenant')->name."/person/".$owner->pdf3 : NULL,
                        'image' => $owner->image_suplent != '' ? app('tenant')->name."/person/".$owner->image_suplent : NULL,
                    ]);
                }

                //Buscamos la persona y le asignamos el substituto creado
                $person = Person::find($owner->id);
                $person->substitutes()->attach($substituto->id);
            }
        }

        print('Personas migradas correctamente<br>');
    }

    public function migrateOwnerstall()
    {
        DB::table('concessions')->truncate();
        DB::table('historicals')->truncate();

        $ownerstalls = DB::connection('migration')->table('ownerstall')->get();

        foreach($ownerstalls as $ownerstall){
            Historical::create([
                'id' => $ownerstall->id,
                'stall_id' => $ownerstall->stall_id,
                'person_id' => $ownerstall->owner_id,
                'start_at' => $ownerstall->discharge_date,
                'ends_at' => $ownerstall->deactivated_at,
                'end_vigencia' => $ownerstall->expire_date,
            ]);

            Concession::create([
                'auth_prod_id' => $ownerstall->auth_prod_id,
                'stall_id' => $ownerstall->stall_id,
                'start_at' => $ownerstall->discharge_date,
                'end_at' => $ownerstall->expire_date,
             ]);
        }

        print('Historial y concesiones migradas correctamente<br>');
    }

    public function migrateAbsences()
    {
        DB::table('absences')->truncate();

        $absences = DB::connection('migration')->table('absence')->get();

        foreach($absences as $absence){
            $historical = Historical::find($absence->ownerstall_id);
            Absence::create([
                'id' => $absence->id, 
                'person_id' => $historical->person_id, 
                'stall_id' => $historical->stall_id, 
                'type' => 'justificada', 
                'cause' => $absence->description, 
                'document' => $absence->document != '' ? app('tenant')->name.'/absence/'.$absence->document : NULL, 
                'start' => $absence->start_at, 
                'end' => $absence->end_at
            ]);
        }

        print('Absencias migradas correctamente<br>');
    }

    public function migrateCommunications()
    {
        DB::table('communications')->truncate();
        DB::table('communication_stall')->truncate();
        DB::table('communication_person')->truncate();

        $communications = DB::connection('migration')->table('communications')->get();

        foreach($communications as $communication){
            $send = Communication::create([
                'id' => $communication->id,
                'market_id' => $communication->market_id,
                'sector_id' => $communication->sector_id,
                'auth_prod_id' => $communication->auth_prod_id,
                'user_id' => $communication->user_id,
                'type' => $communication->type,
                'title' => $communication->title,
                'message' => $communication->message
            ]);

            $historical = Historical::find($communication->ownerstall_id);
            if($historical){
                $send->stalls()->attach($historical->stall_id);
                $send->persons()->attach($historical->person_id);
            }
            
        }

        print('Communications migradas correctamente<br>');
    }

    public function migrateContactEmails()
    {
        DB::table('contact_emails')->truncate();

        $contacts = DB::connection('migration')->table('note_emails')->get();

        foreach($contacts as $contact){
            ContactEmail::create([
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email
            ]);
        }

        print('Contact emails (antic note_emails) migradas correctamente<br>');
    }

    public function migrateInvoices()
    {
        DB::table('invoices')->truncate();

        $invoices = DB::connection('migration')->table('invoice')->get();

        foreach($invoices as $invoice){
            $historical = Historical::find($invoice->ownerstall_id);
            Invoice::create([
                'id' => $invoice->id,
                'person_id' => $historical->person_id,
                'stall_id' => $historical->stall_id,
                'month' => date('n', strtotime($invoice->month)),
                'years' => date('Y', strtotime($invoice->month)),
                'qty_days' => $invoice->qty,
                'length' => $invoice->length,
                'price' => $invoice->price,
                'expenses' => 0,
                'subtotal' => $invoice->total,
                'discount' => 0,
                'total' => $invoice->total,
                'paid' => $invoice->paid,
                'type' => $invoice->type,
            ]);
        }

        print('Invoices migradas correctamente<br>');
    }

    //Comentar el observer
    public function migrateIncidences()
    {
        DB::table('incidences')->truncate();

        $incidences = DB::connection('migration')->table('notes')->get();

        foreach($incidences as $incidence){
            $historical = Historical::find($incidence->ownerstall_id);
            $calendar = Calendar::find($incidence->calendar_id);
            $contact_email = ContactEmail::where('email', $incidence->sent_to)->first();
            DB::table('incidences')->insert([
                'id' => $incidence->id,
                'person_id' => isset($historical) ? $historical->person_id : NULL,
                'stall_id' => isset($historical) ? $historical->stall_id : NULL,
                'market_id' => $incidence->market_id,
                'contact_email_id' => isset($contact_email) ? $contact_email->id : NULL,
                'title' => $incidence->title,
                'type' => 'owner_incidence',
                'add_absence' => 0,
                'description' => $incidence->description,
                'images' => $incidence->images != '' ? '["'.app('tenant')->name.'\/incidence\/'.$incidence->images.'"]' : NULL,
                'status' => isset($incidence->solved_at) ? 'solved' : 'pending',
                'date_incidence' => isset($calendar) ? $calendar->date : $incidence->created_at, //Y-m-d
                'send' => isset($contact_email) ? 1 : 0,
                'send_at' => $incidence->sent_to,
                'date_solved' => $incidence->solved_at,
                'can_mount_stall' => $incidence->can_mount_stall
            ]);
        }

        print('Incidences migradas correctamente<br>');
    }
}
