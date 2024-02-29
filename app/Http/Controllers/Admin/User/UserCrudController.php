<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Requests\Admin\User\UserStoreCrudRequest;
use App\Http\Requests\Admin\User\UserUpdateCrudRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation;

    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setEntityNameStrings("Пользователь", "Пользователи");
        CRUD::setRoute(backpack_url('user'));
    }

    public function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'id',
                'label' => '#',
                'type' => 'number',
            ],
            [
                'name' => 'email',
                'label' => 'Почта',
                'type' => 'email',
            ],
            [
                'name' => 'balance',
                'label' => 'Баланс',
                'type' => 'number',
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime',
                'label' => 'Дата регистрации',
            ],
            [ // n-n relationship (with pivot table)
                'label' => 'Роли', // Table column heading
                'type' => 'select_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
                'label' => 'Права', // Table column heading
                'type' => 'select_multiple',
                'name' => 'permissions', // the method that defines the relationship in your Model
                'entity' => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.permission'), // foreign key model
            ],
        ]);
    }

    public function setupCreateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidationFromRequest(UserStoreCrudRequest::class);
    }

    protected function addUserFields()
    {
        CRUD::addFields([
            [
                'name' => 'email',
                'label' => 'Почта',
                'type' => 'email',
            ],
            [
                'name' => 'balance',
                'label' => 'Баланс',
                'type' => 'number',
            ],
            [
                'name' => 'password',
                'label' => 'Пароль',
                'type' => 'password',
            ],
            [
                'name' => 'password_confirmation',
                'label' => 'Подтверждение пароля',
                'type' => 'password',
            ],
            [
                'label'             => 'Права пользователя',
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency',
                'name'              => 'roles,permissions',
                'subfields'         => [
                    'primary' => [
                        'label'            => 'Роли',
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => config('permission.models.role'), // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => 'Права',
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => config('permission.models.permission'), // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);
    }

    public function setupUpdateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidationFromRequest(UserUpdateCrudRequest::class);
    }

    public function store(): RedirectResponse
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitStore();
    }

    protected function handlePasswordInput(Request $request): Request
    {
        // Remove fields not present on the user.
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        if (!$request->input('password_confirmation') || $request->input('password') != $request->input('password_confirmation')) {
            $request->request->remove('password');
        }
        $request->request->remove('password_confirmation');

        return $request;
    }

    public function update(): RedirectResponse
    {
        $this->crud->setRequest(CRUD::validateRequest());
        $this->crud->setRequest($this->handlePasswordInput(CRUD::getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        $request = $this->crud->getRequest();
        $id = $request->input('id');
        Validator::make(
            [
                'email' => $request->input('email')
            ],
            [
                'email' => Rule::unique('users')->ignore($id),
            ]
        )->validate();

        return $this->traitUpdate();
    }

    public function destroy($id): string
    {
        if ($id == 1) {
            abort(403, 'Вы не можете удалять аккаунт администратора!');
        }

        return parent::destroy($id);
    }
}
