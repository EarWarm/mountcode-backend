<x-backpack::menu-item title="Мониторинг" icon="la la-home" link="{{ backpack_url('dashboard') }}"/>

<x-backpack::menu-dropdown title="Авторизация" icon="la la-users">
    <x-backpack::menu-dropdown-item title="Пользователи" icon="la la-user" link="{{backpack_url('user')}}"/>
    <x-backpack::menu-dropdown-item title="Роли" icon="la la-id-badge" link="{{backpack_url('role')}}"/>
    <x-backpack::menu-dropdown-item title="Права" icon="la la-key" link="{{backpack_url('permission')}}"/>
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Товары" icon="la la-product-hunt">
    <x-backpack::menu-dropdown-item title="Категории" icon="la la-map-pin" link="{{backpack_url('product-category')}}"/>
    <x-backpack::menu-dropdown-item title="Товары" icon="la la-product-hunt" link="{{backpack_url('product')}}"/>
    <x-backpack::menu-dropdown-item title="Ресурсы" icon="la la-file-alt" link="{{backpack_url('product-resource')}}"/>
</x-backpack::menu-dropdown>
