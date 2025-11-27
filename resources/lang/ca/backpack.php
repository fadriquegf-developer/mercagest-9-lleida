<?php


return [
    'yes' => 'Si',
    'no' => 'No',
    'btn_send' => 'Enviar',
    'persons' => [
        'single' => 'Paradista',
        'plural' => 'Paradistes',
        'num' => 'Núm. Llicència',
        'substitutes' => 'Acompanyants',
        'dni' => 'DNI',
        'name' => 'Nom',
        'check_accreditation' => 'Comprovar acreditació',
        'type' => 'Tipus',
        'type_address' => 'Tipus de via',
        'address' => 'Direcció',
        'number_address' => "Número",
        'extra_address' => 'Informacio extra direcció',
        'email' => 'Email',
        'phone' => 'Telèfon',
        'city' => 'Ciutat',
        'zip' => 'Codi postal',
        'region' => 'Comarca',
        'province' => 'Província',
        'name_titular' => 'Nom Titular Compte',
        'nif_titular' => 'NIF Titular Compte',
        'iban' => 'IBAN',
        'date_domiciliacion' => 'Data Domiciliació',
        'ref_domiciliacion' => 'Ref. Domiciliació',
        'image' => 'Foto',
        'docs' => 'Documents',
        'doc' => 'Document',
        'pdf1' => 'Alta IAE',
        'pdf2' => 'Alta autònoms',
        'historicals' => 'Parades',
        'market' => 'Mercat',
        'contact_info' => 'Informació de contacte',
        'substitute_name' => 'Nom Acompanyant',
        'substitute_img' => 'Foto Acompanyant',
        'substitute_dni' => 'DNI Acompanyant',
        'substitute_dni_img' => 'Foto DNI Acompanyant',
        'comment' => 'Comentari',
        'filter_unsubscribe' => [
            'title' => 'Paradistes ocults',
            'inactive' => 'Veure paradistes ocults',
            'active' => 'Veure paradistes visibles',
        ],
        'filter_active' => [
            'title' => 'Visualització parades',
            'active' => 'Veure només parades actives',
            'inactive' => 'Veure totes les parades (actives/ no actives)',
        ],
        'unsubscribe' => 'Ocultar',
        'unsubscribe_question' => 'Esteu segurs que voleu :text al paradista :name?',
        'restore' => 'Restaurar',
        'unsubscribe_success' => 'Paradista ocultat correctament.',
        'restore_success' => 'Paradista restaurat correctament.',
        'legal_doc1' => 'Seguretat social',
        'legal_doc2' => 'Hisenda',
        'legal_doc3' => 'Document legal',
        'show' => [
            'historicals' => [
                'tab' => 'Històrics',
                'table' => [
                    'start' => 'Data inici',
                    'end' => 'Data Baixa',
                    'family_transfer' => 'Motiu transferència',
                    'stall' => 'Parada',
                    'market' => 'Mercat'
                ]
            ],
            'absences' => [
                'tab' => 'Absències',
                'table' => [
                    'start' => 'Data inici',
                    'end' => 'Data fi',
                    'type' => 'Tipus',
                    'cause' => 'Motiu'
                ]
            ],
            'incidences' => [
                'tab' => 'Incidències',
                'table' => [
                    'title' => 'Títol',
                    'status' => 'Estat',
                    'stall' => 'Parada',
                    'market' => 'Mercat'
                ]
            ],
            'receipts' => [
                'tab' => 'Rebuts',
                'table' => [
                    'id' => 'Núm. Rebut',
                    'total' => 'Total',
                    'paid' => 'Pagat',
                    'claimed' => 'Reclamat'
                ]
            ],
            'legal_docs' => [
                'tab' => 'Documents',
                'table' => [
                    'type' => 'Tipus',
                    'user' => 'Usuari firma',
                    'date' => 'Data',
                    'show' => 'Veure document',
                    'to_sign' => 'Firmar'
                ]
            ],
        ],
        'errors' => [
            'accreditation_404' => 'No s\'ha trobat cap acreditació amb aquest número de llicència.'
        ]
    ],
    'sectors' => [
        'single' => 'Sector',
        'plural' => 'Sectors',
        'name' => 'Nom',
        'id' => 'ID',
        'created_at' => 'Creat el'
    ],
    'auth_prods' => [
        'single' => 'Producte Autoritzat',
        'plural' => 'Productes Autoritzats',
        'name' => 'Nom',
        'id' => 'ID',
        'sector_id' => 'Sector',
        'sector_default' => 'Seleccioneu un sector',
    ],
    'markets' => [
        'single' => 'Mercat',
        'plural' => 'Mercats',
        'name' => 'Nom',
        'id' => 'ID',
        'town_id' => 'Municipi',
        'rates' => 'Tarifa',
        'days_of_week' => 'Dies de mercat',
        'market_group' => 'Tipus de Mercat',
        'town_default' => 'Seleccioneu un municipi',
        'rates_default' => 'Seleccioneu una tarifa',
        'option_days_of_week' => [
            'Diumenge',
            'Dilluns',
            'Dimarts',
            'Dimecres',
            'Dijous',
            'Divendres',
            'Dissabte',
        ]
    ],
    'stalls' => [
        'single' => 'Parada',
        'plural' => 'Parades',
        'market_group_id' => 'Tipus de Mercat',
        'years' => 'Any',
        'month' => 'Periode',
        'id' => 'ID',
        'num' => 'Núm. Parada',
        'rate_id' => 'Tarifa',
        'rate_hint' => 'En cas de no especificar una tarifa s\'agafarà la tarifa per defecte assignada al mercat.',
        'titular' => 'Titular',
        'from' => 'Des de',
        'to' => 'Fins',
        'certification' => 'Certificació',
        'unsubscribe' => 'Donar de baixa',
        'subscribe' => 'Assignar titular',
        'unsubscribe_message' => 'Baixa correcta!',
        'subscribe_message' => 'Alta correcta!',
        'accreditation' => 'Acreditació',
        'concession' => 'Concessió',
        'comment' => 'Comentari',
        'length' => 'Espai m<sup>2</sup> / Mòduls',
        'space' => 'Espai',
        'active' => 'Actiu',
        'market_id' => 'Mercat',
        'market_group' => 'Tipus de Mercat',
        'auth_prod_id' => 'Producte Autoritzat',
        'active' => 'Actiu',
        'image' => 'Imatge',
        'reason' => 'Motiu',
        'market' => 'Mercat',
        'classe_id' => 'Classe',
        'expedientes' => 'Expedients',
        'add' => 'Assignar parada',
        'marcket_default' => 'Seleccioneu un mercat',
        'market_group_default' => 'Seleccioneu un grup de mercat',
        'auth_prod_default' => 'Seleccioneu un producte autoritzat',
        'classe_default' => 'Seleccioneu una classe',
        'cant_delete' => 'No es pot eliminar la parada perquè ja te informació associada.',
        'create_checklist' => 'Crear checklist',
        'trimestral' => 'Trimestre',
        'visible' => 'Visible',
        'reasons' => [
            'expansion' => 'Ampliació',
            'voluntary' => 'Baixa voluntària',
            'change_name' => 'Canvi de nom',
            'change_location' => 'Canvi ubicació',
            'non-compliance_file' => 'Expedient incompliment normativa',
            'end' => 'Fi de la carencia',
            'retirement' => 'Jubilació',
            'death' => 'Mort',
            'reduction' => 'Reducció',
            'transfer' => 'Traspàs de parada',
        ],
        'type' => 'Tipus',
        'types' => [
            'concession' => 'Concessió',
            'rent' => 'Lloguer',
        ],
        'explained_reason' => 'Observacions',
        'list' => [
            'modal' => [
                'header' => 'Estàs segur que vols donar-lo de baixa?',
                'header-alta' => 'Assignar titular',
                'footer' => [
                    'close' => 'Tancar',
                    'submit' => 'Donar de baixa',
                    'submit-alta' => 'Assignar titular'
                ]
            ]
        ],
        'show' => [
            'historicals' => [
                'tab' => 'Històric',
                'table' => [
                    'start' => 'Data inici',
                    'end' => 'Data Baixa',
                    'end_vigencia' => 'Data fi vigència',
                    'person' => 'Paradista',
                    'reason' => 'Motiu baixa',
                    'family_transfer' => 'Motiu transferència',
                    'explained_reason' => 'Observacions',
                    'market' => 'Mercat',
                    'without_date' => 'Sense data fi'
                ]
            ],
            'absences' => [
                'tab' => 'Absències',
                'table' => [
                    'start' => 'Data inici',
                    'end' => 'Data fi',
                    'stall' => 'Parada',
                    'person' => 'Paradista',
                    'cause' => 'Motiu',
                    'document' => 'Enllaç Document'
                ]
            ],
            'incidences' => [
                'tab' => 'Incidències',
                'table' => [
                    'title' => 'Títol',
                    'status' => 'Estat',
                    'stall' => 'Parada',
                    'type' => 'Tipus',
                    'market' => 'Mercat',
                    'date' => 'Data Incidència'
                ]
            ],
            // 'checklists' => [
            //     'tab' => 'Checklists',
            //     'table' => [
            //         'name' => 'Checklist',
            //         'pdf' => 'PDF',
            //         'show' => 'Vista prèvia'
            //     ]
            // ],
        ],
        'errors' => [
            'overlap_dates' => 'L\'interval de dates que està intentant introduir coincideix amb l\'històric de dates dels titulars d\'aquesta parada.',
            'after_start' => 'Data fi vigència ha de ser una data posterior a Data inici.',
            'no_stall' => 'No s\'ha trobat la parada.',
            'end_date' => 'La data de baixa no pot ser anterior o posterior al rang de dates assignat al titular de la parada..'
        ]
    ],
    'extensions' => [
        'single' => 'Extensió',
        'plural' => 'Extensions',
        'stall_id' => 'Parada',
        'person_id' => 'Paradista',
        'extension' => 'Extensió',
        'description' => 'Descripció',
        'length' => 'Tamany',
    ],
    'bonuses' => [
        'single' => 'Bonificació',
        'plural' => 'Bonificacions',
        'stall_id' => 'Parada',
        'market_id' => 'Mercat',
        'type' => 'Tipus',
        'types' => [
            'market' => 'Mercat',
            'group' => 'Grup de mercats',
            'individual' => 'Individual'
        ],
        'amount_type' => 'Tipus d\'import',
        'amount_types' => [
            'discount' => 'Import fixe',
            'percentage' => 'Percentatge',
            'days' => 'Número de dies a bonificar'
        ],
        'start_at' => 'Data inici',
        'ends_at' => 'Data fi',
        'title' => 'Títol Rebut',
        'reason' => 'Motiu',
        'amount' => 'Quantitat',
    ],
    'towns' => [
        'single' => 'Municipi',
        'plural' => 'Municipis',
        'name' => 'Nom',
        'id' => 'ID',
        'created_at' => 'Data de creació'
    ],
    'rates' => [
        'single' => 'Tarifa',
        'plural' => 'Tarifes',
        'id' => 'ID',
        'market_id' => 'Mercat',
        'price' => 'Preu',
        'price_expenses' => 'Preu despeses',
        'price_type' => 'Tipus',
        'price_expenses_type' => 'Tipus despeses',
        'rate_type' => 'Tipus de Tarifa'
    ],
    'incidences' => [
        'single' => 'Incidència',
        'plural' => 'Incidències',
        'new' => 'Nova incidència',
        'title' => 'Títol',
        'description' => 'Descripció',
        'image' => 'Imatge',
        'date_incidence' => 'Data d\'incidència',
        'status' => 'Estat',
        'type' => 'Tipus',
        'add_absence' => 'Afegir absència',
        'calendar_id' => 'Dia de mercat',
        'can_mount_stall' => 'S\'han pogut muntar les parades?',
        'types' => [
            'owner_incidence' => 'Incidència per parada/titular',
            'general_incidence' => 'Incidència de tipus general',
            'specific_activities' => 'Activitats puntuals'
        ],
        'statuses' => [
            'pending' => 'Incidència pendent',
            'solved' => 'Incidència resolta'
        ],
        'date_solved' => 'Resolt amb data',
        'contact_email' => 'E-mails de contacte',
        'send' => 'Enviar incidència',
        'contact_email_id' => 'E-mail de contacte',
        'send_at' => 'Data d\'enviament',
        'errors' => [
            'no_dates' => 'La data introduïda no coincideix amb un dia de mercat d\'aquesta parada',
            'no_titular' => 'La parada no té assignat cap titular, no es pot crear la incidència.',
            'stall_no_titular' => 'La parada :stall no té assignat cap titular, no es pot crear la incidència.',
            'date_between' => 'La data no pot ser anterior o posterior al rang de dates assignat al titular de la parada.'
        ]
    ],
    'concessions' => [
        'single' => 'Concessió',
        'plural' => 'Concessions',
        'id' => 'ID',
        'stall_id' => 'Parada',
        'start_at' => 'Data inici',
        'end_at' => 'Data fi',
        'auth_prod_id' => 'Producte Autoritzat',
        'pdf' => 'PDF'
    ],
    'historicals' => [
        'single' => 'Històric',
        'plural' => 'Històrics',
        'id' => 'ID',
        'name' => 'Nom',
        'stall_id' => 'Parada',
        'person_id' => 'Paradista',
        'start_at' => 'Data inici',
        'ends_at' => 'Data Baixa',
        'end_vigencia' => 'Data fi vigència',
        'reason' => 'Motiu Baixa',
        'family_transfer' => 'Motiu transferència',
        'family_transfer_default' => 'Seleccionar motiu',
        'date_range' => 'Interval de dates',
        'family_transfer_options' => [
            'non-family' => 'Transferència no familiar',
            'family' => 'Transferència familiar',
        ]
    ],
    'calendar' => [
        'single' => 'Dia de mercat',
        'plural' => 'Dies de mercat',
        'date' => 'Data',
        'add_calendar' => 'Afegir Data',
        'market_day' => 'Afegir Rang Dates',
        'day_report' => 'Informe diari',
        'add_abcense' => 'Afegir Absència',
        'add_incidence' => 'Afegir Incidència',
        'calendar_view' => 'Vista Calendari',
        'table_view' => 'Vista Llistat'
    ],
    'contact_emails' => [
        'single' => 'E-mail Contacte',
        'plural' => 'E-mails Contacte',
        'id' => 'ID',
        'name' => 'Nom',
        'email' => 'E-mail'
    ],
    'absences' => [
        'single' => 'Absència',
        'plural' => 'Absències',
        'id' => 'ID',
        'type' => 'Tipus',
        'types' => [
            'justificada' => 'Justificada',
            'no-justificada' => 'No Justificada'
        ],
        'person_id' => 'Paradista',
        'stall_id' => 'Parada',
        'calendar_id' => 'Calendari',
        'start' => 'Data inici',
        'end' => 'Data fi',
        'market' => 'Mercat',
        'cause' => 'Motiu',
        'document' => 'Document',
        'show_doc' => 'Veure document',
        'errors' => [
            'overlap_dates' => 'Aquesta parada ja te una absència per alguna d\'aquestes dates.'
        ]
    ],
    'market_days' => [
        'single' => 'Dia de Mercat',
        'plural' => 'Dies de Mercat',
        'id' => 'ID',
        'market_id' => 'Mercat',
        'start' => 'Data inici',
        'end' => 'Data fi',
        'days' => 'Dies'
    ],
    'table' => [
        'not_found' => 'No s\'ha trobat cap resultat',
        'not_invoices' => 'Actualment no hi han rebuts per aquesta parada'
    ],
    'communications' => [
        'single' => 'Comunicació',
        'plural' => 'Comunicacions',
        'id' => 'ID',
        'market_id' => 'Mercat',
        'sector_id' => 'Sector',
        'auth_prod_id' => 'Producte Autoritzat',
        'user_id' => 'Creat per l\'usuari',
        'marcket_default' => 'Tots els mercats',
        'stall_default' => 'Seleccioneu una parada',
        'person_default' => 'Seleccioneu un paradista',
        'type' => 'Tipus',
        'types' => [
            'email' => 'E-mail',
            'sms' => 'SMS'
        ],
        'title' => 'Títol',
        'message' => 'Missatge',
        'stalls' => 'Parada',
        'persons' => 'Paradista',
        'save' => 'Guardar',
        'check' => 'Enviar',
        'created_at' => 'Creat el',
        'updated_at' => 'Modificat el',
        'errors' => [
            'no_filter' => 'Error! si us plau, apliqueu un filtre',
            'no_results' => 'No hi ha cap parada que compleixi els filtres inidcats',
            'filter_required' => 'Es obligatori aplicar com a mínim 1 filtre ja sigui per mercat, parada o paradistes'
        ]
    ],
    'users' => [
        'single' => 'Usuari',
        'markets' => 'Mercats',
        'signature' => 'Firma',
        'tabs' => [
            'general' => 'General',
            'permissions' => 'Permisos'
        ]
    ],
    'roles' => [
        'single'   => 'Rols',
    ],
    'permissions' => [
        'single' => 'Permís',
        'show' => 'Vista prèvia',
        'list' => 'listar',
        'create' => 'crear',
        'update' => 'editar',
        'delete' => 'eliminar',
        'show_for_market' => 'Filtre per mercat'
    ],
    'settings' => [
        'single'   => 'Configuració',
    ],
    'logs' => [
        'single'   => 'Log',
    ],
    'invoices' => [
        'single' => 'Rebut',
        'plural' => 'Rebuts',
        'person_id' => 'Paradista',
        'stall_id' => 'Parada',
        'address_person_id' => 'Adreça Paradista',
        'num' => 'Núm. Rebut',
        'years' => 'Any',
        'month' => 'Mes',
        'trimestral' => 'Trimestre',
        'day' => 'Dia',
        'qty' => 'Quantitat',
        'length' => 'm2',
        'price' => 'Preu',
        'subtotal' => 'Subtotal',
        'discount' => 'Descompte',
        'total' => 'Total',
        'paid' => 'Data de cobrament',
        'is_paid' => 'Pagat?',
        'claimed' => 'Reclamació',
        'type' => 'Tipus',
        'types' => [
            'day' => 'Dia',
            'month' => 'Mes',
            'years' => 'Any'
        ],
        'print_and_pay' => 'Imprimir i marcar cobrat',
        'invoice' => 'Rebut',
        'market' => 'Mercat',
        'town' => 'Municipi',
        'details' => 'Detalls',
        'days' => 'Número de dies',
        'stall_length' => 'Metres de la parada',
        'not_paid' => 'Veure rebuts no pagats'
    ],
    'report' => [
        'single' => 'Informe',
        'plural' => 'Informes',
        'from' => 'Des de',
        'to' => 'Fins',
        'daily_report' => [
            'select' => 'Selecciona:',
            'incidence' => 'Incidència: ',
            'absence' => 'Absència: ',
            'presence' => 'Presència: ',
            'table' => [
                'person_name' => 'Paradista',
                'stall' => 'Parada',
                'market' => 'Mercat',
            ]
        ],
        'range' => 'Rang: ',
        'type' => 'Tipus: ',
        'types' => [
            'settlement of receipts' => 'Liquidació d\'ingressos',
            'accumulated amount' => 'Import acumulat'
        ],
        'file_name' => [
            'downloadSettlementOfReceipts' => 'liquidacioingressos',
            'downloadAccumulatedAmount' => 'importacumulat'
        ],
        'file_type' => "Tipus d'arxiu: ",
        'file_types' => [
            'csv' => 'csv',
            'xls' => 'xls'
        ],
        'market_type' => 'Tipus de mercat:',
        'download' => 'Descarregar',
        'download_alert' => 'Informe generat correctament!'
    ],
    'day_report' => [
        'single' => 'Informe diari',
    ],
    'maps' => [
        'single' => 'Mapa',
        'hint' => 'Heu de seleccionar un mercat per poder visualitzar aquest apartat',
        'index_label_info' => 'Mapa de parades del dia:',
        'index_label_date' => 'Seleccioneu una data:',
        'select' => 'Seleccionar',
        'alerts' => [
            'delete_abcence' => 'Al marcar com assistència \'SI\' s\'eliminaran les absències per a la parada i dia seleccionats. Voleu continuar?'
        ]
    ],
    'tickets' => [
        'single' => 'Rebut',
        'plural' => 'Rebuts',
        'name' => 'Nom',
        'stalls' => 'Parades',
        'markets' => 'Mercats',
        'type' => 'Tipus',
        'types' => [
            'market-group' => 'Grup de Mercat',
            'market' => 'Mercat',
            'stall' => 'Parada'
        ],
        'market_group' => 'Tipus de Mercat',
        'start_at' => 'Data inici',
        'end_at' => 'Data fi',
        'created_at' => 'Data de creació'
    ],
    'market_groups' => [
        'single' => 'Grup de Mercat',
        'plural' => 'Tipus de Mercats',
        'type' => 'Tipus',
        'gtt_type' => 'Concepte GTT',
        'title' => 'Títol',
        'payment' => 'Periodicitat de Pagament',
        'created_at' => 'Data de creació'
    ],
    'expedientes' => [
        'single'   => 'Expedient',
        'plural' => 'Expedients',
        'person_id' => 'Paradista',
        'num_expediente' => 'Núm. Expedient',
        'url' => 'Enllaç expedient'
    ],
    'classes' => [
        'single' => 'Classe',
        'name' => 'Nom classe'
    ],
    'checklists' => [
        'single'   => 'Checklist',
        'plural' => 'Checklists',
        'create_stall' => 'Crear checklist parades',
        'create_market' => 'Crear checklist mercat',
        'hint' => 'Seleccioneu el checklist que voleu crear',
        'text_placeholder' => 'Comentari',
        'type' => 'Tipus',
        'types' => [
            'stall' => 'Checklist parades',
            'market' => 'Checklist Mercat'
        ],
        'origin' => 'Parada/Mercat',
        'created_at' => 'Creat el',
        'all_ok' => 'De la inspecció practicada es comprova que no hi ha cap infracció.',
        'severity' => [
            'low' => 'Lleu',
            'high' => 'Greu',
            'very_high' => 'Molt Greu'
        ]
    ],
    'operations' => [
        'send' => [
            'button' => 'Enviar',
            'confirm' => 'Està segur que desitja enviar aquest element?',
            'confirmation_title' => 'Element enviat',
            'confirmation_message' => 'L\'element ha sigut enviat de manera correcta.',
            'confirmation_not_title' => 'No s\'ha pogut enviar',
            'confirmation_not_message' => 'Ha ocurregut un error. És probable que l\'element no s\'hagi enviat.',
            'confirmation_not_send_title' => 'No s\'ha pogut enviar',
            'confirmation_not_send_message' => 'No ha ocurregut res.',
        ],
        'generate' => [
            'button' => 'Generar Acreditacions',
            'confirm' => 'Està segur que desitja genearar fitxers per aquest element?',
            'confirmation_not_title' => 'No s\'ha pogut enviar',
            'confirmation_not_message' => 'Ha ocurregut un error. És probable que l\'element no s\'hagi enviat.',
        ]
    ],
    'upload_multiple_files_selected_one' => 'fitxer seleccionat. Després de desar, apareixerà a dalt',
    'upload_multiple_files_selected' => 'fitxers seleccionats. Després de desar, apareixeran a dalt',
    'reasons' => [
        'single' => 'Motiu Baixa',
        'plural' => 'Motius Baixa',
        'title' => 'Titol',
        'slug' => 'slug'
    ],
    'bic' => [
        'single' => 'Codis BIC',
    ]
];
