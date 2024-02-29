<?php

namespace App\Http\Controllers\Admin\User;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\PermissionManager\app\Http\Requests\RoleStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\RoleUpdateCrudRequest as UpdateRequest;
use Illuminate\Support\Facades\Cache;

// VALIDATION

class RoleCrudController extends CrudController
{
    protected string $role_model;
    protected string $permission_model;

    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;

    public function setup()
    {
        $this->role_model = $role_model = config('permission.models.role');
        $this->permission_model = config('permission.models.permission');

        $this->crud->setModel($role_model);
        $this->crud->setEntityNameStrings('Роль', 'Роли');
        $this->crud->setRoute(backpack_url('role'));
    }

    public function setupListOperation()
    {
        /**
         * Show a column for the name of the role.
         */
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Имя',
            'type' => 'text',
        ]);

        /**
         * Show a column with the number of users that have that particular role.
         *
         * Note: To account for the fact that there can be thousands or millions
         * of users for a role, we did not use the `relationship_count` column,
         * but instead opted to append a fake `user_count` column to
         * the result, using Laravel's `withCount()` method.
         * That way, no users are loaded.
         */
        $this->crud->query->withCount('users');
        $this->crud->addColumn([
            'label' => 'Пользователи',
            'type' => 'text',
            'name' => 'users_count',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user?role=' . $entry->getKey());
                },
            ],
            'suffix' => ' ' . strtolower('Пользователи'),
        ]);

        /**
         * In case multiple guards are used, show a column for the guard.
         */
        $this->crud->addColumn([
            'name' => 'guard_name',
            'label' => 'Защитник',
            'type' => 'text',
        ]);

        /**
         * Show the exact permissions that role has.
         */
        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label' => mb_ucfirst('Права'),
            'type' => 'select_multiple',
            'name' => 'permissions', // the method that defines the relationship in your Model
            'entity' => 'permissions', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => $this->permission_model, // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
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

        $this->crud->addField([
            'label' => mb_ucfirst('Права'),
            'type' => 'checklist',
            'name' => 'permissions',
            'entity' => 'permissions',
            'attribute' => 'name',
            'model' => $this->permission_model,
            'pivot' => true,
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
