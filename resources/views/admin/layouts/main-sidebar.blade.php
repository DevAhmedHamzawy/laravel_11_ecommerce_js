<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('assets/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('assets/img/brand/logo-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('assets/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{ auth()->user()->img_path }}"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ auth()->user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ Auth::user()->roles->pluck('name')[0] }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('dashboard.main') }}</li>


            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                    <i class="side-menu__icon fe fe-home"></i>
                    &nbsp;&nbsp;<span class="side-menu__label">{{ trans('dashboard.dashboard') }}</span></a>
            </li>



            <li class="side-item side-item-category">{{ trans('dashboard.users') }}</li>

            @canany(['view_user', 'add_user', 'edit_user', 'delete_user', 'active_user', 'restore_user'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fe fe-users"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('user.users') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_user')
                            <li><a class="slide-item"
                                    href="{{ route('admin.users.index') }}">{{ trans('user.show_users') }}</a>
                            </li>
                        @endcan
                        @can('add_user')
                            <li><a class="slide-item"
                                    href="{{ route('admin.users.create') }}">{{ trans('user.add_new_user') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany



            <li class="side-item side-item-category">{{ trans('dashboard.admin') }}</li>

            @canany(['view_admin', 'add_admin', 'edit_admin', 'delete_admin', 'active_admin', 'restore_admin'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-user-shield"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('admin.admins') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_admin')
                            <li><a class="slide-item"
                                    href="{{ route('admin.admins.index') }}">{{ trans('admin.show_admins') }}</a>
                            </li>
                        @endcan
                        @can('add_admin')
                            <li><a class="slide-item"
                                    href="{{ route('admin.admins.create') }}">{{ trans('admin.add_new_admin') }}</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['view_role', 'add_role', 'edit_role', 'delete_role', 'active_role', 'restore_role'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-user-tag"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('roles.roles') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_role')
                            <li><a class="slide-item"
                                    href="{{ route('admin.roles.index') }}">{{ trans('roles.show_roles') }}</a>
                            </li>
                        @endcan
                        @can('add_role')
                            <li><a class="slide-item"
                                    href="{{ route('admin.roles.create') }}">{{ trans('roles.add_new_role') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            <li class="side-item side-item-category">{{ trans('dashboard.products_stocks') }}</li>

            @canany(['view_product', 'add_product', 'edit_product', 'delete_product', 'active_product',
                'restore_product'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-box"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('product.products') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_product')
                            <li><a class="slide-item"
                                    href="{{ route('admin.products.index') }}">{{ trans('product.show_products') }}</a>
                            </li>
                        @endcan
                        @can('add_product')
                            <li><a class="slide-item"
                                    href="{{ route('admin.products.create') }}">{{ trans('product.add_new_product') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['view_purchase', 'add_purchase', 'edit_purchase', 'delete_purchase'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-shopping-basket"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('purchase.purchases') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_purchase')
                            <li><a class="slide-item"
                                    href="{{ route('admin.purchases.index') }}">{{ trans('purchase.show_purchases') }}</a>
                            </li>
                        @endcan
                        @can('add_purchase')
                            <li><a class="slide-item"
                                    href="{{ route('admin.purchases.create') }}">{{ trans('purchase.add_new_purchase') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['view_stock'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-warehouse"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('stock.stock') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_stock')
                            <li><a class="slide-item"
                                    href="{{ route('admin.stocks.index') }}">{{ trans('stock.show_stock') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            <li class="side-item side-item-category">{{ trans('dashboard.flash_sales_coupons') }}</li>

            @canany(['view_flash_sale', 'add_flash_sale', 'edit_flash_sale', 'delete_flash_sale'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-gift"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('flash_sale.flash_sales') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_flash_sale')
                            <li><a class="slide-item"
                                    href="{{ route('admin.flash_sales.index') }}">{{ trans('flash_sale.show_flash_sales') }}</a>
                            </li>
                        @endcan
                        @can('add_flash_sale')
                            <li><a class="slide-item"
                                    href="{{ route('admin.flash_sales.choose_categories') }}">{{ trans('flash_sale.add_new_flash_sale') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            @canany(['view_coupon', 'add_coupon', 'edit_coupon', 'delete_coupon'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-tags"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('coupon.coupons') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_coupon')
                            <li><a class="slide-item"
                                    href="{{ route('admin.coupons.index') }}">{{ trans('coupon.show_coupons') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany



            <li class="side-item side-item-category">{{ trans('dashboard.country') }}</li>

            @canany(['view_country', 'add_country', 'edit_country', 'delete_country', 'active_country',
                'restore_country'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-globe"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('country.countries') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_country')
                            <li><a class="slide-item"
                                    href="{{ route('admin.countries.index') }}">{{ trans('country.show_countries') }}</a>
                            </li>
                        @endcan
                        @can('add_country')
                            <li><a class="slide-item"
                                    href="{{ route('admin.countries.create') }}">{{ trans('country.add_new_country') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <li class="side-item side-item-category">{{ trans('supplier.suppliers') }}</li>

            @canany(['view_supplier', 'add_supplier', 'edit_supplier', 'delete_supplier', 'active_supplier',
                'restore_supplier'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fas fa-truck"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('supplier.suppliers') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_supplier')
                            <li><a class="slide-item"
                                    href="{{ route('admin.suppliers.index') }}">{{ trans('supplier.show_suppliers') }}</a>
                            </li>
                        @endcan
                        @can('add_supplier')
                            <li><a class="slide-item"
                                    href="{{ route('admin.suppliers.create') }}">{{ trans('supplier.add_new_supplier') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany


            <li class="side-item side-item-category">{{ trans('dashboard.categories_and_brands') }}</li>

            @canany(['view_category', 'add_category', 'edit_category', 'delete_category', 'active_category',
                'restore_category', 'add_home_category'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fe fe-grid"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('category.categories') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_category')
                            <li><a class="slide-item"
                                    href="{{ route('admin.categories.index') }}">{{ trans('category.show_categories') }}</a>
                            </li>
                        @endcan
                        @can('add_category')
                            <li><a class="slide-item"
                                    href="{{ route('admin.categories.create') }}">{{ trans('category.add_new_category') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <li class="side-item side-item-category">{{ trans('dashboard.product_settings') }}</li>

            @canany(['view_tax', 'add_tax', 'edit_tax', 'delete_tax', 'active_tax', 'restore_tax'])
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.taxes.index') }}">
                        <i class="side-menu__icon fa fa-receipt"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('tax.taxes') }}</span></a>
                </li>
            @endcanany


            @canany(['view_brand', 'add_brand', 'edit_brand', 'delete_brand', 'active_brand', 'restore_brand'])
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.brands.index') }}">
                        <i class="side-menu__icon fa fa-tags"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('brand.brands') }}</span></a>
                </li>
            @endcanany


            @canany(['view_unit', 'add_unit', 'edit_unit', 'delete_unit', 'active_unit', 'restore_unit'])
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.units.index') }}">
                        <i class="side-menu__icon fa fa-ruler"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('unit.units') }}</span></a>
                </li>
            @endcanany


            @canany(['view_attribute', 'add_attribute', 'edit_attribute', 'delete_attribute', 'active_attribute',
                'restore_attribute'])
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.attributes.index') }}">
                        <i class="side-menu__icon fa fa-puzzle-piece"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('attribute.attributes') }}</span></a>
                </li>
            @endcanany


            <li class="side-item side-item-category">{{ trans('dashboard.site_settings') }}</li>

            @canany(['view_slider', 'add_slider', 'edit_slider', 'delete_slider', 'active_slider', 'restore_slider',
                'add_home_slider'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fe fe-image"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('slider.sliders') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_slider')
                            <li><a class="slide-item"
                                    href="{{ route('admin.sliders.index') }}">{{ trans('slider.show_sliders') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['view_page', 'add_page', 'edit_page', 'delete_page', 'active_page', 'restore_page',
                'add_home_page'])
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="side-menu__icon fe fe-file"></i>
                        &nbsp;&nbsp;<span class="side-menu__label">{{ trans('page.pages') }}</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('view_page')
                            <li><a class="slide-item"
                                    href="{{ route('admin.pages.index') }}">{{ trans('page.show_pages') }}</a>
                            </li>
                        @endcan
                        @can('add_page')
                            <li><a class="slide-item"
                                    href="{{ route('admin.pages.create') }}">{{ trans('page.add_new_page') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany



            <li class="side-item side-item-category">{{ trans('dashboard.activity_logs') }}</li>

            @can('view_activity_log')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.activity_logs') }}">
                        <i class="side-menu__icon fa fa-history"></i>&nbsp;&nbsp;<span
                            class="side-menu__label">{{ trans('dashboard.activity_logs') }}</span></a>
                </li>
            @endcan

        </ul>
    </div>
</aside>
<!-- main-sidebar -->
