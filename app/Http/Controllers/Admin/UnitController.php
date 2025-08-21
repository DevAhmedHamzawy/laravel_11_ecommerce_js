<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_unit'])->only(['store']);
        $this->middleware(['permission:edit_unit'])->only(['update']);
        $this->middleware(['permission:view_unit'])->only(['index']);
        $this->middleware(['permission:delete_unit'])->only(['delete']);
        $this->middleware(['permission:active_unit'])->only(['active']);
        $this->middleware(['permission:restore_unit'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $units = Unit::with('translations')->get();

            return response()->json([
                'data' => $units->map(function ($unit) {

                    $actions = '';

                    if(auth()->user()->can('edit_unit')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $unit->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_unit')) {
                        $route = route('admin.units.destroy', $unit->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $unit->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $unit->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_unit')) {
                        if($unit->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.units.active", $unit->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.units.active", $unit->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $unit->id,
                         'translate' => [
                            'ar' => [
                                'name' => $unit->translate('ar')->name ?? ''
                            ],
                            'en' => [
                                'name' => $unit->translate('en')->name ?? ''
                            ]
                        ],
                        'code' => $unit->code,
                        'created_at' => $unit->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $units->count(),
                'recordsFiltered' => $units->count(),
            ]);
        }

        return view('admin.units.index');
    }

      public function trash()
    {
        $units = Unit::onlyTrashed()->with('translations')->get();
        return view('admin.units.trash', ['units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::create($request->validated());

        foreach ($request->translations as $locale => $translation) {
            $unit->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.' باضافة وحده جديدة'.$unit->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('unit.add_success'),
            'message' => trans('unit.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unit = Unit::with('translations')->findOrFail($id);
        return response()->json($unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        foreach ($request->translations as $locale => $translation) {
            $unit->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل وحده'.$unit->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('unit.updated_success'),
            'message' => trans('unit.updated_success')
        ];

        return redirect()->route('admin.units.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف وحده '.$unit->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('unit.deleted_success'),
            'message' => trans('unit.deleted_success')
        ];

        return redirect()->route('admin.units.index')->with($message);
    }

    public function active(Unit $unit)
    {
        $unit->active ^= 1;
        $unit->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $unit->active ? trans('unit.active_success') : trans('unit.deactive_success'),
            'message' => $unit->active ? trans('unit.active_success') : trans('unit.deactive_success'),
        ];

        $unit->active ? activity()->log('قام '.auth()->user()->name.'بتفعيل الوحده '.$unit->translate('ar')->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل الوحده '.$unit->translate('ar')->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        $unit = Unit::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('unit.restored_success'),
            'message' => trans('unit.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة الوحده '.Unit::find($id)->translate('ar')->name);

        return redirect()->back()->with($message);
    }
}
