<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Upload\Upload;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_category'])->only(['create', 'store']);
        $this->middleware(['permission:edit_category'])->only(['edit', 'update']);
        $this->middleware(['permission:view_category'])->only(['index']);
        $this->middleware(['permission:delete_category'])->only(['delete']);
        $this->middleware(['permission:active_category'])->only(['active']);
        $this->middleware(['permission:restore_category'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function trash()
    {
        $categories = Category::whereNull('parent_id')->onlyTrashed()->get();
        return view('admin.categories.trash', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'categories' , $request->translations['en']['name']), 'slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Category::class)]);

        $category = Category::create($request->all());

        foreach ($request->translations as $locale => $translation) {
            $category->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'باضافة تصنيف جديد'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.add_success'),
            'message' => trans('category.add_success')
        ];

        return redirect()->route('admin.categories.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if ($request->has('main_image')) {

            Upload::deleteImage('categories', $category->image);

            $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'categories' , $request->translations['en']['name'])]);
        }

        $request->merge(['slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Category::class)]);

        $category->update($request->all());

        foreach ($request->translations as $locale => $translation) {
            $category->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'بتعديل التصنيف'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.updated_success'),
            'message' => trans('category.updated_success')
        ];

        return redirect()->route('admin.categories.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        $category->children()->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف التصنيف'.$category->translations[1]['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.deleted_success'),
            'message' => trans('category.deleted_success')
        ];

        return redirect()->route('admin.categories.index')->with($message);
    }

    public function active(Category $category)
    {
        $category->active ^= 1;
        $category->save();

        $category->active ? $category->children()->update(['active' => 1]) : $category->children()->update(['active' => 0]);

        $message = [
            'alert-type' => 'success',
            'title' =>  $category->active ? trans('category.active_success') : trans('category.deactive_success'),
            'message' => $category->active ? trans('category.active_success') : trans('category.deactive_success'),
        ];


        return redirect()->back()->with($message);
    }

    public function appearHome(Category $category)
    {
        $category->appear_home ^= 1;
        $category->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $category->appear_home ? trans('category.appeared_in_home_success') : trans('category.disappeared_in_home_success'),
            'message' => $category->appear_home ? trans('category.appeared_in_home_success') : trans('category.disappeared_in_home_success'),
        ];

        $category->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل التصنيف '.$category->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل التصنيف '.$category->name);

        return redirect()->back()->with($message);
    }

    public function restore($slug)
    {
        $category = Category::withTrashed()->whereSlug($slug)->firstOrFail()->restore();

        Category::whereSlug($slug)->first()->children()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.restored_success'),
            'message' => trans('category.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة التصنيف '.Category::whereSlug($slug)->first()->name);

        return redirect()->back()->with($message);
    }

}
