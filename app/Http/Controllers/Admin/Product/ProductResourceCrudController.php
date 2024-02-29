<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\ProductResourceRequest;
use App\Models\ProductResource;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductResourceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductResourceCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(ProductResource::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-resource');
        CRUD::setEntityNameStrings('Ресурс', 'Ресурсы товара');
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
                'name' => 'uuid',
                'label' => 'Номер',
                'type' => 'text',
            ],
            [
                'name' => 'product_id',
                'label' => 'Товар',
                'type' => 'select',
                'entity' => 'product',
                'attribute' => 'name',
                'model' => "App\Models\Product",
            ],
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text'
            ],
            [
                'name' => 'version',
                'label' => 'Версия',
                'type' => 'text'
            ],
            [
                'name' => 'changelog',
                'label' => 'Изменения',
                'type' => 'text'
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
        CRUD::setValidation(ProductResourceRequest::class);
        $this->setupFields();
    }

    private function setupFields(): void
    {
        CRUD::addFields([
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text',
            ],
            [
                'name' => 'uuid',
                'label' => 'Номер',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'product_id',
                'label' => 'Товар',
                'type' => 'select',
                'entity' => 'product',
                'attribute' => 'name',
                'model' => "App\Models\Product",
                'wrapper' => [
                    'class' => 'form-group col-md-6'
                ]
            ],
            [
                'name' => 'version',
                'label' => 'Версия',
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-6'
                ]
            ],
            [
                'name' => 'changelog',
                'label' => 'Изменения',
                'type' => 'textarea'
            ],
        ]);
    }
}
