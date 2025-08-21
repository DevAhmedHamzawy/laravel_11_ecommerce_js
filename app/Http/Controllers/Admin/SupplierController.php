<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Area;
use App\Models\Supplier;
use App\Upload\Upload;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_supplier'])->only(['create', 'store']);
        $this->middleware(['permission:edit_supplier'])->only(['edit', 'update']);
        $this->middleware(['permission:view_supplier'])->only(['index']);
        $this->middleware(['permission:delete_supplier'])->only(['delete']);
        $this->middleware(['permission:active_supplier'])->only(['active']);
        $this->middleware(['permission:restore_supplier'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', ['suppliers' => $suppliers]);
    }

    public function trash()
    {
        $suppliers = Supplier::onlyTrashed()->get();
        return view('admin.suppliers.trash', ['suppliers' => $suppliers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::getMainAreas();
        return view('admin.suppliers.add', ['areas' => $areas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());

        activity()->log('قام '.auth()->user()->name.'باضافة مورد جديد'.$supplier->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('supplier.add_success'),
            'message' => trans('supplier.add_success')
        ];

        return redirect()->route('admin.suppliers.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        $areas = Area::getMainAreas();

        // Get Current Country Then City
        $city = Area::where('id', $supplier->area_id)->first();
        $city == null ? $governorate = null : $governorate = Area::where('id', $city->parent_id)->first();
        $governorate == null ? $country = null : $country = Area::where('id', $governorate->parent_id)->first();

        return view('admin.suppliers.edit', ['supplier' => $supplier, 'areas' => $areas , 'theCity' => $city, 'theGovernorate' => $governorate , 'theCountry' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        activity()->log('قام '.auth()->user()->name.'بتعديل مورد'.$supplier->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('supplier.updated_success'),
            'message' => trans('supplier.updated_success')
        ];

        return redirect()->route('admin.suppliers.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف مورد'.$supplier->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('supplier.deleted_success'),
            'message' => trans('supplier.deleted_success')
        ];

        return redirect()->route('admin.suppliers.index')->with($message);
    }

   public function active(Supplier $supplier)
    {
        $supplier->active ^= 1;
        $supplier->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $supplier->active ? trans('supplier.active_success') : trans('supplier.deactive_success'),
            'message' => $supplier->active ? trans('supplier.active_success') : trans('supplier.deactive_success'),
        ];

        $supplier->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل المورد '.$supplier->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل المورد '.$supplier->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        Supplier::withTrashed()->find($id)->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('supplier.restored_success'),
            'message' => trans('supplier.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المورد '.Supplier::find($id)->name);

        return redirect()->back()->with($message);
    }

}
