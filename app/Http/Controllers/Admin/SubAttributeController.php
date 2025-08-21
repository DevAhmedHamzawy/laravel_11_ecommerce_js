<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class SubAttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_attribute'])->only(['store']);
        $this->middleware(['permission:edit_attribute'])->only(['update']);
        $this->middleware(['permission:view_attribute'])->only(['index']);
        $this->middleware(['permission:delete_attribute'])->only(['delete']);
        $this->middleware(['permission:active_attribute'])->only(['active']);
        $this->middleware(['permission:restore_attribute'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Attribute $attribute, Request $request)
    {
        if ($request->ajax()) {
            $subattributes = $attribute->children()->latest()->get();

            return response()->json([
                'data' => $subattributes->map(function ($subattribute) use($attribute) {

                    $actions = '';

                    if(auth()->user()->can('edit_attribute')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $subattribute->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_attribute')) {
                        $route = route('admin.subattributes.destroy', [$attribute->id, $subattribute->id]);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $subattribute->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $subattribute->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_attribute')) {
                        if($subattribute->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.subattributes.active", [$attribute->id, $subattribute->id]) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.subattributes.active", [$attribute->id, $subattribute->id]) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $subattribute->id,

                        'translate' => [
                            'ar' => [
                                'name' => $subattribute->translate('ar')->name ?? ''
                            ],
                            'en' => [
                                'name' => $subattribute->translate('en')->name ?? ''
                            ]
                        ],

                        'created_at' => $subattribute->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $subattributes->count(),
                'recordsFiltered' => $subattributes->count(),
            ]);
        }

        return view('admin.subattributes.index', ['attribute' => $attribute]);
    }

    public function trash(Attribute $attribute)
    {
        return view('admin.subattributes.trash', ['attribute' => $attribute, 'subattributes' => $attribute->children()->onlyTrashed()->with('translations')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request, Attribute $attribute)
    {
        $subattribute = $attribute->children()->create($request->validated());


        foreach ($request->translations as $locale => $translation) {
            $subattribute->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.' باضافة فرع مواصفات جديد '.$subattribute->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.add_success'),
            'message' => trans('attribute.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subattribute = Attribute::whereId($id)->with('translations')->firstOrFail();
        return response()->json($subattribute);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute, Attribute $subattribute)
    {
        $subattribute->update($request->validated());

        foreach ($request->translations as $locale => $translation) {
            $subattribute->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل فرغ المواصفات'.$subattribute->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.updated_success'),
            'message' => trans('attribute.updated_success')
        ];

        return redirect()->route('admin.subattributes.index', $attribute)->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute, Attribute $subattribute)
    {
        $subattribute->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف فرع المواصفات '.$subattribute->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.deleted_success'),
            'message' => trans('attribute.deleted_success')
        ];

        return redirect()->route('admin.subattributes.index', $attribute)->with($message);
    }

    public function active(Attribute $attribute, Attribute $subattribute)
    {
        $subattribute->active ^= 1;
        $subattribute->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $subattribute->active ? trans('attribute.active_success') : trans('attribute.deactive_success'),
            'message' => $subattribute->active ? trans('attribute.active_success') : trans('attribute.deactive_success'),
        ];

        $subattribute->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل فرع المواصفات '.$subattribute->translate('ar')->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل فرع المواصفات '.$subattribute->translate('ar')->name);

        return redirect()->back()->with($message);
    }

    public function restore(Attribute $attribute, $id)
    {
        $subattribute = Attribute::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.restored_success'),
            'message' => trans('attribute.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة فرع المواصفات '.Attribute::find($id)->translate('ar')->name);

        return redirect()->back()->with($message);
    }
}
