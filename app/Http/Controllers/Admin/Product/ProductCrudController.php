<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\ProductRequest;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('Товар', 'Товары');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->setupColumns();
    }

    private function setupColumns(): void
    {
        CRUD::addColumns([
            [
                'name' => 'active',
                'label' => 'Активен',
                'type' => 'boolean',
                'options' => [
                    0 => 'Нет',
                    1 => 'Да'
                ],
            ],
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text'
            ],
            [
                'name' => 'price',
                'label' => 'Стоимость',
                'type' => 'number',
                'suffix' => ' руб.'
            ],
            [
                'name' => 'category_id',
                'label' => 'Категория',
                'type' => 'select',
                'entity' => 'category',
                'attribute' => 'name',
                'model' => "App\Models\ProductCategory",
            ],
            [
                'name' => 'archived_at',
                'label' => 'Архивирован',
                'type' => 'datetime',
                'default' => 'Нет'
            ],
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
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);
        $this->setupFields();
    }

    private function setupFields(): void
    {
        $this->crud->disableAutoFocus();
        CRUD::addFields([
            [
                'name' => 'active',
                'label' => 'Активен',
                'type' => 'boolean',
                'default' => true,
                'options' => [
                    0 => 'Нет',
                    1 => 'Да'
                ]
            ],
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-4'
                ]
            ],
            [
                'name' => 'price',
                'label' => 'Стоимость',
                'type' => 'number',
                'suffix' => ' руб.',
                'wrapper' => [
                    'class' => 'form-group col-md-4'
                ]
            ],
            [
                'name' => 'category_id',
                'label' => 'Категория',
                'type' => 'select',
                'entity' => 'category',
                'attribute' => 'name',
                'model' => "App\Models\ProductCategory",
                'wrapper' => [
                    'class' => 'form-group col-md-4'
                ]
            ],
            [
                'name' => 'archived_at',
                'label' => 'Архивирован',
                'type' => 'datetime',
                'default' => 'Нет'
            ],
            [
                'name' => 'description',
                'label' => 'Описание',
                'type' => 'textarea'
            ],
        ]);
    }
}
