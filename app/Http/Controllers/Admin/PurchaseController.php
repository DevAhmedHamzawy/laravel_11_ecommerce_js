<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseAttribute;
use App\Models\Supplier;
use App\Models\Tax;
use App\Services\PurchaseService;
use App\Upload\Upload;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchase_service;

    public function __construct(PurchaseService $purchase_service)
    {
        $this->purchase_service = $purchase_service;
        $this->middleware(['permission:add_purchase'])->only(['create', 'store']);
        $this->middleware(['permission:edit_purchase'])->only(['edit', 'update']);
        $this->middleware(['permission:view_purchase'])->only(['index']);
        $this->middleware(['permission:delete_purchase'])->only(['delete']);
    }
    public function index()
    {
        $purchases = Purchase::all();
        return view('admin.purchases.index', ['purchases' => $purchases]);
    }

    public function create()
    {
        $products = Product::whereActive(1)->get();
        $attributes = Attribute::whereNull('parent_id')->whereActive(1)->get();
        $suppliers = Supplier::all();
        $taxes = Tax::all();

        return view('admin.purchases.add' , compact('products' , 'attributes' , 'suppliers', 'taxes'));
    }

    public function store(PurchaseRequest $request)
    {
        $this->purchase_service->create($request);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('purchase.add_success'),
            'message' => trans('purchase.add_success')
        ];

        activity()->log('قام '.auth()->user()->name.' باضافة طلب شراء جديد'.$request->reference_number);

        return redirect()->route('admin.purchases.index')->with($message);

    }

    public function show(Purchase $purchase)
    {
        $purchase->load('items', 'items.attributes');
        return view('admin.purchases.show', ['purchase' => $purchase,
         'products' => Product::all(), 'suppliers' => Supplier::all(), 'attributes' => Attribute::whereNull('parent_id')->whereActive(1)->get(), 'taxes' => Tax::all(), 'edit' => true]);
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('items', 'items.attributes');
        return view('admin.purchases.edit', ['purchase' => $purchase,
         'products' => Product::all(), 'suppliers' => Supplier::all(), 'attributes' => Attribute::whereNull('parent_id')->whereActive(1)->get(), 'taxes' => Tax::all(), 'edit' => true]);
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $request->merge(['id' => $purchase->id]);

        $this->purchase_service->create($request);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('purchase.updated_success'),
            'message' => trans('purchase.updated_success')
        ];

        activity()->log('قام '.auth()->user()->name.' بتعديل طلب شراء'.$request->reference_number);

        return redirect()->route('admin.purchases.index')->with($message);

    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        $purchase->items()->delete();
        $purchase->stocks()->delete();

         $message = [
            'alert-type' => 'success',
            'title' =>  trans('purchase.deleted_success'),
            'message' => trans('purchase.deleted_success')
        ];

        activity()->log('قام '.auth()->user()->name.' بحذف طلب شراء'.$purchase->reference_number);

        return redirect()->route('admin.purchases.index')->with($message);
    }
}
