<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_coupon'])->only(['store']);
        $this->middleware(['permission:edit_coupon'])->only(['update']);
        $this->middleware(['permission:view_coupon'])->only(['index']);
        $this->middleware(['permission:delete_coupon'])->only(['delete']);
        $this->middleware(['permission:active_coupon'])->only(['active']);
        $this->middleware(['permission:restore_coupon'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $coupons = Coupon::all();

            return response()->json([
                'data' => $coupons->map(function ($coupon) {

                    $actions = '';

                    if(auth()->user()->can('edit_coupon')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $coupon->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_coupon')) {
                        $route = route('admin.coupons.destroy', $coupon->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $coupon->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $coupon->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_coupon')) {
                        if($coupon->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.coupons.active", $coupon->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.coupons.active", $coupon->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'min_order' => $coupon->min_order,
                        'max_usage' => $coupon->max_usage,
                        'used_count' => $coupon->used_count,
                        'start_date' => $coupon->start_date,
                        'end_date' => $coupon->end_date,
                        'created_at' => $coupon->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $coupons->count(),
                'recordsFiltered' => $coupons->count(),
            ]);
        }

        return view('admin.coupons.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        if($request->min_order == null) {
            $data = Arr::except($request->validated(), ['min_order']);
        } else {
            $data = $request->validated();
        }

        $coupon = Coupon::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة كوبون جديد'.$coupon->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('coupon.add_success'),
            'message' => trans('coupon.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        if($request->min_order == null) {
            $data = Arr::except($request->validated(), ['min_order']);
        }else {
            $data = $request->validated();
        }

        $coupon->update($data);

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل كوبون'.$coupon->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('coupon.updated_success'),
            'message' => trans('coupon.updated_success')
        ];

        return redirect()->route('admin.coupons.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف كوبون '.$coupon->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('coupon.deleted_success'),
            'message' => trans('coupon.deleted_success')
        ];

        return redirect()->route('admin.coupons.index')->with($message);
    }

    public function active(Coupon $coupon)
    {
        $coupon->active ^= 1;
        $coupon->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $coupon->active ? trans('coupon.active_success') : trans('coupon.deactive_success'),
            'message' => $coupon->active ? trans('coupon.active_success') : trans('coupon.deactive_success'),
        ];

        $coupon->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل كوبون '.$coupon->code) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل كوبون '.$coupon->code);

        return redirect()->back()->with($message);
    }
}
