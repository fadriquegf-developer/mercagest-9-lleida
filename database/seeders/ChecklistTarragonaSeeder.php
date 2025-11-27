<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChecklistType;
use App\Models\ChecklistQuestion;

class ChecklistTarragonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // stalls
        $this->checklist();
        $this->checklistStallMercadetsNoAlim();
        $this->checklistStallTorreforta();

        // markets
        $this->checklistMarketBonavista();
        $this->checklistMarketNoBonavista();
        $this->checklistMarketTorreforta();
        $this->checklistMarketCentral();
    }

    private function checklist()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist de Parades (Mercadets)',
            'type' => ChecklistType::TYPE_STALL,
            'allowed_sector' => '[2]'
        ]);

        $checklist->markets()->attach([
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        ]);

        $raw = [
            'general' => [
                [
                    'text' => 'Incompliment de l\'horari de venda al públic.',
                    'regulation' => 'Art. 26.2.b Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Disposar de personal treballant que no compleix normativa laboral vigent',
                    'regulation' => 'Art. 26.3.e Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Venda de productes no autoritzats',
                    'regulation' => 'Art. 26.4.d Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'Vendre productes respecte els quals no sigui possible demostrar la seva possessió licita',
                    'regulation' => 'Art. 26.4.e Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'No ocupar el lloc de venda (durant quatre setmanes consecutives, sense causa justificada i que la no assistència estigui acreditada documentalment)',
                    'regulation' => 'Art. 26.4.h Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'No exhibir en un lloc ben visible la targeta d\'identificació de la parada durant les hores establertes per la venda',
                    'regulation' => 'Art. 26.3.f Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Desacatar de forma ostensible les instruccions o mandats de la direcció del mercat (nova)',
                    'regulation' => 'Art. 26.4.k Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'Canviar la ubicació o el producte, sense l\'autorització corresponent. (nova)',
                    'regulation' => 'Art. 26.3.d Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Disposar de menors en edat d\'escolarització obligatòria que exerceixin les activitats de venda, col·locació de productes o de cobraments. (nova)',
                    'regulation' => 'Art. 26.4.n Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
            ],
            'condicionament parada' => [
                [
                    'text' => 'Mercaderia sobresortint del taulell',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'No haver-hi indicadors de PVP a la parada',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Alterar il.legalment el preu dels productes',
                    'regulation' => 'Art. 26.3.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Presència d\'animals de companyia a la parada',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'No haver instal.lat faldó',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Manca suficient de neteja i higiene de les parades, de la zona i dels seus productes',
                    'regulation' => 'Art. 26.2.a Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Realització incorrecta del reciclatge',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
            ],
            'mesures covid' => [
                [
                    'text' => 'Incomplir les condicions higièniques i sanitàries dels productes i les mínimes normes de disposició a la parada',
                    'regulation' => 'Art. 26.4.f Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
            ],
            'alimentació' => [
                [
                    'text' => 'Vendre els productes alimentaris sense complir els requisits relatius a la seva exposició, conservació, manipulació i venda.',
                    'regulation' => 'Art. 26.4.g Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
            ]
        ];

        $this->createItems($checklist, $raw);
    }


    private function checklistMarketBonavista()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist exclusiu del mercadet de Bonavista',
            'type' => ChecklistType::TYPE_MARKET
        ]);

        $checklist->markets()->attach([
            1,
        ]);

        $raw = [
            'General' => [
                [
                    'text' => 'Els accessos per a vehicles de bombers estan lliures d\'obstacles.',
                ],
                [
                    'text' => 'Les New Jersey que donen a Bonavista tenen els indicadors de sortida d\'emergència correctament orientats.',
                ],
                [
                    'text' => 'Els carrers principals de 6 metres d\'amplada estan lliures d\'obstacles.',
                ],
                [
                    'text' => 'Les terrasses del carrer inferior del mercadet no envaeixen els 6 metres de circulació.',
                ],
                [
                    'text' => 'S\'han distribuït els extintors de carro als paradistes que ho necessiten.',
                ],
                [
                    'text' => 'S\'han distribuït els extintors de carro a les zones de contenidors.',
                ],
                [
                    'text' => 'S\'ha verificat que els indicadors d\'evacuació dels paradistes que en disposen estan correctament col·locats a la parada.',
                ],
                [
                    'text' => 'S\'ha verificat que els cartells de recorregut de zones de confinament no estan malmesos.',
                ],
                [
                    'text' => 'Les carpes i la cartelleria corporativa s\'han instal·lat en zones que no impedeixen el pas dels vehicles d\'emergència.',
                ],
                [
                    'text' => 'Els treballadors de Mercats disposen les armilles reflectants.',
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }

    private function checklistMarketNoBonavista()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist de mercadets (tots els mercadets a excepció de Bonavista)',
            'type' => ChecklistType::TYPE_MARKET
        ]);

        $checklist->markets()->attach([
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        ]);

        $raw = [
            'General' => [
                [
                    'text' => 'Els accessos per a vehicles de bombers estan lliures d\'obstacles',
                ],
                [
                    'text' => 'Les carpes i cartellera corporativa s\'han instal·lat en zones que no impedeixen el pas dels vehicles d\'emergència',
                ],
                [
                    'text' => 'Els carrers principals de 6 metres d\'amplada estan lliures d\'obstacles.',
                ],
                [
                    'text' => 'Les terrasses del carrer inferior del mercadet no envaeixen els 6 metres de circulació.',
                ],
                [
                    'text' => 'S\'han distribuït els extintors de carro als paradistes que ho necessiten.',
                ],
                [
                    'text' => 'S\'han distribuït els extintors de carro a les zones de contenidors.',
                ],
                [
                    'text' => 'S\'ha verificat que els indicadors d\'evacuació dels paradistes que en disposen estan correctament col·locats a la parada.',
                ],
                [
                    'text' => 'S\'ha verificat que els cartells de recorregut de zones de confinament no estan malmesos.',
                ],
                [
                    'text' => 'Les carpes i la cartelleria corporativa s\'han instal·lat en zones que no impedeixen el pas dels vehicles d\'emergència.',
                ],
                [
                    'text' => 'Els treballadors de Mercats disposen les armilles reflectants.',
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }


    private function checklistMarketTorreforta()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist Mercat de Torreforta',
            'type' => ChecklistType::TYPE_MARKET
        ]);

        $checklist->markets()->attach([
            15,
            16,
        ]);

        $raw = [
            'General' => [
                [
                    'text' => 'Obertura/Tancament portes',
                ],
                [
                    'text' => 'Enllumenat general',
                ],
                [
                    'text' => 'Accessos al mercat',
                ],
                [
                    'text' => 'Neteja interior',
                ],
                [
                    'text' => 'Muntacàrregues',
                ],
                [
                    'text' => 'Estat WC',
                ],
                [
                    'text' => 'Estat àrea Càrrega/Descàrrega',
                ],
                [
                    'text' => 'Estat cambres de fred',
                ],
                [
                    'text' => 'Estat tanques botigues perímetre exterior',
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }

    private function checklistMarketCentral()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist Mercat Central de Tarragona',
            'type' => ChecklistType::TYPE_MARKET
        ]);

        $checklist->markets()->attach([
            13,
            14,
        ]);

        $raw = [
            'General' => [
                [
                    'text' => 'Obertura/Tancament portes',
                ],
                [
                    'text' => 'Enllumenat general',
                ],
                [
                    'text' => 'Rampes mecàniques',
                ],
                [
                    'text' => 'Correcta arrencada ascensors',
                ],
                [
                    'text' => 'Muntacàrregues',
                ],
                [
                    'text' => 'Cambra de bombes',
                ],
                [
                    'text' => 'Estat WC',
                ],
                [
                    'text' => 'Estat àrea Càrrega/Descàrrega',
                ],
                [
                    'text' => 'Estat Zona Comercial -1',
                ],
                [
                    'text' => 'Centraleta contra incendis',
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }

    private function checklistStallTorreforta()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist parades Mercats Sedentaris (Torreforta i Central)',
            'type' => ChecklistType::TYPE_STALL
        ]);

        $checklist->markets()->attach([
            13,
            14,
            15,
            16,
        ]);

        $raw = [
            'General' => [
                [
                    'text' => 'Compliment horari',
                ],
                [
                    'text' => 'Personal treballant',
                ],
                [
                    'text' => 'Producte de venda',
                ],
                [
                    'text' => 'Ocupació',
                ],
                [
                    'text' => 'Altres compliments normatius',
                ],
            ],
            'Hàbits d\'higiene' => [
                [
                    'text' => 'Indumentària adequada i neta',
                ],
                [
                    'text' => 'Es menja o fuma',
                ],
                [
                    'text' => 'Presència no justificada de persones alienes a l\'activitat de venda',
                ],
                [
                    'text' => 'Presència d\'animals de companyia a la parada',
                ],
            ],
            'Cadena de fred' => [
                [
                    'text' => 'Aliments que necessiten fred fora de les neveres o congeladors',
                ],
            ],
            'Gestió dels residus' => [
                [
                    'text' => 'Residus al terra',
                ],
            ],
            'Neteja i desinfecció' => [
                [
                    'text' => 'Productes de neteja i desinfecció en envàs original i amb etiqueta',
                ],
                [
                    'text' => 'Productes de neteja junt amb els aliments',
                ],
                [
                    'text' => 'No utilitzar esprais o trampes antinsectes o rosegadors',
                ],
            ],
            'Recepció i emmagatzematge de la mercaderia' => [
                [
                    'text' => 'Aliments de consum en cru separats i protegits correctament dels elaborats',
                ],
                [
                    'text' => 'Aliments en contacte directe amb terra, parets i/o sostre',
                ],
                [
                    'text' => 'Deixalles en la zona d\'emmagatzematge dels aliments',
                ],
            ],
            'Transport' => [
                [
                    'text' => 'Vehicle condicionat per a transport de mercaderies',
                ],
                [
                    'text' => 'Bon estat de neteja',
                ],
                [
                    'text' => 'Bon estat de manteniment',
                ],
                [
                    'text' => 'Que no deixa caure aigua',
                ],
            ],
            'Condicionament de la parada' => [
                [
                    'text' => 'Caixes plenes d\'aliments arrossegades o dipositades directament al terra',
                ],
                [
                    'text' => 'Mercaderia sobresortint del taulell',
                ],
                [
                    'text' => 'Peix sense gel o productes que no estiguin en nevera',
                ],
                [
                    'text' => 'Aigua sortint de la parada',
                ],
                [
                    'text' => 'Indicadors amb punxa del PVP',
                ],
                [
                    'text' => 'Bosses, contenidors i papers d\'embolicar aptes per a ús alimentari. No ús de paper de diari',
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }

    private function checklistStallMercadetsNoAlim()
    {
        $checklist = ChecklistType::create([
            'name' => 'Checklist de Parades (Mercadets) no alimentàries',
            'type' => ChecklistType::TYPE_STALL,
            'forbidden_sector' => '[2]'
        ]);

        $checklist->markets()->attach([
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        ]);

        $raw = [
            'general' => [
                [
                    'text' => 'Incompliment de l\'horari de venda al públic.',
                    'regulation' => 'Art. 26.2.b Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Disposar de personal treballant que no compleix normativa laboral vigent',
                    'regulation' => 'Art. 26.3.e Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Venda de productes no autoritzats',
                    'regulation' => 'Art. 26.4.d Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'Vendre productes respecte els quals no sigui possible demostrar la seva possessió licita',
                    'regulation' => 'Art. 26.4.e Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' =>  ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'No ocupar el lloc de venda (durant quatre setmanes consecutives, sense causa justificada i que la no assistència estigui acreditada documentalment)',
                    'regulation' => 'Art. 26.4.h Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'No exhibir en un lloc ben visible la targeta d\'identificació de la parada durant les hores establertes per la venda',
                    'regulation' => 'Art. 26.3.f Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Desacatar de forma ostensible les instruccions o mandats de la direcció del mercat (nova)',
                    'regulation' => 'Art. 26.4.k Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
                [
                    'text' => 'Canviar la ubicació o el producte, sense l\'autorització corresponent. (nova)',
                    'regulation' => 'Art. 26.3.d Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Disposar de menors en edat d\'escolarització obligatòria que exerceixin les activitats de venda, col·locació de productes o de cobraments. (nova)',
                    'regulation' => 'Art. 26.4.n Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
            ],
            'condicionament parada' => [
                [
                    'text' => 'Mercaderia sobresortint del taulell',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'No haver-hi indicadors de PVP a la parada',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Alterar il.legalment el preu dels productes',
                    'regulation' => 'Art. 26.3.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_HIGH,
                ],
                [
                    'text' => 'Presència d\'animals de companyia a la parada',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'No haver instal.lat faldó',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Manca suficient de neteja i higiene de les parades, de la zona i dels seus productes',
                    'regulation' => 'Art. 26.2.a Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
                [
                    'text' => 'Realització incorrecta del reciclatge',
                    'regulation' => 'Art. 26.2.c Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_LOW,
                ],
            ],
            'mesures covid' => [
                [
                    'text' => 'Incomplir les condicions higièniques i sanitàries dels productes i les mínimes normes de disposició a la parada',
                    'regulation' => 'Art. 26.4.f Ordenança reguladora Venda No Sedentària al municipi de Tarragona',
                    'severity' => ChecklistQuestion::SEVERITY_VERY_HIGH,
                ],
            ],
        ];

        $this->createItems($checklist, $raw);
    }

    private function createItems($checklist, $raw)
    {
        foreach ($raw as $name => $questions) {
            $group = $checklist->checklist_groups()->create([
                'name' => $name
            ]);

            foreach ($questions as $question) {
                $group->checklist_questions()->create([
                    'text' => $question['text'],
                    'regulation' => $question['regulation'] ?? null,
                    'severity' => $question['severity'] ?? null,
                ]);
            }
        }
    }
}
