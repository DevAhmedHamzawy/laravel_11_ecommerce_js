<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_slider'])->only(['store']);
        $this->middleware(['permission:edit_slider'])->only(['update']);
        $this->middleware(['permission:view_slider'])->only(['index']);
        $this->middleware(['permission:delete_slider'])->only(['delete']);
        $this->middleware(['permission:active_slider'])->only(['active']);
        $this->middleware(['permission:restore_slider'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sliders = Slider::all();

            return response()->json([
                'data' => $sliders->map(function ($slider) {

                    $actions = '';

                    if(auth()->user()->can('edit_slider')) {
                        $actions .= '<a href="javascript:void(0)" data-id="' . $slider->id . '" class="btn btn-warning edit-btn" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.edit") .'"><i class="fas fa-edit"></i></a>';
                    }

                    if(auth()->user()->can('delete_slider')) {
                        $route = route('admin.sliders.destroy', $slider->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $actions .= '
                        <form id="delete-form-'. $slider->id .'" action="'. $route .'" method="POST" style="display:inline-block;">
                            '. $csrf .'
                            '. $method .'
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="'. $slider->id .'"
                                data-placement="top" data-toggle="tooltip"
                                data-original-title="'. trans('dashboard.delete') .'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }

                    if(auth()->user()->can('active_slider')) {
                        if($slider->active == 0){
                            $actions .= '&nbsp;<a href="'. route("admin.sliders.active", $slider->id) .'" class="btn btn-info" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.active") .'"><i class="fas fa-toggle-on"></i></a>';
                        }else{
                            $actions .= '&nbsp;<a href="'.  route("admin.sliders.active", $slider->id) .'" class="btn btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="'. trans("dashboard.deactive") .'"><i class="fas fa-toggle-off"></i></a>';
                        }
                    }

                    return [
                        'id' => $slider->id,
                        'img_path' => "<img width='50' height='50' src='" . $slider->img_path . "' alt='' srcset=''>",
                        'link' => $slider->link,
                        'created_at' => $slider->created_at->diffForHumans(),
                        'actions' => $actions,
                    ];
                }),
                'recordsTotal' => $sliders->count(),
                'recordsFiltered' => $sliders->count(),
            ]);
        }

        return view('admin.sliders.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request)
    {
        // رفع الصورة وإنشاء السلاج
        $image = Upload::uploadImage($request->main_image, 'sliders', $request->name);

        // دمج البيانات الجديدة مع البيانات الموثقة من الفورم
        $data = array_merge(Arr::except($request->validated(), ['main_image']), [
            'image' => $image,
        ]);

        $slider = Slider::create($data);

        activity()->log('قام '.auth()->user()->name.' باضافة سلايدر جديدة'.$slider->id);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('slider.add_success'),
            'message' => trans('slider.add_success')
        ];

        return response()->json($message);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return response()->json($slider);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, Slider $slider)
    {
        // تحضير بيانات التحديث
        $data = $request->validated();
        unset($data['main_image']);

        // لو فيه صورة جديدة
        if ($request->hasFile('main_image')) {
            Upload::deleteImage('sliders', $slider->image);

            $uniqueName = $request->name;
            $data['image'] = Upload::uploadImage($request->main_image, 'sliders', $uniqueName);
        }

        // تحديث البراند
        $slider->update($data);

        activity()->log(description: 'قام '.auth()->user()->name.' بتعديل سلايدر'.$slider->id);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('slider.updated_success'),
            'message' => trans('slider.updated_success')
        ];

        return redirect()->route('admin.sliders.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        Upload::deleteImage('sliders', $slider->image);

        $slider->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف سلايدر '.$slider->id);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('slider.deleted_success'),
            'message' => trans('slider.deleted_success')
        ];

        return redirect()->route('admin.sliders.index')->with($message);
    }

    public function active(Slider $slider)
    {
        $slider->active ^= 1;
        $slider->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $slider->active ? trans('slider.active_success') : trans('slider.deactive_success'),
            'message' => $slider->active ? trans('slider.active_success') : trans('slider.deactive_success'),
        ];

        $slider->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل سلايدر '.$slider->id) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل سلايدر '.$slider->id);

        return redirect()->back()->with($message);
    }
}
