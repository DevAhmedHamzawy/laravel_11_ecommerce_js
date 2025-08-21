<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubAttributeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\SubCategoryController as ControllersSubCategoryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// DON'T Put it inside the '/admin' Prefix , Otherwise you'll never get the page due to assign.guard that will redirect you too many times
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Forget Password
Route::prefix('admin')->group(function () {
    Route::get('forget-password', [AdminForgotPasswordController::class, 'showForgetPasswordForm'])->name('admin.forget-password');
    Route::post('forget-password', [AdminForgotPasswordController::class, 'sendResetLink'])->name('admin.send-reset-link');
    Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'showResetPasswordForm'])->name('admin.reset-password');
    Route::post('reset-password', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin.reset-password.submit');
});


// Change Language
Route::get('lang/{lang}', [LocalizationController::class, 'index'])->name('language');

Route::group(['prefix' => 'admin', 'as' => 'admin.','middleware' => 'assign.guard:admin,admin/login'], function () {

    Route::get('dashboard', action: [AdminPanelController::class, 'index'])->name('dashboard');

    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/active', [RoleController::class, 'active'])->name('roles.active');
    Route::get('trash_roles', [RoleController::class, 'trash'])->name('roles.trash');
    Route::get('roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');


    Route::resource('admins', AdminController::class);
    Route::get('admins/{admin}/active', [AdminController::class, 'active'])->name('admins.active');
    Route::get('trash_admins', [AdminController::class, 'trash'])->name('admins.trash');
    Route::get('admins/{id}/restore', [AdminController::class, 'restore'])->name('admins.restore');


    Route::resource('users', UserController::class);
    Route::get('users/{user}/active', [UserController::class, 'active'])->name('users.active');
    Route::get('trash_users', [UserController::class, 'trash'])->name('users.trash');
    Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/active', [CategoryController::class, 'active'])->name('categories.active');
    Route::get('categories/{category}/appear_home', [CategoryController::class, 'appearHome'])->name('categories.appear_home');
    Route::get('trash_categories', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('categories/{slug}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

    // Subcategories
    Route::resource('categories/{category}/subcategories', SubCategoryController::class)->except('show');
    Route::get('categories/{category}/subcategories/{subcategory}/active', [SubCategoryController::class, 'active'])->name('subcategories.active');
    Route::get('categories/{category}/subcategories/{subcategory}/appear_home', [SubCategoryController::class, 'appearHome'])->name('subcategories.appear_home');
    Route::get('categories/{category}/trash_subcategories', [SubCategoryController::class, 'trash'])->name('subcategories.trash');
    Route::get('categories/{category}/subcategories/{slug}/restore', [SubCategoryController::class, 'restore'])->name('subcategories.restore');

    // Taxes
    Route::resource('taxes', TaxController::class);
    Route::get('taxes/{tax}/active', [TaxController::class, 'active'])->name('taxes.active');
    Route::get('trash_taxes', [TaxController::class, 'trash'])->name('taxes.trash');
    Route::get('taxes/{id}/restore', [TaxController::class, 'restore'])->name('taxes.restore');

    // Brands
    Route::resource('brands', BrandController::class);
    Route::get('brands/{brand}/active', [BrandController::class, 'active'])->name('brands.active');
    Route::get('trash_brands', [BrandController::class, 'trash'])->name('brands.trash');
    Route::get('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');

    // Units
    Route::resource('units', UnitController::class);
    Route::get('units/{unit}/active', [UnitController::class, 'active'])->name('units.active');
    Route::get('trash_units', [UnitController::class, 'trash'])->name('units.trash');
    Route::get('units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');

    // Attributes
    Route::resource('attributes', AttributeController::class);
    Route::get('attributes/{attribute}/active', [AttributeController::class, 'active'])->name('attributes.active');
    Route::get('trash_attributes', [AttributeController::class, 'trash'])->name('attributes.trash');
    Route::get('attributes/{id}/restore', [AttributeController::class, 'restore'])->name('attributes.restore');

    // SubAttributes
    Route::resource('attributes/{attribute}/subattributes', SubAttributeController::class)->except('show');
    Route::get('attributes/{attribute}/subattributes/{subattribute}/active', [SubAttributeController::class, 'active'])->name('subattributes.active');
    Route::get('attributes/{attribute}/trash_subattributes', [SubAttributeController::class, 'trash'])->name('subattributes.trash');
    Route::get('attributes/{attribute}/subattributes/{id}/restore', [SubAttributeController::class, 'restore'])->name('subattributes.restore');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::get('suppliers/{supplier}active', [SupplierController::class, 'active'])->name('suppliers.active');
    Route::get('trash_suppliers', [SupplierController::class, 'trash'])->name('suppliers.trash');
    Route::get('suppliers/{id}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');

    // Countries
    Route::resource('countries', CountryController::class);
    Route::get('trash_countries', [CountryController::class, 'trash'])->name('countries.trash');
    Route::get('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');

    // governorates
    Route::resource('countries/{country}/governorates', GovernorateController::class);
    Route::get('countries/{country}/trash_governorates', [GovernorateController::class, 'trash'])->name('governorates.trash');
    Route::get('countries/{country}/governorates/{id}/restore', [GovernorateController::class, 'restore'])->name('governorates.restore');

    // Cities
    Route::resource('countries/{country}/governorates/{governorate}/cities', CityController::class);
    Route::get('countries/{country}/governorates/{governorate}/trash_cities', [CityController::class, 'trash'])->name('cities.trash');
    Route::get('countries/{country}/governorates/{governorate}/cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/{product}/active', [ProductController::class, 'active'])->name('products.active');
    Route::get('trash_products', [ProductController::class, 'trash'])->name('products.trash');
    Route::get('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('upload_product', [ProductController::class, 'upload'])->name('products.upload');
    Route::post('delete_product', [ProductController::class, 'deleteImage'])->name('products.delete_image');

    // Get Product Data
    Route::get('get_product', [ProductController::class, 'getData'])->name('get_product');

    // Purchases
    Route::resource('purchases', PurchaseController::class);

    // Stock
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::put('stocks/update-price', [StockController::class, 'updatePrice'])->name('stocks.updatePrice');
    Route::get('stocks/{id}', [StockController::class, 'show'])->name('stocks.show');

    // Flash Sales
    Route::get('flash_sales/show_categories', [FlashSaleController::class, 'chooseCategories'])->name('flash_sales.choose_categories');
    Route::post('flash_sales/show_products', [FlashSaleController::class, 'showProducts'])->name('flash_sales.show_products');
    Route::post('flash_sales/create', [FlashSaleController::class, 'create'])->name('flash_sales.create');
    Route::get('flash_sales/create', [FlashSaleController::class, 'create'])->name('flash_sales.create');
    Route::post('flash_sales/store', action: [FlashSaleController::class, 'store'])->name('flash_sales.store');
    Route::get('flash_sales', [FlashSaleController::class, 'index'])->name('flash_sales.index');
    Route::delete('flash_sales/{flash_sale}/delete', [FlashSaleController::class, 'destroy'])->name('flash_sales.destroy');

    // Slider
    Route::resource('sliders', SliderController::class);
    Route::get('sliders/{slider}/active', [SliderController::class, 'active'])->name('sliders.active');

    // Coupons
    Route::resource('coupons', CouponController::class);
    Route::get('coupons/{coupon}/active', [CouponController::class, 'active'])->name('coupons.active');

    // Pages
    Route::resource('pages', PageController::class);
    Route::get('pages/{page}/active', [PageController::class, 'active'])->name('pages.active');
    Route::get('trash_pages', [PageController::class, 'trash'])->name('pages.trash');
    Route::get('pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');

    Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('activity_logs');

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('areas', [AreaController::class, 'index'])->name('get_areas');

Route::get('list_subcategories/{id}', [ControllersSubCategoryController::class, 'list'])->name('list_subcategories');

Route::get('/storage_link', function () {
    Artisan::call('storage:link');
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Optimization cache cleared!';
});
