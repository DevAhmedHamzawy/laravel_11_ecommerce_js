<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_brand'])->only(['store']);
        $this->middleware(['permission:edit_brand'])->only(['update']);
        $this->middleware(['permission:view_brand'])->only(['index']);
        $this->middleware(['permission:delete_brand'])->only(['delete']);
        $this->middleware(['permission:active_brand'])->only(['active']);
        $this->middleware(['permission:restore_brand'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::all();

            return response()->json([
                'data' => $brands->map(function ($brand) {

                    $actions = '';

                    if(auth()->user()->can('edit_brand')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $brand->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_brand')) {
                        $route = route('admin.brands.destroy', $brand->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $brand->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $brand->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_brand')) {
                        if($brand->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.brands.active", $brand->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.brands.active", $brand->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $brand->id,
                        'img_path' => "<img width='50' height='50' src='" . $brand->img_path . "' alt='' srcset=''>",
                        'name' => $brand->name,
                        'description' => $brand->description,
                        'created_at' => $brand->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $brands->count(),
                'recordsFiltered' => $brands->count(),
            ]);
        }

        return view('admin.brands.index');
    }

      public function trash()
    {
        $brands = Brand::onlyTrashed()->get();
        return view('admin.brands.trash', ['brands' => $brands]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        // رفع الصورة وإنشاء السلاج
        $image = Upload::uploadImage($request->main_image, 'brands', $request->name);

        // دمج البيانات الجديدة مع البيانات الموثقة من الفورم
        $data = array_merge(Arr::except($request->validated(), ['main_image']), [
            'image' => $image,
        ]);

        $brand = Brand::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة علامة تجارية جديدة'.$brand->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('brand.add_success'),
            'message' => trans('brand.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        // تحضير بيانات التحديث
        $data = $request->validated();
        unset($data['main_image']);

        // لو فيه صورة جديدة
        if ($request->hasFile('main_image')) {
            Upload::deleteImage('brands', $brand->image);

            $uniqueName = $request->name;
            $data['image'] = Upload::uploadImage($request->main_image, 'brands', $uniqueName);
        }

        // تحديث البراند
        $brand->update($data);

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل علامة تجارية'.$brand->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('brand.updated_success'),
            'message' => trans('brand.updated_success')
        ];

        return redirect()->route('admin.brands.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف علامة تجارية '.$brand->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('brand.deleted_success'),
            'message' => trans('brand.deleted_success')
        ];

        return redirect()->route('admin.brands.index')->with($message);
    }

    public function active(Brand $brand)
    {
        $brand->active ^= 1;
        $brand->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $brand->active ? trans('brand.active_success') : trans('brand.deactive_success'),
            'message' => $brand->active ? trans('brand.active_success') : trans('brand.deactive_success'),
        ];

        $brand->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل العلامة التجارية '.$brand->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل العلامة التجارية '.$brand->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        $brand = Brand::withTrashed()->whereId($id)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('brand.restored_success'),
            'message' => trans('brand.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة العلامة التجارية '.Brand::find($id)->name);

        return redirect()->back()->with($message);
    }
}
