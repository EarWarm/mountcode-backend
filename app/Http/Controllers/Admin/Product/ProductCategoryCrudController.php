<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\ProductCategoryRequest;
use App\Models\ProductCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductCategoryCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;

    public function setup(): void
    {
        CRUD::setModel(ProductCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-category');
        CRUD::setEntityNameStrings('Категорию товаров', 'Категории товаров');
    }

    protected function setupListOperation(): void
    {
        $this->setupColumns();
    }

    private function setupColumns(): void
    {
        CRUD::addColumns([
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text'
            ],
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(ProductCategoryRequest::class);
        $this->setupFields();
    }

    private function setupFields(): void
    {
        CRUD::addFields([
            [
                'name' => 'name',
                'label' => 'Название',
                'type' => 'text'
            ]
        ]);
    }
}
