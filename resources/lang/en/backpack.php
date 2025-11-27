<?php


return [
    'persons' => [
        'single' => 'Persona',
        'plural' => 'Persones',
        'substitutes' => 'Substituts',
        'dni' => 'DNI',
        'name' => 'Nom',
        'address' => 'Direcció',
        'email' => 'Email',
        'phone' => 'Telèfon',
        'city' => 'Ciutat',
        'zip' => 'Codi postal',
        'region' => 'Regio',
        'province' => 'Provincia',
        'iban' => 'IBAN',
        'image' => 'Foto',
        'pdf1' => 'Alta IAE',
        'pdf2' => 'Alta autònoms'
    ],
    'sectors' => [
        'single' => 'Sector',
        'plural' => 'Sectors',
        'name' => 'Nom',
        'id' => 'ID',
        'created_at' => 'Creat el'
    ],
    'auth_prods' => [
        'single' => 'Producte',
        'plural' => 'Productes Autoritzats',
        'name' => 'Nom',
        'id' => 'ID',
        'sector_id' => 'Sector'
    ],
    'markets' => [
        'single' => 'Mercat',
        'plural' => 'Mercats',
        'name' => 'Nom',
        'id' => 'ID',
        'town_id' => 'Poble',
        'rates' => 'Rates'
    ],
    'stalls' => [
        'single' => 'Parada',
        'plural' => 'Parades',
        'id' => 'Id',
        'num' => 'Num',
        'length' => 'Espai m<sup>2</sup> / Modules',
        'active' => 'Actiu',
        'market_id' => 'Mercat',
        'auth_prod_id' => 'Producte Autoritzat',
        'image' => 'Imatge'
    ],
    'towns' => [
        'single' => 'Poble',
        'plural' => 'Pobles',
        'name' => 'Nom',
        'id' => 'ID',
        'created_at' => 'Creat el'
    ],
    'rates' => [
        'single' => 'Tarifa',
        'plural' => 'Tarifes',
        'id' => 'ID',
        'market_id' => 'Mercat',
        'price' => 'Preu',
    ],
    'incidences' => [
        'single' => 'Incidència',
        'plural' => 'Incidències',
        'title' => 'Títol',
        'description' => 'Descripció',
        'image' => 'Imatge',
        'date_incidence' => 'Data d\'incidència',
        'status' => 'Estat',
        'statuses' => [
            'pending' => 'Pendents',
            'solved' => 'Solucionat'
        ],
        'date_solved' => 'Resolt amb data'
    ],
    'concessions' => [
        'single' => 'Concessio',
        'plural' => 'Concessions',
        'id' => 'ID',
        'stall_id' => 'Parada',
        'start_at' => 'Desde',
        'end_at' => 'Fins',
        'auth_prod_id' => 'Producte Autoritzat',
        'pdf' => 'Pdf'
    ],
    'historicals' => [
        'single' => 'Historial',
        'plural' => 'Historials',
        'id' => 'ID',
        'name' => 'Nom',
        'stall_id' => 'Parada',
        'person_id' => 'Persona',
        'start_at' => 'Desde',
        'ends_at' => 'Fins'
    ],
    'calendar' => [
        'single' => 'Calendar',
        'plural' => 'Calendars',
        'date_incidence' => 'Date',
    ]
];
