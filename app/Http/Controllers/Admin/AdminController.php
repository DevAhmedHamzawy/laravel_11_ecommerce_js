<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Upload\Upload;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_admin'])->only(['create', 'store']);
        $this->middleware(['permission:edit_admin'])->only(['edit', 'update']);
        $this->middleware(['permission:view_admin'])->only(['index']);
        $this->middleware(['permission:delete_admin'])->only(['delete']);
        $this->middleware(['permission:active_admin'])->only(['active']);
        $this->middleware(['permission:restore_admin'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admins.index', ['admins' => Admin::all()]);
    }

    public function trash()
    {
        $admins = Admin::onlyTrashed()->get();
        return view('admin.admins.trash', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereActive(1)->get();
        return view('admin.admins.add')->withRoles($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminRequest $request)
    {
        if($request->has('main_image')) $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'admins' , $request->name)]);
        if($request->has('password')) $request->merge(['password' => bcrypt($request->password)]);


        $admin = Admin::create($request->except('role','main_image'));

        $admin->assignRole($request->role);

        activity()->log('قام '.auth()->user()->name.'باضافة أدمن جديد'.$admin->name);

         $message = [
            'alert-type' => 'success',
            'title' =>  trans('admin.add_success'),
            'message' => trans('admin.add_success')
        ];

        return redirect()->route('admin.admins.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', ['admin' => $admin, 'roles' => Role::whereActive(1)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        if($request->password != null){
            $request->merge(['password' => bcrypt($request->password)]);
        }else{
            $request->merge(['password' => $admin->password]);
        }

        if ($request->has('main_image')) {

            Upload::deleteImage('admins', $admin->image);

            $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'admins' , $request->name)]);
        }

        $admin->update($request->except('role','main_image'));
        $admin->syncRoles($request->role);

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الأدمن'.$admin->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('admin.updated_success'),
            'message' => trans('admin.updated_success')
        ];

        return redirect()->route('admin.admins.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        if($admin->id == auth()->user()->id){
            $message = [
                'alert-type' => 'error',
                'title' =>  trans('admin.deleted_error'),
                'message' => trans('admin.deleted_error')
            ];

            return redirect()->route('admin.admins.index')->with($message);
        }

        if($admin->id == 1){
            $message = [
                'alert-type' => 'error',
                'title' =>  trans('admin.deleted_error'),
                'message' => trans('admin.deleted_error')
            ];

            return redirect()->route('admin.admins.index')->with($message);
        }

        $admin->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف الأدمن'.$admin->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('admin.deleted_success'),
            'message' => trans('admin.deleted_success')
        ];

        return redirect()->route('admin.admins.index')->with($message);

    }

    public function active(Admin $admin)
    {
        if($admin->active) {
             if($admin->id == auth()->user()->id){
                $message = [
                    'alert-type' => 'error',
                    'title' =>  trans('admin.deactive_error'),
                    'message' => trans('admin.deactive_error')
                ];

                return redirect()->route('admin.admins.index')->with($message);
            }

            if($admin->id == 1){
                $message = [
                    'alert-type' => 'error',
                    'title' =>  trans('admin.deactive_error'),
                    'message' => trans('admin.deactive_error')
                ];

                return redirect()->route('admin.admins.index')->with($message);
            }
        }


        $admin->active ^= 1;
        $admin->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $admin->active ? trans('admin.active_success') : trans('admin.deactive_success'),
            'message' => $admin->active ? trans('admin.active_success') : trans('admin.deactive_success'),
        ];

        $admin->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل الأدمن '.$admin->name) : activity()->log('قام '.auth()->user()->name.' بالغاء تفعيل الأدمن '.$admin->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        Admin::withTrashed()->find($id)->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('admin.restored_success'),
            'message' => trans('admin.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة الأدمن '.Admin::find($id)->name);

        return redirect()->back()->with($message);
    }

}
