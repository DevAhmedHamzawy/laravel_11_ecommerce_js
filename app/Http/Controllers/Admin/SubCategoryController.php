<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Upload\Upload;

class SubCategoryController extends Controller
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
    public function index(Category $category)
    {
        return view('admin.subcategories.index', ['category' => $category, 'subcategories' => $category->children()->get()]);
    }

    public function trash(Category $category)
    {
        return view('admin.subcategories.trash', ['category' => $category, 'subcategories' => $category->children()->onlyTrashed()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('admin.subcategories.add', ['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request, Category $category)
    {
        $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'categories' , $request->translations['en']['name']), 'slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Category::class)]);

        $subcategory = $category->children()->create($request->except('main_image'));

        foreach ($request->translations as $locale => $translation) {
            $subcategory->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'باضافة فرع تصنيف جديد'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.add_success'),
            'message' => trans('category.add_success')
        ];

        return redirect()->route('admin.subcategories.index', $category)->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Category $subcategory)
    {
        return view('admin.subcategories.edit', ['category' => $category, 'subcategory' => $subcategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category, Category $subcategory)
    {

        if ($request->has('main_image')) {

            Upload::deleteImage('categories', $subcategory->image);

            $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'categories' , $request->translations['en']['name'])]);
        }

        $request->merge(['slug' => MakeSlug::createUniqueSlug($request->translations['en']['name'], Category::class)]);

        $subcategory->update($request->all());

        foreach ($request->translations as $locale => $translation) {
            $subcategory->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'بتعديل فرع التصنيف'.$request->translations['ar']['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.updated_success'),
            'message' => trans('category.updated_success')
        ];

        return redirect()->route('admin.subcategories.index', $category)->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Category $subcategory)
    {
        $subcategory->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف فرع التصنيف'.$subcategory->translations[1]['name']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.deleted_success'),
            'message' => trans('category.deleted_success')
        ];

        return redirect()->route('admin.subcategories.index', $category)->with($message);
    }

   public function active(Category $category, Category $subcategory)
    {
        $subcategory->active ^= 1;
        $subcategory->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $subcategory->active ? trans('category.active_success') : trans('category.deactive_success'),
            'message' => $subcategory->active ? trans('category.active_success') : trans('category.deactive_success'),
        ];


        return redirect()->back()->with($message);
    }
    public function appearHome(Category $category, Category $subcategory)
    {
        $subcategory->appear_home ^= 1;
        $subcategory->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $subcategory->appear_home ? trans('category.appeared_in_home_success') : trans('category.disappeared_in_home_success'),
            'message' => $subcategory->appear_home ? trans('category.appeared_in_home_success') : trans('category.disappeared_in_home_success'),
        ];

        $subcategory->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل فرع التصنيف '.$subcategory->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل فرع التصنيف '.$subcategory->name);

        return redirect()->back()->with($message);
    }

    public function restore(Category $category, $slug)
    {
        Category::withTrashed()->whereSlug($slug)->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('category.restored_success'),
            'message' => trans('category.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة فرع التصنيف '.Category::find($category->id)->name);

        return redirect()->back()->with($message);
    }

}
