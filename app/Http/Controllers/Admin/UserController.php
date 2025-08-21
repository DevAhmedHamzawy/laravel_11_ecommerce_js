<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Upload\Upload;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add_user'])->only(['create', 'store']);
        $this->middleware(['permission:edit_user'])->only(['edit', 'update']);
        $this->middleware(['permission:view_user'])->only(['index']);
        $this->middleware(['permission:delete_user'])->only(['delete']);
        $this->middleware(['permission:active_user'])->only(['active']);
        $this->middleware(['permission:restore_user'])->only(['restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', ['users' => $users]);
    }

    public function trash()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.users.trash', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        if($request->has('main_image')) $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'users' , $request->name)]);
        if($request->has('password')) $request->merge(['password' => bcrypt($request->password)]);

        $user = User::create($request->except('main_image'));

        activity()->log('قام '.auth()->user()->name.'باضافة مستخدم جديد'.$user->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('user.add_success'),
            'message' => trans('user.add_success')
        ];

        return redirect()->route('admin.users.index')->with($message);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if($request->password != null){
            $request->merge(['password' => bcrypt($request->password)]);
        }else{
            $request->merge(['password' => $user->password]);
        }

        if ($request->has('main_image')) {

            Upload::deleteImage('users', $user->image);

            $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'users' , $request->name)]);
        }

        $user->update($request->except('main_image'));

        activity()->log('قام '.auth()->user()->name.'بتعديل مستخدم'.$user->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('user.updated_success'),
            'message' => trans('user.updated_success')
        ];

        return redirect()->route('admin.users.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        activity()->log('قام '.auth()->user()->name.'بحذف مستخدم'.$user->name);

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('user.deleted_success'),
            'message' => trans('user.deleted_success')
        ];

        return redirect()->route('admin.users.index')->with($message);
    }

   public function active(User $user)
    {
        $user->active ^= 1;
        $user->save();

        $message = [
            'alert-type' => 'success',
            'title' =>  $user->active ? trans('user.active_success') : trans('user.deactive_success'),
            'message' => $user->active ? trans('user.active_success') : trans('user.deactive_success'),
        ];

        $user->active ?  activity()->log('قام '.auth()->user()->name.'بتفعيل المستخدم '.$user->name) : activity()->log('قام '.auth()->user()->name.'بالغاء تفعيل المستخدم '.$user->name);

        return redirect()->back()->with($message);
    }

    public function restore($id)
    {
        User::withTrashed()->find($id)->restore();

        $message = [
            'alert-type' => 'success',
            'title' =>  trans('user.restored_success'),
            'message' => trans('user.restored_success')
        ];

        activity()->log('قام '.auth()->user()->name.'باستعادة المستخدم '.User::find($id)->name);

        return redirect()->back()->with($message);
    }

}
