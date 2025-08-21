<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_country'])->only(['store']);
        $this->middleware(['permission:edit_country'])->only(['update']);
        $this->middleware(['permission:view_country'])->only(['index']);
        $this->middleware(['permission:delete_country'])->only(['delete']);
        $this->middleware(['permission:restore_country'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $countries = Area::whereParentId(1)->get();

            return response()->json([
                'data' => $countries->map(function ($country) {

                    $actions = '';

                    if(auth()->user()->can('edit_country')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $country->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_country')) {
                        $route = route('admin.countries.destroy', $country->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $country->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $country->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('view_city')) {
                        $route = route('admin.governorates.index', $country->id);
                        $actions .= ' <a href="' . $route . '" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="' . trans('dashboard.show') . '"><i class="fas fa-eye"></i></a>';
                    }

                    return [
                        'id' => $country->id,
                        'name' => $country->name,
                        'english' => $country->english,
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $countries->count(),
                'recordsFiltered' => $countries->count(),
            ]);
        }

        return view('admin.countries.index');
    }

    public function trash()
    {
        $countries = Area::whereParentId(1)->onlyTrashed()->get();
        return view('admin.countries.trash', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        $data = array_merge($request->validated(), ['parent_id' => 1]);

        $country = Area::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة بلد جديدة'.$country->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('country.add_success'),
            'message' => trans('country.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $country = Area::findOrFail($id);
        return response()->json($country);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, Area $country)
    {
        $country->update($request->validated());

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل بلد'.$country->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('country.updated_success'),
            'message' => trans('country.updated_success')
        ];

        return redirect()->route('admin.countries.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $country)
    {
        $country->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف بلد '.$country->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('country.deleted_success'),
            'message' => trans('country.deleted_success')
        ];

        return redirect()->route('admin.countries.index')->with($message);
    }

    public function restore($id)
    {
        $country = Area::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('country.restored_success'),
            'message' => trans('country.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة البلد '.Area::find($id)->name);

        return redirect()->back()->with($message);
    }
}
