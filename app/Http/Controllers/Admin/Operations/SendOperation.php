<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait SendOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupSendRoutes($segment, $routeName, $controller)
    {
        Route::post($segment . '/{id}/send', [
            'as'        => $routeName . '.send',
            'uses'      => $controller . '@send',
            'operation' => 'send',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupSendDefaults()
    {
        $this->crud->allowAccess('send');

        $this->crud->operation('send', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'send', 'view', 'crud::buttons.send');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function send($id)
    {
        $this->crud->hasAccessOrFail('send');

        $entry = $this->crud->model->findOrFail($id);

        // send email
        $entry->sendEmail();

        return true;
    }
}
