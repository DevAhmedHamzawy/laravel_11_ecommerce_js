<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tax;
use App\Models\Unit;
use App\Upload\Upload;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_product'])->only(['create', 'store']);
        $this->middleware(['permission:edit_product'])->only(['edit', 'update']);
        $this->middleware(['permission:view_product'])->only(['index']);
        $this->middleware(['permission:delete_product'])->only(['delete']);
        $this->middleware(['permission:active_product'])->only(['active']);
        $this->middleware(['permission:restore_product'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::get();
        return view('admin.products.index', ['products' => $products]);
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->get();
        return view('admin.products.trash', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->whereActive(1)->get();
        $taxes = Tax::whereActive(1)->get();
        $units = Unit::whereActive(1)->get();
        $brands = Brand::whereActive(1)->get();
        return view('admin.products.add', ['categories' => $categories, 'taxes' => $taxes, 'units' => $units, 'brands' => $brands]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'products' , $request->translations['en']['name']), 'slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Product::class)]);

        $product = Product::create($request->all());

        foreach ($request->translations as $locale => $translation) {
            $product->translateOrNew($locale)->fill($translation)->save();
        }

        if ($request->has('images')) {
            foreach ($request->images as $imageName) {
                $product->images()->create(['image' => $imageName]);
            }
        }

        activity()->log('قام '.auth()->user()->name.' باضافة منتج جديد'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('product.add_success'),
            'message' => trans('product.add_success')
        ];

        return redirect()->route('admin.products.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->whereActive(1)->get();
        $taxes = Tax::whereActive(1)->get();
        $units = Unit::whereActive(1)->get();
        $brands = Brand::whereActive(1)->get();

        // Get Current Category Then SubCategory
        $subCategory = Category::where('id', $product->category_id)->first();
        $subCategory == null ? $category = null : $category = Category::where('id', $subCategory->parent_id)->first()->id;

        return view('admin.products.edit', ['product' => $product, 'categories' => $categories, 'theCategory' => $category, 'theSubCategory' => $subCategory, 'taxes' => $taxes, 'units' => $units, 'brands' => $brands]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        if ($request->has('main_image')) {

            Upload::deleteImage('products', $product->image);

            $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'products' , $request->translations['en']['name'])]);
        }

        $request->merge(['slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Product::class)]);

        $product->update($request->all());

        if ($request->has('images')) {
            foreach ($request->images as $imageName) {
                $product->images()->create(['image' => $imageName]);
            }
        }

        foreach ($request->translations as $locale => $translation) {
            $product->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.' بتعديل المنتج'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('product.updated_success'),
            'message' => trans('product.updated_success')
        ];

        return redirect()->route('admin.products.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف المنتج '.$product->translations[1]['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('product.deleted_success'),
            'message' => trans('product.deleted_success')
        ];

        return redirect()->route('admin.products.index')->with($message);
    }

    public function active(Product $product)
    {
        $product->active ^= 1;
        $product->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $product->active ? trans('product.active_success') : trans('product.deactive_success'),
            'message' => $product->active ? trans('product.active_success') : trans('product.deactive_success'),
        ];

        $product->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل منتج '.$product->id) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل منتج '.$product->id);

        return redirect()->back()->with($message);
    }

    public function restore($slug)
    {
        $product = Product::withTrashed()->whereSlug($slug)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('product.restored_success'),
            'message' => trans('product.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المنتج '.Product::whereSlug($slug)->first()->name);

        return redirect()->back()->with($message);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('files')) {

            $section = $request->input('section', 'general');
            $name = rand(1000, 9999);

            // دالة التخزين بتاعتك
            $filename = Upload::uploadImage($request->file('files'), $section, $name);

            return response()->json([
                'success' => true,
                'filename' => $filename
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file found'
        ]);
    }

    public function deleteImage(Request $request)
    {
        $image = ProductImage::find($request->id);
        Upload::deleteImage('products', $image->image);
        $image->delete();

        return response()->json(['success' => true]);

    }

    public function getData(Request $request)
    {
        $qty = $request->qty;

        $product = Product::where('id', $request->product_id)->first();

        $product->price_qty = $product->buying_price * $qty;

        return $product;
    }

}
