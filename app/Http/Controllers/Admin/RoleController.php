<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_role'])->only(['create', 'store']);
        $this->middleware(['permission:edit_role'])->only(['edit', 'update']);
        $this->middleware(['permission:view_role'])->only(['index']);
        $this->middleware(['permission:delete_role'])->only(['delete']);
        $this->middleware(['permission:active_role'])->only(['active']);
        $this->middleware(['permission:restore_role'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roles.index', ['roles' => Role::all()]);
    }

    public function trash()
    {
        $roles = Role::onlyTrashed()->get();
        return view('admin.roles.trash', ['roles' => $roles]);
    }

    public function create()
    {
        $permissions = Permission::get()->groupby('group_name');

        return view('admin.roles.add', ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['name' => 'required|unique:roles,name', 'permissions' => 'required']);

        if($validator->fails()){
            $message = [
                'alert-type' => 'error',
                'title' =>  trans('roles.errors'),
                'message' => trans('roles.errors')
            ];

            return redirect()->back()->withErrors($validator)->with($message);
        }

        $role = Role::create($request->except('permissions'));

        if($request->has('permissions')){
            foreach($request->permissions as $permission){
                $role->givePermissionTo($permission);
            }
        }

        activity()->log('قام '.auth()->user()->name.'باضافة دور جديد '.$request->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('roles.add_success'),
            'message' => trans('roles.add_success')
        ];

        return redirect()->route('admin.roles.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get()->groupby('group_name');

        return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        $validator = Validator::make($request->all(), ['name' => 'required|unique:roles,name,'.$role->id.',id', 'permissions' => 'required']);

        if($validator->fails()){
            $message = [
                'alert-type' => 'error',
                'title' =>  trans('roles.errors'),
                'message' => trans('roles.errors')
            ];

            return redirect()->back()->withErrors($validator)->with($message);
        }

        $role->update($request->all());

        if($request->has('permissions')){
            $role->syncPermissions($request->permissions);
        }

        activity()->log('قام '.auth()->user()->name.'بالتعديل على الدور '.$role->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('roles.updated_success'),
            'message' => trans('roles.updated_success')
        ];

        return redirect()->route('admin.roles.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $checkRole = Role::withCount('users')->find($role->id);

        if(!$checkRole->users_count){
            $role->delete();

            activity()->log('قام '.auth()->user()->name.'بحذف الدور '.$role->name);

            $message = [
                'alert-type' => 'success',
                'title' =>  trans('roles.deleted_success'),
                'message' => trans('roles.deleted_success')
            ];

            return redirect()->back()->with($message);
        }else{
            $message = [
                'alert-type' => 'error',
                'title' =>  trans('roles.deleted_failed'),
                'message' => trans('roles.deleted_failed')
            ];

            return redirect()->back()->with($message);
        }
    }


    public function active(Role $role)
    {
        $role->active ^= 1;
        $role->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $role->active ? trans('roles.active_success') : trans('roles.deactive_success'),
            'message' => $role->active ? trans('roles.active_success') : trans('roles.deactive_success'),
        ];

        $role->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل الدور '.$role->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل الدور '.$role->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        $role = Role::withTrashed()->find($id)->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('roles.restored_success'),
            'message' => trans('roles.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة الدور '.Role::find($id)->name);

        return redirect()->back()->with($message);
    }

}
