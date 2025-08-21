<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_city'])->only(['store']);
        $this->middleware(['permission:edit_city'])->only(['update']);
        $this->middleware(['permission:view_city'])->only(['index']);
        $this->middleware(['permission:delete_city'])->only(['delete']);
        $this->middleware(['permission:restore_city'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Area $country, Area $governorate, Request $request)
    {
        if ($request->ajax()) {
            $cities = $governorate->cities;

            return response()->json([
                'data' => $cities->map(function ($city) use ($country, $governorate) {

                    $actions = '';

                    if(auth()->user()->can('edit_city')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $city->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_city')) {
                        $route = route('admin.cities.destroy', [$country->id, $governorate->id, $city->id]);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $city->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $city->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }


                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                        'english' => $city->english,
                        'shipping_cost' => $city->shipping_cost,
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $cities->count(),
                'recordsFiltered' => $cities->count(),
            ]);
        }

        return view('admin.cities.index', ['country' => $country, 'governorate' => $governorate]);
    }

    public function trash(Area $country, Area $governorate)
    {
        $cities = $governorate->cities()->onlyTrashed()->get();
        return view('admin.cities.trash', ['cities' => $cities, 'country' => $country, 'governorate' => $governorate]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Area $country, Area $governorate, CityRequest $request)
    {
        $data = array_merge($request->validated(), ['parent_id' => $governorate->id]);

        $city = Area::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة مدينة جديدة'.$city->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('city.add_success'),
            'message' => trans('city.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($country_id, $governorate_id,  $id)
    {
        $city = Area::findOrFail($id);
        return response()->json($city);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, Area $country, Area $governorate, Area $city)
    {
        $city->update($request->validated());

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل مدينة'.$city->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('city.updated_success'),
            'message' => trans('city.updated_success')
        ];

        return redirect()->route('admin.cities.index', [$country->id, $governorate->id])->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $country, Area $governorate, Area $city)
    {
        $city->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف مدينة '.$city->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('city.deleted_success'),
            'message' => trans('city.deleted_success')
        ];

        return redirect()->route('admin.cities.index', [$country->id, $governorate->id])->with($message);
    }

    public function restore(Area $country, Area $governorate, $id)
    {
        $city = Area::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('city.restored_success'),
            'message' => trans('city.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المدينة '.Area::find($id)->name);

        return redirect()->back()->with($message);
    }
}
