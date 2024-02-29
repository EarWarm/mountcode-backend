<?php

namespace App\Http\Controllers\Admin\User;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\PermissionManager\app\Http\Requests\PermissionStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\PermissionUpdateCrudRequest as UpdateRequest;
use Illuminate\Support\Facades\Cache;

class PermissionCrudController extends CrudController
{
    protected string $role_model;
    protected string $permission_model;

    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;

    public function setup()
    {
        $this->role_model = config('permission.models.role');
        $this->permission_model = $permission_model = config('permission.models.permission');

        $this->crud->setModel($permission_model);
        $this->crud->setEntityNameStrings('Право', 'Права');
        $this->crud->setRoute(backpack_url('permission'));
    }

    public function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Имя',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'guard_name',
            'label' => 'Защитник',
            'type' => 'text',
        ]);
    }

    public function setupCreateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        Cache::forget('spatie.permission.cache');
    }

    private function addFields()
    {
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Имя',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'guard_name',
            'label' => 'Защитник',
            'type' => 'select_from_array',
            'options' => $this->getGuardTypes(),
        ]);
    }

    private function getGuardTypes()
    {
        $guards = config('auth.guards');

        $returnable = [];
        foreach ($guards as $key => $details) {
            $returnable[$key] = $key;
        }

        return $returnable;
    }

    /*
     * Get an array list of all available guard types
     * that have been defined in app/config/auth.php
     *
     * @return array
     **/

    public function setupUpdateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        Cache::forget('spatie.permission.cache');
    }
}
