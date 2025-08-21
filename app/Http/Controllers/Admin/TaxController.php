<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_tax'])->only(['store']);
        $this->middleware(['permission:edit_tax'])->only(['update']);
        $this->middleware(['permission:view_tax'])->only(['index']);
        $this->middleware(['permission:delete_tax'])->only(['delete']);
        $this->middleware(['permission:active_tax'])->only(['active']);
        $this->middleware(['permission:restore_tax'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $taxes = Tax::all();

            return response()->json([
                'data' => $taxes->map(function ($tax) {

                    $actions = '';

                    if(auth()->user()->can('edit_tax')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $tax->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_tax')) {
                        $route = route('admin.taxes.destroy', $tax->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $tax->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $tax->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_tax')) {
                        if($tax->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.taxes.active", $tax->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.taxes.active", $tax->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $tax->id,
                        'name' => $tax->name,
                        'code' => $tax->code,
                        'rate' => $tax->rate,
                        'created_at' => $tax->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $taxes->count(),
                'recordsFiltered' => $taxes->count(),
            ]);
        }

        return view('admin.taxes.index');
    }

      public function trash()
    {
        $taxes = Tax::onlyTrashed()->get();
        return view('admin.taxes.trash', ['taxes' => $taxes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request)
    {
        $tax = Tax::create($request->validated());

        activity()->log('قام '.auth()->user()->name.' باضافة ضريبة جديدة'.$tax->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('tax.add_success'),
            'message' => trans('tax.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tax = Tax::findOrFail($id);
        return response()->json($tax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        $tax->update($request->validated());

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل ضريبة'.$tax->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('tax.updated_success'),
            'message' => trans('tax.updated_success')
        ];

        return redirect()->route('admin.taxes.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف ضريبة '.$tax->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('tax.deleted_success'),
            'message' => trans('tax.deleted_success')
        ];

        return redirect()->route('admin.taxes.index')->with($message);
    }

    public function active(Tax $tax)
    {
        $tax->active ^= 1;
        $tax->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $tax->active ? trans('tax.active_success') : trans('tax.deactive_success'),
            'message' => $tax->active ? trans('tax.active_success') : trans('tax.deactive_success'),
        ];

        $tax->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل الضريبة '.$tax->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل الضريبة '.$tax->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        $tax = Tax::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('tax.restored_success'),
            'message' => trans('tax.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة الضريبة '.Tax::find($id)->name);

        return redirect()->back()->with($message);
    }
}
