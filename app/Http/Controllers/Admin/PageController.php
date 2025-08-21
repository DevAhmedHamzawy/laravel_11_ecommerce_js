<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MakeSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use App\Upload\Upload;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_page'])->only(['create', 'store']);
        $this->middleware(['permission:edit_page'])->only(['edit', 'update']);
        $this->middleware(['permission:view_page'])->only(['index']);
        $this->middleware(['permission:delete_page'])->only(['delete']);
        $this->middleware(['permission:active_page'])->only(['active']);
        $this->middleware(['permission:restore_page'])->only(['restore']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::get();
        return view('admin.pages.index', ['pages' => $pages]);
    }

    public function trash()
    {
        $pages = Page::onlyTrashed()->get();
        return view('admin.pages.trash', ['pages' => $pages]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageRequest $request)
    {
        $request->merge(['slug' => MakeSlug::createUniqueSlug($request->translations['en']['title'], Page::class)]);

        $page = Page::create($request->all());

        foreach ($request->translations as $locale => $translation) {
            $page->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'باضافة صفحة جديد'.$request->translations['ar']['title']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('page.add_success'),
            'message' => trans('page.add_success')
        ];

        return redirect()->route('admin.pages.index')->with($message);
    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, Page $page)
    {
        $request->merge(['slug' => MakeSlug::createUniqueSlug($request->translations['en']['title'], Page::class)]);

        $page->update($request->all());

        foreach ($request->translations as $locale => $translation) {
            $page->translateOrNew($locale)->fill($translation)->save();
        }

        activity()->log('قام '.auth()->user()->name.'بتعديل الصفحة'.$request->translations['ar']['title']);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('page.updated_success'),
            'message' => trans('page.updated_success')
        ];

        return redirect()->route('admin.pages.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الصفحة'.$page->translate('ar')->title);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('page.deleted_success'),
            'message' => trans('page.deleted_success')
        ];

        return redirect()->route('admin.pages.index')->with($message);
    }

    public function active(Page $page)
    {
        $page->active ^= 1;
        $page->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $page->active ? trans('page.active_success') : trans('page.deactive_success'),
            'message' => $page->active ? trans('page.active_success') : trans('page.deactive_success'),
        ];

        $page->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل الصفحة '.$page->translate('ar')->title) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل الصفحة '.$page->translate('ar')->title);

        return redirect()->back()->with($message);
    }

    public function restore($slug)
    {
        $page = Page::withTrashed()->whereSlug($slug)->firstOrFail()->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('page.restored_success'),
            'message' => trans('page.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة الصفحة '.Page::whereSlug($slug)->first()->translate('ar')->title);

        return redirect()->back()->with($message);
    }



}
