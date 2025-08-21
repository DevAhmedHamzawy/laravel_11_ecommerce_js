<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernorateRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_governorate'])->only(['store']);
        $this->middleware(['permission:edit_governorate'])->only(['update']);
        $this->middleware(['permission:view_governorate'])->only(['index']);
        $this->middleware(['permission:delete_governorate'])->only(['delete']);
        $this->middleware(['permission:restore_governorate'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Area $country, Request $request)
    {
        if ($request->ajax()) {
            $governorates = $country->governorates;

            return response()->json([
                'data' => $governorates->map(function ($governorate) use ($country) {

                    $actions = '';

                    if(auth()->user()->can('edit_governorate')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $governorate->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_governorate')) {
                        $route = route('admin.governorates.destroy', [$country->id, $governorate->id]);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $governorate->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $governorate->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                     if(auth()->user()->can('view_city')) {
                        $route = route('admin.cities.index', [$country->id, $governorate->id]);
                        $actions .= ' <a href="' . $route . '" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="' . trans('dashboard.show') . '"><i class="fas fa-eye"></i></a>';
                    }


                    return [
                        'id' => $governorate->id,
                        'name' => $governorate->name,
                        'english' => $governorate->english,
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $governorates->count(),
                'recordsFiltered' => $governorates->count(),
            ]);
        }

        return view('admin.governorates.index', ['country' => $country]);
    }

    public function trash(Area $country)
    {
        $governorates = $country->governorates()->onlyTrashed()->get();
        return view('admin.governorates.trash', ['governorates' => $governorates, 'country' => $country]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Area $country, GovernorateRequest $request)
    {
        $data = array_merge($request->validated(), ['parent_id' => $country->id]);

        $governorate = Area::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة محافظة / ولاية جديدة'.$governorate->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('governorate.add_success'),
            'message' => trans('governorate.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($country_id, $id)
    {
        $governorate = Area::findOrFail($id);
        return response()->json($governorate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GovernorateRequest $request, Area $country, Area $governorate)
    {
        $governorate->update($request->validated());

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل محافظة / ولاية'.$governorate->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('governorate.updated_success'),
            'message' => trans('governorate.updated_success')
        ];

        return redirect()->route('admin.governorates.index', $country->id)->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $country, Area $governorate)
    {
        $governorate->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف محافظة / ولاية '.$governorate->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('governorate.deleted_success'),
            'message' => trans('governorate.deleted_success')
        ];

        return redirect()->route('admin.governorates.index', $country->id)->with($message);
    }

    public function restore(Area $country, $id)
    {
        $governorate = Area::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('governorate.restored_success'),
            'message' => trans('governorate.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المحافظة / ولاية '.Area::find($id)->name);

        return redirect()->back()->with($message);
    }
}
