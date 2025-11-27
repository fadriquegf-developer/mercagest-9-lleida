<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('backpack.auth.logout');
    Route::post('logout', 'Auth\LoginController@logout');
});


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('/lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);
    Route::get('/set-market/{id}', 'MarketCrudController@setMarketAction');

    Route::crud('sector', 'SectorCrudController');
    Route::crud('auth-prod', 'AuthProdCrudController');
    Route::get('/person/{id}/toggle-subscribe', 'PersonCrudController@toggleSubscribe');
    Route::get('/person/{id}/legal-doc/{type}', 'PersonCrudController@showLegalDoc')->name('person.legal_doc');
    Route::post('/person/{id}/legal-doc/{type}/save', 'PersonCrudController@storeLegalDoc')->name('person.save_legal_doc');
    Route::crud('person', 'PersonCrudController');
    Route::get('/person/ajax-search', 'PersonCrudController@ajaxSearch');
    Route::get('/person/accreditation/{id}', 'PersonCrudController@getAccreditation')->name('person.accreditation');
    Route::get('/person/check-accreditation', 'PersonCrudController@viewCheckAccreditation')->name('person.check_accreditation.form');
    Route::post('/person/check-accreditation', 'PersonCrudController@checkAccreditation');
    //Route::get('/person/import-extra', 'PersonCrudController@importExtra');

    Route::crud('market-group', 'MarketGroupCrudController');
    Route::get('/market-group/{id}/generate', 'MarketGroupCrudController@startGeneratePDFs');
    Route::get('/market-group/{id}/download', 'MarketGroupCrudController@downloadPDFs');
    Route::get('/market-group/{id}/pre-download', 'MarketGroupCrudController@downloadView');
    Route::get('/market-group/{id}/progress', 'MarketGroupCrudController@progress');
    Route::crud('market', 'MarketCrudController');
    Route::crud('town', 'TownCrudController');

    Route::crud('stall', 'StallCrudController');
    Route::get('/stall/certification/{id}', 'StallCrudController@getCertification');
    Route::post('/stall/unsubscribe/{id}', 'StallCrudController@unsubscribeTitular');
    Route::post('/stall/subscribe/{id}', 'StallCrudController@subscribeTitular');
    Route::get('/stall/accreditation/{id}', 'StallCrudController@getAccreditation');
    Route::post('/stall/get-by-market', 'StallCrudController@getByMarket');
    Route::get('/stall/hidde-all-inactive-stalls', 'StallCrudController@hiddeAllInactiveStalls');
    Route::get('/stall/import-meters', 'StallCrudController@importMeters');

    Route::crud('rate', 'RateCrudController');
    Route::crud('incidences', 'IncidencesCrudController');
    Route::crud('concession', 'ConcessionCrudController');
    Route::crud('historical', 'HistoricalCrudController');
    Route::crud('contact-email', 'ContactEmailCrudController');
    Route::get('/absence/toggle', 'AbsenceCrudController@toggleAbcence');
    Route::crud('absence', 'AbsenceCrudController');
    Route::crud(config('backpack.settings.route'), 'SettingCrudController');
    Route::crud('/market/{market_id}/calendar', 'CalendarCrudController');
    Route::post('/market/{market_id}/calendar/check', 'CalendarCrudController@checkDates');

    Route::group(['prefix' => 'calendar'], function () {
        Route::post('/get-dates-calendar/{person}', 'CalendarCrudController@getDatesCalendar');
        Route::post('/get-dates-calendar/stall/{stall}', 'CalendarCrudController@getDatesCalendarByStall');
    });

    Route::get('/market/{market_id}/calendar/create-range', 'CalendarCrudController@getViewMarketDates');
    Route::post('/market/{market_id}/calendar/create-range', 'CalendarCrudController@createMarketDates');

    Route::crud('communication', 'CommunicationCrudController');
    Route::post('/communication/check', 'CommunicationCrudController@check');

    Route::crud('invoice', 'InvoiceCrudController');
    Route::post('/invoice/delete', 'InvoiceCrudController@deleteInvoice');
    Route::get('/invoice/view', 'InvoiceCrudController@getView');
    Route::get('/invoice/printandpay/{id}', 'InvoiceCrudController@getPrintAndPay');
    Route::post('/invoice/get-by-range-dates', 'InvoiceCrudController@getInvoiceFilterByDates');
    Route::get('/invoice/generate-invoices', 'InvoiceCrudController@generateInvoices')->name('form-invoice-date-range');
    Route::post('/invoice/generate-index-invoice', 'InvoiceCrudController@generate_index_invoice')->name('index-invoice-gtt');
    Route::post('/invoice/generate-gtt', 'InvoiceCrudController@generate_gtt')->name('generate-gtt');

    Route::get('/day-report/{date}', 'DayReportController@index');
    Route::get('/day-report/{date}/export', 'DayReportController@export');
    Route::get('/report/{date}', 'ReportController@index');
    Route::post('/report/download', 'ReportController@download');
    // Route::crud('extension', 'ExtensionCrudController');
    Route::post('/bonus/get-market-days-by-stall', 'BonusCrudController@getMarketDaysByStall');
    Route::post('/bonus/get-market-days-by-market', 'BonusCrudController@getMarketDaysByMarket');
    Route::crud('bonus', 'BonusCrudController');

    Route::any('/maps', 'MapController@index');

    Route::crud('expediente', 'ExpedienteCrudController');
    Route::crud('classe', 'ClasseCrudController');

    // Route::get('checklist/{type}/select', 'ChecklistCrudController@selectChecklist'); 
    // Route::get('checklist/{type}/{id}/create', 'ChecklistCrudController@createChecklist');
    // Route::post('checklist/{type}/{id}/save', 'ChecklistCrudController@saveChecklist');
    // Route::crud('checklist', 'ChecklistCrudController');

    // filepond 
    Route::prefix('filepond')->group(function () {
        Route::get('restore/{id}', 'FilepondController@restore')->name('filepond_restore');
        Route::get('load/{id}', 'FilepondController@load')->name('filepond_load');
    });

    //Migrations routes
    Route::get('/migrate', 'MigrationController@migration');
    Route::get('/migrate-substitutes', 'PersonCrudController@migrateSubstitutes');


    Route::get('storage/{filePath}', function ($filePath) {
        // we check for the existing of the file 
        if (!\Storage::disk('local')->exists($filePath)) {
            abort('404'); // we redirect to 404 page if it doesn't exist
        }

        return response()->file(storage_path('app' . DIRECTORY_SEPARATOR . ($filePath)));
    })->where(['filePath' => '.*']);
    Route::crud('bic-conversion', 'BicConversionCrudController');
    Route::crud('reason', 'ReasonCrudController');
}); // this should be the absolute last line of this file