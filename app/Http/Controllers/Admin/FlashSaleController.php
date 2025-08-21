<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlashSaleRequest;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_flash_sale'])->only(['create', 'store', 'choose_categories', 'show_products']);
        $this->middleware(['permission:view_flash_sale'])->only(['index']);
        $this->middleware(['permission:delete_flash_sale'])->only(['delete']);
    }
    public function index()
    {
        $flash_sales = FlashSale::all();

        return view('admin.flash_sales.index', compact('flash_sales'));
    }

    public function chooseCategories()
    {
        $categories = Category::whereActive(1)->get();

        return view('admin.flash_sales.choose_categories', compact('categories'));
    }

    public function showProducts(Request $request)
    {
        $products = Product::whereIn('category_id', $request->input('category_ids'))->whereRelation('stocks', 'qty', '>', 0)->get();

        return view('admin.flash_sales.show_products', compact('products'));
    }

    public function create(Request $request)
    {
        $product_ids = $request->input('product_ids');

        return view('admin.flash_sales.create', compact('product_ids'));
    }

    public function store(FlashSaleRequest $request)
    {

        if(!$request->has('product_ids')) {
            return redirect()->route('admin.flash_sales.index');
        }

        $request->merge(['active' => 0]);

        $flash_sale = FlashSale::create($request->only('name', 'discount', 'start_time', 'end_time', 'active'));


        foreach ($request->input('product_ids') as $product_id) {
            $flash_sale->products()->create(['product_id' => $product_id]);
        }


        $message = [
            'alert-type' => 'success',
            'title' =>  trans('flash_sale.add_success'),
            'message' => trans('flash_sale.add_success')
        ];

        activity()->log('قام '.auth()->user()->name.' باضافة عرض جديد'.$flash_sale->name);

        return redirect()->route('admin.flash_sales.index')->with($message);
    }


    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();

        activity()->log('قام '.auth()->user()->name.' بحذف العرض'.$flashSale->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('flash_sale.deleted_success'),
            'message' => trans('flash_sale.deleted_success')
        ];

        return redirect()->route('admin.flash_sales.index')->with($message);
    }

}
