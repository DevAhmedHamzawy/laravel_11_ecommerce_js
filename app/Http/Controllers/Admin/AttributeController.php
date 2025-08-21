<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attributes = Attribute::whereNull('parent_id')->with('translations')->latest()->get();

            return response()->json([
                'data' => $attributes->map(function ($attribute) {

                    $actions = '';

                    if(auth()->user()->can('view_attribute')) {

                        $route = route('admin.subattributes.index', $attribute->id);

                        $actions .= '<a href="'. $route .'" class="btn btn-info view-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.show") .'"><i class="fas fa-eye"></i></a>';
                    }

                    if(auth()->user()->can('edit_attribute')) {
                        $actions .= '&nbsp;<a href="javascript:void(0)" data-id="' . $attribute->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_attribute')) {
                        $route = route('admin.attributes.destroy', $attribute->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $attribute->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $attribute->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_attribute')) {
                        if($attribute->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.attributes.active", $attribute->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.attributes.active", $attribute->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $attribute->id,
                        'translate' => [
                            'ar' => [
                                'name' => $attribute->translate('ar')->name ?? ''
                            ],
                            'en' => [
                                'name' => $attribute->translate('en')->name ?? ''
                            ]
                        ],
                        'created_at' => $attribute->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $attributes->count(),
                'recordsFiltered' => $attributes->count(),
            ]);
        }

        return view('admin.attributes.index');
    }

      public function trash()
    {
        $attributes = Attribute::whereNull('parent_id')->onlyTrashed()->with('translations')->get();
        return view('admin.attributes.trash', ['attributes' => $attributes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        $attribute = Attribute::create($request->validated());

        foreach ($request->translations as $locale => $translation) {
            $attribute->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.' باضافة مواصفات جديدة'.$attribute->translate('ar')->name);

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
        $attribute = Attribute::whereId($id)->with('translations')->firstOrFail();
        return response()->json($attribute);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());

         foreach ($request->translations as $locale => $translation) {
            $attribute->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل مواصفات'.$attribute->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.updated_success'),
            'message' => trans('attribute.updated_success')
        ];

        return redirect()->route('admin.attributes.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف مواصفات '.$attribute->translate('ar')->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.deleted_success'),
            'message' => trans('attribute.deleted_success')
        ];

        return redirect()->route('admin.attributes.index')->with($message);
    }

    public function active(Attribute $attribute)
    {
        $attribute->active ^= 1;
        $attribute->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $attribute->active ? trans('attribute.active_success') : trans('attribute.deactive_success'),
            'message' => $attribute->active ? trans('attribute.active_success') : trans('attribute.deactive_success'),
        ];

        $attribute->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل سلايدر '.$attribute->translate('ar')->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل سلايدر '.$attribute->translate('ar')->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        $attribute = Attribute::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('attribute.restored_success'),
            'message' => trans('attribute.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المواصفات '.Attribute::find($id)->translate('ar')->name);

        return redirect()->back()->with($message);
    }
}
