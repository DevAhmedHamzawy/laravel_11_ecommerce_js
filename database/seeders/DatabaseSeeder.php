<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        $admin = Admin::updateOrCreate(['id' => 1], [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'active' => 1,
            'remember_token' => Str::random(10),
            'deleted_at' => null
        ]);

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin', 'active' => 1]);

        /*Permission::create(['name' => 'add_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('add_admin');
        Permission::create(['name' => 'edit_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('edit_admin');
        Permission::create(['name' => 'delete_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('delete_admin');
        Permission::create(['name' => 'view_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('view_admin');
        Permission::create(['name' => 'active_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('active_admin');
        Permission::create(['name' => 'restore_admin', 'guard_name' => 'admin', 'group_name' => 'Admins']);
        $role->givePermissionTo('restore_admin');
        Permission::create(['name' => 'add_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('add_user');
        Permission::create(['name' => 'edit_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('edit_user');
        Permission::create(['name' => 'delete_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('delete_user');
        Permission::create(['name' => 'view_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('view_user');
        Permission::create(['name' => 'active_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('active_user');
        Permission::create(['name' => 'restore_user', 'guard_name' => 'admin', 'group_name' => 'Users']);
        $role->givePermissionTo('restore_user');
        Permission::create(['name' => 'add_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('add_role');
        Permission::create(['name' => 'edit_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('edit_role');
        Permission::create(['name' => 'delete_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('delete_role');
        Permission::create(['name' => 'view_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('view_role');
        Permission::create(['name' => 'active_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('active_role');
        Permission::create(['name' => 'restore_role', 'guard_name' => 'admin', 'group_name' => 'Roles']);
        $role->givePermissionTo('restore_role');
        Permission::create(['name' => 'add_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('add_category');
        Permission::create(['name' => 'edit_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('edit_category');
        Permission::create(['name' => 'delete_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('delete_category');
        Permission::create(['name' => 'view_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('view_category');
        Permission::create(['name' => 'active_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('active_category');
        Permission::create(['name' => 'restore_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('restore_category');
        Permission::create(['name' => 'add_home_category', 'guard_name' => 'admin', 'group_name' => 'Category']);
        $role->givePermissionTo('add_home_category');
        Permission::create(['name' => 'add_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('add_tax');
        Permission::create(['name' => 'edit_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('edit_tax');
        Permission::create(['name' => 'delete_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('delete_tax');
        Permission::create(['name' => 'view_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('view_tax');
        Permission::create(['name' => 'active_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('active_tax');
        Permission::create(['name' => 'restore_tax', 'guard_name' => 'admin', 'group_name' => 'Tax']);
        $role->givePermissionTo('restore_tax');
        Permission::create(['name' => 'add_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('add_brand');
        Permission::create(['name' => 'edit_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('edit_brand');
        Permission::create(['name' => 'delete_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('delete_brand');
        Permission::create(['name' => 'view_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('view_brand');
        Permission::create(['name' => 'active_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('active_brand');
        Permission::create(['name' => 'restore_brand', 'guard_name' => 'admin', 'group_name' => 'Brand']);
        $role->givePermissionTo('restore_brand');
        Permission::create(['name' => 'add_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('add_unit');
        Permission::create(['name' => 'edit_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('edit_unit');
        Permission::create(['name' => 'delete_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('delete_unit');
        Permission::create(['name' => 'view_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('view_unit');
        Permission::create(['name' => 'active_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('active_unit');
        Permission::create(['name' => 'restore_unit', 'guard_name' => 'admin', 'group_name' => 'Unit']);
        $role->givePermissionTo('restore_unit');
        Permission::create(['name' => 'add_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('add_attribute');
        Permission::create(['name' => 'edit_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('edit_attribute');
        Permission::create(['name' => 'delete_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('delete_attribute');
        Permission::create(['name' => 'view_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('view_attribute');
        Permission::create(['name' => 'active_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('active_attribute');
        Permission::create(['name' => 'restore_attribute', 'guard_name' => 'admin', 'group_name' => 'Attribute']);
        $role->givePermissionTo('restore_attribute');
        Permission::create(['name' => 'add_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('add_supplier');
        Permission::create(['name' => 'edit_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('edit_supplier');
        Permission::create(['name' => 'delete_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('delete_supplier');
        Permission::create(['name' => 'view_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('view_supplier');
        Permission::create(['name' => 'active_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('active_supplier');
        Permission::create(['name' => 'restore_supplier', 'guard_name' => 'admin', 'group_name' => 'Supplier']);
        $role->givePermissionTo('restore_supplier');
        Permission::create(['name' => 'add_country', 'guard_name' => 'admin', 'group_name' => 'Country']);
        $role->givePermissionTo('add_country');
        Permission::create(['name' => 'edit_country', 'guard_name' => 'admin', 'group_name' => 'Country']);
        $role->givePermissionTo('edit_country');
        Permission::create(['name' => 'delete_country', 'guard_name' => 'admin', 'group_name' => 'Country']);
        $role->givePermissionTo('delete_country');
        Permission::create(['name' => 'view_country', 'guard_name' => 'admin', 'group_name' => 'Country']);
        $role->givePermissionTo('view_country');
        Permission::create(['name' => 'restore_country', 'guard_name' => 'admin', 'group_name' => 'Country']);
        $role->givePermissionTo('restore_country');
        Permission::create(['name' => 'add_governorate', 'guard_name' => 'admin', 'group_name' => 'Governorate']);
        $role->givePermissionTo('add_governorate');
        Permission::create(['name' => 'edit_governorate', 'guard_name' => 'admin', 'group_name' => 'Governorate']);
        $role->givePermissionTo('edit_governorate');
        Permission::create(['name' => 'delete_governorate', 'guard_name' => 'admin', 'group_name' => 'Governorate']);
        $role->givePermissionTo('delete_governorate');
        Permission::create(['name' => 'view_governorate', 'guard_name' => 'admin', 'group_name' => 'Governorate']);
        $role->givePermissionTo('view_governorate');
        Permission::create(['name' => 'restore_governorate', 'guard_name' => 'admin', 'group_name' => 'Governorate']);
        $role->givePermissionTo('restore_governorate');
        Permission::create(['name' => 'add_city', 'guard_name' => 'admin', 'group_name' => 'City']);
        $role->givePermissionTo('add_city');
        Permission::create(['name' => 'edit_city', 'guard_name' => 'admin', 'group_name' => 'City']);
        $role->givePermissionTo('edit_city');
        Permission::create(['name' => 'delete_city', 'guard_name' => 'admin', 'group_name' => 'City']);
        $role->givePermissionTo('delete_city');
        Permission::create(['name' => 'view_city', 'guard_name' => 'admin', 'group_name' => 'City']);
        $role->givePermissionTo('view_city');
        Permission::create(['name' => 'restore_city', 'guard_name' => 'admin', 'group_name' => 'City']);
        $role->givePermissionTo('restore_city');
        Permission::create(['name' => 'add_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('add_product');
        Permission::create(['name' => 'edit_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('edit_product');
        Permission::create(['name' => 'delete_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('delete_product');
        Permission::create(['name' => 'view_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('view_product');
        Permission::create(['name' => 'active_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('active_product');
        Permission::create(['name' => 'restore_product', 'guard_name' => 'admin', 'group_name' => 'Product']);
        $role->givePermissionTo('restore_product');
        Permission::create(['name' => 'add_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('add_purchase');
        Permission::create(['name' => 'edit_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('edit_purchase');
        Permission::create(['name' => 'delete_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('delete_purchase');
        Permission::create(['name' => 'view_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('view_purchase');
        Permission::create(['name' => 'active_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('active_purchase');
        Permission::create(['name' => 'restore_purchase', 'guard_name' => 'admin', 'group_name' => 'Purchase']);
        $role->givePermissionTo('restore_purchase');
        Permission::create(['name' => 'view_stock', 'guard_name' => 'admin', 'group_name' => 'Stock']);
        $role->givePermissionTo('view_stock');*/
        Permission::create(['name' => 'update_stock', 'guard_name' => 'admin', 'group_name' => 'Stock']);
        $role->givePermissionTo('update_stock');
        /*Permission::create(['name' => 'add_flash_sale', 'guard_name' => 'admin', 'group_name' => 'Flash Sale']);
        $role->givePermissionTo('add_flash_sale');
        Permission::create(['name' => 'delete_flash_sale', 'guard_name' => 'admin', 'group_name' => 'Flash Sale']);
        $role->givePermissionTo('delete_flash_sale');
        Permission::create(['name' => 'view_flash_sale', 'guard_name' => 'admin', 'group_name' => 'Flash Sale']);
        $role->givePermissionTo('view_flash_sale');
        Permission::create(['name' => 'add_slider', 'guard_name' => 'admin', 'group_name' => 'Slider']);
        $role->givePermissionTo('add_slider');
        Permission::create(['name' => 'edit_slider', 'guard_name' => 'admin', 'group_name' => 'Slider']);
        $role->givePermissionTo('edit_slider');
        Permission::create(['name' => 'delete_slider', 'guard_name' => 'admin', 'group_name' => 'Slider']);
        $role->givePermissionTo('delete_slider');
        Permission::create(['name' => 'view_slider', 'guard_name' => 'admin', 'group_name' => 'Slider']);
        $role->givePermissionTo('view_slider');
        Permission::create(['name' => 'active_slider', 'guard_name' => 'admin', 'group_name' => 'Slider']);
        $role->givePermissionTo('active_slider');
        Permission::create(['name' => 'add_coupon', 'guard_name' => 'admin', 'group_name' => 'Coupon']);
        $role->givePermissionTo('add_coupon');
        Permission::create(['name' => 'edit_coupon', 'guard_name' => 'admin', 'group_name' => 'Coupon']);
        $role->givePermissionTo('edit_coupon');
        Permission::create(['name' => 'delete_coupon', 'guard_name' => 'admin', 'group_name' => 'Coupon']);
        $role->givePermissionTo('delete_coupon');
        Permission::create(['name' => 'view_coupon', 'guard_name' => 'admin', 'group_name' => 'Coupon']);
        $role->givePermissionTo('view_coupon');
        Permission::create(['name' => 'active_coupon', 'guard_name' => 'admin', 'group_name' => 'Coupon']);
        $role->givePermissionTo('active_coupon');
        Permission::create(['name' => 'add_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('add_page');
        Permission::create(['name' => 'edit_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('edit_page');
        Permission::create(['name' => 'delete_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('delete_page');
        Permission::create(['name' => 'view_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('view_page');
        Permission::create(['name' => 'active_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('active_page');
        Permission::create(['name' => 'restore_page', 'guard_name' => 'admin', 'group_name' => 'Page']);
        $role->givePermissionTo('restore_page');
        Permission::create(['name' => 'view_activity_log', 'guard_name' => 'admin', 'group_name' => 'activity_logs']);
        $role->givePermissionTo('view_activity_log');
        */
        $admin->assignRole($role);


        /*for ($i=0; $i < 11 ; $i++) {
            $category = Category::factory()->create();

            foreach (['en', 'ar'] as $locale) {
                CategoryTranslation::factory()->create([
                    'category_id' => $category->id,
                    'locale' => $locale,
                ]);
            }
        }

        Brand::factory()->count(10)->create();
        Tax::factory()->count(10)->create();
*/
        /*$units = [
            ['en' => 'Kilogram', 'ar' => 'كيلوجرام', 'code' => 'KG'],
            ['en' => 'Gram', 'ar' => 'جرام', 'code' => 'G'],
            ['en' => 'Liter', 'ar' => 'لتر', 'code' => 'L'],
            ['en' => 'Milliliter', 'ar' => 'ملليلتر', 'code' => 'ML'],
            ['en' => 'Piece', 'ar' => 'قطعة', 'code' => 'PCS'],
            ['en' => 'Box', 'ar' => 'علبة', 'code' => 'BOX'],
            ['en' => 'Meter', 'ar' => 'متر', 'code' => 'M'],
            ['en' => 'Centimeter', 'ar' => 'سنتيمتر', 'code' => 'CM'],
        ];

        foreach ($units as $u) {
            $unit = \App\Models\Unit::firstOrCreate(
                ['code' => $u['code']],
                ['active' => true]
            );

            $unit->translateOrNew('ar')->name = $u['ar'];
            $unit->translateOrNew('en')->name = $u['en'];
            $unit->save();
        }

        */
        /*$attributes = [
                'اللون' => [
                    'ar' => ['أحمر', 'وردي', 'نيود'],
                    'en' => ['Red', 'Pink', 'Nude'],
                ],
                'الحجم' => [
                    'ar' => ['50ml', '100ml', '200ml'],
                    'en' => ['50ml', '100ml', '200ml'], // نفس الشكل بس عادي
                ],
                'نوع البشرة' => [
                    'ar' => ['دهنية', 'جافة', 'مختلطة'],
                    'en' => ['Oily', 'Dry', 'Combination'],
                ],
                'نوع المنتج' => [
                    'ar' => ['كريم', 'سيروم', 'ماسك'],
                    'en' => ['Cream', 'Serum', 'Mask'],
                ],
                'الاستخدام' => [
                    'ar' => ['صباحي', 'مسائي', 'يومي'],
                    'en' => ['Morning', 'Night', 'Daily'],
                ],
                'نوع الشعر' => [
                    'ar' => ['عادي', 'دهني', 'جاف'],
                    'en' => ['Normal', 'Oily', 'Dry'],
                ],
                'التغطية' => [
                    'ar' => ['خفيفة', 'متوسطة', 'كاملة'],
                    'en' => ['Light', 'Medium', 'Full'],
                ],
                'درجة الحماية (SPF)' => [
                    'ar' => ['15', '30', '50'],
                    'en' => ['15', '30', '50'],
                ],
            ];

            $translations = [
                'اللون' => 'Color',
                'الحجم' => 'Size',
                'نوع البشرة' => 'Skin Type',
                'نوع المنتج' => 'Product Type',
                'الاستخدام' => 'Usage',
                'نوع الشعر' => 'Hair Type',
                'التغطية' => 'Coverage',
                'درجة الحماية (SPF)' => 'SPF',
            ];

            foreach ($attributes as $parentAr => $children) {
                // Parent
                $parent = \App\Models\Attribute::Create([
                    'parent_id' => null,
                    'active' => true
                ]);

                $parent->translateOrNew('ar')->name = $parentAr;
                $parent->translateOrNew('en')->name = $translations[$parentAr] ?? $parentAr;
                $parent->save();

                // Children
                foreach ($children['ar'] as $index => $childAr) {
                    $childEn = $children['en'][$index] ?? $childAr;

                    $child = \App\Models\Attribute::Create(
                        ['parent_id' => $parent->id, 'active' => true]
                    );

                    $child->translateOrNew('ar')->name = $childAr;
                    $child->translateOrNew('en')->name = $childEn;
                    $child->save();
                }
            }
/*
            DB::transaction(function () {
                Product::factory()
                    ->count(1000)
                    ->create()
                    ->each(function ($product) {
                        // ترجمات
                        ProductTranslation::factory()->create([
                            'product_id' => $product->id,
                            'locale' => 'ar',
                        ]);

                        ProductTranslation::factory()->create([
                            'product_id' => $product->id,
                            'locale' => 'en',
                        ]);

                        // صور
                        ProductImage::factory()->count(3)->create([
                            'product_id' => $product->id,
                        ]);
                    });
            });
*/


    }
}
